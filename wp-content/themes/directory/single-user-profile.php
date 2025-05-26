<?php

/**
 * Template Name: User Detail Page
 */
get_header(); ?>

<!-- Listing Section Begin -->
<section class="listing-hero set-bg" data-setbg="<?php echo esc_url(get_template_directory_uri()); ?>/img/listing/details/listing-hero.jpg">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="listing__hero__option">
                    <div class="listing__hero__icon">
                        <img src="<?php echo get_field('user_icon'); ?>" alt="">
                    </div>
                    <div class="listing__hero__text">
                        <h2><?php echo get_field('user_name'); ?></h2>
                        <?php $current_user_id = get_field('user_id'); ?>
                        <div class="listing__hero__widget">


                        </div>
                        <p><span class="icon_pin_alt"></span> <?php echo get_field('user_location'); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="listing__hero__btns">

                    <a href="<?php echo get_site_url() ?>/user-appointment-booking/?user_id=<?php echo $current_user_id; ?>" class="primary-btn"><i class="fa fa-calendar"></i> Book Appointment</a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Listing Section End -->

<!-- Listing Details Section Begin -->
<section class="listing-details spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="listing__details__text">
                    <div class="listing__details__about">
                        <h4>Overview</h4>
                        <?php echo get_field('user_description'); ?>
                    </div>
                    <div class="listing__details__gallery">
                        <h4>Gallery</h4>
                        <div class="listing__details__gallery__pic">
                            <div class="listing__details__gallery__item">
                                <img class="listing__details__gallery__item__large"
                                    src="<?php echo get_field('background_image'); ?>" alt="">
                                <span><i class="fa fa-camera"></i> 170 Image</span>
                            </div>


                            <div class="listing__details__gallery__slider owl-carousel">
                                <?php

                                if (have_rows('user_portfolio_images')) {
                                    while (have_rows('user_portfolio_images')) {
                                        the_row();


                                ?>
                                        <div><img src="<?php echo get_sub_field('portfolio_image'); ?>" alt=""></div>

                                <?php
                                    }
                                }
                                ?>
                            </div>



                        </div>
                    </div>
                    <div class="listing__details__amenities">
                        <h4>Services</h4>
                        <div class="row">
                            <?php

                            if (have_rows('specific_services')) {
                                while (have_rows('specific_services')) {
                                    the_row();


                            ?>
                                    <div class="col-lg-3 col-md-3 col-6">
                                        <div class="listing__details__amenities__item">
                                            <img src=" <?php echo get_template_directory_uri(); ?>/img/listing/details/amenities/ame-8.png" alt="">
                                            <h6><?php echo get_sub_field('service_name');?></h6>
                                        </div>
                                    </div>
                            <?php
                                }
                            }
                            ?>








                        </div>
                    </div>

                    <div class="listing__details__comment">
                        <h4>Comment</h4>

                        <?php comments_template(); ?>

                    </div>

                </div>
            </div>
            <div class="col-lg-4">
                <div class="listing__sidebar">
                    <div class="listing__sidebar__contact">

                        <div class="listing__sidebar__contact__text">
                            <h4>Contact Information</h4>
                            <ul>
                                <li><span class="icon_pin_alt"></span> <?php echo get_field('user_location');?></li>
                                <li><span class="icon_phone"></span> <?php echo get_field('user_phone');?></li>
                                <li><span class="icon_mail_alt"></span> <?php echo get_field('user_email');?></li>

                            </ul>

                        </div>
                    </div>
                    <div class="listing__sidebar__working__hours">
                        <h4>Working Hours</h4>
                        <?php
                        $schedule = get_field('schedule');
                        ?>

                        <p><?php echo $schedule['day_of_week'];?><br><span><?php echo $schedule['time_from'];?> - <?php echo $schedule['time_to'];?></span></p>


                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Listing Details Section End -->

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