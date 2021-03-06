<?php

/**
 * @file
 * This is gva - pricing item.
 */

if (!class_exists('GaviasEnzioElementGvaPricingItem')) :
  /**
   * Gavias Enzio Element Gva Pricing Item.
   */
  class GaviasEnzioElementGvaPricingItem {

    /**
     * Render form.
     */
    public function renderForm() {
      $fields = [
        'type' => 'gsc_pricing_item',
        'title' => ('Pricing Item'),
        'fields' => [[
          'id' => 'title',
          'type' => 'text',
          'title' => t('Title'),
          'desc' => t('Pricing item title'),
          'admin' => TRUE,
        ], [
          'id' => 'price',
          'type' => 'text',
          'title' => t('Price'),
        ], [
          'id' => 'currency',
          'type' => 'text',
          'title' => t('Currency'),
        ], [
          'id' => 'period',
          'type' => 'text',
          'title' => t('Period'),
        ], [
          'id' => 'content',
          'type' => 'textarea',
          'title' => t('Content'),
          'desc' => t('HTML tags allowed.'),
          'std' => '<ul><li><strong>List</strong> item</li></ul>',
        ], [
          'id' => 'link_title',
          'type' => 'text',
          'title' => t('Link title'),
          'desc' => t('Link will appear only if this field will be filled.'),
        ], [
          'id' => 'link',
          'type' => 'text',
          'title' => t('Link'),
          'desc' => t('Link will appear only if this field will be filled.'),
        ], [
          'id' => 'featured',
          'type' => 'select',
          'title' => t('Featured'),
          'options' => [
            'off' => 'No',
            'on' => 'Yes',
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
      return $fields;
    }

    /**
     * Render content.
     */
    public static function renderContent($attr = [], $content = '') {
      extract(gavias_merge_atts([
        'title' => '',
        'currency' => '',
        'price' => '',
        'period' => '',
        'content' => '',
        'link_title' => 'Sign Up Now',
        'link' => '',
        'featured' => 'off',
        'el_class' => '',
        'animate' => '',
        'animate_delay' => '',
      ], $attr));
      if ($featured == 'on') {
        $el_class .= ' highlight-plan';
      }
      if ($animate) {
        $el_class .= ' wow ' . $animate;
      }
      ob_start();
      ?>
         <div class="pricing-table <?php print $el_class ?>" <?php print gavias_content_builder_print_animate_wow('', $animate_delay) ?>>
            <?php if ($featured == 'on') {
              ?>
               <div class="recommended-plan"><?php print t('Recommended Plan') ?></div>
              <?php
            } ?>   
            <div class="content-inner">
               <div class="content-wrap">
                  <div class="plan-name"><span class="title"><?php print $title; ?></span></div>
                  <div class="plan-price">
                     <div class="price-value clearfix">
                        <span class="dollar"><?php print $currency ?></span>
                        <span class="value"><?php print $price; ?></span>
                        <span class="interval"><span class="space">&nbsp;/&nbsp;</span><?php print $period ?></span>
                     </div>
                  </div>
                  <?php if ($content) {
                    ?>
                     <div class="plan-list">
                        <?php print $content ?>
                     </div>
                    <?php
                  } ?>   
                  <?php if ($link) {
                    ?>
                     <div class="plan-signup">
                        <a class="btn-theme" href="<?php print $link; ?>"><?php print $link_title ?></a>
                     </div>
                    <?php
                  } ?>  
               </div> 
            </div>      
         </div>
      <?php return ob_get_clean();
    }

  }

endif;
