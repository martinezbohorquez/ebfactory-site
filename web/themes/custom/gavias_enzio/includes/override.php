<?php

/**
 * @file
 * This is override.
 */

/**
 * Gavias enzio preprocess views view grid.
 */
function gavias_enzio_preprocess_views_view_grid(&$variables) {
  $view = $variables['view'];
  // $rows = $variables['rows'];
  $style = $view->style_plugin;
  $options = $style->options;
  $variables['gva_masonry']['class'] = '';
  $variables['gva_masonry']['class_item'] = '';
  if (strpos($options['row_class_custom'], 'masonry') || $options['row_class_custom'] == 'masonry') {
    $variables['gva_masonry']['class'] = 'post-masonry-style row';
    $variables['gva_masonry']['class_item'] = 'item-masory';
  }
}
