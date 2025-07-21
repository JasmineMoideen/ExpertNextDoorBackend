<?php
/**
 * Plugin Name: Fresh REST Nonce Replacer
 * Description: Replaces _wpnonce values dynamically in the admin using the fresh REST nonce.
 * Version: 1.0
 * Author: Jasmine
 */

add_action('admin_footer', function () {
    if (!is_admin()) return;

    $new_nonce = wp_create_nonce('wp_rest'); // Correct: 'wp_rest' not 'ea_appointment'
    ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            window.fresh_nonce = '<?php echo esc_js($new_nonce); ?>';

            const observer = new MutationObserver(() => {
                document.querySelectorAll('[name="_wpnonce"]').forEach(el => {
                    if (window.fresh_nonce && el.value !== window.fresh_nonce) {
                        el.value = window.fresh_nonce;
                        console.log("✅ _wpnonce updated to REST nonce:", window.fresh_nonce);
                    }
                });

                document.querySelectorAll('a, button, form').forEach(el => {
                    if (el.hasAttribute('onclick')) {
                        el.setAttribute(
                            'onclick',
                            el.getAttribute('onclick').replace(
                                /_wpnonce=([a-zA-Z0-9]+)/,
                                '_wpnonce=' + window.fresh_nonce
                            )
                        );
                    }
                });
            });

            observer.observe(document.body, {
                childList: true,
                subtree: true
            });

            // Also immediately run once
            setTimeout(() => observer.takeRecords(), 500);
        });
    </script>
    <?php
});
