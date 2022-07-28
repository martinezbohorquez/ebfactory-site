<?php

namespace Drupal\gavias_content_builder\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CloseDialogCommand;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;

/**
 * Import Form Popup.
 */
class ImportFormPopup extends FormBase {

  /**
   * Get Form Id.
   */
  public function getFormId() {
    return 'duplicate_form_import';
  }

  /**
   * Build Form.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $bid = 0;
    if (\Drupal::request()->attributes->get('bid')) {
      $bid = \Drupal::request()->attributes->get('bid');
    }

    if (is_numeric($bid)) {
      $bblock = \Drupal::database()->select('{gavias_content_builder}', 'd')
        ->fields('d')
        ->condition('id', $bid, '=')
        ->execute()
        ->fetchAssoc();
    }
    else {
      $bblock = ['id' => 0, 'title' => ''];
    }
    if ($bblock['id'] == 0) {
      \Drupal::messenger()->addMessage('Not found gavias block builder !');
      return FALSE;
    }

    $form['builder-dialog-messages'] = [
      '#markup' => '<div id="builder-dialog-messages"></div>',
    ];
    $form['id'] = [
      '#type' => 'hidden',
      '#default_value' => $bblock['id'],
    ];
    $form['title'] = [
      '#type' => 'hidden',
      '#default_value' => $bblock['title'],
    ];
    $form['file'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Upload File Content'),
      '#description' => $this->t('Upload your builder that exported before. Allowed extensions: .txt'),
      '#upload_location' => 'public://',
      '#upload_validators' => [
        'file_validate_extensions' => ['txt'],
          // Pass the maximum file size in bytes.
        'file_validate_size' => [1024 * 1280 * 800],
      ],
      '#required' => TRUE,
    ];
    $form['actions']['submit'] = [
      '#value' => t('Submit'),
      '#type' => 'submit',
      '#ajax' => [
        'callback' => '::modal',
      ],
    ];
    return $form;
  }

  /**
   * Validate Form.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

  }

  /**
   * Submit handle for adding Element.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    if ($form['id']['#value']) {
      $params = '';

      if (!empty($values['file'][0])) {
        $fid = $values['file'][0];
        $file = File::load($fid);
        $read_file = \Drupal::service('file_system')->realpath($file->getFileUri());
        $params = file_get_contents($read_file);
      }

      $id = $form['id']['#value'];
      // $builder = \Drupal::database()->update("gavias_content_builder")
        ->fields([
          'params' => $params,
        ])
        ->condition('id', $id)
        ->execute();
      \Drupal::service('plugin.manager.block')->clearCachedDefinitions();
    }
  }

  /**
   * Get Form Args.
   */
  public function getFormArgs($form_state) {
    $args = [];
    $build_info = $form_state->getBuildInfo();
    if (!empty($build_info['args'])) {
      $args = array_shift($build_info['args']);
    }
    return $args;
  }

  /**
   * AJAX callback handler for Add Element Form.
   */
  public function modal(&$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $errors = [];

    if (!empty($errors)) {
      $form_state->clearErrors();
      // Clear next message session;.
      \Drupal::messenger()->deleteByType('error');
      $content = '<div class="messages messages--error" aria-label="Error message" role="contentinfo"><div role="alert"><ul>';
      foreach ($errors as $name => $error) {
        $response = new AjaxResponse();
        $content .= "<li>$error</li>";
      }
      $content .= '</ul></div></div>';
      $data = [
        '#markup' => $content,
      ];
      $data['#attached']['library'][] = 'core/drupal.dialog.ajax';
      $data['#attached']['library'][] = 'core/drupal.dialog';
      $response->addCommand(new HtmlCommand('#builder-dialog-messages', $content));
      return $response;
    }
    return $this->dialog($values);
  }

  /**
   * Dialog.
   */
  protected function dialog($values = []) {
    // $element = $values['element'] ?? [];
    $response = new AjaxResponse();

    $content['#attached']['library'][] = 'core/drupal.dialog.ajax';

    $content['#attached']['library'][] = 'core/drupal.dialog';

    $response->addCommand(new CloseDialogCommand('.ui-dialog-content'));

    // Quick edit compatible.
    $response->addCommand(new InvokeCommand('
    .quickedit-toolbar .action-save', 'attr', [
      'aria-hidden', FALSE,
    ]));

    return $response;

  }

}
