<?php

/**
 * Template Name: Payment Page
 */
get_header('listing');

if (!session_id()) {
    session_start();
}

$appointment_id = 0;

// Priority 1: check GET parameter
if (isset($_GET['appointment_id'])) {
    $appointment_id = intval($_GET['appointment_id']);
}

// Priority 2: fallback to session if not in GET
elseif (isset($_SESSION['ea_last_appointment_id'])) {
    $appointment_id = intval($_SESSION['ea_last_appointment_id']);
}


?>






<div class="payment-container">
    <h2>Complete Your Payment</h2>
    <h2>Your Appointment ID: <?php echo esc_html($appointment_id); ?></h2>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <button id="payBtn">Pay Now</button>

    <script>
        document.getElementById("payBtn").onclick = function() {
            const options = {
                key: "rzp_test_g4so8CfgCDYw6c",
                amount: 50000,
                currency: "INR",
                name: "Expert Next Door",
                description: "Appointment Payment",
                notes: {
                    appointment_id: "<?php echo esc_js($appointment_id); ?>"
                },
                handler: function(response) {
                    console.log("Payment successful:", response);
                    // Optional: send AJAX to confirm on client side
                }
            };

            const rzp = new Razorpay(options);
            rzp.open();
        };
    </script>
    </form>
</div>