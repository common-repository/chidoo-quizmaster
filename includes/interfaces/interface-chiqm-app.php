<?php

interface iChiQmApp{

	public function getSlug();

	public function getName();

	public function getShortcodeName();

	public function getShortcodeHandler();

	public function setShortcodeName($shortcode_name);

	public function setShortcodeHandler($shortcode_handler);
	
}

?>
