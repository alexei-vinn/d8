<?php

namespace Drupal\customentity\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining My entity entities.
 *
 * @ingroup customentity
 */
interface MyEntityInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the My entity name.
   *
   * @return string
   *   Name of the My entity.
   */
  public function getName();

  /**
   * Sets the My entity name.
   *
   * @param string $name
   *   The My entity name.
   *
   * @return \Drupal\customentity\Entity\MyEntityInterface
   *   The called My entity entity.
   */
  public function setName($name);

  /**
   * Gets the My entity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the My entity.
   */
  public function getCreatedTime();

  /**
   * Sets the My entity creation timestamp.
   *
   * @param int $timestamp
   *   The My entity creation timestamp.
   *
   * @return \Drupal\customentity\Entity\MyEntityInterface
   *   The called My entity entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the My entity published status indicator.
   *
   * Unpublished My entity are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the My entity is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a My entity.
   *
   * @param bool $published
   *   TRUE to set this My entity to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\customentity\Entity\MyEntityInterface
   *   The called My entity entity.
   */
  public function setPublished($published);

  /**
   * Gets the My entity revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the My entity revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\customentity\Entity\MyEntityInterface
   *   The called My entity entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the My entity revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the My entity revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\customentity\Entity\MyEntityInterface
   *   The called My entity entity.
   */
  public function setRevisionUserId($uid);

}
