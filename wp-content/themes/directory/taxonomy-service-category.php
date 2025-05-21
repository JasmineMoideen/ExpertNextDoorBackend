<!-- Header Section End -->

<?php 
/**
 * Template Name: Service Category Detail Page
 */
get_header(); 
$term = get_queried_object();

$service_id = get_field('service_id', 'term_' . $term->term_id);

?>


<!-- Listing Section Begin -->

<section class="listing-hero set-bg" data-setbg="<?php echo esc_url(get_template_directory_uri()); ?>/img/listing/details/listing-hero.jpg">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="listing__hero__option">
                    <div class="listing__hero__icon">
                        <img src="img/listing/details/ld-icon.png" alt="">
                    </div>
                    <div class="listing__hero__text">
                        <h2><?php echo $term->name; ?></h2>
                        
                      
                        

                        <div class="listing__hero__widget">


                        </div>
                        <p><span class="icon_pin_alt"></span> <?php echo $term->description; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="listing__hero__btns">

                <a href="<?php echo get_site_url(); ?>/service-appointment-booking/?location_id=1&service_id=<?php echo $service_id; ?>&user_id=3" class="primary-btn">
    <i class="fa fa-calendar"></i> Book Appointment
</a>

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
                                    src="<?php echo get_template_directory_uri(); ?>/img/listing/details/listing-details-1.jpg" alt="">
                                <span><i class="fa fa-camera"></i> 170 Image</span>
                            </div>


                            <div class="listing__details__gallery__slider owl-carousel">
                                <div><img src="<?php echo get_template_directory_uri(); ?>/img/listing/details/thumb-1.jpg" alt=""></div>
                                <div><img src="<?php echo get_template_directory_uri(); ?>/img/listing/details/thumb-2.jpg" alt=""></div>
                                <div><img src="<?php echo get_template_directory_uri(); ?>/img/listing/details/thumb-3.jpg" alt=""></div>
                                <div><img src="<?php echo get_template_directory_uri(); ?>/img/listing/details/thumb-4.jpg" alt=""></div>
                            </div>



                        </div>
                    </div>
                    <div class="listing__details__amenities">
                        <h4>Services</h4>
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-6">
                                <div class="listing__details__amenities__item">
                                    <img src="<?php echo get_template_directory_uri(); ?>/img/listing/details/amenities/ame-1.png" alt="">
                                    <h6>Light fixture</h6>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-3 col-6">
                                <div class="listing__details__amenities__item">
                                    <img src="<?php echo get_template_directory_uri(); ?>/img/listing/details/amenities/ame-2.png" alt="">
                                    <h6> Ceiling fan installation</h6>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-3 col-6">
                                <div class="listing__details__amenities__item">
                                    <img src="<?php echo get_template_directory_uri(); ?>/img/listing/details/amenities/ame-3.png" alt="">
                                    <h6>Fuse box upgrades</h6>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-6">
                                <div class="listing__details__amenities__item">
                                    <img src="<?php echo get_template_directory_uri(); ?>/img/listing/details/amenities/ame-4.png" alt="">
                                    <h6>Automation systems</h6>
                                </div>
                            </div>
                                                        <div class="col-lg-3 col-md-3 col-6">
                                <div class="listing__details__amenities__item">
                                    <img src="<?php echo get_template_directory_uri(); ?>/img/listing/details/amenities/ame-1.png" alt="">
                                    <h6>Light fixture</h6>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-3 col-6">
                                <div class="listing__details__amenities__item">
                                    <img src="<?php echo get_template_directory_uri(); ?>/img/listing/details/amenities/ame-2.png" alt="">
                                    <h6> Ceiling fan installation</h6>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-3 col-6">
                                <div class="listing__details__amenities__item">
                                    <img src="<?php echo get_template_directory_uri(); ?>/img/listing/details/amenities/ame-3.png" alt="">
                                    <h6>Fuse box upgrades</h6>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-6">
                                <div class="listing__details__amenities__item">
                                    <img src="<?php echo get_template_directory_uri(); ?>/img/listing/details/amenities/ame-4.png" alt="">
                                    <h6>Automation systems</h6>
                                </div>
                            </div>






                        </div>
                    </div>



                </div>
            </div>
            <div class="col-lg-4">
                <div class="listing__sidebar">
                    <div class="listing__sidebar__contact">

                        <div class="listing__sidebar__contact__text">
                            <h4>Contact Information</h4>
                            <ul>
                                <li><span class="icon_pin_alt"></span> 236 Littleton St. New Philadelphia, Ohio,
                                    United States</li>
                                <li><span class="icon_phone"></span> (+12) 345-678-910</li>
                                <li><span class="icon_mail_alt"></span> Info.colorlib@gmail.com</li>

                            </ul>

                        </div>
                    </div>
                    <div class="listing__sidebar__working__hours">
                        <h4>Working Hours</h4>
                        <ul>
                            <li>Monday <span>09:00 AM - 20:00 PM</span></li>
                            <li>Tuesday <span>09:00 AM - 20:00 PM</span></li>
                            <li>Wednesday <span>09:00 AM - 20:00 PM</span></li>
                            <li>Thursday <span>09:00 AM - 20:00 PM</span></li>
                            <li>Friday <span class="opening">Opening</span></li>
                            <li>Saturday <span>09:00 AM - 20:00 PM</span></li>
                            <li>Saturday <span class="closed">Closed</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Listing Details Section End -->


<!-- Service Providers Section Begin -->
<section class="service-providers">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h3 class="section-title">Available Service Provider Profiles</h3>
            </div>
            <?php
        



        

        $args = array(
            'post_type' => 'user-profile',
            'order' => 'ASC',
            'tax_query' => array(
                array(
                    'taxonomy' => 'service-category',
                    'field' => 'slug',
                    'terms' => $term->slug,
                ),
            ),
        );


        $query = new WP_Query($args);



        ?>

            <?php if ($query->have_posts()) : ?>
                <?php while ($query->have_posts()) : $query->the_post(); ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="provider-card">
                            <div class="provider-card__image">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('medium'); ?>
                                <?php else : ?>
                                    <img src="<?php echo get_field('profile_image');?>" alt="">
                                <?php endif; ?>
                            </div>
                            <div class="provider-card__content">
                                <h5><?php the_title(); ?></h5>
                                <p><?php echo wp_trim_words(get_field('user_bio'), 20); ?></p>
                                <a href="<?php the_permalink(); ?>" class="primary-btn small">View Profile</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; wp_reset_postdata(); ?>
            <?php else : ?>
                <div class="col-lg-12">
                    <p>No service providers found in this category.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<!-- Service Providers Section End -->







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


