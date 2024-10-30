<?php

	/*
	 * Chidoo Quizmaster options page
	 */

?>

<div class="wrap">
	<h1><?php esc_html_e( get_admin_page_title() ); ?></h1>

	<div class="chiqm-row">
		<label for="chiqm-custom-appdir"><?php esc_html_e('Custom app directory','chidoo-quizmaster') ?></label>
		<input type="text" name="chiqm-custom-appdir" id="chiqm-custom-appdir" value="<?php echo esc_html(get_option('chiqm_custom_appdir')); ?>" />
		<input type="button" id="button-save-custom-appdir" value="<?php esc_html_e('Update','chidoo-quizmaster') ?>" />
		<div class="chiqm-msg" id="chiqm-msg-appdir"></div>
	</div>
	
	<div class="chiqm-row">
		<label for="chiqm-custom-appurl"><?php esc_html_e('Custom app URL','chidoo-quizmaster') ?></label>
		<input type="text" name="chiqm-custom-appurl" id="chiqm-custom-appurl" value="<?php echo esc_url(get_option('chiqm_custom_appurl')); ?>" />
		<input type="button" id="button-save-custom-appurl" value="<?php esc_html_e('Update','chidoo-quizmaster') ?>" />
		<div class="chiqm-msg" id="chiqm-msg-appurl"></div>
	</div>

	<?php

		$appLoader = new ChiQmAppLoader();
		$activeApps = $appLoader->getAllApps();

		foreach($activeApps as $app){
		?>
			<div class="app-item">
				<div class="app-name"><?php esc_html_e($app->getName()) ?></div>
				<div class="app-info">
					<div class="app-status" data-status="<?php echo esc_attr($app->getStatus()); ?>" data-id="<?php echo esc_attr($app->getSlug()); ?>">
						<?php esc_html_e('Status','chidoo-quizmaster'); ?>: <a href="javascript:void(0);"><?php esc_html_e($app->getStatus(),'chidoo-quizmaster'); ?></a>
					</div>
					<p><?php esc_html_e('Version','chidoo-quizmaster'); ?> <?php echo esc_html($app->getVersion()); ?> <?php esc_html_e('by','chidoo-quizmaster') ?> <?php echo esc_html($app->getAuthor()); ?></p>
					<p><?php esc_html_e($app->getDescription(),'chidoo-quizmaster'); ?></p>
					<p>Shortcode: [<?php echo esc_html($app->getShortcodeName()); ?>]</p>
					<p><a href="<?php echo esc_url(menu_page_url('chiqm_configure_app',false).'&slug='.$app->getSlug()); ?>"><?php esc_html_e('Configure app','chidoo-quizmaster') ?></a></p>
				</div>
			</div>
		<?php
		}
	?>
</div>


