<?php

namespace Drupal\document_mgmt\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Folder entities.
 *
 * @ingroup document_mgmt
 */
interface FolderInterface extends  ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Folder name.
   *
   * @return string
   *   Name of the Folder.
   */
  public function getName();

  /**
   * Sets the Folder name.
   *
   * @param string $name
   *   The Folder name.
   *
   * @return \Drupal\document_mgmt\Entity\FolderInterface
   *   The called Folder entity.
   */
  public function setName($name);

  /**
   * Gets the Folder creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Folder.
   */
  public function getCreatedTime();

  /**
   * Sets the Folder creation timestamp.
   *
   * @param int $timestamp
   *   The Folder creation timestamp.
   *
   * @return \Drupal\document_mgmt\Entity\FolderInterface
   *   The called Folder entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Folder published status indicator.
   *
   * Unpublished Folder are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Folder is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Folder.
   *
   * @param bool $published
   *   TRUE to set this Folder to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\document_mgmt\Entity\FolderInterface
   *   The called Folder entity.
   */
  public function setPublished($published);

}
