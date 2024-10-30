<?php

if ( ! defined( 'ABSPATH' ) ) exit; 

// NONCE & COOKIE
if (isset($_POST['kvl_locale'])){
	require 'wp-includes/pluggable.php';

	$retrieved_nonce = $_POST['kvl_wpnonce'];
	
	if (!wp_verify_nonce($retrieved_nonce, 'kvl_change_locale' ) )
		die( 'Failed security check' );

	setcookie( 'kvl_locale', stripslashes(wp_filter_nohtml_kses($_POST['kvl_locale'])), time() + (30 * 86400), "/");
	echo time() + (30 * 86400);

	exit;
}

// USAGE:
// [translate key="example1"]
// [translate lang="en" key="example1"]

$options = get_option('kvl_options');

//UNSESCAPE
$defaultLocale = stripslashes( $options['default-language'] );

$currentLocale = $defaultLocale;
$flagsFolder = plugin_dir_path( __FILE__ ) . 'resources/flags/';

add_action( 'init', 'kvl_lang_cookie' );
add_shortcode( 'translate', 'kvl_translateKey' );
add_action('wp_footer', 'kvl_setUpLocaleChooser');

function kvl_lang_cookie() {
  global $currentLocale;

  if (!isset($_COOKIE['kvl_locale'])) {
  	setcookie( 'kvl_locale', $defaultLocale, time() + (30 * 86400), "/");
  }
  $currentLocale = stripslashes(wp_filter_nohtml_kses($_COOKIE['kvl_locale']));
}

function kvl_setUpLocaleChooser(){
	global $options;
	global $defaultLocale;
	global $currentLocale;
	global $flagsFolder;

	$lang_codes = explode( ';', stripslashes( $options['language-list'] ) );

	$languages = array();

	foreach ($lang_codes as $language){
	
	// UNESCAPE
	$locale = stripslashes( $options['lang_code_'.$language] );
	$json = json_decode($locale, true);

		$aLanguage = 
		[
			'lang_code' => $language,
			'flag_path' => stripslashes( $options['lang_'.$language] ),
			'display_name' => stripslashes( $options['locale_display_name_'.$language] ),
		];

		array_push($languages, $aLanguage);
	}

	wp_nonce_field('kvl_change_locale', 'kvl_wpnonce');

	echo "
		<select title='Select a language' class='kvl-selectpicker kvl-selectpicker-front' name='language_picker' id='language_picker'>
						";
						foreach($languages as $language){

							$val = $language['lang_code'] . ':' . $language['flag_path'];

							if ( stripslashes( $options['lang_'.$currentLocale] ) == $language['flag_path']){
							echo "
							<option selected value=\"$val\">&ensp;{$language['display_name']}</option>
							";
							} else {
								echo "
								<option value=\"$val\">&ensp;{$language['display_name']}</option>
								";
							}
								}
						echo "
						</select>
						";

}

function kvl_translateKey($atts){
	//$dir = plugin_dir_path(__FILE__);

	global $options;
	global $defaultLocale;
	global $currentLocale;

	if (!isset($_COOKIE['kvl_locale'])){
		$currentLocale = $defaultLocale;
	} else {
		$currentLocale = stripslashes(wp_filter_nohtml_kses($_COOKIE['kvl_locale']));
	}
	
	$args = shortcode_atts(
		array(
			'locale' => '',	// The locale
			'key' => ''		// The key
		),
		$atts
	);

	$forceLocale = (String) $args['locale'];
	
	if ($forceLocale != ''){
		$currentLocale = $forceLocale;
	}

	$key = (String) $args['key'];

	// UNSESCAPE
	$locale = stripslashes( $options['lang_code_'.$currentLocale] );
	$json = json_decode($locale, true);
	
	if (!isset($json[$key])){ //IF NO VALUE FOUND FOR LOCALE USE DEDAULT LOCALE
		return "<span style=\"color: red\">Key: $key missing for locale: $currentLocale</span>";
		
	}
	
	$translationStr = $json[$key];
	
	return $translationStr;

}
?>