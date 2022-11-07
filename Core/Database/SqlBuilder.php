<?php

namespace USP\Core\Database;

class SqlBuilder {

	const ACTION_GET = 'GET';
	const ACTION_UPDATE = 'UPDATE';
	const ACTION_DELETE = 'DELETE';

	private DatabaseTable $table;
	private QueryObject $queryObject;

	/**
	 * @param   DatabaseTable  $table
	 * @param   QueryObject    $queryObject
	 */
	public function __construct( DatabaseTable $table, QueryObject $queryObject ) {
		$this->table       = $table;
		$this->queryObject = $queryObject;
	}

	public function buildSql( ?string $action = self::ACTION_GET ): ?string {

		$sql = [];

		if ( $action == self::ACTION_GET ) {

			$select = $this->queryObject->getSelect();

			if ( ! $select ) {
				$select = [ '*' ];
			}

			$select = array_map( function ( string $item ) {
				return $this->table->getAs() . '.' . $item;
			}, $select );

			$sql[] = "SELECT " . implode( ', ', $select );
			$sql[] = "FROM " . $this->table->getName() . " AS " . $this->table->getAs();
		} else if ( $action == self::ACTION_UPDATE ) {
			$sql[] = "UPDATE " . $this->table->getName() . " AS " . $this->table->getAs();
			if ( $needUpdate = $this->queryObject->getNeedUpdate() ) {
				$set = [];
				foreach ( $needUpdate as $col_name => $v ) {
					$set[] = $this->table->getAs() . ".$col_name='" . $v . "'";
				}
				$sql[] = "SET " . implode( ', ', $set );
			}
		} else if ( $action == self::ACTION_DELETE ) {
			$sql[] = "DELETE " . $this->table->getAs() . " FROM " . $this->table->getName() . " AS " . $this->table->getAs();
		}

		$whereList = $this->queryObject->getWhere();

		if ( $whereList ) {

			$whereArray = [];
			/** @var Where $where */
			foreach ( $whereList as $where ) {
				$whereArray[] = $this->getWhereSql( $where );
			}

			$sql[] = "WHERE " . implode( ' AND ', $whereArray );

		}

		if ( $groupBy = $this->queryObject->getGroupBy() ) {
			$sql[] = "GROUP BY " . implode( ', ', $groupBy );
		}

		if ( $having = $this->queryObject->getHaving() ) {
			$sql[] = "HAVING " . implode( ' AND ', $having );
		}

		if ( $orderByArray = $this->queryObject->getOrderBy() ) {

			$orders = [];
			foreach ( $orderByArray as $by => $order ) {
				$by       = count( explode( '.', $by ) ) > 1 ? $by : ( in_array( $by, $this->table->getCols() ) ? $this->table->getAs() . '.' . $by : $by );
				$orders[] = $by . " " . $order;
			}

			$orderBy = implode( ",", $orders );

		} else {
			$orderBy = $this->table->getAs() . "." . $this->table->getCols()[0] . " " . $this->queryObject->getOrder();
		}

		$sql[] = "ORDER BY " . $orderBy;

		$offset = $this->queryObject->getOffset();

		if ( $number = $this->queryObject->getNumber() ) {

			if ( $number < 0 ) {
				$number = 0;
			}

			if ( $offset ) {
				$sql[] = "LIMIT " . $offset . "," . $number;
			} else if ( $number ) {
				$sql[] = "LIMIT " . $number;
			}
		} else if ( $offset ) {
			$sql[] = "OFFSET " . $offset;
		}

		return implode( ' ', $sql );

	}

	private function getWhereSql( Where $where ): string {

		return match ( $where->getOperator() ) {
			Where::OPERATOR_IN => $this->table->getAs() . "." . $where->getColName() . " IN (" . $this->prepareWithIn( esc_sql( $where->getValue() ) ) . ")",
			Where::OPERATOR_NOT_IN => $this->table->getAs() . "." . $where->getColName() . " NOT IN (" . $this->prepareWithIn( esc_sql( $where->getValue() ) ) . ")",
			Where::OPERATOR_BETWEEN => $this->table->getAs() . "." . $where->getColName() . " BETWEEN IFNULL(" . $where->getValue()[0] . ", 0) AND '" . $where->getValue()[1] . "')",
			Where::OPERATOR_LIKE => $this->table->getAs() . "." . $where->getColName() . " LIKE '%" . esc_sql( $where->getValue() ) . "%'",
			default => $this->table->getAs() . "." . $where->getColName() . " " . $where->getOperator() . " '" . esc_sql( $where->getValue() ) . "'",
		};

	}

	private function prepareWithIn( mixed $data ): string {

		$vars = ( is_array( $data ) ) ? $data : explode( ',', $data );

		$vars = array_map( 'trim', $vars );

		$array = [];
		foreach ( $vars as $var ) {

			if ( is_numeric( $var ) ) {
				$array[] = $var;
			} else {
				$array[] = "'$var'";
			}
		}

		return implode( ',', $array );
	}
}