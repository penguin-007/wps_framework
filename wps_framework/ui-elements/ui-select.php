<?php
/**
 * The admin UI elements select functionality.
 *
 * @package   WPS_Framework
 * @version   1.0.0
 * @author    Alexander Laznevoy 
 * @copyright Copyright (c) 2017, Alexander Laznevoy
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/* Example args
array(
  'field_type'   => 'select',
  'field_name'   => 'select',
  'title'        => 'select title',
  'description'  => '',
  'class'        => '',
  'multiple'     => false,
  'options'      => array(
    'key'  => 'val',
    'key1' => 'val1',
  )
),
*/

// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
  die;
}

class UI_Select {

  // general settings
  private $settings = array();

  // defaults settings
  private $defaults_settings = array(
    'field_name'   => '',              // unique id (without spaces)
    'value'        => array(),         // value
    'options'      => array(),
    'multiple'     => false,
    'def_value'    => '',             // default value ( if empty value )
    'class'        => 'wps_ui_select', // class
    'add_class'    => ''               // if need new class
  );

  function __construct( $args = array() ) {
    $this->settings = wp_parse_args( $args, $this->defaults_settings );
  }

  public function render() {
    // get setting
    $setting  = $this->settings;
    // other
    $array_path   = $setting['array_path'];
    $value        = $setting['value'] ? $setting['value'] : $setting['def_value'];
    $options      = $setting['options'];
    $class        = $setting['class'];
    $add_class    = $setting['add_class'];
    $multiple     = $setting['multiple'] ? "multiple" : "";

    $html = '<select class="'.$class .' '.$add_class.'" '.$multiple.' name="'.$array_path.'[]" />';
    if ( $options ){
      $html .= '<option  value="">----</option>';
      foreach ($options as $key => $name) {
        $selected = "";
        if ( is_array( $value )){
          if ( in_array($key, $value) ){
            $selected = 'selected';
          }
        } 
        $html .= '<option value="'.$key.'" '.$selected.' >'.$name.'</option>';
      }
    }
    $html .= '</select>';

    return $html;
  }

}
