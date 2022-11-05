<?php

namespace USP\Core;

abstract class Entity {

	abstract public function getRepository(): string;

	public static function _getColNames( string $className ): array {
		$colsNames = [];
		foreach ( get_class_methods( $className ) as $methodName ) {

			if ( ! \str_starts_with( $methodName, 'set' ) ) {
				continue;
			}

			$colsNames[] = strtolower( preg_replace( [
				'/([a-z\d])([A-Z])/',
				'/([^_])([A-Z][a-z])/'
			], '$1_$2', str_replace( 'set', '', $methodName ) ) );

		}

		return $colsNames;
	}

	public function _getMethods(): array {
		return get_class_methods( get_called_class() );
	}

	public function _camelToSnake( string $camelCase ): string {
		return strtolower( preg_replace( [ '/([a-z\d])([A-Z])/', '/([^_])([A-Z][a-z])/' ], '$1_$2', $camelCase ) );
	}

	public function _snakeToCamel( string $snakeCase ): string {
		return str_replace( '_', '', ucwords( $snakeCase, '_' ) );
	}

}