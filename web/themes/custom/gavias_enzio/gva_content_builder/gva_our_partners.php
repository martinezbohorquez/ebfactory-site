<?php

/**
 * @file
 * This is gva - our partness.
 */

if (!class_exists('GaviasEnzioElementGvaOurPartners')) :
  /**
   * Gavias Enzio Element Gva Our Partners.
   */
  class GaviasEnzioElementGvaOurPartners {

    /**
     * Render form.
     */
    public function renderForm() {
      $fields = [
        'type' => 'gsc_our_partners',
        'title' => t('Our Partners'),
        'fields' => [[
          'id' => 'title',
          'type' => 'text',
          'title' => t('Name'),
          'admin' => TRUE,
        ], [
          'id' => 'image',
          'type' => 'upload',
          'title' => t('Photo'),
        ], [
          'id' => 'content',
          'type' => 'textarea',
          'title' => t('Content'),
        ], [
          'id' => 'address',
          'type' => 'text',
          'title' => t('Address'),
        ], [
          'id' => 'category',
          'type' => 'text',
          'title' => t('Category'),
        ], [
          'id' => 'link',
          'type' => 'text',
          'title' => t('Link'),
        ], [
          'id' => 'target',
          'type' => 'select',
          'title' => ('Open in new window'),
          'desc' => ('Adds a target="_blank" attribute to the link.'),
          'options' => [0 => 'No', 1 => 'Yes'],
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
      return $fields;
    }

    /**
     * Render content.
     */
    public static function renderContent($attr, $content = NULL) {
      global $base_url;
      extract(gavias_merge_atts([
        'title' => '',
        'image' => '',
        'content' => '',
        'address' => '',
        'category' => '',
        'link' => '',
        'target' => '',
        'animate' => '',
        'animate_delay' => '',
        'el_class' => '',
      ], $attr));
      if ($image) {
        $image = $base_url . $image;
      }
      if ($target) {
        $target = 'target="_blank"';
      }
      else {
        $target = FALSE;
      }
      if ($animate) {
        $el_class .= ' wow ' . $animate;
      }
      ob_start();
      ?>
        
            <div class="widget gsc-our-partners <?php print $el_class ?>" <?php print gavias_content_builder_print_animate_wow('', $animate_delay) ?>>
               <?php if ($image) {
                  ?>
                  <div class="image"><img src="<?php print $image ?>" alt="<?php ?>"/></div>
                  <?php
               } ?>

               <div class="content-inner">
                  <?php if ($title) {
                    ?>
                     <div class="title">
                        <?php if ($link) {
                          ?><a href="<?php $link ?>" <?php print $target ?>><?php
                        } ?> 
                           <?php print $title ?>
                        <?php if ($link) {
                          print '</a>';
                        } ?>
                     </div>
                    <?php
                  } ?>    
                  <div class="info">
                     <?php if ($category) {
                        ?>
                        <span class="category"><?php print $category ?>,</span>
                        <?php
                     } ?>
                     <?php if ($address) {
                        ?>
                        <span class="address"><?php print $address ?></span>
                        <?php
                     } ?>
                  </div>
                  <?php if ($content) {
                    ?>
                     <div class="content"><?php print $content ?></div>
                    <?php
                  } ?>                       
               </div>

            </div>

         <?php return ob_get_clean() ?>
         <?php

    }

  }

endif;
