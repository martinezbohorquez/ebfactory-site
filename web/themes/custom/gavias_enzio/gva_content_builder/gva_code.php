<?php

/**
 * @file
 * This is gva - code.
 */

if (!class_exists('GaviasEnzioElementGvaCode')) :
  /**
   * Gavias Enzio Element Gva Code.
   */
  class GaviasEnzioElementGvaCode {

    /**
     * Render form.
     */
    public function renderForm() {
      $fields = [
        'type' => 'gsc_code',
        'title' => t('Code'),
        'fields' => [[
          'id' => 'content',
          'type' => 'textarea',
          'title' => t('Content'),
        ],
        ],
      ];
      return $fields;
    }

    /**
     * Render content.
     */
    public function renderContent($item) {
      if (!array_key_exists('content', $item['fields'])) {
        $item['fields']['content'] = '';
      }
      print self::scCode($item['fields'], $item['fields']['content']);
    }

    /**
     * Sc code.
     */
    public static function scCode($attr, $content = NULL) {
      $output = '<pre>';
      $output .= do_shortcode($content);
      $output .= '</pre>' . "\n";
      print $output;
    }

    /**
     * Load shortcode.
     */
    public function loadShortcode() {
      add_shortcode('code', [$this,
        'sc_code',
      ]);
    }

  }

endif;
