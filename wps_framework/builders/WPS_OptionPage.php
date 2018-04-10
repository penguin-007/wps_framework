<?php
/**
 * Option Page Builder Class.
 *
 * @package   WPS_Framework
 * @version   1.0.0
 * @author    Alexander Laznevoy 
 * @copyright Copyright (c) 2017, Alexander Laznevoy
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 *
 */

 
class WPS_OptionPage {

  private $options;
  private $slug;
  private $name;
  
  function __construct( $option ) {
    $this->options = (object) $option;

    add_action( 'admin_menu', array( $this, 'add_menu_page') );
    add_action( 'wp_ajax_wps_save_option_form', array( $this, 'wps_save_option_form' ) );
  }
  
  /* add_menu_page */
  public function add_menu_page(){

    if ( !empty( $this->options->menu_setting ) ){
      $menu_setting    = $this->options->menu_setting;
    }
    if ( !empty( $this->options->submenu_setting ) ){
      $submenu_setting = $this->options->submenu_setting;
    }

    /* if menu page */
    if ( !empty( $menu_setting ) && is_array( $menu_setting) ){
      $this->slug = $menu_setting['menu_slug'];
      $this->name = $menu_setting['page_title'];

      $icon_menu = isset($menu_setting['icon']) ? $menu_setting['icon'] : "";

      add_menu_page(
        $menu_setting['page_title'],
        $menu_setting['menu_title'],
        $menu_setting['capability'],
        $menu_setting['menu_slug'],
        array( $this, 'option_page_content' ),
        $icon_menu
      );  
    }

    /* if submenu page */
    if ( !empty( $submenu_setting ) && is_array( $submenu_setting) ){
      $this->slug = $submenu_setting['menu_slug'];
      $this->name = $submenu_setting['page_title'];

      $icon_submenu = isset($icon_submenu['icon']) ? $icon_submenu['icon'] : "";

      add_submenu_page(
        $submenu_setting['submenupos'],
        $submenu_setting['page_title'],
        $submenu_setting['menu_title'],
        $submenu_setting['capability'],
        $submenu_setting['menu_slug'],
        array( $this, 'option_page_content'),
        $icon_submenu
      );
    }

  }

  public function option_page_content() {
    // get slug
    $slug    = $this->slug;
    $fields  = $this->options->fields;
    $data    = get_option( $slug );
    // create array
    $array = array();

    // add field before
    $more_fiels_before = apply_filters( 'option_page_content__before', $array, $slug );
    if ( !empty( $more_fiels_before ) && is_array( $more_fiels_before ) ){
      $fields = array_merge($more_fiels_before, $fields);
    }

    // add field after
    $more_fiels_after = apply_filters( 'option_page_content__after', $array, $slug );
    if ( !empty( $more_fiels_after ) && is_array( $more_fiels_after ) ){
      $fields = array_merge($fields, $more_fiels_after);
    }
  ?>

  <div class="wps_option_wrapper">
    <span class="wps_option_title"><?= $this->name; ?></span>
    <div class="wps_option_wrap">
      <form class="wps_option_form" data-scr="<?= $slug; ?>">
        <?php
        foreach ($fields as $value) {
          $field_type  = $value['field_type'];
          $title       = isset($value['title']) && $value['title'] != "" ? $value['title'] : '';
          $description = isset($value['description']) ? $value['description'] : '';

          // field setting
          $field_name          = isset($value['field_name']) && $value['field_name'] != "" ? $value['field_name'] : '';
          $value['save_key']   = $slug;
          $value['array_path'] = $slug."[{$field_name}]";
          $value['value']      = isset($data[$field_name]) && $data[$field_name] != '' ? $data[$field_name] : ""; 

          echo '<div class="wps_option_row">';
          echo "<p>$title</p>";

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
              $ui_message = new UI_File( $value );
              echo $ui_message->render();
            break;

            # 11) button
            case 'button':
              $ui_button = new UI_Button( $value );
              echo $ui_button->render();
            break;

            # 12) html
            case 'html':
              $ui_html = new UI_HTML( $value );
              echo $ui_html->render();
            break;

            # 13) map
            case 'map':
              $ui_map = new UI_Map( $value );
              echo $ui_map->render();
            break;

            default:
              echo "Ой! Что-то пошло не так... Возможно, неверно указан тип поля.";
              break;
          }
          echo '<p class="wps_description_field">'.$description.'</p>';
          echo '</div>';
        }
        ?>
        <div class="wps_option_row">
          <button type="submit" class="wps_option_submit" >Сохранить <span></span></button>
        </div>
      </form>
    </div>
  </div>

  <?php 
  }

  public function wps_save_option_form(){
    parse_str( $_POST['data'], $values);
    $curscr =  $_POST['curscr'];
    $values = $values[$curscr];
    update_option( $curscr, $values );
  }

}