<?php

namespace USP\Core\Template;

class Template implements TemplateInterface {

	private const DEFAULT_TEMPLATE_DIR = 'templates';

	private string $name;
	private string $dir;

	/**
	 * @param   string       $name
	 * @param   string|null  $dir
	 */
	public function __construct( string $name, ?string $dir = null ) {
		$this->name = $name;
		$this->dir  = $dir?? USP_PATH.DIRECTORY_SEPARATOR.self::DEFAULT_TEMPLATE_DIR;
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getDir(): string {
		return $this->dir;
	}

	/**
	 * @param   array|null  $vars
	 *
	 * @return string|null
	 */
	public function getContent(?array $vars = null): ?string {

		ob_start();

		$this->include( $vars );

		$content = ob_get_contents();

		ob_end_clean();

		return $content;
	}

	/**
	 * @param   array|null  $vars
	 */
	public function include(?array $vars = null): void {

		if ( ! empty( $vars ) && is_array( $vars ) ) {
			extract( $vars );
		}

		$path = $this->getPath();

		if ( ! $path ) {
			return;
		}

		include $path;
	}

	/**
	 * @return string|null
	 */
	public function getPath(): ?string {

		$path = $this->dir.DIRECTORY_SEPARATOR.$this->name.'.php';

		if ( ! file_exists( $path ) ) {
			return null;
		}
		return $path;
	}
}