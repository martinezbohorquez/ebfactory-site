<?php

/**
 * @file
 * This is gva - quote text.
 */

if (!class_exists('GaviasEnzioElementGvaQuoteText')) :
  /**
   * Gavias Enzio Element Gva Quote Text.
   */
  class GaviasEnzioElementGvaQuoteText {

    /**
     * Render form.
     */
    public function renderForm() {
      $fields = [
        'type' => 'gsc_quote_text',
        'title' => ('Box Quote Text'),
        'size' => 3,
        'fields' => [[
          'id' => 'title',
          'type' => 'text',
          'title' => t('Title for Admin'),
          'admin' => TRUE,
        ], [
          'id' => 'content',
          'type' => 'textarea',
          'title' => t('Content'),
        ], [
          'id' => 'width',
          'type' => 'text',
          'title' => t('Width'),
          'desc' => 'Sample: 80%',
        ], [
          'id' => 'background',
          'type' => 'text',
          'title' => t('Background color'),
          'desc' => 'Sample: #f5f5f5',
        ], [
          'id' => 'color',
          'type' => 'text',
          'title' => t('Text color'),
          'desc' => 'Sample: #ccc',
        ], [
          'id' => 'border',
          'type' => 'select',
          'title' => t('border'),
          'options' => [
            'has-border' => 'Enable',
            'no-border' => 'Disble',
          ],
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
        'width' => '',
        'background' => '',
        'color' => '',
        'border' => '',
        'animate' => '',
        'animate_delay' => '',
        'el_class' => '',
      ], $attr));
      $el_class .= ' ' . $border;
      $styles = [];
      if ($width) {
        $styles[] = "width:{$width};";
      }
      if ($color) {
        $styles[] = "color:{$color};";
      }
      if ($animate) {
        $el_class .= ' wow ' . $animate;
      }
      ob_start();
      ?>
            <div class="widget gsc-quote-text <?php print $el_class ?>" <?php print ($background ? "style=\"background:{$background};\"" : '') ?> <?php print gavias_content_builder_print_animate_wow('', $animate_delay) ?>>
               <div class="widget-content">
                  <div class="content" style="<?php print(implode('', $styles)) ?>"><i <?php print ($color ? "style=\"color:{$color};\"" : '') ?> class="icon fa fa-quote-left"></i><?php print $content ?></div>
               </div>
            </div>  
            <?php return ob_get_clean() ?>    
         <?php
    }

  }

endif;
