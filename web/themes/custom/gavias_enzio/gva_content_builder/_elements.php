<?php

/**
 * @file
 * This is _element.
 */

/**
 * Gavias content builder set elements.
 */
function gavias_content_builder_set_elements() {
  return [
    'gva_column',
    'gva_row',
    'gva_accordion',
    'gva_box_color',
    'gva_call_to_action',
    'gva_chart',
    'gva_code',
    'gva_text',
    'gva_text_noeditor',
    'gva_counter',
    'gva_drupal_block',
    'gva_heading',
    'gva_icon_box',
    'gva_image',
    'gva_our_team',
    'gva_pricing_item',
    'gva_progress',
    'gva_tabs',
    'gva_video_box',
    'gva_gmap',
    'gva_button',
    'gva_view',
    'gva_quote_text',
    'gva_image_content',
    'gva_services_carousel',
    'gva_gallery',
    'gva_our_partners',
    'gva_download',
    'gva_socials',
    'gva_instagram',
    'gva_text_rotate',
    'gva_quotes_rotator',
  ];
}

/**
 * Gavias Merge Atts.
 */
function gavias_merge_atts($pairs, $atts, $shortcode = '') {
  $atts = (array) $atts;
  $out = [];
  foreach ($pairs as $name => $default) {
    if (array_key_exists($name, $atts)) {
      $out[$name] = $atts[$name];
    }
    else {
      $out[$name] = $default;
    }
  }
  return $out;
}
