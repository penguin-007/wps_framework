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

/**
 * Grab our framework options as registered by the theme.
 * By default everything is turned off.
 */
$defaults = array(
  "wps__example_config" => false,       
  'wps__extends_mail'   => false,
  'wps__extends_seo'    => false,
  'wps__extends_tinymc' => true,
);

$framework_options = wp_parse_args( get_option('wps_framework_options'), $defaults);

/* example_config */
if( '1' == $framework_options['wps__example_config'] ){  
  require_once( PARENT_DIR.'/wps_config/examlpe.php' );
}

/* mail */
if( '1' == $framework_options['wps__extends_mail'] ){  
  require_once( WPS_EXTENSIONS.'/wps_mail/wps_mail_init.php' );
}

/* seo */
if( '1' == $framework_options['wps__extends_seo'] ){  
  require_once( WPS_EXTENSIONS.'/wps_seo/wps_seo_init.php' );
}

/* wps_tinymc extends */
if( '1' == $framework_options['wps__extends_tinymc'] ){  
  require_once( WPS_EXTENSIONS.'/wps_tinymc/wps_tinymc_init.php' );
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
################  WPS Option  ######################
####################################################
new WPS_OptionPage(
  array(
    /* menu_setting */
    'menu_setting' => array(
      'page_title' => 'WPS Framework / v'.WPS_VERSION,
      'menu_title' => 'WPS',
      'capability' => 'administrator',
      'menu_slug'  => 'wps_framework',
      'icon'       => "dashicons-carrot"
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