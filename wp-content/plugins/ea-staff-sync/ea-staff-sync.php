<?php
/*
Plugin Name: EA Staff Sync to User Profiles
Description: Syncs EA Staff database entries to a user-profile custom post type and maps their services as taxonomy terms.
Version: 1.0
Author: Jasmine
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Main sync function
function sync_new_ea_staff_to_users_cpt()
{
    global $wpdb;

    $staff_members = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ea_staff");

    foreach ($staff_members as $staff) {
        $existing_post = new WP_Query([
            'post_type'      => 'user-profile',
            'meta_key'       => 'user_id',
            'meta_value'     => $staff->id,
            'fields'         => 'ids',
            'posts_per_page' => 1,
        ]);

        if (empty($existing_post->posts)) {
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

                    update_field('schedule', $acf_group_schedule, $post_id);
                }
            }
        }

        wp_reset_postdata();
    }
}

// Mirror taxonomy function
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

        $service_ids = $wpdb->get_col(
            $wpdb->prepare(
                "SELECT DISTINCT service FROM {$wpdb->prefix}ea_connections WHERE worker = %d",
                $user_id
            )
        );

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

        if (!empty($service_names)) {
            wp_set_object_terms($profile->ID, $service_names, 'service-category');
        }
    }
}

// Admin menu
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

// Admin UI
function render_sync_staff_page()
{
    if (isset($_POST['sync_staff']) && check_admin_referer('ea_staff_sync')) {
        sync_new_ea_staff_to_users_cpt();
        mirror_ea_connections_to_user_profiles_with_taxonomy();
        echo '<div class="updated"><p>New staff data synced successfully.</p></div>';
    }

    ?>
    <div class="wrap">
        <h1>Sync EA Staff to Users CPT</h1>
        <form method="post">
            <?php wp_nonce_field('ea_staff_sync'); ?>
            <p>This will import only <strong>new</strong> staff records from <code>wp_ea_staff</code> that haven’t been synced before.</p>
            <input type="submit" name="sync_staff" class="button button-primary" value="Synchronize Now">
        </form>
    </div>
    <?php
}
