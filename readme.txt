=== Key Value Localization ===
Contributors: valioov
Donate link:
Tags: localization, shortcode, interpolation, i18n, l10n, dispecto, key, value
Requires at least: 3.0.0
Tested up to: 4.6.1
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Unlike other localization plugins, KVL uses shortcodes to interpolate the translations. Maintaining the translations is easier this way.

== Description ==

Unlike other localization plugins, KVL uses shortcodes to interpolate the translations. Maintaining the translations is easier this way.
The translations are added to the WP database in json-format. The json-model of the locale consists of the keys and the actual translations which are later referred to as shortcodes in the html.
The plugin uses a cookie to store the language that was used in the previous session.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/key-value-localization` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. Use the Settings->KVL Localization screen to configure the plugin
1. Type in your default language. Example: en
1. Type in the list of languages that your page will support separated by semicolon. Example: en;fi;de
1. Select: Save Options
1. More options have appeared to the options page
1. Choose the desired flags for the locales listed in the default languages -list and give them their corresponding display names. These names will be displayed in your wordpress page in the language selector dropdown-menu.
1. Overwrite the default CSS for the language selector dropdown-menu. By default, the dropdown-menu is fixed at the bottom right corner of the browser window.
1. You are provided with a json-template to start working with for each of the locales.
1. To add a test translation, you can remove the content inside the curly brackets and add a new line inside the brackets.
1. For a English locale you would add something like: "test-translation": "This is a test translation" inside the curly brackets.
1. And for a Finnish locale you would add something like: "test-translation": "Tämä on testikäännös" inside the curly brackets.
1. In the WP page editor you can now add content like this: [translate key="test-translation"].
1. Now depending on the selected language the content will be shown as "This is a test translation" OR "Tämä on testikäännös".
1. Start localizing your content now!

== Screenshots ==

1. Select the default language and the list of languages separated by a semicolon. Then choose the display texts and the flags for the corresponding languages and overwrite the CSS if you like. By default the language selector is fixed at the bottom right of the browser window.
2. This screenshot shows the localizations configurations of the 2 locales listed in the language list. The configuration is in json-format.
3. For the locale "en" we have chosen to add a key "main.innovation.short". The corresponding value of the key will be shown for the user when the locale en is chosen.
4. From left to right: (1) Actual output, (2) Shortcode referring to the json-key. (3) Chosen language.

== Changelog ==

= 1.0 =
* Initial release.