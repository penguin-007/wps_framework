<?php

require 'update_checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
  'https://github.com/penguin-007/wps_framework',
  __FILE__,
  'wps_framework'
);

// kek