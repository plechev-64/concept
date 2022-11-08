<?php

namespace USP\Core;

use ReflectionException;
use USP\Core\Attributes\AttributesService;
use USP\Core\Attributes\Column;
use USP\Core\Database\RequestBuilder;
use USP\Core\Database\DatabaseTable;
use USP\Core\Database\Where;

abstract class RepositoryAbstract {

	protected RequestBuilder $requestBuilder;
	private AttributesService $attributesService;
	private array $columnPropertyMap;

	abstract public function getEntity(): string;

	abstract public function getTableName(): string;

	/**
	 * @throws ReflectionException
	 */
	public function __construct( ?string $as = null ) {

		$this->attributesService = new AttributesService();
		$columnAttributes = $this->attributesService->getClassProperties($this->getEntity(), Column::class);

		$this->columnPropertyMap = [];
		foreach($columnAttributes as $property => $attribute){
			$this->columnPropertyMap[$attribute->getArguments()['name']] = $property;
		}

		$columnNames = [];

		foreach($columnAttributes as $property => $attribute){
			$columnNames[] = $attribute->getArguments()['name'];
		}

		$table = ( new DatabaseTable() )
			->setAs( $as )
			->setName( $this->getTableName() )
			->setCols( $columnNames );

		$this->requestBuilder = new RequestBuilder( $table );
	}

	public function getRequestBuilder(): RequestBuilder {
		return $this->requestBuilder;
	}

	public function find( int $id ): ?EntityAbstract {
		$table = $this->requestBuilder->getTable();

		return $this->findOneBy( [
			[ $table->getCols()[0], Where::OPERATOR_EQUAL, $id ]
		] );
	}

	public function findOneBy( array $conditions ): ?EntityAbstract {

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

	private function fillEntity( object $data ): EntityAbstract {

		$entityClass = $this->getEntity();

		/** @var EntityAbstract $entity */
		$entity = new $entityClass();

		foreach ( $data as $colName => $value ) {

			if(!isset($this->columnPropertyMap[$colName])){
				$entity->addExtraData($colName, $value);
			}else{

				$propertyName = $this->columnPropertyMap[$colName];

				$methodName       = 'set' . ucfirst($propertyName);
				if ( method_exists( $entity, $methodName ) ) {
					$entity->$methodName( $value );
				}
			}

		}

		EntityManager::getInstance()->add( $entity );

		return $entity;
	}

}