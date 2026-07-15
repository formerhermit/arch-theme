<?php
/**
 * Template Name: In Loving Memory
 *
 * A dedicated tribute page for horses with the "In Memoriam" status —
 * deliberately not just the memoriam bucket rendered as another category
 * on the Adoption page's listing. Each horse gets real context here
 * (their story, personality, photo, and years if known), not just a name
 * in a grid, and the whole page uses a quieter, more dignified layout
 * than the rest of the site.
 *
 * @package ARCH
 */

get_header();

$memoriam_query = new WP_Query( array(
	'post_type'      => 'equine',
	'posts_per_page' => -1,
	'meta_query'     => array(
		array(
			'key'   => 'status',
			'value' => 'memoriam',
		),
	),
	'orderby'        => 'title',
	'order'          => 'ASC',
) );
?>

<section class="memoriam-intro">
  <div class="wrap">
    <h1 class="font-serif">In Loving Memory</h1>
    <?php
    if ( have_posts() ) :
      while ( have_posts() ) : the_post();
        the_content();
      endwhile;
    else :
      ?>
      <p>Every horse, pony, and donkey who has come through ARCH's doors has been loved, cared for, and remembered. This page is dedicated to those we've said goodbye to — each one made a difference, and each one is still part of our story.</p>
      <?php
    endif;
    ?>
  </div>
</section>

<?php if ( $memoriam_query->have_posts() ) : ?>
  <section class="memoriam-list">
    <div class="wrap">
      <?php while ( $memoriam_query->have_posts() ) : $memoriam_query->the_post();
        $born_year   = arch_get_field( 'born_year' );
        $passed_year = arch_get_field( 'passed_away_date' );
        $personality = arch_get_field( 'personality' );
        $rescue_story = arch_get_field( 'rescue_story' );

        $years = '';
        if ( $born_year && $passed_year ) {
          $years = esc_html( $born_year ) . ' – ' . esc_html( $passed_year );
        } elseif ( $passed_year ) {
          $years = 'Passed ' . esc_html( $passed_year );
        }
      ?>
        <div class="memoriam-entry<?php echo has_post_thumbnail() ? '' : ' no-photo'; ?>" data-reveal>
          <?php if ( has_post_thumbnail() ) : ?>
            <div class="memoriam-photo">
              <?php the_post_thumbnail( 'large', array( 'alt' => get_the_title() ) ); ?>
            </div>
          <?php endif; ?>
          <div class="memoriam-text">
            <h2 class="font-serif"><?php the_title(); ?></h2>
            <?php if ( $years ) : ?>
              <p class="memoriam-years"><?php echo $years; ?></p>
            <?php endif; ?>
            <?php if ( $personality ) : ?>
              <blockquote class="memoriam-quote"><?php echo esc_html( $personality ); ?></blockquote>
            <?php endif; ?>
            <?php if ( $rescue_story ) : ?>
              <?php echo wpautop( esc_html( wp_trim_words( $rescue_story, 60 ) ) ); ?>
            <?php endif; ?>
            <a href="<?php the_permalink(); ?>" class="memoriam-link">Read <?php the_title(); ?>'s Full Story →</a>
          </div>
        </div>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>
  </section>
<?php else : ?>
  <section class="memoriam-empty">
    <div class="wrap">
      <p><em>There are no horses listed here yet.</em></p>
    </div>
  </section>
<?php endif; ?>

<?php get_footer(); ?>
