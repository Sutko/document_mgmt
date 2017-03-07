<?php

namespace Drupal\document_mgmt\Entity;

use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\RevisionableInterface;
use Drupal\Component\Utility\Xss;
use Drupal\Core\Url;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Document entities.
 *
 * @ingroup document_mgmt
 */
interface DocumentInterface extends RevisionableInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Document name.
   *
   * @return string
   *   Name of the Document.
   */
  public function getName();

  /**
   * Sets the Document name.
   *
   * @param string $name
   *   The Document name.
   *
   * @return \Drupal\document_mgmt\Entity\DocumentInterface
   *   The called Document entity.
   */
  public function setName($name);

  /**
   * Gets the Document creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Document.
   */
  public function getCreatedTime();

  /**
   * Sets the Document creation timestamp.
   *
   * @param int $timestamp
   *   The Document creation timestamp.
   *
   * @return \Drupal\document_mgmt\Entity\DocumentInterface
   *   The called Document entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Document published status indicator.
   *
   * Unpublished Document are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Document is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Document.
   *
   * @param bool $published
   *   TRUE to set this Document to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\document_mgmt\Entity\DocumentInterface
   *   The called Document entity.
   */
  public function setPublished($published);

  /**
   * Gets the Document revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Document revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\document_mgmt\Entity\DocumentInterface
   *   The called Document entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Document revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Document revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\document_mgmt\Entity\DocumentInterface
   *   The called Document entity.
   */
  public function setRevisionUserId($uid);

}
