<?php

/**
 * @file
 * This is gva - chart.
 */

if (!class_exists('GaviasEnzioElementGvaChart')) :
  /**
   * Gavias Enzio Element Gva Chart.
   */
  class GaviasEnzioElementGvaChart {

    /**
     * Render form.
     */
    public function renderForm() {
      $fields = [
        'type' => 'gsc_chart',
        'title' => ('Chart'),
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
          'id' => 'icon',
          'type' => 'text',
          'title' => t('Chart Icon'),
          'desc' => t('Use class icon font <a target="_blank" href="https://fontawesome.com/v4.7.0/icons/">Icon Awesome</a>'),
        ], [
          'id' => 'content',
          'type' => 'textarea',
          'title' => t('Chart Content'),
        ], [
          'id' => 'color',
          'type' => 'text',
          'title' => t('Chart color'),
          'desc' => t('Use color name ( blue ) or hex ( #2991D6 )'),
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
     * Render Content.
     */
    public static function renderContent($attr = [], $content = '') {
      extract(gavias_merge_atts([
        'title'           => '',
        'content'         => '',
        'percent'         => '',
        'label'           => '',
        'icon'            => '',
        'color'           => '',
        'animate'         => '',
        'animate_delay'   => '',
        'el_class'        => '',
      ], $attr));
      if (!$color) {
        $color = '#008FD5';
      }
      if ($animate) {
        $el_class .= ' wow ' . $animate;
      }
      ob_start();
      ?>
    <div class="widget gsc-chart <?php
    print $el_class
     ?>
     " <?php
      print gavias_content_builder_print_animate_wow('', $animate_delay)
      ?>
     >
    <div class="pieChart" data-bar-color="<?php
    print $color
     ?>
     " data-bar-width="150" data-percent="<?php
      print $percent
      ?>
     ">
    <span><?php
    print $percent;
    ?>
     %</span>
    </div>
    <div class="content">
      <?php
      if ($icon) {
        ?>
         
    <div class="icon" <?php
    if ($color) {
      print 'style="color:' . $color . ';"';
    }
    ?>
                      ><i class="<?php
                      print $icon
                       ?>
                       "></i></div>
        <?php
      }
      ?>
    
      <?php
      if ($title) {
        ?>
         
    <div class="title"><span><?php
    print $title;
    ?>
       </span></div>
        <?php
      }
      ?>
    
      <?php
      if ($content) {
        ?>
         
    <div class="content"><?php
    print $content;
    ?>
       </div>
        <?php
      }
      ?>
    
    </div>
    </div>
      <?php return ob_get_clean()
      ?>

      <?php
    }

  }

endif;
