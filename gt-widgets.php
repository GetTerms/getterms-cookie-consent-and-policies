<?php

$languages = $languages ?? [];
$policies = $policies ?? [];
$default_language = $default_language ?? 'en-us';
$widget_slug = $widget_slug ?? null;
$google_consent = get_option('getterms-google-consent');
$widget_language = get_option('getterms-widget-language');

// This script is being displayed, not run. Disabling the phpcs rule that requires enqueued scripts to be registered/enqueued.
// phpcs:disable WordPress.WP.EnqueuedResources.NonEnqueuedScript
$googleConsentCode = '
<!-- 1. Initialise Google Consent Mode -->
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag() { dataLayer.push(arguments); }
  gtag("consent", "default", {
    "ad_storage": "denied",
    "ad_user_data": "denied",
    "ad_personalization": "denied",
    "analytics_storage": "denied",
    "functionality_storage": "denied",
    "personalization_storage": "denied",
    "security_storage": "denied",
  });
</script>';
// phpcs:enable WordPress.WP.EnqueuedResources.NonEnqueuedScript

echo '<h3>Manual Installation.</h3>';
echo '<p>To install manually, copy the entire code snippet for your selected language into the &lt;head&gt; section of your page.</p>';
echo '<h4><strong>IMPORTANT:</strong> The code must be the first &lt;script&gt; tags on the page.</h4>';
echo '<p>You can optionally select the "embed" option for a specific language. The widget will be automatically added to your site  &lt;head&gt;.</p>';
echo '<table class="code-table">';
echo '<thead>';
echo '<tr>';
echo '<th class="lang-column">Language</th>';
echo '<th class="code-column">Code</th>';
echo '<th class="copy-column">Copy</th>';
echo '<th class="embed-column">Embed</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';

foreach ($languages as $lang_key => $lang_name) {
	// This script is being displayed, not run. Disabling the phpcs rule that requires enqueued scripts to be registered/enqueued.
// phpcs:disable WordPress.WP.EnqueuedResources.NonEnqueuedScript
	$code = ($google_consent === 'on' ? $googleConsentCode . "\n" : '') .
		'<script type="text/javascript" src="https://app.getterms.io/cookie-consent/embed/' . $widget_slug . '/' . $lang_key . '"></script>';
// phpcs:enable WordPress.WP.EnqueuedResources.NonEnqueuedScript

	$checked = ($lang_key === $widget_language) ? 'checked' : '';

	echo '<tr>';
	echo '<td>' . esc_html($lang_name) . '</td>';
	echo '<td>
        <div class="code-container">
            <code id="code-inner-widget-embed-' . esc_attr($lang_key) . '" class="code-snippet" style="display:none">' . esc_html($code) . '</code>
            <button type="button" class="show-code-btn" data-lang-key="' . esc_attr($lang_key) . '">Show Code</button>
        </div>
    </td>';
	echo '<td>
        <button type="button" class="code-block__copy btn--border btn--icon btn--border-secondary" data-copy="#code-inner-widget-embed-' . esc_attr($lang_key) . '">
            <span class="inner">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <title>Clipboard</title>
                    <path fill="none" stroke="#065af9" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" d="M8.2 20.2h-6c-.8 0-1.5-.7-1.5-1.5v-15c0-.8.7-1.5 1.5-1.5h3m7.6 0h3c-.8 0 1.5.7 1.5 1.5v4.5"></path>
                    <path fill="none" stroke="#065af9" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" d="M8.2 17.2H3.8v-12h10.4v3"></path>
                    <path fill="none" stroke="#065af9" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" d="M12.8 11.2h9c.8 0 1.5.7 1.5 1.5v9c0 .8-.7 1.5-1.5 1.5h-9c-.8 0-1.5-.7-1.5-1.5v-9c-.1-.8.6-1.5 1.5-1.5zM14.2 14.2h6M14.2 17.2h6M14.2 20.2h2.3M12.8 5.2H5.2v-3c0-.8.7-1.5 1.5-1.5h4.5c.8 0 1.5.7 1.5 1.5v3z"></path>
                </svg>
                Copy
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
