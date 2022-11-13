<?php

namespace USP\Core;

use ReflectionException;
use USP\Core\Attributes\AttributesService;
use USP\Core\Attributes\Column;
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
			->setCols( array_keys( $this->attributesService->getColumnPropertyMap( $this->getEntityClassName() ) ) );

		return new QueryBuilder( $table );
	}

	public function find( int $id ): ?EntityAbstract {
		$table = $this->createQueryBuilder()->getTable();

		return $this->findOneBy( [
			[ $table->getCols()[0], Where::OPERATOR_EQUAL, $id ]
		] );
	}

	public function findOneBy( array $conditions ): ?EntityAbstract {

		$query = $this->createQueryBuilder();
		foreach ( $conditions as $condition ) {
			$query->addWhere( $condition[0], $condition[1], $condition[2] );
		}

		$data = $query->getRow();

		if ( ! $data ) {
			return null;
		}

		return $this->fillEntity( $data );

	}

	public function findAllBy( array $conditions ): ?array {
		$query = $this->createQueryBuilder();
		foreach ( $conditions as $condition ) {
			$query->addWhere( $condition[0], $condition[1], $condition[2] );
		}

		$results = $query->getResults();

		if ( ! $results ) {
			return null;
		}

		$data = [];
		foreach ( $results as $row ) {
			$data[] = $this->fillEntity( $row );
		}

		return $data;
	}

	protected function fillEntities( array $entitiesData ): array {
		$data = [];
		foreach ( $entitiesData as $entityData ) {
			$data[] = $this->fillEntity( $entityData );
		}

		return $data;
	}

	protected function fillEntity( object $data ): EntityAbstract {

		$entityClass       = $this->getEntity();
		$columnPropertyMap = $this->attributesService->getColumnPropertyMap( $this->getEntityClassName() );

		/** @var EntityAbstract $entity */
		$entity = new $entityClass();

		foreach ( $data as $colName => $value ) {

			if ( ! isset( $columnPropertyMap[ $colName ] ) ) {
				$entity->addExtraData( $colName, $value );
			} else {

				$propertyName = $columnPropertyMap[ $colName ];

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