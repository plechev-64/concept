<?php

namespace USP\Core\Attributes;

use USP\Core\EntityAbstract;

#[\Attribute] class Column {
	public string $name;
	public ?bool $primary = null;
	public ?string $entityClass = null;

	/**
	 * @param   string $name
	 * @param   bool|false $primary
	 * @param   EntityAbstract|null $entity
	 */
	public function __construct( string $name, ?bool $primary = false, ?string $entityClass = null ) {
		$this->name    = $name;
		$this->primary = $primary;
		$this->entityClass = $entityClass;
	}

}