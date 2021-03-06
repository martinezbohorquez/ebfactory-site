<?php

namespace Drupal\gavias_content_builder\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;

/**
 * Provides blocks which belong to Gavias Blockbuilder.
 */
class GaviasContentBuilderBlock extends DeriverBase {

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    if (\Drupal::database()->schema()->tableExists('gavias_content_builder')) {
      $results = \Drupal::database()->select('{gavias_content_builder}', 'd')
        ->fields('d', ['id', 'title'])
        ->execute();

      foreach ($results as $row) {
        $this->derivatives['gavias_content_builder_block____' . $row->id] = $base_plugin_definition;
        $this->derivatives['gavias_content_builder_block____' . $row->id]['admin_label'] = 'Gavias Content Builder - ' . $row->title;
      }
    }
    return $this->derivatives;
  }

}
