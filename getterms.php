<?php
/*
Plugin Name: GetTerms Cookie Consent and Policies
Description: Easy installation of your GetTerms Cookie Consent and Policies widget.
Version: 0.8
Author: General Labs.
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

define('GETTERMS_PLUGIN_VERSION', '0.8');

add_action('admin_menu', 'getterms_menu');
function getterms_menu()
{
	$page_title = 'GetTerms Policy and Cookie Consent Management';
	$menu_title = 'GetTerms';
	$capability = 'manage_options';
	$menu_slug = 'getterms';
	$function = 'getterms_settings_page';
	$icon_url = 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGhlaWdodD0iMzAiIHdpZHRoPSIzMCIgdmlld0JveD0iMCAwIDMwIDMwIj4KICA8cGF0aCBjbGFzcz0ibG9nb19fZXllIiBmaWxsPSIjMmIyYjJiIiBkPSJNMTUgMGM4LjMgMCAxNSA2LjcgMTUgMTVzLTYuNyAxNS0xNSAxNVMwIDIzLjMgMCAxNSA2LjcgMCAxNSAwem0wIDNDOC40IDMgMyA4LjQgMyAxNXM1LjQgMTIgMTIgMTIgMTItNS40IDEyLTEyUzIxLjYgMyAxNSAzeiIvPgogIDxwYXRoIGNsYXNzPSJsb2dvX19pcmlzIiBmaWxsPSIjMmIyYjJiIiBkPSJNMTUgOC4xaC42Yy0uNi42LS45IDEuNS0uOSAyLjQgMCAyIDEuNiAzLjYgMy42IDMuNiAxLjMgMCAyLjQtLjcgMy4xLTEuNy4zLjguNSAxLjcuNSAyLjYgMCAzLjgtMy4xIDYuOS02LjkgNi45UzguMSAxOC44IDguMSAxNXMzLjEtNi45IDYuOS02Ljl6Ii8+Cjwvc3ZnPg==';
	$position = 65;

	add_menu_page(
		$page_title,
		$menu_title,
		$capability,
		$menu_slug,
		$function,
		$icon_url,
		$position
	);
}

function getterms_settings_page()
{
	include 'gt-settings.php';
}

add_action('admin_init', 'getterms_settings');

add_action('admin_init', function () {
	// Load plugin.php only when needed in admin context
	if (!function_exists('is_plugin_active')) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	if (!is_plugin_active('wp-consent-api/wp-consent-api.php')) {
		add_action('admin_notices', function () {
			echo '<div class="notice notice-warning"><p><strong>GetTerms Plugin Notice:</strong> The <a href="https://wordpress.org/plugins/wp-consent-api/" target="_blank">WP Consent API</a> plugin is recommended for compatibility with Google Consent Mode.</p></div>';
		});
	}
});
function getterms_settings()
{
	register_setting('getterms-settings', 'getterms_token', [
		'type' => 'string',
		'sanitize_callback' => 'sanitize_text_field',
		'default' => ''
	]);
}

function getterms_settings_link($links)
{
	$settings_link = '<a href="options-general.php?page=getterms-cookie-consent-and-policies">' . __('Settings', 'getterms-cookie-consent-and-policies') . '</a>';
	array_unshift($links, $settings_link);
	return $links;
}

$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'getterms_settings_link');

add_action('wp_ajax_clear_getterms_options', 'clear_getterms_options');
function clear_getterms_options()
{
	check_ajax_referer('getterms_nonce_action', 'nonce');
	$options_to_clear = [
		'getterms-token',
		'getterms-widget-slug',
		'getterms-languages',
		'getterms-policies',
	];
	foreach ($options_to_clear as $option) {
		delete_option($option);
	}

	wp_send_json_success('Options cleared successfully.');
}

add_action('wp_ajax_set_getterms_options', 'set_getterms_options');
function set_getterms_options()
{
	check_ajax_referer('getterms_nonce_action', 'nonce');

	if (isset($_POST['options_data']) && is_array($_POST['options_data'])) {
		$options_data = map_deep(wp_unslash($_POST['options_data']), 'sanitize_text_field');
	} else {
		$options_data = null;
	}
	if (!is_null($options_data)) {
		foreach ($options_data as $option_key => $option_value) {
			update_option($option_key, $option_value);
		}

		wp_send_json_success('Options updated successfully.');
	} else {
		wp_send_json_error('No options data provided.');
	}

	wp_die();
}

add_action('wp_ajax_get_getterms_options', 'get_getterms_options');
function get_getterms_options()
{
	check_ajax_referer('getterms_nonce_action', 'nonce');

	$options_to_get = [
		'getterms-token',
		'getterms-widget-slug',
		'getterms-languages',
		'getterms-policies',
		'getterms-default-language',
	];

	$options = [];
	foreach ($options_to_get as $option) {
		$options[$option] = get_option($option);
	}

	wp_send_json_success($options);

	wp_die();
}

add_action('wp_ajax_update_getterms_auto_widget', 'update_getterms_auto_widget');
function update_getterms_auto_widget()
{

	check_ajax_referer('getterms_nonce_action', 'nonce');

	$auto_widget = isset($_POST['auto_widget']) ? sanitize_text_field(wp_unslash($_POST['auto_widget'])) : '0';
	if ($auto_widget) {
		update_option('getterms-manual-widget', 'false');
	}
	update_option('getterms-auto-widget', $auto_widget);
	update_option('getterms-show-widget', $auto_widget);

	wp_send_json_success();
}

add_action('wp_ajax_update_getterms_manual_widget', 'update_getterms_manual_widget');
function update_getterms_manual_widget()
{

	$manual_widget = isset($_POST['manual_widget']) ? sanitize_text_field(wp_unslash($_POST['manual_widget'])) : '0';

	check_ajax_referer('getterms_nonce_action', 'nonce');

	if ($manual_widget) {
		update_option('getterms-show-widget', 'false');
		update_option('getterms-auto-widget', 'false');
	}

	update_option('getterms-manual-widget', $manual_widget);

	wp_send_json_success();
}

add_action('wp_enqueue_scripts', 'getterms_add_consent_scripts', 1);
function getterms_add_consent_scripts() {

	$widget_slug = get_option('getterms-widget-slug');
	$google_consent = get_option('getterms-google-consent');
	$widget_lang = get_option('getterms-widget-language');
	$show_auto = get_option('getterms-auto-widget');
	$show_manual = get_option('getterms-manual-widget');

	if (!empty($google_consent && $google_consent === 'on')) {
		$google_consent_script = '
			window.dataLayer = window.dataLayer || [];
			function gtag() { dataLayer.push(arguments); }
			gtag("consent", "default", {
				"ad_storage": "denied",
				"ad_user_data": "denied",
				"ad_personalization": "denied",
				"analytics_storage": "denied",
				"functionality_storage": "denied",
				"personalization_storage": "denied",
				"security_storage": "denied"
			});
		';
		// Register a dummy script handle for the inline script
		wp_register_script('getterms-google-consent', false, array(), GETTERMS_PLUGIN_VERSION, false);
		wp_enqueue_script('getterms-google-consent');
		wp_add_inline_script('getterms-google-consent', $google_consent_script);
	}

	if (!empty($widget_slug)) {
		if (!empty($widget_lang) && $show_manual === 'true') {
			$src = 'https://app.getterms.io/cookie-consent/embed/' . esc_attr($widget_slug) . '/' . $widget_lang;
		} elseif ($show_auto === 'true') {
			$src = 'https://app.getterms.io/cookie-consent/embed/' . esc_attr($widget_slug);
		}

		if (!empty($src)) {
			wp_enqueue_script('getterms-widget', $src, array(), GETTERMS_PLUGIN_VERSION, false);
		}
	}

	// Check if any getterms shortcodes are present in the current post/page content
	global $post;
	if (is_object($post) && !empty($post->post_content)) {
		$languages = get_option('getterms-languages');
		$policies = get_option('getterms-policies');

		if (is_array($languages) && is_array($policies)) {
			$shortcode_found = false;
			foreach ($policies as $policy) {
				foreach ($languages as $lang_key => $lang_name) {
					$shortcode_tag = 'getterms_' . $policy . '_' . $lang_key;
					if (has_shortcode($post->post_content, $shortcode_tag)) {
						$shortcode_found = true;
						break 2;
					}
				}
			}

			if ($shortcode_found) {
				wp_enqueue_script('getterms-embed-js', 'https://app.getterms.io/dist/js/embed.js', array(), GETTERMS_PLUGIN_VERSION, true);
			}
		}
	}
}

$languages = get_option('getterms-languages');
if (is_string($languages)) {
	$languages = json_decode($languages, true);
}

$policies = get_option('getterms-policies');
if (is_string($policies)) {
	$policies = json_decode($policies, true);
}


add_action('init', 'getterms_generate_shortcodes', 5);
function getterms_generate_shortcodes()
{
	$languages = get_option('getterms-languages');
	$policies = get_option('getterms-policies');
	$token = get_option('getterms-token');

	if (is_array($languages) && !empty($languages) && is_array($policies) && !empty($policies)) {
		foreach ($policies as $originalPolicy) {
			foreach ($languages as $lang_key => $lang_name) {
				$shortcode_tag = 'getterms_' . $originalPolicy . '_' . $lang_key;

				add_shortcode($shortcode_tag, function () use ($originalPolicy, $lang_key, $lang_name, $token) {

					$transformedPolicy = $originalPolicy;
					switch ($originalPolicy) {
						case 'terms':
							$transformedPolicy = 'tos';
							break;
						case 'cookies':
							$transformedPolicy = 'cookie';
							break;
					}

					$lang_key = str_replace('_', '-', $lang_key);

					$output = '<div class="getterms-document-embed" data-getterms="' . esc_attr($token) . '" data-getterms-document="' . esc_attr($transformedPolicy) . '" data-getterms-lang="' . esc_attr($lang_key) . '" data-getterms-mode="direct" data-getterms-env="https://app.getterms.io"></div>';
					return $output;
				});
			}
		}
	}
}

add_action('admin_enqueue_scripts', 'getterms_enqueue_styles');
function getterms_enqueue_styles()
{
	wp_enqueue_style(
		'getterms-style',
		plugins_url('css/getterms.css', __FILE__),
		[],
		GETTERMS_PLUGIN_VERSION
	);
}

add_action('admin_enqueue_scripts', 'getterms_admin_scripts');
function getterms_admin_scripts()
{
	wp_enqueue_script(
		'getterms-bundle',
		plugin_dir_url(__FILE__) . 'dist/getterms.bundle.js',
		[],
		GETTERMS_PLUGIN_VERSION,
		true
	);

	wp_localize_script(
		'getterms-bundle',
		'getTermsAjax',
		array(
			'ajax_url' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('getterms_nonce_action')
		)
	);
}

add_action('wp_ajax_set_widget_lang', 'set_widget_lang');
add_action('wp_ajax_nopriv_set_widget_lang', 'set_widget_lang');

function set_widget_lang()
{
	check_ajax_referer('getterms_nonce_action', 'nonce');

	if (isset($_POST['lang'])) {
		update_option('getterms-widget-language', sanitize_text_field(wp_unslash($_POST['lang'])));
		wp_send_json_success('Language updated successfully.');
	} else {
		wp_send_json_error('No language provided.');
	}

	wp_die();
}

/*
 * Custom Menu option for Consent Widget
 */
function my_custom_menu_item($item_id, $item, $depth, $args)
{
	?>
    <div class="field-custom description-wide">
        <label for="edit-menu-item-custom-<?php echo esc_attr($item_id); ?>">
			<?php esc_html_e('Custom Menu Item Field', 'getterms-cookie-consent-and-policies'); ?><br/>
            <input type="text"
                   id="edit-menu-item-custom-<?php echo esc_attr($item_id); ?>"
                   class="widefat edit-menu-item-custom"
                   name="menu-item-custom[<?php echo esc_attr($item_id); ?>]"
                   value="<?php echo esc_attr(get_post_meta($item_id, '_menu_item_custom', true)); ?>"
            />
        </label>
    </div>
	<?php
}

add_action('wp_nav_menu_item_custom_fields', 'my_custom_menu_item', 10, 4);

function my_save_custom_menu_item($menu_id, $menu_item_db_id)
{
	if (!isset($_POST['update-nav-menu-nonce']) ||
		!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['update-nav-menu-nonce'])), 'update-nav_menu')) {
		return;
	}

	if (isset($_POST['menu-item-custom'][$menu_item_db_id])) {
		$custom_value = sanitize_text_field(wp_unslash($_POST['menu-item-custom'][$menu_item_db_id]));
		update_post_meta($menu_item_db_id, '_menu_item_custom', $custom_value);
	} else {
		delete_post_meta($menu_item_db_id, '_menu_item_custom');
	}
}

add_action('wp_update_nav_menu_item', 'my_save_custom_menu_item', 10, 2);

function my_custom_menu_item_output($items, $args)
{
	foreach ($items as &$item) {
		$custom_value = get_post_meta($item->ID, '_menu_item_custom', true);
		if (!empty($custom_value)) {
			$item->title .= ' - ' . esc_html($custom_value);
		}
	}
	return $items;
}

add_filter('wp_nav_menu_objects', 'my_custom_menu_item_output', 10, 2);

function getterms_install_wp_consent_api()
{
	if (!class_exists('WP_Consent_API')) {
		// Load required files only when needed in admin context
		if (!function_exists('plugins_api')) {
			require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
		}
		if (!class_exists('Plugin_Upgrader')) {
			require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		}

		$plugin_slug = 'wp-consent-api';
		$api = plugins_api('plugin_information', ['slug' => $plugin_slug]);

		if (is_wp_error($api)) {
			return;
		}

		$upgrader = new Plugin_Upgrader(new Automatic_Upgrader_Skin());
		$upgrader->install($api->download_link);

		activate_plugin($plugin_slug . '/' . $plugin_slug . '.php');
	}
}

add_action('wp_enqueue_scripts', function () {
	if (function_exists('wp_register_consent_script')) {
		wp_register_consent_script();
	}
});
