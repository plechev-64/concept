<?php

namespace USP\Core;

use USP\Core\Database\Where;

class EntityManager {

	private array $entities = [];

	/**
	 * @return array
	 */
	public function getEntities(): array {
		return $this->entities;
	}

	public static function getInstance() {
		static $instance;

		if ( null === $instance ) {
			$instance = new self();
		}

		return $instance;
	}

	private function __construct() {
		static $hasInstance = false;

		if ( $hasInstance ) {
			return;
		}

		$hasInstance = true;
	}

	public function add( Entity $entity ): void {
		$this->entities[ $this->getEntityHash( $entity ) ] = $entity;
	}

	public function save(): void {
		if ( ! $this->entities ) {
			return;
		}

		/** @var Entity $entity */
		foreach ( $this->entities as $entityHash => $entity ) {
			$classRepo = $entity->getRepository();

			/** @var Repository $repository */
			$repository = new $classRepo();

			$request = $repository->getRequestBuilder();

			if ( ! $entity->getId() ) {
				$request->insert( $this->getDataFromEntity( $entity ) );
			} else {
				if ( $entityHash !== $this->getEntityHash( $entity ) ) {
					$request
						->addWhere( $request->getTable()->getCols()[0], Where::OPERATOR_EQUAL, $entity->getId() )
						->update( $this->getDataFromEntity( $entity ) );
				}
			}
		}

	}

	private function getEntityHash( Entity $entity ): string {
		return md5( serialize( $entity ) );
	}

	private function getDataFromEntity( Entity $entity ): array {

		$data = [];
		foreach ( get_class_methods( $entity ) as $methodName ) {

			if ( ! \str_starts_with( $methodName, 'set' ) ) {
				continue;
			}

			$clearMethodName = str_replace( 'set', '', $methodName );

			$getterName = 'get' . $clearMethodName;

			if ( ! method_exists( $entity, $getterName ) ) {
				continue;
			}

			$colsName = strtolower( preg_replace( [
				'/([a-z\d])([A-Z])/',
				'/([^_])([A-Z][a-z])/'
			], '$1_$2', $clearMethodName ) );

			$data[ $colsName ] = $entity->$getterName();

		}

		return $data;
	}
}