<?php
/**
 * The admin UI elements file Class.
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
  'field_type'   => 'file', 
  'field_name'   => 'file',
  'title'        => 'file text',
  'description'  => 'file description',
  'def_value'    => '',
),
*/

// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
  die;
}

class UI_File {

  // general settings
  private $settings = array();

  // defaults settings
  private $defaults_settings = array(
    'field_name'   => '',            // unique id (without spaces)
    'value'        => '',            // value
    'def_value'    => '',            // default value ( if empty value )
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
    $class        = $setting['class'];

    $file_name = basename($value);
    $file_name = basename($value, ".php");

    $html = '
    <div class="wps_ui_file">
      <span class="wps_ui_file_btn">Выбрать файл</span>
      <input type="text" name="'.$array_path.'" value="'.$value.'" />
      <span class="wps_ui_file__name">'.$file_name.'</span>
    </div>';

    return $html;
  }

}