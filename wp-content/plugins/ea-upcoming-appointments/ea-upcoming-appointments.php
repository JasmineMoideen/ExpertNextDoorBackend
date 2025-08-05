<?php
/*
Plugin Name: EA Upcoming Appointments
Description: Adds an admin menu to view the next 20 upcoming appointments from Easy Appointments.
Version: 1.0
Author: Jasmine
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Register Admin Menu
function ea_register_appointments_admin_menu()
{
    add_menu_page(
        'Upcoming Appointments',
        'Upcoming Appointments',
        'manage_options',
        'ea-upcoming-appointments',
        'ea_render_appointments_page',
        'dashicons-calendar-alt',
        18
    );
}
add_action('admin_menu', 'ea_register_appointments_admin_menu');

// Render the admin page
function ea_render_appointments_page()
{
    global $wpdb;

    $today = current_time('Y-m-d');

    // Get upcoming appointments
    $appointments = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}ea_appointments WHERE date >= %s ORDER BY date ASC LIMIT 20",
            $today
        )
    );

    echo '<div class="wrap">';
    echo '<h1>Upcoming Appointments</h1>';

    if (!empty($appointments)) {
        echo '<table class="widefat fixed striped">';
        echo '<thead>
                <tr>
                    <th>Date</th>
                     <th>Start Time</th>
                      <th>End Time</th>
                    <th>Staff</th>
                    <th>Service</th>
                     <th>Payment Status</th>
                   
                </tr>
              </thead><tbody>';


        foreach ($appointments as $appointment) {

            // Get staff name
            $staff_name = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT name FROM {$wpdb->prefix}ea_staff WHERE id = %d",
                    $appointment->worker
                )
            );

            // Get service name
            $service_name = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT name FROM {$wpdb->prefix}ea_services WHERE id = %d",
                    $appointment->service
                )
            );


            $start_time = date('g:i a', strtotime($appointment->start));
            $end_time   = date('g:i a', strtotime($appointment->end));
            echo '<tr>';
            echo '<td>' . esc_html($appointment->date) . '</td>';
            echo '<td>' . esc_html($start_time) . '</td>';
            echo '<td>' . esc_html($end_time) . '</td>';
            echo '<td>' . esc_html($staff_name ?? 'Unknown') . '</td>';
            echo '<td>' . esc_html($service_name ?? '-') . '</td>';
            echo '<td>' . esc_html($appointment->payment_status) . '</td>';
            echo '</tr>';
        }

        echo '</tbody></table>';
    } else {
        echo '<p>No upcoming appointments found.</p>';
    }

    echo '</div>';
}
