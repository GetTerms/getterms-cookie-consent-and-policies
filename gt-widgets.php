<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$languages = $languages ?? [];
$policies = $policies ?? [];
$default_language = $default_language ?? 'en-us';
$widget_slug = $widget_slug ?? null;
$google_consent = get_option('getterms-google-consent');
$widget_language = get_option('getterms-widget-language');

/**
 * Google Consent Mode initialization template for display purposes only.
 * This code is shown as a text example to users and is not executed by WordPress.
 * It's properly escaped when displayed using esc_html().
 */
function getterms_get_google_consent_template() {
	return '<!-- 1. Initialise Google Consent Mode -->' . "\n" .
		'&lt;script&gt;' . "\n" .
		'  window.dataLayer = window.dataLayer || [];' . "\n" .
		'  function gtag() { dataLayer.push(arguments); }' . "\n" .
		'  gtag("consent", "default", {' . "\n" .
		'    "ad_storage": "denied",' . "\n" .
		'    "ad_user_data": "denied",' . "\n" .
		'    "ad_personalization": "denied",' . "\n" .
		'    "analytics_storage": "denied",' . "\n" .
		'    "functionality_storage": "denied",' . "\n" .
		'    "personalization_storage": "denied",' . "\n" .
		'    "security_storage": "denied"' . "\n" .
		'  });' . "\n" .
		'&lt;/script&gt;';
}

$googleConsentCode = getterms_get_google_consent_template();

echo '<h3>' . __('Manual Installation.', 'getterms-cookie-consent-and-policies') . '</h3>';
echo '<p>' . __('To install manually, copy the entire code snippet for your selected language into the &lt;head&gt; section of your page.', 'getterms-cookie-consent-and-policies') . '</p>';
echo '<h4><strong>' . __('IMPORTANT:', 'getterms-cookie-consent-and-policies') . '</strong> ' . __('The code must be the first &lt;script&gt; tags on the page.', 'getterms-cookie-consent-and-policies') . '</h4>';
echo '<p>' . __('You can optionally select the "embed" option for a specific language. The widget will be automatically added to your site  &lt;head&gt;.', 'getterms-cookie-consent-and-policies') . '</p>';
echo '<table class="code-table">';
echo '<thead>';
echo '<tr>';
echo '<th class="lang-column">' . __('Language', 'getterms-cookie-consent-and-policies') . '</th>';
echo '<th class="code-column">' . __('Code', 'getterms-cookie-consent-and-policies') . '</th>';
echo '<th class="copy-column">' . __('Copy', 'getterms-cookie-consent-and-policies') . '</th>';
echo '<th class="embed-column">' . __('Embed', 'getterms-cookie-consent-and-policies') . '</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';

foreach ($languages as $lang_key => $lang_name) {
	// Create display-only code snippet template for users to copy.
	// This HTML/JavaScript is not executed by WordPress - it's shown as escaped text.
	$code = ($google_consent === 'on' ? $googleConsentCode . "\n" : '') .
		'&lt;script type="text/javascript" src="https://app.getterms.io/cookie-consent/embed/' . $widget_slug . '/' . $lang_key . '"&gt;&lt;/script&gt;';

	$checked = ($lang_key === $widget_language) ? 'checked' : '';

	echo '<tr>';
	echo '<td>' . esc_html($lang_name) . '</td>';
	echo '<td>
        <div class="code-container">
            <code id="code-inner-widget-embed-' . esc_attr($lang_key) . '" class="code-snippet" style="display:none">' . esc_html($code) . '</code>
            <button type="button" class="show-code-btn" data-lang-key="' . esc_attr($lang_key) . '">' . __('Show Code', 'getterms-cookie-consent-and-policies') . '</button>
        </div>
    </td>';
	echo '<td>
        <button type="button" class="code-block__copy btn--border btn--icon btn--border-secondary" data-copy="#code-inner-widget-embed-' . esc_attr($lang_key) . '">
            <span class="inner">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" role="img" aria-label="' . esc_attr__('Clipboard', 'getterms-cookie-consent-and-policies') . '">
                    <title>' . __('Clipboard', 'getterms-cookie-consent-and-policies') . '</title>
                    <path fill="none" stroke="#065af9" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" d="M8.2 20.2h-6c-.8 0-1.5-.7-1.5-1.5v-15c0-.8.7-1.5 1.5-1.5h3m7.6 0h3c-.8 0 1.5.7 1.5 1.5v4.5"></path>
                    <path fill="none" stroke="#065af9" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" d="M8.2 17.2H3.8v-12h10.4v3"></path>
                    <path fill="none" stroke="#065af9" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" d="M12.8 11.2h9c.8 0 1.5.7 1.5 1.5v9c0 .8-.7 1.5-1.5 1.5h-9c-.8 0-1.5-.7-1.5-1.5v-9c-.1-.8.6-1.5 1.5-1.5zM14.2 14.2h6M14.2 17.2h6M14.2 20.2h2.3M12.8 5.2H5.2v-3c0-.8.7-1.5 1.5-1.5h4.5c.8 0 1.5.7 1.5 1.5v3z"></path>
                </svg>
                ' . __('Copy', 'getterms-cookie-consent-and-policies') . '
            </span>
        </button>
    </td>';
	echo '<td>
        <label class="switch">
            <input type="checkbox" class="language-toggle-checkbox" id="toggle-' . esc_attr($lang_key) . '" data-lang="' . esc_attr($lang_key) . '" ' . esc_attr($checked) . '>
            <span class="slider round"></span>
        </label>
    </td>';
	echo '</tr>';
}

echo '</tbody>';
echo '</table>';
