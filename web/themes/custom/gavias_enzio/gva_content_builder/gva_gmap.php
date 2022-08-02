<?php

/**
 * @file
 * This is gva - gmap.
 */

if (!class_exists('GaviasEnzioElementGvaGmap')) :
  /**
   * Gavias Enzio Element Gva Gmap.
   */
  class GaviasEnzioElementGvaGmap {

    /**
     * Render form.
     */
    public function renderForm() {
      $fields = [
        'type' => 'gsc_gmap',
        'title' => t('Google Map'),
        'size' => 3,
        'fields' => [[
          'id' => 'title',
          'type' => 'text',
          'title' => t('Title for Admin'),
          'admin' => TRUE,
        ], [
          'id' => 'map_type',
          'type' => 'select',
          'title' => t('Map Type'),
          'options' => [
            'ROADMAP' => 'ROADMAP',
            'HYBRID' => 'HYBRID',
            'SATELLITE' => 'SATELLITE',
            'TERRAIN' => 'TERRAIN',
          ],
        ], [
          'id' => 'link',
          'type' => 'text',
          'title' => t('Latitude, Longitude for map'),
          'desc' => 'eg: 21.0173222,105.78405279999993',
        ], [
          'id' => 'height',
          'type' => 'text',
          'title' => 'Map height',
          'desc' => 'Enter map height (in pixels or leave empty for responsive map), eg: 400px',
          'std' => '400px',
        ], [
          'id' => 'content',
          'type' => 'text',
          'title' => 'Text Address',
        ], [
          'id' => 'desc',
          'type' => 'textarea',
          'title' => 'Content',
        ], [
          'id' => 'email',
          'type' => 'text',
          'title' => 'Email',
        ], [
          'id' => 'phone',
          'type' => 'text',
          'title' => 'Phone',
        ], [
          'id' => 'facebook',
          'type' => 'text',
          'title' => 'Facebook Link',
        ], [
          'id' => 'twitter',
          'type' => 'text',
          'title' => 'Twitter Link',
        ], [
          'id' => 'instagram',
          'type' => 'text',
          'title' => 'Instagram Link',
        ], [
          'id' => 'dribbble',
          'type' => 'text',
          'title' => 'Dribbble Link',
        ], [
          'id' => 'linkedin',
          'type' => 'text',
          'title' => 'Linkedin Link',
        ], [
          'id' => 'pinterest',
          'type' => 'text',
          'title' => 'Pinterest Link',
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
        'map_type' => 'ROADMAP',
        'link' => '',
        'height' => '',
        'info' => '',
        'el_class' => '',
        'animate' => '',
        'animate_delay' => '',
        'content' => '',
        'desc' => '',
        'phone' => '',
        'email' => '',
        'facebook' => '',
        'twitter' => '',
        'instagram' => '',
        'dribbble' => '',
        'linkedin' => '',
        'pinterest' => '',
      ], $attr));
      $zoom = 14;
      // $bubble = TRUE;
      $_id = gavias_content_builder_makeid();
      if ($animate) {
        $el_class .= ' wow ' . $animate;
      }
      $style = '[{"featureType":"water","elementType":"geometry","stylers":[{"color":"#e9e9e9"},{"lightness":17}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#f5f5f5"},{"lightness":20}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#ffffff"},{"lightness":17}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#ffffff"},{"lightness":29},{"weight":0.2}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#ffffff"},{"lightness":18}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#ffffff"},{"lightness":16}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#f5f5f5"},{"lightness":21}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#dedede"},{"lightness":21}]},{"elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#ffffff"},{"lightness":16}]},{"elementType":"labels.text.fill","stylers":[{"saturation":36},{"color":"#333333"},{"lightness":40}]},{"elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#f2f2f2"},{"lightness":19}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#fefefe"},{"lightness":20}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#fefefe"},{"lightness":17},{"weight":1.2}]}]';
      ?>
         <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCcABaamniA6OL5YvYSpB3pFMNrXwXnLwU&ver=4.9.3"></script>
         <script type="text/javascript" src="<?php print (base_path() . drupal_get_path('theme',
          'gavias_enzio')) ?>/vendor/gmap3.js"></script>
         <script type="text/javascript" src="<?php print (base_path() . drupal_get_path('theme',
          'gavias_enzio')) ?>/vendor/jquery.ui.map.min.js"></script>

         <div class="gsc-map <?php print $el_class ?>" <?php print gavias_content_builder_print_animate_wow('', $animate_delay) ?>>
            <div id="map_canvas_<?php echo $_id; ?>" class="map_canvas" style="width:100%; height:<?php echo $height; ?>;"></div>
            <div class="content-inner clearfix">
               <?php if ($desc) {
                  ?><div class="desc"><?php print $desc ?></div><?php
               } ?>
               <?php if ($content) {
                  ?><div class="address info"><span class="icon gv-icon-1138"></span><?php print $content ?></div> <?php
               } ?>
               <?php if ($email) {
                  ?><div class="email info"><span class="icon gv-icon-226"></span><?php print $email ?></div> <?php
               } ?>
               <?php if ($phone) {
                  ?><div class="phone info"><span class="icon gv-icon-266"></span><?php print $phone ?></div> <?php
               } ?>
               <div class="social-inline">
                  <?php if ($facebook) {
                    ?>
                     <a href="<?php echo $facebook ?>"><i class="fa fa-facebook-square"></i></a>
                    <?php
                  } ?>   
                  <?php if ($twitter) {
                    ?>
                     <a href="<?php echo $twitter ?>"><i class="fa fa-twitter-square"></i></a>
                    <?php
                  } ?>   
                  <?php if ($instagram) {
                    ?>
                     <a href="<?php echo $instagram ?>"><i class="fa fa-instagram"></i></a>
                    <?php
                  } ?>
                  <?php if ($dribbble) {
                    ?>
                     <a href="<?php echo $dribbble ?>"><i class="fa fa-dribbble"></i></a>
                    <?php
                  } ?>
                  <?php if ($linkedin) {
                    ?>
                     <a href="<?php echo $linkedin ?>"><i class="fa fa-linkedin-square"></i></a>
                    <?php
                  } ?>
                  <?php if ($pinterest) {
                    ?>
                     <a href="<?php echo $pinterest ?>"><i class="fa fa-pinterest"></i></a>
                    <?php
                  } ?>
               </div>
            </div>
         </div>
         <script type="text/javascript">
            jQuery(document).ready(function($) {
               var stmapdefault = '<?php echo $link; ?>';
               var marker = {position:stmapdefault};
               var content = '<?php print $content ?>';
           
               jQuery('#map_canvas_<?php echo $_id; ?>').gmap({
                  'scrollwheel':false,
                  'zoom': <?php echo $zoom;  ?>  ,
                  'center': stmapdefault,
                  'mapTypeId':google.maps.MapTypeId.<?php echo ($map_type); ?>,
                  'styles': <?php echo $style; ?>,
                  'callback': function() {
                     var self = this;
                     self.addMarker(marker).click(function(){
                        if(content){
                           self.openInfoWindow({'content': content}, self.instance.markers[0]);
                        }                     
                     });
                  },
                  panControl: true
               });
            });
         </script>
      <?php

    }

  }

endif;
