<?php

/**
 * @file
 * This is gva - box color.
 */

if (!class_exists('GaviasEnzioElemenGvaBoxColor')) :
  /**
   * Gavias Enzio Element Gva Box Color.
   */
  class GaviasEnzioElementGvaBoxColor {

    /**
     * Render form.
     */
    public function renderForm() {
      $fields = [
        'type'            => 'gsc_box_color',
        'title'           => t('Box color'),
        'fields' => [
             [
               'id'        => 'title_admin',
               'type'      => 'text',
               'title'     => 'Title Administrator',
               'admin'     => TRUE,
             ],
             [
               'id'        => 'title',
               'type'      => 'textarea',
               'title'     => 'Content',
             ],
             [
               'id'        => 'image',
               'type'      => 'upload',
               'title'     => t('Images'),
             ],
             [
               'id'        => 'link',
               'type'      => 'text',
               'title'     => t('Link'),
             ],
             [
               'id'        => 'text_link',
               'type'      => 'text',
               'title'     => t('Text Link'),
               'std'       => t('Read more'),
             ],
             [
               'id'        => 'color',
               'type'      => 'text',
               'title'     => t('Background color'),
               'desc'      => t('Background color fox box. e.g: #ccc'),
             ],
             [
               'id'        => 'text_style',
               'type'      => 'select',
               'title'     => t('Text Style'),
               'options'   => ['white' => t('White'), 'dark' => t('Dark')],
             ],
             [
               'id'        => 'target',
               'type'      => 'select',
               'title'     => t('Open in new window'),
               'desc'      => t('Adds a target="_blank" attribute to the link'),
               'options'   => [0 => 'No', 1 => 'Yes'],
             ],
             [
               'id'        => 'height',
               'type'      => 'text',
               'title'     => t('Min Height'),
             ],
             [
               'id'        => 'el_class',
               'type'      => 'text',
               'title'     => t('Extra class name'),
               'desc'      => t('Style particular content element differently - add a class name and refer to it in custom CSS.'),
             ],
             [
               'id'        => 'animate',
               'type'      => 'select',
               'title'     => t('Animation'),
               'desc'      => t('Entrance animation for element'),
               'options'   => gavias_content_builder_animate(),
               'class'     => 'width-1-2',
             ],
             [
               'id'        => 'animate_delay',
               'type'      => 'select',
               'title'     => t('Animation Delay'),
               'options'   => gavias_content_builder_delay_aos(),
               'desc'      => '0 = default',
               'class'     => 'width-1-2',
             ],
        ],
      ];
      return $fields;
    }

    /**
     * Render content.
     */
    public function renderContent($item) {
      print self::scBoxColor($item['fields']);
    }

    /**
     * Sc BoxColor.
     */
    public static function scBoxColor($attr, $content = NULL) {
      global $base_url;
      extract(gavias_merge_atts([
        'icon'                  => '',
        'title'                 => '',
        'link'                  => '',
        'text_link'             => 'Read more',
        'color'                 => '',
        'text_style'            => 'white',
        'target'                => '',
        'image'                 => '',
        'height'                => '',
        'el_class'              => '',
        'animate'               => '',
        'animate_delay'         => '',
      ], $attr));

      // Target.
      if ($target) {
        $target = 'target="_blank"';
      }
      else {
        $target = FALSE;
      }

      if ($image) {
        $image = $base_url . $image;
      }

      $el_class .= ' text-' . $text_style;
      $css = '';
      $css .= !empty($color) ? "background-color: {$color};" : "";
      $css .= !empty($height) ? "min-height: {$height};" : "";
      if (!empty($css)) {
        $css = "style=\"{$css}\"";
      }

      if ($animate) {
        $el_class .= ' wow ' . $animate;
      }

      ob_start();
      ?>
    <div class="widget gsc-box-color clearfix <?php print $el_class; ?>" <?php print $css ?> <?php print gavias_content_builder_print_animate_wow('', $animate_delay) ?>>
    <div class="box-content">
      <?php if ($image) {
        ?><div class="image"><img src="<?php print $image ?>"/></div> <?php
      } ?>
    <div class="content-inner">
    <div class="box-title"><?php print $title ?></div>
    <div class="action"><a class="link"
      <?php if ($link) {
        print 'href="' . $link . '"';
      }
      ?> 
      <?php print $target ?>><span class="text"><?php print $text_link ?></span></a></div>
    </div>
    </div>
    </div>
      <?php return ob_get_clean() ?>
      <?php
    }

    /**
     * Load Short code.
     */
    public function loadShortcode() {
      add_shortcode('box_color', ['gsc_box_color', 'sc_box_color']);
    }

  }
endif;
