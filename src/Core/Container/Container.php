<?php

namespace USP\Core\Container;
//@todo переделать на PSR-11
use ReflectionClass;
use ReflectionException;

final class Container {

	private array $servicesFactories;
	private array $services;

	private function __construct() {

		$this->servicesFactories = [];
		$this->services          = [];

	}

	public static function getInstance() {
		static $instance;

		if ( null === $instance ) {
			$instance = new self();
		}

		return $instance;
	}

	public function get( string $id ): mixed {

		if ( ! $this->has( $id ) ) {
			return null;
		}

		if ( isset( $this->servicesFactories[ $id ] ) ) {
			$serviceFactory = $this->servicesFactories[ $id ];

			return $serviceFactory( $this );
		}

		if ( isset( $this->services[ $id ] ) ) {
			return $this->services[ $id ];
		}

		$this->services[ $id ] = $this->createInstance( $id );

		return $this->services[ $id ];
	}

	public function has( string $id ): bool {
		return isset( $this->servicesFactories[ $id ] ) || isset( $this->services[ $id ] ) || class_exists( $id );
	}

	public function set( string $id, \Closure $instanceBuilder ): void {
		$this->servicesFactories[ $id ] = $instanceBuilder;
		unset( $this->services[ $id ] );
	}

	/**
	 * @throws ReflectionException
	 */
	public function createInstance( string $id ) {

		$reflection = new ReflectionClass( $id );

		$constructor = $reflection->getConstructor();

		if ( null === $constructor ) {
			return new $id();
		}

		/**
		 * Если конструктор приватный, значит Singletone
		 * и инстанс получаем через статический метод getInstance
		 * @todo возможно это лишнее и надо удалить
		 */
		if ( ! $constructor->isPublic() ) {
			$id          = [ $id, 'getInstance' ];
			$constructor = $reflection->getMethod( 'getInstance' );
		}

		$parameters = $constructor->getParameters();

		if ( ! $parameters ) {
			return is_array( $id ) ? call_user_func( $id ) : new $id();
		}

		$dependencies = [];

		foreach ( $parameters as $parameter ) {

			if ( $parameter->isOptional() ) {
				$dependencies[] = $parameter->getDefaultValue();
				continue;
			}

			//todo если тип данных параметра не указан - будет Null и фатал
			$parameterType  = $parameter->getType();
			$dependencies[] = $this->get( $parameterType );

		}

		return is_array( $id ) ? call_user_func_array( $id, $dependencies ) : new $id( ...$dependencies );

	}

}