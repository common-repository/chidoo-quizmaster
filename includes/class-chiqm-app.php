<?php

require_once(plugin_dir_path(__DIR__) . 'includes/interfaces/interface-chiqm-app.php');

if(!class_exists('ChiQmApp')){

class ChiQmApp implements iChiQmApp
{

	protected $slug;
	protected $name;
	protected $shortcode_name;
	protected $shortcode_handler;

	protected $author;
	protected $version;
	protected $description;
	protected $license; // TODO: license handling

	protected $status; // activation status
	protected $is_custom; // should be true if custom app

	public function __construct($slug = '', $name = '', $shortcode_name = null, $shortcode_handler = null) {
		$this->slug = $slug;
		$this->name = $name;
		$this->is_custom = 0;
		isset($shortcode_name) ? ($this->shortcode_name = $shortcode_name) : ('');
		isset($shortcode_handler) ? ($this->shortcode_handler = $shortcode_handler) : ('');
	}
	
	public function setIsCustom($custom = 0)
	{ $this->is_custom = $custom; }

	public function isCustom()
	{ return $this->is_custom; }

	public function getStatus()
	{ return $this->status; }

	public function isActive()
	{ return ($this->status == 'active') ? true : false; }

	public function setStatus($status = 'inactive')
	{ $this->status = $status; }

	public function getSlug()
	{ return $this->slug; }

	public function getName()
	{ return $this->name; }

	public function getAuthor()
	{ return $this->author; }

	public function getVersion()
	{ return $this->version; }

	public function getDescription()
	{ return $this->description; }

	public function getShortcodeName()
	{ return $this->shortcode_name; }

	public function getShortcodeHandler()
	{ return $this->shortcode_handler; }

	public function setShortcodeName($shortcode_name)
	{ $this->shortcode_name = $shortcode_name; }

	public function setShortcodeHandler($shortcode_handler)
	{ $this->shortcode_handler = $shortcode_handler; }

	public function register_ajax_callbacks(){
		/*
		 * A derived class should register callbacks 
		 * for both, logged-in and non-logged-in users here
		 */
		//add_action('wp_ajax_nopriv_chiqm_app_ajax_handler',[$this,'chiqm_app_ajax_handler']);
		//add_action('wp_ajax_chiqm_app_ajax_handler',[$this,'chiqm_app_ajax_handler']);

		/*
		 * This is an implementation example how to 
		 * differenciate between callbacks available
		 * for logged-in OR logged-out users
		 */
		//$this->register_public_ajax_callbacks();
		//$this->register_private_ajax_callbacks();
	}

	/*
	 * Callbacks available for non-logged-in users
	 */
	public function register_public_ajax_callbacks(){}

	/*
	 * Callbacks available for logged-in users
	 */
	public function register_private_ajax_callbacks(){}

	public function loadConfigurationPage(){}

	public function enqueueStyles(){}

	public function enqueueScripts(){}

}

} // ChiQmApp

?>
