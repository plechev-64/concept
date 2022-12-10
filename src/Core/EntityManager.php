<?php

namespace USP\Core;

use ReflectionException;
use USP\Core\Attributes\AttributesService;
use USP\Core\Attributes\Entity;
use USP\Core\Container\Container;
use USP\Core\Database\Where;

class EntityManager {

	private array $entities = [];
	private AttributesService $attributesService;
	private Container $container;

	/**
	 * @return array
	 */
	public function getEntities(): array {
		return $this->entities;
	}

	public function __construct(
		Container $container,
		AttributesService $attributesService
	) {
		$this->attributesService = $attributesService;
		$this->container         = $container;
	}

	public function add( EntityAbstract $entity ): void {
		$this->entities[ $this->getEntityHash( $entity ) ] = $entity;
	}

	public function getRepository( string $entityClass ): ?RepositoryAbstract {

		try {
			$attribute = $this->attributesService->getClassAttribute( $entityClass, Entity::class );
		}
		catch ( ReflectionException $e ) {
			return null;
		}

		if ( ! $attribute || ! $classRepo = $attribute->getArguments()['repository'] ?? null ) {
			return null;
		}

		return $this->container->get( $classRepo );
	}

	public function save(): void {
		if ( ! $this->entities ) {
			return;
		}

		/** @var EntityAbstract $entity */
		foreach ( $this->entities as $entityHash => $entity ) {

			$repository = $this->getRepository( $entity::class );

			if ( ! $repository ) {
				continue;
			}

			$request = $repository->createQueryBuilder();

			if ( ! $entity->getId() ) {
				$request->insert( $this->getDataFromEntity( $entity ) );
			} else {
				if ( $entityHash !== $this->getEntityHash( $entity ) ) {
					$colNames = array_values($request->getTable()->getCols());
					$request
						->addWhere( array_shift($colNames), Where::OPERATOR_EQUAL, $entity->getId() )
						->update( $this->getDataFromEntity( $entity ) );
				}
			}
		}

	}

	private function getEntityHash( EntityAbstract $entity ): string {
		return md5( serialize( $entity ) );
	}

	private function getDataFromEntity( EntityAbstract $entity ): array {

		$columnPropertyMap = $this->attributesService->getColumnPropertyMap( $entity::class );

		$data = [];
		foreach ( $columnPropertyMap as $propertyName => $attributes ) {

			$getterName = 'get' . ucfirst( $propertyName );

			if ( ! method_exists( $entity, $getterName ) ) {
				continue;
			}

			$data[ $attributes['name'] ] = $entity->$getterName();

		}

		return $data;
	}


}