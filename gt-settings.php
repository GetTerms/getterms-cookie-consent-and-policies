<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( isset( $_POST['gt_install_consent_api'] )
	&& isset( $_POST['_gt_consent_nonce'] )
	&& wp_verify_nonce( sanitize_key( wp_unslash( $_POST['_gt_consent_nonce'] ) ), 'gt_install_consent_api' )
	&& current_user_can( 'manage_options' ) ) {
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
$widget_slug = esc_attr(get_option('getterms-widget-slug'));

$auto_widget = esc_attr(get_option('getterms-auto-widget'));
$auto_widget = $auto_widget !== false ? $auto_widget : '0';

$manual_widget = esc_attr(get_option('getterms-manual-widget'));
$manual_widget = $manual_widget !== false ? $manual_widget : '0';

$auto_language_detection = esc_attr(get_option('getterms-auto-language-detection'));
$auto_language_detection = $auto_language_detection !== false ? $auto_language_detection : '0';

$languages = get_option('getterms-languages');
$policies = get_option('getterms-policies');
$default_language = get_option('getterms-default-language');

$default_language_name = __('Unknown Language', 'getterms-cookie-consent-and-policies');
switch ($default_language) {
	case 'hi-in':
		$default_language_name = __('Hindi', 'getterms-cookie-consent-and-policies');
		break;
	case 'en-us':
		$default_language_name = __('English (US)', 'getterms-cookie-consent-and-policies');
		break;
	case 'en-au':
		$default_language_name = __('English (UK)', 'getterms-cookie-consent-and-policies');
		break;
	case 'es':
		$default_language_name = __('Spanish', 'getterms-cookie-consent-and-policies');
		break;
	case 'de':
		$default_language_name = __('German', 'getterms-cookie-consent-and-policies');
		break;
	case 'fr':
		$default_language_name = __('French', 'getterms-cookie-consent-and-policies');
		break;
	case 'it':
		$default_language_name = __('Italian', 'getterms-cookie-consent-and-policies');
		break;
}

?>
<div class="wrap">
    <h1><?php esc_html_e('GetTerms Settings', 'getterms-cookie-consent-and-policies'); ?></h1>
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
                <strong><?php esc_html_e('GetTerms notice:', 'getterms-cookie-consent-and-policies'); ?></strong> <?php
                /* translators: %s: Plugin name with link to WordPress.org plugin page */
                printf(esc_html__('The %s plugin is required for full compatibility with Google Consent Mode.', 'getterms-cookie-consent-and-policies'), '<a href="https://wordpress.org/plugins/wp-consent-api/" target="_blank">WP Consent API</a>'); ?>
            </p>
            <form method="post" style="display:inline">
                <?php
                wp_nonce_field( 'gt_install_consent_api', '_gt_consent_nonce' );
                ?>
                <input type="hidden" name="gt_install_consent_api" value="1">
                <input type="submit" class="button button-primary"
                       value="Install & Activate WP Consent API">
            </form>
        </div>
    <?php endif; ?>
    <form method="post" action="options.php" id="getterms-form">
		<?php settings_fields('getterms-settings'); ?>
		<?php do_settings_sections('getterms-settings'); ?>
        <div class="form-row" id="getterms-token-input">
            <div class="form-label">
                <label for="getterms_token"><?php esc_html_e('GetTerms Token:', 'getterms-cookie-consent-and-policies'); ?></label>
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
			echo '<p>' . esc_html__('No policies have been set up yet.', 'getterms-cookie-consent-and-policies') . '</p>';
		}
		?>
		<?php if (!empty($widget_slug)) : ?>
            <hr style='margin-top:1rem'>
            <div id="getterms-widget-content">
                <div style="padding-bottom: 10px;">
                    <h2><?php esc_html_e('Cookie Consent Widget Management', 'getterms-cookie-consent-and-policies'); ?></h2>
                    <div class="toggle-group">
                        <label class="switch">
                            <input type="checkbox"
                                   id="getterms-auto-enable-widget-toggle"
                                   name="getterms_auto_enable" <?php checked($auto_widget, 'true'); ?> />
                            <span class="slider round"></span>
                        </label>
                        <p>
                            <?php 
                            /* translators: %s: Default language name (e.g., English (US), Spanish, etc.) */
                            printf(esc_html__('Automatically Embed in %s', 'getterms-cookie-consent-and-policies'), esc_html($default_language_name)); ?>
                        </p>
                    </div>
                    <div class="toggle-group">
                        <label class="switch">
                            <input type="checkbox"
                                   id="getterms-manual-enable-widget-toggle"
                                   name="getterms_manual_enable" <?php checked($manual_widget, 'true'); ?> />
                            <span class="slider round"></span>
                        </label>
                        <p>
                            <?php esc_html_e('Embed Widget Manually (Supports multilingual options)', 'getterms-cookie-consent-and-policies'); ?>
                        </p>
                    </div>
                    <div class="toggle-group">
                        <label class="switch">
                            <input type="checkbox"
                                   id="getterms-auto-language-detection-toggle"
                                   name="getterms_auto_language_detection" <?php checked($auto_language_detection, 'true'); ?> />
                            <span class="slider round"></span>
                        </label>
                        <p>
                            <?php esc_html_e('Enable auto language detection', 'getterms-cookie-consent-and-policies'); ?>
                            <span class="description" style="display: block; font-style: italic; color: #666; margin-top: 5px;">
                                <?php esc_html_e('This implementation will attempt to match the visitor\'s Accept-Language settings in their OS/Browser to one of our available languages. The selected embed language will be used as the default fallback.', 'getterms-cookie-consent-and-policies'); ?>
                            </span>
                        </p>
                    </div>
                </div>

                <div id="getterms-widget-settings" style="display: <?php echo $manual_widget === 'true' ? 'block' : 'none' ?>">
					<?php include('gt-widgets.php')?>
                </div>
                <div>
                    <a id="getterms-cookie-link"
                       target="_blank"
                       href="https://app.getterms.io/cookie-consent/<?php echo esc_url_raw($widget_slug); ?>/dashboard/appearance">
                        <?php esc_html_e('Update your Cookie Consent Widget style, layout, language, and content', 'getterms-cookie-consent-and-policies'); ?>
                    </a>
                </div>
            </div>
		<?php endif; ?>
    </div>
</div>
