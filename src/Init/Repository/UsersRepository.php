<?php

namespace USP\Init\Repository;

use USP\Core\RepositoryAbstract;
use USP\Init\Entity\User;

class UsersRepository extends RepositoryAbstract {

	public function getEntity(): string {
		return User::class;
	}

	public function getTableName(): string {
		global $wpdb;

		return $wpdb->users;
	}

}