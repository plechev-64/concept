<?php

namespace USP\Init\Repository;

use USP\Core\RepositoryAbstract;
use USP\Init\Entity\Post;

class PostsRepository extends RepositoryAbstract {

	public function getEntity(): string {
		return Post::class;
	}

	public function getTableName(): string {
		global $wpdb;

		return $wpdb->posts;
	}

}