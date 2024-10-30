<?php

/**
 * ChiQm - the core plugin class
 */

if(!class_exists('ChiQm')){

class ChiQm {

	protected $loader;
	protected $plugin_name;
	protected $version;

	public function __construct() {

		$this->load_dependencies();
		
		if ( defined( 'CHIDOO_QUIZMASTER_VERSION' ) ) {
			$this->version = CHIDOO_QUIZMASTER_VERSION;
		} else {
			$this->version = '0.0.1';
		}
		if ( defined( 'CHIDOO_QUIZMASTER' ) ) {
			$this->plugin_name = CHIDOO_QUIZMASTER;
		} else {
			$this->plugin_name = 'chidoo-quizmaster';
		}

		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	private function load_dependencies() {


		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/chiqm-definitions.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-chiqm-loader.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-chiqm-i18n.php';

		/**
		 * All the actions that occur in the admin area.
		 * NILS: This class also defines methods for maintaining registration of apps
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-chiqm-admin.php';

		/**
		 * All the actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-chiqm-public.php';

		$this->loader = new ChiQmLoader();

	}

	private function set_locale() {
		$plugin_i18n = new ChiQmi18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	private function define_admin_hooks() {

		$plugin_admin = new ChiQmAdmin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		//$this->loader->add_action( 'admin_menu', $plugin_admin, 'chiqm_options_page' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'chiqm_noptions_page' );
//		$this->loader->add_action( 'admin_init', $plugin_admin, 'chiqm_settings_init' );

		/*
		 * Admin ajax actions
		 * TODO: maybe somewhere else
		 */
		$this->loader->add_action('wp_ajax_chiqm_toggle_app_status',$plugin_admin,'chiqm_toggle_app_status');
		$this->loader->add_action('wp_ajax_chiqm_update_custom_appdir',$plugin_admin,'chiqm_update_custom_appdir');
		$this->loader->add_action('wp_ajax_chiqm_update_custom_appurl',$plugin_admin,'chiqm_update_custom_appurl');

	}

	private function define_public_hooks() {

		$plugin_public = new ChiQmPublic( $this->get_plugin_name(), $this->get_version() );

		/*
		 * Main style and scripts for the plugin
		 */
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'init', $plugin_public, 'chiqm_init' );

	}

	public function run() {
		$this->loader->run();
	}

	public function get_plugin_name() {
		return $this->plugin_name;
	}

	public function get_loader() {
		return $this->loader;
	}

	public function get_version() {
		return $this->version;
	}

}

} // class_exists
