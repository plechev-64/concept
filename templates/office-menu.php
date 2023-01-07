<?php

use USP\Core\Button;
use USP\Core\Collections\ArrayCollection;
use USP\Core\Template\Template;

/**
 * @var string          $menuId
 * @var array<string>   $classes
 * @var ArrayCollection $buttons
 */
?>
<div id="usp-nav-<?php echo esc_attr( $menuId ); ?>" class="">
	<?php
	/** @var Button $button */
	foreach ( $buttons as $button ) {
		echo ( new Template( 'button' ) )->getContent( [
			'button' => $button
		] );
	} ?>
</div>
