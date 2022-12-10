<?php

namespace USP\Core\Database;

use Countable;

class Paginator implements Countable {
	private const PAGE_SIZE = 10;
	private QueryBuilder $queryBuilder;
	private int $currentPage;
	private int $pageSize;
	private array $results;
	private int $numResults;

	public function __construct( QueryBuilder $queryBuilder, int $pageSize = self::PAGE_SIZE ) {
		/*
		 * @todo Для получения результата и подсчета общего кол-ва итемов, инстанс queryBuilder приходится изменять
		 * @todo возможно надо использовать clone, что бы оригинальный инстанс не трогать, а может и нет, хз
		 */
		$this->queryBuilder = $queryBuilder;
		$this->pageSize     = $pageSize;
	}

	public function paginate( int $page = 1 ): self {
		$this->currentPage = max( 1, $page );
		$offset            = ( $this->currentPage - 1 ) * $this->pageSize;

		/*
		 * @todo Не уверен как работает mysql, может быть limit в join так же надо подставить
		 */
		$this->results = $this->queryBuilder->limit( $this->pageSize, $offset )->getResults();

		/*
		 * @todo Очистка что бы от join не был select лишних данных, которые не нужны для подсчета кол-ва итемов
		 */
		foreach ( $this->queryBuilder->getQueryObject()->getJoin() as $join ) {
			$join->getQueryBuilder()->select( [] );
		}


		/*
		 * @todo сейчас из основной таблицы выбираем только ID, но надо в queryBuilder добавить метод count или т.п.
		 */
		$this->numResults = count( $this->queryBuilder->limit( 0, 0 )->select( [ 'ID' ] )->getResults() );

		return $this;
	}

	public function getCurrentPage(): int {
		return $this->currentPage;
	}

	public function getLastPage(): int {
		return (int) ceil( $this->numResults / $this->pageSize );
	}

	public function getPageSize(): int {
		return $this->pageSize;
	}

	public function hasPreviousPage(): bool {
		return $this->currentPage > 1;
	}

	public function getPreviousPage(): int {
		return max( 1, $this->currentPage - 1 );
	}

	public function hasNextPage(): bool {
		return $this->currentPage < $this->getLastPage();
	}

	public function getNextPage(): int {
		return min( $this->getLastPage(), $this->currentPage + 1 );
	}

	public function hasToPaginate(): bool {
		return $this->numResults > $this->pageSize;
	}

	public function getNumResults(): int {
		return $this->numResults;
	}

	public function getResults(): array {
		return $this->results;
	}

	public function count(): int {
		return $this->numResults;
	}
}