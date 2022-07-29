<?php

namespace Drupal\gavias_content_builder\Plugin\Field\FieldType;

use Drupal\user\Entity\Role;
use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;
use Drupal\Core\Form\FormStateInterface;

/**
 * Gavias Content Builder.
 */
class GaviasContentBuilder extends FieldItemBase {

  /**
   * Default Field Settings.
   */
  public static function defaultFieldSettings() {
    return [
      'role_field_gcb' => [],
    ] + parent::defaultFieldSettings();
  }

  /**
   * Field Settings Form.
   */
  public function fieldSettingsForm(array $form, FormStateInterface $form_state) {
    $element = [];
    $settings = $this->getSettings();
    $tmp_roles = Role::loadMultiple();
    $roles = [];
    foreach ($tmp_roles as $key => $role) {
      $roles[$key] = $role->get('label');
    }
    $element['role_field_gcb'] = [
      '#type'          => 'checkboxes',
      '#title'         => $this->t('Roles'),
      '#default_value' => $settings['role_field_gcb'] ?? [],
      '#options'       => $roles,
      '#description'   => 'When the user has the following roles',
    ];
    return $element;
  }

  /**
   * Property Definitions.
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['bid'] = DataDefinition::create('integer')
      ->setLabel(t('Gavias Builder ID'))
      ->setDescription(t('A Builder ID referenced the Gavias Builder'));
    return $properties;
  }

  /**
   * Schema.
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    $columns = [
      'bid' => [
        'description' => 'The Block buider ID being referenced in this field.',
        'type' => 'int',
        'unsigned' => TRUE,
      ],
    ];

    $schema = [
      'columns' => $columns,
      'indexes' => [
        'bid' => ['bid'],
      ],
    ];

    return $schema;
  }

  /**
   * Is Empty.
   */
  public function isEmpty() {
    $value = $this->get('bid')->getValue();
    return $value === NULL || $value === '';
  }

}
