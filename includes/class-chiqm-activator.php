<?php

if(!class_exists('ChiQmActivator')){

class ChiQmActivator {

	/**
	 *
	 */
	public static function activate() {

		$chiQmAppLoader = new ChiQmAppLoader();
		$chiQmAppLoader->scanApps();

		// Clear the permalinks after the post type has been registered.
		flush_rewrite_rules(); 

	}

}

} // ChiQmActivator

?>
