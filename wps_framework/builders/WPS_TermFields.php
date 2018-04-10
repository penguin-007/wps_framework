<?php
/**
 * Term Meta Box Builder Class.
 *
 * @package   WPS_Framework
 * @version   1.0.0
 * @author    Alexander Laznevoy 
 * @copyright Copyright (c) 2017, Alexander Laznevoy
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 */


/* HOU USE
new WPS__TermFields( 
  array(
    'taxonomy'  => array( 'name_cat' ), // can choose several
    'fields'    => array(
      // FIELDS
    )
  )
);

*/
 
class WPS_TermFields {

  private $options;
  
  function __construct( $option ) {
    $this->options = (object) $option;

    if ( is_array( $this->options->taxonomy ) ) {
      foreach( $this->options->taxonomy as $taxonomy ){
        // when create
        add_action( "{$taxonomy}_add_form_fields",  array( $this, 'add_new_custom_fields'     ) );
        add_action( "create_{$taxonomy}",           array( $this, 'save_custom_taxonomy_meta' ) );
        // when edit
        add_action( "{$taxonomy}_edit_form_fields", array( $this, 'edit_new_custom_fields'    ) );
        add_action( "edited_{$taxonomy}",           array( $this, 'save_custom_taxonomy_meta' ) );
      }
    }
  }



  ## edit_new_custom_fields name_cat
  public function edit_new_custom_fields( $term ) {
    // get fields
    $fields = $this->options->fields;

    foreach ($fields as $value) {
      $field_type  = $value['field_type'];
      $title       = $value['title'] ? $value['title'] : '';
      $description = $value['description'] ? $value['description'] : '';

      // field setting
      $field_name          = $value['field_name'];
      $value['save_key']   = "wps_term_meta";
      $value['array_path'] = "wps_term_meta[{$field_name}]"; 
      $value['value']      = get_term_meta( $term->term_id, $field_name, true );  

      echo '
      <tr class ="form-field">
        <th scope="row" valign="top">'.$title.'</th>
        <td>';

      switch ( $field_type ) {

        # 1) input 
        case 'input':
          $ui_input = new UI_Input( $value );
          echo $ui_input->render();
          echo '<p class="description">'.$description.'</p>';
        break;

        # 2) textarea
        case 'textarea':
          $ui_textarea = new UI_Textarea( $value );
          echo $ui_textarea->render();
          echo '<p class="description">'.$description.'</p>';
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
          echo '<p class="description">'.$description.'</p>';
        break;

        # 5) select
        case 'select':
          $ui_select = new UI_Select( $value );
          echo $ui_select->render();
          echo '<p class="description">'.$description.'</p>';
        break;

        # 6) image
        case 'image':
          $ui_image = new UI_Image( $value );
          echo $ui_image->render();
          echo '<p class="description">'.$description.'</p>';
        break;

        # 7) simple_gallery
        case 'simple_gallery':
          $ui_simple_gallery = new UI_SimpleGallery( $value );
          echo $ui_simple_gallery->render();
          echo '<p class="description">'.$description.'</p>';
        break;

        # 8) repeater
        case 'repeater':
          $ui_repeater = new UI_Repeater( $value );
          echo $ui_repeater->render();
          echo '<p class="description">'.$description.'</p>';
        break;

        # 9) message
        case 'message':
          $ui_message = new UI_Message( $value );
          echo $ui_message->render();
        break;

        # 10) file
        case 'file':
          $ui_message = new UI_File( $value );
          echo $ui_message->render();
          echo '<p class="description">'.$description.'</p>';
        break;

        # 11) html
        case 'html':
          $ui_html = new UI_HTML( $value );
          echo $ui_html->render();
        break;

        # 12) map
        case 'map':
          $ui_map = new UI_Map( $value );
          echo $ui_map->render();
        break;

        default:
          echo "Ой! Что-то пошло не так... Возможно, неверно указан тип поля.";
          break;
      }

      echo '
        </td>
      </tr>';
    }
  }




  ## add_new_custom_fields
  public function add_new_custom_fields( $taxonomy_slug ){

    $fields = $this->options->fields;

    foreach ($fields as $value) {
      $field_type  = $value['field_type'];
      $title       = $value['title'] ? $value['title'] : '';
      $description = $value['description'] ? $value['description'] : '';

      // field setting
      $field_name          = $value['field_name'];
      $value['array_path'] = "wps_term_meta[{$field_name}]";

      echo '<div class="form-field">';

      switch ( $field_type ) {

        # 1) input 
        case 'input':
          echo '<label for="tag-title">'.$title.'</label>';
          $ui_input = new UI_Input( $value );
          echo $ui_input->render();
        break;

        # 2) textarea
        case 'textarea':
          echo '<label for="tag-title">'.$title.'</label>';
          $ui_textarea = new UI_Textarea( $value );
          echo $ui_textarea->render();
        break;

        # 3) checkbox
        case 'checkbox':
          echo '<label for="tag-title">'.$title.'</label>';
          $ui_checkbox = new UI_Checkbox( $value );
          echo $ui_checkbox->render();
        break;

        # 4) wp_editor
        case 'wp_editor':
        break;

        # 5) select
        case 'select':
          echo '<label for="tag-title">'.$title.'</label>';
          $ui_select = new UI_Select( $value );
          echo $ui_select->render();
        break;

        # 6) image
        case 'image':
          echo '<label for="tag-title">'.$title.'</label>';
          $ui_image = new UI_Image( $value );
          echo $ui_image->render();
        break;

        # 7) simple_gallery
        case 'simple_gallery':
        break;

        # 8) repeater
        case 'repeater':
        break;

        # 9) message
        case 'message':
          $ui_message = new UI_Message( $value );
          echo $ui_message->render();
        break;

        # 10) file
        case 'file':
          echo '<label for="tag-title">'.$title.'</label>';
          $ui_message = new UI_File( $value );
          echo $ui_message->render();
        break;

        # 999) hide_block
        case 'hide_block':
          $ui_hide_block = new UI_Hide_block( $value );
          echo $ui_hide_block->render();
        break;

        default:
          echo "Ой! Что-то пошло не так... Возможно, неверно указан тип поля.";
        break;
      }

      echo '</div>';
    }
  }
  

  ## save_custom_taxonomy_meta
  public function save_custom_taxonomy_meta( $term_id ) {
    if ( ! isset($_POST['wps_term_meta']) ) return;
    if ( ! current_user_can('edit_term', $term_id) ) return;

    foreach( $_POST['wps_term_meta'] as $key => $value ){
      if( empty($value) ){
        delete_term_meta( $term_id, $key );
        continue;
      }
      update_term_meta( $term_id, $key, $value );
    }
    return $term_id;
  }

}