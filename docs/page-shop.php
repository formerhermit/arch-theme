<?php
/**
 * Template Name: Charity Shop
 *
 * A dedicated page for ARCH's physical charity shop — one location, no
 * online checkout (their Facebook page lists items informally, so that's
 * linked to honestly as "see what's in the shop", not sold as a proper
 * online shop). Structured facts (address, hours, phone, email, links) are
 * ACF fields so they can be updated without touching code; the main
 * narrative is normal page content.
 *
 * @package ARCH
 */

get_header();

$address    = arch_get_field( 'shop_address' );
$maps_url   = arch_get_field( 'shop_maps_url' );
$hours      = arch_get_field( 'shop_hours' );
$phone      = arch_get_field( 'shop_phone' );
$email      = arch_get_field( 'shop_email' );
$fb_url     = arch_get_field( 'shop_facebook_url' );
$accepted   = arch_get_field( 'shop_accepted_items' );
$photo      = arch_get_field( 'shop_photo' );
?>

<section class="shop-intro">
  <div class="wrap">
    <div class="shop-hero<?php echo $photo ? ' has-photo' : ''; ?>">
      <div class="shop-intro-content">
        <h1 class="font-serif"><?php the_title(); ?></h1>
        <?php
        if ( have_posts() ) :
          while ( have_posts() ) : the_post();
            the_content();
          endwhile;
        else :
          ?>
          <p>Our charity shop in Alhaurín el Grande provides a vital income that directly supports the equines at the ARCH Rescue Centre.</p>
          <?php
        endif;
        ?>

        <div class="shop-cta-row">
          <?php if ( $maps_url ) : ?>
            <a href="<?php echo esc_url( $maps_url ); ?>" target="_blank" rel="noopener" class="btn btn-primary">Get Directions</a>
          <?php endif; ?>
          <?php if ( $fb_url ) : ?>
            <a href="<?php echo esc_url( $fb_url ); ?>" target="_blank" rel="noopener" class="btn btn-outline-dark">See What's in the Shop</a>
          <?php endif; ?>
        </div>

        <blockquote class="shop-kindness-note">
          🧡 Every visit, every donation, every purchase — it all adds up to a kinder life for the horses in our care.
        </blockquote>
      </div>

      <?php if ( $photo ) : ?>
        <div class="shop-hero-photo">
          <img src="<?php echo esc_url( $photo ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>">
          <span class="sticker shop-hero-sticker" style="background:var(--hay); color:var(--forest-deep); transform:rotate(-4deg);">Thank you for shopping with love</span>
        </div>
      <?php endif; ?>
    </div>

    <div class="shop-info-grid">
      <?php if ( $address ) : ?>
        <div class="info-row">
          <div class="icon-circle"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg></div>
          <div><h4>Address</h4><p><?php echo esc_html( $address ); ?></p></div>
        </div>
      <?php endif; ?>
      <?php if ( $hours ) : ?>
        <div class="info-row">
          <div class="icon-circle"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
          <div><h4>Opening Hours</h4><p><?php echo esc_html( $hours ); ?></p></div>
        </div>
      <?php endif; ?>
      <?php if ( $phone ) : ?>
        <div class="info-row">
          <div class="icon-circle"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92Z"/></svg></div>
          <div><h4>Phone / Messenger</h4><p><?php echo esc_html( $phone ); ?></p></div>
        </div>
      <?php endif; ?>
      <?php if ( $email ) : ?>
        <div class="info-row">
          <div class="icon-circle"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 6-10 7L2 6"/></svg></div>
          <div><h4>Email</h4><p><?php echo esc_html( $email ); ?></p></div>
        </div>
      <?php endif; ?>
    </div>

    <?php if ( $accepted ) : ?>
      <div class="shop-accepted">
        <h2 class="font-serif">Donating Goods</h2>
        <?php echo wpautop( esc_html( $accepted ) ); ?>
      </div>
    <?php endif; ?>

    <div class="shop-volunteer-note">
      <h2 class="font-serif">🧡 Volunteer at the Shop</h2>
      <p>Another way to support the ARCH Charity Shop is by joining our team of volunteers for a few hours a week.</p>
      <a href="<?php echo esc_url( arch_get_volunteer_page_url() ); ?>" class="btn btn-outline-dark">Find Out About Volunteering</a>
    </div>
  </div>
</section>

<?php get_footer(); ?>
