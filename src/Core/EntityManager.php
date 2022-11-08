<?php

namespace USP\Core;

use ReflectionException;
use USP\Core\Attributes\AttributesService;
use USP\Core\Attributes\Entity;
use USP\Core\Database\Where;

class EntityManager {

	private array $entities = [];
	private array $attributes = [];
	private AttributesService $attributesService;

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

		$instance->attributesService = new AttributesService();

		return $instance;
	}

	public function add( EntityAbstract $entity ): void {
		$this->entities[ $this->getEntityHash( $entity ) ] = $entity;
	}

	/**
	 * @throws ReflectionException
	 */
	private function getRepository( EntityAbstract $entity ): ?RepositoryAbstract {
		$attribute = $this->attributesService->getClassAttribute( $entity::class, Entity::class );

		if ( ! $attribute ) {
			return null;
		}

		$classRepo = $attribute->getArguments()['repository'] ?? null;

		if ( ! $classRepo ) {
			return null;
		}

		return new $classRepo();
	}

	/**
	 * @throws ReflectionException
	 */
	public function save(): void {
		if ( ! $this->entities ) {
			return;
		}

		/** @var EntityAbstract $entity */
		foreach ( $this->entities as $entityHash => $entity ) {

			$repository = $this->getRepository( $entity );

			if ( ! $repository ) {
				continue;
			}

			$request = $repository->getQueryBuilder();

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

	private function getEntityHash( EntityAbstract $entity ): string {
		return md5( serialize( $entity ) );
	}

	private function getDataFromEntity( EntityAbstract $entity ): array {

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