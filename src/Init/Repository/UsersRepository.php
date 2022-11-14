<?php

namespace USP\Init\Repository;

use USP\Core\RepositoryAbstract;
use USP\Init\Entity\User;

/**
 * @method User findOneBy( array $conditions )
 * @method User find( int $id )
 * @method User[] findAllBy( array $conditions )
 */
class UsersRepository extends RepositoryAbstract {

	public function getEntityClassName(): string {
		return User::class;
	}

	public function getTableName(): string {
		global $wpdb;

		return $wpdb->users;
	}

}