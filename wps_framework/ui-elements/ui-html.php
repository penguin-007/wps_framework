<?php
/**
 * Sets up the admin UI custom html functionality.
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

class UI_HTML {

  // general settings
  private $settings = array();

  // defaults settings
  private $defaults_settings = array(
    'content'   => '',  // if need new class
    'add_class' => '',  // value
  );

  function __construct( $args = array() ) {
    $this->settings = wp_parse_args( $args, $this->defaults_settings );
  }

  public function render() {
    // get setting
    $setting  = $this->settings;
    // other
    $content   = $setting['content'];
    $add_class = $setting['add_class'];

    $html = '<div class="wps__ui_html__holder '.$add_class.' ">'.$content.'</div>';

    return $html;
  }

}