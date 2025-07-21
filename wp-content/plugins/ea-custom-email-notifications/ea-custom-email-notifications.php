<?php
/*
Plugin Name: Easy Appointments – Custom Email Notifications
Description: Sends custom HTML email notifications to users and admins when a new appointment is created in Easy Appointments.
Version: 1.0
Author: Jasmine
*/

if (!defined('ABSPATH')) exit;

// === USER EMAIL ===
add_action('ea_user_email_notification', 'eacn_custom_user_email', 10, 1);

function eacn_custom_user_email($appointment_id)
{
    global $wpdb;

    $appointment = $wpdb->get_row(
        $wpdb->prepare("SELECT * FROM {$wpdb->prefix}ea_appointments WHERE id = %d", $appointment_id),
        ARRAY_A
    );
    if (!$appointment) return;

    $fields = $wpdb->get_results(
        $wpdb->prepare("SELECT field_id, value FROM {$wpdb->prefix}ea_fields WHERE app_id = %d", $appointment_id),
        OBJECT_K
    );

    $service_name = isset($fields[5]) ? $fields[5]->value : '';
    $user_email   = isset($fields[1]) ? $fields[1]->value : '';
    $user_name    = isset($fields[2]) ? $fields[2]->value : '';

    $subject = 'Your Appointment Confirmation – Expert Next Door';

    $body = '
    <div style="font-family: Arial, sans-serif; max-width: 600px; margin: auto; padding: 20px; background-color: #f9f9f9; border: 1px solid #ddd; border-radius: 8px;">
      <h2 style="color: #f03250;">Hello ' . esc_html($user_name) . ',</h2>
      <p style="font-size: 16px; color: #333;">
        Thank you for booking with <strong>Expert Next Door</strong>! We\'re happy to confirm your appointment.
      </p>
      <div style="background-color: #fff; padding: 15px 20px; border: 1px solid #ccc; border-radius: 6px; margin: 20px 0;">
        <p><strong>Service:</strong> ' . esc_html($service_name) . '</p>
        <p><strong>Date:</strong> ' . date('F j, Y', strtotime($appointment['start'])) . '</p>
        <p><strong>Time:</strong> ' . date('H:i', strtotime($appointment['start'])) . ' – ' . date('H:i', strtotime($appointment['end'])) . '</p>
        <p><strong>Status:</strong> ' . esc_html($appointment['status']) . '</p>
      </div>
      <p>If you need to make changes, reply to this email or contact us.</p>
      <p>Best regards,<br><strong>Expert Next Door</strong></p>
      <hr style="margin-top: 30px; border: none; border-top: 1px solid #ddd;">
      <p style="font-size: 12px; color: #aaa; text-align: center;">
        This is an automated message. Please do not reply unless you need support.
      </p>
    </div>';

    $headers = ['Content-Type: text/html; charset=UTF-8'];
    wp_mail($user_email, $subject, $body, $headers);
}

// === ADMIN EMAIL ===
add_action('ea_admin_email_notification', 'eacn_custom_admin_email', 12, 1);

function eacn_custom_admin_email($appointment_id)
{
    global $wpdb;

    $appointment = $wpdb->get_row(
        $wpdb->prepare("SELECT * FROM {$wpdb->prefix}ea_appointments WHERE id = %d", $appointment_id)
    );
    if (!$appointment) return;

    $fields = $wpdb->get_results(
        $wpdb->prepare("SELECT field_id, value FROM {$wpdb->prefix}ea_fields WHERE app_id = %d", $appointment_id),
        OBJECT_K
    );

    $service_name = isset($fields[5]) ? $fields[5]->value : '';
    $user_email   = isset($fields[1]) ? $fields[1]->value : '';
    $user_name    = isset($fields[2]) ? $fields[2]->value : '';
    $user_phone   = isset($fields[3]) ? $fields[3]->value : '';

    $location_name = $wpdb->get_var(
        $wpdb->prepare("SELECT location FROM {$wpdb->prefix}ea_locations WHERE id = %d", $appointment->location)
    );

    $staff_name = $wpdb->get_var(
        $wpdb->prepare("SELECT name FROM {$wpdb->prefix}ea_staff WHERE id = %d", $appointment->worker)
    );

    $admin_email = get_option('admin_email');
    $subject = '🔔 New Appointment Received';

    ob_start();
    ?>
    <div style="font-family: Arial, sans-serif;">
        <h2 style="color: #333;">New Appointment Notification</h2>
        <p><strong>Name:</strong> <?php echo esc_html($user_name); ?></p>
        <p><strong>Email:</strong> <?php echo esc_html($user_email); ?></p>
        <p><strong>Phone:</strong> <?php echo esc_html($user_phone); ?></p>
        <p><strong>Service:</strong> <?php echo esc_html($service_name); ?></p>
        <p><strong>Location:</strong> <?php echo esc_html($location_name); ?></p>
        <p><strong>Worker:</strong> <?php echo esc_html($staff_name); ?></p>
        <p><strong>Start Time:</strong> <?php echo esc_html($appointment->start); ?></p>
        <p><strong>End Time:</strong> <?php echo esc_html($appointment->end); ?></p>
        <p><strong>Price:</strong> <?php echo esc_html($appointment->price); ?></p>
        <p><strong>Payment Status:</strong> <?php echo esc_html($appointment->payment_status); ?></p>
    </div>
    <?php
    $message = ob_get_clean();

    wp_mail($admin_email, $subject, $message, ['Content-Type: text/html; charset=UTF-8']);
}
