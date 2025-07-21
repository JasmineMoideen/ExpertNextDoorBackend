<?php
/*
Plugin Name: Easy Appointments Asset Loader
Description: Load Easy Appointments assets only on the appointment booking page template.
Version: 1.0
Author: Jasmine
*/

function load_easy_appointments_assets_manually()
{
    if (is_page_template('template-appointment-booking.php')) {
        wp_enqueue_style('easy-appointments-bootstrap', plugins_url('/assets/css/bootstrap.min.css', __FILE__));
        wp_enqueue_script('easy-appointments-bootstrap', plugins_url('/assets/js/bootstrap.min.js', __FILE__), array('jquery'), null, true);
        wp_enqueue_script('easy-appointments-main', plugins_url('/assets/js/main.js', __FILE__), array('jquery'), null, true);
    }
}
add_action('wp_enqueue_scripts', 'load_easy_appointments_assets_manually');
