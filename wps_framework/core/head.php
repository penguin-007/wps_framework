<?php
/**
 * Functions for site head.
 * Clear head.
 *
 * @package    WPS_Framework
 * @subpackage Functions
 * @author     Alexander Laznevoy 
 * @copyright  Copyright (c) 2017, Alexander Laznevoy
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Clean head.
 *
 * @since 1.0.0
 */
remove_action( 'wp_head', 'rest_output_link_wp_head', 10);
remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10);
remove_action( 'wp_head', 'wp_oembed_add_host_js');
remove_action( 'template_redirect', 'rest_output_link_header', 11, 0);
// meta titles
remove_filter( 'comment_text', 'make_clickable', 9);
remove_action( 'wp_head', 'feed_links_extra', 3 );
remove_action( 'wp_head', 'feed_links', 2 );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'index_rel_link' );
remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 );
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_head', 'dns-prefetch' );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );
remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
// remove version WordPress start 
add_filter('the_generator', 'remove_wpversion');
function remove_wpversion() {
  return '';
}
// remove version WordPress in links
add_filter( 'style_loader_src', 'wp_version_js_css', 9999);
add_filter( 'script_loader_src', 'wp_version_js_css', 9999);
function wp_version_js_css($src) {
  if (strpos($src, 'ver=' . get_bloginfo('version')))
    $src = remove_query_arg('ver', $src);
  return $src;
}
add_filter('emoji_svg_url', '__return_empty_string');
// полное отключение Emoji start
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );
// удалить WP_Widget_Recent_Comments css
function remove_recent_comments_style() {
  global $wp_widget_factory;
  remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));
}
add_action('widgets_init', 'remove_recent_comments_style');


// Adds common theme items to <head>.
add_action( 'wp_head', 'wps_meta_charset', 0  );
add_action( 'wp_head', 'wps_meta_viewport', 1 );
add_action( 'wp_head', 'wps_meta_base', 2 );


/**
 * Adds the meta charset to the header.
 *
 * @since 1.0.0
 * @return string <meta> tag for document charset.
 */
function wps_meta_charset() {
  printf( '<meta charset="%s" />' . "\n", get_bloginfo( 'charset' ) );
}

/**
 * Adds meta tag for viewport.
 *
 * @since 1.0.0
 * @return string <meta> tag for viewport.
 */
function wps_meta_viewport() {
  $string  = '<meta name="HandheldFriendly" content="True">' . "\n";
  $string .= '<meta name="MobileOptimized" content="320">' . "\n";
  $string .= '<meta name="viewport" content="width=device-width, initial-scale=1"/>' . "\n";
  echo $string;
}

/**
 * Adds tag base.
 *
 * @since 1.0.0
 * @return string <base> tag.
 */
function wps_meta_base() {
  printf( '<base href="%s/" >' . "\n", get_site_url() );
}

