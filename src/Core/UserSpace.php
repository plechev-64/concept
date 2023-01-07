<?php

namespace USP\Core;

use USP\Core\Container\Container;
use USP\Core\Tabs\TabsManager;

final class UserSpace {

	private function getContainer(): Container
	{
		return Container::getInstance();
	}

	public function getEntityManager(): EntityManager
	{
		return $this->getContainer()->get(EntityManager::class);
	}

	public function getTabsManager(): TabsManager
	{
		return $this->getContainer()->get(TabsManager::class);
	}

}