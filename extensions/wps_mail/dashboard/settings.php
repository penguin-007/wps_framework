<?php

/*
* Cart Settings
*/

add_action( 'wp_loaded', 'render_menu_mail', 1 );

function render_menu_mail(){
  new WPS_OptionPage(
    array(
      /* submenu_setting */
      'submenu_setting' => array(
        'submenupos' => "wps_framework",
        'page_title' => 'E-mail settings',
        'menu_title' => 'E-mail',
        'capability' => 'administrator',
        'menu_slug'  => 'wps_mail_module_settings',
      ),
      /* submenu_setting */
      'fields'    => array(

        array(
          'field_type'  => 'input',
          'field_name'  => 'theme_email',
          'title'       => 'Основной e-mail для писем',
          'description' => "Можно ввести несколько почтовых адресов через запятую"
        ),

        array(
          'field_type'   => 'checkbox',
          'field_name'   => 'mail_in_admin_panel',
          'title'        => 'Вывести почту в админ-панель?',
        ),

      )
    )
  );
}