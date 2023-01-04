<?php

interface TemplateInterface {
	public function getContent(?array $vars): ?string;
	public function include(?array $vars): void;
	public function getPath(): ?string;
}