<?php

/**
 * @file
 * This is form.
 */

/**
 * Gavias sliderlayer delete.
 */
function gavias_sliderlayer_delete($gid) {
  return drupal_get_form('gavias_sliderlayer_delete_confirm_form');
}

/**
 * Gavias sliderlayer delete confirm form.
 */
function gavias_sliderlayer_delete_confirm_form($form_state) {
  $form = [];
  $form['id'] = [
    '#type' => 'hidden',
    '#default_value' => arg(2),
  ];
  return confirm_form($form, 'Do you really want to detele this block bulider ?', 'admin/gavias_sliderlayer', NULL, 'Delete', 'Cancel');
}

/**
 * Gavias sliderlayer delete confirm form submit.
 */
function gavias_sliderlayer_delete_confirm_form_submit($form, &$form_state) {
  $gid = $form['id']['#value'];
  \Drupal::database()->delete('gavias_sliderlayer')
    ->condition('id', $gid)
    ->execute();
  \Drupal::messenger()->addMessage('The block bulider has been deleted');
  drupal_goto('admin/gavias_sliderlayer');
}

/**
 * Gavias sliderlayer export.
 */
function gavias_sliderlayer_export($gid) {
  $pbd_single = gavias_sliderlayer_load($gid);
  $data = $pbd_single->params;
  header("Content-Type: text/txt");
  header("Content-Disposition: attachment; filename=gavias_sliderlayer_export.txt");
  print $data;
  exit;
}

/**
 * Gavias sliderlayer import.
 */
function gavias_sliderlayer_import($bid) {
  $bid = arg(2);
  if (is_numeric($bid)) {
    $bblock = \Drupal::database()->select('{gavias_sliderlayer}', 'd')
      ->fields('d')
      ->condition('id', $bid, '=')
      ->execute()
      ->fetchAssoc();
  }
  else {
    $bblock = ['id' => 0, 'title' => ''];
  }

  if ($bblock['id'] == 0) {
    \Drupal::messenger()->addMessage('Not found gavias slider !');
    return FALSE;
  }

  $form = [];
  $form['id'] = [
    '#type' => 'hidden',
    '#default_value' => $bblock['id'],
  ];
  $form['params'] = [
    '#type' => 'textarea',
    '#title' => 'Past code import for block builder "' . $bblock['title'] . '"',
    '#default_value' => '',
  ];
  $form['submit'] = [
    '#type' => 'submit',
    '#value' => 'Save',
  ];
  return $form;
}

/**
 * Gavias sliderlayer import submit.
 */
function gavias_sliderlayer_import_submit($form, $form_state) {
  if ($form['id']['#value']) {
    $id = $form['id']['#value'];
    \Drupal::database()->update("gavias_sliderlayer")
      ->fields([
        'params' => $form['params']['#value'],
      ])
      ->condition('id', $id)
      ->execute();
    drupal_goto('admin/gavias_sliderlayer/' . $id . '/edit');
    \Drupal::messenger()->addMessage("Block Builder '{$form['title']['#value']}' has been updated");
  }
}
