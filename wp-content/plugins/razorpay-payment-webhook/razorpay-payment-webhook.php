<?php
/*
Plugin Name: Razorpay Payment Webhook for Easy Appointments
Description: Handles Razorpay payment webhook and updates appointment payment status.
Version: 1.0
Author: Jasmine
*/

if (!defined('ABSPATH')) exit; // Exit if accessed directly

add_action('rest_api_init', function () {
    register_rest_route('razorpay/v1', '/payment-success', [
        'methods' => 'POST',
        'callback' => 'rpay_handle_payment_webhook',
        'permission_callback' => '__return_true',
    ]);
});

function rpay_handle_payment_webhook(WP_REST_Request $request)
{
    global $wpdb;

    $data = $request->get_json_params();

    // Extract appointment_id from notes
    $appointment_id = intval($data['payload']['payment']['entity']['notes']['appointment_id'] ?? 0);

    if ($appointment_id === 0) {
        return new WP_REST_Response(['success' => false, 'error' => 'Missing appointment_id'], 400);
    }

    // Update payment status in wp_ea_appointments
    $updated = $wpdb->update(
        $wpdb->prefix . 'ea_appointments',
        ['payment_status' => 'paid'],
        ['id' => $appointment_id],
        ['%s'],
        ['%d']
    );

    if ($updated !== false) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();

        return new WP_REST_Response(['success' => true, 'appointment_id' => $appointment_id], 200);
    } else {
        return new WP_REST_Response(['success' => false, 'error' => 'Failed to update DB'], 500);
    }
}
