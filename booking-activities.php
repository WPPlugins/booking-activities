<?php  
/**
 * Plugin Name: Booking Activities
 * Plugin URI: http://booking-activities.fr/en
 * Description: Create your activity calendars with drag and drop and book scheduled events with one click. Enable online payments of reservations with WooCommerce.
 * Version: 1.0.8
 * Author: Booking Activities Team
 * Author URI: http://booking-activities.fr/en
 * Text Domain: booking-activities
 * Domain Path: /languages/
 * License: GPL3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * 
 * This file is part of Booking Activities.
 * 
 * Booking Activities is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 * 
 * Booking Activities is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with Booking Activities. If not, see <http://www.gnu.org/licenses/>.
 * 
 * @package Booking Activities
 * @category Core
 * @author Booking Activities Team
 * 
 * Copyright 2017 Yoan Cutillas
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) { exit; }


// GLOBALS AND CONSTANTS
if( ! defined( 'BOOKACTI_VERSION' ) )			{ define( 'BOOKACTI_VERSION', '1.0.8' ); }
if( ! defined( 'BOOKACTI_PLUGIN_NAME' ) )		{ define( 'BOOKACTI_PLUGIN_NAME', 'booking-activities' ); }
if( ! defined( 'BOOKACTI_PLUGIN_BASENAME' ) )	{ define( 'BOOKACTI_PLUGIN_BASENAME', plugin_basename( __FILE__ ) ); }


// HEADER STRINGS (For translation)
__( 'Booking Activities', BOOKACTI_PLUGIN_NAME );
__( 'Create your activity calendars with drag and drop and book scheduled events with one click. Enable online payments of reservations with WooCommerce.', BOOKACTI_PLUGIN_NAME );
/* translators: Add " /lang-code " after this url and check if the url exists, else do not translate. Ex: http://booking-activities.fr/fr works, but http://booking-activities.fr/nl doesn't exist yet. */
__( 'http://booking-activities.fr/en', BOOKACTI_PLUGIN_NAME );


// INCLUDE LANGUAGES FILES
add_action( 'plugins_loaded', 'bookacti_load_textdomain' );
function bookacti_load_textdomain() { load_plugin_textdomain( BOOKACTI_PLUGIN_NAME, FALSE, basename( dirname( __FILE__ ) ) . '/languages/' ); }


// INCLUDE PHP FUNCTIONS
include_once( 'functions/functions-global.php' ); 
include_once( 'functions/functions-booking-system.php' ); 
include_once( 'functions/functions-template.php' );
include_once( 'functions/functions-templates-forms-control.php' );
include_once( 'functions/functions-bookings.php' );
include_once( 'functions/functions-settings.php' );

include_once( 'controller/controller-templates.php' );
include_once( 'controller/controller-booking-system.php' );
include_once( 'controller/controller-settings.php' );
include_once( 'controller/controller-bookings.php' );
include_once( 'controller/controller-shortcodes.php' );

// If woocommerce is active, include functions
if( bookacti_is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
	include_once( 'controller/controller-woocommerce-bookings.php' );
	include_once( 'controller/controller-woocommerce-backend.php' );
	include_once( 'controller/controller-woocommerce-frontend.php' );
	include_once( 'controller/controller-woocommerce-settings.php' );
	include_once( 'functions/functions-woocommerce.php' );
}


// INCLUDE DATABASE FUNCTIONS
require_once( 'model/model-global.php' );
require_once( 'model/model-install.php' );
require_once( 'model/model-templates.php' );
require_once( 'model/model-booking-system.php' );
require_once( 'model/model-bookings.php' );
require_once( 'model/model-woocommerce.php' );


// INCLUDE CLASSES
require_once( 'class/class-bookings-list.php' );


// INCLUDE SCRIPTS
add_action( 'init', 'bookacti_build_translation_array' );
function bookacti_build_translation_array() {
	global $bookacti_translation_array;
	require_once( 'languages/script-translation.php' );
}

add_action( 'admin_enqueue_scripts','bookacti_enqueue_global_scripts', 20 );
add_action( 'wp_enqueue_scripts',	'bookacti_enqueue_global_scripts', 20 );
function bookacti_enqueue_global_scripts() {
	
	// INCLUDE LIBRARIES
	wp_enqueue_script( 'bookacti-js-moment',					plugins_url( 'lib/fullcalendar/moment.min.js', __FILE__ ),				array( 'jquery' ), '1.0', true );
	wp_enqueue_style ( 'bookacti-css-fullcalendar',				plugins_url( 'lib/fullcalendar/fullcalendar.min.css', __FILE__ ) );
	wp_enqueue_style ( 'bookacti-css-fullcalendar-print',		plugins_url( 'lib/fullcalendar/fullcalendar.print.min.css', __FILE__ ),array( 'bookacti-css-fullcalendar' ), false, 'print' );
	wp_enqueue_script( 'bookacti-js-fullcalendar',				plugins_url( 'lib/fullcalendar/fullcalendar.min.js', __FILE__ ),		array( 'jquery', 'bookacti-js-moment' ), '1.0', true );
	wp_enqueue_script( 'bookacti-js-fullcalendar-locale-all',	plugins_url( 'lib/fullcalendar/fullcalendar-locale-all.min.js', __FILE__ ),array( 'jquery', 'bookacti-js-fullcalendar' ), '1.0', true );
	
	// INCLUDE STYLESHEETS
	wp_enqueue_style ( 'bookacti-css-global',		plugins_url( 'css/global.css', __FILE__ ) );
	wp_enqueue_style ( 'bookacti-css-bookings',		plugins_url( 'css/bookings.css', __FILE__ ) );
	wp_enqueue_style ( 'bookacti-css-woocommerce',	plugins_url( 'css/woocommerce.css', __FILE__ ) );
	wp_enqueue_style ( 'jquery-ui-bookacti-theme',	plugins_url( 'lib/jquery-ui/themes/booking-activities/jquery-ui.min.css', __FILE__ ), false, null );
	
	// INCLUDE JAVASCRIPT FILES
	wp_enqueue_script( 'bookacti-js-global-var',						plugins_url( 'js/global-var.js', __FILE__ ),						array(), '1.0', true );
	wp_register_script( 'bookacti-js-global-booking-system',			plugins_url( 'js/global-booking-system.js', __FILE__ ),				array( 'jquery', 'bookacti-js-global-var', 'bookacti-js-fullcalendar', 'bookacti-js-global-functions' ), '1.0', true );
	wp_register_script( 'bookacti-js-global-functions',					plugins_url( 'js/global-functions.js', __FILE__ ),					array( 'jquery', 'bookacti-js-global-var' ), '1.0', true );
	wp_register_script( 'bookacti-js-global-functions-booking-system',	plugins_url( 'js/global-functions-booking-system.js', __FILE__ ),	array( 'jquery', 'bookacti-js-global-var', 'bookacti-js-fullcalendar', 'jquery-effects-highlight' ), '1.0', true );
	wp_register_script( 'bookacti-js-global-functions-calendar',		plugins_url( 'js/global-functions-calendar.js', __FILE__ ),			array( 'jquery', 'bookacti-js-global-var', 'bookacti-js-fullcalendar', 'bookacti-js-global-functions' ), '1.0', true );
	wp_register_script( 'bookacti-js-bookings',							plugins_url( 'js/bookings.js', __FILE__ ),							array( 'jquery', 'bookacti-js-global-var', 'bookacti-js-fullcalendar', 'bookacti-js-global-functions' ), '1.0', true );
	wp_register_script( 'bookacti-js-bookings-functions',				plugins_url( 'js/bookings-functions.js', __FILE__ ),				array( 'jquery', 'bookacti-js-global-var', 'bookacti-js-fullcalendar', 'bookacti-js-global-functions', ), '1.0', true );
	wp_register_script( 'bookacti-js-bookings-dialogs',					plugins_url( 'js/bookings-dialogs.js', __FILE__ ),					array( 'jquery', 'bookacti-js-global-var', 'bookacti-js-global-functions', 'bookacti-js-moment', 'jquery-ui-dialog' ), '1.0', true );
	
	// LOCALIZE SCRIPTS
	global $bookacti_translation_array;
	wp_localize_script( 'bookacti-js-global-var',						'bookacti_localized', $bookacti_translation_array );
	wp_localize_script( 'bookacti-js-global-booking-system',			'bookacti_localized', $bookacti_translation_array );
	wp_localize_script( 'bookacti-js-global-functions',					'bookacti_localized', $bookacti_translation_array );
	wp_localize_script( 'bookacti-js-global-functions-booking-system',	'bookacti_localized', $bookacti_translation_array );
	wp_localize_script( 'bookacti-js-global-functions-calendar',		'bookacti_localized', $bookacti_translation_array );
	wp_localize_script( 'bookacti-js-bookings',							'bookacti_localized', $bookacti_translation_array );
	wp_localize_script( 'bookacti-js-bookings-functions',				'bookacti_localized', $bookacti_translation_array );
	wp_localize_script( 'bookacti-js-bookings-dialogs',					'bookacti_localized', $bookacti_translation_array );
	
	//ENQUEUE SCRIPTS
	wp_enqueue_script ( 'bookacti-js-global-var' );
	wp_enqueue_script ( 'bookacti-js-global-booking-system' );
	wp_enqueue_script ( 'bookacti-js-global-functions' );
	wp_enqueue_script ( 'bookacti-js-global-functions-booking-system' );
	wp_enqueue_script ( 'bookacti-js-global-functions-calendar' );
	wp_enqueue_script ( 'bookacti-js-bookings' );
	wp_enqueue_script ( 'bookacti-js-bookings-functions' );
	wp_enqueue_script ( 'bookacti-js-bookings-dialogs' );
}


add_action( 'admin_enqueue_scripts', 'bookacti_enqueue_backend_scripts', 30 );
function bookacti_enqueue_backend_scripts() {

	// INCLUDE STYLESHEETS
	wp_enqueue_style ( 'bookacti-css-backend',	plugins_url( 'css/backend.css', __FILE__ ) );
	wp_enqueue_style ( 'bookacti-css-templates',plugins_url( 'css/templates.css', __FILE__ ) );
	wp_enqueue_style ( 'bookacti-css-landing',	plugins_url( 'css/landing.css', __FILE__ ) );
	
	// INCLUDE JAVASCRIPT FILES
	wp_register_script( 'bookacti-js-backend-functions',		plugins_url( 'js/backend-functions.js', __FILE__ ),			array( 'jquery', 'bookacti-js-global-var', 'jquery-ui-dialog', 'jquery-ui-tabs', 'jquery-ui-tooltip' ), '1.0', true );
	wp_register_script( 'bookacti-js-templates',				plugins_url( 'js/templates.js', __FILE__ ),					array( 'jquery', 'bookacti-js-global-var', 'bookacti-js-fullcalendar', 'bookacti-js-global-functions', 'bookacti-js-templates-functions', 'bookacti-js-templates-dialogs' ), '1.0', true );
	wp_register_script( 'bookacti-js-templates-functions',		plugins_url( 'js/templates-functions.js', __FILE__ ),		array( 'jquery', 'bookacti-js-global-var', 'bookacti-js-fullcalendar', 'bookacti-js-bookings', 'jquery-effects-highlight' ), '1.0', true );
	wp_register_script( 'bookacti-js-templates-forms-control',	plugins_url( 'js/templates-forms-control.js', __FILE__ ),	array( 'jquery', 'bookacti-js-global-var', 'bookacti-js-moment' ), '1.0', true );
	wp_register_script( 'bookacti-js-templates-dialogs',		plugins_url( 'js/templates-dialogs.js', __FILE__ ),			array( 'jquery', 'bookacti-js-global-var', 'bookacti-js-global-functions', 'bookacti-js-backend-functions', 'bookacti-js-templates-forms-control', 'bookacti-js-moment', 'jquery-ui-dialog', 'jquery-ui-selectmenu' ), '1.0', true );
	wp_register_script( 'bookacti-js-woocommerce-backend',		plugins_url( 'js/woocommerce-backend.js', __FILE__ ),		array( 'jquery', 'bookacti-js-global-var', 'bookacti-js-moment' ), '1.0', true );
	
	// LOCALIZE SCRIPTS
	global $bookacti_translation_array;
	wp_localize_script( 'bookacti-js-templates',				'bookacti_localized', $bookacti_translation_array );
	wp_localize_script( 'bookacti-js-templates-functions',		'bookacti_localized', $bookacti_translation_array );
	wp_localize_script( 'bookacti-js-templates-forms-control',	'bookacti_localized', $bookacti_translation_array );
	wp_localize_script( 'bookacti-js-templates-dialogs',		'bookacti_localized', $bookacti_translation_array );
	
	//ENQUEUE SCRIPTS
	wp_enqueue_script ( 'bookacti-js-backend-functions' );
	wp_enqueue_script ( 'bookacti-js-templates' );
	wp_enqueue_script ( 'bookacti-js-templates-functions' );
	wp_enqueue_script ( 'bookacti-js-templates-forms-control' );
	wp_enqueue_script ( 'bookacti-js-templates-dialogs' );
	wp_enqueue_script ( 'bookacti-js-woocommerce-backend' );
}


add_action( 'wp_enqueue_scripts', 'bookacti_enqueue_frontend_scripts', 30 );
function bookacti_enqueue_frontend_scripts() {
	// INCLUDE STYLESHEETS
	wp_enqueue_style ( 'bookacti-css-frontend', plugins_url( 'css/frontend.css', __FILE__ ) );
	
	// INCLUDE JAVASCRIPT FILES
	wp_register_script( 'bookacti-js-woocommerce-frontend', plugins_url( 'js/woocommerce-frontend.js', __FILE__ ), array( 'jquery', 'bookacti-js-global-var', 'bookacti-js-fullcalendar', 'bookacti-js-global-functions', 'bookacti-js-global-functions-calendar' ), '1.0', true );
	wp_register_script( 'bookacti-js-shortcodes', plugins_url( 'js/shortcodes.js', __FILE__ ),array( 'jquery', 'bookacti-js-global-var', 'bookacti-js-moment' ), '1.0', true );
	
	// LOCALIZE SCRIPTS
	global $bookacti_translation_array;
	wp_localize_script( 'bookacti-js-woocommerce-frontend', 'bookacti_localized', $bookacti_translation_array );
	wp_localize_script( 'bookacti-js-shortcodes', 'bookacti_localized', $bookacti_translation_array );
	
	//ENQUEUE SCRIPTS
	wp_enqueue_script ( 'bookacti-js-woocommerce-frontend' );
	wp_enqueue_script ( 'bookacti-js-shortcodes' );
}


// ACTIVATE
register_activation_hook( __FILE__, 'bookacti_activate' );
function bookacti_activate() {
	
	// Update current version
	delete_option( 'bookacti_version' );
	add_option( 'bookacti_version', BOOKACTI_VERSION );
	
	// Allow users to manage Bookings
	bookacti_set_role_and_cap();

	// Create tables in database
    bookacti_create_tables();
	
	// Insert default values for plugin settings
	bookacti_define_default_settings_constants();
	bookacti_init_settings_values();
	
	// Keep in memory the first installed date
	if( empty( get_option( 'bookacti-install-date' ) ) ) {
		update_option( 'bookacti-install-date', date( 'Y-m-d H:i:s' ) );
	}
	
	do_action( 'bookacti_activate' );
	
	// Flush rules after install
	flush_rewrite_rules();
}


// DEACTIVATION
register_deactivation_hook( __FILE__, 'bookacti_deactivate' );
function bookacti_deactivate() {
	
	do_action( 'bookacti_deactivate' );
}


// UNINSTALL
register_uninstall_hook( __FILE__, 'bookacti_uninstall' );
function bookacti_uninstall() {
	//Deregister the hourly reccuring event
	wp_clear_scheduled_hook( 'bookacti_hourly_event' );

	// Delete plugin settings
	bookacti_delete_settings();
	
	// Delete notices acknowledgement
	bookacti_reset_notices();
	
	// Drop tables and every Booking Activities Data
	delete_option( 'bookacti_version' );
	bookacti_drop_tables();
	
	// Unset roles and capabilities
	bookacti_unset_role_and_cap();
	
	do_action( 'bookacti_uninstall' );
	
	// Clear any cached data that has been removed
	wp_cache_flush();
}


// UPDATE
add_action( 'init', 'bookacti_check_version', 5 );
function bookacti_check_version() {
	if( get_option( 'bookacti_version' ) !== BOOKACTI_VERSION ) {
		bookacti_activate();
		do_action( 'bookacti_updated' );
	}
}


// ADMIN MENU
add_action( 'admin_menu', 'bookacti_create_menu' );
function bookacti_create_menu(){
    // Add a menu and submenus
    $icon_url = 'dashicons-calendar-alt';
    add_menu_page( __( 'Booking Activities', BOOKACTI_PLUGIN_NAME ), _x( 'Booking Activities', 'Name of the tab in the menu', BOOKACTI_PLUGIN_NAME ), 'bookacti_manage_booking_activities', 'booking-activities', null, $icon_url, '56.5' );
    add_submenu_page( 'booking-activities',	_x( 'Booking Activities', 'Landing page title', BOOKACTI_PLUGIN_NAME ), _x( 'Home', 'Landing page tab name', BOOKACTI_PLUGIN_NAME ),'bookacti_manage_booking_activities',			'booking-activities',	'bookacti_landing_page' );
	add_submenu_page( 'booking-activities',	__( 'Calendar editor', BOOKACTI_PLUGIN_NAME ),							__( 'Calendar editor', BOOKACTI_PLUGIN_NAME ),				'bookacti_manage_templates',					'bookacti_calendars',	'bookacti_templates_page' );
	add_submenu_page( 'booking-activities',	__( 'Bookings', BOOKACTI_PLUGIN_NAME ),									__( 'Bookings', BOOKACTI_PLUGIN_NAME ),						'bookacti_manage_bookings',						'bookacti_bookings',	'bookacti_bookings_page' );
    add_submenu_page( 'booking-activities',	__( 'Settings', BOOKACTI_PLUGIN_NAME ),									__( 'Settings', BOOKACTI_PLUGIN_NAME ),						'bookacti_manage_booking_activities_settings',	'bookacti_settings',	'bookacti_settings_page' );
}


// Landing Page
function bookacti_landing_page() {
    include_once( 'view/view-landing.php' );
}

// Page content of Booking top-level menu
function bookacti_templates_page() {
    include_once( 'view/view-templates.php' );
}

// Page content of the first Booking submenu
function bookacti_bookings_page() {
    include_once( 'view/view-bookings.php' );
}

// Page content of the settings submenu
function bookacti_settings_page() {
    include_once( 'view/view-settings.php' );
}