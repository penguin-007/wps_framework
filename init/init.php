<?php

/**
 * Set default setting framework.
 *
 * @package   WPS_Framework
 * @version   1.0.0
 * @author    Alexander Laznevoy 
 * @copyright Copyright (c) 2017, Alexander Laznevoy
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 *
 */


// load common script for admin panel
add_action( 'admin_enqueue_scripts', 'enqueue_admin_scripts' );
## Add script to admin panel
function enqueue_admin_scripts() {
  wp_enqueue_script( 'wps_admin_script', trailingslashit( WPS_ASSETS_URI ) . 'wps.admin.script.js', array('jquery'), WPS_VERSION, true );
  wp_enqueue_style ( 'wps_admin_style',  trailingslashit( WPS_ASSETS_URI ) . 'wps.admin.style.css', array(), WPS_VERSION, null );
}

/**
 * Grab our framework options as registered by the theme.
 * By default everything is turned off.
 */
$defaults = array(
  "wps__example_config" => false,       
  'wps__extends_mail'   => false,
  'wps__extends_seo'    => false,
  'wps__extends_cart'   => false,
  'wps__extends_likes'  => false,
  'wps__extends_tinymc' => true,
  'wps__shortcodes'     => true,
    'wps__users'        => false,
);

$framework_options = wp_parse_args( get_option('wps_framework_options'), $defaults);

/* example_config */
if( true == $framework_options['wps__example_config'] ){  
  require_once( PARENT_DIR.'/wps_config/examlpe.php' );
}

/* mail */
if( true == $framework_options['wps__extends_mail'] ){  
  require_once( WPS_EXTENSIONS.'/wps_mail/wps_mail_init.php' );
}

/* seo */
if( true == $framework_options['wps__extends_seo'] ){  
  require_once( WPS_EXTENSIONS.'/wps_seo/wps_seo_init.php' );
}

/* cart */
if( true == $framework_options['wps__extends_cart'] ){  
  require_once( WPS_EXTENSIONS.'/wps_cart/wps_cart_init.php' );
}

/* likes */
if( true == $framework_options['wps__extends_likes'] ){  
  require_once( WPS_EXTENSIONS.'/wps_likes/wps_likes_init.php' );
}

/* tinymc  */
if( true == $framework_options['wps__extends_tinymc'] ){  
  require_once( WPS_EXTENSIONS.'/wps_tinymc/wps_tinymc_init.php' );
}

/* shortcode */
if( true == $framework_options['wps__shortcodes'] ){  
  require_once( WPS_EXTENSIONS.'/wps_shortcodes/shortcodes.php' );
}

/*users*/
if( true == $framework_options['wps__users'] ){
    require_once( WPS_EXTENSIONS.'/wps_users/wps_users_init.php' );
}


####################################################
################  Main Option Page  ################
####################################################
new WPS_OptionPage(
  array(
    /* menu_setting */
    'menu_setting' => array(
      'page_title' => 'Настройки темы',
      'menu_title' => 'Настройки темы',
      'capability' => 'administrator',
      'menu_slug'  => 'wps_theme_main_settings',
    ),
    /* menu_setting */
    'fields'    => array(

      array(
        'field_type'  => 'image',
        'field_name'  => 'theme_favicon',
        'title'       => 'Favicon',
      ),

    )
  )
);


####################################################
################  WPS Page  ########################
####################################################
new WPS_OptionPage(
  array(
    /* menu_setting */
    'menu_setting' => array(
      'page_title' => 'WPS Framework / v'.WPS_VERSION,
      'menu_title' => 'WPS',
      'capability' => 'administrator',
      'menu_slug'  => 'wps_framework',
      'icon'       => WPS_ASSETS_URI.'/img/framework_ico.png'
    ),
    /* menu_setting */
    'fields'    => array(
      array(
        'field_type'   => 'message', 
        'message'      => 'Документация <a href="https://github.com/penguin-007/wps_framework/wiki" target="_blank">WPS Framework</a>',
      ),
    )
  )
);


####################################################
################  WPS Settings  ####################
####################################################
new WPS_OptionPage(
  array(
    // submenu_setting 
    'submenu_setting' => array(
      'submenupos' => "wps_framework",
      // если нужно добавить в меню типа записей - 'submenupos' => "edit.php?post_type=custom_post2",
      'page_title' => 'WPS settings',
      'menu_title' => 'WPS settings',
      'capability' => 'administrator',
      'menu_slug'  => 'wps_framework_options',
    ),
    // submenu_setting 
    'fields'    => array(
      // FIELDS
      array(
        'field_type'   => 'message',
        'type_message' => 'info', 
        'message'      => 'Модули:',
      ),
      array(
        'field_type'   => 'checkbox',
        'field_name'   => 'wps__example_config',
        'title'        => 'Example Config',
        'description'  => 'Подключить демонстрационный файл настроек'
      ),
      array(
        'field_type'   => 'checkbox',
        'field_name'   => 'wps__extends_mail',
        'title'        => 'Почта',
        'description'  => '',
      ),
      array(
        'field_type'   => 'checkbox',
        'field_name'   => 'wps__extends_seo',
        'title'        => 'SEO',
        'description'  => 'Мета-поля для страниц архивов',
      ),
      array(
        'field_type'   => 'checkbox',
        'field_name'   => 'wps__extends_cart',
        'title'        => 'Корзина',
        'description'  => '',
      ),
      array(
        'field_type'   => 'checkbox',
        'field_name'   => 'wps__extends_likes',
        'title'        => 'Лайки',
        'description'  => '',
      ),
        array(
            'field_type'   => 'checkbox',
            'field_name'   => 'wps__users',
            'title'        => 'Регистрация',
            'description'  => "или скачайте шаблон для своей регистрации". "<a target='__blank' href=> Сюда надо вставить ссылку на гитхаб.</a> ",
        ),
    )
  )
);