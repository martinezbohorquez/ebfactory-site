<?php

/**
 * @file
 * This is gva - accordion.
 */

if (!class_exists('GaviasEnzioElementGvaAccordion')) :
  /**
   * Gavias Enzio Element Gva Accordion.
   */
  class GaviasEnzioElementGvaAccordion {

    /**
     * Render form.
     */
    public function renderForm() {
      $fields = [
        'type' => 'gsc_accordion',
        'title' => t('Accordion'),
        'fields' => [[
          'id' => 'title',
          'type' => 'text',
          'title' => t('Title'),
          'admin' => TRUE,
        ], [
          'id' => 'style',
          'type' => 'select',
          'title' => t('Style'),
          'options' => [
            'skin-white' => 'Background White',
            'skin-dark' => 'Background Dark',
            'skin-white-border' => 'Background White Border',
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
        'style' => '',
        'animate' => '',
        'animate_delay' => '',
        'el_class' => '',
      ];
      for ($i = 1; $i <= 10; $i++) {
        $default["title_{$i}"] = '';
        $default["content_{$i}"] = '';
      }
      extract(gavias_merge_atts($default, $attr));
      $_id = 'accordion-' . gavias_content_builder_makeid();
      $classes = $style;
      if ($el_class) {
        $classes .= ' ' . $el_class;
      }

      if ($animate) {
        $classes .= ' wow ' . $animate;
      }
      ob_start();
      ?>

    <div class="gsc-accordion<?php print $el_class ?>" <?php print gavias_content_builder_print_animate_wow('', $animate_delay) ?>>
    <div class="panel-group <?php print $classes ?>" id="<?php print $_id; ?>" role="tablist" aria-multiselectable="true">
      <?php for ($i = 1; $i <= 10; $i++) {
        ?>
        <?php
        $title = "title_{$i}";
        $content = "content_{$i}";
        ?>
        <?php if ($$title) {
          ?>
    <div class="panel panel-default">
    <div class="panel-heading" role="tab">
    <h4 class="panel-title">
    <a role="button" data-toggle="collapse" class="<?php print ($i == 1) ? '' : 'collapsed' ?>" data-parent="#<?php print $_id; ?>" href="#<?php print ($_id . '-' . $i) ?>" aria-expanded="true">
          <?php print $$title ?>
    </a>
    </h4>
    </div>
    <div id="<?php print ($_id . '-' . $i) ?>" class="panel-collapse collapse
          <?php if ($i == 1) {
            print ' in';
          } ?>
             " role="tabpanel">
    <div class="panel-body">
          <?php print $$content ?>
    </div>
    </div>
    </div>
          <?php
        } ?>
        <?php
      } ?>
    </div>
    </div>
      <?php  return ob_get_clean() ?>
      <?php
    }

  }


endif;
