<?php

namespace Drupal\gavias_sliderlayer\Form;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Form\FormInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * ¡Slider Duplicate!
 */
class SliderDuplicate implements FormInterface {
  use StringTranslationTrait;

  /**
   * Implements \Drupal\Core\Form\FormInterface::getFormId()
   */
  public function getFormId() {
    return 'slider_duplicate';
  }

  /**
   * Implements \Drupal\Core\Form\FormInterface::buildForm().
   */

  /**
   * ¡Build Form!
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $id = 0;
    if (\Drupal::request()->attributes->get('id')) {
      $id = \Drupal::request()->attributes->get('id');
    }

    if (is_numeric($id)) {
      $slide = \Drupal::database()->select('{gavias_sliderlayers}', 'd')->fields('d')->condition('id', $id, '=')->execute()->fetchAssoc();
    }
    else {
      $slide = [
        'id' => 0,
        'title' => '',
        'sort_index' => 1,
        'group_id' => 0,
        'params' => '',
        'layersparams' => '',
        'status' => 0,
        'background_image_uri' => 0,
      ];
    }

    $form = [];
    $form['id'] = [
      '#type' => 'hidden',
      '#default_value' => $slide['id'],
    ];
    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#default_value' => $this->t('Duplicate') . $slide['title'],
    ];
    $form['sort_index'] = [
      '#type' => 'hidden',
      '#default_value' => $slide['sort_index'],
    ];
    $form['group_id'] = [
      '#type' => 'hidden',
      '#default_value' => $slide['group_id'],
    ];
    $form['params'] = [
      '#type' => 'hidden',
      '#default_value' => $slide['params'],
    ];
    $form['layersparams'] = [
      '#type' => 'hidden',
      '#default_value' => $slide['layersparams'],
    ];
    $form['status'] = [
      '#type' => 'hidden',
      '#default_value' => $slide['status'],
    ];
    $form['background_image_uri'] = [
      '#type' => 'hidden',
      '#default_value' => $slide['background_image_uri'],
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];
    $form['actions'] = [
      '#type' => 'actions',
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];
    return $form;
  }

  /**
   * Implements \Drupal\Core\Form\FormInterface::validateForm().
   */

  /**
   * ¡Validate Form!
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (isset($form['values']['title']) && $form['values']['title'] === '') {
      $this->setFormError('title', $form_state, $this->t('Please enter title for slider.'));
    }
  }

}

/**
 * Implements \Drupal\Core\Form\FormInterface::submitForm().
 */

/**
 * ¡Submit Form!
 */
public function submit_form(array &$form, FormStateInterface $form_state) {
  if (is_numeric($form['id']['#value']) && $form['id']['#value'] > 0) {
    $builder = \Drupal::database()->insert(
      "gavias_sliderlayers")->fields([
        'title' => $form['title']['#value'],
        'group_id' => $form['group_id']['#value'],
        'sort_index' => $form['sort_index']['#value'],
        'params' => $form['params']['#value'],
        'layersparams' => $form['layersparams']['#value'],
        'status' => $form['status']['#value'],
        'background_image_uri' => $form['background_image_uri']['#value'],
      ])->execute();
    \Drupal::messenger()->addMessage("Slide '{$form['title']['#value']}' has been duplicate");
    \Drupal::service('plugin.manager.block')->clearCachedDefinitions();
  }
  $response = new RedirectResponse(Url::fromRoute('gavias_sl_sliders.admin.list', ['gid' => $form['group_id']['#value']])->toString());
  $response->send();
}
