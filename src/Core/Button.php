<?php

namespace USP\Core;

class Button {

	private string $id;
	private string $label;
	private ?string $title = null;
	private ?string $href = null;

	/**
	 * @return string
	 */
	public function getId(): string {
		return $this->id;
	}

	/**
	 * @param   string  $id
	 *
	 * @return Button
	 */
	public function setId( string $id ): Button {
		$this->id = $id;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getLabel(): string {
		return $this->label;
	}

	/**
	 * @param   string  $label
	 *
	 * @return Button
	 */
	public function setLabel( string $label ): Button {
		$this->label = $label;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getTitle(): ?string {
		return $this->title;
	}

	/**
	 * @param   string|null  $title
	 *
	 * @return Button
	 */
	public function setTitle( ?string $title ): Button {
		$this->title = $title;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getHref(): ?string {
		return $this->href;
	}

	/**
	 * @param   string|null  $href
	 *
	 * @return Button
	 */
	public function setHref( ?string $href ): Button {
		$this->href = $href;

		return $this;
	}

}