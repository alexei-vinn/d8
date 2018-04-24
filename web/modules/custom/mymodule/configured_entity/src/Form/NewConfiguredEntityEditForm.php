<?php

namespace Drupal\configured_entity\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class NewConfiguredEntityEditForm.
 */
class NewConfiguredEntityEditForm extends EntityForm
{

    public function form(array $form, FormStateInterface $form_state) {
        $form = parent::form($form, $form_state);

        $entity = $this->entity;
        $form['label'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Label'),
            '#maxlength' => 255,
            '#default_value' => $entity->label(),
            '#description' => $this->t("Label for the Default entity."),
            '#required' => TRUE,
        ];

        $form['id'] = [
            '#type' => 'machine_name',
            '#default_value' => $entity->get('id'),
            '#machine_name' => [
                'exists' => '\Drupal\generated\Entity\DefaultEntity::load',
            ],
            '#disabled' => TRUE,
        ];

        $form['title'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Title'),
            '#maxlength' => 255,
            '#default_value' => $entity->get('title'),
            '#description' => $this->t("Title for the New configured entity."),
            '#required' => TRUE,
        ];

        $form['description'] = [
            '#type' => 'textarea',
            '#title' => $this->t('Description'),
            '#description' => $this->t("Description the new configured entity."),
            '#default_value' => $entity->get('description'),
            '#required' => TRUE,
        ];

        return $form;
    }


    protected function actions(array $form, FormStateInterface $form_state)
    {
        $actions = parent::actions($form, $form_state);
        $actions['submit']['#value'] = $this->t('Edit');
        return $actions;
    }

}
