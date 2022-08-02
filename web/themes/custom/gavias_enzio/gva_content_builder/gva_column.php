<?php

/**
 * @file
 * This is gva - column.
 */

/**
 * Gavias Enzio Element Gva Column.
 */
class GaviasEnzioElementGvaColumn {

  /**
   * Render form.
   */
  public function renderForm() {
    return [
      'title'    => $this->t('Column'),
      'key'    => 'gva_column',
      'fields'    => [
              [
                'id'        => 'info',
                'type'      => 'info',
                'title'      => 'Setting background for column',
              ],
              [
                'id'       => 'bg_image',
                'type'     => 'upload',
                'title'    => ('Background Image'),
              ],
              [
                'id'          => 'bg_color',
                'type'        => 'text',
                'title'       => ('Background Color'),
                'desc'        => ('Use color name (eg. "gray") or hex (eg. "#808080").'),
              ],
              [
                'id'         => 'bg_position',
                'type'       => 'select',
                'title'      => $this->t('Background Position'),
                'class'       => 'width-1-4',
                'options'    => [
                  'center top'             => 'center top',
                  'center right'         => 'center right',
                  'center bottom'         => 'center bottom',
                  'center center'         => 'center center',
                  'left top'             => 'left top',
                  'left center'         => 'left center',
                  'left bottom'         => 'left bottom',
                  'right top'             => 'right top',
                  'right center'         => 'right center',
                  'right bottom'         => 'right bottom',
                ],
              ],
              [
                'id'         => 'bg_repeat',
                'type'       => 'select',
                'title'      => $this->t('Background Position'),
                'class'       => 'width-1-4',
                'options'    => [
                  'no-repeat'     => 'no-repeat',
                  'repeat'         => 'repeat',
                  'repeat-x'     => 'repeat-x',
                  'repeat-y'     => 'repeat-y',
                ],
              ],

              [
                'id'         => 'bg_attachment',
                'type'       => 'select',
                'title'      => $this->t('Background Attachment'),
                'class'       => 'width-1-4',
                'options'    => [
                  'scroll' => 'Scroll',
                  'fixed'  => 'Fixed - Parallax',
                ],
                'std'         => 'scroll',
              ],

              [
                'id'         => 'bg_size',
                'type'       => 'select',
                'title'      => $this->t('Background Size'),
                'class'       => 'width-1-4',
                'options'    => [
                  'cover'      => 'cover',
                  'contain'    => 'contain',
                  'default'    => 'default',
                ],
                'std'         => 'cover',
              ],

              [
                'id'        => 'info',
                'type'      => 'info',
                'title'      => 'Setting padding, margin for column',
              ],
              [
                'id'        => 'padding_top',
                'type'      => 'text',
                'title'     => ('Padding Top'),
                'desc'      => ('Padding Top for column (e.g. 30)'),
                'class'     => 'width-1-4',
                'std'       => '0',
              ],
              [
                'id'          => 'padding_right',
                'type'        => 'text',
                'title'       => ('Padding Right'),
                'desc'        => ('Padding Right for column (e.g. 30)'),
                'class'     => 'width-1-4',
                'std'         => '0',
              ],
              [
                'id'          => 'padding_bottom',
                'type'        => 'text',
                'title'       => ('Padding Bottom'),
                'desc'        => ('Padding Bottom for column (e.g. 30)'),
                'class'     => 'width-1-4',
                'std'         => '0',
              ],
              [
                'id'          => 'padding_left',
                'type'        => 'text',
                'title'       => ('Padding Left'),
                'desc'        => ('Padding Left for column (e.g. 30)'),
                'class'     => 'width-1-4',
                'std'         => '0',
              ],

              [
                'id'        => 'info',
                'type'      => 'info',
                'title'      => 'Setting padding, margin for column',
              ],

              [
                'id'          => 'margin_top',
                'type'        => 'text',
                'title'       => ('Margin Top'),
                'desc'        => ('Margin top for column (e.g. 30)'),
                'class'     => 'width-1-4',
                'std'         => '0',
              ],
              [
                'id'          => 'margin_right',
                'type'        => 'text',
                'title'       => ('Margin Right'),
                'desc'        => ('Margin Right for column (e.g. 30)'),
                'class'     => 'width-1-4',
                'std'         => '0',
              ],
              [
                'id'          => 'margin_bottom',
                'type'        => 'text',
                'title'       => ('Margin Bottom'),
                'desc'        => ('Margin Bottom for column (e.g. 30)'),
                'class'     => 'width-1-4',
                'std'         => '0',
              ],
              [
                'id'          => 'margin_left',
                'type'        => 'text',
                'title'       => ('Margin Left'),
                'desc'        => ('Margin Left for column (e.g. 30)'),
                'class'     => 'width-1-4',
                'std'         => '0',
              ],

              [
                'id'        => 'info',
                'type'      => 'info',
                'title'      => 'Setting style, class, id for columns',
              ],
              [
                'id'         => 'style',
                'type'       => 'select',
                'title'      => $this->t('Style Available'),
                'options'    => [
                  ''                 => 'Default',
                  'column-style-1'      => 'Style 1: max-with 700px, center, padding responsive',
                  'column-style-2'      => 'Style 2: max-with 800px, center, padding responsive',
                  'column-style-3'      => 'Style 3: Padding 60px & padding responsive',
                ],
              ],
              [
                'id'      => 'el_class',
                'type'    => 'text',
                'title'   => ('Custom CSS classes'),
                'desc'    => ('Multiple classes should be separated with SPACE.'),
              ],
              [
                'id'      => 'inner_class',
                'type'    => 'text',
                'title'   => ('Custom for element inner CSS classes'),
                'desc'    => ('Multiple classes should be separated with SPACE.'),
              ],

              [
                'id'      => 'id_column',
                'type'    => 'text',
                'title'   => ('Custom ID'),
                'desc'    => ('For example: Your Custom ID for column'),
              ],

              [
                'id'        => 'info',
                'type'      => 'info',
                'title'      => 'Setting Responsive Visibility for Columns',
              ],
              [
                'id' => 'hidden_lg',
                'type'    => 'select',
                'title'   => ('Hide on large screen'),
                'class'   => 'width-1-4',
                'options'   => [
                  ''             => 'Show',
                  'hidden-lg'    => 'Hidden',
                ],
              ],
              [
                'id' => 'hidden_md',
                'type'    => 'select',
                'title'   => ('Hide on medium screen'),
                'class'   => 'width-1-4',
                'options'   => [
                  ''             => 'Show',
                  'hidden-sm'    => 'Hidden',
                ],
              ],
              [
                'id' => 'hidden_sm',
                'type'    => 'select',
                'title'   => ('Hide on small screen'),
                'class'   => 'width-1-4',
                'options'   => [
                  ''             => 'Show',
                  'hidden-sm'    => 'Hidden',
                ],
              ],
              [
                'id' => 'hidden_xs',
                'type'    => 'select',
                'title'   => ('Hide on extra small screen'),
                'class'   => 'width-1-4',
                'options'   => [
                  ''            => 'Show',
                  'hidden-xs'   => 'Hidden',
                ],
              ],
              [
                'id'        => 'info',
                'type'      => 'info',
                'title'      => 'Setting Responsive Width for Columns',
              ],

              [
                'id' => 'col_md',
                'type'    => 'select',
                'title'   => ('Responsive Medium Screen'),
                'class'   => 'width-1-4',
                'options'   => [
                  ''         => 'Default',
                  '1'        => '1',
                  '2'        => '2',
                  '3'        => '3',
                  '4'        => '4',
                  '5'        => '5',
                  '6'        => '6',
                  '7'        => '7',
                  '8'        => '8',
                  '9'        => '9',
                  '10'        => '10',
                  '11'        => '11',
                  '12'        => '12',
                ],
              ],
              [
                'id' => 'col_sm',
                'type'    => 'select',
                'title'   => ('Responsive Small Screen'),
                'class'   => 'width-1-4',
                'options'   => [
                  ''         => 'Default',
                  '1'        => '1',
                  '2'        => '2',
                  '3'        => '3',
                  '4'        => '4',
                  '5'        => '5',
                  '6'        => '6',
                  '7'        => '7',
                  '8'        => '8',
                  '9'        => '9',
                  '10'        => '10',
                  '11'        => '11',
                  '12'        => '12',
                ],
              ],
              [
                'id' => 'col_xs',
                'type'    => 'select',
                'title'   => ('Responsive Extra Small screen'),
                'class'   => 'width-1-4',
                'options'   => [
                  ''         => 'Default',
                  '1'        => '1',
                  '2'        => '2',
                  '3'        => '3',
                  '4'        => '4',
                  '5'        => '5',
                  '6'        => '6',
                  '7'        => '7',
                  '8'        => '8',
                  '9'        => '9',
                  '10'        => '10',
                  '11'        => '11',
                  '12'        => '12',
                ],
              ],
              [
                'id'        => 'info',
                'type'      => 'info',
                'title'      => 'Setting class, id for columns',
              ],
              [
                'id'    => 'class',
                'type'    => 'text',
                'title'   => ('Custom CSS classes'),
                'desc'    => ('Multiple classes should be separated with SPACE.'),
              ],

              [
                'id'    => 'class_inner',
                'type'    => 'text',
                'title'   => ('Custom for element inner CSS classes'),
                'desc'    => ('Multiple classes should be separated with SPACE.'),
              ],

              [
                'id'    => 'column_id',
                'type'    => 'text',
                'title'   => ('Custom ID'),
                'desc'    => ('For example: Your Custom ID for column'),
                'class'   => 'small-text',
              ],
      ],
    ];
  }

  /**
   * Render content.
   */
  public function renderContent($settings = [], $content = '') {
    global $base_path;
    extract(gavias_merge_atts([
      'bg_image'               => '',
      'bg_color'               => '',
      'bg_position'            => '',
      'bg_repeat'                => '',
      'bg_attachment'          => '',
      'bg_size'                  => '',
      'bg_video'               => '',
      'padding_top'              => '',
      'padding_right'         => '',
      'padding_bottom'        => '',
      'padding_left'          => '',
      'margin_top'            => '',
      'margin_right'          => '',
      'margin_bottom'         => '',
      'margin_left'           => '',
      'hidden_lg'             => '',
      'hidden_md'             => '',
      'hidden_sm'             => '',
      'hidden_xs'             => '',
      'style'                 => '',
      'el_class'                   => '',
      'inner_class'                => '',
      'id_column'                    => '',
      'col_lg'                        => 12,
      'col_md'                        => '',
      'col_sm'                        => '',
      'col_xs'                        => '',
    ], $settings));

    // Responsive.
    switch ($col_lg) {
      case 1:
        $default_col_md = 1;
        $default_col_sm = 2;
        $default_col_xs = 6;
        break;

      case 2:
        $default_col_md = 2;
        $default_col_sm = 4;
        $default_col_xs = 12;
        break;

      case 3:
        $default_col_md = 3;
        $default_col_sm = 6;
        $default_col_xs = 12;
        break;

      case 4:
        $default_col_md = 4;
        $default_col_sm = 4;
        $default_col_xs = 12;
        break;

      case 5:
        $default_col_md = 5;
        $default_col_sm = 5;
        $default_col_xs = 12;
        break;

      case 6:
        $default_col_md = 6;
        $default_col_sm = 12;
        $default_col_xs = 12;
        break;

      case 7:
        $default_col_md = 7;
        $default_col_sm = 7;
        $default_col_xs = 12;
        break;

      case 8:
        $default_col_md = 8;
        $default_col_sm = 8;
        $default_col_xs = 12;
        break;

      case 9:
        $default_col_md = 9;
        $default_col_sm = 9;
        $default_col_xs = 12;
        break;

      case 10:
        $default_col_md = 10;
        $default_col_sm = 10;
        $default_col_xs = 12;
        break;

      case 11:
        $default_col_md = 11;
        $default_col_sm = 12;
        $default_col_xs = 12;
        break;

      case 12:
        $default_col_md = 12;
        $default_col_sm = 12;
        $default_col_xs = 12;
        break;

      default:
        $default_col_md = 12;
        $default_col_sm = 12;
        $default_col_xs = 12;
        break;
    }
    if (empty($col_md)) {
      $col_md = $default_col_md;
    }
    if (empty($col_sm)) {
      $col_sm = $default_col_sm;
    }
    if (empty($col_xs)) {
      $col_xs = $default_col_xs;
    }

    $array_class = [];
    $array_style = [];
    $array_class_inner = [];

    $array_class[] = 'col-lg-' . $col_lg;
    $array_class[] = 'col-md-' . $col_md;
    $array_class[] = 'col-sm-' . $col_sm;
    $array_class[] = 'col-xs-' . $col_xs;

    if ($hidden_lg) {
      $array_class[] = $hidden_lg;
    }
    if ($hidden_md) {
      $array_class[] = $hidden_md;
    }
    if ($hidden_sm) {
      $array_class[] = $hidden_sm;
    }
    if ($hidden_xs) {
      $array_class[] = $hidden_xs;
    }

    $array_class[] = $el_class;
    if ($padding_top) {
      $array_style[] = 'padding-top:' . intval($padding_top) . 'px';
    }
    if ($padding_left) {
      $array_style[] = 'padding-left:' . intval($padding_left) . 'px';
    }
    if ($padding_bottom) {
      $array_style[] = 'padding-bottom:' . intval($padding_bottom) . 'px';
    }
    if ($padding_right) {
      $array_style[] = 'padding-right:' . intval($padding_right) . 'px';
    }
    if ($margin_top) {
      $array_style[] = 'margin-top:' . intval($margin_top) . 'px';
    }
    if ($margin_right) {
      $array_style[] = 'margin-right:' . intval($margin_right) . 'px';
    }
    if ($margin_bottom) {
      $array_style[] = 'margin-bottom:' . intval($margin_bottom) . 'px';
    }
    if ($margin_left) {
      $array_style[] = 'margin-left:' . intval($margin_left) . 'px';
    }

    if ($bg_color) {
      $array_style[] = 'background-color:' . $bg_color;
    }

    $class_col_parallax = '';
    if ($bg_image) {
      $array_style[] = 'background-image:url(\'' . substr($base_path, 0, -1) . $bg_image . '\')';
      $array_style[] = 'background-repeat:' . $bg_repeat;
      if ($bg_attachment == 'fixed') {
        $array_style[] = 'background-position: 50% 0';
        $class_col_parallax = 'gva-parallax-background';
      }
      else {
        $array_style[] = 'background-position:' . $bg_position;
        $array_style[] = 'background-position:' . $bg_position;
      }
    }

    $col_bg_size = 'bg-size-cover';
    if ($bg_size) {
      $col_bg_size = 'bg-size-' . $bg_size;
    }
    $array_class_inner[] = $class_col_parallax;
    $array_class_inner[] = $col_bg_size;
    $array_class_inner[] = $inner_class;
    $array_class_inner[] = $style;

    $column_class       = implode(' ', $array_class);
    $column_class_inner = implode(' ', $array_class_inner);
    $column_style       = implode('; ', $array_style);

    ob_start();
    ?>
        <div 
    <?php
    if ($id_column) {
      print 'id="' . $id_column . '"';
    }
    ?>
          class="gsc-column <?php
          print $column_class;
          ?>
         ">
          <div class="column-inner <?php
          print $column_class_inner
          ?>
           " <?php
            echo(($column_style) ? 'style="' . $column_style . '"' : '');
            ?>
           >
             <div class="column-content-inner">
               <?php
                print $content;
                ?>
                
             </div>  
             <?php
              if ($bg_attachment == 'fixed') {
                ?>
                
               <div style="background-repeat: <?php
                print $bg_repeat;
                ?>
                 ;background-position:<?php
                  print $bg_position
                  ?>
                 ;" class="<?php
                  print $col_bg_size
                  ?>
                  gva-parallax-inner skrollable skrollable-between" data-bottom-top="top: -60%;" data-top-bottom="top: 0%;"></div>
                <?php
              }
              ?>
             
          </div>
        </div>
       <?php return ob_get_clean();
  }

}
