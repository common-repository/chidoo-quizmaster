<?php

if( ! defined('CHIQM_SUCCESS') )
	define('CHIQM_SUCCESS', 0);

if( ! defined('CHIQM_ERROR') )
	define('CHIQM_ERROR',   1);

if( ! defined('CHIQM_APP_STATUS_ACTIVE') )
	define('CHIQM_APP_STATUS_ACTIVE', 'active');

if(! defined('CHIQM_APP_STATUS_INACTIVE') )
	define('CHIQM_APP_STATUS_INACTIVE', 'inactive');

if(! defined('CHIQM_CUSTOM_APPS_DIR') )
	define('CHIQM_CUSTOM_APPS_DIR', 'apps');

if(! defined('CHIQM_APPS') )
	define('CHIQM_APPS', 'apps');

if(! defined('CHIQM_PLUGIN_PATH') )
	define( 'CHIQM_PLUGIN_PATH', plugin_dir_path( __DIR__ ) );

if(! defined('CHIQM_APPDIR') )
	define('CHIQM_APPDIR',plugin_dir_path(__DIR__).CHIQM_APPS);

if(! defined('CHIQM_APPURL') )
	define('CHIQM_APPURL',plugin_dir_url(__DIR__).CHIQM_APPS);

/*
 * Use this only for params that must have a value
 */
if(!function_exists('chiqmGetRequiredAjaxParams') ){
	function chiqmGetRequiredAjaxParams($params){

		$ret = array();

		foreach($params as $p){
			if(isset($_POST[$p])){
				$ret[$p] = sanitize_text_field($_POST[$p]);
			} else {
				return false;
			}
		}

		return $ret;
	}
} // chiqmGetRequiredAjaxParams

if(!function_exists('chiqmSanitizeAndValidateAppSlug') ){

	function chiqmSanitizeAndValidateAppSlug($slug){

		if(!isset($slug) || empty($slug)){
			return false;
		}
    
		$slug = sanitize_key($slug);
		$slug = preg_replace( '/[^a-z\-]/', '', $slug );

		// slug format validation
    if( !preg_match('/^[a-z]+[a-z\-]*[a-z]+$/',$slug) ){
			return false;
    }       
          
    // check if app exists for slug in question
    $applist = get_option('chiqm_applist');
    if( !in_array($slug, array_keys($applist))){
			return false;
    }

		return $slug;	
	}

}

?>
