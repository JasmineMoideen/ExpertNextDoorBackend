<?php get_header('listing'); ?>

<div class="thank-you-container" style="text-align: center; padding: 60px 20px;margin-top:100px;">
    <h1 style="font-size: 36px; color: #f03250;">🎉 Thank You!</h1>
    <p style="font-size: 18px; color: #555;">Your payment was successful.</p>
    <p style="font-size: 16px; color: #777;">We’ve received your appointment and will contact you soon.</p>
    <a href="https://expert-next-door.vercel.app/" style="margin-top: 30px; display: inline-block; background: #f03250; color: white; padding: 12px 24px; border-radius: 5px; text-decoration: none;">Back to Home</a>
</div>

<script>
    // Clear storages on page load
    try {
        sessionStorage.clear();
        localStorage.clear();
        console.log('Storage cleared after payment success.');
    } catch (e) {
        console.warn('Storage clear failed:', e);
    }
</script>

<?php get_footer(); ?>
