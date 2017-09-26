<?php
/**
 * Core.
 *
 * @package    WPS_Framework
 * @subpackage Functions
 * @author     Alexander Laznevoy 
 * @copyright  Copyright (c) 2017, Alexander Laznevoy
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */


/**
 * Show post view in frontend
 *
 * @global type $post
 * @return type
 */
add_action( 'wp_footer', "wps__post_view" );
function wps__post_view() {
  global $post;
  if ( ! isset($post ) || ! is_object( $post ) )
  return;
  if ( is_single( $post->ID ) ) {
    wps__set_post_views( $post->ID );
  }
}


// Add image size // plugin Force Regenerate Thumbnails 
if ( function_exists( 'add_image_size' ) ) {
  add_image_size( '150_150', 150, 150, true ); 
} 

