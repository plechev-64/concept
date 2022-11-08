<?php

namespace USP\Core\Attributes;

#[\Attribute] class Entity {
	public string $repository;

	/**
	 * @param   string  $repository
	 */
	public function __construct( string $repository = '' ) {
		$this->repository = $repository;
	}


}