<?php

?>

<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

		<?php
			if(isset($_GET['slug']) && $_GET['slug'] != ''){

				$slug = chiqmSanitizeAndValidateAppSlug($_GET['slug']);
				if($slug == false){
					?> <div class="app-item">App not found.</div> <?php
					return;
				}
	
				$appLoader = new ChiQmAppLoader();
				$app = $appLoader->getAppBySlug($slug); // this retrieves active and inactive apps
				if($app !== false){ ?>

					<div class="app-item">
						<div class="app-name"><?php esc_html_e($app->getName()); ?></div>
						<div class="app-info">
							<div class="app-status" data-status="<?php echo esc_attr($app->getStatus()); ?>" data-id="<?php echo esc_attr($app->getSlug()); ?>">
								<?php esc_html_e('Status','chidoo-quizmaster'); ?>: <a href="javascript:void(0)"><?php esc_html_e($app->getStatus(),'chidoo-quizmaster'); ?></a></div>
							<p><?php esc_html_e('Version','chidoo-quizmaster'); ?> <?php echo esc_html($app->getVersion()); ?> <?php esc_html_e('by','chidoo-quizmaster'); ?> <?php echo esc_html($app->getAuthor()); ?></p>
							<p><?php esc_html_e($app->getDescription(),'chidoo-quizmaster'); ?></p>
							<p>Shortcode: [<?php echo esc_html($app->getShortcodeName()); ?>]</p>
						</div>
					</div>

					<?php
					if($app->isActive()){
						$app->loadConfigurationPage();
					}
				}
			}	
		?>
	
</div>


