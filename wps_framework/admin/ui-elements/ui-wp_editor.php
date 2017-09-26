<?php
/**
 * The admin UI wp_editor functionality.
 *
 * @package   WPS_Framework
 * @version   1.0.0
 * @author    Alexander Laznevoy 
 * @copyright Copyright (c) 2017, Alexander Laznevoy
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/* Example args
array(
  'field_type'   => 'wp_editor',
  'field_name'   => 'wp_editor',
  'title'        => 'wp_editor',
  'description'  => '',
  'def_value'    => '',
  'options'      => array(),
),
*/


// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
  die;
}

class UI_WP_editor {

  // general settings
  private $settings = array();

  private $defaults_options = array(
    'wpautop'       => 0, // wpautop() добавляет параграфы
    'textarea_rows' => 5,
    'media_buttons' => 0,
    'teeny'         => 0,
    'dfw'           => 1,
    'tinymce' => array(
      //'toolbar2'         => '',
      'resize'           => false, 
      'wp_autoresize_on' => true
    ),
    'quicktags'        => 1,
    'drag_drop_upload' => false,
  );

  function __construct( $args = array() ) {
    $this->settings = $args;
  }

  public function render() {
    // get setting
    $setting  = $this->settings;
    // other
    $editor_id    = str_replace( "_", "", $setting['field_name'] );
    $array_path   = $setting['array_path'];
    $value        = $setting['value'] ? $setting['value'] : $setting['def_value'];
    $options      = wp_parse_args( $setting['options'], $this->defaults_options );

    wp_editor( $value, $editor_id, array_merge( $options, array(
      'textarea_name' => $array_path
      )) 
    );
  }

}
