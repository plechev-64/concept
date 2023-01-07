<?php

namespace USP\Init\Tabs;

use USP\Core\Tabs\AbstractTab;

class SecondTab extends AbstractTab {

	public function getContent(): string
	{
		return 'Содержимое вкладки';
	}

}