<?php
/**
 * The admin UI elements repeater Class.
 *
 * @package   WPS_Framework
 * @version   1.0.0
 * @author    Alexander Laznevoy 
 * @copyright Copyright (c) 2017, Alexander Laznevoy
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * 
 */


/* Example args
array(
  'field_type'   => 'repeater', 
  'field_name'   => 'repeater',
  'title'        => 'repeater text',
  'description'  => '',
  'width'        => '',
  'fields'       => array()
),
*/

// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
  die;
}

class UI_Repeater {

  // general settings
  private $settings = array();

  // defaults settings
  private $defaults_settings = array(
    'field_name'   => '',             // unique id (without spaces)
    'value'        => '',             // value
    'width'        => '300px',
    'fields'       => array()
  );

  function __construct( $args = array() ) {
    $this->settings = wp_parse_args( $args, $this->defaults_settings );
  }

  public function render() {
    // get setting
    $setting  = $this->settings;
    // other
    $repeater_item_width  = $setting['width'];
    $repeater_field_name  = $setting['field_name'];
    $repeater_array_path  = $setting['array_path'];
    $repeater_save_key    = $setting['save_key'];
    $repeater_value       = $setting['value'];
    $repeater_fields      = $setting['fields'];


    // html
    $html .= '<div class="wps__repeater__holder" >';
      $html .= '<span class="wps__repeater__add_before">Добавить</span>';

      // clone
      $html .= '<div class="wps__repeater__clone" >';
        $html .= '<div class="wps__repeater__item" style="width: '.$repeater_item_width.'" >';
          $html .= '<span class="wps__repeater__remove_item"></span>';
          foreach ($repeater_fields as $value) {
            $title               = $value['title'] ? $value['title'] : '';
            $field_name          = $value['field_name'];
            $value['array_path'] = "{$repeater_save_key}[{$repeater_field_name}][repeater_number][{$field_name}]";
            $html .= "<p class='wps__repeater__title'>$title</p>";
            $html .= $this->get_ui_field( $value['field_type'], $value );
          }
        $html .= '</div>';
      $html .= '</div>';

      // render
      $html .= '<div class="wps__repeater__wrap" >';
        if ( is_array( $repeater_value ) ) :
        unset( $repeater_value['repeater_number'] );
        $iterator = 0;
        foreach ($repeater_value as $value) {
          $html .= '<div class="wps__repeater__item" style="width: '.$repeater_item_width.'" >';
            $html .= '<span class="wps__repeater__remove_item"></span>';
            $field_values = array_values($value);
            $in = 0;
            foreach ($repeater_fields as $value) {
              $field_name          = $value['field_name'];
              $field_title         = $value['title'];
              $value['value']      = $field_values[$in];
              $value['array_path'] = "{$repeater_save_key}[{$repeater_field_name}][{$iterator}][{$field_name}]";
              $html .= "<p class='wps__repeater__title'>$field_title</p>";
              $html .= $this->get_ui_field( $value['field_type'], $value );
              $in++;
            }
          $html .= '</div>';
          $iterator++;
        }
        endif;
      $html .= '</div>';

    $html .= '</div>';

    return $html;
  }


  // get_ui_field 
  public function get_ui_field( $field_type, $value ) {

    switch ( $field_type ) {

      # 1) input
      case 'input':
        $ui_input = new UI_Input( $value );
        $html = $ui_input->render();
      break;

      # 2) textarea
      case 'textarea':
        $ui_textarea = new UI_Textarea( $value );
        $html = $ui_textarea->render();
      break;

      # 3) checkbox
      case 'checkbox':
        $ui_checkbox = new UI_Checkbox( $value );
        $html = $ui_checkbox->render();
      break;

      # 4) image
      case 'image':
        $ui_image = new UI_Image( $value );
        $html = $ui_image->render();
      break;

      # 5) simple_gallery
      case 'simple_gallery':
        $ui_simple_gallery = new UI_SimpleGallery( $value );
        $html = $ui_simple_gallery->render();
      break;

      # 6) file
      case 'file':
        $ui_file = new UI_File( $value );
        $html = $ui_file->render();
      break;

      # 7) select
      case 'select':
        $ui_select = new UI_Select( $value );
        $html = $ui_select->render();
      break;

      default:
        $html = "Неверно указан тип поля.";
      break;
    }
    return $html;
  }

  // get repeater
  public static function wps__get_repeater( $repeater_name ){
    global $post;
    $repeater = get_post_meta( $post->ID, $repeater_name, true );
    if ( !is_array( $repeater ) ) return false;
    unset( $repeater['repeater_number'] );
    return $repeater;
  }

}