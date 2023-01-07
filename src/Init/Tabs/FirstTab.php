<?php

namespace USP\Init\Tabs;

use USP\Core\Tabs\AbstractTab;

class FirstTab extends AbstractTab {

	public function getContent(): string
	{
		return 'Содержимое вкладки';
	}

}