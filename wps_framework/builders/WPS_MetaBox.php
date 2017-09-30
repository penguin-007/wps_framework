<?php
/**
 * Meta Box Builder Class for CustomPostType and Page Templates.
 *
 * @package   WPS_Framework
 * @version   1.0.0
 * @author    Alexander Laznevoy 
 * @copyright Copyright (c) 2017, Alexander Laznevoy
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * @todo type: radio, map
 */


/* HOW USE
new WPS_MetaBox(
  array(
    'meta_box_name'   => 'Поля 2',                 // 1) 
    'post_types'      => array( 'custom_post' ),   // 2) can choose several
    'page_templates'  => array( 'templates/home-page.php' ), // 3) path to page template, can choose several 
    'meta_box_groups' => array(
      // Group fields
      array(
        'title'    => 'Группа 1',
        'fields'   => array(
          // FIELDS
        )
      ),
      // Group fields
    )
  )
);

*/
 
class WPS_MetaBox {

  private $options;

  private static $count;
  
  function __construct( $option ) {
    // get all oprions
    $this->options = (object) $option;

    ################## Meta Box Setting ################
    add_action( 'add_meta_boxes', array( $this, 'reg_meta_box' ) );
    ## save_post
    add_action( 'save_post', array( $this, 'meta_fields_update' ) );
  }


  ####################################################
  ################## Meta Box Setting ################
  ####################################################
  public function reg_meta_box() {
    global $post;
    $post_types     = $this->options->post_types;
    $meta_box_name  = $this->options->meta_box_name;
    $page_templates = $this->options->page_templates;

    self::$count++;

    if ( is_array($post_types) ){
      foreach ( $post_types as $value ) {
        if ( $value != "" ){
          add_meta_box( $value.'_'.self::$count, $meta_box_name, array( $this, 'meta_fields_post' ), $value, 'normal', 'low' );
        }
      }
    }

    if ( is_array($page_templates) ){
      foreach ( $page_templates as $value ) {
        if ( $value == get_post_meta( $post->ID, '_wp_page_template', true ) ) {
          add_meta_box( $value.'_'.self::$count, $meta_box_name, array( $this, 'meta_fields_post' ), 'page', 'normal', 'low' );
        }
      }
    }

  }

  ## Meta fields
  function meta_fields_post( $post ){
    $meta_box_groups = $this->options->meta_box_groups;
    $post_type       = $post->post_type;

    foreach ($meta_box_groups as $value) {
      $group_name = $value['title'];
      $fields     = $value['fields'];
  ?>
  
  <table class="wps_post_page_table">
    <?php 
    if( $group_name != '' ) {
      echo "<caption>{$group_name}</caption>";
    }

    if ( $fields ) {
      foreach ($fields as $value) {
        $field_type  = $value['field_type'];
        $title       = $value['title'] ? $value['title'] : '';
        $description = $value['description'] ? $value['description'] : '';

        // field setting
        $field_name          = $value['field_name'];
        $value['save_key']   = "wps_post_field";
        $value['array_path'] = "wps_post_field[{$field_name}]";
        $value['value']      = get_post_meta( $post->ID, $field_name, true );
    ?>
    <tr>
      <td>
        <p class="description"><?= $title; ?></p>
        <?php

        switch ( $field_type ) {

          # 1) input
          case 'input':
            $ui_input = new UI_Input( $value );
            echo $ui_input->render();
          break;

          # 2) textarea
          case 'textarea':
            $ui_textarea = new UI_Textarea( $value );
            echo $ui_textarea->render();
          break;

          # 3) checkbox
          case 'checkbox':
            $ui_checkbox = new UI_Checkbox( $value );
            echo $ui_checkbox->render();
          break;

          # 4) wp_editor
          case 'wp_editor':
            $ui_wp_editor = new UI_WP_editor( $value );
            $ui_wp_editor->render();
          break;

          # 5) select
          case 'select':
            $ui_select = new UI_Select( $value );
            echo $ui_select->render();
          break;

          # 6) image
          case 'image':
            $ui_image = new UI_Image( $value );
            echo $ui_image->render();
          break;

          # 7) simple_gallery
          case 'simple_gallery':
            $ui_simple_gallery = new UI_SimpleGallery( $value );
            echo $ui_simple_gallery->render();
          break;

          # 8) repeater
          case 'repeater':
            $ui_repeater = new UI_Repeater( $value );
            echo $ui_repeater->render();
          break;

          # 9) message
          case 'message':
            $ui_message = new UI_Message( $value );
            echo $ui_message->render();
          break;

          # 10) file
          case 'file':
            $ui_file = new UI_File( $value );
            echo $ui_file->render();
          break;

          # 11) button
          case 'button':
            $ui_button = new UI_Button( $value );
            echo $ui_button->render();
          break;

          # 999) hide_block
          case 'hide_block':
            $ui_hide_block = new UI_Hide_block( $value );
            echo $ui_hide_block->render();
          break;

          default:
            echo "UI-элемент не поддерживается или неверно указан тип.";
          break;
        }
        ?>
        <p class="wps_description_field"><?= $description; ?></p>
      </td>
    </tr>
    <?php
      }
    }
    ?>
  </table>

  <?php 
    }
  ?>
  <input type="hidden" name="extra_fields_nonce" value="<?php echo wp_create_nonce('nonce'); ?>" />

  <?php
  }


  ## Save Meta Field Post
  public function meta_fields_update( $post_id ){
    if ( !isset($_POST['extra_fields_nonce']) || !wp_verify_nonce($_POST['extra_fields_nonce'], 'nonce') ) return false; // check
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE  ) return false; // if autosave
    if ( !current_user_can('edit_post', $post_id) ) return false; // if user have rule for edit

    if( !isset($_POST['wps_post_field']) ) return false; 

    // Ok!
    foreach( $_POST['wps_post_field'] as $key=>$value ){
      if( empty($value) ){
        delete_post_meta($post_id, $key); // remove if empty
        continue;
      }
      update_post_meta($post_id, $key, $value);
    }
    return $post_id;
  }
    
}