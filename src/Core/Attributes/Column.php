<?php

namespace USP\Core\Attributes;

#[\Attribute] class Column {
	public string $name;
	public ?bool $primary;

	/**
	 * @param   string  $name
	 * @param   bool    $primary
	 */
	public function __construct( string $name, ?bool $primary = false ) {
		$this->name    = $name;
		$this->primary = $primary;
	}

}