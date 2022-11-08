<?php

namespace USP\Core\Database;

class DatabaseTable {
	private string $name;
	private array $cols;
	private ?string $as = null;
	private ?array $serialize = null;

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @param   string  $name
	 *
	 * @return DatabaseTable
	 */
	public function setName( string $name ): DatabaseTable {
		$this->name = $name;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getAs(): string {
		return $this->as ?? $this->name;
	}

	/**
	 * @param   string|null  $as
	 *
	 * @return DatabaseTable
	 */
	public function setAs( ?string $as ): DatabaseTable {
		$this->as = $as;

		return $this;
	}

	/**
	 * @return array
	 */
	public function getCols(): array {
		return $this->cols;
	}

	/**
	 * @param   array  $cols
	 *
	 * @return DatabaseTable
	 */
	public function setCols( array $cols ): DatabaseTable {
		$this->cols = $cols;

		return $this;
	}

	/**
	 * @return array|null
	 */
	public function getSerialize(): ?array {
		return $this->serialize;
	}

	/**
	 * @param   array|null  $serialize
	 *
	 * @return DatabaseTable
	 */
	public function setSerialize( ?array $serialize ): DatabaseTable {
		$this->serialize = $serialize;

		return $this;
	}

}