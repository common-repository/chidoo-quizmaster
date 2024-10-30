<?php

// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

$option_names = ['chiqm_applist','chiqm_custom_appdir','chiqm_custom_appurl'];

foreach($option_names as $option_name){
	if(is_multisite()){
		delete_site_option($option_name);
	} else {
		delete_option($option_name);
	}
}

?>
