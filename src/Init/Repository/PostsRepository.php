<?php

namespace USP\Init\Repository;

use USP\Core\Collections\ArrayCollection;
use USP\Core\Database\Join;
use USP\Core\Database\Where;
use USP\Core\RepositoryAbstract;
use USP\Init\Entity\Post;
use USP\Init\Entity\User;

/**
 * @method Post findOneBy( array $conditions )
 * @method Post find( int $id )
 * @method Post[] findAllBy( array $conditions )
 */
class PostsRepository extends RepositoryAbstract {

	public function getEntityClassName(): string {
		return Post::class;
	}

	public function getTableName(): string {
		global $wpdb;

		return $wpdb->posts;
	}

	public function getPostsByUserNicename( string $userNicename ): ?ArrayCollection {

		$usersQueryBuilder = $this->getEntityManager()->getRepository( User::class )
		                          ->createQueryBuilder( 'u' );

		$posts = $this->createQueryBuilder( 'p' )
//		              ->select( [
//			              'ID',
//			              'post_title',
//			              'post_name',
//		              ] )
		              ->join(
			              [ 'post_author', Join::ON_EQUAL, 'ID' ],
			              $usersQueryBuilder
				              //->select( [ 'user_nicename' ] )
				              ->addWhere( 'user_nicename', Where::OPERATOR_EQUAL, $userNicename )
		              )->getResults();

		if ( ! $posts ) {
			return null;
		}

		return $this->fillEntities( $posts, $this->getEntityClassName() );

	}

}