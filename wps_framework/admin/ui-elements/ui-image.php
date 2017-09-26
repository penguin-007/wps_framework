<?php
/**
 * Sets up the admin UI image functionality.
 *
 * @package   WPS_Framework
 * @version   1.0.0
 * @author    Alexander Laznevoy 
 * @copyright Copyright (c) 2017, Alexander Laznevoy
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/* Example args
array(
  'field_type'   => 'image', 
  'field_name'   => 'image',
  'title'        => 'image text',
  'description'  => '',
),
*/


// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
  die;
}

class UI_Image {

  // general settings
  private $settings = array();

  // defaults settings
  private $defaults_settings = array(
    'field_name' => '',             // unique id (without spaces)
    'value'      => '',             // value
  );

  function __construct( $args = array() ) {
    $this->settings = wp_parse_args( $args, $this->defaults_settings );
  }

  public function render() {
    // get setting
    $setting  = $this->settings;
    // other
    $array_path = $setting['array_path'];
    $value      = $setting['value'];
    $img_url    = wp_get_attachment_image_url( $value, '150_150' );

    $html = '
    <div class="wps__ui_image__holder" >
      <img width="150" height="150" src="'.$img_url.'" alt="">
      <input type="hidden" name="'.$array_path.'" value="'.$value.'" />
      <span class="wps__ui_image__remove"></span>
    </div>
    ';

    return $html;
  }

}