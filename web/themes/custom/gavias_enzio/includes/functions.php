<?php

/**
 * @file
 * This is functions.
 */

use Symfony\Cmf\Component\Routing\RouteObjectInterface;
use Drupal\block\Entity\Block;

/**
 * Gavias_enzio_render_block.
 */
gavias_enzio_render_block($key) {
$block = Block::load($key);
if ($block) {
  $block_content = \Drupal::entityManager()->getViewBuilder('block')->view($block);
  return drupal_render($block_content);
}
return '';
}

/**
 * Gavias enzio makeid.
 */
function gavias_enzio_makeid($length = 5) {
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
    $randomString .= $characters[rand(0, strlen($characters) - 1)];
  }
  return $randomString;
}

/**
 * Scrape_insta_hash.
 */
function scrape_insta_hash($tag) {
  $insta_source = file_get_contents('https://www.instagram.com/' . trim($tag));
  // Instagrame tag url.
  $shards = explode('window._sharedData = ', $insta_source);
  $insta_json = explode(';</script>', $shards[1]);
  $insta_array = json_decode($insta_json[0], TRUE);
  return $insta_array;
  // This return a lot things print it and see what else you need.
}

/**
 * Gavias enzio base url.
 */
function gavias_enzio_base_url() {
  global $base_url;
  $theme_path = drupal_get_path('theme', 'gavias_enzio');
  return $base_url . '/' . $theme_path . '/';
}

/**
 * Gavias enzio preprocess node.
 */
function gavias_enzio_preprocess_node(&$variables) {
  $date = $variables['node']->getCreatedTime();
  $variables['date'] = date('j', $date) . ' ' . $this->t(date('F', $date)) . ' ' . date('Y', $date);
  if ($variables['teaser'] || !empty($variables['content']['comments']['comment_form'])) {
    unset($variables['content']['links']['comment']['#links']['comment-add']);
  }
  if ($variables['node']->getType() == 'article') {
    $node = $variables['node'];
    $variables['comment_count'] = $node->get('comment')->comment_count;
    $post_format = 'standard';
    try {
      $field_post_format = $node->get('field_post_format');
      if (isset($field_post_format->value) && $field_post_format->value) {
        $post_format = $field_post_format->value;
      }
    }
    catch (Exception $e) {
      $post_format = 'standard';
    }

    $iframe = '';
    if ($post_format == 'video' || $post_format == 'audio') {
      try {
        $field_post_embed = $node->get('field_post_embed');
        if (isset($field_post_embed->value) && $field_post_embed->value) {
          $autoembed = new AutoEmbed();
          $iframe = $autoembed->parse($field_post_embed->value);
        }
        else {
          $iframe = '';
          $post_format = 'standard';
        }
      }
      catch (Exception $e) {
        $post_format = 'standard';
      }
    }
    $variables['gva_iframe'] = $iframe;
    $variables['post_format'] = $post_format;
  }
}

/**
 * Gavias enzio preprocess node _portfolio.
 */
function gavias_enzio_preprocess_node__portfolio(&$variables) {
  $node = $variables['node'];
  // Override lesson list on single course.
  $output = '';
  $count_information = 0;
  if ($node->hasField('field_portfolio_information')) {
    $informations = $node->get('field_portfolio_information');
    $count_information = count($informations);
    foreach ($informations as $key => $information) {
      $texts = preg_split('/--/', $information->value);
      $information_text = '';
      foreach ($texts as $k => $text) {
        $information_text .= '<span>' . $text . '</span>';
      }
      $output .= '<div class="item-information">' . $information_text . '</div>';
    }
  }
  $variables['count_information'] = $count_information;
  $variables['informations'] = $output;
}

/**
 * Gavias enzio preprocess node _event.
 */
function gavias_enzio_preprocess_node__event(&$variables) {
  $node = $variables['node'];
  $event_date = [];
  if ($node->hasField('field_event_start')) {
    $event_start = $node->field_event_start->value;
    if ($event_start) {
      $event_date['day'] = \Drupal::service('date.formatter')->format(strtotime($event_start), 'custom', 'd');
      $event_date['month'] = \Drupal::service('date.formatter')->format(strtotime($event_start), 'custom', 'F');
    }
  }
  $variables['event_date'] = $event_date;
}

/**
 * Gavias enzio preprocess breadcrumb.
 */
function gavias_enzio_preprocess_breadcrumb(&$variables) {
  $variables['#cache']['max-age'] = 0;
  $request = \Drupal::request();
  $title = '';
  if ($route = $request->attributes->get(RouteObjectInterface::ROUTE_OBJECT)) {
    $title = \Drupal::service('title_resolver')->getTitle($request, $route);
  }

  if ($variables['breadcrumb']) {
    foreach ($variables['breadcrumb'] as $key => &$value) {
      if ($value['text'] == 'Node') {
        unset($variables['breadcrumb'][$key]);
      }
    }
    if ($node = \Drupal::routeMatch()->getParameter('node')) {
      if ($node->getType() == 'article') {
        $variables['breadcrumb'][] = ['text' => ''];
        $variables['breadcrumb'][] = ['text' => 'Article'];
      }
      if ($node->getType() == 'course') {
        $variables['breadcrumb'][] = ['text' => ''];
        $variables['breadcrumb'][] = ['text' => 'Course'];
      }
      if ($node->getType() == 'event') {
        $variables['breadcrumb'][] = ['text' => ''];
        $variables['breadcrumb'][] = ['text' => 'Event'];
      }
      if ($node->getType() == 'portfolio') {
        $variables['breadcrumb'][] = ['text' => ''];
        $variables['breadcrumb'][] = ['text' => 'Portfolio'];
      }
    }
    else {
      if (!empty($title)) {
        $variables['breadcrumb'][] = ['text' => ''];
        $variables['breadcrumb'][] = ['text' => $title];
      }
    }
  }
}
