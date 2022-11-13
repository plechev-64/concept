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

use USP\Core\Attributes\Entity;
use USP\Core\Container\Container;
use USP\Core\EntityManager;
use USP\Init\Entity\Post;
use USP\Init\Repository\PostsRepository;

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

///** @var Post $post */
//$post = ( new PostsRepository() )->findOneBy([['ID', '>', 0]]);
//print_r([$post]);

$container = Container::getInstance();

/** @var PostsRepository $postsRepository */
$postsRepository = $container->get( PostsRepository::class );
$posts = $postsRepository->getPostsByUserNicename('admin');
print_pre($posts);

