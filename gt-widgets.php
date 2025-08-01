<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$languages = $languages ?? [];
$policies = $policies ?? [];
$default_language = $default_language ?? 'en-us';
$widget_slug = $widget_slug ?? null;
$widget_language = get_option('getterms-widget-language');
$auto_language_detection = get_option('getterms-auto-language-detection');

echo '<h3>' . esc_html__('Manual Installation.', 'getterms-cookie-consent-and-policies') . '</h3>';
echo '<p>' . esc_html__('To install manually, copy the entire code snippet for your selected language into the &lt;head&gt; section of your page.', 'getterms-cookie-consent-and-policies') . '</p>';
echo '<h4><strong>' . esc_html__('IMPORTANT:', 'getterms-cookie-consent-and-policies') . '</strong> ' . esc_html__('The code must be the first &lt;script&gt; tags on the page.', 'getterms-cookie-consent-and-policies') . '</h4>';
echo '<p>' . esc_html__('You can optionally select the "embed" option for a specific language. The widget will be automatically added to your site  &lt;head&gt;.', 'getterms-cookie-consent-and-policies') . '</p>';
echo '<table class="code-table">';
echo '<thead>';
echo '<tr>';
echo '<th class="lang-column">' . esc_html__('Language', 'getterms-cookie-consent-and-policies') . '</th>';
echo '<th class="code-column">' . esc_html__('Code', 'getterms-cookie-consent-and-policies') . '</th>';
echo '<th class="copy-column">' . esc_html__('Copy', 'getterms-cookie-consent-and-policies') . '</th>';
echo '<th class="embed-column">' . esc_html__('Embed', 'getterms-cookie-consent-and-policies') . '</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';

foreach ($languages as $lang_key => $lang_name) {
	// Create display-only code snippet template for users to copy.
	// This HTML/JavaScript is not executed by WordPress - it's shown as escaped text.
	$script_url = 'https://gettermscmp.com/cookie-consent/embed/' . $widget_slug . '/' . $lang_key;

	// Add auto language detection parameter if enabled
	if ($auto_language_detection === 'true') {
		$script_url .= '?auto=true';
	}

	$code = '&lt;script type="text/javascript" src="' . $script_url . '"&gt;&lt;/script&gt;';

	$checked = ($lang_key === $widget_language) ? 'checked' : '';

	echo '<tr>';
	echo '<td>' . esc_html($lang_name) . '</td>';
	echo '<td>
        <div class="code-container">
            <button type="button" class="show-code-btn" data-lang-key="' . esc_attr($lang_key) . '">' . esc_html__('Show Code', 'getterms-cookie-consent-and-policies') . '</button>
            <code id="code-inner-widget-embed-' . esc_attr($lang_key) . '" class="code-snippet" style="display:none">' . esc_html($code) . '</code>
        </div>
    </td>';
	echo '<td>
        <button type="button" class="code-block__copy btn--border btn--icon btn--border-secondary" data-copy="#code-inner-widget-embed-' . esc_attr($lang_key) . '">
            <span class="inner">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" role="img" aria-label="' . esc_attr__('Clipboard', 'getterms-cookie-consent-and-policies') . '">
                    <title>' . esc_html__('Clipboard', 'getterms-cookie-consent-and-policies') . '</title>
                    <path fill="none" stroke="#065af9" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" d="M8.2 20.2h-6c-.8 0-1.5-.7-1.5-1.5v-15c0-.8.7-1.5 1.5-1.5h3m7.6 0h3c-.8 0 1.5.7 1.5 1.5v4.5"></path>
                    <path fill="none" stroke="#065af9" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" d="M8.2 17.2H3.8v-12h10.4v3"></path>
                    <path fill="none" stroke="#065af9" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" d="M12.8 11.2h9c.8 0 1.5.7 1.5 1.5v9c0 .8-.7 1.5-1.5 1.5h-9c-.8 0-1.5-.7-1.5-1.5v-9c-.1-.8.6-1.5 1.5-1.5zM14.2 14.2h6M14.2 17.2h6M14.2 20.2h2.3M12.8 5.2H5.2v-3c0-.8.7-1.5 1.5-1.5h4.5c.8 0 1.5.7 1.5 1.5v3z"></path>
                </svg>
                ' . esc_html__('Copy', 'getterms-cookie-consent-and-policies') . '
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
