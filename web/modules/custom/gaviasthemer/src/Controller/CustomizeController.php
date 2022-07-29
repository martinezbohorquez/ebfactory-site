<?php

namespace Drupal\gaviasthemer\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Customize Controller.
 */
class CustomizeController extends ControllerBase {

  /**
   * Save.
   */
  public function save() {
    // $user = \Drupal::currentUser();
    header('Content-type: application/json');
    $json = $this->getCurrentRequest()->request->get('data');
    $theme = $this->getCurrentRequest()->request->get('theme_name');
    $path_theme = drupal_get_path('theme', $theme);
    gaviasthemer_writecache($path_theme . '/css/', 'customize', $json, 'json');
    // Clear all cache
    // $json = base64_encode($json);
    \Drupal::configFactory()->getEditable('gaviasthemer.settings')
      ->set('gavias_customize', $json)
      ->save();

    $result = [
      'data' => 'update saved',
    ];
    print json_encode($result);
    exit(0);
  }

  /**
   * Preview.
   */
  public function preview() {
    header('Content-type: application/json');
    $json = $this->getCurrentRequest()->request->get('data');
    $theme = $this->getCurrentRequest()->request->get('theme_name');
    $path_theme = drupal_get_path('theme', $theme);
    $styles = '';
    if ($json) {
      ob_start();
      require_once $path_theme . '/customize/preview.php';
      $styles = ob_get_clean();
    }
    $styles = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $styles);
    $styles = str_replace(["\r\n", "\r", "\n", "\t", '  ', '   ', '    '], '', $styles);
    $return['style'] = $styles;

    echo json_encode($return);
    exit(0);
  }

}
