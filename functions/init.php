<?php

global $UserSpace;

use USP\Core\Tabs\AbstractTab;
use USP\Core\UserSpace;
use USP\Init\Tabs\FirstTab;
use USP\Init\Tabs\SecondTab;

$UserSpace = new UserSpace();

function USP(): UserSpace {
	global $UserSpace;
	return $UserSpace;
}

require_once 'shortcodes.php';

add_action('plugins_loaded', 'usp_load');
function usp_load(){

}

add_action('init', 'usp_init_script_style');
function usp_init_script_style(){
	wp_enqueue_script( 'usp-scripts', USP_URL . 'assets/script.js', [], USP_VERSION );
	wp_enqueue_style( 'usp-styles', USP_URL . 'assets/style.css', [], USP_VERSION );
}

add_action('init', 'usp_tabs_init');
function usp_tabs_init(){
	do_action('usp_tabs_init');
}

add_action('usp_tabs_init', function(){

	$tabManager = USP()->getTabsManager();

	$firstTab = (new FirstTab())
		->setId('firstTab')
		->setLabel('Первая вкладка');

	$secondTab = (new SecondTab())
		->setId('secondTab')
		->setLabel('Вторая вкладка');

	$tabManager->initTab($firstTab);
	$tabManager->initTab($secondTab);

});
