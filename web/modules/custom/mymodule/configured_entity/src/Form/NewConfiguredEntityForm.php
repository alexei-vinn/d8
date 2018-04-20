<?php

namespace Drupal\configured_entity\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class NewConfiguredEntityForm.
 */
class NewConfiguredEntityForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $new_configured_entity = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $new_configured_entity->label(),
      '#description' => $this->t("Label for the New configured entity."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $new_configured_entity->id(),
      '#machine_name' => [
        'exists' => '\Drupal\configured_entity\Entity\NewConfiguredEntity::load',
      ],
      '#disabled' => !$new_configured_entity->isNew(),
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $new_configured_entity = $this->entity;
    $status = $new_configured_entity->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label New configured entity.', [
          '%label' => $new_configured_entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label New configured entity.', [
          '%label' => $new_configured_entity->label(),
        ]));
    }
    $form_state->setRedirectUrl($new_configured_entity->toUrl('collection'));
  }

}
