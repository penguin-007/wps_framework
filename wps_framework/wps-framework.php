<?php
/**
 *
 * @package   WPS_Framework
 * @version   1.0.0
 * @author    Alexander Laznevoy 
 * @copyright Copyright (c) 2017, Alexander Laznevoy
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
  die;
}

class WPS_Framework {

  function __construct() {
    // Set global variable.
    global $wps, $wps_setting;

    // Set up an empty class for the global $wps object.
    $wps         = new stdClass;
    $wps_setting = get_option( 'wps_framework' );

    // Define framework, parent theme, and child theme constants.
    add_action( 'after_setup_theme', array( $this, 'wps__set__constants' ) );

    // Load the core functions/classes required by the rest of the framework.
    add_action( 'after_setup_theme', array( $this, 'wps_core' ) );

    add_action( 'after_setup_theme', array( $this, 'wps_theme_support' ) );

    add_action( 'after_setup_theme', array( $this, 'wps_admin' ) );

    add_action( 'after_setup_theme', array( $this, 'wps_builders' ) );

    add_action( 'after_setup_theme', array( $this, 'wps_init' ) );

    // Модули подгружать в последнюю очередь 
    add_action( 'after_setup_theme', array( $this, 'wps_modules' ) );
  }


  /**
  * Defines the constant paths for use within the core framework theme.
  * @since 1.0.0
  */
  function wps__set__constants(){
    /** Sets the framework version number. */
    $template  = get_template();
    $framework = wp_get_theme( $template );

    define( 'WPS_VERSION', $framework->get( 'Version' ) );

    /** Sets the path to the parent theme directory. */
    define( 'PARENT_DIR', get_template_directory() );

    /** Sets the path to the parent theme directory URI. */
    define( 'PARENT_URI', get_template_directory_uri() );

    /** Sets the path to the child theme directory. */
    define( 'CHILD_DIR', get_stylesheet_directory() );

    /** Sets the path to the child theme directory URI. */
    define( 'CHILD_URI', get_stylesheet_directory_uri() );

    /* WPS_Framework DIR  */
    define( 'WPS_DIR', trailingslashit( PARENT_DIR ) . basename( dirname( __FILE__ ) ) );

    /** WPS_Framework URI. */
    define( 'WPS_URI', trailingslashit( PARENT_URI ) . basename( dirname( __FILE__ ) ) );

    /** Sets the path to the core framework functions directory. */
    define( 'WPS_CORE', trailingslashit( WPS_DIR ) . 'core' );

    /** Sets the path to the builder functions directory. */
    define( 'WPS_BUILDERS', trailingslashit( WPS_DIR ) . 'builders' );

    /** Sets the path to the modules framework directory. */
    define( 'WPS_MODULES', trailingslashit( WPS_DIR ) . 'modules' );

    /** Sets the path to the modules url framework directory. */
    define( 'WPS_MODULES_URI', trailingslashit( WPS_URI ) . 'modules' );

    /** Sets the path to the core framework admin directory. */
    define( 'WPS_ADMIN_DIR', trailingslashit( WPS_DIR ) . 'admin' );

    /** Sets the path to the core framework admin directory. */
    define( 'WPS_ADMIN_URI', trailingslashit( WPS_URI ) . 'admin' );
  }



  /**
   * Defines the theme core functions.
   * @since 1.0.0
   */
  function wps_core(){
    // Load the theme framework functions.
    require_once( trailingslashit( WPS_CORE ) . 'functions.php' );

    // Load the theme head framework functions.
    require_once( trailingslashit( WPS_CORE ) . 'head.php' );

    // Load the theme functions for development.
    require_once( trailingslashit( WPS_CORE ) . 'development.php' );

    // Load the theme functions for admin panel.
    require_once( trailingslashit( WPS_CORE ) . 'admin.php' );

    // Load the theme functions core.
    require_once( trailingslashit( WPS_CORE ) . 'core.php' );

    // Load the theme functions for console.
    require_once( trailingslashit( WPS_CORE ) . 'console.php' );

    // Load the theme filters.
    require_once( trailingslashit( WPS_CORE ) . 'filters.php' );
  }


  /**
   * Defines the theme admin function and UI elements.
   * @since 1.0.0
   */
  function wps_admin(){
    // Load the theme functions for development.
    require_once( trailingslashit( WPS_ADMIN_DIR ) . 'class-wps-admin.php' );
  }

  /**
   * Adds theme supported features.
   *
   * @since 1.0.0
   */
  function wps_theme_support(){
    # This feature enables plugins and themes to manage the document title tag.
    add_theme_support('title-tag');

    # Allow post thumbnails
    add_theme_support('post-thumbnails');

    // Add default posts and comments RSS feed links to head.
    add_theme_support( 'automatic-feed-links' );

    // Enable HTML5 markup structure.
    add_theme_support( 'html5', array(
      'comment-list', 'comment-form', 'search-form', 'gallery', 'caption',
    ) );
  }


  /**
   * Defines the theme admin builders.
   * @since 1.0.0
   */
  function wps_builders(){
    require_once( trailingslashit( WPS_BUILDERS ) . 'WPS_CustomType.php'  );
    require_once( trailingslashit( WPS_BUILDERS ) . 'WPS_MetaBox.php'     );
    require_once( trailingslashit( WPS_BUILDERS ) . 'WPS_PostColumns.php' );
    require_once( trailingslashit( WPS_BUILDERS ) . 'WPS_TermFields.php'  );
    require_once( trailingslashit( WPS_BUILDERS ) . 'WPS_OptionPage.php'  );
  }


  /**
   * Defines the theme modules.
   * @since 1.0.0
   */
  function wps_modules(){
    require_once( trailingslashit( WPS_MODULES ) . 'wps-modules.php'  );
  }


  function wps_init(){
    // File for use functionality the framework 
    require_once( trailingslashit( WPS_DIR ) . 'init.php' );
  }

}