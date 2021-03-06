<?php

namespace Drupal\gavias_sliderlayer\Form;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Form\FormInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Group Form.
 */
class GroupForm implements FormInterface {

  /**
   * Implements \Drupal\Core\Form\FormInterface::getFormId().
   */
  public function getFormId() {
    return 'add_form';
  }

  /**
   * Implements \Drupal\Core\Form\FormInterface::buildForm().
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
      $slide = ['id' => 0, 'title' => ''];
    }
    $form = [];
    $form['id'] = [
      '#type' => 'hidden',
      '#default_value' => $slide['id'],
    ];
    $form['title'] = [
      '#type' => 'textfield',
      '#title' => 'Title',
      '#default_value' => $slide['title'],
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => 'Save',
    ];

    $form['actions'] = ['#type' => 'actions'];
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
      $this->setFormError('title', $form_state, $this->t('Please enter title for slider.'));
    }
  }

  /**
   * Implements \Drupal\Core\Form\FormInterface::submitForm().
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    if (is_numeric($form['id']['#value']) && $form['id']['#value'] > 0) {
      \Drupal::database()->update("gavias_sliderlayergroups")
        ->fields([
          'title' => $form['title']['#value'],
        ])
        ->condition('id', $form['id']['#value'])
        ->execute();
      \Drupal::service('plugin.manager.block')->clearCachedDefinitions();
      \Drupal::messenger()->addMessage("Slide '{$form['title']['#value']}' has been updated");
    }
    else {
      \Drupal::database()->insert("gavias_sliderlayergroups")
        ->fields([
          'title' => $form['title']['#value'],
          'params' => '',
        ])
        ->execute();
      \Drupal::messenger()->addMessage("Slide '{$form['title']['#value']}' has been created");
      \Drupal::service('plugin.manager.block')->clearCachedDefinitions();
    }
    $response = new RedirectResponse(Url::fromRoute('gaviasSlGroup.admin')->toString());
    $response->send();
  }

}
