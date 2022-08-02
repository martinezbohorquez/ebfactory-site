<?php

/**
 * @file
 * This is gva - heading.
 */

if (!class_exists('GaviasEnzioElementGvaHeading')) :
  /**
   * Element gva heading.
   */
  class GaviasEnzioElementGvaHeading {

    /**
     * Render form.
     */
    public function renderForm() {
      $fields = [
        'type' => 'gsc_heading',
        'title' => t('Heading'),
        'fields' => [[
          'id' => 'title',
          'type' => 'text',
          'title' => t('Title'),
          'admin' => TRUE,
        ], [
          'id' => 'sub_title',
          'type' => 'text',
          'title' => t('Sub Title'),
        ], [
          'id' => 'desc',
          'type' => 'textarea',
          'title' => t('Description'),
        ], [
          'id' => 'icon',
          'type' => 'text',
          'title' => t('Icon for heading'),
          'desc' => t('Use class icon font <a target="_blank" href="https://fontawesome.com/v4.7.0/icons/">Icon Awesome</a>'),
        ], [
          'id' => 'align',
          'type' => 'select',
          'title' => t('Align text for heading'),
          'options' => [
            'align-center' => 'Align Center',
            'align-left' => 'Align Left',
            'align-right' => 'Align Right',
          ],
          'std' => 'align-center',
        ], [
          'id' => 'style',
          'type' => 'select',
          'title' => t('Style display'),
          'options' => [
            'style-1' => 'Style v1',
            'style-2' => 'Style v2',
          ],
        ], [
          'id' => 'html_tags',
          'type' => 'select',
          'title' => t('Html Title Tags'),
          'options' => [
            'h1' => 'H1',
            'h2' => 'H2',
            'h3' => 'H3',
            'h4' => 'H4',
            'h5' => 'H5',
            'h6' => 'H6',
          ],
          'default' => 'h2',
        ], [
          'id' => 'style_text',
          'type' => 'select',
          'title' => t('Skin Text for box'),
          'options' => [
            'text-dark' => 'Text dark',
            'text-light' => 'Text light',
          ],
        ], [
          'id' => 'remove_padding',
          'type' => 'select',
          'title' => t('Remove Padding'),
          'options' => [
            '' => 'Default',
            'padding-top-0' => 'Remove padding top',
            'padding-bottom-0' => 'Remove padding bottom',
            'padding-bottom-0 padding-top-0' => 'Remove padding top & bottom',
          ],
          'std' => '',
          'desc' => 'Default heading padding top & bottom: 30px',
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
    public static function renderContent($attr = [], $content = '') {
      extract(gavias_merge_atts([
        'title' => '',
        'desc' => '',
        'sub_title' => '',
        'align' => '',
        'style' => 'style-1',
        'html_tags' => 'h2',
        'icon' => '',
        'style_text' => 'text-dark',
        'el_class' => '',
        'remove_padding' => '',
        'animate' => '',
        'animate_delay' => '',
      ], $attr));
      $class = [];
      $class[] = $el_class;
      $class[] = $align;
      $class[] = $style;
      $class[] = $style_text;
      $class[] = $remove_padding;
      if ($animate) {
        $class[] = 'wow ' . $animate;
      }
      ob_start();
      ?>
         <div class="widget gsc-heading <?php print implode(' ', $class) ?>" <?php print gavias_content_builder_print_animate_wow('', $animate_delay) ?>>
            <?php if ($sub_title) {
              ?><div class="sub-title"><span><?php print $sub_title; ?></span></div><?php
            } ?>
            <?php if ($title) {
              ?><<?php echo $html_tags ?> class="title"><span><?php print $title; ?></span></<?php echo $html_tags ?>><?php
            } ?>
            <?php if ($icon) {
              ?><div class="title-icon"><span><i class="<?php print $icon ?>"></i></span></div><?php
            } ?> 
            <?php if ($desc) {
              ?><div class="title-desc"><?php print $desc; ?></div><?php
            } ?>
         </div>
         <div class="clearfix"></div>
         <?php return ob_get_clean() ?>
         <?php

    }

  }

endif;
