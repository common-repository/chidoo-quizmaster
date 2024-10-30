<?php

require_once( CHIQM_PLUGIN_PATH . '/includes/class-chiqm-app.php');

class ChiQmMemory extends ChiQmApp
{

	public function __construct() {
		$this->slug = "chiqm-memory";
		$this->name = "The Memory by Chidoo Quizmaster";
		$this->setShortcodeName('chiqm_memory_shortcode');
		$this->setShortcodeHandler('chiqm_memory_shortcode_handler');

		$this->version = '0.0.1'; // app version
		$this->author = 'Nils Doormann <nils.doormann@chidoo.de>'; // app author
		$this->description = 'This is a configurable memory game application.'; // app description
	}

	/*
	 * The shortcode handler for the memory game
	 */
	public function chiqm_memory_shortcode_handler($atts){

		ob_start();

		?>

		<div class="chiqm-memory-container">
			<div id="gameoverlay">
				<div class="verti"><p></p></div>
			</div>
			<p class="chiqm-row">
				<input type="button" id="bmem" name="bmem" value="<?php esc_attr_e('Start Game','chidoo-quizmaster'); ?>" />
				<input type="button" id="breset" name="breset" value="<?php esc_attr_e('Reset Game','chidoo-quizmaster') ?>" style="display:none"/>
			</p>
			<div class="chiqm-row chiqm-mode">
				<label for="energymode"><?php esc_html_e('Energy mode','chidoo-quizmaster') ?></label>
				<input type="checkbox" id="energymode" name="energymode" checked></input>
			</div>

			<div class="chiqm-row chiqm-energy" style="display:none">
				<div class="energy-wrap">
					<div class="chiqm-energy-color"></div>
					<div class="chiqm-energy-value"></div>
					<div class="chiqm-energy-top"></div>
					<div class="chiqm-energy-bottom"></div>
					<div class="chiqm-energy-display" id="chiqm-energy-percentage">100%</div>
				</div>
			</div>
			
			<p class="chiqm-row">
				<label for="numpairs"><?php esc_html_e('Number of pairs','chidoo-quizmaster'); ?></label>
				<select name="numpairs" id="numpairs">
					<option value="0"><?php esc_html_e('Number of pairs','chidoo-quizmaster'); ?></option>
					<?php
						for($i = 2;$i <= 12;$i++){
						?>
							<option value="<?php echo($i); ?>" <?php echo(($i == 6) ? (' selected') : ('')); ?> ><?php echo($i); ?></option>
						<?php
						} ?>
				</select>
			</p>

			<h3 id="chiqm-available"><?php esc_html_e('Available card sets','chidoo-quizmaster'); ?>:</h3>
			<div class="chiqm-row"><div class="chiqm-om" id="optionmap"></div></div>
				
			<div id="game"></div>




		</div>

		<?php
			return ob_get_clean(); 
	}

	public function register_ajax_callbacks(){
		/*
		 * Callbacks for both, logged-in and non-logged-in users
		 */
		add_action('wp_ajax_nopriv_chiqm_memory_ajax_handler',[$this,'chiqm_memory_ajax_handler']);
		add_action('wp_ajax_chiqm_memory_ajax_handler',[$this,'chiqm_memory_ajax_handler']);

		/*
		 * Logged-in OR logged-out user call backs
		 */
		$this->register_public_ajax_callbacks();
		$this->register_private_ajax_callbacks();
	}

	/*
	 * Callbacks available for non-logged-in users
	 */
	public function register_public_ajax_callbacks(){
	}

	/*
	 * Callbacks available for logged-in users
	 */
	public function register_private_ajax_callbacks(){
	
	}

	/*
	 * A test ajax handler
	 */
	public function chiqm_memory_ajax_handler(){
		$responseData = array('name' => "Hello from ".esc_html($this->getName())."!!!");
		echo json_encode($responseData);
		die();
	}

	
	/*
	 * Extra styles go here
	 * Main css file is loaded by class-chiqm-public.php based on the app slug name.
	 */
	public function enqueueStyles(){}

	/*
	 * Extra scripts go here. 
	 * Main js file is loaded by class-chiqm-public.php based on the app slug name.
	 */
	public function enqueueScripts(){
		
		// now registered in ChiQmPublic::enqueue_scripts
		wp_enqueue_script( 'chiqm-abcjs');

		wp_enqueue_script("jquery-effects-shake");	
		wp_enqueue_script("jquery-effects-bounce");	
		wp_enqueue_script("jquery-effects-pulsate");	
		wp_enqueue_script("jquery-effects-highlight");	

		require_once (plugin_dir_path( __FILE__ ) . 'chiqm-memory-dynamic-translation.php');
		// no tags in js strings for now
		foreach(array_keys($chiqmTransLib) as $libKey){
			$chiqmTransLib[$libKey] = esc_html(wp_strip_all_tags($chiqmTransLib[$libKey]));
		}	
		foreach(array_keys($chiqmTransAssets) as $akey){
			$chiqmTransAssets[$akey] = esc_html(wp_strip_all_tags($chiqmTransAssets[$akey]));
		}	

		wp_enqueue_script( 'chiqm-memory-lib', plugin_dir_url( __FILE__ ) . 'js/chiqm-memory-lib.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( 'chiqm-memory-lib', 'chiqmMemoryLibl10n', $chiqmTransLib );

		/*
		 * Load memory game asset maps
		 */
		wp_enqueue_script( 'chiqm-memory-asset-violin', plugin_dir_url( __FILE__ ) . 'js/chiqm-memory-asset-violin.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( 'chiqm-memory-asset-violin', 'chiqmMemoryAssetl10n', $chiqmTransAssets);
		
	}
}

?>
