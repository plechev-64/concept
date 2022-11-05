<?php

namespace USP\Core\Database;

class QueryObject {

	const ORDER_DESC = 'DESC';
	const ORDER_ASC = 'ASC';

	const ORDERS = [
		self::ORDER_ASC,
		self::ORDER_DESC
	];

	private ?array $select = null;
	private ?array $where = null;
	private ?array $join = null;
	private ?int $number = null;
	private ?int $offset = null;
	private ?array $orderBy = null;
	private ?string $order = null;
	private ?array $having = null;
	private ?array $groupBy = null;
	private ?array $needUpdate = null;

	public function __construct() {
		$this->order = self::ORDER_DESC;
	}


	/**
	 * @return array|null
	 */
	public function getSelect(): ?array {
		return $this->select;
	}

	/**
	 * @param   array|null  $select
	 *
	 * @return QueryObject
	 */
	public function setSelect( ?array $select ): QueryObject {
		$this->select = $select;

		return $this;
	}

	/**
	 * @return array|null
	 */
	public function getWhere(): ?array {
		return $this->where;
	}

	/**
	 * @param   array|null  $where
	 *
	 * @return QueryObject
	 */
	public function setWhere( ?array $where ): QueryObject {
		$this->where = $where;

		return $this;
	}

	public function addWhere( Where $where ): QueryObject {
		$this->where[] = $where;

		return $this;
	}

	/**
	 * @return array|null
	 */
	public function getJoin(): ?array {
		return $this->join;
	}

	/**
	 * @param   array|null  $join
	 *
	 * @return QueryObject
	 */
	public function setJoin( ?array $join ): QueryObject {
		$this->join = $join;

		return $this;
	}

	/**
	 * @return int|null
	 */
	public function getNumber(): ?int {
		return $this->number;
	}

	/**
	 * @param   int|null  $number
	 *
	 * @return QueryObject
	 */
	public function setNumber( ?int $number ): QueryObject {
		$this->number = $number;

		return $this;
	}

	/**
	 * @return int|null
	 */
	public function getOffset(): ?int {
		return $this->offset;
	}

	/**
	 * @param   int|null  $offset
	 *
	 * @return QueryObject
	 */
	public function setOffset( ?int $offset ): QueryObject {
		$this->offset = $offset;

		return $this;
	}

	/**
	 * @return array|null
	 */
	public function getOrderBy(): ?array {
		return $this->orderBy;
	}

	/**
	 * @param   array|null  $orderBy
	 *
	 * @return QueryObject
	 */
	public function setOrderBy( ?array $orderBy ): QueryObject {
		$this->orderBy = $orderBy;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getOrder(): ?string {
		return $this->order;
	}

	/**
	 * @param   string|null  $order
	 *
	 * @return QueryObject
	 */
	public function setOrder( ?string $order ): QueryObject {
		if ( ! in_array( $order, self::ORDERS ) ) {
			return $this;
		}

		$this->order = $order;

		return $this;
	}

	/**
	 * @return array|null
	 */
	public function getHaving(): ?array {
		return $this->having;
	}

	/**
	 * @param   array|null  $having
	 *
	 * @return QueryObject
	 */
	public function setHaving( ?array $having ): QueryObject {
		$this->having = $having;

		return $this;
	}

	/**
	 * @return array|null
	 */
	public function getGroupBy(): ?array {
		return $this->groupBy;
	}

	/**
	 * @param   array|null  $groupBy
	 *
	 * @return QueryObject
	 */
	public function setGroupBy( ?array $groupBy ): QueryObject {
		$this->groupBy = $groupBy;

		return $this;
	}

	/**
	 * @return array|null
	 */
	public function getNeedUpdate(): ?array {
		return $this->needUpdate;
	}

	/**
	 * @param   array|null  $needUpdate
	 *
	 * @return QueryObject
	 */
	public function setNeedUpdate( ?array $needUpdate ): QueryObject {
		$this->needUpdate = $needUpdate;

		return $this;
	}

}