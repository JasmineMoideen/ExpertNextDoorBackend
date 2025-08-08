<?php
/*
Plugin Name: Return to Home
Description: Adds an admin menu item to return to the React app home page.
Version: 1.1
Author: Your Name
*/



add_filter('show_admin_bar', '__return_true');




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

add_action('admin_bar_menu', function($wp_admin_bar) {
    if (!current_user_can('administrator')) {
        $wp_admin_bar->remove_node('view-site'); // Removes "Visit Site" link
    }
}, 999);
