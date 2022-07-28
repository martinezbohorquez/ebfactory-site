<?php

/**
 * @file
 * This is sc_block.
 */

/**
 * Sc blockbulider.
 */
sc_blockbulider($attr) {
extract(shortcode_atts(['name' => ''], $attr));
$_id = 'blockbulider-' . gaviasBuilderMakeId();
$output = '';
$output .= "<div class=\"block-builder-main\" id=\"{$_id}\"><div class=\"gavias-blockbuilder-content\">";
if ($name) {
  $results = gavias_builder_load_by_machine($name);
  if (!$results) {
    return '<div class="alert alert-waring">No block builder selected</div>';
  }
  $user = \Drupal::currentUser();
  $url = \Drupal::request()->getRequestUri();
  $edit_url = '';
  if ($user->hasPermission('administer gavias_content_builder')) {
    $edit_url = Url::fromRoute(
      'gavias_builder.admin.edit', [
        'bid' => $results->id,
        'destination' => $url,
      ]
      )->toString();
    $output .= "<a class=\"link-edit-blockbuider\" href=\"{$edit_url}\"> Config block builder </a>";
  }
  $output .= gavias_builder_frontend($results->params);
  $results = NULL;
}
$output .= "</div></div>";
return $output;
}

add_shortcode('gbb', 'sc_blockbulider');

/**
 * Sc_image.
 */
function sc_image($attr) {
  extract(shortcode_atts(['src' => ''], $attr));
  $output = '<img src="' . base_path() . $src . '" alt=""/>';
  return $output;
}

add_shortcode('sc_image', 'sc_image');
