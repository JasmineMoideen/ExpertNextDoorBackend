<?php
/*
Plugin Name: Easy Appointments Service Sync
Description: Syncs the selected service ID from user meta with Easy Appointments when a new appointment is created.
Version: 1.0
Author: Jasmine
*/

if (!defined('ABSPATH')) exit; // Exit if accessed directly

add_action('ea_new_app', 'eass_handle_new_appointment', 10, 3);

function eass_handle_new_appointment($appointment_id, $appointment_data, $all_data)
{
    if (!is_user_logged_in()) {
        return;
    }

    $user_id = get_current_user_id();
    update_user_meta($user_id, 'ea_last_appointment_id', $appointment_id);

    $service_id = get_user_meta($user_id, '_ea_temp_service_id', true);

    if ($service_id) {
        global $wpdb;

        // Step 1: Get service name from wp_ea_services
        $services_table = $wpdb->prefix . 'ea_services';
        $service_name = $wpdb->get_var(
            $wpdb->prepare("SELECT name FROM {$services_table} WHERE id = %d", $service_id)
        );

        // Step 2: Update wp_ea_appointments table
        $appointments_table = $wpdb->prefix . 'ea_appointments';
        $wpdb->update(
            $appointments_table,
            ['service' => intval($service_id)],
            ['id' => $appointment_id],
            ['%d'],
            ['%d']
        );

        // Clean up temporary user meta
        delete_user_meta($user_id, '_ea_temp_service_id');

        // Step 3: Update wp_ea_fields (field_id = 5) with service name
        $fields_table = $wpdb->prefix . 'ea_fields';
        $wpdb->update(
            $fields_table,
            ['value' => $service_name],
            [
                'app_id' => $appointment_id,
                'field_id' => 5
            ],
            ['%s'],
            ['%d', '%d']
        );
    }
}
