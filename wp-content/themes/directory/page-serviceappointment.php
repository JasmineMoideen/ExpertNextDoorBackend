<?php

/**
 * Template Name: Appointment Booking Service
 */
get_header('listing');
$current_user = wp_get_current_user();
$allowed_roles = array('subscriber', 'customer');

$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
if (is_user_logged_in() && array_intersect($allowed_roles, $current_user->roles)) : ?>



    <main id="primary" class="site-main">
        <section class="appointment-section">
            <div class="container">
                <h3>Book Appointment</h3>
                <?php echo do_shortcode('[dynamic_ea_booking_service]'); ?>

            </div>
        </section>
        <?php
        $last_appointment_id = get_user_meta(get_current_user_id(), 'ea_last_appointment_id', true);


        ?>
        <script>
            const lastAppointmentId = "<?php echo esc_js($last_appointment_id); ?>";
            console.log("🧪 Debug - Last Appointment ID in service page:", lastAppointmentId);
        </script>




    </main>

<?php else : ?>
    <main id="primary" class="site-main">
        <section class="appointment-section">
            <div class="container" style="padding-top: 120px; min-height: 60vh;">
                <div class="ea-login-message" style="background: #fff3cd; padding: 20px; border: 1px solid #ffeeba; border-radius: 6px;">
                    <p><strong>You must be logged in with the proper role to book an appointment.</strong></p>
                    <a href="<?php echo wp_login_url(get_permalink()); ?>" class="primary-btn">Login here</a>
                </div>
            </div>
        </section>
    </main>
<?php endif; ?>


<?php get_footer(); ?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const observer = new MutationObserver((mutationsList) => {
            for (let mutation of mutationsList) {
                mutation.addedNodes.forEach((node) => {
                    if (node.nodeType === 1) {
                        const text = node.textContent.trim().toLowerCase();

                        if (text === "appointment booked successfully!") {
                            // Redirect to the payment page instead of reloading
                            setTimeout(() => {
                                const paymentURL = window.location.origin + "/servicelisting/payment-page/";

                                    window.location.href = paymentURL;
                                
                                // Replace with your actual payment page path
                            }, 1500);
                        }
                    }
                });
            }
        });

        const target = document.querySelector("#ea_bootstrap") || document.body;
        if (target) {
            observer.observe(target, {
                childList: true,
                subtree: true
            });
            console.log("MutationObserver is watching for 'appointment booked suuccesfully'");
        } else {
            console.warn("Could not find booking container");
        }
    });
</script>