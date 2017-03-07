<?php

namespace Drupal\document_mgmt;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Folder entity.
 *
 * @see \Drupal\document_mgmt\Entity\Folder.
 */
class FolderAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\document_mgmt\Entity\FolderInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished folder entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published folder entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit folder entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete folder entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add folder entities');
  }

}
