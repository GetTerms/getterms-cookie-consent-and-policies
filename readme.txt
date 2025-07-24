=== GetTerms Cookie Consent and Policies ===
Contributors: generallabs
Tags: privacy, terms of service, cookie consent, GDPR, compliance
Requires at least: 4.7
Tested up to: 6.8
Stable tag: 0.8
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

The GetTerms plugin allows users to manage their GetTerms compliance packs, embed auto-updating policies, and display their Cookie Consent Widget.

== Description ==

The GetTerms plugin allows users to manage their GetTerms compliance packs, embed auto-updating policies, and display their Cookie Consent Widget.

== Features ==

- Easy installation and management of GetTerms compliance pack.
- Embed Cookie Consent Widget with a domain-specific token.
- Shortcodes for embedding automatically updating website policies.
- Supports multiple languages for policy documents and cookie consent widget.

== Installation ==

1. Go to the WordPress admin dashboard.
2. Navigate to `Plugins > Add New`.
3. Search for `GetTerms Cookie Consent and Policies Plugin`.
4. Click `Install Now` and then `Activate` the plugin.

== Usage ==

=== Setting Up ===

1. Navigate to `GetTerms` in the WordPress admin dashboard, or access `GetTerms` settings from the `Plugins` menu.
2. Enter your GetTerms token in the provided field and save the settings.

=== Embedding the Cookie Consent Widget ===

You can choose to automatically embed the Cookie Consent Widget on your website by enabling the option in the settings. This will embed the widget in your selected default language.

You can optionally choose to embed the widget in other available languages automatically, or manually via script that the plugin provides, giving you greater control over the widget's appearance and behavior.

The widget will be displayed on all pages of your website automatically, or on any pages or templates where manually embedded in the head.

For best results and to ensure auto-blocking features when manually installing, ensure the widget is the first script embedded in the head of your website.

=== Using Shortcodes ===

The plugin provides shortcodes to embed the policy documents you have enabled in your GetTerms account for this Compliance Pack. Examples of shortcodes are as follows:

- `[getterms_privacy_en]` - Embed the Privacy Policy in English.
- `[getterms_terms_en]` - Embed the Terms of Service in English.
- `[getterms_aup_en]` - Embed the Acceptable Use Policy in English.
- `[getterms_privacy_fr]` - Embed the Privacy Policy in French.
- `[getterms_terms_fr]` - Embed the Terms of Service in French.
- `[getterms_aup_fr]` - Embed the Acceptable Use Policy in French.
- ... and other language options as available inside the GetTerms settings.

== Data Collection and Privacy ==

The GetTerms Cookie Consent and Policies plugin collects and sends the following data to the GetTerms server:

- **Token String**: A token string supplied by GetTerms is used to authenticate the plugin.
- **Domain Name**: The domain name of the site where the plugin is installed.
- **User Cookie Consent Logs**: Records of what cookies visitors to the site accept or reject.

Additionally, error logs for the widget and policy embed functions may be sent to GetTerms to assist in providing support and improvements for the plugin. This data helps us ensure the plugin functions correctly and allows us to offer better support and enhancements.

All policy embed and consent log data sent to GetTerms is stripped of PII prior to reaching the GetTerms server. All data stored on the GetTerms server complies with GDPR requirements for data sovereignty.

== Remote Service Usage ==

The GetTerms plugin integrates with the GetTerms Cookie Consent and Policy service platform, which serves all consent widget functionality via a remote script.

When enabled, the plugin embeds a script tag that loads the GetTerms Cookie Consent Widget from `https://app.getterms.io` or other domains within the getterms CDN, i.e. `https://gettermscmp.com` and `https://gettermscdn.com`. This script is required to render the cookie banner, manage consent, block scripts where applicable, and interface with services such as Google Consent Mode.

This remote script is a core part of the GetTerms service and cannot function without being embedded. This usage is permitted under WordPress.org guidelines, as the external code originates from a documented and expected third-party service.

No plugin updates, add-ons, or administrative interfaces are served from outside WordPress.

Administrators retain full control over whether or not the script is embedded, the manner in which it is embedded, and which language or configuration is used.

== Changelog ==

= 0.8 =
* Integration with WP Consent API for better consent management and integration with other plugins.
* Require WP Consent API version 1.0 or later.

= 0.7 =
* Created readme.txt file.
* Moved changelog into readme.txt

= 0.6 =
* Improved authentication method.
* Enhanced error logging functionality.

= 0.5 =
* Initial release of GetTerms Cookie Consent and Policies plugin beta version 0.5.

== Support ==

For support, please contact [support@getterms.io](mailto:support@getterms.io).

== License ==

This plugin is licensed under the [GPLv2 or later](https://www.gnu.org/licenses/gpl-2.0.html).