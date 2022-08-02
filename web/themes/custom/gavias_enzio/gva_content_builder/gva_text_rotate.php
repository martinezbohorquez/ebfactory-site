<?php

/**
 * @file
 * This is gva - text rotate.
 */

if (!class_exists('GaviasEnzioElementGvaTextRotate')) :
  /**
   * Gavias Enzio Element Gva Text Rotate.
   */
  class GaviasEnzioElementGvaTextRotate {

    /**
     * Render form.
     */
    public function renderForm() {
      $fields = [
        'type' => 'gsc_icon_box',
        'title' => ('Text Rotate'),
        'fields' => [[
          'id' => 'title',
          'type' => 'text',
          'title' => t('Administrator Title'),
          'admin' => TRUE,
        ], [
          'id' => 'content',
          'type' => 'textarea_without_html',
          'title' => t('Content'),
          'default' => 'Enzio is <span id="typer" class="typer" data-typer-targets="Incredibly, Especially, Extremely, Incredibly"></span> and fully responsive.',
        ], [
          'id' => 'desc',
          'type' => 'textarea',
          'title' => t('Desciption'),
        ], [
          'id' => 'link',
          'type' => 'text',
          'title' => t('Link'),
          'desc' => t('Link for text'),
        ], [
          'id' => 'text_link',
          'type' => 'text',
          'title' => t('Text Link'),
          'default' => 'Read more',
        ], [
          'id' => 'align',
          'type' => 'select',
          'title' => 'Align',
          'options' => ['left' => t('Left'), 'center' => t('Center')],
          'default' => 'center',
        ], [
          'id' => 'skin_text',
          'type' => 'select',
          'title' => 'Skin Text for box',
          'options' => [
            'text-dark' => t('Text Dark'),
            'text-light' => t('Text Light'),
          ],
        ], [
          'id' => 'target',
          'type' => 'select',
          'options' => [
            'off' => 'No',
            'on' => 'Yes',
          ],
          'title' => t('Open in new window'),
          'desc' => t('Adds a target="_blank" attribute to the link.'),
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
        'content' => '',
        'desc' => '',
        'link' => '',
        'text_link' => 'Read more',
        'align' => 'center',
        'skin_text' => '',
        'target' => '',
        'animate' => '',
        'animate_delay'      => '',
        'el_class'           => '',
      ], $attr));
      // Target.
      if ($target == 'on') {
        $target = ' target="_blank"';
      }
      else {
        $target = FALSE;
      }

      $class = [];
      if ($el_class) {
        $class[] = $el_class;
      }
      if ($animate) {
        $class[] = 'wow ' . $animate;
      }

      ob_start();
      ?>
            <div class="widget gsc-text-rotate <?php print $el_class ?> align-<?php print $align ?>" <?php print gavias_content_builder_print_animate_wow('', $animate_delay) ?>>
               <div class="rotate-text">
                  <div class="primary-text"><?php print $content ?></div>
                  <div class="second-text"><?php print $desc ?></div>
                  <?php if ($link) { ?>
                     <div class="link"><a<?php print $target ?> class="btn-theme" href="<?php print $link ?>"><?php print $text_link ?></a></div>
                  <?php } ?>   
               </div>
            </div>
       
         <?php return ob_get_clean() ?>
       <?php

    }

  }

endif;
