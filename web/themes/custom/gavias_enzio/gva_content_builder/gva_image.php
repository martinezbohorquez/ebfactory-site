<?php

/**
 * @file
 * This is gva - image.
 */

if (!class_exists('GaviasEnzioElementGvaImage')) :
  /**
   * Gavias Enzio Element Gva Image.
   */
  class GaviasEnzioElementGvaImage {

    /**
     * Render form.
     */
    public function renderForm() {
      $fields = [
        'type' => 'gsc_image',
        'title' => ('Image'),
        'size' => 3,
        'fields' => [
             [
               'id'        => 'image',
               'type'      => 'upload',
               'title'     => t('Image'),
             ],
             [
               'id'        => 'align',
               'type'      => 'select',
               'title'     => t('Align Image'),
               'options'   => [
                 ''          => 'None',
                 'left'      => 'Left',
                 'right'     => 'Right',
                 'center'    => 'Center',
               ],
             ],
             [
               'id'     => 'margin',
               'type'      => 'text',
               'title'  => t('Margin Top'),
               'desc'      => t('example: 30px'),
             ],
             [
               'id'     => 'alt',
               'type'      => 'text',
               'title'  => t('Alternate Text'),
             ],
             [
               'id'     => 'link',
               'type'      => 'text',
               'title'  => t('Link'),
             ],
             [
               'id'     => 'target',
               'type'      => 'select',
               'options'   => ['off' => 'No', 'on' => 'Yes'],
               'title'  => t('Open in new window'),
               'desc'      => t('Adds a target="_blank" attribute to the link.'),
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
             [
               'id'        => 'el_class',
               'type'      => 'text',
               'title'     => t('Extra class name'),
               'desc'      => t('Style particular content element differently - add a class name and refer to it in custom CSS.'),
             ],
        ],
      ];
      return $fields;
    }

    /**
     * Render content.
     */
    public static function renderContent($attr, $content = NULL) {
      global $base_url;
      extract(gavias_merge_atts([
        'image'           => '',
        'border'          => 'off',
        'alt'             => '',
        'margin'          => '',
        'align'           => 'none',
        'link'            => '',
        'target'          => 'off',
        'animate'         => '',
        'animate_delay'   => '',
        'el_class'        => '',
      ], $attr));

      $image = $base_url . $image;

      if ($align) {
        $align = 'text-' . $align;
      }

      if ($target == 'on') {
        $target = 'target="_blank"';
      }
      else {
        $target = '';
      }

      if ($margin) {
        $margin = 'style="margin-top:' . intval($margin) . 'px"';
      }
      else {
        $margin = '';
      }

      $class_array = [];
      $class_array[] = $align;
      $class_array[] = $el_class;
      if ($animate) {
        $class_array[] = 'wow ' . $animate;
      }
      ob_start();
      ?>
    <div class="widget gsc-image
      <?php if (count($class_array) > 0) {
        print (' ' . implode(' ', $class_array));
      } ?>" 
      <?php print $margin ?> <?php print gavias_content_builder_print_animate_wow('', $animate_delay) ?>>
    <div class="widget-content">
      <?php if ($link) { ?>
    <a href="<?php print $link ?>" <?php print $target ?>>
      <?php } ?>
    <img src="<?php print $image ?>" alt="<?php print $alt ?>" />
      <?php if ($link) {
        print '</a>';
      } ?>
    </div>
    </div>
      <?php return ob_get_clean() ?>
      <?php
    }

  }
endif;
