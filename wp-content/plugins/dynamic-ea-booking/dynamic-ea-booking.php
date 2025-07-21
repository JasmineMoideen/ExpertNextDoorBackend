<?php
/*
Plugin Name: Dynamic EA Booking Shortcode
Description: Dynamically generates Easy Appointments booking shortcode based on user_id, service_id, and location_id from URL.
Version: 1.0
Author: Jasmine
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function dynamic_booking_shortcode()
{
    $user_id     = isset($_GET['user_id']) ? intval($_GET['user_id']) : '';
    $service_id  = isset($_GET['service_id']) ? intval($_GET['service_id']) : '';
    $location_id = isset($_GET['location_id']) ? intval($_GET['location_id']) : '';

    $shortcode = '[ea_bootstrap';

    if (!empty($location_id)) {
        $shortcode .= ' location="' . esc_attr($location_id) . '"';
    }

    if (!empty($service_id)) {
        $shortcode .= ' service="' . esc_attr($service_id) . '"';
    }

    if (!empty($user_id)) {
        $shortcode .= ' worker="' . esc_attr($user_id) . '"';
    }

    $shortcode .= ']';

    return do_shortcode($shortcode);
}
add_shortcode('dynamic_ea_booking_service', 'dynamic_booking_shortcode');
