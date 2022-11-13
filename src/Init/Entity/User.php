<?php

namespace USP\Init\Entity;

use DateTime;
use USP\Core\Attributes\Column;
use USP\Core\Attributes\Entity;
use USP\Core\EntityAbstract;
use USP\Init\Repository\UsersRepository;

#[Entity( repository: "USP\Init\Repository\UsersRepository" )]
class User extends EntityAbstract {

	#[Column( name: "ID", primary: true )]
	public int $id;

	#[Column( name: "user_login" )]
	public string $userLogin;

	#[Column( name: "user_email" )]
	public string $userEmail;

	#[Column( name: "display_name" )]
	public string $displayName;

	#[Column( name: "user_nicename" )]
	public string $userNicename;

	#[Column( name: "user_registered" )]
	public string $userRegistered;

	/**
	 * @return int
	 */
	public function getId(): int {
		return $this->id;
	}

	/**
	 * @param   int  $id
	 *
	 * @return User
	 */
	public function setId( int $id ): User {
		$this->id = $id;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getUserLogin(): string {
		return $this->userLogin;
	}

	/**
	 * @param   string  $userLogin
	 *
	 * @return User
	 */
	public function setUserLogin( string $userLogin ): User {
		$this->userLogin = $userLogin;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getUserEmail(): string {
		return $this->userEmail;
	}

	/**
	 * @param   string  $userEmail
	 *
	 * @return User
	 */
	public function setUserEmail( string $userEmail ): User {
		$this->userEmail = $userEmail;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getDisplayName(): string {
		return $this->displayName;
	}

	/**
	 * @param   string  $displayName
	 *
	 * @return User
	 */
	public function setDisplayName( string $displayName ): User {
		$this->displayName = $displayName;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getUserNicename(): string {
		return $this->userNicename;
	}

	/**
	 * @param   string  $userNicename
	 *
	 * @return User
	 */
	public function setUserNicename( string $userNicename ): User {
		$this->userNicename = $userNicename;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getUserRegistered(): string {
		return $this->userRegistered;
	}

	/**
	 * @param   string  $userRegistered
	 *
	 * @return User
	 */
	public function setUserRegistered( string $userRegistered ): User {
		$this->userRegistered = $userRegistered;

		return $this;
	}

}