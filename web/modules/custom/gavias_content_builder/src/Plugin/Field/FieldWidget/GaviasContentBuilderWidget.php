<?php

namespace Drupal\gavias_content_builder\Plugin\Field\FieldWidget;

use Drupal\Core\Render\Markup;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Gavias Content Builder Widget.
 */
class GaviasContentBuilderWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $element = parent::settingsForm($form, $form_state);

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {

    $field_settings = $this->getFieldSettings();
    $results = \Drupal::database()->select('{gavias_content_builder}', 'd')
      ->fields('d', ['id', 'title'])
      ->orderBy('title', 'ASC')
      ->execute();

    $list_gcb = ['' => 'Disable'];
    foreach ($results as $key => $result) {
      $list_gcb[$result->id] = $result->title;
    }

    if (isset($form['#parents'][0]) && $form['#parents'][0] == 'default_value_input' && !empty($element['#field_parents'][0] && $element['#field_parents'][0] == 'default_value_input')) {
      $element['bid'] = [
        '#type' => 'hidden',
        '#default_value' => 0,
      ];

      $element['default'] = [
        '#type' => 'select',
        '#title' => $this->t('Default'),
        '#default_value' => $items[$delta]->default,
        '#options' => $list_gcb,
      ];
      return $element;

    }
    $parent_entity = $items->getEntity();

    $user = \Drupal::currentUser();
    // Show field via roles.
    $role_field_gcb = $field_settings['role_field_gcb'] ?? [];
    $role_field_gcb = array_filter($role_field_gcb);

    $flag_use_role = FALSE;
    if ($role_field_gcb) {
      $use_roles = $user->getRoles();
      if ($role_field_gcb) {
        foreach ($role_field_gcb as $key => $role) {
          if ($role && in_array($role, $use_roles)) {
            $flag_use_role = TRUE;
          }
        }
      }
    }
    else {
      $flag_use_role = TRUE;
    }
    // Print $flag_use_role;.
    if (!$user->hasPermission('administer gavias_content_builder')) {
      return;
    }

    // $langcode = $items->getLangcode();
    // $field_name = $items->getName();
    // $input = $form_state->getUserInput();
    $results = \Drupal::database()->select('{gavias_content_builder}', 'd')
      ->fields('d', ['id', 'title'])
      ->condition('use_field', 1)
      ->orderBy('title', 'ASC')
      ->execute();

    $list_builder = ['' => 'Disable'];
    foreach ($results as $key => $result) {
      $list_builder[$result->id] = $result->title;
    }

    $bid = !empty($items[$delta]->bid) ? $items[$delta]->bid : $items[$delta]->default;

    $random = gavias_content_builder_makeid(10);

    // if($flag_use_role){
    // $element['addform'] = array(
    // '#type' => 'linkfield',
    // '#title' => t('<strong>Add New Builder</strong>'),
    // '#url' => Url::fromRoute('gavias_content_builder.admin.add_popup',
    // array('random'=>$random))->toString(),
    // '#attributes' => array(
    // 'class' => array('use-ajax'),
    // 'data-dialog-type' => 'modal',
    // 'data-dialog-options' =>  json_encode(array(
    // 'resizable' => TRUE,
    // 'width' => '80%',
    // 'height' => 'auto',
    // 'max-width' => '1100px',
    // 'modal' => TRUE,
    // )),
    // 'title' => t('Add new builder'),
    // ),
    // );
    // }.
    if ($flag_use_role) {

      $link_html = '<a href="' . Url::fromRoute('gavias_content_builder.admin.add_popup', ['random' => $random])->toString() . '" class="use-ajax" data-dialog-type="modal" data-dialog-options="{&quot;width&quot;:&quot;600px&quot;,&quot;modal&quot;:true}" data-drupal-selector="edit-field-content-builder-0-addform" id="edit-field-content-builder-0-addform">';
      $link_html .= '<strong>Add New Builder</strong>';
      $link_html .= '</a>';

      $element['addform'] = [
        '#type' => 'markup',
        '#markup' => Markup::create($link_html),
        '#weight' => -11,
      ];

    };

    $element['bid'] = [
      '#title' => $items->getFieldDefinition()->getLabel() . (' <a class="gva-popup-iframe" href="' . Url::fromRoute('gavias_content_builder.admin', ['gva_iframe' => 'on'])->toString() . '">Manage All Blockbuilders</a>'),
      '#type' => 'textfield',
      '#default_value' => $bid,
      '#attributes' => [
        'class' => [
          'field_gavias_content_builder',
          'gva-id-' . $random,
        ],
        'data-random' => $random,
        'readonly' => 'readonly',
      ],
    ];
    if ($flag_use_role) {
      $element['bid']['#title'] = $items->getFieldDefinition()->getLabel() . (' <a class="gva-popup-iframe" href="' . Url::fromRoute('gavias_content_builder.admin', ['gva_iframe' => 'on'])->toString() . '">Manage All Blockbuilders</a>');
    }
    else {
      $element['bid']['#title'] = $items->getFieldDefinition()->getLabel();
    }

    if ($flag_use_role) {
      $element['choose_gbb'] = [
        '#type' => 'markup',
        '#markup' => $this->getListBlockBuilder($random),
        '#allowed_tags' => ['a', 'div', 'span'],
      ];
    }
    return $element;
  }

  /**
   * Get list blockbuilder.
   */
  public function getListBlockBuilder($random) {
    $results = \Drupal::database()->select('{gavias_content_builder}', 'd')
      ->fields('d', ['id', 'title', 'machine_name'])
      ->orderBy('title', 'ASC')
      ->execute();
    $html = '<div class="gva-choose-gbb gva-id-' . $random . '">';
    $html .= '<span class="gbb-item disable"><a class="select" data-id="" title="disable">Disable</a></span>';
    foreach ($results as $key => $result) {
      $html .= '<span class="gbb-item id-' . $result->id . '">';
      $html .= '<a class="select" data-id="' . $result->id . '" title="' . $result->machine_name . '">' . $list_builder[$result->id] = $result->title . '</a>';
      $html .= ' <span class="action">( <a class="edit gva-popup-iframe" href="' . Url::fromRoute('gavias_content_builder.admin.edit', ['bid' => $result->id])->toString() . '?gva_iframe=on" data-id="' . $result->id . '" title="' . $result->machine_name . '">Edit</a>';
      $html .= ' | <a class="duplicate use-ajax"
      data-dialog-type="modal"
      data-dialog-options="{
        "resizable":true,
        "width":"80%",
        "height":"auto",
        "max-width":"1100px",
        "modal":true}"
        href="' . Url::fromRoute(
          'gavias_content_builder.admin.duplicate_popup',
        [
          'bid' => $result->id,
          'random' => $random,
        ]
        )->toString() . '" data-id="' . $result->id . '" 
        title="' . $result->machine_name . '
        ">Duplicate</a>';
      $html .= ' | <a class="import use-ajax" data-dialog-type="modal" data-dialog-options="{"resizable":true,
        "width":"80%",
        "height":"auto",
        "max-width":"1100px",
        "modal":true}" href="' . Url::fromRoute('gavias_content_builder.admin.import_popup', ['bid' => $result->id])->toString() . '" data-id="' . $result->id . '" title="' . $result->machine_name . '">Import</a> ';
      $html .= ' | <a class="export" href="' . Url::fromRoute('gavias_content_builder.admin.export', ['bid' => $result->id])->toString() . '" data-id="' . $result->id . '" title="' . $result->machine_name . '">Export</a>';
      $html .= ' | <a class="delete use-ajax" data-dialog-type="modal" data-dialog-options="{"resizable":true,
        "width":"80%",
        "height":"auto",
        "max-width":"1100px",
        "modal":true}" href="' .
        Url::fromRoute('gavias_content_builder.admin.delete_popup',
        [
          'bid' => $result->id,
          'random' => $random,
        ]
        )->toString() . '" data-id="' . $result->id . '" 
        title="' . $result->machine_name . '">Delete</a> )
        </span>';
      $html .= '</span>';
    }
    $html .= '</div>';
    return $html;
  }

}
