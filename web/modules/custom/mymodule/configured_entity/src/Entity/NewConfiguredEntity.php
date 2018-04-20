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
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\configured_entity\NewConfiguredEntityListBuilder",
 *     "form" = {
 *       "add" = "Drupal\configured_entity\Form\NewConfiguredEntityForm",
 *       "edit" = "Drupal\configured_entity\Form\NewConfiguredEntityForm",
 *       "delete" = "Drupal\configured_entity\Form\NewConfiguredEntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\configured_entity\NewConfiguredEntityHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "new_configured_entity",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/new_configured_entity/{new_configured_entity}",
 *     "add-form" = "/admin/structure/new_configured_entity/add",
 *     "edit-form" = "/admin/structure/new_configured_entity/{new_configured_entity}/edit",
 *     "delete-form" = "/admin/structure/new_configured_entity/{new_configured_entity}/delete",
 *     "collection" = "/admin/structure/new_configured_entity"
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
