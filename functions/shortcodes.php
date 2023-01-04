<?php

use USP\Core\Template\Template;

add_shortcode('userspace', 'usp_get_shortcode_userspace');
function usp_get_shortcode_userspace(): ?string {
	return (new Template('office'))->getContent();
}
