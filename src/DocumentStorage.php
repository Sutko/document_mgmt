<?php

namespace Drupal\document_mgmt;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\document_mgmt\Entity\DocumentInterface;

/**
 * Defines the storage handler class for Document entities.
 *
 * This extends the base storage class, adding required special handling for
 * Document entities.
 *
 * @ingroup document_mgmt
 */
class DocumentStorage extends SqlContentEntityStorage implements DocumentStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(DocumentInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {document_revision} WHERE id=:id ORDER BY vid',
      array(':id' => $entity->id())
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {document_field_revision} WHERE uid = :uid ORDER BY vid',
      array(':uid' => $account->id())
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(DocumentInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {document_field_revision} WHERE id = :id AND default_langcode = 1', array(':id' => $entity->id()))
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('document_revision')
      ->fields(array('langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED))
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
