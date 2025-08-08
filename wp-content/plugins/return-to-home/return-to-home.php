<?php
/*
Plugin Name: Return to Home
Description: Adds an admin menu item to return to the React app home page.
Version: 1.1
Author: Your Name
*/

add_action('admin_menu', function() {
    add_menu_page(
        'Return to Home',         // Page title
        'Return to Home',         // Menu title
        'read',                   // Capability
        'return-to-home',         // Menu slug
        '__return_null',          // Temporary callback
        'dashicons-admin-home',   // Icon
        1                         // Position
    );
});

add_action('admin_init', function() {
    if (isset($_GET['page']) && $_GET['page'] === 'return-to-home') {
        wp_redirect('https://expert-next-door.vercel.app/');
        exit;
    }
});


add_action('admin_bar_menu', function($wp_admin_bar) {
    if (!current_user_can('administrator')) {
        $wp_admin_bar->remove_node('view-site'); // Removes "Visit Site" link
    }
}, 999);


add_action('admin_bar_menu', function($admin_bar) {
    if (is_user_logged_in()) {
        $admin_bar->add_menu([
            'id'    => 'return-to-home',
            'title' => 'Return to Home',
            'href'  => 'https://expert-next-door.vercel.app/',
            'meta'  => ['target' => '_blank']
        ]);
    }
}, 100);
