<?php

?>
<div id="usp-office-profile"
     class="usp-office-profile usps usps__column usps__nowrap usps__relative">
	<div class="usp-office-top usps usps__jc-end">
		<?php
		/**
		 * Fires on the account page - from the top, in the cover area.
		 *
		 * @since   1.0.0
		 */
		do_action( 'usp_area_top' ); ?>
	</div>
	<div class="usp-office-card usps usps__nowrap usps__relative">
		<div class="usp-office-left usps usps__column">
			<?php // get avatar ?>
			<div class="usp-under-ava"><?php
				/**
				 * Fires on the account page - at the bottom of the avatar.
				 *
				 * @since   1.0.0
				 */
				do_action( 'usp_area_under_ava' ); ?></div>
		</div>

		<div class="usp-office-right usps usps__column usps__grow usps__jc-between">
			<div class="usp-office-usermeta usps usps__column">
				<div class="usp-office-title usps usps__ai-start">
					<div class="usp-user-name">
						<div><?php //username ?></div>
					</div>
					<?php
					// profile menu here
					?>
					<div class="usp-action"><?php // office owner icons here ?></div>
				</div>
				<div class="usp-user-icons"><?php
					/**
					 * Fires on the account page - in the name area.
					 *
					 * @since   1.0.0
					 */
					do_action( 'usp_area_icons' ); ?></div>
			</div>

			<div class="usp-office-middle usps usps__column usps__grow">
				<div class="usp-office-bttn-act">
					<?php // actions here ?>
				</div>
			</div>

			<div class="usp-office-bottom">
				<div class="usp-office-bttn-lite usps usps__jc-end">
					<?php // counters here ?>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="usp-tabs" class="usp-tab-area usps usps__nowrap usps__relative">
	<?php
	// main menu tabs here
	?>
	<div class="usp-profile-content usps usps__nowrap usps__grow">
		<?php //content of tab here ?>
		<?php if ( function_exists( 'dynamic_sidebar' ) && is_active_sidebar( 'usp_theme_sidebar' ) ) { ?>
			<div class="usp-profile__sidebar">
				<?php dynamic_sidebar( 'usp_theme_sidebar' ); ?>
			</div>
		<?php } ?>
	</div>
</div>
