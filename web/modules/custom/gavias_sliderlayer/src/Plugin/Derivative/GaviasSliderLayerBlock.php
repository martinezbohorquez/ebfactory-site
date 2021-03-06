<?php

namespace Drupal\gavias_sliderlayer\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;

/**
 * Provides blocks which belong to Gavias SliderLayer.
 */
class GaviasSliderLayerBlock extends DeriverBase {

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    if (!\Drupal::database()->schema()->tableExists('gavias_sliderlayergroups')) {
      return "";
    }
    $results = \Drupal::database()->select('{gavias_sliderlayergroups}', 'd')
      ->fields('d', ['id', 'title'])
      ->execute();
    foreach ($results as $row) {
      $this->derivatives['gavias_sliderlayer_block____' . $row->id] = $base_plugin_definition;
      $this->derivatives['gavias_sliderlayer_block____' . $row->id]['admin_label'] = 'Gavias SliderLayer - ' . $row->title;
    }
    return $this->derivatives;
  }

}
