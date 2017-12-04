<?php

require 'update_checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
  'https://github.com/penguin-007/wps_framework',
  __FILE__,
  'wps_framework'
);

/* WPS session_start */
add_action( 'init', 'wps__start_session' );
function wps__start_session() {
  if(!session_id()) {
    session_start();
  }
}