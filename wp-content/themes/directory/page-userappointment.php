<?php

/**
 * Template Name: Appointment Booking User
 */
get_header('listing');
$current_url = home_url(add_query_arg(null, null));
$current_user = wp_get_current_user();
$allowed_roles = array('subscriber', 'customer');

$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
if (is_user_logged_in() && array_intersect($allowed_roles, $current_user->roles)) :

?>

    <main id="primary" class="site-main">
        <section class="appointment-section">
            <div class="container">
                <h3>Book an Appointment</h3>
                <?php echo do_shortcode('[dynamic_ea_booking_service]'); ?>

            </div>
        </section>

        <?php
        $last_appointment_id = get_user_meta(get_current_user_id(), 'ea_last_appointment_id', true);


        ?>
         <script>
            const lastAppointmentId = "<?php echo esc_js($last_appointment_id); ?>";
            
        </script>

    </main>
<?php else : ?>
    <main id="primary" class="site-main">
        <section class="appointment-section">
            <div class="container" style="padding-top: 120px; min-height: 60vh;">
                <div class="ea-login-message" style="background: #fff3cd; padding: 20px; border: 1px solid #ffeeba; border-radius: 6px;">
                    <p><strong>You must be logged in with the proper role to book an appointment.</strong></p>
                    <a href="<?php echo esc_url(wp_login_url($current_url)); ?>" class="btn btn-primary">Login here</a>
                </div>
            </div>
        </section>
    </main>
<?php endif; ?>

<?php get_footer(); ?>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const formContainer = document.querySelector("#ea_bootstrap");

    if (!formContainer) {
        console.warn("Easy Appointments form container not found.");
        return;
    }

    const clearForm = () => {
        const form = formContainer.querySelector("form");
        if (form) {
            form.reset(); // Basic reset

            // Clear input values manually (in case .reset() doesn't handle some custom inputs)
            form.querySelectorAll("input[type='text'], input[type='email'], textarea").forEach(input => {
                input.value = '';
            });

            // Reset all selects
            form.querySelectorAll("select").forEach(select => {
                select.selectedIndex = 0;
            });

            // Remove validation states (if using jQuery Validate or similar)
            form.querySelectorAll(".valid, .error").forEach(el => {
                el.classList.remove("valid", "error");
                el.setAttribute("aria-invalid", "false");
            });

            console.log("EA form cleared.");
        }
    };

    // Handle Cancel button
    formContainer.addEventListener("click", function (e) {
        if (e.target.matches(".ea-cancel")) {
            e.preventDefault(); // prevent default Cancel behavior if needed
            clearForm();
        }
    });

    // Handle Submit button after successful booking (observe DOM for success message)
    const observer = new MutationObserver((mutationsList) => {
        for (let mutation of mutationsList) {
            mutation.addedNodes.forEach((node) => {
                if (node.nodeType === 1) {
                    const text = node.textContent.trim().toLowerCase();
                    if (text.includes("appointment booked successfully")) {
                        clearForm(); // Clear the form after successful booking
                    }
                }
            });
        }
    });

    observer.observe(formContainer, {
        childList: true,
        subtree: true
    });
});
</script>

