<?php

/**
 * @file
 * This is gva - text noeditor.
 */

if (!class_exists('GaviasEnzioElementGvaTextNoeditor')) :
  /**
   * Gavias Enzio Element Gva Text Noeditor.
   */
  class GaviasEnzioElementGvaTextNoeditor {

    /**
     * Render form.
     */
    public function renderForm() {
      $fields = [
        'type' => 'gsc_text_noeditor',
        'title' => t('Custom Text Without Editor'),
        'size' => 3,
        'fields' => [[
          'id' => 'title',
          'type' => 'text',
          'title' => t('Title'),
          'admin' => TRUE,
        ], [
          'id' => 'content',
          'type' => 'textarea_without_html',
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
        ], [
          'id' => 'el_class',
          'type' => 'text',
          'title' => t('Extra class name'),
          'desc' => t('Style particular content element differently - add a class name and refer to it in custom CSS.'),
        ],
        ],
      ];
      return $fields;
    }

    /**
     * Render content.
     */
    public static function renderContent($attr = [], $content = '') {
      extract(gavias_merge_atts([
        'title' => '',
        'content' => '',
        'style' => '',
        'el_class' => '',
        'animate' => '',
        'animate_delay'   => '',
      ], $attr));
      $el_class .= ' ' . $style;
      if ($animate) {
        $el_class .= ' wow ' . $animate;
      }
      $ouput = '';
      $ouput .= '<div class="column-content ' . $el_class . '" ' . gavias_content_builder_print_animate_wow('', $animate_delay) . '>';
      $ouput .= ($content);
      $ouput .= '</div>';
      return $ouput;

    }

  }

endif;
