<?php
/**
 * Functions for site console.
 *
 * @package    WPS_Framework
 * @subpackage Functions
 * @author     Alexander Laznevoy 
 * @copyright  Copyright (c) 2017, Alexander Laznevoy
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
* WPS__Console Right Now
*/
add_action( 'dashboard_glance_items', 'wps__add_right_now_info' );
  
## Добавляем все типы записей в виджет "Прямо сейчас" в консоли
function wps__add_right_now_info( $items ){

  if( ! current_user_can('edit_posts') ) return $items; // exit
  // post types
  $args = array(
    'public'   => true,
    '_builtin' => false
  );
  $post_types = get_post_types( $args, 'object', 'and' );

  foreach( $post_types as $post_type ){
    $num_posts = wp_count_posts( $post_type->name );
    $num       = number_format_i18n( $num_posts->publish );
    $text      = _n( $post_type->labels->singular_name, $post_type->labels->name, intval( $num_posts->publish ) );
    $items[]   = "<a href=\"edit.php?post_type=$post_type->name\">$num $text</a>";
  }

  // tax
  $taxonomies = get_taxonomies( $args, 'object', 'and' );
  foreach( $taxonomies as $taxonomy ){
    $num_terms = wp_count_terms( $taxonomy->name );
    $num       = number_format_i18n( $num_terms );
    $text      = _n( $taxonomy->labels->singular_name, $taxonomy->labels->name , intval( $num_terms ) );
    $items[]   = "<a href='edit-tags.php?taxonomy=$taxonomy->name'>$num $text</a>";
  }

  return $items;
}
