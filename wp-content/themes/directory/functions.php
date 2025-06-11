<?php


function service_listing_enqueue_styles()
{
    wp_enqueue_style('directing_bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css');
    wp_enqueue_style('directing_bfont_awesome', get_template_directory_uri() . '/css/font-awesome.min.css');
    wp_enqueue_style('directing_elegant_cons', get_template_directory_uri() . '/css/elegant-icons.css');
    wp_enqueue_style('directing_flaticon', get_template_directory_uri() . '/css/flaticon.css');
    wp_enqueue_style('directing_nice-select', get_template_directory_uri() . '/css/nice-select.css');
    wp_enqueue_style('directing_barfiller', get_template_directory_uri() . '/css/barfiller.css');
    wp_enqueue_style('directing_magnific_popup', get_template_directory_uri() . '/css/magnific-popup.css');
    wp_enqueue_style('directing_jquery_ui_min', get_template_directory_uri() . '/css/jquery-ui.min.css');
    wp_enqueue_style('directing_owl_carousel', get_template_directory_uri() . '/css/owl.carousel.min.css');
    wp_enqueue_style('directing_slicknav', get_template_directory_uri() . '/css/slicknav.min.css');
    wp_enqueue_style('directing_style', get_template_directory_uri() . '/css/style.css');
}
add_action('wp_enqueue_scripts', 'service_listing_enqueue_styles');


function service_listing_enqueue_scripts()
{
    wp_enqueue_script('jquery');
    // JS plugins with jQuery dependency
    wp_enqueue_script('directing_bootstrapjs', get_template_directory_uri() . '/js/bootstrap.min.js', array('jquery'), null, true);
    wp_enqueue_script('directing_jquery_nice_select', get_template_directory_uri() . '/js/jquery.nice-select.min.js', array('jquery'), null, true);
    wp_enqueue_script('directing_jquery_ui', get_template_directory_uri() . '/js/jquery-ui.min.js', array('jquery'), null, true);
    wp_enqueue_script('directing_jquery_nicescroll', get_template_directory_uri() . '/js/jquery.nicescroll.min.js', array('jquery'), null, true);
    wp_enqueue_script('directing_jquery_barfiller', get_template_directory_uri() . '/js/jquery.barfiller.js', array('jquery'), null, true);
    wp_enqueue_script('directing_jquery_magnific_popup', get_template_directory_uri() . '/js/jquery.magnific-popup.min.js', array('jquery'), null, true);
    wp_enqueue_script('directing_jquery_slicknav', get_template_directory_uri() . '/js/jquery.slicknav.js', array('jquery'), null, true);
    wp_enqueue_script('directing_owl_carouseljs', get_template_directory_uri() . '/js/owl.carousel.min.js', array('jquery'), null, true);

    // Your main custom JS (e.g., to initialize Owl)
    wp_enqueue_script('directing_main', get_template_directory_uri() . '/js/main.js', array('jquery'), null, true);
}



add_action('wp_enqueue_scripts', 'service_listing_enqueue_scripts');

function load_easy_appointments_assets_manually()
{
    if (is_page_template('template-appointment-booking.php')) { // your template filename
        wp_enqueue_style('easy-appointments-bootstrap', plugins_url('/assets/css/bootstrap.min.css', __FILE__));
        wp_enqueue_script('easy-appointments-bootstrap', plugins_url('/assets/js/bootstrap.min.js', __FILE__), array('jquery'), null, true);
        wp_enqueue_script('easy-appointments-main', plugins_url('/assets/js/main.js', __FILE__), array('jquery'), null, true);
    }
}
add_action('wp_enqueue_scripts', 'load_easy_appointments_assets_manually');


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


function sync_new_ea_staff_to_users_cpt()
{
    global $wpdb;

    // Step 1: Fetch all ea_staff entries
    $staff_members = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ea_staff");

    foreach ($staff_members as $staff) {
        // Step 2: Check if this staff is already synced to user-profile CPT
        $existing_post = new WP_Query([
            'post_type'      => 'user-profile',
            'meta_key'       => 'user_id',
            'meta_value'     => $staff->id,
            'fields'         => 'ids',
            'posts_per_page' => 1,
        ]);

        if (empty($existing_post->posts)) {
            // Not found, insert new user-profile post
            $post_id = wp_insert_post([
                'post_type'    => 'user-profile',
                'post_title'   => $staff->name,
                'post_content' => $staff->description,
                'post_status'  => 'publish',
                'meta_input'   => [
                    'user_name'        => $staff->name,
                    'user_description' => $staff->description,
                    'user_email'       => $staff->email,
                    'user_phone'       => $staff->phone,
                    'user_id'          => $staff->id,
                ]
            ]);

            if ($post_id) {

                // Fetch the first schedule entry from ea_connections
                $schedule_row = $wpdb->get_row(
                    $wpdb->prepare(
                        "SELECT day_of_week, time_from, time_to FROM {$wpdb->prefix}ea_connections WHERE worker = %d LIMIT 1",
                        $staff->id
                    ),
                    ARRAY_A
                );

                if (!empty($schedule_row)) {
                    $acf_group_schedule = [
                        'day_of_week' => $schedule_row['day_of_week'],
                        'time_from'   => $schedule_row['time_from'],
                        'time_to'     => $schedule_row['time_to'],
                    ];

                    // Save as a Group field
                    update_field('schedule', $acf_group_schedule, $post_id);
                }
            }
        }

        wp_reset_postdata();
    }
}



add_action('admin_menu', function () {
    add_submenu_page(
        'tools.php',
        'Sync EA Staff to Users',
        'Sync EA Staff',
        'manage_options',
        'sync-ea-staff',
        'render_sync_staff_page'
    );
});

function render_sync_staff_page()
{
    if (isset($_POST['sync_staff'])) {
        sync_new_ea_staff_to_users_cpt();
        mirror_ea_connections_to_user_profiles_with_taxonomy();
        echo '<div class="updated"><p>New staff data synced successfully.</p></div>';
    }
?>
    <div class="wrap">
        <h1>Sync EA Staff to Users CPT</h1>
        <form method="post">
            <p>This will import only new staff records from <code>wp_ea_staff</code> that haven’t been synced before.</p>
            <input type="submit" name="sync_staff" class="button button-primary" value="Synchronize Now">
        </form>
    </div>
<?php
}


/* assign categories */
function mirror_ea_connections_to_user_profiles_with_taxonomy()
{
    global $wpdb;

    $user_profiles = get_posts([
        'post_type'      => 'user-profile',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'meta_key'       => 'user_id'
    ]);


    foreach ($user_profiles as $profile) {
        $user_id = get_post_meta($profile->ID, 'user_id', true);


        if (!$user_id) continue;

        // Get connected service IDs
        $service_ids = $wpdb->get_col(
            $wpdb->prepare(
                "SELECT DISTINCT service FROM {$wpdb->prefix}ea_connections WHERE worker= %d",
                $user_id
            )
        );



        // Map service IDs to service names
        $service_names = [];
        foreach ($service_ids as $sid) {
            $service_name = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT name FROM {$wpdb->prefix}ea_services WHERE id = %d",
                    $sid
                )
            );
            if ($service_name) $service_names[] = $service_name;
        }



        wp_set_object_terms($profile->ID, $service_names, 'service-category');
    }
}

/* Customer Management */
function custom_login_redirect($redirect_to, $request, $user)
{
    // Check if the user object is valid
    if (isset($user->roles) && is_array($user->roles)) {
        // Redirect all users to home page
        return home_url();
    }
    return $redirect_to;
}
add_filter('login_redirect', 'custom_login_redirect', 10, 3);


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



if (isset($_POST['delete_appointment']) && isset($_POST['delete_appointment_id'])) {
    $appointment_id = intval($_POST['delete_appointment_id']);

    // Optional: confirm ownership by current user before deleting
    $email = wp_get_current_user()->user_email;

    $app_owner_check = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM wp_ea_fields WHERE app_id = %d AND field_id = 1 AND value = %s",
        $appointment_id,
        $email
    ));

    if ($app_owner_check) {
        // Delete from both tables
        $wpdb->delete('wp_ea_fields', ['app_id' => $appointment_id]);
        $wpdb->delete('wp_ea_appointments', ['id' => $appointment_id]);

        echo '<div class="notice notice-success"><p>Appointment deleted successfully.</p></div>';
    } else {
        echo '<div class="notice notice-error"><p>You are not allowed to delete this appointment.</p></div>';
    }
}



function render_my_customer_appointments()
{
    $current_user = wp_get_current_user();
    $email = $current_user->user_email;

    global $wpdb;

    // Step 1: Get app_ids where email field matches user email
    $appointment_ids = $wpdb->get_col($wpdb->prepare(
        "SELECT app_id FROM wp_ea_fields WHERE field_id = 1 AND value = %s",
        $email
    ));



    if (empty($appointment_ids)) {
        echo '<div class="wrap"><h1>My Appointments</h1><p>No appointments found.</p></div>';
        return;
    }

    // Step 2: Get wp_ea_appointments for those IDs
    $placeholders = implode(',', array_fill(0, count($appointment_ids), '%d'));
    $query = "SELECT * FROM wp_ea_appointments WHERE id IN ($placeholders) ORDER BY start DESC";
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
        <th>Payment Status</th>
        <th>Description</th>
        <th>Phone</th>
        <th>Name</th>
        <th>Email</th>
        <th>Staff Name</th>
        <th>Action</th>
    </tr></thead><tbody>';

    foreach ($appointments as $appt) {
        // Step 3: Get fields for this appointment
        $fields = $wpdb->get_results($wpdb->prepare(
            "SELECT field_id, value FROM wp_ea_fields WHERE app_id = %d",
            $appt->id
        ), OBJECT_K);
        $worker_id = $appt->worker;
        $worker_name = $wpdb->get_var($wpdb->prepare(
            "SELECT name FROM wp_ea_staff WHERE id = %d",
            $worker_id
        ));

        $service_name = isset($fields[5]) ? $fields[5]->value : '';
        $description  = isset($fields[4]) ? $fields[4]->value : '';
        $phone        = isset($fields[3]) ? $fields[3]->value : '';
        $customer_name        = isset($fields[2]) ? $fields[2]->value : '';
        $customer_email        = isset($fields[1]) ? $fields[1]->value : '';

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
        echo '<td>' . esc_html($worker_name) . '</td>';
        echo '<td>';
        echo '<form method="post">';
        echo '<input type="hidden" name="delete_appointment_id" value="' . esc_attr($appt->id) . '">';
        echo '<input type="submit" name="delete_appointment" value="Delete" onclick="return confirm(\'Are you sure?\');">';
        echo '</form>';
        echo '</td>';
        echo '</tr>';
    }

    echo '</tbody></table></div>';
}

/*Staff Management */

function add_staff_user_role()
{
    add_role(
        'staff',
        'Staff',
        [
            'read' => true, // basic capability to log in
        ]
    );
}
add_action('init', 'add_staff_user_role');


function render_my_staff_appointments()
{
    $current_user = wp_get_current_user();

    if (!in_array('staff', (array) $current_user->roles)) {
        echo '<div class="wrap"><h1>Access Denied</h1><p>You do not have permission to view this page.</p></div>';
        return;
    }

    $email = $current_user->user_email;
    global $wpdb;

    // Step 1: Get staff ID from wp_ea_staff table
    $staff_id = $wpdb->get_var($wpdb->prepare(
        "SELECT id FROM wp_ea_staff WHERE email = %s",
        $email
    ));

    if (!$staff_id) {
        echo '<div class="wrap"><h1>My Appointments</h1><p>No staff record found for your email.</p></div>';
        return;
    }

    // Step 2: Get appointments for this staff ID
    $appointments = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM wp_ea_appointments WHERE worker = %d ORDER BY start DESC",
        $staff_id
    ));

    if (empty($appointments)) {
        echo '<div class="wrap"><h1>My Appointments</h1><p>No appointments found.</p></div>';
        return;
    }

    // Step 3: Display appointments
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
        // Load fields (email, name, phone, description, service_name)
        $fields = $wpdb->get_results($wpdb->prepare(
            "SELECT field_id, value FROM wp_ea_fields WHERE app_id = %d",
            $appt->id
        ), OBJECT_K);

        $service_name = isset($fields[5]) ? $fields[5]->value : '';
        $description  = isset($fields[4]) ? $fields[4]->value : '';
        $phone        = isset($fields[3]) ? $fields[3]->value : '';
        $customer_name = isset($fields[2]) ? $fields[2]->value : '';
        $customer_email = isset($fields[1]) ? $fields[1]->value : '';

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



function add_staff_appointments_menu()
{
    if (current_user_can('staff')) {
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

/* Remove <p> from cf7 */
add_filter('wpcf7_autop_or_not', '__return_false');

/* Upcoming appointments */


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
function ea_render_appointments_page()
{
    global $wpdb;



    $today = current_time('Y-m-d');

    // Step 1: Get upcoming appointments
    $appointments = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT id, date, service, worker FROM {$wpdb->prefix}ea_appointments WHERE date >= %s ORDER BY date ASC LIMIT 20",
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
                    <th>Staff</th>
                    <th>Service</th>
                </tr>
              </thead><tbody>';

        foreach ($appointments as $appointment) {
            // Step 2: Get staff name by worker ID
            $staff_name = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT name FROM {$wpdb->prefix}ea_staff WHERE id = %d",
                    $appointment->worker
                )
            );

            $service_name = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT name FROM {$wpdb->prefix}ea_services WHERE id = %d",
                    $appointment->service
                )
            );

            echo '<tr>';
            echo '<td>' . esc_html($appointment->date) . '</td>';
            echo '<td>' . esc_html($staff_name ?? 'Unknown') . '</td>';

            echo '<td>' . esc_html($service_name ?? '-') . '</td>';
            echo '</tr>';
        }

        echo '</tbody></table>';
    } else {
        echo '<p>No upcoming appointments found.</p>';
    }

    echo '</div>';
}


/* Hook into ea_new_app , the hook used while inserting a new appointment in wp_ea_appointment table to get the appointment id */

add_action('ea_new_app', 'handle_new_appointment', 10, 3);

function handle_new_appointment($appointment_id, $appointment_data, $send_notifications) {
    if (is_user_logged_in()) {
        $user_id = get_current_user_id();
        update_user_meta($user_id, 'ea_last_appointment_id', $appointment_id);
    }
}

/* Create API endpoint for Razorpay */


add_action('rest_api_init', function () {
    register_rest_route('razorpay/v1', '/payment-success', [
        'methods' => 'POST',
        'callback' => 'handle_razorpay_payment_webhook',
        'permission_callback' => '__return_true',
    ]);
});



function handle_razorpay_payment_webhook(WP_REST_Request $request)
{

    global $wpdb;

    $data = $request->get_json_params();



    // Extract appointment_id from notes
    $appointment_id = intval($data['payload']['payment']['entity']['notes']['appointment_id'] ?? 0);

    if ($appointment_id === 0) {
        return new WP_REST_Response(['success' => false, 'error' => 'Missing appointment_id'], 400);
    }

    // Update payment status
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

/* Customize wp-login.php to include 2 types of registrations --> Customer(Subscriber) and Staff */

add_action('login_header', 'add_registration_buttons_above_login');
function add_registration_buttons_above_login() {
    echo '<div style="text-align:center;margin-bottom:15px;margin-top:15px;">
        <a href="' . site_url('/customer-registration') . '" style="margin: 5px; padding: 8px 18px; background: #f03250; color: white; border-radius: 4px; text-decoration: none;">Customer Registration</a>
        <a href="' . site_url('/staff-registration') . '" style="margin: 5px; padding: 8px 18px; background: #0073aa; color: white; border-radius: 4px; text-decoration: none;">Staff Registration</a>
    </div>';
}


add_action('login_enqueue_scripts', 'enqueue_custom_login_css');
function enqueue_custom_login_css() {
    wp_enqueue_style('custom-login-style', get_stylesheet_directory_uri() . '/css/login.css');
}


add_action('admin_footer', function () {
    if (!is_admin()) return;

    $new_nonce = wp_create_nonce('wp_rest'); // Note: 'wp_rest' not 'ea_appointment'
    ?>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        window.fresh_nonce = '<?php echo esc_js($new_nonce); ?>';

        const observer = new MutationObserver(() => {
            document.querySelectorAll('[name="_wpnonce"]').forEach(el => {
                if (window.fresh_nonce && el.value !== window.fresh_nonce) {
                    el.value = window.fresh_nonce;
                    console.log("✅ _wpnonce updated to REST nonce:", window.fresh_nonce);
                }
            });

            document.querySelectorAll('a, button, form').forEach(el => {
                if (el.hasAttribute('onclick')) {
                    el.setAttribute('onclick', el.getAttribute('onclick').replace(/_wpnonce=([a-zA-Z0-9]+)/, '_wpnonce=' + window.fresh_nonce));
                }
            });
        });

        observer.observe(document.body, { childList: true, subtree: true });
        setTimeout(() => observer.takeRecords(), 500);
    });
    </script>
    <?php
});


/* Email Template Enhancements */
add_action('ea_user_email_notification', 'custom_ea_html_email', 10, 1);

function custom_ea_html_email($appointment_id) {
     error_log("✅ Custom Hook Triggered for appointment: $appointment_id");
    global $wpdb;

    // Get appointment data from DB
    $table = $wpdb->prefix . 'ea_appointments';
    $appointment = $wpdb->get_row(
        $wpdb->prepare("SELECT * FROM $table WHERE id = %d", $appointment_id),
        ARRAY_A
    );
    if (!$appointment) {
        return; // Invalid ID
    }

    $fields = $wpdb->get_results(
    $wpdb->prepare(
        "SELECT field_id, value FROM {$wpdb->prefix}ea_fields WHERE app_id = %d",
        $appointment_id
    ),
    OBJECT_K
);

error_log(print_r($fields, true));

$service_name = isset($fields[5]) ? $fields[5]->value : '';
$user_email   = isset($fields[1]) ? $fields[1]->value : '';
$user_name = isset($fields[2]) ? $fields[2]->value : '';

error_log($service_name);
error_log($user_email);

    
    

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












?>