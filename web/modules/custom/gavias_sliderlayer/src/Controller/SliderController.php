<?php

namespace Drupal\gavias_sliderlayer\Controller;

use Drupal\Core\Url;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Cache\Cache;

/**
 * Slider Controller.
 */
class SliderController extends ControllerBase {
  use StringTranslationTrait;

  /**
   * Gavias sl sliders list.
   */
  public function gaviasSlSlidersList($gid) {

    if (!\Drupal::database()->schema()->tableExists('gavias_sliderlayers')) {
      return "";
    }

    $header = ['ID', 'Name', 'Action'];

    $results = \Drupal::database()->select('{gavias_sliderlayers}', 'd')
      ->fields('d', ['id', 'title'])
      ->condition('group_id', $gid, '=')
      ->execute();
    $rows = [];

    foreach ($results as $row) {

      $tmp = [];
      $tmp[] = $row->id;
      $tmp[] = $row->title;
      $tmp[] = $this->t('<a href="@link_1">Edit Silder</a> | <a href="@link_2">Duplicate</a> | <a href="@link_3">Delete</a>', [
        '@link_1' => Url::fromRoute(
          'gavias_sl_sliders.admin.form', [
            'sid' => $row->id,
            'gid' => $gid,
          ]
        )->toString(),
        '@link_2' => Url::fromRoute(
          'gavias_sl_sliders.admin.duplicate', [
            'id' => $row->id,
          ]
        )->toString(),
        '@link_3' => Url::fromRoute(
          'gavias_sl_group.admin.delete', [
            'sid' => $row->id,
            'gid' => $gid,
            'action' => 'slider',
          ]
        )->toString(),
      ]);
      $rows[] = $tmp;
    }
    return [
      '#theme' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#empty' => $this->t(
        'No Slider available. <a href="@link">Add Slider</a>.',
        [
          '@link' => Url::fromRoute('gavias_sl_sliders.admin.form',
        ['sid' => 0, 'gid' => $gid])->toString(),
        ]
      ),
    ];
  }

  /**
   * Gavias sl sliders edit.
   */
  public function gaviasSlSlidersEdit($gid = 0, $sid = 0) {

    global $base_url;
    $page['#attached']['library'][] = 'gavias_sliderlayer/gavias_sliderlayer.assets.admin';
    $theme_name = \Drupal::config('system.theme')->get('default');

    $group = get_slider_group($gid);
    // $group_settings = (isset($group->params)
    // && $group->params) ? base64_decode($group->params) : 'null';
    $group_settings_decode = (isset($group->params) && $group->params) ? json_decode(base64_decode($group->params)) : 'null';

    $sliderlayers = gavias_sliderlayer_load($sid);

    $layers = (isset($sliderlayers->layers) && $sliderlayers->layers) ? ($sliderlayers->layers) : 'null';
    // print"<pre>";print_r($layers); die();
    $settings = (isset($sliderlayers->settings) &&
    $sliderlayers->settings) ?
    ($sliderlayers->settings) : 'null';

    $abs_url_save = Url::fromRoute('gavias_sl_sliders.admin.save', [], ['absolute' => FALSE])->toString();
    $abs_url_edit = Url::fromRoute(
      'gavias_sl_sliders.admin.form', [
        'gid' => $gid,
        'sid' => $sid,
      ],
        ['absolute' => FALSE,
        ])->toString();

    $abs_url_config = Url::fromRoute('gavias_sliderlayer.admin.getImagesUpload', [], ['absolute' => FALSE])->toString();
    $page['#attached']['drupalSettings']['gavias_sliderlayer']['get_images_upload_url'] = $abs_url_config;
    $page['#attached']['drupalSettings']['gavias_sliderlayer']['base_path'] = base_path();
    $page['#attached']['drupalSettings']['gavias_sliderlayer']['base_url'] = $base_url;
    $page['#attached']['drupalSettings']['gavias_sliderlayer']['save_url'] = $abs_url_save;
    $page['#attached']['drupalSettings']['gavias_sliderlayer']['edit_url'] = $abs_url_edit;
    $page['#attached']['drupalSettings']['gavias_sliderlayer']['group_settings'] = $group_settings_decode;
    $page['#attached']['drupalSettings']['gavias_sliderlayer']['layers_settings'] = $layers;
    $page['#attached']['drupalSettings']['gavias_sliderlayer']['settings'] = $settings;
    $deslider = [
      'title' => 'Slider',
      'status' => '1',
      'sort_index' => 1,
      'background_image_uri' => '',
      'background_image' => '',
      'background_position' => 'center top',
      'background_repeat' => 'no-repeat',
      'background_fit' => 'cover',
      'data_transition' => 'random',
      'slide_easing_in' => 'Power0.easeIn',
      'slide_easing_out' => 'Power1.easeOut',
      'delay' => '1000',
      'video_source' => '',
      'youtube_video' => '',
      'vimeo_video' => '',
      'html5_mp4'   => '',
      'mp4_nextslideatend' => 'true',
      'mp4_videoloop' => 'true',
      'video_youtube_args' => '&loop=1&autoplay=1&playlist=',
      'video_vimeo_args' => 'title=0&byline=0&portrait=0&api=1',
      'video_start_at' => '',
      'video_end_at' => '',
      'parallax_scroll' => 'off',
      'data_parallax'   => '8',
      'scalestart' => '0',
      'scaleend'  => '0',
    ];

    $delayer = [
      'index' => 10,
      'title' => '',
      'type' => 'text',
      'text' => 'Text Layer',
      'image' => '',
      'image_uri' => '',
      'fid' => '',
      'top' => 0,
      'left' => 0,
      'data_time_start' => 500,
      'data_time_end' => 50000,
      'incomingclasses' => 'SlideMaskFromTop',
      'outgoingclasses' => 'Fade-Out',
      'data_speed' => 600,
      'data_end' => 600,
      'data_easing' => 'easeOutExpo',
      'data_endeasing' => 'Power0.easeIn',
      'removed' => 0,
      'width' => 200,
      'height' => 100,
      'custom_css' => '',
      'select_content_type' => 'text',
    ];

    $page['#attached']['drupalSettings']['gavias_sliderlayer']['deslider'] = $deslider;
    $page['#attached']['drupalSettings']['gavias_sliderlayer']['delayer'] = $delayer;

    drupal_get_path('theme', $theme_name) . '/css/sliderlayer.css';

    ob_start();
    include GAVIAS_SLIDERLAYER_PATH . '/templates/backend/slider.php';
    $content = ob_get_clean();
    $page['admin-form'] = [
      '#theme' => 'admin-form',
      '#content' => $content,
    ];
    return $page;
  }

  /**
   * Gavias sliderlayer save.
   */
  public function gaviasSliderlayerSave() {
    header('Content-type: application/json');
    $gid = $this->getCurrentRequest()->request->get('gid');
    $sid = $this->getCurrentRequest()->request->get('sid');
    $title = $this->getCurrentRequest()->request->get('title');
    $sort_index = $this->getCurrentRequest()->request->get('sort_index');
    $status = $this->getCurrentRequest()->request->get('status');
    $settings = $this->getCurrentRequest()->request->get('settings');
    $datalayers = $this->getCurrentRequest()->request->get('datalayers');
    $background_image_uri = $this->getCurrentRequest()->request->get('background_image_uri');
    if ($sid > 0) {
      $builder = \Drupal::database()->update("gavias_sliderlayers")
        ->fields([
          ' sort_index' => $sort_index,
          'status' => $status,
          'title' => $title,
          'params'  => $settings,
          'layersparams' => $datalayers,
          'background_image_uri' => $background_image_uri,
        ])
        ->condition('id', $sid, '=')
        ->execute();

      $abs_url_edit = Url::fromRoute(
        'gavias_sl_sliders.admin.form', [
          'gid' => $gid,
          'sid' => $sid,
        ],
          ['absolute' => TRUE]
          )->toString();
      $result = [
        'data' => 'insert saved',
        'action' => 'edit',
        'url_edit'  => $abs_url_edit,
      ];
    }
    else {
      $sid = $builder = \Drupal::database()->insert("gavias_sliderlayers")
        ->fields([
          'sort_index' => $sort_index,
          'status' => $status,
          'title' => $title,
          'group_id' => $gid,
          'params'  => $settings,
          'layersparams' => $datalayers,
          'background_image_uri' => $background_image_uri,
        ])
        ->execute();
      $abs_url_edit = Url::fromRoute(
        'gavias_sl_sliders.admin.form', [
          'gid' => $gid,
          'sid' => $sid,
        ],
        [
          'absolute' => TRUE,
        ])->toString();
      $result = [
        'data' => 'insert saved',
        'sid'  => $sid,
        'gid'  => $gid,
        'action' => 'add',
        'url_edit'  => $abs_url_edit,
      ];
      \Drupal::messenger()->addMessage("SliderLayers has been created");
    }
    // Clear all cache.
    \Drupal::service('plugin.manager.block')->clearCachedDefinitions();
    foreach (Cache::getBins() as $service_id => $cache_backend) {
      if ($service_id == 'render' || $service_id == 'page') {
        $cache_backend->deleteAll();
      }
    }
    print json_encode($result);
    exit(0);
  }

  /**
   * Gavias upload file.
   */
  public function gaviasUploadFile() {
    // A list of permitted file extensions.
    global $base_url;
    $allowed = ['png', 'jpg', 'gif', 'zip'];
    $_id = gavias_sliderlayer_makeid(6);
    if (isset($this->getCurrentRequest()->files->get('upl')) && $this->getCurrentRequest()->files->get('upl')('error') == 0) {

      $extension = pathinfo($this->getCurrentRequest()->files->get('upl')('name'), PATHINFO_EXTENSION);

      if (!in_array(strtolower($extension), $allowed)) {
        echo '{"status":"error extension"}';
        exit;
      }
      $path_folder = \Drupal::service('file_system')->realpath(gva_file_default_scheme() . "://gva-slider-upload");

      $file_path = $path_folder . '/' . $_id . '-' . $this->getCurrentRequest()->files->get('upl')('name');
      $file_url = str_replace($base_url, '', file_create_url(gva_file_default_scheme() . "://gva-slider-upload") . '/' . $_id . '-' . $this->getCurrentRequest()->files->get('upl')('name'));
      if (!is_dir($path_folder)) {
        @mkdir($path_folder);
      }
      if (move_uploaded_file($this->getCurrentRequest()->files->get('upl')('tmp_name'), $file_path)) {
        $result = [
          'file_url' => $file_url,
          'file_url_full' => $base_url . $file_url,
        ];
        print json_encode($result);
        exit;
      }
    }

    echo '{"status":"error"}';
    exit;

  }

}
