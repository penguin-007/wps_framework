<?php
/**
 * Functions for development.
 *
 * @package    WPS_Framework
 * @subpackage Functions
 * @author     Alexander Laznevoy 
 * @copyright  Copyright (c) 2017, Alexander Laznevoy
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Learn Performance.
 *
 * @since 1.0.0
 */
function show_performance( $text ) {
  $stat = sprintf('SQL: %d за %.3f sec. %.2fMB',
    get_num_queries(),
    timer_stop( 0, 3 ),
    memory_get_peak_usage() / 1024 / 1024
  );
  echo $stat;
}

/**
 * Print current template path.
 *
 * @since 1.0.0
 */
function show_template_path() {
	add_action( 'wp_footer', 'wps_show_template');
}
function wps_show_template(){
  echo "<br>";
  global $template;
  echo $template;
}

/**
 * Print_r between <pre></pre>
 *
 * @since 1.0.0
 */
function pre_print_r( $arg ) {
  $messages = array(
    "¯\_(ツ)_/¯",
    "><(((°>",
    "\з=(•̪●)=ε/",
    "(-'_'-)",
    "(✖╭╮✖)",
    "ಠ_ಠ",
    "(╥_╥)",
    "┌∩┐(◣_◢)┌∩┐",
    "¯\(°_o)/¯",
    "\(^o^)/",
  );
  if ( $arg ) {
    echo '<pre>';
    print_r( $arg );
    echo '</pre>';
  } else {
    echo 'Kek<br><br>';
    echo $messages[array_rand($messages)];
    echo '<br><br>Try again!';
  }
}