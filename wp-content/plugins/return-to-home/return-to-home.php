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
