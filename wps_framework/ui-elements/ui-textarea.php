<?php
/**
 * The admin UI elements textarea Class.
 *
 * @package   WPS_Framework
 * @version   1.0.0
 * @author    Alexander Laznevoy 
 * @copyright Copyright (c) 2017, Alexander Laznevoy
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/* Example args
array(
  'field_type'   => 'textarea',
  'field_name'   => 'textarea',
  'title'        => 'textarea',
  'description'  => '',
  'def_value'    => '',
  'placeholder'  => '',
  'height'       => '',
  'class'        => '',
),
*/

// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
  die;
}

class UI_Textarea {

  // general settings
  private $settings = array();

  // defaults settings
  private $defaults_settings = array(
    'field_name'   => '',                // unique id (without spaces)
    'value'        => '',                // value
    'def_value'    => '',                // default value ( if empty value )
    'placeholder'  => '',                // placeholder
    'height'       => '100',             // height
    'class'        => 'wps_ui_textarea', // class
    'add_class'    => '',                // if need new class
    'editor'       => false
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
    $placeholder  = $setting['placeholder'];
    $height       = $setting['height'];
    $class        = $setting['class'];
    $add_class    = $setting['add_class'];
    $editor       = $setting['editor'];

    if ( $editor ){
      $class .= ' textarea__simple_editor';
    }

    $html = '<textarea 
    name="'.$array_path.'" 
    class="'.$class.' '.$add_class.'" 
    placeholder="'.$placeholder.'" 
    style="height: '.$height.'px; 
    ">'.$value.'</textarea>';

    return $html;
  }

}