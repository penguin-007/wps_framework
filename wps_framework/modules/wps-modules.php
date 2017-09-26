<?php
/**
 *
 * @package   WPS_Framework
 * @version   1.0.0
 * @author    Alexander Laznevoy 
 * @copyright Copyright (c) 2017, Alexander Laznevoy
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

class WPS_Modules {

  function __construct() {

  }

}

/* mail */
require_once( 'wps_mail/wps_mail_init.php' );

/* seo */
require_once( 'wps_seo/wps_seo_init.php' );

/* wps_tinymc extends */
require_once( 'wps_tinymc/wps_tinymc_init.php' );

/* cart */
require_once( 'wps_cart/wps_cart_init.php' );