<?php

namespace Drupal\gavias_content_builder\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Provides blocks which belong to Gavias BuilderBlock.
 *
 * @Block(
 *   id = "gavias_content_builder_block",
 *   admin_label = @Translation("Gavias Builder Content"),
 *   category = @Translation("Gavias Builder Content"),
 *   deriver = "Drupal\gavias_content_builder\Plugin\Derivative\GaviasContentBuilderBlock",
 * )
 */
class GaviasContentBuilderBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  protected $bid;

  /**
   * {@inheritdoc}
   */
  public function build() {
    $bid = $this->getDerivativeId();
    $this->bid = $bid;
    $block = [];
    if (str_replace('gavias_content_builder_block____', '', $bid) != $bid) {
      $bid = str_replace('gavias_content_builder_block____', '', $bid);
      $results = gavias_content_builder_load($bid);
      if (!$results) {
        return 'No block builder selected';
      }
      $content_block = gavias_content_builder_frontend($results->params);
      $user = \Drupal::currentUser();
      $url = \Drupal::request()->getRequestUri();
      $edit_url = '';
      if ($user->hasPermission('administer gavias_content_builder')) {
        $edit_url = Url::fromRoute(
          'gavias_content_builder.admin.edit',
          [
            'bid' => $bid,
            'destination' => $url,
          ]
          )->toString();
      }
      $block = [
        '#theme' => 'builder',
        '#content' => $content_block,
        '#edit_url' => $edit_url,
        '#cache' => ['max-age' => 0],
      ];
    }

    return $block;
  }

  /**
   * Default cache is disabled.
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $rebuild_form = parent::buildConfigurationForm($form, $form_state);
    $rebuild_form['cache']['max_age']['#default_value'] = 0;
    return $rebuild_form;
  }

}
