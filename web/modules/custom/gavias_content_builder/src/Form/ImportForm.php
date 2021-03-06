<?php

namespace Drupal\gavias_content_builder\Form;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Form\FormInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;
use Drupal\Core\Url;

/**
 * Import Form.
 */
class ImportForm implements FormInterface {

  /**
   * Implements \Drupal\Core\Form\FormInterface::getFormId().
   */
  public function getFormId() {
    return 'import_form';
  }

  /**
   * Implements \Drupal\Core\Form\FormInterface::buildForm().
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
    $form = [];
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
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => 'Save',
    ];
    return $form;
  }

  /**
   * Implements \Drupal\Core\Form\FormInterface::validateForm().
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (isset($form['values']['title']) && $form['values']['title'] === '') {
      $this->setFormError('title', $form_state, $this->t('Please enter title for buider block.'));
    }
  }

  /**
   * Implements \Drupal\Core\Form\FormInterface::submitForm().
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
      \Drupal::database()->update("gavias_content_builder")
        ->fields([
          'params' => $params,
        ])
        ->condition('id', $id)
        ->execute();
      \Drupal::service('plugin.manager.block')->clearCachedDefinitions();
      \Drupal::messenger()->addMessage("Block Builder '{$form['title']['#value']}' has been updated");
      $response = new RedirectResponse(Url::fromRoute(
        'gavias_content_builder.admin.edit',
        ['bid' => $id])->toString());
      $response->send();
    }
  }

}
