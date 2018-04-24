<?php

namespace Drupal\customentity;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of My entity entities.
 *
 * @ingroup customentity
 */
class MyEntityListBuilder extends EntityListBuilder
{


    /**
     * {@inheritdoc}
     */
    public function buildHeader()
    {

        $header['id'] = $this->t('My entity ID');
        $header['name'] = $this->t('Name');
        $header['language'] = $this->t('Language');

        return $header + parent::buildHeader();
    }

    /**
     * {@inheritdoc}
     */
    public function buildRow(EntityInterface $entity)
    {
        /* @var $entity \Drupal\customentity\Entity\MyEntity */

        $row['id'] = $entity->id();
        $row['name'] = Link::createFromRoute(
            $entity->label(),
            'entity.my_entity.edit_form',
            ['my_entity' => $entity->id()]
        );
        $row['language'] = $entity->language()->getName();
        return $row + parent::buildRow($entity);
    }

    protected function getEntityIds() {

        $query = $this->getStorage()->getQuery()
            ->sort($this->entityType->getKey('id'));

        $langcode = \Drupal::languageManager()->getCurrentLanguage()->getId();
        $query->condition('langcode', $langcode);


        // Only add the pager if a limit is specified.
        if ($this->limit) {
            $query->pager($this->limit);
        }
        return $query->execute();
    }

}
