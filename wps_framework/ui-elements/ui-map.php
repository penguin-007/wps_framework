<?php
/**
 * Sets up the admin UI map functionality.
 *
 * @package   WPS_Framework
 * @version   1.0.0
 * @author    Alexander Laznevoy 
 * @copyright Copyright (c) 2017, Alexander Laznevoy
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
  die;
}

class UI_Map {

  // general settings
  private $settings = array();

  // defaults settings
  private $defaults_settings = array(
    'api_key' => '', // value
  );

  function __construct( $args = array() ) {
    $this->settings = wp_parse_args( $args, $this->defaults_settings );
  }

  public function render() {
    // get setting
    $setting  = $this->settings;
    // other
    $api_key         = $setting['api_key'];
    $array_path      = $setting['array_path'];
    $value           = isset($setting['value']) ? $setting['value'] : array();
    $value_place     = isset($value['place']) ? $value['place'] : '';
    $value_latitude  = isset($value['latitude']) ? $value['latitude'] : '0';
    $value_longitude = isset($value['longitude']) ? $value['longitude'] : '0';

    //pre_print_r($setting);

    $html = '
    <div class="wps__ui_map__holder fn__wps__ui_map">
      <div class="wps__ui_map__input__holder">
        <input type="text" name="'.$array_path.'[place]" value="'.$value_place.'" class="wps__ui_map__input fn__wps__ui_map__geocoder" placeholder="Начните вводить адрес" >
      </div>
      <div class="wps__ui_map__cord">
        <span>latitude</span>
        <input type="text" name="'.$array_path.'[latitude]" value="'.$value_latitude.'" class="wps__ui_map__cord__latitude">
        <span>longitude</span>
        <input type="text" name="'.$array_path.'[longitude]" class="wps__ui_map__cord__longitude" value="'.$value_longitude.'" >
      </div>
      <div class="wps__ui_map__map__holder fn__wps__ui_map__holder" id="map">

      </div>
    </div>';

    return $html;
  }

}