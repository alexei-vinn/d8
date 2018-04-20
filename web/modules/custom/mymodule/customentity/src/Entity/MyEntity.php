<?php

namespace Drupal\customentity\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\RevisionableContentEntityBase;
use Drupal\Core\Entity\RevisionableInterface;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the My entity entity.
 *
 * @ingroup customentity
 *
 * @ContentEntityType(
 *   id = "my_entity",
 *   label = @Translation("My entity"),
 *   handlers = {
 *     "storage" = "Drupal\customentity\MyEntityStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\customentity\MyEntityListBuilder",
 *     "views_data" = "Drupal\customentity\Entity\MyEntityViewsData",
 *     "translation" = "Drupal\customentity\MyEntityTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\customentity\Form\MyEntityForm",
 *       "add" = "Drupal\customentity\Form\MyEntityForm",
 *       "edit" = "Drupal\customentity\Form\MyEntityForm",
 *       "delete" = "Drupal\customentity\Form\MyEntityDeleteForm",
 *     },
 *     "access" = "Drupal\customentity\MyEntityAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\customentity\MyEntityHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "my_entity",
 *   data_table = "my_entity_field_data",
 *   revision_table = "my_entity_revision",
 *   revision_data_table = "my_entity_field_revision",
 *   translatable = TRUE,
 *   admin_permission = "administer my entity entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "revision" = "vid",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/my_entity/{my_entity}",
 *     "add-form" = "/admin/structure/my_entity/add",
 *     "edit-form" = "/admin/structure/my_entity/{my_entity}/edit",
 *     "delete-form" = "/admin/structure/my_entity/{my_entity}/delete",
 *     "version-history" = "/admin/structure/my_entity/{my_entity}/revisions",
 *     "revision" = "/admin/structure/my_entity/{my_entity}/revisions/{my_entity_revision}/view",
 *     "revision_revert" = "/admin/structure/my_entity/{my_entity}/revisions/{my_entity_revision}/revert",
 *     "revision_delete" = "/admin/structure/my_entity/{my_entity}/revisions/{my_entity_revision}/delete",
 *     "translation_revert" = "/admin/structure/my_entity/{my_entity}/revisions/{my_entity_revision}/revert/{langcode}",
 *     "collection" = "/admin/structure/my_entity",
 *   },
 *   field_ui_base_route = "my_entity.settings"
 * )
 */
class MyEntity extends RevisionableContentEntityBase implements MyEntityInterface
{

    use EntityChangedTrait;

    /**
     * {@inheritdoc}
     */
    public static function preCreate(EntityStorageInterface $storage_controller, array &$values)
    {
        parent::preCreate($storage_controller, $values);
        $values += [
            'user_id' => \Drupal::currentUser()->id(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function baseFieldDefinitions(EntityTypeInterface $entity_type)
    {
        $fields = parent::baseFieldDefinitions($entity_type);

        // Authored by

        $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
            ->setLabel(t('Authored by'))
            ->setDescription(t('The user ID of author of the My entity entity.'))
            ->setRevisionable(TRUE)
            ->setSetting('target_type', 'user')
            ->setSetting('handler', 'default')
            ->setTranslatable(TRUE)
            ->setDisplayOptions('view', [
                'label' => 'hidden',
                'type' => 'author',
                'weight' => 0,
            ])
            ->setDisplayOptions('form', [
                'type' => 'entity_reference_autocomplete',
                'weight' => 5,
                'settings' => [
                    'match_operator' => 'CONTAINS',
                    'size' => '60',
                    'autocomplete_type' => 'tags',
                    'placeholder' => '',
                ],
            ])
            ->setDisplayConfigurable('form', TRUE)
            ->setDisplayConfigurable('view', TRUE);

        // Name
        $fields['name'] = BaseFieldDefinition::create('string')
            ->setLabel(t('Name'))
            ->setDescription(t('The name of the My entity entity.'))
            ->setRevisionable(TRUE)
            ->setSettings([
                'max_length' => 50,
                'text_processing' => 0,
            ])
            ->setDefaultValue('')
            ->setDisplayOptions('view', [
                'label' => 'above',
                'type' => 'string',
                'weight' => -4,
            ])
            ->setDisplayOptions('form', [
                'type' => 'string_textfield',
                'weight' => -4,
            ])
            ->setDisplayConfigurable('form', TRUE)
            ->setDisplayConfigurable('view', TRUE)
            ->setRequired(TRUE)
            ->setTranslatable(TRUE);

        // Publishing status
        $fields['status'] = BaseFieldDefinition::create('boolean')
            ->setLabel(t('Publishing status'))
            ->setDescription(t('A boolean indicating whether the My entity is published.'))
            ->setRevisionable(TRUE)
            ->setDefaultValue(TRUE)
            ->setDisplayOptions('form', [
                'type' => 'boolean_checkbox',
                'weight' => -3,
            ]);

        $fields['created'] = BaseFieldDefinition::create('created')
            ->setLabel(t('Created'))
            ->setDescription(t('The time that the entity was created.'));

        $fields['changed'] = BaseFieldDefinition::create('changed')
            ->setLabel(t('Changed'))
            ->setDescription(t('The time that the entity was last edited.'));

        // create custom property definition
        $fields['article'] = BaseFieldDefinition::create('entity_reference')
            ->setLabel(t('Article'))
            ->setDescription(t('Title of the referenced article.'))
            ->setSettings(array(
                'target_type' => 'node',
                'default_value' => 0,
            ))
            ->setDisplayOptions('form', [
                'type' => 'entity_reference_autocomplete',
                'weight' => 5,
                'settings' => [
                    'match_operator' => 'CONTAINS',
                    'size' => '60',
                    'autocomplete_type' => 'article',
                    'placeholder' => '',
                ],
            ])
            ->setDisplayConfigurable('form', TRUE)
            ->setDisplayConfigurable('view', TRUE)
            ->setRevisionable(TRUE)
            ->setTranslatable(TRUE);

        return $fields;
    }

    /**
     * {@inheritdoc}
     */
    public function preSave(EntityStorageInterface $storage)
    {
        parent::preSave($storage);

        foreach (array_keys($this->getTranslationLanguages()) as $langcode) {
            $translation = $this->getTranslation($langcode);

            // If no owner has been set explicitly, make the anonymous user the owner.
            if (!$translation->getOwner()) {
                $translation->setOwnerId(0);
            }
        }

        // If no revision author has been set explicitly, make the my_entity owner the
        // revision author.
        if (!$this->getRevisionUser()) {
            $this->setRevisionUserId($this->getOwnerId());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getOwner()
    {
        return $this->get('user_id')->entity;
    }

    /**
     * {@inheritdoc}
     */
    public function setOwnerId($uid)
    {
        $this->set('user_id', $uid);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getOwnerId()
    {
        return $this->get('user_id')->target_id;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->get('name')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->set('name', $name);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedTime()
    {
        return $this->get('created')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setCreatedTime($timestamp)
    {
        $this->set('created', $timestamp);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setOwner(UserInterface $account)
    {
        $this->set('user_id', $account->id());
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isPublished()
    {
        return (bool)$this->getEntityKey('status');
    }

    /**
     * {@inheritdoc}
     */
    public function setPublished($published)
    {
        $this->set('status', $published ? TRUE : FALSE);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function urlRouteParameters($rel)
    {
        $uri_route_parameters = parent::urlRouteParameters($rel);

        if ($rel === 'revision_revert' && $this instanceof RevisionableInterface) {
            $uri_route_parameters[$this->getEntityTypeId() . '_revision'] = $this->getRevisionId();
        } elseif ($rel === 'revision_delete' && $this instanceof RevisionableInterface) {
            $uri_route_parameters[$this->getEntityTypeId() . '_revision'] = $this->getRevisionId();
        }

        return $uri_route_parameters;
    }

    public  function  uriRelationships() {

    }

}
