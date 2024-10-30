<?php
/*
Plugin Name: Key Value Localization
Plugin URI: http://www.dispecto.com/files/key-value-localization.zip
Description: A wordpress plugin that enables you to use string interpolation localization on your website.
Version: 1.0.0
Author: Joakim Sandqvist, Dispecto
Author URI: http://www.dispecto.com
License: GPL2
*/

if ( ! defined( 'ABSPATH' ) ) exit; 

//OPTIONS PAGE
$submenu = null; 

function kvl_menu(){
	$submenu = add_submenu_page('options-general.php', __('Localization Options', 'kvl'), __('KVL Localization', 'kvl'), 'administrator', 'kvl_options', 'kvl_options_page');
	add_action( 'admin_footer-' . $submenu, 'kvl_textarea_disable_tab_focus' );
}

add_action('admin_menu', 'kvl_menu');

include( plugin_dir_path( __FILE__ ) . 'options.php');

//TRANSLATION
include( plugin_dir_path( __FILE__ ) . 'functionality.php');

//ENQUEUE SCRIPTS AND STYLES
$options_css_path = plugin_dir_path( __FILE__ ) . 'css/kvl_styles.css';

function kvl_add_admin_styles() {
    // KVL STYLES
	wp_register_style( 'kvl_styles_css', plugins_url('/css/kvl_styles.css', __FILE__), false, '1.0.0', 'all');
	wp_enqueue_style( 'kvl_styles_css' );

    // SELECT 2 CSS
    wp_register_style('kvl_bootstrap_css',  plugins_url('/js/select2.css', __FILE__), false, '1.0.0', 'all');
    wp_enqueue_style('kvl_bootstrap_css');
}

function kvl_add_admin_scripts() {

	// SELECT 2 JS
    wp_register_script('kvl_bootstrap_js', plugins_url('/js/select2.js', __FILE__), false, '1.0.0', 'all');
    wp_enqueue_script('kvl_bootstrap_js');

	// OPTIONS JS
    wp_register_script('kvl_options_js', plugins_url('/js/options.js', __FILE__), true, '1.0.0', 'all');
    wp_enqueue_script('kvl_options_js');
}

function kvl_add_wp_scripts() {

	// SELECT 2 JS
    wp_register_script('kvl_bootstrap_js', plugins_url('/js/select2.js', __FILE__), false, '1.0.0', 'all');
    wp_enqueue_script('kvl_bootstrap_js');

	// OPTIONS JS
    wp_register_script('kvl_options_js', plugins_url('/js/options.js', __FILE__), true, '1.0.0', 'all');
    wp_enqueue_script('kvl_options_js');
}

function kvl_add_wp_styles() {
    // KVL STYLES
    wp_register_style( 'kvl_styles_css', plugins_url('/css/kvl_styles.css', __FILE__), false, '1.0.0', 'all');
	wp_enqueue_style( 'kvl_styles_css' );

    // SELECT 2 CSS
    wp_register_style('kvl_bootstrap_css',  plugins_url('/js/select2.css', __FILE__), false, '1.0.0', 'all');
    wp_enqueue_style('kvl_bootstrap_css');
}

add_action( 'admin_enqueue_scripts', 'kvl_add_admin_styles' );
add_action( 'admin_enqueue_scripts', 'kvl_add_admin_scripts' );
add_action( 'wp_enqueue_scripts', 'kvl_add_wp_scripts' );
add_action( 'wp_enqueue_scripts', 'kvl_add_wp_styles' );