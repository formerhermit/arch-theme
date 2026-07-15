<?php
/**
 * 404 error page — deliberately a little playful, since a broken link
 * isn't a distressing topic the way the Report an Equine page is. Reuses
 * the horseshoe icon already used on News cards, so it's not a one-off
 * new visual element.
 *
 * @package ARCH
 */

get_header();
?>

<section class="error-404-section">
  <div class="wrap">
    <div class="error-404-number">404</div>
    <div class="error-404-badge">
      <svg class="error-404-badge-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M6.5 21c-1.4-4.2.2-9.6 2.9-12.2C11.3 6.9 12.7 6.9 14.6 8.8c2.7 2.6 4.3 8 2.9 12.2"/></svg>
      Page not found
    </div>
    <h1 class="font-serif error-404-heading">This one's escaped the paddock.</h1>
    <p class="error-404-text">The page you're looking for may have moved, been renamed, or wandered off somewhere we can't quite reach. Let's get you back on the trail.</p>
    <div class="error-404-actions">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary">Back to Home</a>
      <a href="<?php echo esc_url( home_url( '/#equines' ) ); ?>" class="btn btn-outline">Meet the Equines</a>
      <a href="<?php echo esc_url( home_url( '/#contact' ) ); ?>" class="btn btn-outline">Contact Us</a>
    </div>
  </div>
</section>

<?php get_footer(); ?>
