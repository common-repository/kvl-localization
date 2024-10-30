<?php

if ( ! defined( 'ABSPATH' ) ) exit;


function kvl_options_page(){
	// setting_fields() implements nonce
	/*
	if ($_SERVER['REQUEST_METHOD'] === 'POST'){
		$retrieved_nonce = $_POST['_wpnonce'];
		die($retrieved_nonce);
		if (!wp_verify_nonce($retrieved_nonce, 'kvl_save_options' ) )
			die( 'Failed security check' );
	}
	*/

	// CHECK PERMISSION
	if (!current_user_can('edit_plugins')){
		die( 'Unauthorized access' );
	}
	
	?>
	<div class="wrap">
	
		<?php screen_icon(); ?>
		
		<h2><?php _e('Key Value Localization (KVL)', 'kvl'); ?></h2>
		<p><?php _e('
		Simple and free localization plugin by Dispecto (www.dispecto.com).<br>
		Please follow the instructions carefully.<br><br>
		* Type in your default language. Example: en<br>
		* Type in the list of languages that your page will support separated by semicolon. Example: en;fi;de<br>
		* Select: Save Options<br>
		* More options have appeared to the options page<br>
		* Choose the desired flags for the locales listed in the default languages -list and give them their corresponding display names. These names will be displayed in your wordpress page in the language selector dropdown-menu.<br>
		* Overwrite the default CSS for the language selector dropdown-menu. By default, the dropdown-menu is fixed at the bottom right corner of the browser window.<br>
		* You are provided with a json-template to start working with for each of the locales.<br>
		* To add a test translation, you can remove the content inside the curly brackets and add a new line inside the brackets.<br>
		* For a English locale you would add something like: "test-translation": "This is a test translation" inside the curly brackets.<br>
		* And for a Finnish locale you would add something like: "test-translation": "Tämä on testikäännös" inside the curly brackets.<br>
		* In the WP page editor you can now add content like this: [translate key="test-translation"].<br>
		* Now depending on the selected language the content will be shown as "This is a test translation" OR "Tämä on testikäännös".<br>
		* Start localizing your content now!
		', 'kvl'); ?></p>
		<?php
		//settings_fields() implements nonce
		//$save_options_general_url = wp_nonce_url('options.php', 'kvl_settings_group');
		echo "<form method='POST' action='options.php'>";
			
				settings_fields('kvl_settings_group');
			?>
			<table class="form-table">
				<?php kvl_render_options(); ?>
			</table>
			<input type="submit" class="button-primary" value="<?php _e('Save Options', 'kvl'); ?>"/>
			<input type="hidden" name="kvl-submit" value="Y"/>
		</form>
		
	</div>
	
	<?php
}

function kvl_textarea_disable_tab_focus(){
	?>
	<script type="text/javascript">
		(function() {
		var textareas = document.getElementsByTagName('textarea');
		var count = textareas.length;
		for(var i=0;i<count;i++){
			textareas[i].onkeydown = function(e){
				if(e.keyCode==9 || e.which==9){
					e.preventDefault();
					var s = this.selectionStart;
					this.value = this.value.substring(0,this.selectionStart) + '\t' + this.value.substring(this.selectionEnd);
					this.selectionEnd = s+1; 
				}
			}
		}
	})();
	</script>
	<?php
}

function kvl_init(){
	register_setting('kvl_settings_group', 'kvl_options', 'kvl_options_general_before_save');
}

add_action('admin_init', 'kvl_init');

function kvl_render_options() {
	// settings_fields() implements nonce
	//wp_nonce_field('kvl_save_options');
	//die('nonce_field');

	$options = get_option('kvl_options');
	
	if ( $options['default-language']){
		$options['default-language'] = wp_kses($options['default-language'], array());
	} else {
		$options['default-language'] = __('en', 'kvl');
	}

	if ( $options['language-list']){
		$options['language-list'] = wp_kses($options['language-list'], array());
	} 
	
	?>
	<tr valign="top"><th scope="row"><?php _e('Default language', 'kvl'); ?></th>
		<td>
			<input type="text" id="default-language" placeholder="Example: fi" name="kvl_options[default-language]" width="6" value="<?php echo $options['default-language']?>">
			<br>
			<label class="description" for="kvl_options['default-language']"><?php _e('Allows you to change the default language.', 'kvl'); ?></label>
		</td>
	</tr>
	
	<tr valign="top"><th scope="row"><?php _e('Language list', 'kvl'); ?></th>
		<td>
			<input type="text" id="language-list" placeholder="Example: en;fi;ru" name="kvl_options[language-list]" width="60" value="<?php echo $options['language-list']?>">
			<br>
			<label class="description" for="kvl_options['language-list']"><?php _e('List all available languages separated by a semicolon ( ; ).', 'kvl'); ?></label>
		</td>
	</tr>

	<?php

	//UNSESCAPE LANGUAGE LIST
	$lang_codes = preg_split( '@;@', stripslashes( $options['language-list'] ), NULL, PREG_SPLIT_NO_EMPTY );
	$template_file_location = plugin_dir_path( __FILE__ ) . 'locales-template.json';
	$localeTemplate = file_get_contents($template_file_location);


	//FLAGS

	?>
		<tr valign="top"><th scope="row"><h2>Flag options</h2></th>
			<td></td>
		</tr>
	<?php

	$flagsPath = plugin_dir_path( __FILE__ ) . 'resources/flags/';
	$flagsUrl = plugin_dir_Url( __FILE__ ) . 'resources/flags/';
	
	$imgSuffix = ".png";

	foreach($lang_codes as $lang_code) {
		
		if (!$lang_code){
			break;
		}
		
		foreach(glob($flagsPath . '*.*') as $filename){
			$filename = basename($filename);
			$filename = substr_replace($filename , '', strrpos($filename , '.') +0);
		}

		?>

		<tr valign="top">
		<th scope="row"><?php echo 'Locale identifier: ' . $lang_code; ?></th>
			<td>

				<?php
						echo "
						<select title='Select a flag' class='kvl-selectpicker' name='kvl_options[lang_$lang_code]' id='flag_$lang_code'>
							<option value=\"\">Select...</option>
						";
						foreach(glob($flagsPath . '*.*') as $filename){
							$path = $filename;
							$filename = basename($filename);
							$path = $flagsUrl . $filename;
							$filename = substr_replace($filename , '', strrpos($filename , '.') +0);

							// UNESCAPE FLAGS
							if ( stripslashes( $options["lang_$lang_code"] ) == $path ){
							echo "
							<option selected value=\"$path\">$filename</option>
							";
							} else {
								echo "
								<option value=\"$path\">$filename</option>
								";
							}
								}
						echo "
						</select>
						";
				?>
				<br>
				<label class="description" for="<?php echo $lang_code ?>">
					<?php echo 'Choose the preferred flag for the locale: ' . $lang_code ?>
				</label>
			</td>
		</tr>
		<tr valign="top">
		<th scope="row"><?php echo 'Display name: ' . $lang_code; ?></th>
			<td>
				<?php
				$displayOpts = 'locale_display_name_'.$lang_code;

				echo "
				<input type='text' id='locale-display-name' placeholder='Example: English or EN' name='kvl_options[$displayOpts]' width='60' value='$options[$displayOpts]'>
				<br>
				<label class='description' for='$displayOpts'>Please type in how this locale is displayed.</label>
				";
				?>
			</td>
		</tr>

	<?php
	}

	//LOCALIZATIONS & CSS

	$css_file_location = plugin_dir_path( __FILE__ ) . '/css/kvl_styles.css';
	$css_file_content = file_get_contents($css_file_location);


	?>
		<!-- CSS -->
		<tr valign="top"><th scope="row"><?php echo "Overwrite CSS" ?></th>
			<td>
				<textarea style="width: 100%;" rows="20" id="<?php echo 'kvl_css' ?>" placeholder="Overwrite CSS" name="kvl_options[<?php echo kvl_css ?>]"><?php echo $css_file_content ?></textarea>
				<br>
				<label class="description" for="<?php echo kvl_css ?>">
					<?php echo 'File content: ' . $css_file_location ?>
				</label>
			</td>
		</tr>
		<!-- LOCALIZATIONS -->
		<tr valign="top"><th scope="row"><h2>Localizations</h2></th>
			<td></td>
		</tr>
	<?php

	foreach($lang_codes as $lang_code) {
		
		if (!$lang_code){
			break;
		}

		// UNESCAPE
		$localeContent = stripslashes($options["lang_code_$lang_code"]);
		if ($localeContent == ""){
			$localeContent = $localeTemplate;
		}
		
		?>

		<tr valign="top">
		<th scope="row"><?php echo $lang_code ?></th>
			<td>
				<textarea style="width: 100%;" rows="20" id="<?php echo 'lang_code_' . $lang_code ?>" placeholder="Json key-value-data." name="kvl_options[<?php echo 'lang_code_' . $lang_code ?>]"><?php echo $localeContent ?></textarea>
				<br>
				<label class="description" for="<?php echo $lang_code ?>">
					<?php echo 'Json formatted data of the locale: ' . $lang_code ?>
				</label>
			</td>
		</tr>

	<?php
	}
}


function kvl_options_general_before_save( $input ){

	// CHECK PERMISSION
	if (!current_user_can('edit_plugins')){
		die( 'Unauthorized access' );
	}

	// SANITATION
	// if (!current_user_can()) //NOT IMPLEMENTING THIS. IF AN ILLEGAL USER SOMEHOW SUCCEEDS IN USING OTHER FUNCTIONS WE STILL WANT THIS FUNCTION TO RUN
	//     return;

	//DEFAULT LANGUAGE
	$input['default-language'] = wp_filter_nohtml_kses( $input['default-language'] );

	// LANGUAGE LIST
	$fixedLanguageList = wp_filter_nohtml_kses( $input['language-list'] );
	$fixedLanguageList = kvl_removeExtraDelims($fixedLanguageList, ";");
	$input['language-list'] = $fixedLanguageList;

	$lang_codes = preg_split('@;@', $input['language-list'], NULL, PREG_SPLIT_NO_EMPTY);

	//FLAGS
	foreach ($lang_codes as $lang_code){
		$input['lang_'.$lang_code] = wp_filter_nohtml_kses( $input['lang_'.$lang_code] );
	}

	//DISPLAY NAMES
	foreach ($lang_codes as $lang_code){
		$displayOpts = 'locale_display_name_'.$lang_code;
		$input[$displayOpts] = wp_filter_nohtml_kses( $input[$displayOpts] );
	}

	// STYLES
	$css_file_location = plugin_dir_path( __FILE__ ) . '/css/kvl_styles.css';
	$input['kvl_css'] = stripslashes( wp_filter_nohtml_kses( $input['kvl_css'] ) ); //NO NEED TO ESCAPE (AND WILL BREAK the "content" css-property)
	$css_file_content = $input['kvl_css'];

	file_put_contents($css_file_location, $css_file_content);

	//JSON
	foreach ($lang_codes as $lang_code){
		$input['lang_code_'.$lang_code] = wp_filter_nohtml_kses( $input['lang_code_'.$lang_code] );
	}

	return $input;
}

function kvl_removeExtraDelims($input, $delim){

	$input = preg_replace('/'.$delim.''.$delim.'+/', $delim, $input); //replace delimiter with '' if consecutive
	$input = preg_replace('/' . '^' . $delim . '/', '', $input); //replace delimiter with '' if first
	$input = preg_replace('/' . $delim . '$/', '', $input);	//replace delimiter with '' if last

	return $input;
}
?>