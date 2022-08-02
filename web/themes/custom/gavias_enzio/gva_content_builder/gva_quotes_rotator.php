<?php

/**
 * @file
 * This is gva - quotes rotator.
 */

if (!class_exists('GaviasEnzioElementGvaQuotesRotator')) :
  /**
   * Gavias Enzio Element Gva Quotes Rotator.
   */
  class GaviasEnzioElementGvaQuotesRotator {

    /**
     * Render form.
     */
    public function renderForm() {
      $fields = [
      // 'key'      => 'gva_quotes_rotator',
        'title' => t('Quotes Rotator'),
        'fields' => [[
          'id' => 'title',
          'type' => 'text',
          'title' => t('Title'),
          'admin' => TRUE,
        ],
         [
           'id' => 'skin_text',
           'type' => 'select',
           'title' => 'Skin Text for box',
           'options' => [
             'text-dark' => t('Text Dark'),
             'text-light' => t('Text Light'),
           ],
         ], [
           'id' => 'max_width',
           'type' => 'text',
           'title' => t('Max Width'),
           'desc' => 'e.g: 600px',
         ], [
           'id' => 'min_height',
           'type' => 'text',
           'title' => t('Min Height'),
           'desc' => 'e.g: 200px',
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
      for ($i = 1; $i <= 10; $i++) {
        $fields['fields'][] = [
          'id' => "info_${i}",
          'type' => 'info',
          'desc' => "Information for item {$i}",
        ];
        $fields['fields'][] = [
          'id' => "title_{$i}",
          'type' => 'text',
          'title' => t("Title {$i}"),
        ];
        $fields['fields'][] = [
          'id' => "content_{$i}",
          'type' => 'textarea',
          'title' => t("Content {$i}"),
        ];
      }
      return $fields;
    }

    /**
     * Render content.
     */
    public static function renderContent($attr = [], $content = '') {
      $default = [
        'title' => '',
        'skin_text' => 'text-dark',
        'max_width' => '',
        'min_height' => '',
        'animate' => '',
        'animate_delay' => '',
        'el_class' => '',
      ];
      for ($i = 1; $i <= 10; $i++) {
        $default["title_{$i}"] = '';
        $default["content_{$i}"] = '';
      }
      extract(gavias_merge_atts($default, $attr));

      $style = '';
      if ($max_width) {
        $style .= "max-width:{$max_width};";
      }
      if ($min_height) {
        $style .= "min-height:{$min_height};";
      }
      if ($style) {
        $style = " style=\"{$style}\"";
      }
      if ($animate) {
        $el_class .= ' wow ' . $animate;
      }
      ob_start();
      ?>
         <div class="gsc-quotes-rotator <?php print $skin_text ?> <?php print $el_class ?>" <?php print gavias_content_builder_print_animate_wow('', $animate_delay) ?>>
            <div class="cbp-qtrotator"<?php print $style ?>>
              <?php for ($i = 1; $i <= 10; $i++) { ?>
                  <?php
                  $title = "title_{$i}";
                  $content = "content_{$i}";
                  ?>
                  <?php if ($$title) { ?>
                     <div class="cbp-qtcontent">
                        <div class="content-title"><?php print $$title ?></div>
                        <div class="content-inner"><?php print $$content ?></div>
                     </div>
                  <?php } ?>   
              <?php } ?>  
            </div>
         </div>   
         <?php  return ob_get_clean() ?>
      <?php

    }

  }


endif;
