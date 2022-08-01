<?php

/**
 * @file
 * This is Theme-settings.
 */

/**
 * Implementation of hook_form_system_theme_settings_alter()
 *
 * @param string $form
 *   Nested array of form elements that comprise the form.
 * @param string $form_state
 *   A keyed array containing the current state of the form.
 */
function gavias_enzio_form_system_theme_settings_alter(&$form, &$form_state) {
  $form['#attached']['library'][] = 'gavias_enzio/gavias-enzio-admin';
  // Get the build info for the form.
  // $build_info = $form_state->getBuildInfo();
  // Get the theme name we are editing.
  // $theme = \Drupal::theme()->getActiveTheme()->getName();
  // Create Omega Settings Object.
  $form['core'] = [
    '#type' => 'vertical_tabs',
    '#attributes' => ['class' => ['entity-meta']],
    '#weight' => -899,
  ];

  $form['theme_settings']['#group'] = 'core';
  $form['logo']['#group'] = 'core';
  $form['favicon']['#group'] = 'core';

  $form['theme_settings']['#open'] = FALSE;
  $form['logo']['#open'] = FALSE;
  $form['favicon']['#open'] = FALSE;

  // Custom settings in Vertical Tabs container.
  $form['options'] = [
    '#type' => 'vertical_tabs',
    '#attributes' => ['class' => ['entity-meta']],
    '#weight' => -999,
    '#default_tab' => 'edit-variables',
    '#states' => [
      'invisible' => [
        ':input[name="force_subtheme_creation"]' => ['checked' => TRUE],
      ],
    ],
  ];

  /* --------- Setting general ----------------*/
  $form['general'] = [
    '#type' => 'details',
    '#attributes' => [],
    '#title' => t('Gerenal options'),
    '#weight' => -999,
    '#group' => 'options',
    '#open' => FALSE,
  ];

  $form['general']['sticky_menu'] = [
    '#type' => 'select',
    '#title' => t('Enable Sticky Menu'),
    '#default_value' => theme_get_setting('sticky_menu'),
    '#group' => 'general',
    '#options' => [
      '0'        => t('Disable'),
      '1'        => t('Enable'),
    ],
  ];

  $form['general']['site_layout'] = [
    '#type' => 'select',
    '#title' => t('Body Layout'),
    '#default_value' => theme_get_setting('site_layout'),
    '#options' => [
      'wide' => t('Wide (default)'),
      'boxed' => t('Boxed'),
    ],
  ];

  $form['general']['preloader'] = [
    '#type' => 'select',
    '#title' => t('Preloader'),
    '#default_value' => theme_get_setting('preloader'),
    '#group' => 'options',
    '#options' => [
      '0' => t('Disable'),
      '1' => t('Enable'),
    ],
  ];

  /*--------- Setting Header ------------ */
  $form['header'] = [
    '#type' => 'details',
    '#attributes' => [],
    '#title' => t('Header options'),
    '#weight' => -998,
    '#group' => 'options',
    '#open' => FALSE,
  ];

  $form['header']['default_header'] = [
    '#type' => 'select',
    '#title' => t('Setting default header'),
    '#default_value' => theme_get_setting('default_header'),
    '#options' => [
      'header' => t('header default'),
      'header-1' => t('Header v1'),
      'header-2' => t('header v2'),
      'header-3' => t('Header v3'),
      'header-4' => t('Header v4'),
    ],
  ];

  // User CSS.
  $form['options']['css_customize'] = [
    '#type' => 'details',
    '#attributes' => [],
    '#title' => t('Customize css'),
    '#weight' => -996,
    '#group' => 'options',
    '#open' => TRUE,
  ];

  /*--------- Setting Footer ------------ */
  $form['footer'] = [
    '#type' => 'details',
    '#attributes' => [],
    '#title' => t('Footer options'),
    '#weight' => -998,
    '#group' => 'options',
    '#open' => FALSE,
  ];

  $form['footer']['footer_skin'] = [
    '#type' => 'select',
    '#title' => t('Footer Skin'),
    '#default_value' => theme_get_setting('footer_skin'),
    '#group' => 'footer',
    '#options' => [
      ''              => t('Footer Default'),
      'footer-v2'     => t('Footer gray'),
      'footer-v3'     => t('Footer white'),
    ],
  ];
  $form['footer']['footer_first_size'] = [
    '#type' => 'select',
    '#title' => t('Footer First Size'),
    '#default_value' => theme_get_setting('footer_first_size') ? theme_get_setting('footer_first_size') : 3,
    '#options' => [0 => $this->t('Hidden'), 1, 2, 3, 4, 5, 6, 7, 8, 9, 10 , 11, 12],
    '#description' => 'Setting width for grid boostrap / 12',
  ];

  $form['footer']['footer_second_size'] = [
    '#type' => 'select',
    '#title' => t('Footer Second Size'),
    '#default_value' => theme_get_setting('footer_second_size') ? theme_get_setting('footer_second_size') : 3,
    '#options' => [0 => $this->t('Hidden'), 1, 2, 3, 4, 5, 6, 7, 8, 9, 10 , 11, 12],
    '#description' => 'Setting width for grid boostrap / 12',
  ];

  $form['footer']['footer_third_size'] = [
    '#type' => 'select',
    '#title' => t('Footer Third Size'),
    '#default_value' => theme_get_setting('footer_third_size') ? theme_get_setting('footer_third_size') : 3,
    '#options' => [0 => $this->t('Hidden'), 1, 2, 3, 4, 5, 6, 7, 8, 9, 10 , 11, 12],
    '#description' => 'Setting width for grid boostrap / 12',
  ];

  $form['footer']['footer_four_size'] = [
    '#type' => 'select',
    '#title' => t('Footer Four Size'),
    '#default_value' => theme_get_setting('footer_four_size') ? theme_get_setting('footer_four_size') : 3,
    '#options' => [0 => $this->t('Hidden'), 1, 2, 3, 4, 5, 6, 7, 8, 9, 10 , 11, 12],
    '#description' => 'Setting width for grid boostrap / 12',
  ];

  // User CSS --------------------------------------.
  $form['options']['css_customize'] = [
    '#type' => 'details',
    '#attributes' => [],
    '#title' => t('Customize css'),
    '#weight' => -996,
    '#group' => 'options',
    '#open' => TRUE,
  ];
  $form['customize']['customize_css'] = [
    '#type' => 'textarea',
    '#title' => t('Add your own CSS'),
    '#group' => 'css_customize',
    '#attributes' => ['class' => ['code_css']],
    '#default_value' => theme_get_setting('customize_css'),
  ];

  // Customize color ----------------------------------.
  $form['options']['settings_customize'] = [
    '#type' => 'details',
    '#attributes' => [],
    '#title' => t('Settings Customize'),
    '#weight' => -997,
    '#group' => 'options',
    '#open' => TRUE,
  ];

  $form['options']['settings_customize']['settings'] = [
    '#type' => 'details',
    '#open' => TRUE,
    '#attributes' => [],
    '#title' => t('Cutomize Setting'),
    '#weight' => -999,
  ];

  $form['options']['settings_customize']['settings']['theme_skin'] = [
    '#type' => 'select',
    '#title' => t('Theme Skin'),
    '#default_value' => theme_get_setting('theme_skin'),
    '#group' => 'settings',
    '#options' => [
      ''            => t('Default'),
      'blue'        => t('Blue'),
      'brown'       => t('Brown'),
      'green'       => t('Green'),
      'lilac'       => t('Lilac'),
      'lime_green'  => t('Lime Green'),
      'orange'      => t('Orange'),
      'pink'        => t('Pink'),
      'purple'      => t('Purple'),
      'red'         => t('Red'),
      'turquoise'   => t('Turquoise'),
      'turquoise2'  => t('Turquoise2'),
      'violet_red'  => t('Violet Red'),
      'violet_red2' => t('Violet Red2'),
      'yellow'      => t('Yellow'),
    ],
  ];

  $form['options']['settings_customize']['settings']['enable_customize'] = [
    '#type' => 'select',
    '#title' => t('Enable Display Cpanel Customize'),
    '#default_value' => theme_get_setting('enable_customize'),
    '#group' => 'settings',
    '#options' => [
      '0'        => t('Disable'),
      '1'        => t('Enable'),
    ],
  ];

  $form['actions']['submit']['#value'] = t('Save');
}
