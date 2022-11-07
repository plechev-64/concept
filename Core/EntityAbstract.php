<?php

namespace USP\Core;

use ReflectionClass;
use USP\Core\Attributes\Column;

abstract class EntityAbstract {

	private array $extraData = [];

	/**
	 * @return array
	 */
	public function getExtraData(): array {
		return $this->extraData;
	}

	/**
	 * @param   array  $extraData
	 *
	 * @return EntityAbstract
	 */
	public function setExtraData( array $extraData ): EntityAbstract {
		$this->extraData = $extraData;

		return $this;
	}

	public function addExtraData(string $key, mixed $value): EntityAbstract{
		$this->extraData[$key] = $value;

		return $this;
	}

}