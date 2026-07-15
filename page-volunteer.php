<?php
/**
 * Template Name: Volunteer
 *
 * A dedicated volunteer page, replacing the generic "Content Page with
 * Button" template previously used here. Structured facts (hours, apply
 * link, optional video) are ACF fields; the main narrative is normal page
 * content. Assign this template to the existing Volunteer page in
 * wp-admin (Page Attributes → Template) to switch it over.
 *
 * @package ARCH
 */

get_header();

$apply_url = arch_get_field( 'volunteer_apply_url' );
if ( ! $apply_url ) {
	$apply_url = home_url( '/#contact' );
}
$video_url = arch_get_field( 'volunteer_video_url' );
?>

<section class="volunteer-intro">
  <div class="wrap">
    <div class="volunteer-intro-content">
      <h1 class="font-serif"><?php the_title(); ?></h1>
      <?php
      if ( have_posts() ) :
        while ( have_posts() ) : the_post();
          the_content();
        endwhile;
      else :
        ?>
        <p>We rely on the support of animal lovers across the globe, and it is with your help that we continue to provide relief to horses, donkeys and ponies in need.</p>
        <p>Enjoy some meaningful work at the rescue centre. Donate a few hours of your time to help us groom, feed and muck out the paddocks — there is always plenty to do.</p>
        <?php
      endif;
      ?>
    </div>
  </div>
</section>

<section class="volunteer-ways">
  <div class="wrap">
    <div class="volunteer-ways-grid">
      <div class="volunteer-way-card">
        <span class="volunteer-way-icon" aria-hidden="true">🐴</span>
        <h3 class="font-serif">Rescue Centre</h3>
        <p>Grooming, feeding, and mucking out at the rescue centre — always plenty to do.</p>
      </div>
      <div class="volunteer-way-card">
        <span class="volunteer-way-icon" aria-hidden="true">📣</span>
        <h3 class="font-serif">Marketing & Events</h3>
        <p>Hours and commitment vary — get in touch to see what's currently needed.</p>
      </div>
      <div class="volunteer-way-card">
        <span class="volunteer-way-icon" aria-hidden="true">🛍️</span>
        <h3 class="font-serif">Charity Shop</h3>
        <p>The ARCH Charity Shop in Alhaurín is periodically looking for new volunteers too.</p>
        <a href="<?php echo esc_url( arch_get_shop_page_url() ); ?>" class="btn btn-outline-dark btn-small">About the Shop</a>
      </div>
    </div>
  </div>
</section>

<section class="volunteer-cta">
  <div class="wrap">
    <a href="<?php echo esc_url( $apply_url ); ?>" class="btn btn-primary">Apply to Volunteer</a>
  </div>
</section>

<section class="volunteer-hours">
  <div class="wrap">
    <div class="volunteer-hours-box">
      <h2 class="font-serif">Volunteering at the Rescue Centre</h2>
      <div class="volunteer-hours-grid">
        <div class="info-row">
          <div class="icon-circle"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg></div>
          <div><h4>Mornings</h4><p>Mon–Fri, 8:30am–11am. Sat–Sun, 9am–11am.</p></div>
        </div>
        <div class="info-row">
          <div class="icon-circle"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79Z"/></svg></div>
          <div><h4>Evenings</h4><p>Times vary seasonally — usually a 2hr shift between 6pm and 9pm.</p></div>
        </div>
        <div class="info-row">
          <div class="icon-circle"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
          <div><h4>Visitor Tours</h4><p>1st Sunday of the month (except July &amp; August), 10am–2pm — two 2hr slots, 10–12 or 12–2.</p></div>
        </div>
        <div class="info-row">
          <div class="icon-circle"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2Z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></div>
          <div><h4>Accommodation</h4><p>We don't have accommodation on site, so volunteers from further afield will need to arrange their own accommodation and transport.</p></div>
        </div>
      </div>
      <div class="volunteer-first-visit">
        <h4>Your first visit</h4>
        <p>Come along on a Wednesday or Saturday, 9–11am. You'll meet other volunteers and staff, and get a full tour of the facilities.</p>
        <p class="volunteer-contact-note">For questions, email volunteer@horserescuespain.org or use the Apply to Volunteer button above.</p>
      </div>
    </div>
  </div>
</section>

<?php if ( $video_url ) : ?>
  <section class="volunteer-video">
    <div class="wrap">
      <h2 class="font-serif">See What It's Like</h2>
      <div class="volunteer-video-embed">
        <?php echo wp_oembed_get( esc_url( $video_url ) ); ?>
      </div>
    </div>
  </section>
<?php endif; ?>

<?php
$gallery_query = new WP_Query( array(
	'post_type'      => 'volunteer_photo',
	'posts_per_page' => -1,
	'orderby'        => 'menu_order date',
	'order'          => 'ASC',
) );
?>
<?php if ( $gallery_query->have_posts() ) : ?>
  <section class="volunteer-gallery">
    <div class="wrap">
      <h2 class="font-serif">Life at the Rescue Centre</h2>
      <div class="volunteer-gallery-grid">
        <?php while ( $gallery_query->have_posts() ) : $gallery_query->the_post(); ?>
          <div class="volunteer-gallery-item">
            <?php the_post_thumbnail( 'medium_large', array( 'alt' => get_the_title() ? get_the_title() : 'Volunteering at ARCH' ) ); ?>
          </div>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>
    </div>
  </section>
<?php endif; ?>

<?php get_footer(); ?>
