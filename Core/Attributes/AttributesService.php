<?php

namespace USP\Core\Attributes;

use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

class AttributesService {

	/**
	 * @throws ReflectionException
	 */
	public function getClassAttribute(string $className, string $attributeName): ?ReflectionAttribute {
		$classReflection = new ReflectionClass($className);
		$attributes = $classReflection->getAttributes($attributeName);
		if(!isset($attributes[0])){
			return null;
		}

		return $attributes[0];
	}

	/**
	 * @throws ReflectionException
	 */
	public function getClassProperties(string $className, string $attributeName): array {
		$classReflection = new ReflectionClass($className);
		$properties = $classReflection->getProperties();

		$result = [];
		foreach($properties as $property){
			$attributes = $property->getAttributes($attributeName);

			if(!$attributes){
				continue;
			}

			$result[$property->getName()] = $attributes[0];

		}

		return $result;
	}

}