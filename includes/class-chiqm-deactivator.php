<?php

if(!class_exists('ChiQmDeactivator')){

class ChiQmDeactivator {

	/**
	 *
	 */
	public static function deactivate() {

		// Clear the permalinks after the post type has been registered.
		flush_rewrite_rules(); 

	}

}

} // ChiQmDeactivator

?>
