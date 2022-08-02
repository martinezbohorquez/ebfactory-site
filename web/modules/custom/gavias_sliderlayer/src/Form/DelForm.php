<?php

namespace Drupal\gavias_sliderlayer\Form;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Core\StringTranslation\StringTranslationTrait
/**
 * Form.
 */
class DelForm extends ConfirmFormBase {
  use StringTranslationTrait;

  /**
   * The ID of the item to delete.
   *
   * @var string
   */
  protected $sid;

  /**
   * The ID of the item to delete.
   *
   * @var string
   */
  protected $gid;

  /**
   * The ID of the item to delete.
   *
   * @var string
   */
  protected $action;

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
    if ($this->action == 'slider') {
      return $this->t('Do you want to delete Slider #%id?', ['%id' => $this->sid]);
    }
    if ($this->action == 'group') {
      return $this->t('Do you want to delete Group Slider #%id?', ['%id' => $this->gid]);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    if ($this->action == 'slider') {
      return new Url('gavias_sl_sliders.admin.list', ['gid' => $this->gid]);
    }
    else {
      return new Url('gaviasSlGroup.admin');
    }
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
   * Build Form.
   */
  public function buildForm(array $form, FormStateInterface $form_state, $sid = 0, $gid = 0, $action = '') {
    $this->sid = $sid;
    $this->gid = $gid;
    $this->action = $action;
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $sid = $this->sid;
    $gid = $this->gid;
    $action = $this->action;
    if ($action == 'group') {

      \Drupal::database()->delete('gavias_sliderlayergroups')
        ->condition('id', $gid)
        ->execute();

      \Drupal::database()->delete('gavias_sliderlayers')
        ->condition('group_id', $gid)
        ->execute();

      \Drupal::service('plugin.manager.block')->clearCachedDefinitions();
      \Drupal::messenger()->addMessage("SliderLayer Group '#{$gid}' has been deleted");
      $response = new RedirectResponse(Url::fromRoute('gaviasSlGroup.admin')->toString());
      $response->send();
    }

    if ($action == 'slider') {

      \Drupal::database()->delete('gavias_sliderlayers')
        ->condition('id', $sid)
        ->execute();

      \Drupal::service('plugin.manager.block')->clearCachedDefinitions();
      \Drupal::messenger()->addMessage("SliderLayer item '#{$sid}' has been deleted");
      $response = new RedirectResponse(Url::fromRoute('gavias_sl_sliders.admin.list', ['gid' => $gid])->toString());
      $response->send();

    }

  }

}
