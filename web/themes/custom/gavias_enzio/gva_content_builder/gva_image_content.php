<?php

/**
 * @file
 * This is gva - image content.
 */

if (!class_exists('GaviasEnzioElementGvaImageContent')) :
  /**
   * GaviasvEnzio Element Gva Image Content.
   */
  class GaviasEnzioElementGvaImageContent {

    /**
     * Render form.
     */
    public function renderForm() {
      return [
        'type' => 'gsc_image_content',
        'title' => t('Image content'),
        'fields' => [[
          'id' => 'title',
          'type' => 'text',
          'title' => t('Title'),
          'admin' => TRUE,
        ], [
          'id' => 'background',
          'type' => 'upload',
          'title' => t('Images'),
        ], [
          'id' => 'content',
          'type' => 'textarea',
          'title' => t('Content'),
          'desc' => t('Some HTML tags allowed'),
        ], [
          'id' => 'link',
          'type' => 'text',
          'title' => t('Link'),
        ], [
          'id' => 'text_link',
          'type' => 'text',
          'title' => t('Text Link'),
        ], [
          'id' => 'target',
          'type' => 'select',
          'title' => t('Open in new window'),
          'desc' => t('Adds a target="_blank" attribute to the link'),
          'options' => [
            'off' => 'No',
            'on' => 'Yes',
          ],
          'std' => 'on',
        ], [
          'id' => 'skin',
          'type' => 'select',
          'title' => t('Skin'),
          'options' => [
            'skin-v1' => t('Skin 1'),
            'skin-v2' => t('Skin 2'),
            'skin-v3' => t('Skin 3'),
          ],
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
    }

    /**
     * Render content.
     */
    public static function renderContent($attr = [], $content = '') {
      global $base_url;
      extract(gavias_merge_atts([
        'title'              => '',
        'content'            => '',
        'background'         => '',
        'link'               => '',
        'text_link'          => 'Read more',
        'target'             => '',
        'skin'               => 'skin-v1',
        'el_class'           => '',
        'animate'            => '',
        'animate_delay'      => '',
      ], $attr));
      // Target.
      if ($target == 'on') {
        $target = 'target="_blank"';
      }
      else {
        $target = FALSE;
      }
      if ($background) {
        $background = $base_url . $background;
      }
      if ($skin) {
        $el_class .= ' ' . $skin;
      }
      if ($animate) {
        $el_class .= ' wow ' . $animate;
      }
      ob_start();
      ?>
      <?php if ($skin == 'skin-v1') { ?>
    <div class="gsc-image-content 
        <?php print $el_class; ?>
    " 
        <?php print gavias_content_builder_print_animate_wow('', $animate_delay) ?>
    >
    
        <?php if ($title) { ?>
      
    <h4 class="title">
    
          <?php if ($link) {
            ?>
    <a 
            <?php print $target ?>
     href="
            <?php print $link ?>
    ">
            <?php
          } ?>
    
    
          <?php print $title ?>
    
    
          <?php if ($link) {
            ?>
    </a>
            <?php
          } ?>
    
    </h4>
    
        <?php } ?>
    
    <div class="image">
        <?php if ($link) {
          ?>
    <a 
          <?php print $target ?>
     href="
          <?php print $link ?>
    ">
          <?php
        } ?>
    <img src="
        <?php print $background ?>
    " alt="
        <?php print strip_tags($title) ?>
    " />
        <?php if ($link) {
          ?>
    </a>
          <?php
        } ?>
    </div>
    <div class="box-content">
    <div class="desc">
        <?php print $content; ?>
   </div>
    
        <?php if ($link) { ?>
      
    <div class="read-more"><a class="btn-theme" 
          <?php print $target ?>
     href="
          <?php print $link ?>
    ">
          <?php print $text_link ?>
   </a></div>
    
        <?php } ?>
    
    </div>
    </div>
    
      <?php } ?>
    
    
      <?php if ($skin == 'skin-v2') { ?>
      
    <div class="gsc-image-content 
        <?php print $el_class; ?>
    " 
        <?php print gavias_content_builder_print_animate_wow('', $animate_delay) ?>
    >
    <div class="image">
        <?php if ($link) {
          ?>
    <a 
          <?php print $target ?>
     href="
          <?php print $link ?>
    ">
          <?php
        } ?>
    <img src="
        <?php print $background ?>
    " alt="
        <?php print strip_tags($title) ?>
    " />
        <?php if ($link) {
          ?>
    </a>
          <?php
        } ?>
    </div>
    <div class="box-content">
    
        <?php if ($title) { ?>
      
    <h4 class="title">
    
          <?php if ($link) {
            ?>
    <a 
            <?php print $target ?>
     href="
            <?php print $link ?>
    ">
            <?php
          } ?>
    
    
          <?php print $title ?>
    
    
          <?php if ($link) {
            ?>
    </a>
            <?php
          } ?>
    
    </h4>
    
        <?php } ?>
    
    <div class="desc">
        <?php print $content; ?>
   </div>
    
        <?php if ($link) { ?>
      
    <div class="read-more"><a 
          <?php print $target ?>
     href="
          <?php print $link ?>
    ">
          <?php print $text_link ?>
    <i class="gv-icon-165"></i></a></div>
    
        <?php } ?>
    
    </div>
    </div>
    
      <?php } ?>
    
    
      <?php if ($skin == 'skin-v3') { ?>
      
    <div class="gsc-image-content 
        <?php print $el_class; ?>
    " 
        <?php print gavias_content_builder_print_animate_wow('', $animate_delay) ?>
    >
    <div class="image">
    <img src="
        <?php print $background ?>
    " alt="
        <?php print strip_tags($title) ?>
    " />
    
        <?php if ($title) { ?>
      
    <h4 class="title">
    
          <?php if ($link) {
            ?>
    <a 
            <?php print $target ?>
     href="
            <?php print $link ?>
    ">
            <?php
          } ?>
    
    
          <?php print $title ?>
    
    
          <?php if ($link) {
            ?>
    </a>
            <?php
          } ?>
    
    </h4>
    
        <?php } ?>
    
    </div>
    <div class="box-content">
    <div class="desc">
        <?php print $content; ?>
   </div>
    
        <?php if ($link) { ?>
      
    <div class="read-more"><a class="btn-theme" 
          <?php print $target ?>
     href="
          <?php print $link ?>
    ">
          <?php print $text_link ?>
   </a></div>
    
        <?php } ?>
    
    </div>
    </div>
    
      <?php } ?>
    
    
      <?php return ob_get_clean() ?>
    
    
      <?php
    }

  }

endif;
