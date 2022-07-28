<?php

namespace Drupal\gavias_sliderlayer\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\Core\StringTranslation\StringTranslationTrait
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Group Slider Controller.
 */
class GroupSliderController extends ControllerBase {
  use StringTranslationTrait;

  /**
   * Gavias sl group list.
   */
  public function gaviasSlGroupList() {

    if (!\Drupal::database()->schema()->tableExists('gavias_sliderlayergroups')) {
      return "";
    }

    $header = ['ID', 'Name', 'Action'];

    $results = \Drupal::database()->select('{gavias_sliderlayergroups}', 'd')
      ->fields('d', ['id', 'title'])
      ->execute();
    $rows = [];

    foreach ($results as $row) {

      $tmp = [];
      $tmp[] = $row->id;
      $tmp[] = $row->title;
      $tmp[] = $this->t('<a href="@link_1">Edit Name</a> | <a href="@link_2">List Silders</a> | <a href="@link_3">Config General</a> | <a href="@link_5">Clone</a> | <a href="@link_6">Export</a> | <a href="@link_7">Import</a> | <a href="@link_4">Delete</a>', [
        '@link_1' => Url::fromRoute(
          'gavias_sl_group.admin.add', ['sid' => $row->id]
        )->toString(),
        '@link_2' => Url::fromRoute(
          'gavias_sl_sliders.admin.list', [
            'gid' => $row->id,
          ]
        )->toString(),
        '@link_3' => Url::fromRoute(
          'gavias_sl_group.admin.config', [
            'gid' => $row->id,
          ]
        )->toString(),
        '@link_5' => Url::fromRoute(
          'gavias_sl_group.admin.clone', [
            'sid' => $row->id,
          ]
        )->toString(),
        '@link_6' => Url::fromRoute(
          'gavias_sl_group.admin.export', [
            'gid' => $row->id,
          ]
        )->toString(),
        '@link_7' => Url::fromRoute(
          'gavias_sl_group.admin.import', [
            'gid' => $row->id,
          ]
        )->toString(),
        '@link_4' => Url::fromRoute(
          'gavias_sl_group.admin.delete', [
            'gid' => $row->id,
            'sid' => '0',
            'action' => 'group',
          ],
        )->toString(),
      ]);
      $rows[] = $tmp;
    }
    return [
      '#theme' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#empty' => $this->t('No Slider available. <a href="@link">Add Slider</a>.', ['@link' => Url::fromRoute('gavias_sl_group.admin.add', ['sid' => 0])->toString()]),
    ];
  }

  /**
   * Gavias sl group config.
   */
  public function gaviasSlGroupConfig($gid) {
    global $base_url;
    $page['#attached']['library'][] = 'gavias_sliderlayer/gavias_sliderlayer.assets.config_global';
    $slideshow = get_slider_group($gid);
    $settings = ((isset($slideshow->params) && $slideshow->params) ? json_decode(base64_decode($slideshow->params)) : '{}');

    $save_url = Url::fromRoute('gavias_sl_group.admin.config_save', [], ['absolute' => FALSE])->toString();
    $page['#attached']['drupalSettings']['gavias_sliderlayer']['base_url'] = $base_url;
    $page['#attached']['drupalSettings']['gavias_sliderlayer']['save_url'] = $save_url;
    $page['#attached']['drupalSettings']['gavias_sliderlayer']['settings'] = $settings;

    ob_start();
    include GAV_SLIDERLAYER_PATH . '/templates/backend/global.php';
    $content = ob_get_clean();
    $page['admin-global'] = [
      '#theme' => 'admin-global',
      '#content' => $content,
    ];
    return $page;
  }

  /**
   * Gavias sl group config save.
   */
  public function gaviasSlGroupConfigSave() {
    header('Content-type: application/json');
    $gid = requestStack->getCurrentRequest()->query->get('gid');
    $settings = requestStack->getCurrentRequest()->request->get('settings')];

    \Drupal::database()->update("gavias_sliderlayergroups")->fields([
      'params'  => $settings,
    ])->condition('id', $gid, '=')->execute();
    $result = [
      'data' => 'update saved',
    ];

    // Clear all cache.
    \Drupal::service('plugin.manager.block')->clearCachedDefinitions();
    $module_handler = \Drupal::moduleHandler();
    $module_handler->invokeAll('rebuild');

    \Drupal::messenger()->addMessage("Group Slider has been updated");
    print json_encode($result);
    exit(0);
  }

  /**
   * Gavias sl group export.
   */
  public function gaviasSlGroupExport($gid) {
    $data = gavias_sliderlayer_export($gid);
    // print"<pre>"; print_r(json_decode(base64_decode($data)));die();
    $title = 'sliderlayer_' . date('Y_m_d_h_i_s');
    header("Content-Type: text/txt");
    header("Content-Disposition: attachment; filename={$title}.txt");
    // header("Content-Length: " . strlen($data));
    print $data;
    exit;
  }

}
