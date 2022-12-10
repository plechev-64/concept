<?php

namespace USP\Core\Attributes;

use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;

class AttributesService {

	/**
	 * @param   string  $className
	 * @param   string  $attributeName
	 *
	 * @return ReflectionAttribute|null
	 * @throws ReflectionException
	 */
	public function getClassAttribute( string $className, string $attributeName ): ?ReflectionAttribute {
		$classReflection = new ReflectionClass( $className );
		$attributes      = $classReflection->getAttributes( $attributeName );
		if ( ! isset( $attributes[0] ) ) {
			return null;
		}

		return $attributes[0];
	}

	/**
	 * @param   string  $className
	 * @param   string  $attributeName
	 */
	public function getClassProperties( string $className, string $attributeName ): ?array {

		try {
			$classReflection = new ReflectionClass( $className );
		}
		catch ( ReflectionException $e ) {
			return null;
		}

		$properties = $classReflection->getProperties();

		$result = [];
		foreach ( $properties as $property ) {
			$attributes = $property->getAttributes( $attributeName );

			if ( ! $attributes ) {
				continue;
			}

			$result[ $property->getName() ] = $attributes[0];

		}

		return $result;
	}

	/**
	 * @param   string  $entityClassName
	 */
	public function getColumnPropertyMap( string $entityClassName ): array {

		$columnAttributes = $this->getClassProperties( $entityClassName, Column::class );

		$columnPropertyMap = [];
		foreach ( $columnAttributes as $property => $attribute ) {
			$columnPropertyMap[ $property ] = $attribute->getArguments();
		}

		return $columnPropertyMap;

	}

}