<?php

use USP\Core\Tabs\TabsManager;
use USP\Core\Template\Template;

add_shortcode('userspace', 'usp_get_shortcode_userspace');
function usp_get_shortcode_userspace(): ?string {

	$tabsManager = USP()->getTabsManager();

	return (new Template('office'))->getContent([
		'mainMenuButtons' => $tabsManager->getGroupButtons(TabsManager::GROUP_MAIN),
		'counterMenuButtons' => $tabsManager->getGroupButtons(TabsManager::GROUP_COUNTERS),
		'actionMenuButtons' => $tabsManager->getGroupButtons(TabsManager::GROUP_ACTIONS),
	]);
}
