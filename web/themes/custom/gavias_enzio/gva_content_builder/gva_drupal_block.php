<?php

/**
 * @file
 * This is gva - drupal block.
 */

if (!class_exists('GaviasEnzioElementGvaDrupalBlock')) :
  /**
   * Gavias Enzio Element Gva Drupal Block.
   */
  class GaviasEnzioElementGvaDrupalBlock {

    /**
     * Render form.
     */
    public function renderForm() {
      $fields = [
        'type' => 'gsc_drupal_block',
        'title' => ('Drupal Block'),
        'fields' => [[
          'id' => 'title_admin',
          'type' => 'text',
          'title' => t('Administrator Title'),
          'admin' => TRUE,
        ], [
          'id' => 'block_drupal',
          'type' => 'select',
          'title' => t('Block for drupal'),
          'options' => gavias_content_builder_get_blocks_options(),
          'class' => 'change_value_admin',
        ], [
          'id' => 'hidden_title',
          'type' => 'select',
          'title' => t('Hidden title'),
          'options' => [
            'on' => 'Display',
            'off' => 'Hidden',
          ],
          'desc' => t('Hidden title default for block'),
        ], [
          'id' => 'align_title',
          'type' => 'select',
          'title' => t('Align title'),
          'options' => [
            'title-align-left' => 'Align Left',
            'title-align-right' => 'Align Right',
            'title-align-center' => 'Align Center',
          ],
          'std' => 'title-align-center',
          'desc' => t('Align title default for block'),
        ], [
          'id' => 'remove_margin',
          'type' => 'select',
          'title' => ('Remove Margin'),
          'options' => [
            'on' => 'Yes',
            'off' => 'No',
          ],
          'std' => 'off',
          'desc' => t('Defaut block margin bottom 30px, You can remove margin for block'),
        ], [
          'id' => 'style_text',
          'type' => 'select',
          'title' => t('Skin Text for box'),
          'options' => [
            'text-dark' => 'Text dark',
            'text-light' => 'Text light',
          ],
          'std' => 'text-dark',
        ], [
          'id' => 'el_class',
          'type' => 'text',
          'title' => t('Extra class name'),
          'desc' => t('Style particular content element differently - add a class name and refer to it in custom CSS.'),
        ], [
          'id' => 'animate',
          'type' => 'select',
          'title' => t('Animation'),
          'desc' => t('Entrance animation for element'),
          'options' => gavias_content_builder_animate(),
          'class' => 'width-1-2',
        ], [
          'id' => 'animate_delay',
          'type' => 'select',
          'title' => t('Animation Delay'),
          'options' => gavias_content_builder_delay_aos(),
          'desc' => '0 = default',
          'class' => 'width-1-2',
        ],
        ],
      ];
      return $fields;
    }

    /**
     * Render content.
     */
    public function renderContent($settings = [], $content = '') {
      extract(gavias_merge_atts([
        'title' => '',
        'block_drupal' => '',
        'hidden_title' => 'on',
        'align_title' => 'title-align-center',
        'el_class' => '',
        'style_text' => '',
        'remove_margin' => 'off',
        'animate' => '',
        'animate_delay' => '',
      ], $settings));
      $output = '';
      $class = [];
      $class[] = $align_title;
      $class[] = $el_class;
      $class[] = 'hidden-title-' . $hidden_title;
      $class[] = 'remove-margin-' . $remove_margin;
      $class[] = $style_text;
      if ($animate) {
        $class[] = 'wow ' . $animate;
      }

      if ($block_drupal) {
        $output .= '<div class=" clearfix widget gsc-block-drupal ' . implode(' ', $class) . '" ' . gavias_content_builder_print_animate_wow('', $animate_delay) . '>';
        $output .= gavias_content_builder_render_block($block_drupal);
        $output .= '</div>';
      }
      return $output;
    }

  }

endif;
