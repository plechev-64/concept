<?php

namespace USP\Core\Database;

class QueryBuilder {

	const METHOD_GET_RESULTS = 'get_results';
	const METHOD_GET_ROW = 'get_row';
	const METHOD_GET_VAR = 'get_var';
	const METHOD_GET_COL = 'get_col';

	private DatabaseTable $table;
	private ?QueryObject $queryObject;

	/**
	 * @param   DatabaseTable  $table
	 */
	public function __construct( DatabaseTable $table ) {
		$this->table       = $table;
		$this->queryObject = new QueryObject();
	}

	public function getTable(): DatabaseTable {
		return $this->table;
	}

	public function select( ?array $selectCols ): QueryBuilder {
		$this->queryObject->setSelect( $selectCols );

		return $this;
	}

	public function addWhere( string $colName, string $operator, mixed $value ): QueryBuilder {
		$this->queryObject->addWhere(
			( new Where() )
				->setColName( $colName )
				->setOperator( $operator )
				->setValue( $value )
		);

		return $this;
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
		global $wpdb;

		$sql = $this->getSql( SqlBuilder::ACTION_GET );

		$data = $wpdb->$method( $sql );

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

	public function insert( $data ) {
		global $wpdb;

		return $wpdb->insert( $this->table->getName(), $data );
	}

	public function update( array $colNameValues ) {
		global $wpdb;
		$this->queryObject->setNeedUpdate( $colNameValues );

		return $wpdb->query( $this->getSql( SqlBuilder::ACTION_UPDATE ) );
	}

	public function delete() {
		global $wpdb;

		return $wpdb->query( $this->getSql( SqlBuilder::ACTION_DELETE ) );
	}
}