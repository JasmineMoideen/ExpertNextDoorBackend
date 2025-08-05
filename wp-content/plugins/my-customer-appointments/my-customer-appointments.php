<?php
/*
Plugin Name: My Customer Appointments
Description: Displays logged-in user's appointments from Easy Appointments.
Version: 1.0
Author: Jasmine
*/

if (!defined('ABSPATH')) {
    exit;
}

// 1. Add menu item for subscribers
function my_appointments_add_menu_page() {
    if (!is_user_logged_in()) return;

    $user = wp_get_current_user();

    if (in_array('subscriber', (array)$user->roles)) {
        add_menu_page(
            'My Appointments',
            'My Appointments',
            'read',
            'my-customer-appointments',
            'render_my_customer_appointments_page',
            'dashicons-calendar-alt',
            6
        );
    }
}
add_action('admin_menu', 'my_appointments_add_menu_page');

// 2. Admin page content wrapper
function render_my_customer_appointments_page() {
    echo '<div class="wrap">';
    echo do_shortcode('[my_customer_appointments]');
    echo '</div>';
}

// 3. Register shortcode
add_shortcode('my_customer_appointments', 'render_my_customer_appointments');

// 4. Main appointment rendering logic (used in shortcode and admin page)
function render_my_customer_appointments() {
    if (!is_user_logged_in()) {
        echo '<p>You must be logged in to view your appointments.</p>';
        return;
    }

    $current_user = wp_get_current_user();
    $email = $current_user->user_email;

    global $wpdb;

    $appointment_ids = $wpdb->get_col($wpdb->prepare(
        "SELECT app_id FROM {$wpdb->prefix}ea_fields WHERE field_id = 1 AND value = %s",
        $email
    ));

    if (empty($appointment_ids)) {
        echo '<div class="wrap"><h1>My Appointments</h1><p>No appointments found.</p></div>';
        return;
    }

    $placeholders = implode(',', array_fill(0, count($appointment_ids), '%d'));
    $query = "SELECT * FROM {$wpdb->prefix}ea_appointments WHERE id IN ($placeholders) ORDER BY start DESC";
    $prepared_query = $wpdb->prepare($query, ...$appointment_ids);
    $appointments = $wpdb->get_results($prepared_query);

    echo '<div class="wrap"><h1>My Appointments</h1>';
    echo '<table class="widefat fixed striped">';
    echo '<thead><tr>
        <th>ID</th><th>Service</th><th>Date</th><th>Time</th><th>Status</th>
        <th>Payment Status</th><th>Description</th><th>Phone</th><th>Name</th>
        <th>Email</th><th>Staff Name</th><th>Action</th>
    </tr></thead><tbody>';

    foreach ($appointments as $appt) {
        $fields = $wpdb->get_results($wpdb->prepare(
            "SELECT field_id, value FROM {$wpdb->prefix}ea_fields WHERE app_id = %d",
            $appt->id
        ), OBJECT_K);

        $worker_name = $wpdb->get_var($wpdb->prepare(
            "SELECT name FROM {$wpdb->prefix}ea_staff WHERE id = %d",
            $appt->worker
        ));

        $service_name   = isset($fields[5]) ? $fields[5]->value : '';
        $description    = isset($fields[4]) ? $fields[4]->value : '';
        $phone          = isset($fields[3]) ? $fields[3]->value : '';
        $customer_name  = isset($fields[2]) ? $fields[2]->value : '';
        $customer_email = isset($fields[1]) ? $fields[1]->value : '';

        $start_date = date('F j, Y', strtotime($appt->date)); //fix
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
        echo '<td>' . esc_html($worker_name) . '</td>';
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

// 5. Handle deletion of appointments
function handle_delete_appointment() {
    if (
        isset($_POST['delete_appointment']) &&
        !empty($_POST['delete_appointment_id']) &&
        is_user_logged_in()
    ) {
        global $wpdb;
        $appt_id = intval($_POST['delete_appointment_id']);
        $current_user = wp_get_current_user();
        $email = $current_user->user_email;

        $owned = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->prefix}ea_fields WHERE app_id = %d AND field_id = 1 AND value = %s",
            $appt_id,
            $email
        ));

        if ($owned) {
            $wpdb->delete("{$wpdb->prefix}ea_fields", ['app_id' => $appt_id]);
            $wpdb->delete("{$wpdb->prefix}ea_appointments", ['id' => $appt_id]);
        }
    }
}
add_action('init', 'handle_delete_appointment');