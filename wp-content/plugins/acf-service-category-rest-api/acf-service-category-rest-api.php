<?php
/*
Plugin Name: ACF Service Category REST API
Description: Adds ACF fields and image URLs to the REST API response for service-category taxonomy.
Version: 1.2
Author: Jasmine
*/

if (!defined('ABSPATH')) {
    exit;
}

add_action('rest_api_init', function () {
    register_rest_field('service-category', 'acf_plus', [
        'get_callback' => function ($term) {

            $object_id = $term['taxonomy'] . '_' . $term['id']; // e.g., service-category_13



            $fields = get_fields($object_id);


            // Add image URL if image field exists
            if (!empty($fields['service_category_image'])) {
                $fields['service_category_image_url'] = wp_get_attachment_image_url($fields['service_category_image'], 'full');
            }

            return $fields;
        },
        'schema' => null,
    ]);
});
