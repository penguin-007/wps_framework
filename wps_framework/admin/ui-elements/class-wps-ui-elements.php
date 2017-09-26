<?php
/**
 * Sets up the admin UI elements functionality for the framework.
 *
 * @package   WPS_Framework
 * @version   1.0.0
 * @author    Alexander Laznevoy 
 * @copyright Copyright (c) 2017, Alexander Laznevoy
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */


/**
 * Loads admin-specific javascript and styles for UI .
 *
 * @since 1.0.0
 */
add_action( 'admin_enqueue_scripts', 'enqueue_ui_elements_scripts' );

## add script to admin panel
function enqueue_ui_elements_scripts() {
  if ( ! did_action( 'wp_enqueue_media' ) ){ wp_enqueue_media(); }
  
  // Register common admin script and styles
  wp_enqueue_script( 'wps_admin_ui_script', trailingslashit( WPS_ADMIN_URI ) . 'ui-elements/assets/wps.admin.ui.script.js', array('jquery'), WPS_VERSION, true );
  wp_enqueue_style ( 'wps_admin_ui_style',  trailingslashit( WPS_ADMIN_URI ) . 'ui-elements/assets/wps.admin.ui.style.css', array(), WPS_VERSION, null );
}

## Add UI elements 
require_once( 'ui-input.php' );
require_once( 'ui-textarea.php' );
require_once( 'ui-checkbox.php' );
require_once( 'ui-wp_editor.php' );
require_once( 'ui-select.php' );
require_once( 'ui-image.php' );
require_once( 'ui-simple_gallery.php' );
require_once( 'ui-repeater.php' );
require_once( 'ui-message.php' );
require_once( 'ui-file.php' );
require_once( 'ui-button.php' );
require_once( 'ui-hide_block.php' );