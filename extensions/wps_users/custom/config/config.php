<?php
new WPS_CustomType(
    array(
        ## Create Files
        'create_archive_file' => false,
        'create_single_file'  => false,

        ## Post Type Register
        'register_post_type' => array(
            'post_type' => 'users', // 1) custom-type name
            // labels
            'labels'    => array(
                'name'          => 'Юзеры',
                'singular_name' => 'Юзеры',
                'menu_name'     => 'Юзеры'
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
                'slug'         => 'users', // 2) custom-type slug
                'with_front'   => false,
                'hierarchical' => true
            ),
            // general
            'general' => array(
                // if need remove in query
                //'query_var'           => false,
                'publicly_queryable'  => false,
                'exclude_from_search' => true,
                'taxonomies'        => array('victory_week','victory_type'), // 3)
                'menu_icon'         => 'dashicons-businessman', // 4) https://developer.wordpress.org/resource/dashicons/
            )
        ),

    )
);


new WPS_MetaBox(
    array(
        'meta_box_name'   => 'Информация о пользователе',
        'post_types'      => array( 'users' ),
        'page_templates'  => array(),
        'meta_box_groups' => array(
            // Group fields
            array(
                'title'    => 'Данные пользователя',
                'fields'   => array(
                    array(
                        'field_type'   => 'image',
                        'field_name'   => 'user_avatar',
                        'title'        => 'Аватар(фото)',
                    ),
                    array(
                        'field_type'   => 'input',
                        'field_name'   => 'user_phone',
                        'title'        => 'Телефон',
                        'type_input'   => 'text',
                    ),
                    array(
                        'field_type'   => 'input',
                        'field_name'   => 'user_city',
                        'title'        => 'Город',
                        'type_input'   => 'text',
                    ),
                    array(
                        'field_type'   => 'wp_editor',
                        'field_name'   => 'user_about',
                        'title'        => 'О себе',
                    ),
                )
            ),
            array(
                'title'    => 'Служебные данные',
                'fields'   => array(
                    array(
                        'field_type'   => 'input',
                        'field_name'   => 'user_code',
                        'title'        => 'SMS Code (Email code)',
                        'type_input'   => 'text',
                    ),
                    array(
                        'field_type'   => 'input',
                        'field_name'   => 'user_password',
                        'title'        => 'Пароль',
                        'type_input'   => 'text',
                    ),
                    array(
                        'field_type'   => 'input',
                        'field_name'   => 'user_email',
                        'title'        => 'Email',
                        'type_input'   => 'text',
                    ),
                    array(
                        'field_type'   => 'checkbox',
                        'field_name'   => 'user_verify',
                        'title'        => 'Верификация',
                        'class'        => 'wps_ui_checkbox_css',
                    ),
                )
            ),
            // Group fields
        )
    )
);

new WPS_PostColumns(
    array(
        'post_type' => 'users',
        'fields'    => array(
            array(
                'field_type'   => 'text',
                'field_name'   => 'user_email',
                'columns_name' => 'Email'
            ),
            array(
                'field_type'   => 'checkbox',
                'field_name'   => 'user_verify',
                'columns_name' => 'Верификация'
            ),
        )
    )
);
