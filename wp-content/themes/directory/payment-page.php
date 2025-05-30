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
    <form>
        <script
            src="https://checkout.razorpay.com/v1/payment-button.js"
            data-payment_button_id="pl_QaHV27OqPCcgu5"
            async>
        </script>
    </form>
</div>
<script>

document.addEventListener("DOMContentLoaded", function () {
  let paymentAlreadyDetected = false;

  const observer = new MutationObserver((mutationsList) => {
    if (paymentAlreadyDetected) return;

    for (let mutation of mutationsList) {
      // Check if new nodes were added
      if (mutation.type === 'childList') {
        for (let node of mutation.addedNodes) {
          if (node.nodeType === 1) {
            const text = node.textContent.trim().toLowerCase();
            console.log("Child added:", text);

            if (text.includes("payment successful")) {
              handlePaymentDetected();
              return;
            }
          }
        }
      }

      // Check if text content changed in existing nodes
      if (mutation.type === 'characterData' || mutation.type === 'subtree') {
        const target = mutation.target;
        if (target.textContent.trim().toLowerCase().includes("payment successful")) {
          handlePaymentDetected();
          return;
        }
      }
    }
  });

  function handlePaymentDetected() {
    paymentAlreadyDetected = true;
    console.log("✅ Detected payment success message");

    fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
      method: 'POST',
      credentials: 'same-origin',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: 'action=mark_payment_complete'
    })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        console.log("✅ Payment status updated.");
      } else {
        console.error("❌ Failed to update payment status:", data);
      }
    })
    .catch((error) => {
      console.error("❌ AJAX error:", error);
    });
  }

  observer.observe(document.body, {
    childList: true,
    subtree: true,
    characterData: true,
    characterDataOldValue: true
  });
});
</script>

