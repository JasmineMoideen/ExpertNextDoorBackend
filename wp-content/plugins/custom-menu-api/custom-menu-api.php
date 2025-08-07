<?php
/**
 * Plugin Name: Custom Menu REST API
 * Description: Exposes WordPress menus via a custom REST API endpoint.
 * Version: 1.0
 * Author: Jasmine
 */
function register_custom_menus() {
    register_nav_menus([
        'primary' => __('Primary Menu', 'expert-next-door'),
        
    ]);
}
add_action('after_setup_theme', 'register_custom_menus');

// Register REST endpoint
add_action('rest_api_init', function () {
    register_rest_route('custom/v1', '/menu/(?P<slug>[a-zA-Z0-9_-]+)', array(
        'methods'             => 'GET',
        'callback'            => 'custom_get_menu_items',
        'permission_callback' => '__return_true',
        'args'                => array(
            'slug' => array(
                'required' => true
            )
        )
    ));
});

// Callback function to get menu items
function custom_get_menu_items($data) {
    $menu_slug = $data['slug'];
    $locations = get_nav_menu_locations();

    if (!isset($locations[$menu_slug])) {
        return new WP_Error('menu_not_found', 'Menu not found', array('status' => 404));
    }

    $menu_id = $locations[$menu_slug];
    $menu_items = wp_get_nav_menu_items($menu_id);
    $items = [];

    foreach ($menu_items as $item) {
        $items[] = [
            'id'         => $item->ID,
            'title'      => $item->title,
            'url'        => $item->url,
            'parent'     => $item->menu_item_parent,
            'target'     => $item->target,
            'classes'    => $item->classes,
            'menu_order'=> $item->menu_order,
        ];
    }

    return $items;
}
