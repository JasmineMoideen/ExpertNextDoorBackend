<?php
/*
Plugin Name: Custom Login Buttons and Styles
Description: Adds Customer and Staff registration buttons above the login form and loads custom login CSS.
Version: 1.0
Author: Jasmine
*/

if (!defined('ABSPATH')) exit;

// Add registration buttons above wp-login.php form
add_action('login_header', 'clb_add_registration_buttons');
function clb_add_registration_buttons()
{
    echo '<div style="text-align:center;margin-bottom:15px;margin-top:15px;">
        <a href="' . esc_url(site_url('/customer-registration')) . '" style="margin: 5px; padding: 8px 18px; background: #f03250; color: white; border-radius: 4px; text-decoration: none;">Customer Registration</a>
        <a href="' . esc_url(site_url('/staff-registration')) . '" style="margin: 5px; padding: 8px 18px; background: #0073aa; color: white; border-radius: 4px; text-decoration: none;">Staff Registration</a>
    </div>';
}

// Enqueue custom login CSS from theme directory
add_action('login_enqueue_scripts', 'clb_enqueue_custom_login_css');
function clb_enqueue_custom_login_css()
{
    $theme_style_path = get_stylesheet_directory_uri() . '/css/login.css';
    wp_enqueue_style('custom-login-style', $theme_style_path, [], null);
}
