<?php

namespace USP\Core;

use USP\Core\Database\RequestBuilder;
use USP\Core\Database\DatabaseTable;
use USP\Core\Database\Where;

abstract class Repository {

	protected RequestBuilder $requestBuilder;

	abstract public function getEntity(): string;

	abstract public function getTableName(): string;

	public function __construct( ?string $as = null ) {

		$colsNames = Entity::_getColNames( $this->getEntity() );

		$table = ( new DatabaseTable() )
			->setAs( $as )
			->setName( $this->getTableName() )
			->setCols( $colsNames );

		$this->requestBuilder = new RequestBuilder( $table );
	}

	public function getRequestBuilder(): RequestBuilder {
		return $this->requestBuilder;
	}

	public function find( int $id ): ?Entity {
		$table = $this->requestBuilder->getTable();

		return $this->findOneBy( [
			[ $table->getCols()[0], Where::OPERATOR_EQUAL, $id ]
		] );
	}

	public function findOneBy( array $conditions ): ?Entity {

		$query = $this->requestBuilder;
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
		$query = $this->requestBuilder;
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

	private function fillEntity( object $data ): Entity {

		$entityClass = $this->getEntity();

		/** @var Entity $entity */
		$entity = new $entityClass();

		foreach ( $data as $colName => $value ) {
			$camelCaseColName = str_replace( '_', '', ucwords( $colName, '_' ) );
			$methodName       = 'set' . $camelCaseColName;
			if ( method_exists( $entity, $methodName ) ) {
				$entity->$methodName( $value );
			}
		}

		EntityManager::getInstance()->add( $entity );

		return $entity;
	}

}