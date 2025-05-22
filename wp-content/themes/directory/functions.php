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
            'post_type'  => 'user-profile',
            'meta_key'   => 'user_id',
            'meta_value' => $staff->id,
            'fields'     => 'ids',
            'posts_per_page' => 1,
        ]);

        if (empty($existing_post->posts)) {
            // Not found, insert new user-profile post
            wp_insert_post([
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


function custom_login_redirect($redirect_to, $request, $user) {
    // Check if the user object is valid
    if (isset($user->roles) && is_array($user->roles)) {
        // Redirect all users to home page
        return home_url();
    }
    return $redirect_to;
}
add_filter('login_redirect', 'custom_login_redirect', 10, 3);






?>