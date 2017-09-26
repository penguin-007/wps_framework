<?php
/**
 * Other functions.
 *
 * @package   WPS_Framework
 * @version   1.0.0
 * @author    Alexander Laznevoy 
 * @copyright Copyright (c) 2017, Alexander Laznevoy
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Set post views
 */
function wps__set_post_views( $postID ){
  $count_key = 'wps_post_views_count';
  $count     = get_post_meta( $postID, $count_key, true );
  if( $count != "" ){
    $count++;
    update_post_meta( $postID, $count_key, $count );
  } else {
    update_post_meta( $postID, $count_key, 0 );
  }
}

/**
 * Get post views
 */
function wps__get_post_views( $postID ){
  $count_key = 'wps_post_views_count';
  $count     = get_post_meta( $postID, $count_key, true );
  if( $count != "" ){
    return $count;
  } else {
    update_post_meta( $postID, $count_key, 0 );
    return 0;
  }
}

## Get_sitename
function wps__get_sitename(){
  $site = strtolower( $_SERVER['SERVER_NAME'] );
  if ( substr( $site, 0, 4 ) == 'www.' ) {
    $site = substr( $site, 4 );
  }
  return $site;
}

## get_video_id
function wps__get_video_id( $name ){
  preg_match('#(\.be/|/embed/|/v/|/watch\?v=)([A-Za-z0-9_-]{5,11})#', $name, $matches);
  if(isset($matches[2]) && $matches[2] != ''){
    $YoutubeId = $matches[2];
  }
  return $YoutubeId;
}

## give_me filtered content
function wps__the_content( $content ) {
  return apply_filters('the_content', $content);
}

## Shorten Text 
function wps__text_limit( $text, $count, $after ) {
  $text = mb_substr($text,0,$count);
  return "<p>".$text.$after."</p>";
}

## Price format 999 999 999 
function wps__price_format( $text ) {
  return strrev(implode(' ', str_split(strrev($text),3))); 
}

## Custom button "More"
add_filter('the_content_more_link', 'wps__my_more_link', 10, 2);
function wps__my_more_link( $more_link, $more_link_text ) {
  return str_replace($more_link_text, 'Читать полностью', $more_link);
}


/**
 * Get favicons from theme wps main options.
 *
 * @since  1.0.0
 * @return string
 */
function wps_get_favicon_tags() {

  $favicon_option = get_option( 'wps_theme_main_settings' );
  $favicon_id     = $favicon_option['theme_favicon'];

  if ( $favicon_id ) {
    $favicon_url = wp_get_attachment_url( $favicon_id );
  } else {
    return false;
  }

  $default_format = '<link type="%1$s" href="%3$s" rel="%2$s">'. "\n";
  $device_format  = '<link href="%3$s" sizes="%2$sx%2$s" rel="%1$s">'. "\n";

  $icons = array(
    'type'  => 'image/x-icon',
    'rel'   => 'shortcut icon',
    'sizes' => "16",
  );

  $result .= sprintf( $default_format, $icons['type'], $icons['rel'], $favicon_url );
  $result .= sprintf( $device_format, $icons['rel'], $icons['sizes'], $favicon_url );

  return $result;
}

/**
 * Display a favicons tags.
 *
 * @since 1.0.0
 */
function wps__favicon_tags() {
  echo wps_get_favicon_tags();
}
