<?php

/*
Plugin Name: User Space
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: A brief description of the Plugin.
Version: 1.0
Author: Andrey
Author URI: http://URI_Of_The_Plugin_Author
License: A "Slug" license name e.g. GPL2
*/

use USP\Core\Collections\ArrayCollection;
use USP\Core\Container\Container;
use USP\Core\Database\Join;
use USP\Core\Database\Paginator;
use USP\Core\Database\Where;
use USP\Init\Entity\Post;
use USP\Init\Repository\PostsRepository;
use USP\Init\Repository\UsersRepository;

require_once 'vendor/autoload.php';

if ( ! function_exists( 'str_starts_with' ) ) {
	function str_starts_with( $haystack, $needle ): bool {
		return (string) $needle !== '' && strncmp( $haystack, $needle, strlen( $needle ) ) === 0;
	}
}

function print_pre( $data ) {
	echo '<pre>';
	print_r( $data );
	echo '</pre>';
}

$container = Container::getInstance();
/** @var PostsRepository $postsRepository */
$postsRepository = $container->get( PostsRepository::class );
//** @var ArrayCollection<Post> $posts */
//$posts = $postsRepository->find(1);
//print_pre($posts);

/** @var UsersRepository $usersRepository */
$usersRepository = $container->get( UsersRepository::class );

$usersQueryBuilder = $usersRepository->createQueryBuilder( 'u' );

$userNicenameFilter = sanitize_text_field( $_GET['user_nicename'] ?? 'admin' );
$page               = absint( $_GET['usp_page'] ?? 1 );

/**
 * Проблемы:
 * 1. В $postsRepository есть метод getPostsByUserNicename, он возвращает результат, по этому приходится дублировать
 * запрос. Хотя может и не проблема это
 * 2. Paginator работает с QueryBuilder, а он ничего не знает о repository и не может заполнить Entity
 * по этому возвращает просто объект с данными, может это так и должно быть, а может нет
 * 3. Подсчет кол-ва всех записей в Paginator пока не сделан нормально, подумаю как корректно сделать
 */
$postsQueryBuilder = $postsRepository->createQueryBuilder( 'p' );
$paginator = new Paginator( $postsQueryBuilder, 3 );
$paginator->paginate( $page );

print_pre( [
	'count'      => count( $paginator ),
	'curPage'    => $paginator->getCurrentPage(),
	'totalPages' => $paginator->getLastPage(),
	'nextPage'   => $paginator->getNextPage(),
	'prevPage'   => $paginator->getPreviousPage(),
	'data'       => $postsRepository->fillEntities($paginator->getResults())
] );

