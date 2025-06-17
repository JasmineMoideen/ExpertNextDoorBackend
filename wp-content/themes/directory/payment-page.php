<?php

/**
 * Template Name: Payment Page
 */
get_header('listing');


$last_appointment_id = get_user_meta(get_current_user_id(), 'ea_last_appointment_id', true);



?>



<div class="payment-container">
    <h4>Complete Your Payment</h4>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <button id="payBtn">Pay Now</button>

    <script>
    const lastAppointmentId = "<?php echo esc_js($last_appointment_id); ?>";
    document.getElementById("payBtn").onclick = function() {
    const options = {
        key: "rzp_test_g4so8CfgCDYw6c",
        amount: 50000,
        currency: "INR",
        name: "Expert Next Door",
        description: "Appointment Payment",
        notes: {
            appointment_id: lastAppointmentId
        },
        handler: function(response) {
            // On successful payment
            window.location.href = "<?php echo site_url('/thank-you'); ?>";
        },
        modal: {
        ondismiss: function () {
            // Razorpay modal was closed without payment
            // Clear local storage/session storage if needed
            sessionStorage.clear();
            localStorage.clear();
            // Redirect to homepage
            window.location.href = "/servicelisting";
        }
    }
    };

    const rzp = new Razorpay(options);
    rzp.open();
};


    
</script>

</div>

<?php get_footer(); ?>