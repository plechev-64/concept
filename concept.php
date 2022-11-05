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

use USP\Core\EntityManager;
use USP\Init\Entity\Post;
use USP\Init\Repository\PostsRepository;

require_once 'core/Entity.php';
require_once 'core/Repository.php';
require_once 'core/EntityManager.php';
require_once 'core/database/QueryObject.php';
require_once 'core/database/DatabaseTable.php';
require_once 'core/database/RequestBuilder.php';
require_once 'core/database/SqlBuilder.php';
require_once 'core/database/Where.php';
require_once 'init/entity/User.php';
require_once 'init/entity/Post.php';
require_once 'init/repository/UsersRepository.php';
require_once 'init/repository/PostsRepository.php';


if (!function_exists('str_starts_with')) {
	function str_starts_with( $haystack, $needle ): bool {
		return (string) $needle !== '' && strncmp( $haystack, $needle, strlen( $needle ) ) === 0;
	}
}

/** @var Post $post */
$post = ( new PostsRepository() )->find(1);

$post
	->setPostTitle('Залоговок2')
	->setPostContent('Содержимое2');

//$post = ( new PostsRepository('p') )->findAllBy([
//	['post_type', '=', 'post'],
//	['post_status', '=', 'publish']
//]);

//$newPost = (new Post())
//	->setPostTitle('Залоговок3')
//	->setPostContent('Содержимое3')
//	->setPostAuthor(1)
//	->setPostType('post')
//	->setPostStatus('publish')
//;
//EntityManager::getInstance()->add($newPost);

print_r([serialize($post)]);

EntityManager::getInstance()->save();