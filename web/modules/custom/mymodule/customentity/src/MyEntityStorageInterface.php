<?php

namespace Drupal\customentity;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\customentity\Entity\MyEntityInterface;

/**
 * Defines the storage handler class for My entity entities.
 *
 * This extends the base storage class, adding required special handling for
 * My entity entities.
 *
 * @ingroup customentity
 */
interface MyEntityStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of My entity revision IDs for a specific My entity.
   *
   * @param \Drupal\customentity\Entity\MyEntityInterface $entity
   *   The My entity entity.
   *
   * @return int[]
   *   My entity revision IDs (in ascending order).
   */
  public function revisionIds(MyEntityInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as My entity author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   My entity revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\customentity\Entity\MyEntityInterface $entity
   *   The My entity entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(MyEntityInterface $entity);

  /**
   * Unsets the language for all My entity with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
