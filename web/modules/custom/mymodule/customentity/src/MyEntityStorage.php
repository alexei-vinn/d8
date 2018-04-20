<?php

namespace Drupal\customentity;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Language\LanguageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\customentity\Entity\MyEntityInterface;

/**
 * Defines the storage handler class for My entity entities.
 *
 * This extends the base storage class, adding required special handling for
 * My entity entities.
 *
 * @ingroup customentity
 */
class MyEntityStorage extends SqlContentEntityStorage implements MyEntityStorageInterface
{
    /**
     * {@inheritdoc}
     */
    public function revisionIds(MyEntityInterface $entity)
    {

        return $this->database->query(
            'SELECT vid FROM {my_entity_revision} WHERE id=:id ORDER BY vid',
            [':id' => $entity->id()]
        )->fetchCol();
    }

    /**
     * {@inheritdoc}
     */
    public function userRevisionIds(AccountInterface $account)
    {
        return $this->database->query(
            'SELECT vid FROM {my_entity_field_revision} WHERE uid = :uid ORDER BY vid',
            [':uid' => $account->id()]
        )->fetchCol();
    }

    /**
     * {@inheritdoc}
     */
    public function countDefaultLanguageRevisions(MyEntityInterface $entity)
    {
        return $this->database->query('SELECT COUNT(*) FROM {my_entity_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
            ->fetchField();
    }

    /**
     * {@inheritdoc}
     */
    public function clearRevisionsLanguage(LanguageInterface $language)
    {
        return $this->database->update('my_entity_revision')
            ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
            ->condition('langcode', $language->getId())
            ->execute();
    }

    protected function doLoadMultiple(array $ids = NULL)
    {
        $ids = $this->database->query(
            'SELECT id FROM {my_entity_revision} WHERE langcode=:langcode GROUP BY id ORDER BY id',
            [':langcode' => \Drupal::languageManager()->getCurrentLanguage()->getId()]
        )->fetchCol();

        return $this->getFromStorage($ids);

    }

}
