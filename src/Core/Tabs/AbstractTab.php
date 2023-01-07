<?php

namespace USP\Core\Tabs;

abstract class AbstractTab implements TabInterface{

	private string $id;
	private ?string $label = null;
	private string $icon = 'fa-cog';
	private bool $isPublic = false;
	private bool $isHidden = false;
	private ?int $counter = null;
	private string $menuId = 'main';
	private bool $isCustomTab = false;

	/**
	 * @return string
	 */
	public function getId(): string {
		return $this->id;
	}

	/**
	 * @param   string  $id
	 *
	 * @return AbstractTab
	 */
	public function setId( string $id ): AbstractTab {
		$this->id = $id;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getLabel(): ?string {
		return $this->label;
	}

	/**
	 * @param   string|null  $label
	 *
	 * @return AbstractTab
	 */
	public function setLabel( ?string $label ): AbstractTab {
		$this->label = $label;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getIcon(): string {
		return $this->icon;
	}

	/**
	 * @param   string  $icon
	 *
	 * @return AbstractTab
	 */
	public function setIcon( string $icon ): AbstractTab {
		$this->icon = $icon;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function isPublic(): bool {
		return $this->isPublic;
	}

	/**
	 * @param   bool  $isPublic
	 *
	 * @return AbstractTab
	 */
	public function setIsPublic( bool $isPublic ): AbstractTab {
		$this->isPublic = $isPublic;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function isHidden(): bool {
		return $this->isHidden;
	}

	/**
	 * @param   bool  $isHidden
	 *
	 * @return AbstractTab
	 */
	public function setIsHidden( bool $isHidden ): AbstractTab {
		$this->isHidden = $isHidden;

		return $this;
	}

	/**
	 * @return int|null
	 */
	public function getCounter(): ?int {
		return $this->counter;
	}

	/**
	 * @param   int|null  $counter
	 *
	 * @return AbstractTab
	 */
	public function setCounter( ?int $counter ): AbstractTab {
		$this->counter = $counter;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function isCustomTab(): bool {
		return $this->isCustomTab;
	}

	/**
	 * @param   bool  $isCustomTab
	 *
	 * @return AbstractTab
	 */
	public function setIsCustomTab( bool $isCustomTab ): AbstractTab {
		$this->isCustomTab = $isCustomTab;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getMenuId(): string {
		return $this->menuId;
	}

	/**
	 * @param   string  $menuId
	 *
	 * @return AbstractTab
	 */
	public function setMenuId( string $menuId ): AbstractTab {
		$this->menuId = $menuId;

		return $this;
	}
}