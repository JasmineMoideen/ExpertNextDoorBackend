<?php
/**
 * Plugin Name: React Contact Form API
 * Description: Custom API for handling React contact form submissions.
 * Version: 1.0
 * Author: Jasmine
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

add_action( 'rest_api_init', function() {
    header("Access-Control-Allow-Origin: *"); // or your frontend URL
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");
}, 15 );

// Register REST API route
add_action('rest_api_init', function () {
    register_rest_route('react-form/v1', '/submit/', array(
        'methods'  => 'POST',
        'callback' => 'handle_contact_form_submission',
        'permission_callback' => '__return_true', // Change for authentication if needed
    ));
});

// Function to handle form submission
function handle_contact_form_submission(WP_REST_Request $request) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'contact_form_entries';

    // Get data from request
    $first_name = sanitize_text_field($request->get_param('first_name'));
    $last_name = sanitize_text_field($request->get_param('last_name'));
    $your_email = sanitize_email($request->get_param('your_email'));
    $phone_number = sanitize_text_field($request->get_param('phone_number'));
    $your_message = sanitize_textarea_field($request->get_param('your_message'));
    error_log('Details are: ' . $first_name . ', ' . $last_name . ', ' . $your_email . ', ' . $phone_number.','.$your_message);


    // Validate required fields
    if (empty($first_name) || empty($last_name) || empty($your_email) || empty($phone_number) ||empty($your_message)) {
        return new WP_REST_Response(['message' => 'All fields are required.'], 400);
    }




    $inserted = $wpdb->insert($table_name, [
    'first_name'    => $first_name,
    'last_name'     => $last_name,
    'your_email'    => $your_email,
    'phone_number'  => $phone_number,
    'your_message'  => $your_message,
    'created_at'    => current_time('mysql'),
]);

if ($inserted == false) {
    error_log('Contact form insert failed: ' . $wpdb->last_error);
    return new WP_REST_Response(['message' => 'Failed to insert data.'], 500);
} else {
    error_log('Contact form insert successful: ID ' . $wpdb->insert_id);
}

     // Send email to admin
     $admin_email = get_option('admin_email');
     $subject = "New Contact Form Submission from $first_name";
     $headers = ['Content-Type: text/html; charset=UTF-8', "Reply-To: $first_name <$your_email>"];
 
     $email_body = "
         <h2>New Contact Form Submission</h2>
         <p><strong>Name:</strong> $first_name</p>
         <p><strong>Email:</strong> $your_email</p>
         <p><strong>Message:</strong></p>
         <p>$your_message</p>
     ";
 
     wp_mail($admin_email, $subject, $email_body, $headers);

    return new WP_REST_Response(['message' => 'Form submitted successfully!'], 200);
}

// Create database table on plugin activation
function create_contact_form_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'contact_form_entries';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id INT NOT NULL AUTO_INCREMENT,
        first_name VARCHAR(255) NOT NULL,
        last_name VARCHAR(255) NOT NULL,
        your_email VARCHAR(255) NOT NULL,
        phone_number VARCHAR(255) NOT NULL,
        your_message TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'create_contact_form_table');
