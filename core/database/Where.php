<?php

namespace USP\Core\Database;

class Where {

	const OPERATOR_EQUAL = '=';
	const OPERATOR_IN = 'in';
	const OPERATOR_NOT_IN = 'not_in';
	const OPERATOR_LIKE = 'like';
	const OPERATOR_MORE = '>';
	const OPERATOR_MORE_OR_EQUAL = '>=';
	const OPERATOR_LESS = '<';
	const OPERATOR_LESS_OR_EQUAL = '<=';
	const OPERATOR_BETWEEN = 'between';

	const OPERATORS = [
		self::OPERATOR_IN,
		self::OPERATOR_NOT_IN,
		self::OPERATOR_BETWEEN,
		self::OPERATOR_LESS,
		self::OPERATOR_LESS_OR_EQUAL,
		self::OPERATOR_MORE,
		self::OPERATOR_MORE_OR_EQUAL,
		self::OPERATOR_LIKE,
		self::OPERATOR_EQUAL,
	];

	private string $colName;
	private string $operator;
	private mixed $value;

	/**
	 * @return string
	 */
	public function getColName(): string {
		return $this->colName;
	}

	/**
	 * @param   string  $colName
	 *
	 * @return Where
	 */
	public function setColName( string $colName ): Where {
		$this->colName = $colName;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getOperator(): string {
		return $this->operator;
	}

	/**
	 * @param   string  $operator
	 *
	 * @return Where|null
	 */
	public function setOperator( string $operator ): ?Where {
		if ( ! in_array( $operator, self::OPERATORS ) ) {
			return null;
		}

		$this->operator = $operator;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getValue(): mixed {
		return $this->value;
	}

	/**
	 * @param   mixed  $value
	 *
	 * @return Where
	 */
	public function setValue( mixed $value ): Where {
		$this->value = $value;

		return $this;
	}

}