<?php
/**
 * New Post Type Columns Class.
 *
 * @package   WPS_Framework
 * @version   1.0.0
 * @author    Alexander Laznevoy 
 * @copyright Copyright (c) 2017, Alexander Laznevoy
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * Allow type: text, checkbox, views
 *
 */


/* HOU USE
new WPS_PostColumns(
  array(
    'post_type' => 'custom_post',
    'fields'    => array(
      // FIELDS
    )
  )
);

// FIELDS
## text
array(
  'type'         => 'text',
  'meta_name'    => 'meta_name',
  'columns_name' => 'Заголовок'
),

## views
array(
  'type'         => 'views',
  'columns_name' => 'Просмотры'
),

## checkbox
array(
  'type'         => 'checkbox',
  'meta_name'    => 'checkbox',
  'columns_name' => 'checkbox'
),

*/

/*  TODO фильтрация колонок
http://shibashake.com/wordpress-theme/expand-the-wordpress-quick-edit-menu
## https://wp-kama.ru/id_995/dopolnitelnyie-sortiruemyie-kolonki-u-postov-v-adminke.html
*/

 
class WPS_PostColumns {

  private $options;
  private $post_type;

  function __construct( $option ) {
    // get all options
    $this->options = (object) $option;
    // get options post-type
    $this->post_type = $this->options->post_type;

    ################### Column Setting #################
    add_filter( "manage_{$this->post_type}_posts_custom_column", array( $this, 'fill_post_columns' ), 10, 2 );
    add_filter( "manage_edit-{$this->post_type}_columns", array( $this, 'add_post_columns' ) );

    ################### Column Sort #################
    add_filter( "manage_edit-{$this->post_type}_sortable_columns", array( $this, 'add_views_sortable_column') );
    add_filter( 'pre_get_posts', array( $this, 'add_column_views_request') );
  }

  

  ####################################################
  ################### Column Setting #################
  ####################################################
  public function add_post_columns( $columns ) {
    // get fields
    $fields = $this->options->fields;

    $date = $columns['date'];
    unset( $columns['date'] );
    $count  = 0;

    foreach ($fields as $value) {
      $columns["columns_title_{$count}"] = $value['columns_name'];
      $count++;
    }
    
    $columns['date'] = $date;
    return $columns;
  }


  public function fill_post_columns( $column, $postID ) {
    // get fields
    $fields = $this->options->fields;

    $count  = 0;
    foreach ($fields as $value) {
      if ( $column  === "columns_title_{$count}" ){
        switch ( $value['field_type'] ) {

          case 'views':
            echo wps__get_post_views( $postID );
          break;

          case 'row_color':
            $field_name = $value['field_name'];
            $options    = $value['options'];
            $key        = get_post_meta( $postID, $field_name, true );
            $value      = $key;
            $html       = "<span class='wps__row_color'>{$options[$value]}</span>";
            echo $html;
          break;

          case 'checkbox':
            $field_name = $value['field_name'];
            wps_ui_checkbox__ajax( $field_name, '' );
          break;

          case 'text':
            $field_name = $value['field_name'];
            echo get_post_meta( $postID, $field_name, true );
          break;

          case 'image':
            $field_name = $value['field_name'];
            echo wp_get_attachment_image( get_post_meta( $postID, $field_name, true ), array( 60, 60 ) );
          break;

          default:
            echo "Неверно указан тип поля.";
          break;
        }
      }
      $count++;
    }
  }


  ####################################################
  ##################### Column Sort ##################
  ####################################################
  function add_views_sortable_column($sortable_columns){
    $sortable_columns['columns_title_0'] = array('views', 'desc'); // desc - по умолчанию
    return $sortable_columns;
  }

  function add_column_views_request( $object ){
    if( $object->get('orderby') != 'views' ){
      return;
    }
    $object->set('meta_key', 'wps_post_views_count');
    $object->set('orderby', 'meta_value_num');
  }

}