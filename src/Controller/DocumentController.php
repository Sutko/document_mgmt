<?php

namespace Drupal\document_mgmt\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\document_mgmt\Entity\DocumentInterface;

/**
 * Class DocumentController.
 *
 *  Returns responses for Document routes.
 *
 * @package Drupal\document_mgmt\Controller
 */
class DocumentController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Displays a Document  revision.
   *
   * @param int $document_revision
   *   The Document  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($document_revision) {
    $document = $this->entityManager()->getStorage('document')->loadRevision($document_revision);
    $view_builder = $this->entityManager()->getViewBuilder('document');

    return $view_builder->view($document);
  }

  /**
   * Page title callback for a Document  revision.
   *
   * @param int $document_revision
   *   The Document  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($document_revision) {
    $document = $this->entityManager()->getStorage('document')->loadRevision($document_revision);
    return $this->t('Revision of %title from %date', array('%title' => $document->label(), '%date' => format_date($document->getRevisionCreationTime())));
  }

  /**
   * Generates an overview table of older revisions of a Document .
   *
   * @param \Drupal\document_mgmt\Entity\DocumentInterface $document
   *   A Document  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(DocumentInterface $document) {
    $account = $this->currentUser();
    $langcode = $document->language()->getId();
    $langname = $document->language()->getName();
    $languages = $document->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $document_storage = $this->entityManager()->getStorage('document');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $document->label()]) : $this->t('Revisions for %title', ['%title' => $document->label()]);
    $header = array($this->t('Revision'), $this->t('Operations'));

    $revert_permission = (($account->hasPermission("revert all document revisions") || $account->hasPermission('administer document entities')));
    $delete_permission = (($account->hasPermission("delete all document revisions") || $account->hasPermission('administer document entities')));

    $rows = array();

    $vids = $document_storage->revisionIds($document);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\document_mgmt\DocumentInterface $revision */
      $revision = $document_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')->format($revision->revision_timestamp->value, 'short');
        if ($vid != $document->getRevisionId()) {
          $link = $this->l($date, new Url('entity.document.revision', ['document' => $document->id(), 'document_revision' => $vid]));
        }
        else {
          $link = $document->link($date);
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => \Drupal::service('renderer')->renderPlain($username),
              'message' => ['#markup' => $revision->revision_log_message->value, '#allowed_tags' => Xss::getHtmlTagList()],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => Url::fromRoute('document.revision_revert_confirm', ['document' => $document->id(), 'document_revision' => $vid]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('document.revision_delete_confirm', ['document' => $document->id(), 'document_revision' => $vid]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['document_revisions_table'] = array(
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    );

    return $build;
  }

}
