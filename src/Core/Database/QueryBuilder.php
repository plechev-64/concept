<?php

namespace USP\Core\Database;

use wpdb;

class QueryBuilder {

	const METHOD_GET_RESULTS = 'get_results';
	const METHOD_GET_ROW = 'get_row';
	const METHOD_GET_VAR = 'get_var';
	const METHOD_GET_COL = 'get_col';

	private DatabaseTable $table;
	private ?QueryObject $queryObject;

	/**
	 * @param DatabaseTable $table
	 */
	public function __construct( DatabaseTable $table ) {

		$this->table       = $table;
		$this->queryObject = new QueryObject();
	}

	public function getTable(): DatabaseTable {
		return $this->table;
	}

	public function select( ?array $selectCols ): QueryBuilder {

		$as = $this->getTable()->getAs();
		foreach ( $selectCols as &$selectCol ) {
			$selectCol = $as . '.' . $selectCol;
		}

		$this->queryObject->setSelect( $selectCols );

		return $this;
	}

	public function addWhere( string $colName, string $operator, mixed $value ): QueryBuilder {

		$this->queryObject->addWhere(
			( new Where() )
				->setColName( $this->getTable()->getAs() . '.' . $colName )
				->setOperator( $operator )
				->setValue( $value )
		);

		return $this;
	}

	protected function commonJoin( string $typeJoin, string $leftCol, string $onCondition, string $rightCol, QueryBuilder $queryBuilder ): QueryBuilder {

		$this->queryObject->addJoin(
			( new Join() )
				->setType( $typeJoin )
				->setLeftCol( $this->getTable()->getAs() . '.' . $leftCol )
				->setRightCol( $queryBuilder->getTable()->getAs() . '.' . $rightCol )
				->setOnCondition( $onCondition )
				->setQueryBuilder( $queryBuilder )
		);

		return $this;
	}
	public function join( array $onCondition, QueryBuilder $queryBuilder ): QueryBuilder {
		return $this->commonJoin( Join::TYPE_INNER, $onCondition[0], $onCondition[1], $onCondition[2], $queryBuilder );
	}

	public function leftJoin( array $onCondition, QueryBuilder $queryBuilder ): QueryBuilder {
		return $this->commonJoin( Join::TYPE_LEFT, $onCondition[0], $onCondition[1], $onCondition[2], $queryBuilder );
	}

	public function rightJoin( array $onCondition, QueryBuilder $queryBuilder ): QueryBuilder {
		return $this->commonJoin( Join::TYPE_RIGHT, $onCondition[0], $onCondition[1], $onCondition[2], $queryBuilder );
	}

	/**
	 * @return QueryObject|null
	 */
	public function getQueryObject(): ?QueryObject {
		return $this->queryObject;
	}

	/**
	 * @param string|null $action
	 *
	 * @return string
	 */
	public function getSql( ?string $action ): string {
		$sqlBuilder = new SqlBuilder( $this->table, $this->queryObject );

		return $sqlBuilder->buildSql( $action );
	}

	public function limit( int $number, int $offset = 0 ): QueryBuilder {
		$this->number( $number );
		$this->offset( $offset );

		return $this;
	}

	public function number( int $number ): QueryBuilder {
		$this->queryObject->setNumber( $number );

		return $this;
	}

	public function offset( int $offset ): QueryBuilder {
		$this->queryObject->setOffset( $offset );

		return $this;
	}

	public function groupBy( array $groupBy ): QueryBuilder {

		$this->queryObject->setGroupBy( $groupBy );

		return $this;
	}

	public function orderBy( array $orderBy ): QueryBuilder {

		$this->queryObject->setOrderBy( $orderBy );

		return $this;
	}

	private function getData( string $method ): mixed {

		$sql = $this->getSql( SqlBuilder::ACTION_GET );

		$data = $this->db()->$method( $sql );

		$data = $this->maybeUnserialize( $data );

		return wp_unslash( $data );

	}

	private function maybeUnserialize( mixed $data ): mixed {

		if ( ! $this->table->getSerialize() ) {
			return $data;
		}

		if ( is_string( $data ) ) {
			return maybe_unserialize( $data );
		}

		foreach ( $this->table->getSerialize() as $colName ) {
			if ( is_array( $data ) ) {
				foreach ( $data as $k => $item ) {
					if ( is_object( $item ) ) {
						if ( isset( $item->$colName ) ) {
							$item->$colName = maybe_unserialize( $item->$colName );
						}
					} else {
						$data[ $k ] = maybe_unserialize( $item );
					}
				}
			} else if ( is_object( $data ) ) {
				if ( isset( $data->$colName ) ) {
					$data->$colName = maybe_unserialize( $data->$colName );
				}
			}
		}

		return $data;
	}

	public function getVar(): mixed {
		return $this->getData( self::METHOD_GET_VAR );
	}

	public function getResults(): ?array {
		return $this->getData( self::METHOD_GET_RESULTS );
	}

	public function getRow(): ?object {
		return $this->getData( self::METHOD_GET_ROW );
	}

	public function getCol(): ?array {
		return $this->getData( self::METHOD_GET_COL );
	}

	public function insert( $data ): bool|int {

		return $this->db()->insert( $this->table->getName(), $data );
	}

	public function update( array $colNameValues ): bool|int {
		$this->queryObject->setNeedUpdate( $colNameValues );

		return $this->db()->query( $this->getSql( SqlBuilder::ACTION_UPDATE ) );
	}

	public function delete(): bool|int {

		return $this->db()->query( $this->getSql( SqlBuilder::ACTION_DELETE ) );
	}

	//@todo пока убрал wpdb из переменной т.к. засирает дебаг
	private function db(): wpdb {
		global $wpdb;

		return $wpdb;
	}
}