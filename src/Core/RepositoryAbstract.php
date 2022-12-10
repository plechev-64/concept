<?php

namespace USP\Core;

use ReflectionException;
use USP\Core\Attributes\AttributesService;
use USP\Core\Attributes\Column;
use USP\Core\Collections\ArrayCollection;
use USP\Core\Database\QueryBuilder;
use USP\Core\Database\DatabaseTable;
use USP\Core\Database\Where;

abstract class RepositoryAbstract {

	private AttributesService $attributesService;
	private EntityManager $entityManager;

	abstract public function getEntityClassName(): string;

	abstract public function getTableName(): string;

	public function __construct(
		AttributesService $attributesService,
		EntityManager $entityManager
	) {
		$this->entityManager     = $entityManager;
		$this->attributesService = $attributesService;
	}

	/**
	 * @param   string|null  $as
	 *
	 * @return QueryBuilder
	 */
	public function createQueryBuilder( ?string $as = null ): QueryBuilder {

		$table = ( new DatabaseTable() )
			->setAs( $as )
			->setName( $this->getTableName() )
			->setCols( array_map( function ( $item ) {
				return $item['name'];
			}, $this->attributesService->getColumnPropertyMap( $this->getEntityClassName() ) ) );

		return new QueryBuilder( $table );
	}

	public function find( int $id ): ?EntityAbstract {
		$table    = $this->createQueryBuilder()->getTable();
		$colNames = array_values( $table->getCols() );

		return $this->findOneBy( [
			[ array_shift( $colNames ), Where::OPERATOR_EQUAL, $id ]
		] );
	}

	public function findOneBy( array $conditions ): ?EntityAbstract {

		$query = $this->createQueryBuilder();
		foreach ( $conditions as $condition ) {
			$query->addWhere( $condition[0], $condition[1], $condition[2] );
		}

		$results = $query->getResults();

		if ( ! $results->count() ) {
			return null;
		}

		return $this->fillEntities( $results )->first();

	}

	public function findAllBy( array $conditions ): ?ArrayCollection {
		$query = $this->createQueryBuilder();
		foreach ( $conditions as $condition ) {
			$query->addWhere( $condition[0], $condition[1], $condition[2] );
		}

		$results = $query->getResults();

		if ( ! $results->count() ) {
			return null;
		}

		return $this->fillEntities( $results );
	}

	protected function fillEntities( ArrayCollection $entitiesData ): ArrayCollection {

		$this->fillColumnEntities( $entitiesData );

		$data = [];
		foreach ( $entitiesData as $entityData ) {
			$data[] = $this->fillEntity( $entityData );
		}

		return new ArrayCollection( $data );
	}

	protected function fillColumnEntities( ArrayCollection $parentEntitiesData ): void {

		$columnPropertyMap = $this->attributesService->getColumnPropertyMap( $this->getEntityClassName() );
		$columnEntitiesMap = array_filter( $columnPropertyMap, function ( $item ) {
			return isset( $item['entityClass'] );
		} );

		if ( ! $columnEntitiesMap ) {
			return;
		}

		foreach ( $columnEntitiesMap as $attributes ) {
			$propertyEntityClass = $attributes['entityClass'];
			$columnName          = $attributes['name'];

			$entityPropertyMap = $this->attributesService->getColumnPropertyMap( $propertyEntityClass );
			$primaryColumns    = array_filter( $entityPropertyMap, function ( $item ) {
				return isset( $item['primary'] );
			} );

			$primaryColumn = [];
			foreach ( $primaryColumns as $propertyName => $attributes ) {
				$primaryColumn = array_merge( [ 'property' => $propertyName ], $attributes );
			}

			$entityIds = [];
			foreach ( $parentEntitiesData as $entityData ) {
				$entityIds[] = $entityData->$columnName;
			}

			if ( $entityIds ) {

				/** @var RepositoryAbstract $entityRepository */
				$entityRepository = $this->getEntityManager()->getRepository( $propertyEntityClass );

				$entitiesData = $entityRepository
					->createQueryBuilder()
					->addWhere( $primaryColumn['name'], Where::OPERATOR_IN, array_unique( $entityIds ) )
					->getResults();

				$entities = $entityRepository->fillEntities( $entitiesData );

				$parentEntitiesData->map( function ( $item ) use ( $columnName, $primaryColumn, $entities ) {

					foreach ( $entities as $entity ) {
						$methodName = 'get' . ucfirst( $primaryColumn['property'] );
						if ( ! method_exists( $entity, $methodName ) ) {
							continue;
						}

						if ( $item->$columnName == $entity->$methodName() ) {
							$item->$columnName = $entity;
						}
					}
				} );

			}

		}

	}

	protected function fillEntity( object $data ): EntityAbstract {

		$entityClass       = $this->getEntityClassName();
		$columnPropertyMap = $this->attributesService->getColumnPropertyMap( $entityClass );

		$propertiesMap = [];
		foreach ( $columnPropertyMap as $propertyName => $attributes ) {
			$propertiesMap[ $attributes['name'] ] = $propertyName;
		}

		/** @var EntityAbstract $entity */
		$entity = new $entityClass();

		foreach ( $data as $colName => $value ) {

			if ( ! isset( $propertiesMap[ $colName ] ) ) {
				$entity->addExtraData( $colName, $value );
			} else {

				$propertyName = $propertiesMap[ $colName ];

				$methodName = 'set' . ucfirst( $propertyName );
				if ( method_exists( $entity, $methodName ) ) {
					$entity->$methodName( $value );
				}
			}

		}

		$this->entityManager->add( $entity );

		return $entity;
	}

	/**
	 * @return EntityManager
	 */
	public function getEntityManager(): EntityManager {
		return $this->entityManager;
	}

}