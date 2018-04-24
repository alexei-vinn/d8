<?php

namespace Drupal\configured_entity\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Class NewConfiguredEntityAddForm.
 */
class NewConfiguredEntityAddForm extends EntityForm
{

    protected $messenger;

    public function __construct(MessengerInterface $messenger)
    {
        $this->messenger = $messenger;
    }

    public static function create(ContainerInterface $container)
    {
        return new static(
            $container->get('messenger')
        );
    }


    public function form(array $form, FormStateInterface $form_state)
    {

        $this->messenger->addMessage('Yahoo');

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
            '#default_value' => $entity->id(),
            '#machine_name' => [
                'exists' => '\Drupal\configured_entity\Entity\NewConfiguredEntity::load',
            ],
            '#disabled' => !$entity->isNew(),
        ];

        $form['title'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Title'),
            '#maxlength' => 255,
            '#default_value' => '',
            '#description' => $this->t("Title for the New configured entity."),
            '#required' => TRUE,
        ];

        $form['description'] = [
            '#type' => 'textarea',
            '#title' => $this->t('Description'),
            '#description' => $this->t("Description the new configured entity."),
            '#default_value' => '',
            '#required' => TRUE,
        ];

        return $form;
    }

    public function save(array $form, FormStateInterface $form_state)
    {

        $status = $this->entity->save();

        if ($status === SAVED_NEW) {
            $this->messenger->addMessage($this->t('Created the %label New Configured Entity', [
                '%label' => $this->entity->label(),
            ]));
            $form_state->setRedirectUrl($this->entity->toUrl('collection'));
        } else {
            $this->messenger->addMessage($this->t('Something went wrong,  with status code @status', ['@status' => $status]), TYPE_WARNING);
        }


    }

    protected function actions(array $form, FormStateInterface $form_state)
    {
        $actions = parent::actions($form, $form_state);
        $actions['submit']['#value'] = $this->t('Add');
        return $actions;
    }
}
