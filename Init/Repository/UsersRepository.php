<?php

namespace USP\Init\Repository;

use USP\Core\Repository;
use USP\Init\Entity\User;

class UsersRepository extends Repository {

	public function getEntity(): string {
		return User::class;
	}

	public function getTableName(): string {
		global $wpdb;

		return $wpdb->users;
	}

}