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

$postsRepository = $container->get( PostsRepository::class );

$container->set( 'myCustomId', function ( Container $container ) {
	/*
	 * $customClass = new Class();
	 * $customClass->setA(1);
	 * $customClass->setB($container->get( PostsRepository::class ));
	 *
	 * return $customClass;
	 */
	echo 'call my custom builder';
} );

$container->get('myCustomId');


//
//$post
//	->setPostTitle('Залоговок2')
//	->setPostContent('Содержимое2');

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

//print_r([serialize($post)]);
//
//EntityManager::getInstance()->save();

//$attrService = (new \USP\Core\Attributes\AttributesService());
//$attribute = $attrService->getClassAttribute(Post::class, Entity::class);
//print_r( [
//	$attribute->getName(),
//	$attribute->getArguments()
//	]);

//$classReflection = new ReflectionClass(Post::class);
//
//$properties = $classReflection->getProperties( );
//print_r(array_map(function($property){
//	$attributes = $property->getAttributes();
//	return array_map(function($attribute) use ($property){
//		return [
//			$property->getName(),
//			$attribute->getName(),
//			$attribute->getArguments()
//		];
//	}, $attributes);
//}, $properties));

//$attributes = $classReflection->getAttributes(Entity::class );
//$classReflection = new ReflectionProperty(Post::class);
//print_r(array_map(function($attribute){
//	return [
//		$attribute->getName(),
//		$attribute->getArguments()
//	];
//}, $attributes));
//print_r([$classReflection->getDocComment()]);