<?php


/**
 * Exampels to use.
 *
 * @package   WPS_Framework
 * @version   1.0.0
 * @author    Alexander Laznevoy 
 * @copyright Copyright (c) 2017, Alexander Laznevoy
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 */

// Документация: https://github.com/penguin-007/WP_StartTheme/wiki


####################################################
################ Custom Type Example ###############
####################################################
new WPS_CustomType(
  array(
    /* Create Files */
    'create_archive_file' => false,
    'create_single_file'  => false,

    /* Post Type Register */
    'register_post_type' => array(
      'post_type' => 'example_post_type', // 1) custom-type name
      // labels
      'labels'    => array(
        'name'          => 'Example Post Type',
        'singular_name' => 'Example Post Type', 
        'menu_name'     => 'Example Post Type'
      ),
      // supports_label
      'supports_label' => array(
        'title',
        //'thumbnail', 
        //'editor',
        //'custom-fields',
      ),
      // rewrite
      'rewrite' => array(
        'slug'         => 'example_post_type', // 2) custom-type slug
        'with_front'   => false,
        'hierarchical' => true
      ),
      // general
      'general' => array(
        /* if need remove in query
        'query_var'         => false, 
        'publicly_queryable'  => false,
        'exclude_from_search' => true,
        */
        'taxonomies'        => array('example_tax'), // 3) 
        'menu_icon'         => 'dashicons-star-empty', // 4) https://developer.wordpress.org/resource/dashicons/
      )
    ),

    /* Create Taxonomy */
    'register_taxonomy' => array(
      // tax
      array (
        'taxonomy_name' => 'example_tax',         // 1) 
        'setting' => array(
          'label'             => 'Example tax', // 2) 
          'hierarchical'      => true,
          'public'            => true,
          'query_var'         => true,
          'rewrite'           => array( 
            'slug'         => 'example_tax',      // 3)
            'with_front'   => true,
            'hierarchical' => true
          ),
          'show_admin_column' => true, 
          'show_ui'           => true 
        )
      ),
      // tax
    )

  )
);




####################################################
################## Meta Box Example ################
####################################################
new WPS_MetaBox(
  array(
    'meta_box_name'   => 'Заголовок (обязательно)',                   
    'post_types'      => array( 'example_post_type' ),   
    'page_templates'  => array( 'templates/example-page.php' ),
    'meta_box_groups' => array(
      // GROUP FIELD
      array(
        'title'    => 'Подзаголовок (не обязательно)',
        'fields'   => array(

          array(
            'field_type'  => 'input',
            'field_name'  => 'input',
            'title'       => 'UI_Input',
            'description' => 'input desc',
          ),

          array(
            'field_type'  => 'textarea',
            'field_name'  => 'textarea',
            'title'       => 'UI_Textarea',
            'description' => 'textarea desc',
            'editor'      => true
          ),

          array(
            'field_type'  => 'checkbox',
            'field_name'  => 'checkbox',
            'title'       => 'UI_Checkbox',
            'description' => 'checkbox desc',
          ),

          array(
            'field_type'  => 'wp_editor',
            'field_name'  => 'wp_editor',
            'title'       => 'UI_WP_editor',
            'description' => 'wp_editor desc',
          ),

          array(
            'field_type'  => 'select',
            'field_name'  => 'select',
            'title'       => 'UI_Select',
            'description' => 'select desc',
            'multiple'     => false,
            'options'     => array(
              'key'  => 'val',
              'key1' => 'val1',
              'key2' => 'val2',
            )
          ),

          array(
            'field_type'  => 'image',
            'field_name'  => 'image',
            'title'       => 'UI_Image',
            'description' => 'image desc',
          ),

          array(
            'field_type'  => 'simple_gallery',
            'field_name'  => 'simple_gallery',
            'title'       => 'UI_SimpleGallery',
            'description' => 'simple_gallery desc',
          ),

          array(
            'field_type'  => 'repeater',
            'field_name'  => 'repeater',
            'title'       => 'UI_Repeater',
            'description' => 'repeater desc',
            'fields'      => array(
              array(
                'field_type'  => 'image',
                'field_name'  => 'image',
                'title'       => 'image title',
                'description' => 'image desc',
              ),
              array(
                'field_type'  => 'input',
                'field_name'  => 'input',
                'title'       => 'input title',
                'description' => 'input desc',
              ),
              array(
                'field_type'  => 'textarea',
                'field_name'  => 'textarea',
                'title'       => 'textarea title',
                'description' => 'textarea desc',
                'editor'      => false
              ),
              array(
                'field_type'   => 'select',
                'field_name'   => 'select',
                'title'        => 'select title',
                'options'      => array(
                  'key'  => 'val',
                  'key1' => 'val1',
                  'key2' => 'val2',
                )
              ),
            )
          ),

          array(
            'field_type'   => 'message', 
            'message'      => 'Сообщение!',
          ),

          array(
            'field_type'  => 'file',
            'field_name'  => 'file',
            'title'       => 'UI_File',
            'description' => 'file desc',
          ),

          array(
            'field_type'  => 'button',
            'btn_value'   => 'Чудо Кнопка',
            'class'       => 'my_button_class',
            'id'          => 'my_button_id',
            'title'       => 'button title',
            'description' => 'button desc',
          ),

          array(
            'field_type'  => 'button',
            'btn_value'   => 'Чудо Кнопка + Ajax',
            'title'       => 'button + ajax title',
            'description' => 'button + ajax desc',
            'confirm'     => "Вы точно этого хотите?",
            // if need ajax
            'ajax'        => true,
            'ajax_action' => 'button_ajax_action',
            'set_timeout' => 1000
          ),

        )
      ),
      // GROUP FIELD
    )
  )
);




####################################################
################ Term Fields Example ###############
####################################################
new WPS_TermFields( 
  array(
    'taxonomy'  => array( 'example_tax' ),
    'fields'    => array(
      // FIELDS
      array(
        'field_type'  => 'input',
        'field_name'  => 'input',
        'title'       => 'UI_Input',
        'description' => 'input desc',
      ),
    )
  )
);



####################################################
################ OptionPage Example ################
####################################################
new WPS_OptionPage(
  array(
    /* menu_setting */
    'menu_setting' => array(
      'page_title' => 'Пример страницы',
      'menu_title' => 'Пример страницы',
      'capability' => 'administrator',
      'menu_slug'  => 'wps_theme_settings_test',
    ),
    // FIELDS
    'fields'    => array(

      array(
        'field_type'  => 'input',
        'field_name'  => 'input',
        'title'       => 'UI_Input',
        'description' => 'input desc',
      ),

    )
  )
);

new WPS_OptionPage(
  array(
    /* submenu_setting */
    'submenu_setting' => array(
      'submenupos' => "wps_theme_settings_test",
      //'submenupos' => "edit.php?post_type=custom_post2",
      'page_title' => 'Пример подстраницы',
      'menu_title' => 'Пример подстраницы',
      'capability' => 'administrator',
      'menu_slug'  => 'wps_theme_settings_sub_test',
    ),
    // FIELDS
    'fields'    => array( 
      array(
        'field_type'  => 'input',
        'field_name'  => 'input',
        'title'       => 'UI_Input',
        'description' => 'input desc',
      ),
    )
  )
);



####################################################
################ PostColumns Example ###############
####################################################
new WPS_PostColumns(
  array(
    'post_type' => 'example_post_type',
    'fields'    => array(
      array(
        'field_type'   => 'views',
        'columns_name' => 'Просмотры'
      ),
      // views первый
      array(
        'field_type'   => 'image',
        'field_name'   => 'image',
        'columns_name' => 'image'
      ),
      array(
        'field_type'   => 'checkbox',
        'field_name'   => 'checkbox',
        'columns_name' => 'checkbox'
      ),
      array(
        'field_type'   => 'text',
        'field_name'   => 'seo_post_title',
        'columns_name' => 'SEO Title'
      ),
      array(
        'field_type'   => 'row_color',
        'field_name'   => 'checkbox',
        'columns_name' => 'Row Color',
        'options'      => array(
          "on" => "rgba(130, 218, 185, 0.3)",
          ""   => "rgba(255, 90, 90, 0.3)",
        )
      ),
    )
  )
);



// пример добавления полей на страницу опций
add_filter( "option_page_content__before", "add_to_mail_module", 1, 2 );

function add_to_mail_module( $arr, $slug ){
  $arr = array(
    array(
      'field_type'  => 'input',
      'field_name'  => 'theme_email_2',
      'title'       => 'Второй E-mail для писем',
      'description' => "Можно ввести несколько почтовых адресов через запятую"
    ),
  );

  if ( $slug == "wps_mail_module_settings" ){
    return $arr;
  }
}