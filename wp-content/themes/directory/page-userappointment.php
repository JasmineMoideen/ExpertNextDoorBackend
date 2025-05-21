<?php
/**
 * Template Name: Appointment Booking User
 */
get_header('listing'); 

$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

?>

<main id="primary" class="site-main">
    <section class="appointment-section">
        <div class="container">
            <h3>Book an Appointment</h3>
            <?php echo do_shortcode('[dynamic_ea_booking_service]'); ?>

        </div>
    </section>
</main>

<?php get_footer(); ?>
