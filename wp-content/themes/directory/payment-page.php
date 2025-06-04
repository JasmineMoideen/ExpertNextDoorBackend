<?php
/**
 * Template Name: Payment Page
 */
get_header('listing');

if (!session_id()) {
    session_start();
}

$appointment_id = 0;

if (isset($_GET['appointment_id'])) {
    $appointment_id = intval($_GET['appointment_id']);
} elseif (isset($_SESSION['ea_last_appointment_id'])) {
    $appointment_id = intval($_SESSION['ea_last_appointment_id']);
}
?>



<div class="payment-container">
    <h4>Complete Your Payment</h4>
    
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
                    window.location.href = "<?php echo site_url('/thank-you'); ?>";
                }
            };

            const rzp = new Razorpay(options);
            rzp.open();
        };
    </script>
</div>

<?php get_footer(); ?>
