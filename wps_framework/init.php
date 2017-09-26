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
        'message'      => 'Документация <a href="https://github.com/penguin-007/WP_StartTheme/wiki" target="_blank">WPS Framework</a>',
      ),
    )
  )
);
