<?php
/**
 * The admin UI elements button Class.
 *
 * @package   WPS_Framework
 * @version   1.0.0
 * @author    Alexander Laznevoy 
 * @copyright Copyright (c) 2017, Alexander Laznevoy
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * 
 */

/* Example args
array(
  'field_type'  => 'button',
  'btn_value'   => 'Чудо Кнопка',
  'class'       => 'my_button_class',
  'id'          => 'my_button_id',
  'title'       => 'button title',
  'description' => 'button desc',
  'confirm'     => '',
  // if need ajax
  'ajax'        => true,
  'ajax_action' => 'button_ajax_action', // name wp ajax action hook
  'set_timeout' => 1000 // js setTimeout before alert block creal
),
*/

// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
  die;
}

class UI_Button {

  // general settings
  private $settings = array();

  // defaults settings
  private $defaults_settings = array(
    'btn_value'    => 'Кнопка',      // value
    'class'        => '',
    'id'           => '',
    'ajax'         => false,
    'ajax_action'  => '',
    'set_timeout'  => '',
    'confirm'      => ''
  );

  function __construct( $args = array() ) {
    $this->settings = wp_parse_args( $args, $this->defaults_settings );
  }

  public function render() {
    global $post;
    // get setting
    $setting  = $this->settings;
    // post id
    $post_id = is_object($post) && $post->ID != "" ? $post->ID : "";
    // other
    $btn_value    = $setting['btn_value'];
    $class        = $setting['class'];
    $id           = $setting['id'];
    $ajax         = $setting['ajax'];
    $ajax_action  = $setting['ajax_action'];
    $set_timeout  = $setting['set_timeout'];
    $confirm      = $setting['confirm'];

    if ( $ajax && $ajax_action != "" ){
      $class = "wps__ui_button__ajax";
      $id    = "";
    }

    $html = '
    <div class="wps__ui_button__holder" >
      <input 
      type="button" 
      data-post-id="'.$post_id.'" 
      data-confirm="'.$confirm.'" 
      class="'.$class.'" 
      id="'.$id.'" 
      data-ajax_action="'.$ajax_action.'" 
      data-ajax_set_timeout="'.$set_timeout.'" 
      value="'.$btn_value.'">
      <span></span>
      <div class="wps__ui_button__alert_holder" ></div>
    </div>
    ';

    return $html;
  }

}