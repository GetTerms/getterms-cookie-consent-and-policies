<?php
if ( isset( $_POST['gt_install_consent_api'] )
	&& isset( $_POST['_gt_consent_nonce'] )
	&& wp_verify_nonce( sanitize_key( wp_unslash( $_POST['_gt_consent_nonce'] ) ), 'gt_install_consent_api' ) ) {
    if ( ! class_exists( 'Plugin_Upgrader' ) ) {
        require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
    }
    if ( ! function_exists( 'plugins_api' ) ) {
        require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
    }
    $api = plugins_api( 'plugin_information', [ 'slug' => 'wp-consent-api' ] );
    if ( ! is_wp_error( $api ) ) {
        $upgrader = new Plugin_Upgrader( new Automatic_Upgrader_Skin() );
        $upgrader->install( $api->download_link );
        wp_safe_redirect( admin_url( 'options-general.php?page=getterms' ) );
        exit;
    }
}

$token = esc_attr(get_option('getterms-token'));
$google_consent = esc_attr(get_option('getterms-google-consent'));
$widget_slug = esc_attr(get_option('getterms-widget-slug'));

$auto_widget = esc_attr(get_option('getterms-auto-widget'));
$auto_widget = $auto_widget !== false ? $auto_widget : '0';

$manual_widget = esc_attr(get_option('getterms-manual-widget'));
$manual_widget = $manual_widget !== false ? $manual_widget : '0';

$languages = get_option('getterms-languages');
$policies = get_option('getterms-policies');
$default_language = get_option('getterms-default-language');

$default_language_name = 'Unknown Language';
switch ($default_language) {
	case 'hi-in':
		$default_language_name = 'Hindi';
		break;
	case 'en-us':
		$default_language_name = 'English (US)';
		break;
	case 'en-au':
		$default_language_name = 'English (UK)';
		break;
	case 'es':
		$default_language_name = 'Spanish';
		break;
	case 'de':
		$default_language_name = 'German';
		break;
	case 'fr':
		$default_language_name = 'French';
		break;
	case 'it':
		$default_language_name = 'Italian';
		break;
}

?>
<div class="wrap">
    <h1>GetTerms Settings</h1>
    <?php
    // --- WP Consent API dependency notice / installer ---
    // Load plugin.php only when needed in admin context
    if (!function_exists('is_plugin_active')) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }
    if ( ! is_plugin_active( 'wp-consent-api/wp-consent-api.php' ) ) :
        ?>
        <div class="notice notice-warning is-dismissible">
            <p>
                <strong>GetTerms notice:</strong> The
                <a href="https://wordpress.org/plugins/wp-consent-api/" target="_blank">WP Consent API</a>
                plugin is required for full compatibility with Google Consent Mode.
            </p>
            <form method="post" style="display:inline">
                <?php
                wp_nonce_field( 'gt_install_consent_api', '_gt_consent_nonce' );
                ?>
                <input type="hidden" name="gt_install_consent_api" value="1">
                <input type="submit" class="button button-primary"
                       value="Install & Activate WP Consent API">
            </form>
        </div>
    <?php endif; ?>
    <form method="post" action="options.php" id="getterms-form">
		<?php settings_fields('getterms-settings'); ?>
		<?php do_settings_sections('getterms-settings'); ?>
        <div class="form-row" id="getterms-token-input">
            <div class="form-label">
                <label for="getterms_token">GetTerms Token:</label>
                <input type="text" id="getterms_token" name="getterms_token" value="<?php echo esc_attr($token); ?>" />
            </div>
            <div class="button-container">
				<?php submit_button('Update', 'primary', 'submit', false); ?>
            </div>
        </div>
        <div id="getterms-error-message" style="display:none"></div>
    </form>
    <div id='getterms-content' style='<?php echo !empty($token) ? 'display: block' : 'display: none'; ?>'>
		<?php
		if (is_string($languages)) {
			$languages = json_decode($languages, true);
		}
		if (is_string($policies)) {
			$policies = json_decode($policies, true);
		}

		if (!empty($languages) && !empty($policies)) {
			include('gt-policies.php');
		} else {
			echo '<p>No policies have been set up yet.</p>';
		}
		?>
		<?php if (!empty($widget_slug)) : ?>
            <hr style='margin-top:1rem'>
            <div id="getterms-widget-content">
                <div style="padding-bottom: 10px;">
                    <h2>Cookie Consent Widget Management</h2>
                    <div class="toggle-group">
                        <label class="switch">
                            <input type="checkbox"
                                   id="getterms-auto-enable-widget-toggle"
                                   name="getterms_auto_enable" <?php checked($auto_widget, 'true'); ?> />
                            <span class="slider round"></span>
                        </label>
                        <p for="getterms-auto-enable-widget-toggle">
                            Automatically Embed in <?php echo esc_html($default_language_name); ?>
                        </p>
                    </div>
                    <div class="toggle-group">
                        <label class="switch">
                            <input type="checkbox"
                                   id="getterms-manual-enable-widget-toggle"
                                   name="getterms_manual_enable" <?php checked($manual_widget, 'true'); ?> />
                            <span class="slider round"></span>
                        </label>
                        <p for="getterms-manual-enable-widget-toggle">
                            Embed Widget Manually (Supports multilingual options)
                        </p>
                    </div>
                </div>

                <div id="getterms-widget-settings" style="display: <?php echo $manual_widget === 'true' ? 'block' : 'none' ?>">
					<?php include('gt-widgets.php')?>
                </div>
                <div>
                    <a id="getterms-cookie-link"
                       target="_blank"
                       href="https://app.getterms.io/cookie-consent/<?php echo esc_url_raw($widget_slug); ?>">
                        Update your Cookie Consent Widget style, layout, language, and content
                    </a>
                </div>
            </div>
		<?php endif; ?>
    </div>
</div>
