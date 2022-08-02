<?php

/**
 * @file
 * This is gva-button.
 */

if (!class_exists('GaviasEnzioElementGvaButton')) :
  /**
   * Element gva button.
   */
  class GaviasEnzioElementGvaButton {

    /**
     * Gsc button id.
     */
    public static function gscButtonId($length = 12) {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
      $randomString = '';
      for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
      }
      return $randomString;
    }

    /**
     * Render form.
     */
    public function renderForm() {
      $fields = [
        'type' => 'gsc_button',
        'title' => ('Button'),
        'size' => 3,
        'fields' => [[
          'id' => 'title',
          'type' => 'text',
          'title' => t('Title'),
          'admin' => TRUE,
        ], [
          'id' => 'size',
          'type' => 'select',
          'title' => t('Size'),
          'options' => [
            'mini' => 'Mini',
            'small' => 'Small',
            'medium' => 'Medium',
            'large' => 'Large',
            'extra-large' => 'Extra Large',
          ],
        ], [
          'id' => 'color',
          'type' => 'text',
          'title' => t('Text color'),
          'desc' => 'Sample: #ccc',
          'std' => '#000',
        ], [
          'id' => 'border_color',
          'type' => 'text',
          'title' => t('Border Color'),
          'std' => '#000',
        ], [
          'id' => 'background_color',
          'type' => 'text',
          'title' => t('Background Color'),
          'std' => '',
        ], [
          'id' => 'border_radius',
          'type' => 'select',
          'title' => t('Border radius'),
          'options' => [
            '' => 'None',
            'radius-2x' => 'Border radius 2x',
            'radius-5x' => 'Border radius 5x',
          ],
        ], [
          'id' => 'link',
          'type' => 'text',
          'title' => t('Link'),
        ], [
          'id' => 'color_hover',
          'type' => 'text',
          'title' => t('Text Color Hover'),
          'desc' => 'Sample: #ccc',
        ], [
          'id' => 'border_color_hover',
          'type' => 'text',
          'title' => t('Border Color Hover'),
        ], [
          'id' => 'background_color_hover',
          'type' => 'text',
          'title' => t('Background Color Hover'),
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
        'content' => '',
        'title' => 'Read more',
        'size' => 'mini',
        'color' => '#000',
        'border_color' => '#000',
        'background_color' => '',
        'border_radius' => '',
        'link' => '',
        'background_color_hover' => '',
        'color_hover' => '',
        'border_color_hover' => '',
        'animate' => '',
        'animate_delay' => '',
        'el_class' => '',
      ], $attr));
      $_id = 'button-' . self::gscButtonId(12);

      $classes = [];
      $classes[] = "{$el_class} ";

      if ($border_radius) {
        $classes[] = "{$border_radius} ";
      }

      $classes[] = " {$size} ";

      $styles = [];
      if ($background_color) {
        $styles[] = "background:{$background_color};";
      }
      if ($color) {
        $styles[] = "color:{$color};";
      }
      if ($border_color) {
        $styles[] = "border-color:{$border_color};";
      }

      $styles_hover = [];
      if ($background_color_hover) {
        $styles_hover[] = "background:{$background_color_hover};";
      }
      if ($color_hover) {
        $styles_hover[] = "color:{$color_hover};";
      }
      if ($border_color_hover) {
        $styles_hover[] = "border-color:{$border_color_hover};";
      }

      if ($animate) {
        $classes[] = 'wow ' . $animate;
      }
      ob_start();
      ?>

         <style rel="stylesheet">
            <?php print "#{$_id}{" . implode('', $styles) . "}" ?>
            <?php print "#{$_id}:hover{" . implode('', $styles_hover) . "}" ?>
         </style>

         <div class="clearfix"></div>
         <a href="<?php print $link ?>" class="gsc-button <?php print implode('', $classes) ?>" id="<?php print $_id; ?>" <?php print gavias_content_builder_print_animate_wow('', $animate_delay) ?>>
            <?php print $title ?>
         </a> 

         <?php return ob_get_clean() ?>

         <?php

    }

  }

endif;
