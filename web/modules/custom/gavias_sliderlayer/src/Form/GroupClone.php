<?php

namespace Drupal\gavias_sliderlayer\Form;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Form\FormInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * ¡Group Clone!
 */
class GroupClone implements FormInterface {
  use StringTranslationTrait;

  /**
   * Implements \Drupal\Core\Form\FormInterface::getFormId().
   */
  public function getFormId() {
    return 'clone_form';
  }

  /**
   * Implements \Drupal\Core\Form\FormInterface::buildForm().
   */

  /**
   * ¡Build Form!
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $sid = 0;
    if (\Drupal::request()->attributes->get('sid')) {
      $sid = \Drupal::request()->attributes->get('sid');
    }

    if (is_numeric($sid)) {
      $slide = \Drupal::database()->select('{gavias_sliderlayergroups}', 'd')->fields('d')->condition('id', $sid, '=')->execute()->fetchAssoc();
    }
    else {
      $slide = ['id' => 0, 'title' => '', 'params' => ''];
    }
    $form = [];
    $form['id'] = [
      '#type' => 'hidden',
      '#default_value' => $slide['id'],
    ];
    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#default_value' => $this->t('Clone') . $slide['title'],
    ];
    $form['params'] = [
      '#type' => 'hidden',
      '#default_value' => $slide['params'],
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'
      ),
    ];
    $form['actions'] = [
      '#type' => 'actions',
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'
      ),
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

  /**
   * Implements \Drupal\Core\Form\FormInterface::submitForm().
   */

  /**
   * ¡Submit Form!
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    if (is_numeric($form['id']['#value']) && $form['id']['#value'] > 0) {
      $old_id = $form['id']['#value'];
      $new_gid = $builder = \Drupal::database()->insert(
        "gavias_sliderlayergroups")->fields([
          'title' => $form['title']['#value'],
          'params' => $form['params']['#value'],
        ])->execute();
      $slides = gavias_sliders_by_group($old_id);
      foreach ($slides as $key => $slide) {
        $builder = \Drupal::database()->insert("gavias_sliderlayers")->fields([
          'title' => (isset($slide->title) && $slide->title) ? $slide->title : '',
          'group_id' => $new_gid,
          'sort_index' => (isset($slide->sort_index) && $slide->sort_index) ? $slide->sort_index : 1,
          'params' => (isset($slide->params) && $slide->params) ? $slide->params : '',
          'layersparams' => (isset($slide->layersparams) && $slide->layersparams) ? $slide->layersparams : '',
          'status' => (isset($slide->status)) ? $slide->status : 1,
          'background_image_uri' => (isset($slide->background_image_uri
          ) && $slide->background_image_uri) ? $slide->background_image_uri : '',
        ])->execute();
      }

      \Drupal::messenger()->addMessage("Slide '{$form['title']['#value']}' has been cloned");
      \Drupal::service('plugin.manager.block')->clearCachedDefinitions();
    }
    $response = new RedirectResponse(Url::fromRoute('gaviasSlGroup.admin')->toString());
    $response->send();
  }

}
