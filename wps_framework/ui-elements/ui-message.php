<?php
/**
 * Sets up the admin UI message functionality.
 *
 * @package   WPS_Framework
 * @version   1.0.0
 * @author    Alexander Laznevoy 
 * @copyright Copyright (c) 2017, Alexander Laznevoy
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * Type message: info, warning
 */


/* Example args
array(
  'field_type'   => 'message',
  'type_message' => '', 
  'message'      => '',
),
*/


// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
  die;
}

class UI_Message {

  // general settings
  private $settings = array();

  // defaults settings
  private $defaults_settings = array(
    'type_message' => 'info', 
    'message'      => '',     // value
  );

  function __construct( $args = array() ) {
    $this->settings = wp_parse_args( $args, $this->defaults_settings );
  }

  public function render() {
    // get setting
    $setting  = $this->settings;
    // other
    $type_message = $setting['type_message'];
    $message      = $setting['message'];

    $html = '<div class="wps__ui_message message_'.$type_message.'" >'.$message.'</div>';

    return $html;
  }

}