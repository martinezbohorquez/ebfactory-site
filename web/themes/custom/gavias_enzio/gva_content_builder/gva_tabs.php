<?php

/**
 * @file
 * This is gva tabs.
 */

if (!class_exists('gaviasEnzioElementGvaTabs')) {
  /**
   * GaviasEnzio - element gva tabs.
   */
  class GaviasEnzioElementGvaTabs {

    /**
     * Render form.
     */
    public function renderForm() {
      $fields = [
        'type' => 'gsc_tabs',
        'title' => t('Tabs'),
        'fields' => [[
          'id' => 'title',
          'type' => 'text',
          'title' => t('Title for admin'),
          'admin' => TRUE,
        ], [
          'id' => 'type',
          'type' => 'select',
          'options' => [
            'horizontal' => 'Horizontal',
            'horizontal_icon' => 'Horizontal Icon',
            'vertical' => 'Vertical',
          ],
          'title' => t('Style'),
          'desc' => t('Vertical tabs works only for column widths: 1/2, 3/4 & 1/1'),
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
      for ($i = 1; $i <= 8; $i++) {
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
        'uid' => 'tab-',
        'type' => '',
        'el_class' => '',
        'animate' => '',
        'animate_delay' => '',
      ];
      for ($i = 1; $i <= 8; $i++) {
        $default["title_{$i}"] = '';
        $default["content_{$i}"] = '';
      }
      extract(gavias_merge_atts($default, $attr));
      $_id = gavias_content_builder_makeid();
      $uid .= $_id;
      if ($animate) {
        $el_class .= ' wow' . $animate;
      }
      ob_start() ?>
         <div class="gsc-tabs <?php print $el_class ?>" <?php print gavias_content_builder_print_animate_wow('', $animate_delay) ?>>
            <div class="tabs_wrapper tabs_<?php print $type ?>">
               <ul class="nav nav-tabs">
                  <?php for ($i = 1; $i <= 8; $i++) {
                    ?>
                     <?php
                      $title = "title_{$i}";
                      $content = "content_{$i}";
                      ?>
                     <?php if ($$title) {
                        ?>
                        <li <?php print($i == 1 ? 'class="active"' : '') ?>><a data-toggle="tab" href="#<?php print ($uid . '-' . $i) ?>">  <?php print $$title ?> </a></li>
                        <?php
                     } ?>
                    <?php
                  } ?>
               </ul>
               <div class="tab-content">
                  <?php for ($i = 1; $i <= 8; $i++) {
                    ?>
                     <?php
                      $title = "title_{$i}";
                      $content = "content_{$i}";
                      ?>
                     <?php if ($$title) {
                        ?>
                        <div id="<?php print($uid . '-' . $i) ?>" class="tab-pane fade in <?php print($i == 1 ? 'active' : '') ?>"><?php print $$content ?></div>
                        <?php
                     } ?>
                    <?php
                  } ?>
               </div>
            </div>
         </div>
         <?php return ob_get_clean();
    }

  }
}
