<?php

/**
 * @file
 * This is gva - view.
 */

use Drupal\views\Views;
use Drupal\views\Element\View;

if (!class_exists('GaviasEnzioElementGvaView')) :
  /**
   * Gavias Enzio Element Gva View.
   */
  class GaviasEnzioElementGvaView {

    /**
     * Render form.
     */
    public function renderForm() {
      $view_options = Views::getViewsAsOptions(TRUE, 'all', NULL, FALSE, TRUE);
      $view_display = [];
      foreach ($view_options as $view_key => $view_name) {
        $view = Views::getView($view_key);
        $view_display[''] = '-- None --';
        foreach ($view->storage->get('display') as $name => $display) {
          if ($display['display_plugin'] == 'block') {
            $view_display[$view_key . '-----' . $name] = $view_name . ' || ' . $display['display_title'];
          }
        }
      }
      asort($view_display);
      $fields = [
        'type' => 'gsc_view',
        'title' => ('Drupal View'),
        'size' => 12,
        'fields' => [[
          'id' =>
          'title_admin
         type' => 'text',
          'title' => t('Administrator Title'),
          'admin' => TRUE,
        ], [
          'id' => 'title',
          'type' => 'text',
          'title' => t('Title'),
        ], [
          'id' => 'view',
          'type' => 'select',
          'title' => t('View Name'),
          'options' => $view_display,
          'class' => 'gsc_display_view change_value_admin',
        ], [
          'id' => 'view_arg',
          'type' => 'text',
          'title' => t('View Arg'),
          'std' => '',
        ], [
          'id' => 'show_title',
          'type' => 'select',
          'title' => t('Show Title'),
          'options' => [
            'hidden' => 'Hidden',
            'title_view' => 'Title View',
            'title_block' => 'Title Block',
          ],
          'std' => 'hidden',
          'desc' => t('Hidden title default for block'),
        ], [
          'id' => 'style_text',
          'type' => 'select',
          'title' => t('Skin Text for box'),
          'options' => [
            'text-dark' => 'Text dark',
            'text-light' => 'Text light',
          ],
          'std' => 'text-dark',
        ], [
          'id' => 'align_title',
          'type' => 'select',
          'title' => t('Align title'),
          'options' => [
            'title-align-left' => 'Align Left',
            'title-align-right' => 'Align Right',
            'title-align-center' => 'Align Center',
          ],
          'std' => 'title-align-center',
          'desc' => t('Align title default for block'),
        ], [
          'id' => 'remove_margin',
          'type' => 'select',
          'title' => ('Remove Margin'),
          'options' => [
            'off' => 'No',
            'on' => 'Yes',
          ],
          'std' => 'off',
          'desc' => t('Defaut block margin bottom 60px, You can remove margin for block'),
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
    public function renderContent($attr = [], $content = '') {
      extract(gavias_merge_atts([
        'title_admin' => '',
        'title' => '',
        'view' => '',
        'view_arg' => '',
        'show_title' => 'hidden',
        'align_title' => 'title-align-center',
        'style_text' => '',
        'el_class' => '',
        'remove_margin' => 'off',
        'animate' => '',
        'animate_delay' => '',
      ], $attr));
      if (!$view) {
        return "None view choose";
      }

      $output = '';
      $class = [];
      $class[] = $align_title;
      $class[] = $el_class;
      $class[] = $style_text;
      $class[] = 'remove-margin-' . $remove_margin;
      if ($animate) {
        $class[] = 'wow ' . $animate;
      }
      $view_tmp = $view;
      $_view = preg_split("/-----/", $view);
      if (isset($_view[0]) && isset($_view[1])) {
        $output .= '<div>';
        $output .= '<div class="widget block clearfix gsc-block-view  gsc-block-drupal block-view ' . implode(' ', $class) . '" ' . gavias_content_builder_print_animate_wow('', $animate_delay) . '>';
        if ($title && $show_title == 'title_block') {
          $output .= '<h2 class="block-title title-shortcode"><span>' . $title . '</span></h2>';
        }
        try {
          $view = Views::getView($_view[0]);
          if ($view) {
            $v_output = $view->buildRenderable($_view[1], [], FALSE);
            if ($v_output) {
              if ($view->getTitle() && $show_title == 'title_view') {
                $output .= '<h2 class="block-title title-view"><span>' . $view->getTitle() . '</span></h2>';
              }
              $v_output['#view_id'] = $view->storage->id();
              $v_output['#view_display_show_admin_links'] = $view->getShowAdminLinks();
              $v_output['#view_display_plugin_id'] = $view->display_handler->getPluginId();
              views_add_contextual_links($v_output,
              'block', $_view[1]);
              $v_output = View::preRenderViewElement($v_output);
              if (empty($v_output['view_build'])) {
                $v_output = ['#cache' => $v_output['#cache']];
              }
              if ($v_output) {
                $output .= render($v_output);
              }
            }
          }
          else {
            $output .= '<div>Missing view, block "' . $view_tmp . '"</div>';
          }
        }
        catch (PluginNotFoundException $e) {
          $output .= '<div>Missing view, block "' . $view_tmp . '"</div>';
        }
        $output .= '</div></div>';
        $view = NULL;
        $v_output = NULL;
      }
      return $output;
    }

  }

endif;
