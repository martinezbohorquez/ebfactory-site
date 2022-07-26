<?php

namespace Drupal\gavias_content_builder\Form;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Form\FormInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 *
 */
class AddForm implements FormInterface {

  /**
   * Implements \Drupal\Core\Form\FormInterface::getFormID().
   */
  public function getFormID() {
    return 'add_form';
  }

  /**
   * Implements \Drupal\Core\Form\FormInterface::buildForm().
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $bid = 0;
    if (\Drupal::request()->attributes->get('bid')) {
      $bid = \Drupal::request()->attributes->get('bid');
    }
    if (is_numeric($bid) && $bid > 0) {
      $builder = \Drupal::database()->select('{gavias_content_builder}', 'd')
        ->fields('d', ['id', 'title', 'machine_name', 'use_field'])
        ->condition('id', $bid)
        ->execute()
        ->fetchAssoc();
    }
    else {
      $builder = ['id' => 0, 'title' => '', 'machine_name' => '', 'use_field' => 1];
    }
    $form['id'] = [
      '#type' => 'hidden',
      '#default_value' => $builder['id'],
    ];
    $form['title'] = [
      '#type' => 'textfield',
      '#title' => 'Title',
      '#default_value' => $builder['title'],
    ];
    $form['machine_name'] = [
      '#type' => 'textfield',
      '#title' => 'Machine name',
      '#description' => 'A unique machine-readable name containing letters, numbers, and underscores. e.g: home_page',
      '#default_value' => $builder['machine_name'],
    ];
    $form['use_field'] = [
      '#type' => 'checkbox',
      '#title' => 'Use This Content Builder for Field',
      '#default_value' => $builder['use_field'],
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
    if (!$form_state->getValue('title')) {
      $form_state->setErrorByName('title', 'Please enter title for buider block.');
    }
  }

  /**
   * Implements \Drupal\Core\Form\FormInterface::submitForm().
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    if (is_numeric($form['id']['#value']) && $form['id']['#value'] > 0) {

      $pid = $builder = \Drupal::database()->update("gavias_content_builder")
        ->fields([
          'title'         => $form['title']['#value'],
          'machine_name'  => $form['machine_name']['#value'],
          'use_field'     => $form['use_field']['#value'],
        ])
        ->condition('id', $form['id']['#value'])
        ->execute();

      \Drupal::service('plugin.manager.block')->clearCachedDefinitions();
      \Drupal::messenger()->addMessage("Builder '{$form['title']['#value']}' has been update");
      $response = new RedirectResponse(Url::fromRoute('gavias_content_builder.admin')->toString());
      $response->send();
    }
    else {
      $pid = $builder = \Drupal::database()->insert("gavias_content_builder")
        ->fields([
          'title'         => $form['title']['#value'],
          'machine_name'  => $form['machine_name']['#value'],
          'use_field'     => $form['use_field']['#value'],
          'params'        => '',
        ])
        ->execute();
      \Drupal::service('plugin.manager.block')->clearCachedDefinitions();
      \Drupal::messenger()->addMessage("Builder '{$form['title']['#value']}' has been created");
      $response = new RedirectResponse(Url::fromRoute('gavias_content_builder.admin')->toString());
      $response->send();
    }
  }

}
