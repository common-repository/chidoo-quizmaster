<?php

if(!class_exists('ChiQmi18n')){

class ChiQmi18n {


	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'chidoo-quizmaster',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}
}

} // ChiQmi18n
