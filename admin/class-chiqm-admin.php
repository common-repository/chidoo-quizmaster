<?php

/**
 * The admin-specific functionality of the plugin.
 */

if(!class_exists('ChiQmAdmin')){

class ChiQmAdmin {

	private $plugin_name;
	private $version;

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/chiqm-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * JavaScript for the admin area.
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/chiqm-admin.js', array( 'jquery' ), $this->version, false );
		wp_localize_script($this->plugin_name, 'chiqm', array('ajaxurl' => admin_url('admin-ajax.php')));

	}

	public function chiqm_noptions_page() {
		add_menu_page(
   		'Chidoo Quizmaster',
			'Quizmaster',
			'manage_options',
			'chiqm_apps',
			[$this,'chiqm_options_page_callback'],
			plugin_dir_url(__FILE__) . 'images/chiqm-icon.png',
			20
    );

		/*
		 * 
		 */
		add_submenu_page(
    	'chiqm_apps',
        'Custom Apps',
        'How to create custom apps',
        'manage_options',
        'chiqm_custom_apps',
        [$this,'chiqm_options_custom_apps_callback']
    );

		/*
		 * A hidden submenu for the app-specific page
		 */
		add_submenu_page(
    	'options.php',
        'Configure Quizmaster App',
        'Configure Quizmaster App',
        'manage_options',
        'chiqm_configure_app',
        [$this,'chiqm_options_configure_app_callback']
    );
	}
	
	/*
	 * Backend page callback
	 */
	public function chiqm_options_page_callback(){
		require_once(plugin_dir_path(__FILE__) . 'partials/chiqm-admin-noptions.php');
	}

	/*
	 * Backend single app configuration page callback
	 */
	public function chiqm_options_configure_app_callback($args){
		require_once(plugin_dir_path(__FILE__) . 'partials/chiqm-admin-options-configure-app.php');
	}

	/*
	 * Backend single app configuration page callback
	 */
	public function chiqm_options_custom_apps_callback($args){
		require_once(plugin_dir_path(__FILE__) . 'partials/chiqm-admin-custom-apps.php');
	}

	/*
	 * Ajax handler for backend app activation
	 */
	public function chiqm_toggle_app_status($args){

		if(!current_user_can('manage_options')){
			$r['chiqm_status'] = CHIQM_ERROR;
			$r['chiqm_status_name'] = 'CHIQM_ERROR';
			$r['chiqm_val'] = 'Insufficient capabilities.';
			echo json_encode($r);
			die();
		}

		// check if params are set
		$retAry = chiqmGetRequiredAjaxParams(['status','slug']);

		if(!(is_array($retAry)) && $retAry == false){
			$r['chiqm_status'] = CHIQM_ERROR;
			$r['chiqm_status_name'] = 'CHIQM_ERROR';
			$r['chiqm_val'] = 'Wrong parameters.';
			echo json_encode($r);
			die();
		}

		// values set, now validation
		if( empty($retAry['status']) || (!in_array($retAry['status'],[CHIQM_APP_STATUS_ACTIVE, CHIQM_APP_STATUS_INACTIVE])) ){
			$r['chiqm_status'] = CHIQM_ERROR;
			$r['chiqm_status_name'] = 'CHIQM_ERROR';
			$r['chiqm_val'] = 'Wrong status.';
			echo json_encode($r);
			die();
		}

		// slug format validation
		if( !preg_match('/^[a-z]+[a-z\-]*[a-z]+$/',$retAry['slug']) ){
			$r['chiqm_status'] = CHIQM_ERROR;
			$r['chiqm_status_name'] = 'CHIQM_ERROR';
			$r['chiqm_val'] = 'Incorrect slug format: '.$retAry['slug'];
			echo json_encode($r);
			die();
		}

		// slug validation
		$applist = get_option('chiqm_applist');
		if( empty($retAry['slug']) || (!in_array($retAry['slug'], array_keys($applist))) ){
			$r['chiqm_status'] = CHIQM_ERROR;
			$r['chiqm_status_name'] = 'CHIQM_ERROR';
			$r['chiqm_val'] = 'Incorrect slug.';
			$r['applist'] = array_keys($applist);
			echo json_encode($r);
			die();
		}

		// set the new status
		if($retAry['status'] == CHIQM_APP_STATUS_ACTIVE){
			$applist[$retAry['slug']]['status'] = CHIQM_APP_STATUS_INACTIVE;
		} else {
			$applist[$retAry['slug']]['status'] = CHIQM_APP_STATUS_ACTIVE;
		}
		update_option('chiqm_applist',$applist);

		$r['chiqm_status'] = CHIQM_SUCCESS;
		$r['chiqm_status_name'] = 'CHIQM_SUCCESS';
		$r['chiqm_val'] = $applist[$retAry['slug']]['status'];

		echo json_encode($r);
		die();
	
	}

	/*
	 * Ajax handler for custom app directory
	 */
	public function chiqm_update_custom_appdir($args){

		if(!current_user_can('manage_options')){
			$r['chiqm_status'] = CHIQM_ERROR;
			$r['chiqm_status_name'] = 'CHIQM_ERROR';
			$r['chiqm_val'] = 'Insufficient capabilities.';
			echo json_encode($r);
			die();
		}

		// check if params are set
		//$retAry = chiqmGetRequiredAjaxParams(['custom_appdir']);

		$retAry = [];

		if( isset($_POST['custom_appdir']) ){
			$retAry['custom_appdir'] = sanitize_text_field($_POST['custom_appdir']);
		} else {
			$r['chiqm_status'] = CHIQM_ERROR;
			$r['chiqm_status_name'] = 'CHIQM_ERROR';
			$r['chiqm_val'] = 'Wrong parameters.';
			echo json_encode($r);
			die();
		}
		 
		// $retAry is allowed to be empty, so validate content if not empty
		if( !empty($retAry['custom_appdir']) && !file_exists($retAry['custom_appdir']) ){
			$r['chiqm_status'] = CHIQM_ERROR;
			$r['chiqm_status_name'] = 'CHIQM_ERROR';
			$r['chiqm_val'] = 'Given directory does not exist.';
			echo json_encode($r);
			die();
		}
		
		if( !empty($retAry['custom_appdir']) && !is_dir($retAry['custom_appdir']) ){
			$r['chiqm_status'] = CHIQM_ERROR;
			$r['chiqm_status_name'] = 'CHIQM_ERROR';
			$r['chiqm_val'] = 'Given path is not a directory.';
			echo json_encode($r);
			die();
		}
	
		if( !empty($retAry['custom_appdir']) && validate_file( $retAry['custom_appdir']) != 0 ){
			$r['chiqm_status'] = CHIQM_ERROR;
			$r['chiqm_status_name'] = 'CHIQM_ERROR';
			$r['chiqm_val'] = 'The custom folder must be an absolute path.';
			echo json_encode($r);
			die();
		}

		if( !empty($retAry['custom_appdir']) && validate_file(basename($retAry['custom_appdir']), [CHIQM_CUSTOM_APPS_DIR]) != 0  ){
			$r['chiqm_status'] = CHIQM_ERROR;
			$r['chiqm_status_name'] = 'CHIQM_ERROR';
			$r['chiqm_val'] = 'The custom folder must be named `'.CHIQM_CUSTOM_APPS_DIR.'`';
			echo json_encode($r);
			die();
		}

		update_option('chiqm_custom_appdir',$retAry['custom_appdir']);

		$r['chiqm_status'] = CHIQM_SUCCESS;
		$r['chiqm_status_name'] = 'CHIQM_SUCCESS';
		$r['chiqm_val'] = get_option('chiqm_custom_appdir');
		echo json_encode($r);
		die();
	}

	/*
	 * Ajax handler for custom app url
	 */
	public function chiqm_update_custom_appurl($args){
		
		if(!current_user_can('manage_options')){
			$r['chiqm_status'] = CHIQM_ERROR;
			$r['chiqm_status_name'] = 'CHIQM_ERROR';
			$r['chiqm_val'] = 'Insufficient capabilities.';
			echo json_encode($r);
			die();
		}

		$retAry = [];
		if( isset($_POST['custom_appurl']) ){
			$retAry['custom_appurl'] = sanitize_text_field($_POST['custom_appurl']);
		} else {
			$r['chiqm_status'] = CHIQM_ERROR;
			$r['chiqm_status_name'] = 'CHIQM_ERROR';
			$r['chiqm_val'] = 'Wrong parameters.';
			echo json_encode($r);
			die();
		}

		if( !wp_http_validate_url($retAry['custom_appurl']) ){
			$r['chiqm_status'] = CHIQM_ERROR;
			$r['chiqm_status_name'] = 'CHIQM_ERROR';
			//$r['chiqm_val'] = 'Invalid URL: '.$retAry['custom_appurl'];
			$r['chiqm_val'] = 'Invalid URL format.';
			echo json_encode($r);
			die();
		}
	
		//if( !preg_match('/.*app(s|s\/)$/',$retAry['custom_appurl']) ){
		if( !preg_match('/.*('.CHIQM_CUSTOM_APPS_DIR.'|'.CHIQM_CUSTOM_APPS_DIR.'\/)$/',$retAry['custom_appurl']) ){
			$r['chiqm_status'] = CHIQM_ERROR;
			$r['chiqm_status_name'] = 'CHIQM_ERROR';
			$r['chiqm_val'] = 'URL must point to '.CHIQM_CUSTOM_APPS_DIR.' directory.';
			echo json_encode($r);
			die();
		}

		// TODO: check if given url is reachable
	
		update_option('chiqm_custom_appurl', esc_url_raw($retAry['custom_appurl']) );

		$r['chiqm_status'] = CHIQM_SUCCESS;
		$r['chiqm_status_name'] = 'CHIQM_SUCCESS';
		$r['chiqm_val'] = esc_url(get_option('chiqm_custom_appurl'));
		echo json_encode($r);
		die();
	}

}

} // ChiQmAdmin
