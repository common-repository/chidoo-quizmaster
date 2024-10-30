<?php

/**
 * The public-facing functionality of the plugin.
 */

if(!class_exists('ChiQmPublic')){

class ChiQmPublic {

	private $plugin_name;
	private $version;

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/chiqm-public.css', array(), $this->version, 'all' );

		/*
		 * Load the main app style sheet based on app slug name.
		 */
		require_once plugin_dir_path(__DIR__).'includes/class-chiqm-apploader.php';
		$appLoader = new ChiQmAppLoader();
		$activeApps = $appLoader->getActiveAppsNew();
		$post = get_post();
		if(!isset($post)){ return; } // in case post doesnt exist
		foreach($activeApps as $app){

			/*
			 * Load app-specific styles only if app shortcode is on current page
			 */
			if(has_shortcode($post->post_content,$app->getShortcodeName()) ){
				$app->enqueueStyles();
				wp_enqueue_style( $app->getSlug(), 
					//($app->isCustom() ? get_option('chiqm_custom_appurl') : (plugin_dir_url( __DIR__ ) . CHIQM_APPS_DIR) ) 
					($app->isCustom() ? get_option('chiqm_custom_appurl') : (CHIQM_APPURL) ) 
					.'/'.$app->getSlug().'/css/'.$app->getSlug().'.css', array(), $this->version, 'all' );
			}
		}
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 */
	public function enqueue_scripts() {

		// only registered here, enqueued on a per-app basis
		wp_register_script( 'chiqm-abcjs', plugin_dir_url( __FILE__ ) .'js/abcjs_basic_6.0.0-beta.24-min.js', array( 'jquery' ), $this->version, false );

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/chiqm-public.js', array( 'jquery' ), $this->version, false );


		/*
		 * Load the main app script sheet based on app slug name.
		 */
		require_once plugin_dir_path(__DIR__).'includes/class-chiqm-apploader.php';
		$appLoader = new ChiQmAppLoader();
		$activeApps = $appLoader->getActiveAppsNew();
		$post = get_post();
		if(!isset($post)){ return; } // in case post doesnt exist
		foreach($activeApps as $app){

			/*
			 * Load app-specific scripts only if app shortcode is on current page
			 */
			if(has_shortcode($post->post_content,$app->getShortcodeName()) ){
				
				/*
				 * Load app-specific extra scripts
				 */
				$app->enqueueScripts();

				/*
				 * Load the main app script
				 */
				wp_enqueue_script( $app->getSlug(), 
					($app->isCustom() ? get_option('chiqm_custom_appurl') : (CHIQM_APPURL) ) 
					.'/'.$app->getSlug().'/js/'.$app->getSlug().'.js', array( 'jquery' ), $this->version, false );

				/*
				 * For all slugs that serve as js ajax vars must have underscores instead of hiphens we replace them here
				 */
				$slugrep = $app->getSlug();
				$slugrep = preg_replace('/\-/',"_",$slugrep);

				wp_localize_script($app->getSlug(), $slugrep, array('ajaxurl' => admin_url('admin-ajax.php')));
			} // if
		} // foreach
	}

	public function chiqm_init(){
		require_once plugin_dir_path(__DIR__).'includes/class-chiqm-apploader.php';
		$appLoader = new ChiQmAppLoader();
		$activeApps = $appLoader->getActiveAppsNew();

		foreach($activeApps as $app){
			//echo '<div class="app-item"><div>'.$app->getShortcodeHandler().'</div></div>';
			add_shortcode( $app->getShortcodeName(), array($app,$app->getShortcodeHandler()) );
			$app->register_ajax_callbacks();
		}
	}
}

} // ChiQmPublic
