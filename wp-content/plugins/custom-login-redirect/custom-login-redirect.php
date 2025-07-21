<?php
/*
Plugin Name: Custom Login Redirect
Description: Redirect all users to the home page after login.
Version: 1.0
Author: Jasmine
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function custom_login_redirect($redirect_to, $request, $user)
{
    if (isset($user->roles) && is_array($user->roles)) {
        return home_url(); // Redirect to homepage
    }
    return $redirect_to;
}
add_filter('login_redirect', 'custom_login_redirect', 10, 3);
