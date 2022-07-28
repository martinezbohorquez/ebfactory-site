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
 * Del Form Popup.
 */
class DelFormPopup extends FormBase {
  /**
   * The ID of the item to delete.
   *
   * @var protected
   */

  protected $bid;

  /**
   * Get Form Id.
   */
  public function getFormId() {
    return 'del_form_popup';
  }

  /**
   * Get Question.
   */
  public function getQuestion() {
    return $this->t('Do you want to delete %bid?', ['%bid' => $this->bid]);
  }

  /**
   * Get Cancel Url.
   */
  public function getCancelUrl() {
    return new Url('gavias_content_builder.admin');
  }

  /**
   * Get Description.
   */
  public function getDescription() {
    return $this->t('Only do this if you are sure!');
  }

  /**
   * Get Confirm Text.
   */
  public function getConfirmText() {
    return $this->t('Delete it!');
  }

  /**
   * Get Cancel Text.
   */
  public function getCancelText() {
    return $this->t('Cancel');
  }

  /**
   * Build Form.
   */
  public function buildForm(array $form, FormStateInterface $form_state, $bid = NULL) {
    $this->bid = $bid;
    if (\Drupal::request()->attributes->get('bid')) {
      $bid = \Drupal::request()->attributes->get('bid');
    }
    if (\Drupal::request()->attributes->get('random')) {
      $random = \Drupal::request()->attributes->get('random');
    }
    $form['builder-dialog-messages'] = ['#markup' => '<div id="builder-dialog-messages">' . $this->t('Do you want to delete it') . '</div>'];
    $form['id'] = ['#type' => 'hidden', '#default_value' => $bid];
    $form['random'] = ['#type' => 'hidden', '#default_value' => $random];
    $form['actions']['submit'] = [
      '#value' => $this->t('Submit'),
      '#type' => 'submit',
      '#ajax' => ['callback' => '::modal'],
    ];
    return $form;
  }

  /**
   * Submit Form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $bid = $this->bid;
    if (!$bid && \Drupal::request()->attributes->get('bid')) {
      $bid = \Drupal::request()->attributes->get('bid');
    }
    \Drupal::database()->delete('gavias_content_builder')->condition('id', $bid)->execute();
    \Drupal::service('plugin.manager.block')->clearCachedDefinitions();
    \Drupal::messenger()->addMessage("blockbuilder '#{$bid}' has been delete");
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
      \Drupal::messenger()->deleteByType('error');
      // Clear next message session;.
      $content = '<div class="messages messages--error" aria-label="Error message" role="contentinfo"><div role="alert"><ul>';
      foreach ($errors as $name => $error) {
        $response = new AjaxResponse();
        $content .= "<li>$error</li>";
      }
      $content .= '</ul></div></div>';
      $data = ['#markup' => $content];
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
    $pid = $values['id'];
    $random = $values['random'];
    // $element = $values['element'] ?? [];
    $response = new AjaxResponse();
    $content['#attached']['library'][] = 'core/drupal.dialog.ajax';
    $content['#attached']['library'][] = 'core/drupal.dialog';
    $response->addCommand(new CloseDialogCommand('.ui-dialog-content'));
    $response->addCommand(new InvokeCommand('.field--type-gavias-content-builder .gva-choose-gbb .gbb-item.id-' . $pid, 'remove'));
    $response->addCommand(new InvokeCommand(".field--type-gavias-content-builder .gva-choose-gbb.gva-id-{$random} .gbb-item.disable", 'addClass', ['active']));
    $response->addCommand(new InvokeCommand('.field_gavias_content_builder.gva-id-' . $random, 'val', ['']));
    // Quick edit compatible.
    $response->addCommand(new InvokeCommand(
      '.quickedit-toolbar .action-save',
      'attr', ['aria-hidden', FALSE],
    ));
    return $response;
  }

}
