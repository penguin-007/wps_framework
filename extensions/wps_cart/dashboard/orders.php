<?php


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
      'post_type' => 'wps_orders', // 1) custom-type name
      // labels
      'labels'    => array(
        'name'          => 'Заказы',
        'singular_name' => 'Заказы', 
        'menu_name'     => 'Заказы'
      ),
      // supports_label
      'supports_label' => array(
        'title',
      ),
      // rewrite
      'rewrite' => array(
        'slug'         => 'wps_orders', // 2) custom-type slug
        'with_front'   => false,
        'hierarchical' => true
      ),
      // general
      'general' => array(
        /* if need remove in query */
        'query_var'           => false, 
        'publicly_queryable'  => false,
        'exclude_from_search' => true,
        'taxonomies'        => array(''), // 3) 
        'menu_icon'         => 'dashicons-cart', // 4) https://developer.wordpress.org/resource/dashicons/
      )
    ),

  )
);




####################################################
################## Meta Box Example ################
####################################################
new WPS_MetaBox(
  array(
    'meta_box_name'   => 'Информация',                   
    'post_types'      => array( 'wps_orders' ),   
    'page_templates'  => array( ),
    'meta_box_groups' => array(
      // GROUP FIELD
      array(
        'title'    => '',
        'fields'   => array(

          array(
            'field_type'  => 'checkbox',
            'field_name'  => 'order_close',
            'title'       => 'Статус заказа: завершен',
            'description' => '',
          ),

          array(
            'field_type'  => 'wp_editor',
            'field_name'  => 'user_data',
            'title'       => 'Информация о заказе',
            'description' => '',
          ),

        )
      ),
      // GROUP FIELD
    )
  )
);



####################################################
################ PostColumns Example ###############
####################################################
new WPS_PostColumns(
  array(
    'post_type' => 'wps_orders',
    'fields'    => array(
      array(
        'field_type'   => 'text',
        'field_name'   => 'order_price',
        'columns_name' => 'Сумма'
      ),
      array(
        'field_type'   => 'checkbox',
        'field_name'   => 'order_close',
        'columns_name' => 'Завершен'
      ),
      array(
        'field_type'   => 'row_color',
        'field_name'   => 'order_close',
        'columns_name' => 'Row Color',
        'options'      => array(
          "on" => "rgba(130, 218, 185, 0.5)",
        )
      ),
    )
  )
);