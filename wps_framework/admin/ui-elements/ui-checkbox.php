<?php
/**
 * The admin UI elements checkbox functionality.
 *
 * @package   WPS_Framework
 * @version   1.0.0
 * @author    Alexander Laznevoy 
 * @copyright Copyright (c) 2017, Alexander Laznevoy
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/* Example args
array(
  'field_type'   => 'checkbox',
  'field_name'   => 'checkbox',
  'title'        => 'checkbox',
  'description'  => '',
  'class'        => '',
),
*/


// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
  die;
}

class UI_Checkbox {

  // general settings
  private $settings = array();

  // defaults settings
  private $defaults_settings = array(
    'field_name'   => '',  // unique id (without spaces)
    'value'        => '',  // value
    'description'  => '',  // description
    'class'        => 'wps_ui_checkbox_css' // class
  );

  function __construct( $args = array() ) {
    $this->settings = wp_parse_args( $args, $this->defaults_settings );
  }

  public function render() {
    // get setting
    $setting  = $this->settings;
    // other
    $array_path  = $setting['array_path'];
    $checked     = $setting['value'] != '' ? 'checked' : '';
    $class       = $setting['class'];
    $description = $setting['description'];

    $html = '
    <input type="hidden" class="'.$class.'" name="'.$array_path.'" />
    <label class="save_my_checkbox__label">
      <input type="checkbox" class="'.$class.'" name="'.$array_path.'" '.$checked.' />
      <span></span>
    </label>';

    return $html;
  }

}


## wps_ui_checkbox in ajax for page and post type
function wps_ui_checkbox__ajax( $name, $text ) {
  global $post;
  $value = get_post_meta($post->ID, $name, true);
  $state = $value == "on" ? "checked" : "";
  echo '
  <label class="save_my_checkbox__label">
    <input type="checkbox" data-id="'.$post->ID.'" data-key="'.$name.'" class="wps_save_my_checkbox wps_ui_checkbox_css" name="extra['.$name.']" '.$state.' />
    <span></span>
    '.$text.'
  </label>';
}


## Save_checkbox
add_action('wp_ajax_wps_save_checkbox', 'wps_admin_ui_save_checkbox' );
function wps_admin_ui_save_checkbox(){
  $value = $_POST['value'];
  $key   = $_POST['key'];
  $id    = $_POST['id'];
  if ($value === "true"){
    update_post_meta($id, $key, "on");
    exit("ID[$id] / Key[$key] - Checkbox ON.");
  } else {
    delete_post_meta($id, $key);
    exit("ID[$id] / Key[$key] - Checkbox REMOVE.");
  }
}