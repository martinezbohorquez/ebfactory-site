<?php

namespace Drupal\gavias_content_builder\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Url;

/**
 *
 */
class GaviasContentBuilderFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    foreach ($items as $delta => $item) {
      $bid = !empty($item->bid) ? $item->bid : 0;
      $content = '';
      if ($bid) {
        $results = gavias_content_builder_load($bid);
        if (!$results) {
          $content = t('No block builder selected');
        }
        else {
          $user = \Drupal::currentUser();
          $url = \Drupal::request()->getRequestUri();
          $edit_url = '';
          if ($user->hasPermission('administer gavias_content_builder')) {
            $edit_url = Url::fromRoute('gavias_content_builder.admin.edit', ['bid' => $bid, 'destination' => $url])->toString();
          }

          $content .= '<div class="gavias-builder--content">';
          if ($edit_url) {
            $content .= '<a class="link-edit-blockbuider" href="' . $edit_url . '"> Config block builder </a>';
          }

          $content .= gavias_content_builder_frontend($results->params);
          $content .= '</div>';
        }
      }
      $elements[$delta] = [
        '#type' => 'markup',
        '#id' => $bid,
        '#theme' => 'builder',
        '#content' => $content,
        '#cache' => [
          'max-age' => 0,
        ],
      ];
    }
    return $elements;
  }

}
