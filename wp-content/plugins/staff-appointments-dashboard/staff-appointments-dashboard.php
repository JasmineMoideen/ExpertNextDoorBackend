<?php
/*
Plugin Name: Staff Appointments Dashboard
Description: Allows users with the "Staff" role to view their assigned appointments from Easy Appointments.
Version: 1.0
Author: Jasmine
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Create "staff" user role
function add_staff_user_role()
{
    if (!get_role('staff')) {
        add_role(
            'staff',
            'Staff',
            [
                'read' => true,
            ]
        );
    }
}
add_action('init', 'add_staff_user_role');

// Render staff appointments dashboard
function render_my_staff_appointments()
{
    $current_user = wp_get_current_user();

    if (!in_array('staff', (array) $current_user->roles)) {
        echo '<div class="wrap"><h1>Access Denied</h1><p>You do not have permission to view this page.</p></div>';
        return;
    }

    $email = $current_user->user_email;
    global $wpdb;

    // Get staff ID
    $staff_id = $wpdb->get_var($wpdb->prepare(
        "SELECT id FROM {$wpdb->prefix}ea_staff WHERE email = %s",
        $email
    ));

    if (!$staff_id) {
        echo '<div class="wrap"><h1>My Appointments</h1><p>No staff record found for your email.</p></div>';
        return;
    }

    // Get appointments
    $appointments = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}ea_appointments WHERE worker = %d ORDER BY start DESC",
        $staff_id
    ));

    if (empty($appointments)) {
        echo '<div class="wrap"><h1>My Appointments</h1><p>No appointments found.</p></div>';
        return;
    }

    echo '<div class="wrap"><h2>Staff Appointments Dashboard</h2><h1>My Appointments</h1>';
    echo '<table class="widefat fixed striped">';
    echo '<thead><tr>
        <th>ID</th>
        <th>Service</th>
        <th>Date</th>
        <th>Time</th>
        <th>Status</th>
        <th>Payment Status</th>
        <th>Description</th>
        <th>Phone</th>
        <th>Name</th>
        <th>Email</th>
    </tr></thead><tbody>';

    foreach ($appointments as $appt) {
        $fields = $wpdb->get_results($wpdb->prepare(
            "SELECT field_id, value FROM {$wpdb->prefix}ea_fields WHERE app_id = %d",
            $appt->id
        ), OBJECT_K);

        $service_name    = isset($fields[5]) ? $fields[5]->value : '';
        $description     = isset($fields[4]) ? $fields[4]->value : '';
        $phone           = isset($fields[3]) ? $fields[3]->value : '';
        $customer_name   = isset($fields[2]) ? $fields[2]->value : '';
        $customer_email  = isset($fields[1]) ? $fields[1]->value : '';

        $start_date = date('F j, Y', strtotime($appt->start));
        $start_time = date('g:i a', strtotime($appt->start));
        $end_time   = date('g:i a', strtotime($appt->end));

        echo '<tr>';
        echo '<td>' . esc_html($appt->id) . '</td>';
        echo '<td>' . esc_html($service_name) . '</td>';
        echo '<td>' . esc_html($start_date) . '</td>';
        echo '<td>' . esc_html("$start_time – $end_time") . '</td>';
        echo '<td>' . esc_html($appt->status) . '</td>';
        echo '<td>' . esc_html($appt->payment_status) . '</td>';
        echo '<td>' . esc_html($description) . '</td>';
        echo '<td>' . esc_html($phone) . '</td>';
        echo '<td>' . esc_html($customer_name) . '</td>';
        echo '<td>' . esc_html($customer_email) . '</td>';
        echo '</tr>';
    }

    echo '</tbody></table></div>';
}

// Add admin menu for staff
function add_staff_appointments_menu()
{
    if (current_user_can('read') && in_array('staff', (array) wp_get_current_user()->roles)) {
        add_menu_page(
            'My Appointments',
            'My Appointments',
            'read',
            'staff-appointments',
            'render_my_staff_appointments',
            'dashicons-calendar-alt',
            20
        );
    }
}
add_action('admin_menu', 'add_staff_appointments_menu');
