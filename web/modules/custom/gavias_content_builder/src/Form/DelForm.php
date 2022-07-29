<?php

namespace Drupal\gavias_content_builder\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Del Form.
 */
class DelForm extends ConfirmFormBase {
  /**
   * The ID of the item to delete.
   *
   * @var string
   */
  protected $bid;

  /**
   * Implements \Drupal\Core\Form\FormInterface::getFormId().
   */
  public function getFormId() {
    return 'del_form';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Do you want to delete %bid?', ['%bid' => $this->bid]);
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('gavias_content_builder.admin');
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return $this->t('Only do this if you are sure!');
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Delete it!');
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelText() {
    return $this->t('Cancel');
  }

  /**
   * The ID of the item to be deleted.
   */
  public function buildForm(array $form, FormStateInterface $form_state, $bid = NULL) {
    $this->bid = $bid;
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $bid = $this->bid;
    if (!$bid && \Drupal::request()->attributes->get('bid')) {
      $bid = \Drupal::request()->attributes->get('bid');
    }
    \Drupal::database()->delete('gavias_content_builder')
      ->condition('id', $bid)
      ->execute();
    \Drupal::service('plugin.manager.block')->clearCachedDefinitions();
    \Drupal::messenger()->addMessage("blockbuilder '#{$bid}' has been delete");
    $response = new RedirectResponse(Url::fromRoute('gavias_content_builder.admin')->toString());
    $response->send();
  }

}
