<?php
/**
 * Template Name: Donate
 *
 * Assign this to a Page via Page Attributes → Template in wp-admin.
 * Layout: editable intro content → a prominent "donate by card right now"
 * shortcut → a quick jump-nav to every donation method → the full list of
 * methods (bank transfer, shop, fundraising, sponsorship, Teaming, etc.),
 * each managed as its own entry under Donation Methods in wp-admin.
 *
 * @package ARCH
 */

get_header();
?>

<section class="donate-intro">
  <div class="wrap">
    <div class="donate-intro-content">
      <h1 class="font-serif"><?php the_title(); ?></h1>
      <?php
      if ( have_posts() ) :
        while ( have_posts() ) : the_post();
          the_content();
        endwhile;
      else :
        ?>
        <p>Add an introduction here by editing this page's content in wp-admin — whatever context you want people to have before choosing how to give.</p>
        <?php
      endif;
      ?>
    </div>

    <?php
    // Reuses the same Wishlist Items used on the homepage — real items with
    // real prices the charity has already set, rather than inventing a
    // separate set of "suggested amounts" that would need to be kept in
    // sync with the wishlist by hand. See Wishlist Items in wp-admin to
    // add, edit, or reorder what shows here.
    $quick_give_query = new WP_Query( array(
      'post_type'      => 'wishlist_item',
      'posts_per_page' => 4,
      'orderby'        => 'menu_order date',
      'order'          => 'ASC',
    ) );
    ?>

    <?php if ( $quick_give_query->have_posts() ) : ?>
      <div class="donate-quick-give">
        <p class="donate-quick-give-label">Give something specific — pick what to fund:</p>
        <div class="donate-quick-give-grid">
          <?php while ( $quick_give_query->have_posts() ) : $quick_give_query->the_post();
            $price = arch_get_field( 'price' );
            $icon  = arch_wishlist_icon( arch_get_field( 'icon' ) );
          ?>
            <a href="https://donorbox.org/donate-to-arch?amount=<?php echo esc_attr( $price ); ?>" target="_blank" rel="noopener" class="donate-quick-give-tile">
              <span class="donate-quick-give-icon" style="background:<?php echo esc_attr( $icon['bg'] ); ?>;"><?php echo $icon['icon']; // phpcs:ignore -- fixed, theme-defined markup, not user input. ?></span>
              <span class="donate-quick-give-name"><?php the_title(); ?></span>
              <span class="donate-quick-give-price">&euro;<?php echo esc_html( $price ); ?></span>
            </a>
          <?php endwhile; wp_reset_postdata(); ?>
        </div>
      </div>
    <?php endif; ?>

    <div class="donate-quick-cta">
      <a href="https://donorbox.org/donate-to-arch?default_interval=m" target="_blank" rel="noopener" class="btn btn-primary donate-quick-cta-btn">
        Give Any Amount
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
      </a>
      <p class="donate-quick-cta-note">The fastest way to give — takes under a minute. Prefer another way? See all options below.</p>
    </div>
  </div>
</section>

<?php
$methods_query = new WP_Query( array(
  'post_type'      => 'donation_method',
  'posts_per_page' => -1,
  'orderby'        => 'menu_order title',
  'order'          => 'ASC',
) );
?>

<?php if ( $methods_query->have_posts() ) : ?>
  <nav class="donate-jumpnav" aria-label="Ways to give">
    <div class="wrap">
      <?php foreach ( $methods_query->posts as $m ) : ?>
        <a href="#donate-<?php echo esc_attr( $m->post_name ); ?>"><?php echo esc_html( $m->post_title ); ?></a>
      <?php endforeach; ?>
    </div>
  </nav>

  <section class="donate-methods">
    <div class="wrap">
      <?php while ( $methods_query->have_posts() ) : $methods_query->the_post(); ?>
        <?php arch_render_donation_method(); ?>
      <?php endwhile; ?>
    </div>
  </section>
  <?php wp_reset_postdata(); ?>

<?php else : ?>
  <section class="donate-methods">
    <div class="wrap">
      <p class="donate-methods-empty"><?php _e( 'No donation methods have been added yet. Add some from Donation Methods &rarr; Add New in wp-admin.', 'arch' ); ?></p>
    </div>
  </section>
<?php endif; ?>

<?php get_footer(); ?>
