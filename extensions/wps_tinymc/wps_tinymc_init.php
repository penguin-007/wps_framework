<?php

// https://code.tutsplus.com/tutorials/guide-to-creating-your-own-wordpress-editor-buttons--wp-30182
add_action( 'init', 'wptuts_buttons' );
function wptuts_buttons() {
	add_filter( "mce_external_plugins", "wptuts_add_buttons" );
	add_filter( 'mce_buttons', 'wptuts_register_buttons' );
}
function wptuts_add_buttons( $plugin_array ) {
	$plugin_array['wptuts'] = trailingslashit( WPS_EXTENSIONS_URI ) . 'wps_tinymc/tinymc.js';
	return $plugin_array;
}
function wptuts_register_buttons( $buttons ) {
	//$buttons = array('formatselect', 'bold', 'removeformat', 'bullist', 'link', 'unlink', 'fullscreen', 'wp_adv');
	array_push( $buttons, 'wps_bold', 'collapse_btn' );
	return $buttons;
}
add_action( 'current_screen', 'my_theme_add_editor_styles' );
function my_theme_add_editor_styles() {
	add_editor_style( trailingslashit( WPS_EXTENSIONS_URI ) . 'wps_tinymc/tinymc-styles.css' );
}

/*
[0] => formatselect
	[1] => bold
	[2] => italic
	[3] => bullist
	[4] => numlist
	[5] => blockquote
	[6] => alignleft
	[7] => aligncenter
	[8] => alignright
	[9] => link
	[10] => unlink
	[11] => wp_more
	[12] => spellchecker
	[13] => fullscreen
	[14] => wp_adv
	[15] => to_span
*/