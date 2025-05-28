<?php
/* Template Name: Faq
*/
get_header('listing'); ?>

<div class="faq-container">
    <h1 class="faq-title">Frequently Asked Questions</h1>

    <?php

    if (have_rows('faq')) {
        while (have_rows('faq')) {
            the_row();


    ?>

            <div class="faq-item">
                <div class="faq-question"><?php echo get_sub_field('faq_question');?></div>
                <div class="faq-answer"><?php echo get_sub_field('faq_answer');?></div>
            </div>

    <?php
        }
    }
    ?>





</div>
<script>
    const questions = document.querySelectorAll('.faq-question');
    questions.forEach(q => {
        q.addEventListener('click', () => {
            q.classList.toggle('active');
        });
    });
</script>

<?php get_footer(); ?>