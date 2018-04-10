<?php
/**
 * The admin UI elements input Class.
 *
 * @package   WPS_Framework
 * @version   1.0.0
 * @author    Alexander Laznevoy 
 * @copyright Copyright (c) 2017, Alexander Laznevoy
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * 
 * Allow type (type_input) : text, number, email, password, color, date
 */

// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
  die;
}

class UI_Input {

  // general settings
  private $settings = array();

  // defaults settings
  private $defaults_settings = array(
    'field_name'   => '',             // unique id (without spaces)
    'type_input'   => 'text',         // type of the input
    'value'        => '',             // value
    'def_value'    => '',             // default value ( if empty value )
    'placeholder'  => '',             // placeholder
    'required'     => false,          // required true/false
    'autocomplete' => 'off',          // autocomplete on/off
    'class'        => 'wps_ui_input', // class
    'add_class'    => ''              // if need new class
  );

  function __construct( $args = array() ) {
    $this->settings = wp_parse_args( $args, $this->defaults_settings );
  }

  public function render() {
    // get setting
    $setting  = $this->settings;
    // other
    $type_input   = $setting['type_input'];
    $array_path   = $setting['array_path'];
    $value        = $setting['value'] ? $setting['value'] : $setting['def_value'];
    $placeholder  = $setting['placeholder'];
    $required     = $setting['required'] ? "required" : '';
    $autocomplete = $setting['autocomplete'];
    $class        = $setting['class'];
    $add_class    = $setting['add_class'];

    // if type color
    if ( $type_input == 'color' ){
      $type_input = 'text';
      $class      = 'wps_ui_input_color';
    }
    // if type data
    if ( $type_input == 'date' ){
      $type_input = 'text';
      $class      = 'wps_ui_input_date';
    }

    $html = '<input 
    type="'.$type_input.'" 
    name="'.$array_path.'" 
    class="'.$class.' '.$add_class.'" 
    placeholder="'.$placeholder.'" 
    value="'.$value.'" 
    '.$required.' 
    autocomplete="'.$autocomplete.'" />';

    return $html;
  }

}