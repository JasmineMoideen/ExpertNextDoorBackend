<?php
/*
Plugin Name: Redirect Old Domain to Vercel
Description: Redirects all front-end requests from expertnext.demoserver.work to expert-next-door.vercel.app while keeping admin area accessible.
Version: 1.0
Author: Jasmine
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

add_action('template_redirect', function() {
    if (!is_admin()) {
        $old_domain = 'expertnext.demoserver.work';
        $new_domain = 'https://expert-next-door.vercel.app';
        
        $current_host = $_SERVER['HTTP_HOST'];

        // Only redirect if the request is to the old domain
        if ($current_host === $old_domain) {
            $redirect_url = rtrim($new_domain, '/') . $_SERVER['REQUEST_URI'];
            wp_redirect($redirect_url, 301);
            exit;
        }
    }
});
