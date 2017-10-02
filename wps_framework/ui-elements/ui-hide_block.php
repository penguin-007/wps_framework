<?php
/**
 * Sets up the admin UI hide block functionality.
 *
 * @package   WPS_Framework
 * @version   1.0.0
 * @author    Alexander Laznevoy 
 * @copyright Copyright (c) 2017, Alexander Laznevoy
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */


/* Example args
array(
  'field_type'   => 'hide_block', 
  'block_cont'   => 'hide_block',
),
*/


// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
  die;
}

class UI_Hide_block {

  // general settings
  private $settings = array();

  // defaults settings
  private $defaults_settings = array(
    'block_cont' => '',             // value
  );

  function __construct( $args = array() ) {
    $this->settings = wp_parse_args( $args, $this->defaults_settings );
  }

  public function render() {
    // get setting
    $setting  = $this->settings;
    // other
    $block_cont = $setting['block_cont'];

    $html = '<div hidden>'.$block_cont.'</div>';

    return $html;
  }

}