<?php
/*
Plugin Name: Customer Appointments Dashboard
Description: Adds a dashboard menu for subscribers to view and delete their appointments from Easy Appointments.
Version: 1.0
Author: Jasmine
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Render the appointments table
function render_my_customer_appointments()
{
    if (!is_user_logged_in()) {
        echo '<div class="wrap"><h1>Access Denied</h1><p>Please log in to view your appointments.</p></div>';
        return;
    }

    global $wpdb;
    $current_user = wp_get_current_user();
    $email = $current_user->user_email;

    // Get appointment IDs owned by current user
    $appointment_ids = $wpdb->get_col($wpdb->prepare(
        "SELECT app_id FROM {$wpdb->prefix}ea_fields WHERE field_id = 1 AND value = %s",
        $email
    ));

    if (empty($appointment_ids)) {
        echo '<div class="wrap"><h1>My Appointments</h1><p>No appointments found.</p></div>';
        return;
    }

    // Fetch appointments
    $placeholders = implode(',', array_fill(0, count($appointment_ids), '%d'));
    $query = "SELECT * FROM {$wpdb->prefix}ea_appointments WHERE id IN ($placeholders) ORDER BY start DESC";
    $prepared_query = $wpdb->prepare($query, ...$appointment_ids);
    $appointments = $wpdb->get_results($prepared_query);

    echo '<div class="wrap"><h1>My Appointments</h1>';
    echo '<table class="widefat fixed striped">';
    echo '<thead><tr>
        <th>ID</th>
        <th>Service</th>
        <th>Date</th>
        <th>Time</th>
        <th>Status</th>
        <th>Payment</th>
        <th>Description</th>
        <th>Phone</th>
        <th>Name</th>
        <th>Email</th>
        <th>Action</th>
    </tr></thead><tbody>';

    foreach ($appointments as $appt) {
        $fields = $wpdb->get_results($wpdb->prepare(
            "SELECT field_id, value FROM {$wpdb->prefix}ea_fields WHERE app_id = %d",
            $appt->id
        ), OBJECT_K);

        $service_name   = $fields[5]->value ?? '';
        $description    = $fields[4]->value ?? '';
        $phone          = $fields[3]->value ?? '';
        $name           = $fields[2]->value ?? '';
        $email_field    = $fields[1]->value ?? '';

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
        echo '<td>' . esc_html($name) . '</td>';
        echo '<td>' . esc_html($email_field) . '</td>';
        echo '<td>
            <form method="post">
                <input type="hidden" name="delete_appointment_id" value="' . esc_attr($appt->id) . '">
                <input type="submit" name="delete_appointment" value="Delete" onclick="return confirm(\'Are you sure?\');">
            </form>
        </td>';
        echo '</tr>';
    }

    echo '</tbody></table></div>';
}

// Add menu for subscribers
function my_custom_appointments_menu()
{
    if (current_user_can('subscriber')) {
        add_menu_page(
            'My Appointments',
            'My Appointments',
            'read',
            'my-customer-appointments',
            'render_my_customer_appointments',
            'dashicons-calendar-alt',
            6
        );
    }
}
add_action('admin_menu', 'my_custom_appointments_menu');

// Handle appointment deletion
function handle_customer_appointment_delete()
{
    if (
        isset($_POST['delete_appointment']) &&
        isset($_POST['delete_appointment_id']) &&
        is_user_logged_in()
    ) {
        global $wpdb;
        $appointment_id = intval($_POST['delete_appointment_id']);
        $email = wp_get_current_user()->user_email;

        $app_owner_check = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->prefix}ea_fields WHERE app_id = %d AND field_id = 1 AND value = %s",
            $appointment_id,
            $email
        ));

        if ($app_owner_check) {
            $wpdb->delete("{$wpdb->prefix}ea_fields", ['app_id' => $appointment_id]);
            $wpdb->delete("{$wpdb->prefix}ea_appointments", ['id' => $appointment_id]);

            add_action('admin_notices', function () {
                echo '<div class="notice notice-success"><p>Appointment deleted successfully.</p></div>';
            });
        } else {
            add_action('admin_notices', function () {
                echo '<div class="notice notice-error"><p>You are not allowed to delete this appointment.</p></div>';
            });
        }
    }
}
add_action('admin_init', 'handle_customer_appointment_delete');
