<?php

namespace Drupal\gavias_content_builder\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CloseDialogCommand;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Duplicate Form Popup.
 */
class DuplicateFormPopup extends FormBase {

  /**
   * Get Form Id.
   */
  public function getFormId() {
    return 'duplicate_form_popup';
  }

  /**
   * Build Form.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // $args = $this->getFormArgs($form_state);
    $bid = 0;
    $random = '';
    if (\Drupal::request()->attributes->get('bid')) {
      $bid = \Drupal::request()->attributes->get('bid');
    }
    if (\Drupal::request()->attributes->get('random')) {
      $random = \Drupal::request()->attributes->get('random');
    }
    if (is_numeric($bid) && $bid > 0) {
      $builder = \Drupal::database()->select('{gavias_content_builder}', 'd')
        ->fields('d', ['id', 'title', 'machine_name'])
        ->condition('id', $bid)
        ->execute()
        ->fetchAssoc();
    }
    else {

      $builder = [
        'id' => 0,
        'title' => '',
        'machine_name' => '',
        'use_field' => 1,
      ];
    }

    $form['builder-dialog-messages'] = [
      '#markup' => '<div id="builder-dialog-messages"></div>',
    ];
    $form['random'] = [
      '#type' => 'hidden',
      '#default_value' => $random,
    ];

    $form['id'] = [
      '#type' => 'hidden',
      '#default_value' => $builder['id'],
    ];
    $form['title'] = [
      '#type' => 'textfield',
      '#title' => 'Title',
      '#default_value' => 'Clone ' . $builder['title'],
    ];
    $form['machine_name'] = [
      '#type' => 'textfield',
      '#title' => 'Machine name',
      '#description' => 'A unique machine-readable name containing letters, numbers, and underscores<br>Sample home_page_1',
      '#default_value' => '',
    ];
    $form['use_field'] = [
      '#type' => 'checkbox',
      '#title' => 'Use this Builder for Field',
      '#default_value' => 1,
    ];
    $form['actions']['submit'] = [
      '#value' => $this->t('Submit'),
      '#type' => 'submit',
      '#ajax' => [
        'callback' => '::modal',
      ],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (!$form_state->getValue('title')) {
      $form_state->setErrorByName('title', 'Please enter title for buider block.');
    }
  }

  /**
   * Submit handle for adding Element.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $errors = [];

    if (!$form_state->getValue('title')) {
      $errors[] = 'Please enter title for buider block.';
    }

    $bid = '';
    if ($errors) {
    }
    else {

      $bid = $form['id']['#value'];

      if (is_numeric($bid) && $bid > 0) {
        $buider = \Drupal::database()->select('{gavias_content_builder}', 'd')
          ->fields('d', ['id', 'title', 'params'])
          ->condition('id', $bid)
          ->execute()
          ->fetchAssoc();
      }
      else {
        $buider = [
          'id' => 0,
          'title' => '',
          'machine_name' => '',
          'params' => '',
          'use_field' => 1,
        ];
      }

      $pid = $builder = \Drupal::database()->insert("gavias_content_builder")
        ->fields([
          'title' => $form['title']['#value'],
          'machine_name' => $form['machine_name']['#value'],
          'params' => $buider['params'],
          'use_field' => $form['use_field']['#value'],
        ])
        ->execute();
    }

    $form_state->setValue('pid', $pid);
    $form_state->setValue('machine_name', $form['machine_name']['#value']);
    $form_state->setValue('use_field', $form['use_field']['#value']);
    $form_state->setValue('errors_exist', $errors_exist);
    $form_state->setValue('errors', $errors);
  }

  /**
   * Get Form Args.
   */
  public function getFormArgs($form_state) {
    // $args = [];
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

    if (!$form_state->getValue('title')) {
      $errors[] = 'Please enter title for buider block.';
    }

    if (!empty($errors)) {
      $form_state->clearErrors();
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

    $pid = $values['pid'];
    $title = $values['title'];
    $machine_name = $values['machine_name'];
    $random = $values['random'];
    $response = new AjaxResponse();

    $content['#attached']['library'][] = 'core/drupal.dialog.ajax';

    $content['#attached']['library'][] = 'core/drupal.dialog';

    $response->addCommand(new CloseDialogCommand('.ui-dialog-content'));

    $response->addCommand(new InvokeCommand('.field--type-gavias-content-builder .gva-choose-gbb.gva-id-' . $random . ' span', 'removeClass', ['active']));

    $html = '';
    $html .= '<span class="gbb-item active id-' . $pid . '">';
    $html .= '<a class="select" data-id="' . $pid . '" title="' . $machine_name . '">' . $title . '</a>';
    $html .= ' <span class="action">';
    $html .= '( <a class="edit gva-popup-iframe" href="' . Url::fromRoute(
      'gavias_content_builder.admin.edit',
      ['bid' => $pid, 'gva_iframe' => 'on'])->toString() . '" title="' . $machine_name . '">Edit</a>';
    $html .= ' | <a>Please save and refesh if you want duplicate</a>) </span>';
    $html .= '</span>';

    $response->addCommand(new InvokeCommand('.field--type-gavias-content-builder .gva-choose-gbb', 'append', [$html]));

    $response->addCommand(new InvokeCommand('.field_gavias_content_builder.gva-id-' . $random, 'val', [$pid]));

    // Quick edit compatible.
    $response->addCommand(new InvokeCommand(
      '.quickedit-toolbar .action-save',
      'attr',
      ['aria-hidden', FALSE]));

    return $response;

  }

}
