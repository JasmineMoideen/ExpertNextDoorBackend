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










/* Remove <p> from cf7 */
add_filter('wpcf7_autop_or_not', '__return_false');

























?>