<?php

namespace Drupal\customentity\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\customentity\Entity\MyEntityInterface;

/**
 * Class MyEntityController.
 *
 *  Returns responses for My entity routes.
 */
class MyEntityController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Displays a My entity  revision.
   *
   * @param int $my_entity_revision
   *   The My entity  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($my_entity_revision) {
    $my_entity = $this->entityManager()->getStorage('my_entity')->loadRevision($my_entity_revision);
    $view_builder = $this->entityManager()->getViewBuilder('my_entity');

    return $view_builder->view($my_entity);
  }

  /**
   * Page title callback for a My entity  revision.
   *
   * @param int $my_entity_revision
   *   The My entity  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($my_entity_revision) {
    $my_entity = $this->entityManager()->getStorage('my_entity')->loadRevision($my_entity_revision);
    return $this->t('Revision of %title from %date', ['%title' => $my_entity->label(), '%date' => format_date($my_entity->getRevisionCreationTime())]);
  }

  /**
   * Generates an overview table of older revisions of a My entity .
   *
   * @param \Drupal\customentity\Entity\MyEntityInterface $my_entity
   *   A My entity  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(MyEntityInterface $my_entity) {
    $account = $this->currentUser();
    $langcode = $my_entity->language()->getId();
    $langname = $my_entity->language()->getName();
    $languages = $my_entity->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $my_entity_storage = $this->entityManager()->getStorage('my_entity');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $my_entity->label()]) : $this->t('Revisions for %title', ['%title' => $my_entity->label()]);
    $header = [$this->t('Revision'), $this->t('Operations')];

    $revert_permission = (($account->hasPermission("revert all my entity revisions") || $account->hasPermission('administer my entity entities')));
    $delete_permission = (($account->hasPermission("delete all my entity revisions") || $account->hasPermission('administer my entity entities')));

    $rows = [];

    $vids = $my_entity_storage->revisionIds($my_entity);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\customentity\MyEntityInterface $revision */
      $revision = $my_entity_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $my_entity->getRevisionId()) {
          $link = $this->l($date, new Url('entity.my_entity.revision', ['my_entity' => $my_entity->id(), 'my_entity_revision' => $vid]));
        }
        else {
          $link = $my_entity->link($date);
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => \Drupal::service('renderer')->renderPlain($username),
              'message' => ['#markup' => $revision->getRevisionLogMessage(), '#allowed_tags' => Xss::getHtmlTagList()],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => $has_translations ?
              Url::fromRoute('entity.my_entity.translation_revert', ['my_entity' => $my_entity->id(), 'my_entity_revision' => $vid, 'langcode' => $langcode]) :
              Url::fromRoute('entity.my_entity.revision_revert', ['my_entity' => $my_entity->id(), 'my_entity_revision' => $vid]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.my_entity.revision_delete', ['my_entity' => $my_entity->id(), 'my_entity_revision' => $vid]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['my_entity_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
