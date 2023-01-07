<?php

namespace USP\Core\Tabs;

use JetBrains\PhpStorm\Pure;
use USP\Core\Collections\ArrayCollection;

class TabsManager {

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

}