<?php

namespace USP\Init\Entity;

use USP\Core\Attributes\Entity;
use USP\Core\Attributes\Column;
use USP\Core\Collections\ArrayCollection;
use USP\Core\EntityAbstract;
use USP\Init\Repository\PostsRepository;

#[Entity( repository: "USP\Init\Repository\PostsRepository" )]
class Post extends EntityAbstract {

	#[Column( name: "ID", primary: true )]
	private ?int $id = null;

	#[Column( name: "post_author", entityClass: User::class )]
	private User $postAuthor;

	#[Column( name: "post_status" )]
	private string $postStatus;

	#[Column( name: "post_type" )]
	private string $postType;

	#[Column( name: "post_date" )]
	private ?string $postDate = null;

	#[Column( name: "post_modified" )]
	private ?string $postModified = null;

	#[Column( name: "post_title" )]
	private string $postTitle;

	#[Column( name: "post_content" )]
	private string $postContent;

	#[Column( name: "post_excerpt" )]
	private ?string $postExcerpt = null;

	#[Column( name: "post_parent" )]
	private ?int $postParent = null;

	#[Column( name: "post_name" )]
	private ?string $postName = null;

	#[Column( name: "post_mime_type" )]
	private ?string $postMimeType = null;

	#[Column( name: "guid" )]
	private ?string $guid = null;

	#[Column( name: "comment_count" )]
	private ?int $commentCount = null;

	#[Column( name: "comment_status" )]
	private ?string $commentStatus = null;

	/**
	 * @return int|null
	 */
	public function getId(): ?int {
		return $this->id;
	}

	/**
	 * @param   int|null  $id
	 *
	 * @return Post
	 */
	public function setId( ?int $id ): Post {
		$this->id = $id;

		return $this;
	}

	/**
	 * @return User
	 */
	public function getPostAuthor(): User {
		return $this->postAuthor;
	}

	/**
	 * @param   User  $postAuthor
	 *
	 * @return Post
	 */
	public function setPostAuthor( User $postAuthor ): Post {
		$this->postAuthor = $postAuthor;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getPostStatus(): string {
		return $this->postStatus;
	}

	/**
	 * @param   string  $postStatus
	 *
	 * @return Post
	 */
	public function setPostStatus( string $postStatus ): Post {
		$this->postStatus = $postStatus;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getPostType(): string {
		return $this->postType;
	}

	/**
	 * @param   string  $postType
	 *
	 * @return Post
	 */
	public function setPostType( string $postType ): Post {
		$this->postType = $postType;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getPostDate(): ?string {
		return $this->postDate;
	}

	/**
	 * @param   string|null  $postDate
	 *
	 * @return Post
	 */
	public function setPostDate( ?string $postDate ): Post {
		$this->postDate = $postDate;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getPostModified(): ?string {
		return $this->postModified;
	}

	/**
	 * @param   string|null  $postModified
	 *
	 * @return Post
	 */
	public function setPostModified( ?string $postModified ): Post {
		$this->postModified = $postModified;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getPostTitle(): string {
		return $this->postTitle;
	}

	/**
	 * @param   string  $postTitle
	 *
	 * @return Post
	 */
	public function setPostTitle( string $postTitle ): Post {
		$this->postTitle = $postTitle;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getPostContent(): string {
		return $this->postContent;
	}

	/**
	 * @param   string  $postContent
	 *
	 * @return Post
	 */
	public function setPostContent( string $postContent ): Post {
		$this->postContent = $postContent;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getPostExcerpt(): ?string {
		return $this->postExcerpt;
	}

	/**
	 * @param   string|null  $postExcerpt
	 *
	 * @return Post
	 */
	public function setPostExcerpt( ?string $postExcerpt ): Post {
		$this->postExcerpt = $postExcerpt;

		return $this;
	}

	/**
	 * @return int|null
	 */
	public function getPostParent(): ?int {
		return $this->postParent;
	}

	/**
	 * @param   int|null  $postParent
	 *
	 * @return Post
	 */
	public function setPostParent( ?int $postParent ): Post {
		$this->postParent = $postParent;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getPostName(): ?string {
		return $this->postName;
	}

	/**
	 * @param   string|null  $postName
	 *
	 * @return Post
	 */
	public function setPostName( ?string $postName ): Post {
		$this->postName = $postName;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getPostMimeType(): ?string {
		return $this->postMimeType;
	}

	/**
	 * @param   string|null  $postMimeType
	 *
	 * @return Post
	 */
	public function setPostMimeType( ?string $postMimeType ): Post {
		$this->postMimeType = $postMimeType;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getGuid(): ?string {
		return $this->guid;
	}

	/**
	 * @param   string|null  $guid
	 *
	 * @return Post
	 */
	public function setGuid( ?string $guid ): Post {
		$this->guid = $guid;

		return $this;
	}

	/**
	 * @return int|null
	 */
	public function getCommentCount(): ?int {
		return $this->commentCount;
	}

	/**
	 * @param   int|null  $commentCount
	 *
	 * @return Post
	 */
	public function setCommentCount( ?int $commentCount ): Post {
		$this->commentCount = $commentCount;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getCommentStatus(): ?string {
		return $this->commentStatus;
	}

	/**
	 * @param   string|null  $commentStatus
	 *
	 * @return Post
	 */
	public function setCommentStatus( ?string $commentStatus ): Post {
		$this->commentStatus = $commentStatus;

		return $this;
	}

}