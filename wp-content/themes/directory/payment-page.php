<?php
/**
 * Template Name: Payment Page
 */
get_header('listing'); 
?>

<style>
.payment-container {
    max-width: 600px;
    margin: 100px auto 40px; /* Push down from top, center horizontally */
    padding: 20px;
    background: #ffffff;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    border-radius: 12px;
    text-align: center;
}

.payment-container h2 {
    margin-bottom: 20px;
    font-size: 1.8rem;
    color: #333;
}
</style>

<div class="payment-container">
    <h2>Complete Your Payment</h2>
    <form>
        <script 
            src="https://checkout.razorpay.com/v1/payment-button.js" 
            data-payment_button_id="pl_QaHV27OqPCcgu5" 
            async>
        </script>
    </form>
</div>

<?php get_footer(); ?>
