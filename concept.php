<?php

/*
Plugin Name: User Space
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: A brief description of the Plugin.
Version: 1.0
Author: Andrey
Author URI: http://URI_Of_The_Plugin_Author
License: A "Slug" license name e.g. GPL2
*/

global $wpdb;
const USP_VERSION = '0.0.1';
define( 'USP_URL', trailingslashit( plugins_url( '/', __FILE__ ) ) );
define( 'USP_PREF', $wpdb->base_prefix . 'usp_' );
define( 'USP_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );

require_once 'vendor/autoload.php';
require_once 'functions/init.php';

add_action('wp', function(){

//	print_r(USP()->getTabsManager()->getTabs());

});
