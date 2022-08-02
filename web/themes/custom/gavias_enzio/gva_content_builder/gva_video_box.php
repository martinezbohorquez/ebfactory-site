<?php

/**
 * @file
 * This is gva - video.
 */

if (!class_exists('GaviasEnzioElementGvaVideoBox')) :
  /**
   * Element gva videobox.
   */
  class GaviasEnzioElementGvaVideoBox {

    /**
     * Render form.
     */
    public function renderForm() {
      $fields = [
        'type' => 'gsc_video_box',
        'title' => ('Video Box'),
        'size' => 3,
        'fields' => [[
          'id' => 'title',
          'type' => 'text',
          'title' => t('Administrator Title'),
          'admin' => TRUE,
        ], [
          'id' => 'content',
          'type' => 'text',
          'title' => t('Data Url'),
          'desc' => t('example: https://www.youtube.com/watch?v=4g7zRxRN1Xk'),
        ], [
          'id' => 'image',
          'type' => 'upload',
          'title' => t('Image Preview'),
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
    public static function renderContent($attr, $content = NULL) {
      global $base_url;
      extract(gavias_merge_atts([
        'title' => '',
        'content' => '',
        'image' => '',
        'link' => '',
        'animate' => '',
        'animate_delay' => '',
        'el_class' => '',
      ], $attr));
      //$_id = gavias_content_builder_makeid();
      if ($image) {
        $image = $base_url . $image;
      }
      if ($animate) {
        $el_class .= ' wow ' . $animate;
      }
      ob_start();
      ?>
      
         <div class="widget gsc-video-box <?php print $el_class;?> style-1 clearfix" <?php print gavias_content_builder_print_animate_wow('', $animate_delay) ?>>
            <div class="video-inner">
               <div class="image text-center"><img src="<?php print $image ?>" alt="<?php print $title ?>"/></div>
               <div class="video-body">
                  <a class="popup-video gsc-video-link" href="<?php print $content ?>">
                     <i class="fa icon-play space-40"></i>
                  </a>
               </div> 
            </div>    
         </div> 
      
      <?php return ob_get_clean() ?>
       <?php

    }

  }

endif;
