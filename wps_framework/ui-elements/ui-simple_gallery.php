<?php
/**
 * Sets up the admin UI galery functionality.
 *
 * @package   WPS_Framework
 * @version   1.0.0
 * @author    Alexander Laznevoy 
 * @copyright Copyright (c) 2017, Alexander Laznevoy
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/* Example args
array(
  'field_type'   => 'simple_gallery', 
  'field_name'   => 'simple_gallery',
  'title'        => 'simple_gallery title',
  'description'  => '',
),
*/


// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
  die;
}

class UI_SimpleGallery {

  // general settings
  private $settings = array();

  // defaults settings
  private $defaults_settings = array(
    'field_name' => '',             // unique id (without spaces)
    'value'      => '',             // value
  );

  function __construct( $args = array() ) {
    $this->settings = wp_parse_args( $args, $this->defaults_settings );
  }

  public function render() {
    // get setting
    $setting  = $this->settings;
    // other
    $array_path  = $setting['array_path'];
    $value       = array();
    $value       = $setting['value'];

    if ( is_array( $value ) ){
    	unset($value[0]);
    }

    $html = '<div class="wps__simple_gallery__holder" >';
    	$html .= '<span class="wps__simple_gallery__add_before">Добавить изображение</span>';

    	// clone
	    $html .= '<div class="wps__simple_gallery__clone" >';
	    	$html .= '<div class="wps__simple_gallery__item" >';
    			$html .= '<span class="wps__simple_gallery__remove_item"></span>';
			    $args = array(
			      'array_path'  => $array_path.'[]'
			    );
			    $ui_image = new UI_Image( $args );
		    	$html .= $ui_image->render();
		    $html .= '</div>';
	    $html .= '</div>';

	    // render images
	    $html .= '<div class="wps__simple_gallery__wrap" >';
	    	if ( is_array( $value ) ) :
		    foreach ($value as $id) {
		    	$html .= '<div class="wps__simple_gallery__item" >';
    				$html .= '<span class="wps__simple_gallery__remove_item"></span>';
			    	$args = array(
				      'array_path'  => $array_path.'[]',
				      'value'       => $id
				    );
			    	$ui_image = new UI_Image( $args );
		    		$html .= $ui_image->render();
		    	$html .= '</div>';
		    }
		    endif;
	    $html .= '</div>';

    $html .= '</div>';

    return $html;
  }

  // get gallery
  public static function wps__get_simple_gallery( $gallery_name ){
    global $post;
    $images = get_post_meta( $post->ID, $gallery_name, true );
    if ( !is_array( $images ) ) return false;
    unset( $images[0] );
    return $images;
  }

}