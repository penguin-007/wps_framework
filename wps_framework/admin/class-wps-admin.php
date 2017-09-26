<?php
/**
 * Sets up the admin functionality for the framework.
 *
 * @package   WPS_Framework
 * @version   1.0.0
 * @author    Alexander Laznevoy 
 * @copyright Copyright (c) 2017, Alexander Laznevoy
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

class WPS_Admin {

  /**
   * Holds the instances of this class.
   *
   * @since 1.0.0
   * @var   object
   */
  private static $instance = null;

  /**
   * Initialize the loading admin scripts & styles. 
   * Add menu setting page
   *
   * @since 1.0.0
   */
  public function __construct() {
    add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

    // Load init class for ui elements.
    require_once( trailingslashit( WPS_ADMIN_DIR ) . 'ui-elements/class-wps-ui-elements.php' );
  }


  ## Add script to admin panel
  function enqueue_admin_scripts() {
    // Register common admin script and styles
    wp_enqueue_script( 'jquery-ui-sortable' );
    // color-picker
    wp_enqueue_script( 'wp-color-picker' );
    wp_enqueue_style( 'wp-color-picker' );
    
    wp_enqueue_script( 'select2', trailingslashit( WPS_ADMIN_URI ) . 'assets/select2/js/select2.min.js', array('jquery'), WPS_VERSION, true );
    wp_enqueue_style ( 'select2',  trailingslashit( WPS_ADMIN_URI ) . 'assets/select2/css/select2.min.css', array(), WPS_VERSION, null );

    wp_enqueue_script( 'wps_admin_script', trailingslashit( WPS_ADMIN_URI ) . 'assets/wps.admin.script.js', array('jquery'), WPS_VERSION, true );
    wp_enqueue_style ( 'wps_admin_style',  trailingslashit( WPS_ADMIN_URI ) . 'assets/wps.admin.style.css', array(), WPS_VERSION, null );
  }

  /**
   * Returns the instance.
   *
   * @since  1.0.0
   * @return object
   */
  public static function get_instance() {
    // If the single instance hasn't been set, set it now.
    if ( null == self::$instance ) {
      self::$instance = new self;
    }
    return self::$instance;
  }

}

WPS_Admin::get_instance();