<?php
/*
Plugin Name: ACF Profile Image REST Extension
Description: Adds profile image URL to user-profile post type
Version: 1.0
*/

add_action('rest_api_init', function () {
    register_rest_field('user-profile', 'acf_plus', [
        'get_callback' => function ($post_arr) {
            $fields = get_fields($post_arr['id']);

            if (!empty($fields['profile_image'])) {
                $image_id = $fields['profile_image'];
                $fields['profile_image_url'] = wp_get_attachment_image_url($image_id, 'full');
            }

            return $fields;
        },
        'schema' => null,
    ]);
});
