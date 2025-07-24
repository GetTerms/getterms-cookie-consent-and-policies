import tippy from 'tippy.js';
import 'tippy.js/dist/tippy.css';

jQuery(document).ready(function ($) {

    const pluginVersion = '0.8';
    const currentDomain = window.location.hostname;

    function initializeCopyButtons(buttons) {
        buttons.forEach((button) => {
            tippy(button, {
                content: 'Copied!',
                trigger: 'click',
                onShow(instance) {
                    setTimeout(() => {
                        instance.hide();
                    }, 2000);
                },
            });

            button.addEventListener('click', function () {
                const target = document.querySelector(button.getAttribute('data-copy'));
                const textarea = document.createElement('textarea');
                textarea.value = target ? target.textContent : button.getAttribute('data-shortcode');
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
            });
        });
    }

    const copyButtons = document.querySelectorAll('.copy-button, .code-block__copy');
    if (copyButtons) {
        initializeCopyButtons(copyButtons);
    }

    $('#getterms-form').submit(function (e) {
        e.preventDefault();
        updateToken();
    });

    function updateToken() {
        $('#getterms-error-message').hide();

        const token = $('[name="getterms_token"]').val();
        if (!token) {
            $('#getterms-error-message').text('Token is required.').show();
            return;
        }

        const domain = 'https://' + window.location.hostname;
        const encryptedDomain = window.btoa(domain);

        const apiUrl = `https://app.getterms.io/api/wp-plugin/v1/${token}?dm=${encryptedDomain}`;

        $.ajax({
            url: getTermsAjax.ajax_url,
            method: 'POST',
            data: {
                action: 'clear_getterms_options',
                nonce: getTermsAjax.nonce,
            },
            success: function () {
                $.ajax({
                    url: apiUrl,
                    type: 'GET',
                    headers: {
                        'X-Site-Domain': window.location.hostname
                    },
                    success: function (fetchResponse) {
                        $.ajax({
                            url: getTermsAjax.ajax_url,
                            method: 'POST',
                            data: {
                                action: 'set_getterms_options',
                                nonce: getTermsAjax.nonce,
                                options_data: {
                                    'getterms-token': fetchResponse.token,
                                    'getterms-google-consent': fetchResponse.google_consent,
                                    'getterms-widget-slug': fetchResponse.widget_slug,
                                    'getterms-languages': fetchResponse.available_languages,
                                    'getterms-policies': fetchResponse.available_policies,
                                    'getterms-default-language': fetchResponse.default_language,
                                },
                            },
                            success: function () {
                                window.location.reload();
                            },
                            error: function (xhr, status, error) {
                                $('#getterms-error-message').text(`Error setting options: ${error}`).show();
                                $('#getterms-content').hide();
                                logErrorToServer({
                                    message: `Error setting options: ${error}`,
                                    source: 'updateToken > set_getterms_options',
                                    token: token,
                                    pluginVersion: pluginVersion,
                                    error: error,
                                    domain: currentDomain
                                });
                            },
                        });
                    },
                    error: function (xhr, status, error) {
                        const errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'An unexpected error occurred.';
                        $('#getterms-error-message').text(errorMessage).show();
                        $('#getterms-content').hide();
                        logErrorToServer({
                            message: `Error fetching token: ${error}`,
                            source: 'updateToken > ' + apiUrl,
                            token: token,
                            pluginVersion: pluginVersion,
                            error: error,
                            domain: currentDomain
                        });
                    },
                });
            },
            error: function (xhr, status, error) {
                $('#getterms-error-message').text(`Error clearing options: ${error}`).show();
                $('#getterms-content').hide();
                logErrorToServer({
                    message: `Error clearing options: ${error}`,
                    source: 'updateToken > clear_getterms_options',
                    token: token,
                    pluginVersion: pluginVersion,
                    error: error,
                    domain: currentDomain
                });
            },
        });
    }

    function fetchGetTermsOptions(callback) {
        $.ajax({
            url: getTermsAjax.ajax_url,
            method: 'POST',
            data: {
                action: 'get_getterms_options',
                nonce: getTermsAjax.nonce,
            },
            success: function (response) {
                if (response.success) {
                    const options = response.data;
                    callback(null, options);
                } else {
                    console.error('Failed to fetch options:', response.data);
                    callback('Failed to fetch options');
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX error:', error);
                callback('AJAX error');
                logErrorToServer({
                    message: `Error fetching options: ${error}`,
                    source: 'fetchGetTermsOptions > get_getterms_options',
                    token: $('[name="getterms_token"]').val() ?? "unknown",
                    pluginVersion: pluginVersion,
                    error: error,
                    domain: currentDomain
                });
            },
        });
    }

    function checkForLanguageUpdate() {
        const token = $('[name="getterms_token"]').val();
        if (!token) {
            return;
        }

        fetchGetTermsOptions(function (err, options) {
            if (err) {
                console.error(err);
                return;
            }

            const current_language = options['getterms-default-language'];

            const domain = 'https://' + window.location.hostname;
            const encryptedDomain = window.btoa(domain);

            const apiUrl = `https://app.getterms.io/api/wp-plugin/v1/${token}?dm=${encryptedDomain}`;

            $.ajax({
                url: apiUrl,
                type: 'GET',
                headers: {
                    'X-Site-Domain': window.location.hostname
                },
                success: function (fetchResponse) {
                    if (current_language !== fetchResponse.default_language) {
                        updateToken();
                    }
                },
                error: function (xhr, status, error) {
                    const errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'An unexpected error occurred.';
                    $('#getterms-error-message').text(errorMessage).show();
                    $('#getterms-content').hide();
                    logErrorToServer({
                        message: `Error fetching options: ${error}`,
                        source: 'fetchGetTermsOptions > ' + apiUrl,
                        token: token,
                        pluginVersion: pluginVersion,
                        error: error,
                        domain: currentDomain
                    });
                },
            });
        });
    }

    checkForLanguageUpdate();

    const languageSelector = document.getElementById('language-selector');
    if (languageSelector) {
        languageSelector.addEventListener('change', function () {
            const selectedLang = this.value;
            const cells = document.querySelectorAll('th[data-lang], td[data-lang]');

            cells.forEach((cell) => {
                cell.style.display = selectedLang === '' || cell.dataset.lang === selectedLang ? '' : 'none';
            });
        });
        languageSelector.dispatchEvent(new Event('change'));
    }

    const autoEnableToggle = document.getElementById('getterms-auto-enable-widget-toggle');
    const manualEnableToggle = document.getElementById('getterms-manual-enable-widget-toggle');
    const widgetSettings = document.getElementById('getterms-widget-settings');

    if (autoEnableToggle && manualEnableToggle && widgetSettings) {
        autoEnableToggle.addEventListener('change', function () {
            if (autoEnableToggle.checked) {
                manualEnableToggle.checked = false;
            }
            widgetSettings.style.display = manualEnableToggle.checked ? 'block' : 'none';

            $.ajax({
                url: getTermsAjax.ajax_url,
                type: 'POST',
                data: {
                    action: 'update_getterms_auto_widget',
                    auto_widget: autoEnableToggle.checked,
                    nonce: getTermsAjax.nonce,
                },
                error: function (xhr, status, error) {
                    console.error('Error updating widget display option:', error);
                    logErrorToServer({
                        message: `Error updating widget display option: ${error}`,
                        source: 'autoEnableToggle.addEventListener',
                        token: $('[name="getterms_token"]').val() ?? "unknown",
                        pluginVersion: pluginVersion,
                        error: error,
                        domain: currentDomain
                    });
                }
            });
        });

        manualEnableToggle.addEventListener('change', function () {
            if (manualEnableToggle.checked) {
                autoEnableToggle.checked = false;
                widgetSettings.style.display = 'block';
                window.scrollTo({top: document.body.scrollHeight, behavior: 'smooth'});
            } else {
                widgetSettings.style.display = 'none';
            }
            $.ajax({
                url: getTermsAjax.ajax_url,
                type: 'POST',
                data: {
                    action: 'update_getterms_manual_widget',
                    manual_widget: manualEnableToggle.checked,
                    nonce: getTermsAjax.nonce,
                },
                error: function (xhr, status, error) {
                    console.error('Error updating widget display option:', error);
                    logErrorToServer({
                        message: `Error updating widget display option: ${error}`,
                        source: 'manualEnableToggle.addEventListener',
                        token: $('[name="getterms_token"]').val() ?? "unknown",
                        pluginVersion: pluginVersion,
                        error: error,
                        domain: currentDomain
                    });
                }
            });
        });

        const showCodeButtons = document.querySelectorAll(".show-code-btn");
        showCodeButtons.forEach(button => {
            button.addEventListener("click", function () {
                const langKey = button.getAttribute("data-lang-key");
                const codeElement = document.getElementById(`code-inner-widget-embed-${langKey}`);

                if (codeElement.style.display === "none") {
                    document.querySelectorAll(".code-snippet").forEach(code => {
                        code.style.display = "none";
                    });
                    document.querySelectorAll(".show-code-btn").forEach(btn => {
                        btn.classList.remove("active");
                        btn.textContent = "Show Code";
                    });

                    codeElement.style.display = "block";
                    button.classList.add("active");
                    button.textContent = "Hide Code";
                } else {
                    codeElement.style.display = "none";
                    button.classList.remove("active");
                    button.textContent = "Show Code";
                }
            });
        });

        const checkboxes = document.querySelectorAll('.language-toggle-checkbox');
        checkboxes.forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                const lang = this.dataset.lang;
                if (this.checked) {
                    checkboxes.forEach(function (otherCheckbox) {
                        if (otherCheckbox !== checkbox) {
                            otherCheckbox.checked = false;
                        }
                    });
                }
                if (this.checked) {
                    jQuery.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'set_widget_lang',
                            lang: lang,
                            nonce: getTermsAjax.nonce
                        },
                        error: function (xhr, status, error) {
                            console.error('AJAX error:', error);
                            logErrorToServer({
                                message: `Error setting widget language: ${error}`,
                                source: 'checkboxes.forEach > set_widget_lang',
                                token: $('[name="getterms_token"]').val() ?? "unknown",
                                pluginVersion: pluginVersion,
                                error: error,
                                domain: currentDomain
                            });
                        }
                    });
                } else {
                    jQuery.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'set_widget_lang',
                            lang: null,
                            nonce: getTermsAjax.nonce
                        },
                        error: function (xhr, status, error) {
                            console.error('AJAX error:', error);
                            logErrorToServer({
                                message: `Error setting widget language: ${error}`,
                                source: 'checkboxes.forEach > set_widget_lang > ELSE',
                                token: $('[name="getterms_token"]').val() ?? "unknown",
                                pluginVersion: pluginVersion,
                                error: error,
                                domain: currentDomain
                            });
                        }
                    });
                }
            });
        });

        function logErrorToServer(errorData) {
            $.ajax({
                url: 'https://app.getterms.io/api/wp-plugin-error',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(errorData)
            });
        }
    }

});