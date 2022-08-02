<?php

/**
 * @file
 * This is gva - videobox.
 */

if (!class_exists('GaviasEnzioElementGvaCounter')) :
  /**
   * Gavias Enzio Element Gva Counter.
   */
  class GaviasEnzioElementGvaCounter {

    /**
     * Render Form.
     */
    public function renderForm() {
      $fields = [
        'type' => 'element_gva_counter',
        'title' => ('Counter'),
        'fields' => [[
          'id' => 'title',
          'title' => t('Title'),
          'type' => 'text',
          'admin' => TRUE,
        ], [
          'id' => 'icon',
          'title' => t('Icon'),
          'type' => 'text',
          'std' => '',
          'desc' => t('Use class icon font <a target="_blank" href="https://fontawesome.com/v4.7.0/icons/">Icon Awesome</a> or <a target="_blank" href="http://gaviasthemes.com/icons/">Custom icon</a>'),
        ], [
          'id' => 'number',
          'title' => t('Number'),
          'type' => 'text',
        ], [
          'id' => 'symbol',
          'title' => t('Symbol'),
          'type' => 'text',
          'desc' => 'e.g %',
        ], [
          'id' => 'content',
          'type' => 'textarea',
          'title' => t('Content'),
        ], [
          'id' => 'type',
          'title' => t('Style'),
          'type' => 'select',
          'options' => [
            'icon-left' => 'Icon left',
            'icon-top' => 'Icon top',
            'icon-top-2' => 'Icon top 2',
          ],
          'std' => 'icon-left',
        ], [
          'id' => 'color',
          'type' => 'text',
          'title' => t('Icon Color'),
          'desc' => t('Use color name ( blue ) or hex ( #2991D6 )'),
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
    public function renderContent($attr = [], $content = '') {
      extract(gavias_merge_atts([
        'title' => '',
        'icon' => '',
        'number' => '',
        'symbol' => '',
        'content' => '',
        'type' => 'icon-top',
        'el_class' => '',
        'style_text' => 'text-dark',
        'color' => '',
        'animate' => '',
        'animate_delay' => '',
      ], $attr));
      $class = [];
      $class[] = $el_class;
      $class[] = 'position-' . $type;
      $class[] = $style_text;
      $style = '';
      if ($color) {
        $style = "color: {$color};";
      }
      if ($style) {
        $style = 'style="' . $style . '"';
      }
      if ($animate) {
        $class[] = 'wow ' . $animate;
      }
      ob_start();
      ?>
         <?php if ($type == 'icon-top-2') {
            ?>
            <div class="widget milestone-block 
            <?php if (count($class) > 0) {
              print implode(' ', $class);
            }
            ?>" 
                     <?php print gavias_content_builder_print_animate_wow('', $animate_delay) ?>>
               <div class="milestone-text"><?php print $title ?></div>
               <?php if ($icon) {
                  ?>
                  <div class="milestone-icon"><span <?php print $style ?> class="<?php print $icon; ?>"></span></div>
                  <?php
               } ?>   
               <div class="milestone-right">
                  <div class="milestone-number-inner" <?php print $style ?>><span class="milestone-number"><?php print $number; ?></span><span class="symbol"><?php print $symbol ?></span></div>
                  <?php if ($content) {
                    ?><div class="milestone-content"><?php print $content; ?></div><?php
                  } ?>
               </div>
            </div>
            <?php
         }
         else {
            ?>
            <div class="widget milestone-block 
            <?php if (count($class) > 0) {
              print implode(' ', $class);
            } ?>"
                        <?php print gavias_content_builder_print_animate_wow('', $animate_delay) ?>>
               <?php if ($icon) {
                  ?>
                  <div class="milestone-icon"><span <?php print $style ?> class="<?php print $icon; ?>"></span></div>
                  <?php
               } ?>   
               <div class="milestone-right">
                  <div class="milestone-number-inner" <?php print $style ?>><span class="milestone-number"><?php print $number; ?></span><span class="symbol"><?php print $symbol ?></span></div>
                  <div class="milestone-text"><?php print $title ?></div>
                  <?php if ($content) {
                    ?><div class="milestone-content">
                     <?php print $content; ?></div><?php
                  } ?>
               </div>
            </div>
            <?php
         } ?>   
         <?php return ob_get_clean() ?>
         <?php

    }

  }
endif;
