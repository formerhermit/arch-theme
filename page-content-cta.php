<?php
/**
 * Template Name: Content Page with Button
 *
 * A generic, reusable page template — plain editable content (normal
 * WordPress page content, full block editor) with an OPTIONAL call-to-action
 * button underneath. Both the button's text and its destination are set
 * per-page under "Page Button Settings" in wp-admin; leave the Button Link
 * field blank and no button shows at all — just the content.
 *
 * Apply this to as many pages as you like for whatever purpose — Volunteer,
 * an FAQ page, a partnership info page, anything that's "some content, maybe
 * a button." Because it's reusable across multiple pages, this template
 * deliberately does NOT have its own "find the page using this template"
 * lookup function the way Adoption/Donate/News do — there could be several
 * pages using it at once, so there's no single answer to "which one." If a
 * specific page built with this template needs other parts of the site to
 * link to it directly (like Volunteer does), that page is instead found by
 * its URL slug — see arch_get_volunteer_page_url() in functions.php for
 * how that's done for the Volunteer page specifically.
 *
 * @package ARCH
 */

get_header();

$cta_url   = arch_get_field( 'cta_url' );
$cta_label = arch_get_field( 'cta_label' );
if ( ! $cta_label ) {
	$cta_label = 'Learn More';
}
?>

<section class="generic-page-intro">
  <div class="wrap">
    <div class="adoption-intro-content">
      <h1 class="font-serif"><?php the_title(); ?></h1>
      <?php
      if ( have_posts() ) :
        while ( have_posts() ) : the_post();
          the_content();
        endwhile;
      else :
        // Fallback shown until someone writes real content for this page.
        ?>
        <p>Add your content here by editing this page in wp-admin.</p>
        <?php
      endif;
      ?>
    </div>
    <?php if ( $cta_url ) : ?>
      <div class="adoption-intro-cta">
        <a href="<?php echo esc_url( $cta_url ); ?>" target="_blank" rel="noopener" class="btn btn-primary"><?php echo esc_html( $cta_label ); ?></a>
      </div>
    <?php endif; ?>
  </div>
</section>

<?php get_footer(); ?>
