<?php

namespace Drupal\gavias_sliderlayer\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * File Controller.
 */
class FileController extends ControllerBase {

  /**
   * Gavias sliderlayer upload file.
   */
  public function gaviasSliderlayerUploadFile() {
    // A list of permitted file extensions.
    global $base_url;
    $allowed = ['png', 'jpg', 'gif', 'zip'];
    $_id = gavias_sliderlayer_makeid(6);
    if (isset($this->getCurrentRequest()->files->get('upl')) && $this->getCurrentRequest()->files->get('upl')('error') == 0) {

      $extension = pathinfo($stack->getCurrentRequest()->files->get('upl')('name'), PATHINFO_EXTENSION);

      if (!in_array(strtolower($extension), $allowed)) {
        echo '{"status":"error extension"}';
        exit;
      }
      $path_folder = \Drupal::service('file_system')->realpath(gva_file_default_scheme() . "://gva-sliderlayer-upload");

      // $file_path = $path_folder . '/' . $_id . '-' . $_FILES['upl']['name'];
      $ext = end(explode('.', $stack->getCurrentRequest()->files->get('upl')('name')));
      $image_name = basename($stack->getCurrentRequest()->files->get('upl')('name'), ".{$ext}");

      $file_path = $path_folder . '/' . $image_name . "-{$_id}" . ".{$ext}";
      $file_url = str_replace($base_url, '', file_create_url(gva_file_default_scheme() . "://gva-sliderlayer-upload") . '/' . $image_name . "-{$_id}" . ".{$ext}");

      if (!is_dir($path_folder)) {
        @mkdir($path_folder);
      }
      if (move_uploaded_file($stack->getCurrentRequest()->files->get('upl')('tmp_name'), $file_path)) {
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

  /**
   * Get images upload.
   */
  public function getImagesUpload() {
    header('Content-type: application/json');
    global $base_url;

    $file_path = \Drupal::service('file_system')->realpath(gva_file_default_scheme() . "://gva-sliderlayer-upload");

    $file_url = file_create_url(gva_file_default_scheme() . "://gva-sliderlayer-upload") . '/';
    $list_file = glob($file_path . '/*.{jpg,png,gif}', GLOB_BRACE);

    $files = [];
    foreach ($list_file as $key => $file) {
      if (basename($file)) {
        $file_url = str_replace($base_url, '', file_create_url(gva_file_default_scheme() . "://gva-sliderlayer-upload") . '/' . basename($file));
        $files[$key]['file_url'] = $file_url;
        $files[$key]['file_url_full'] = $base_url . $file_url;
      }
    }
    $result = [
      'data' => $files,
    ];
    print json_encode($result);
    exit(0);
  }

}
