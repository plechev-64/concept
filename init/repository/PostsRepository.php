<?php

namespace USP\Init\Repository;

use USP\Core\Repository;
use USP\Init\Entity\Post;

class PostsRepository extends Repository {

	public function getEntity(): string {
		return Post::class;
	}

	public function getTableName(): string {
		global $wpdb;

		return $wpdb->posts;
	}

}