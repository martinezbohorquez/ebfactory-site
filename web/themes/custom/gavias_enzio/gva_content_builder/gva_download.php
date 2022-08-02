<?php

/**
 * @file
 * This is gva - download.
 */

if (!class_exists('GaviasEnzioElementGvaDownload')) :
  /**
   * Gavias enzio element gva download.
   */
  class GaviasEnzioElementGvaDownload {

    /**
     * Render form.
     */
    public function renderForm() {
      $fields = [
        'type' => 'gsc_download',
        'title' => t('Download box'),
        'size' => 3,
        'fields' => [[
          'id' => 'title',
          'type' => 'text',
          'title' => t('Title'),
          'admin' => TRUE,
        ], [
          'id' => 'content',
          'type' => 'textarea',
          'title' => t('Content'),
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
        ], [
          'id' => 'style',
          'type' => 'select',
          'options' => [
            'vertical' => t('Vertical'),
            'horizontal' => t('Horizontal'),
          ],
          'title' => t('Style'),
        ],
        ],
      ];

      for ($i = 1; $i <= 5; $i++) {
        $fields['fields'][] = [
          'id' => "info_${i}",
          'type' => 'info',
          'desc' => "Information for item file {$i}",
        ];
        $fields['fields'][] = [
          'id' => "name_${i}",
          'type' => 'text',
          'title' => "File Name {$i}",
        ];
        $fields['fields'][] = [
          'id' => "link_{$i}",
          'type' => 'text',
          'title' => t("File Link Download {$i}"),
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
        'el_class' => '',
        'animate' => '',
        'animate_delay' => '',
        'content' => '',
        'style' => 'vertical',
      ];

      for ($i = 1; $i <= 10; $i++) {
        $default["name_{$i}"] = '';
        $default["link_{$i}"] = '';
      }

      extract(shortcode_atts($default, $attr));
      $el_class .= ' ' . $style;
      // $_id = gavias_content_builder_makeid();
      if ($animate) {
        $el_class .= ' wow ' . $animate;
      }
      ob_start();
      ?>
            <div class="gsc-box-download <?php echo $el_class ?>" <?php print gavias_content_builder_print_animate_wow('', $animate_delay) ?>> 
               <div class="box-content">
                  <div class="info">
                     <?php if ($title) {
                        ?>
                        <div class="title"><?php print $title ?></div>
                        <?php
                     } ?>
                      <?php if ($content) {
                        ?>
                        <div class="desc"><?php print $content ?></div>
                        <?php
                      } ?>
                  </div>
                  <div class="box-files">
                  <?php for ($i = 1; $i <= 10; $i++) {
                    ?>
                     <?php
                      $name = "name_{$i}";
                      $link = "link_{$i}";
                      ?>
                     <?php if ($$name) {
                        ?>
                        <div class="item">
                          <div class="file">
                              <a href="<?php print $$link ?>"><?php print $$name ?></a></div>
                        </div>
                        <?php
                     } ?>    
                    <?php
                  } ?>
                  </div>
               </div> 
           
         <?php return ob_get_clean();
    }

  }

endif;
