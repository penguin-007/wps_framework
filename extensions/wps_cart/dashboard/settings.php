<?php

/*
* Cart Settings
*/

add_action( 'wp_loaded', 'render_menu_cart', 1 );

function render_menu_cart(){
  new WPS_OptionPage(
    array(
      // submenu_setting 
      'submenu_setting' => array(
        'submenupos' => "wps_framework",
        // если нужно добавить в меню типа записей - 'submenupos' => "edit.php?post_type=custom_post2",
        'page_title' => 'Настройки корзины',
        'menu_title' => 'Магазин',
        'capability' => 'administrator',
        'menu_slug'  => 'wps_cart_module_settings',
      ),
      // submenu_setting 
      'fields'    => array(
        // FIELDS
        array(
          'field_type'   => 'input',
          'field_name'   => 'general_email',
          'title'        => 'E-mail для заказов',
          'description'  => 'Можно ввести несколько почтовых адресов через запятую',
        ),
        array(
          'field_type'   => 'input',
          'field_name'   => 'currency',
          'title'        => 'Валюта',
          'description'  => 'Например, "грн"',
          'def_value'    => 'грн'
        ),
        array(
          'field_type'   => 'input',
          'field_name'   => 'slug_page_thanks',
          'title'        => 'Страница благодарности',
          'description'  => 'Например, "thanks-for-order". Или оставьте поле пустым.',
        ),
      )
    )
  );
}