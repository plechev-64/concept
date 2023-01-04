<?php

require_once 'shortcodes.php';

add_action('plugins_loaded', 'usp_load');
function usp_load(){

}

add_action('init', 'usp_init_script_style');
function usp_init_script_style(){
	wp_enqueue_script( 'usp-scripts', USP_URL . 'assets/script.js', [], USP_VERSION );
	wp_enqueue_style( 'usp-styles', USP_URL . 'assets/style.css', [], USP_VERSION );
}
