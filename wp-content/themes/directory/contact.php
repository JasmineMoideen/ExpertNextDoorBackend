<?php
/* Template Name: Contact
*/
get_header(); ?>

<!-- Breadcrumb Begin -->
<div class="breadcrumb-area set-bg" data-setbg="<?php echo get_template_directory_uri(); ?>/img/breadcrumb/breadcrumb-normal.jpg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="breadcrumb__text">
                    <h2><?php echo get_the_title(); ?></h2>
                    <div class="breadcrumb__option">
                        <a href="<?php echo  get_home_url(); ?>"><i class="fa fa-home"></i> Home</a>
                        <span><?php echo get_the_title(); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb End -->

<!-- Contact Section Begin -->
<section class="contact spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="contact__map">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d423283.43556031643!2d-118.69192431097179!3d34.020730495817475!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x80c2c75ddc27da13%3A0xe22fdf6f254608f4!2sLos%20Angeles%2C%20CA%2C%20USA!5e0!3m2!1sen!2sbd!4v1586670019340!5m2!1sen!2sbd"
                        height="550" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4">
                <div class="contact__widget">
                    <div class="contact__widget__address">
                        <h4>Contact Us</h4>
                        <ul>
                            <li><i class="fa fa-send"></i> <?php echo get_field('address'); ?></li>
                            <li><i class="fa fa-envelope"></i> <?php echo get_field('email_id'); ?></li>
                            <li><i class="fa fa-phone"></i><?php echo get_field('phone_number'); ?></li>
                        </ul>
                    </div>
                    <div class="contact__widget__time">
                        <h4>Opening Hours</h4>
                        <ul>
                            <?php

                            if (have_rows('opening_hours')) {
                                while (have_rows('opening_hours')) {
                                    the_row();


                            ?>
                                    <li><i class="fa fa-clock-o"></i> <?php echo get_sub_field('day_of_the_week'); ?>: <?php echo get_sub_field('time_from'); ?> - <?php echo get_sub_field('time_to'); ?></li>
                            <?php
                                }
                            }
                            ?>

                        </ul>
                    </div>
                </div>
            </div>

            
           <div class="col-lg-8 col-md-8 contact__form">
            <?php  echo do_shortcode('[contact-form-7 id="5163368" title="Contact Form"]')?>
              
            </div> 
        </div>
    </div>
</section>
<!-- Contact Section End -->

<!-- Newslatter Section Begin -->
<section class="newslatter">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <div class="newslatter__text">
                    <h3>Subscribe Newsletter</h3>
                    <p>Subscribe to our newsletter and don’t miss anything</p>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <form action="#" class="newslatter__form">
                    <input type="text" placeholder="Your email">
                    <button type="submit">Subscribe</button>
                </form>
            </div>
        </div>
    </div>
</section>
<!-- Newslatter Section End -->

<?php get_footer(); ?>