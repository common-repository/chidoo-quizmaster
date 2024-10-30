<?php

/**
 * Plugin Name:       Chidoo Quizmaster
 * Plugin URI:        https://chidoo.de/chidoo-quizmaster/
 * Description:       A plugin for creating and managing various online quizzes.
 * Version:           0.0.7
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Nils Doormann
 * Author URI:        https://nils-doormann.de
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       chidoo-quizmaster
 * Domain Path:       /languages
 */

/*
 * Chidoo Quizmaster is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *  
 * Chidoo Quizmaster is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *  
 * You should have received a copy of the GNU General Public License
 * along with Chidoo Quizmaster. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*
 * Plugin Version
 */
if( ! defined('CHIDOO_QUIZMASTER_VERSION') )
	define( 'CHIDOO_QUIZMASTER_VERSION', '0.0.7' );

if( ! defined('CHIDOO_QUIZMASTER') )
	define( 'CHIDOO_QUIZMASTER', 'chidoo-quizmaster' );


/**
 * Activate the plugin.
 */
function chiqm_activate() { 
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-chiqm-apploader.php';
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-chiqm-activator.php';
	ChiQmActivator::activate();

}
register_activation_hook( __FILE__, 'chiqm_activate' );

/**
 * Deactivate the plugin.
 */
function chiqm_deactivate() { 
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-chiqm-deactivator.php';
	ChiQmDeactivator::deactivate();

}
register_deactivation_hook( __FILE__, 'chiqm_deactivate' );

/**
 * The core plugin class.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-chiqm.php';

/**
 * Begins execution of the plugin.
 */
function chiqm_run() {

	$chiqm = new ChiQm();
	$chiqm->run();

}
chiqm_run();

?>
