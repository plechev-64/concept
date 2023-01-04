<?php

namespace USP\Core\Template;

interface TemplateInterface {
	public function getContent(?array $vars = null): ?string;
	public function include(?array $vars = null): void;
	public function getPath(): ?string;
}