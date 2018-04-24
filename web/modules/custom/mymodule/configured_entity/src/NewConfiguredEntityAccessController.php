<?php

namespace Drupal\configured_entity;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

class NewConfiguredEntityAccessController extends EntityAccessControlHandler
{

    public function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
        // The $opereration parameter tells you what sort of operation access is
        // being checked for.
        if ($operation == 'view') {
            return TRUE;
        }
        // Other than the view operation, we're going to be insanely lax about
        // access. Don't try this at home!
        return parent::checkAccess($entity, $operation, $account);
    }

    /**
     * {@inheritdoc}
     */
/*    protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
        switch ($operation) {
            case 'view':
                return AccessResult::allowedIfHasPermission($account, 'view new configured entity');

            case 'update':
                return AccessResult::allowedIfHasPermission($account, 'update new configured entity');

            case 'delete':
                return AccessResult::allowedIfHasPermission($account, 'delete new configured entity');
        }

        return AccessResult::neutral();
    }*/

    /**
     * {@inheritdoc}
     */
/*    protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
        return AccessResult::allowedIfHasPermission($account, 'add new configured entity');
    }*/

}
