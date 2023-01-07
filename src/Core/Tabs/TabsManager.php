<?php

namespace USP\Core\Tabs;

use JetBrains\PhpStorm\Pure;
use USP\Core\Button;
use USP\Core\Collections\ArrayCollection;

class TabsManager {

	public const GROUP_MAIN = 'main';
	public const GROUP_COUNTERS = 'counters';
	public const GROUP_ACTIONS = 'actions';

	private ArrayCollection $tabs;

	#[Pure] public function __construct() {
		$this->tabs = new ArrayCollection();
	}

	/**
	 * @param   AbstractTab  $tab
	 */
	public function initTab(AbstractTab $tab): void
	{
		if(!$this->tabs->contains($tab)){
			$this->tabs->add($tab);
		}
	}

	/**
	 * @return ArrayCollection
	 */
	public function getTabs(): ArrayCollection {
		return $this->tabs;
	}

	public function getGroupButtons(string $groupName): ArrayCollection
	{
		$buttons = new ArrayCollection();

		$groupTabs = $this->tabs->filter(function(AbstractTab $tab) use ($groupName){
			return $tab->getGroup() === $groupName;
		});

		if($groupTabs->count()){
			/** @var AbstractTab $tab */
			foreach($groupTabs as $tab){

				$button = (new Button())
					->setId('usp-button-tab-' . $tab->getId())
					->setLabel($tab->getLabel())
					->setTitle($tab->getLabel());

				$buttons->add($button);
			}
		}

		return $buttons;

	}

}