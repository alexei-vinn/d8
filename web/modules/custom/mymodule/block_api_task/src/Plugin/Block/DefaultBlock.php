<?php

namespace Drupal\block_api_task\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'DefaultBlock' block.
 *
 * @Block(
 *  id = "default_block",
 *  admin_label = @Translation("Default block"),
 * )
 */
class DefaultBlock extends BlockBase implements ContainerFactoryPluginInterface
{

    protected $messenger;
    protected $MyEntityStorage;
    protected $NodeStorage;

    public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityStorageInterface $entity_storage, EntityStorageInterface $node_storage, MessengerInterface $messenger)
    {
        parent::__construct($configuration, $plugin_id, $plugin_definition);
        $this->messenger = $messenger;
        $this->MyEntityStorage = $entity_storage;
        $this->NodeStorage = $node_storage;
    }

    public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition)
    {
        $storage = $container->get('entity.manager');
        return new static(
            $configuration, $plugin_id, $plugin_definition,
            $storage->getStorage('my_entity'),
            $storage->getStorage('node'),
            $container->get('messenger')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $ids = $this->MyEntityStorage->getQuery()
            ->condition('status', 1)
            ->execute();

        $entities = $this->MyEntityStorage->loadMultiple($ids);
        $items = array();

        foreach ($entities as $entity) {
            /** @var \Drupal\customentity\Entity\MyEntityInterface $entity */

            if ($entity->hasField('article') && $entity->getFieldDefinition('article')->getType() == 'entity_reference') {
                if (!empty($entity->get('article')->getValue()[0]['target_id']) && $referenced = $this->NodeStorage->load($entity->get('article')->getValue()[0]['target_id'])) {

                    if ($referenced->hasField('field_color') && !empty($referenced->get('field_color')->getValue()[0]['value'])) {

                        $items['name'][] = $entity->get('name')->getValue()[0]['value'];
                        $items['colors'][] = $entity->get('article')->referencedEntities()[0]->get('field_color')->getValue()[0]['value'];
                    }

                }

            }

        }

        $build = [];
        $build['default_block'] = array(
            '#theme' => 'item_list',
            '#items' => $items['name']
        );
        $build['#attached']['library'][] = 'block_api_task/block_api_task.colored';
        $build['#attached']['drupalSettings']['block_api_task']['colors'] = $items['colors'];
        $build['#attached']['drupalSettings']['block_api_task']['classname'] = $this->getPluginId();
        $build['#attributes']['class'][] = $this->getPluginId();
        $build['#cache']['max-age'] = 0;
        return $build;
    }
}
