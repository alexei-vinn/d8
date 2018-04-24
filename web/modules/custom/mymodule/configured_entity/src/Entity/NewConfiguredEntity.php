<?php

namespace Drupal\configured_entity\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;


/**
 * Defines the New configured entity entity.
 *
 * @ConfigEntityType(
 *   id = "new_configured_entity",
 *   label = @Translation("New configured entity"),
 *   handlers = {
 *     "access" = "Drupal\configured_entity\NewConfiguredEntityAccessController",
 *     "list_builder" = "Drupal\configured_entity\NewConfiguredEntityListBuilder",
 *     "form" = {
 *       "add" = "Drupal\configured_entity\Form\NewConfiguredEntityAddForm",
 *       "edit" = "Drupal\configured_entity\Form\NewConfiguredEntityEditForm",
 *       "delete" = "Drupal\configured_entity\Form\NewConfiguredEntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\configured_entity\NewConfiguredEntityHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "new_configured_entity",
 *   admin_permission = "administer new_configured_entity",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/configured_entity/{new_configured_entity}",
 *     "add-form" = "/configured_entity/add",
 *     "edit-form" = "/configured_entity/{new_configured_entity}/edit",
 *     "delete-form" = "/configured_entity/{new_configured_entity}/delete",
 *     "collection" = "/configured_entity"
 *
 *   }
 * )
 */
class NewConfiguredEntity extends ConfigEntityBase implements NewConfiguredEntityInterface {

  /**
   * The New configured entity ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The New configured entity label.
   *
   * @var string
   */
  protected $label;

}
