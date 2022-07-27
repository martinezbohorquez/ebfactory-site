<?php

namespace Drupal\gavias_sliderlayer\Form;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Form\FormInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;
use Drupal\Core\Url;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * ¡Importing forms!
 */
class ImportForm implements FormInterface {
  use StringTranslationTrait;

  /**
   * Implements \Drupal\Core\Form\FormInterface::getFormId().
   */
  public function getFormId() {
    return 'import_form';
  }

  /**
   * Implements \Drupal\Core\Form\FormInterface::buildForm().
   */

  /**
   * ¡Building forms!
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $gid = 0;
    if (\Drupal::request()->attributes->get('gid')) {
      $gid = \Drupal::request()->attributes->get('gid');
    }

    if (is_numeric($gid)) {
      $group = \Drupal::database()
        ->select('{gavias_sliderlayergroups}', 'd')
        ->fields('d')
        ->condition('id', $gid, '=')
        ->execute()
        ->fetchAssoc();
    }
    else {
      $group = ['id' => 0, 'title' => ''];
    }
    if ($group['id'] == 0) {
      \Drupal::messenger()->addMessage('Not found gavias slider layer !');
      return FALSE;
    }

    $form = [];
    $form['gid'] = ['#type' => 'hidden', '#default_value' => $group['id']];
    $form['title'] = [
      '#type' => 'textfield',
      '#value' => $group['title'],
      '#attributes' => ['readonly' => 'readonly'],
    ];
    $form['file'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Upload File Content'),
      '#description' => $this->t(
              'Upload your sliderlayer that exported before. Allowed extensions: .txt'
      ),
      '#upload_location' => 'public://',
      '#upload_validators' => [
              // Pass the maximum file size in bytes.
        'file_validate_extensions' => ['txt'],
        'file_validate_size' => [1024 * 1280 * 800],
      ],
      '#required' => TRUE,
    ];
    $form['submit'] = ['#type' => 'submit', '#value' => 'Save'];
    return $form;
  }

  /**
   * Implements \Drupal\Core\Form\FormInterface::validateForm().
   */

  /**
   * ¡Validating Form!
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (
          isset($form['values']['title']) &&
          $form['values']['title'] === ''
      ) {
      $this->setFormError(
            'title',
            $form_state,
            $this->t('Please enter title for slider layer.')
        );
    }
  }

  /**
   * Implements \Drupal\Core\Form\FormInterface::submitForm().
   */

  /**
   * ¡Submiting Form!
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    if ($form['gid']['#value']) {
      $data = '';
      if (!empty($values['file'][0])) {
        $fid = $values['file'][0];
        $file = File::load($fid);
        $read_file = \Drupal::service('file_system')->realpath(
              $file->getFileUri()
          );
        $data = file_get_contents($read_file);
      }

      $gid = $form['gid']['#value'];
      $json = base64_decode($data);
      $slideshow = json_decode($json);
      $builder = \Drupal::database()
        ->update('gavias_sliderlayergroups')
        ->fields([
          'params' =>
          isset($slideshow->group->params) &&
          $slideshow->group->params
          ? $slideshow->group->params
          : '',
        ])
        ->condition('id', $gid)
        ->execute();
      $i = 0;
      if ($slideshow->sliders) {
        \Drupal::database()
          ->delete('gavias_sliderlayers')
          ->condition('group_id', $gid)
          ->execute();
        foreach ($slideshow->sliders as $key => $slider) {
          $i++;
          $builder = \Drupal::database()
            ->insert('gavias_sliderlayers')
            ->fields([
              'sort_index' =>
              isset($slider->sort_index) &&
              $slider->sort_index
              ? $slider->sort_index
              : $i,
              'status' =>
              isset($slider->status) && $slider->status
              ? $slider->status
              : 1,
              'title' =>
              isset($slider->title) && $slider->title
              ? $slider->title
              : 'Title',
              'group_id' => $gid,
              'params' =>
              isset($slider->params) && $slider->params
              ? $slider->params
              : '',
              'layersparams' => $slider->layersparams,
              'background_image_uri' =>
              isset($slider->background_image_uri) &&
              $slider->background_image_uri
              ? $slider->background_image_uri
              : '',
            ])
            ->execute();
        }
      }

      \Drupal::service('plugin.manager.block')->clearCachedDefinitions();
      \Drupal::messenger()->addMessage(
            "Slider Layer '{$form['title']['#value']}' has been import"
        );
      $response = new RedirectResponse(
            Url::fromRoute('gavias_sl_group.admin', [
              'gid' => $gid,
            ])->toString()
        );
      $response->send();
    }
  }

}
irectResponse(
    Url::fromRoute('gavias_sl_group.admin', ['gid' => $gid])->toString()
);
$response->send();
