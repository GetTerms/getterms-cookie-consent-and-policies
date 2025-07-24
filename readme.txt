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

The GetTerms plugin helps you implement cookie consent management and embed legal policies directly into your WordPress site. It connects with your GetTerms account to provide compliant, auto-updating documents and a fully configurable cookie consent banner.

== Features ==

- Connect to your GetTerms Compliance Pack using a unique token.
- Embed the Cookie Consent Widget site-wide or via manual integration.
- Shortcodes to display live-updated legal documents (Privacy Policy, Terms, AUP, etc.).
- Support for multiple languages for both widget and policies.

== Installation ==

1. Go to your WordPress admin dashboard.
2. Navigate to `Plugins > Add New`.
3. Search for `GetTerms Cookie Consent and Policies`.
4. Click `Install Now` and then `Activate`.

== Usage ==

=== Setting Up ===
1. Navigate to `GetTerms` in the WordPress admin menu.
2. Enter your GetTerms token and save your settings.

=== Embedding the Cookie Consent Widget ===
- Enable the auto-embed option in plugin settings to insert the consent widget in the `<head>` tag.
- You can also embed manually with the provided script tag for advanced use cases or multilingual configurations.
- To ensure proper consent enforcement, embed the script as early as possible in the head section.

=== Using Shortcodes ===
Use the following shortcodes to display your policy documents:

- `[getterms_privacy_en]`, `[getterms_terms_en]`, `[getterms_aup_en]`
- `[getterms_privacy_fr]`, `[getterms_terms_fr]`, `[getterms_aup_fr]`
- Additional languages and policies are listed in the plugin settings.

== Data Collection and Privacy ==

The plugin communicates with the GetTerms service (`https://app.getterms.io`) to deliver policy content and manage cookie consent. The following data is transmitted:

- **Authentication Token**: Used to authenticate and fetch Compliance Pack data.
- **Domain Name**: Validates the plugin’s authorization and retrieves domain-specific settings.
- **User Consent Logs**: Records of accepted/rejected cookie categories (anonymized).
- **Policy Requests**: Dynamic retrieval of policy content for embedding.
- **Error Logs**: Technical error messages (non-personal) may be sent to assist with debugging.

All data is anonymized where applicable and stored in compliance with GDPR data sovereignty standards.

== External Services ==

This plugin connects to the following service:

=== GetTerms (https://app.getterms.io) ===

Provides:
- Consent management functionality via an embeddable script
- Dynamic policy hosting and retrieval
- Consent logging and language-specific configurations

Domains used:
- `app.getterms.io`
- `gettermscdn.com`
- `gettermscmp.com`

These scripts are required to render the banner, block scripts where applicable, and interface with tools like Google Consent Mode.

The plugin does **not** load updates, interfaces, or add-ons from external servers — only documented and expected scripts are embedded, in compliance with WordPress.org guidelines. Admins retain full control over embedding behavior and configuration.

== Development ==

=== Build Tools ===

This plugin uses modern build tooling for asset management:

- **Vite** compiles source JavaScript files from `src/` to `dist/`.
- **NPM** manages dependencies and scripts.
- Output is compiled as an IIFE for safe WordPress execution.

Build config: `vite.config.mjs`

=== Source Code ===

GitHub repository: https://github.com/GetTerms/getterms-wpplugin

== Changelog ==

= 0.8 =
* Integration with WP Consent API.
* Require WP Consent API v1.0 or later.

= 0.7 =
* Added `readme.txt`.
* Moved changelog into `readme.txt`.

= 0.6 =
* Improved authentication method.
* Enhanced error logging.

= 0.5 =
* Initial beta release.

== Support ==

For help, email [support@getterms.io](mailto:support@getterms.io)

== License ==

This plugin is licensed under the [GPLv2 or later](https://www.gnu.org/licenses/gpl-2.0.html).