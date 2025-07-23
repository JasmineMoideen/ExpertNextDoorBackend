<?php
/*
Plugin Name: Service Category Image REST API
Description: Adds ACF image URL for service categories in REST API responses.
Version: 1.0
Author: Jasmine
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Register custom REST field for service-category
add_action('rest_api_init', function () {
    register_rest_field('service-category', 'image_url', [
        'get_callback' => function ($term) {
            // Fetch ACF field value using term ID and taxonomy prefix
            $image_id = get_field('service_category_image', 'service-category_' . $term['id']);
            if ($image_id) {
                return wp_get_attachment_image_url($image_id, 'full');
            }
            return null;
        },
        'schema' => null,
    ]);
});


