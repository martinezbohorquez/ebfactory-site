<?php

/**
 * @file
 * This is gva - call to action.
 */

if (!class_exists('GaviasEnzioElementGvaCallToAction')) :
  /**
   * Gavias Enzio Element Gva Call To Action.
   */
  class GaviasEnzioElementGvaCallToAction {

    /**
     * Render form.
     */
    public function render_form() {
      $fields = [
        'type' => 'gsc_call_to_action',
        'title' => t('Call to Action'),
        'fields' => [[
          'id' => 'title',
          'type' => 'text',
          'title' => t('Title'),
          'admin' => TRUE,
        ], [
          'id' => 'content',
          'type' => 'textarea',
          'title' => t('Content'),
          'desc' => t('HTML tags allowed.'),
        ], [
          'id' => 'link',
          'type' => 'text',
          'title' => t('Link'),
        ], [
          'id' => 'button_title',
          'type' => 'text',
          'title' => t('Button Title'),
          'desc' => t('Leave this field blank if you want Call to Action with Big Icon'),
        ], [
          'id' => 'button_align',
          'type' => 'select',
          'title' => 'Style',
          'options' => [
            'button-left' => t('Button Left'),
            'button-right' => t('Button Right'),
            'button-right-v2' => t('Button Right 2'),
            'button-bottom' => t('Button Bottom Left'),
            'button-center' => t('Button Bottom Center'),
          ],
        ], [
          'id' => 'box_background',
          'type' => 'text',
          'title' => t('Box Background'),
          'desc' => t('Box Background, e.g: #f5f5f5'),
        ], [
          'id' => 'width',
          'type' => 'text',
          'title' => t('Max width for content'),
          'desc' => 'e.g 660px',
        ], [
          'id' => 'style_text',
          'type' => 'select',
          'title' => 'Skin Text for box',
          'options' => [
            'text-light' => 'Text light',
            'text-dark' => 'Text dark',
          ],
          'std' => 'text-dark',
        ], [
          'id' => 'style_button',
          'type' => 'select',
          'title' => 'Style button',
          'options' => [
            'btn-theme' => 'Button default of theme',
            'btn-white' => 'Button white',
          ],
          'std' => 'text-dark',
        ], [
          'id' => 'target',
          'type' => 'select',
          'title' => t('Open in new window'),
          'desc' => t('Adds a target="_blank" attribute to the link'),
          'options' => [
            'off' => 'Off',
            'on' => 'On',
          ],
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
    public function render_content($attr = [], $content = '') {
      extract(gavias_merge_atts([
        'title'           => '',
        'subtitle'        => '',
        'link'            => '',
        'content'         => '',
        'button_title'    => '',
        'button_align'    => '',
        'width'           => '',
        'style_button'    => 'btn-theme',
        'target'          => '',
        'el_class'        => '',
        'animate'         => '',
        'animate_delay'   => '',
        'style_text'      => 'text-dark',
        'box_background'  => '',
        'video'           => '',
      ], $attr));

      // Target.
      if ($target == 'on') {
        $target = 'target="_blank"';
      }
      else {
        $target = FALSE;
      }

      $class = [];
      $class[] = $el_class;
      $class[] = $button_align;
      $class[] = $style_text;
      if ($animate) {
        $class[] = 'wow ' . $animate;
      }
      if ($box_background) {
        $class[] = 'has-background';
      }

      $style = '';
      if ($width) {
        $style .= "max-width: {$width};";
      }
      if ($box_background) {
        $style .= "background: {$box_background};";
      }
      $style = !empty($style) ? "style=\"" . $style . "\"" : '';
      ob_start();
      ?>

      <div class="widget gsc-call-to-action <?php print implode(' ', $class) ?>" <?php print $style ?> <?php print gavias_content_builder_print_animate_wow('', $animate_delay) ?>>
      <div class="content-inner clearfix" >
      <div class="content">
      <h2 class="title"><span><?php print $title; ?></span></h2>
      <div class="desc"><?php print $content; ?></div>
      </div>
      <?php if ($link) {?>
      <div class="button-action">
      <a href="<?php print $link ?>" class="<?php print $style_button ?>" <?php print $target ?>><?php print $button_title ?></a>   
      </div>
      
                  <?php return ob_get_clean() ?>
                  <?php
      }

    }
  }
  endif;
