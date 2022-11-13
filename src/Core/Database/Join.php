<?php

namespace USP\Core\Database;

class Join {

	const TYPE_INNER = 'INNER';
	const TYPE_LEFT = 'LEFT';
	const TYPE_RIGHT = 'RIGHT';

	const ON_EQUAL = '=';
	const ON_NOT_EQUAL = '!=';

	private string $type;
	private string $leftCol;
	private string $rightCol;
	private string $onCondition;
	private QueryBuilder $queryBuilder;

	/**
	 * @return string
	 */
	public function getType(): string {
		return $this->type;
	}

	/**
	 * @param   string  $type
	 *
	 * @return Join
	 */
	public function setType( string $type ): Join {
		$this->type = $type;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getLeftCol(): string {
		return $this->leftCol;
	}

	/**
	 * @param   string  $leftCol
	 *
	 * @return Join
	 */
	public function setLeftCol( string $leftCol ): Join {
		$this->leftCol = $leftCol;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getRightCol(): string {
		return $this->rightCol;
	}

	/**
	 * @param   string  $rightCol
	 *
	 * @return Join
	 */
	public function setRightCol( string $rightCol ): Join {
		$this->rightCol = $rightCol;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getOnCondition(): string {
		return $this->onCondition;
	}

	/**
	 * @param   string  $onCondition
	 *
	 * @return Join
	 */
	public function setOnCondition( string $onCondition ): Join {
		$this->onCondition = $onCondition;

		return $this;
	}

	/**
	 * @return QueryBuilder
	 */
	public function getQueryBuilder(): QueryBuilder {
		return $this->queryBuilder;
	}

	/**
	 * @param   QueryBuilder  $queryBuilder
	 *
	 * @return Join
	 */
	public function setQueryBuilder( QueryBuilder $queryBuilder ): Join {
		$this->queryBuilder = $queryBuilder;

		return $this;
	}
}