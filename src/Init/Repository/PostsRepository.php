<?php

namespace USP\Init\Repository;

use USP\Core\EntityAbstract;
use USP\Core\RepositoryAbstract;
use USP\Init\Entity\Post;

/**
 * @method Post findOneBy( array $conditions )
 * @method Post find( int $id )
 * @method Post[] findAllBy( array $conditions )
 */
class PostsRepository extends RepositoryAbstract {

	public function getEntity(): string {
		return Post::class;
	}

	public function getTableName(): string {
		global $wpdb;

		return $wpdb->posts;
	}

}