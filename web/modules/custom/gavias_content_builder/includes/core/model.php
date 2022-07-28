<?php

/**
 * @file
 * This is model.
 */

/**
 * Gavias content builder load.
 */
function gavias_content_builder_load($pid) {
  $result = \Drupal::database()->select('{gavias_content_builder}', 'd')
    ->fields('d')
    ->condition('id', $pid, '=')
    ->execute()
    ->fetchObject();
  $page = new stdClass();
  if ($result) {
    $page->title = $result->title;
    $page->id = $result->id;
    $page->params = $result->params;
  }
  else {
    $page->title = '';
    $page->params = [];
  }
  return $page;
}

/**
 * Gavias content builder load by machine.
 */
function gavias_content_builder_load_by_machine($mid) {
  $result = \Drupal::database()->select('{gavias_content_builder}', 'd')
    ->fields('d', ['id', 'title', 'params'])
    ->condition('body_class', $mid, '=')
    ->execute()
    ->fetchObject();
  $page = new stdClass();
  if ($result) {
    $page->id = $result->id;
    $page->title = $result->title;
    $page->params = $result->params;
  }
  else {
    return FALSE;
  }
  $result = NULL;
  return $page;
}

/**
 * Gavias content builder check machine.
 */
function gavias_content_builder_check_machine($id, $mid) {
  $result = \Drupal::database()->select('{gavias_content_builder}', 'd')
    ->fields('d')
    ->condition('id', $id, '<>')
    ->condition('body_class', $mid, '=')
    ->execute()
    ->fetchObject();
  if ($result && $result->body_class) {
    return TRUE;
  }
  return FALSE;
}

/**
 * Gavias_content_builder_get_list.
 */
function gavias_content_builder_get_list() {
  $result = \Drupal::database()->select('{gavias_content_builder}', 'd')
    ->fields('d')
    ->execute();
  return $result;
}
