<?php

/**
 * @file
 * This is gva - socials.
 */

if (!class_exists('GaviasEnzioElementGvaSocials')) :
  /**
   * Gavias Enzio Element Gva Socials.
   */
  class GaviasEnzioElementGvaSocials {

    /**
     * Render form.
     */
    public function renderForm() {
      $fields = [
        'type' => 'gsc_socials',
        'title' => t('Socials'),
        'fields' => [[
          'id' => 'title',
          'type' => 'text',
          'title' => t('Title for admin'),
          'admin' => TRUE,
        ], [
          'id' => 'style',
          'type' => 'select',
          'options' => ['style-1' => t('Style 1'), 'style-2' => t('Style 2')],
          'title' => t('Style'),
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
      for ($i = 1; $i <= 10; $i++) {
        $fields['fields'][] = [
          'id' => "info_${i}",
          'type' => 'info',
          'desc' => "Information for item {$i}",
        ];
        $fields['fields'][] = [
          'id' => "icon_{$i}",
          'type' => 'text',
          'title' => t("Icon {$i}"),
          'desc' => t('Use class icon font <a target="_blank" href="https://fontawesome.com/v4.7.0/icons/">Icon Awesome</a>'),
        ];
        $fields['fields'][] = [
          'id' => "link_{$i}",
          'type' => 'text',
          'title' => t("Link {$i}"),
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
        'style' => 'style-1',
        'el_class' => '',
        'animate' => '',
        'animate_delay' => '',
      ];

      for ($i = 1; $i <= 10; $i++) {
        $default["icon_{$i}"] = '';
        $default["link_{$i}"] = '';
      }
      extract(gavias_merge_atts($default, $attr));
      $class = [];
      $class[] = $el_class;
      $class[] = $style;
      if ($animate) {
        $class[] = 'wow ' . $animate;
      }
      ob_start();
      ?>
         <div class="widget gsc-socials <?php print implode(' ', $class) ?>" <?php print gavias_content_builder_print_animate_wow('', $animate_delay) ?>>
            <?php for ($i = 1; $i <= 10; $i++) {
              ?>
               <?php $icon = "icon_{$i}";
                $link = "link_{$i}"; ?>
               <?php if ($$icon && $$link) {
                  ?>
                  <a href="<?php print $$link ?>"><i class="<?php print $$icon ?>" /></i></a>
                  <?php
               } ?>
              <?php
            } ?>
         </div>
         <?php return ob_get_clean() ?>
         <?php

    }

  }

endif;
