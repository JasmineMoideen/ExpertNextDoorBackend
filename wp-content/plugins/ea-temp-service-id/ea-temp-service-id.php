<?php
/*
Plugin Name: EA Temp Service ID Tracker
Description: Temporarily stores the Easy Appointments service ID from the URL into user meta during booking.
Version: 1.0
Author: Jasmine
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Store the service ID from the URL to user meta
add_action('init', function () {
    if (is_user_logged_in() && isset($_GET['service_id'])) {
        update_user_meta(get_current_user_id(), '_ea_temp_service_id', intval($_GET['service_id']));
    }
});
