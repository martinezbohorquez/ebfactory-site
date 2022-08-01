<?php

/**
 * @file
 * This is menu.
 */

use Drupal\Core\Menu\MenuLinkInterface;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;

/**
 * Gavias enzio preprocess menu _main.
 */
function gavias_enzio_preprocess_menu__main(&$variables) {
  $variables['attributes']['class'][] = 'clearfix';
  foreach ($variables['items'] as &$item) {
    $menu_link_attributes = _gavias_enzio_attributes_get_attributes($item['original_link']);
    if (count($menu_link_attributes)) {
      $url_attributes = $item['url']->getOption('attributes') ?: [];
      $attributes = array_merge($url_attributes, $menu_link_attributes);
      $item['url']->setOption('attributes', $attributes);
      $item['gva_block_content'] = '';
      $item['attributes']['gva_class'] = (isset($attributes['gva_class']) && $attributes['gva_class']) ? trim($attributes['gva_class']) : '';
      $item['attributes']['gva_icon'] = (isset($attributes['gva_icon']) && $attributes['gva_icon']) ? trim($attributes['gva_icon']) : '';
      $item['attributes']['gva_layout'] = (isset($attributes['gva_layout']) && $attributes['gva_layout']) ? $attributes['gva_layout'] : '';
      $item['attributes']['gva_layout_columns'] = (isset($attributes['gva_layout_columns']) && $attributes['gva_layout_columns']) ? $attributes['gva_layout_columns'] : 4;
      $item['attributes']['gva_block'] = (isset($attributes['gva_block']) && $attributes['gva_block']) ? $attributes['gva_block'] : '';
      if (isset($attributes['gva_layout']) && $attributes['gva_layout'] == 'menu-block') {
        $item['gva_block_content'] = gavias_enzio_render_block($attributes['gva_block']);
      }
    }
  }
}

/**
 * Gavias enzio attributes get attributes.
 */
function _gavias_enzio_attributes_get_attributes(MenuLinkInterface $menu_link_content_plugin) {
  $attributes = [];
  try {
    $plugin_id = $menu_link_content_plugin->getPluginId();
  }
  catch (PluginNotFoundException $e) {
    return $attributes;
  }
  if (strpos($plugin_id, ':') === FALSE) {
    return $attributes;
  }
  [$entity_type, $uuid] = explode(':', $plugin_id, 2);
  if ($entity_type == 'menu_link_content') {
    $entity = \Drupal::entityTypeManager()->getStorage('menu_link_content')->loadByProperties(['uuid' => $uuid]);
    if (count($entity)) {
      $entity_values = array_values($entity)[0];
      $options = $entity_values->link->first()->options;
      $attributes = $options['attributes'] ?? [];
    }
  }
  return $attributes;
}
