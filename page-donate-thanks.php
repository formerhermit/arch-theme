<?php
/**
 * Template Name: Donation Thank You
 *
 * The page Donorbox redirects to after a successful donation. Built
 * assuming the free/Standard Donorbox tier, which means no personalised
 * donor name or amount is passed through — this is deliberately a warm,
 * generic thank-you rather than a personalised one. If ARCH ever upgrades
 * to a Donorbox plan with the Tracking & Analytics add-on, this page can
 * be revisited to pull in the donor's name/amount via URL parameters.
 *
 * @package ARCH
 */

get_header();
?>

<section class="thanks-section">
  <div class="wrap">
    <span class="thanks-icon" aria-hidden="true">🧡</span>
    <h1 class="font-serif">Thank You</h1>
    <p class="thanks-lede">Your donation means the world to us — and to every horse, pony, and donkey in our care. Because of people like you, we can keep giving them the second chance they deserve.</p>

    <blockquote class="thanks-quote">
      Every donation, big or small, goes directly toward feed, veterinary care, and shelter for the animals who need it most.
    </blockquote>

    <div class="thanks-actions">
      <a href="<?php echo esc_url( home_url( '/#equines' ) ); ?>" class="btn btn-primary">Meet the Horses You're Helping</a>
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-outline-dark">Back to Home</a>
    </div>

    <div class="thanks-social">
      <p>Know someone else who'd want to help? Share ARCH:</p>
      <div class="thanks-social-links">
        <a href="https://www.facebook.com/ARCHhorserescuespain" target="_blank" rel="noopener" aria-label="ARCH on Facebook">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M15 3h-2a4 4 0 0 0-4 4v3H7v4h2v7h4v-7h2.6l.7-4H13V7a1 1 0 0 1 1-1h2V3Z"/></svg>
        </a>
        <a href="https://www.instagram.com/archhorserescuespain/" target="_blank" rel="noopener" aria-label="ARCH on Instagram">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.3" cy="6.7" r="0.9" fill="currentColor" stroke="none"/></svg>
        </a>
        <a href="https://x.com/archhorserescue" target="_blank" rel="noopener" aria-label="ARCH on X">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="4" x2="20" y2="20"/><line x1="20" y1="4" x2="4" y2="20"/></svg>
        </a>
      </div>
    </div>
  </div>
</section>

<?php get_footer(); ?>
