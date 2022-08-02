<?php

/**
 * @file
 * This is gva - progress.
 */

if (!class_exists('GaviasEnzioElementGvaProgress')) :
  /**
   *
   *
   */
  class GaviasEnzioElementGvaProgress {

    /**
     * Render form.
     */
    public function renderForm() {
      $fields = [
        'type' => 'gsc_progress',
        'title' => t('Progress'),
        'fields' => [[
          'id' => 'title',
          'type' => 'text',
          'title' => t('Title'),
          'admin' => TRUE,
        ], [
          'id' => 'percent',
          'type' => 'text',
          'title' => t('Percent'),
          'desc' => t('Number between 0-100'),
        ], [
          'id' => 'background',
          'type' => 'text',
          'title' => t('Background Color'),
          'desc' => 'Background color for progress',
        ], [
          'id' => 'skin_text',
          'type' => 'select',
          'title' => 'Skin Text for box',
          'options' => ['text-light' => t('Text Light'), 'text-dark' => t('Text Dark')],
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
        'title' => '',
        'percent' => '',
        'background' => '',
        'skin_text' => '',
        'animate' => '',
        'animate_delay'   => '',
        'el_class'        => '',
      ], $attr));
      $style = '';
      if ($background) {
        $style = 'style="background-color: ' . $background . '"';
      }
      $class_array = [];
      $class_array[] = $el_class;
      $class_array[] = $skin_text;
      if ($animate) {
        $class_array[] = 'wow ' . $animate;
      }
      ob_start();
      ?>
         <div class="widget gsc-progress<?php if (count($class_array)) {
            print (' ' . implode(' ', $class_array));
                                        } ?>"
                                         <?php print gavias_content_builder_print_animate_wow('', $animate_delay) ?>>
            <div class="progress-label">
               <?php print $title ?>
            </div>
            <div class="progress">
               <div class="progress-bar" 
               <?php if ($style) {
                  print $style;
               } ?> 
                                         data-progress-animation="<?php print $percent ?>%">
                                         <span></span>
                                       </div>
               <span class="percentage">
                  <span></span>
                  <?php print $percent ?>%
               </span>
            </div>
         </div>   
         <?php return ob_get_clean() ?>
      <?php

    }

  }

endif;
