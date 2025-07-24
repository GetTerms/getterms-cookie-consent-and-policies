<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$languages = isset($languages) ? $languages : [];
$policies = isset($policies) ? $policies : [];
$default_language = isset($default_language) ? $default_language : 'en-us';

echo '<hr style="margin-top:1rem">';
echo '<h2>Embed your policies</h2>';
echo '<h4>Use these shortcodes on your pages to display your policy content</h4>';

echo '<select id="language-selector">';
echo '<option value="">Select a Language</option>';
foreach ($languages as $lang_key => $lang_name) {
 $selected = ($lang_key == $default_language) ? ' selected' : '';
	echo '<option value="' . esc_attr($lang_key) . '"' . esc_attr($selected) . '>' . esc_html($lang_name) . '</option>';
}
echo '</select>';

echo '<table class="getterms-table">';

echo '<tr>';
echo '<th>Policy</th>';
foreach ($languages as $lang_key => $lang_name) {
 echo '<th data-lang="' . esc_attr($lang_key) . '">' . esc_html($lang_name) . '</th>';
}
echo '</tr>';

foreach ($policies as $policy) {
 echo '<tr>';
	echo '<td>' . esc_html(ucfirst($policy)) . '</td>';
 foreach ($languages as $lang_key => $lang_name) {
  $shortcode_tag = 'getterms_' . $policy . '_' . $lang_key;
  echo '<td data-lang="' . esc_attr($lang_key) . '"><button class="copy-button" data-shortcode="[' . esc_attr($shortcode_tag) . ']">[' . esc_html($shortcode_tag) . ']</button></td>';
 }
 echo '</tr>';
}
echo '</table>';
