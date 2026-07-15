<?php
/**
 * Search results — groups results by type (Equines first, since that's
 * the content people are most likely searching for, then News, then
 * everything else) and reuses the existing card renderers so results
 * look consistent with the rest of the site rather than a generic list.
 *
 * @package ARCH
 */

get_header();

$query      = get_search_query();
$equines    = array();
$news_posts = array();
$other      = array();

if ( have_posts() ) :
	while ( have_posts() ) : the_post();
		if ( get_post_type() === 'equine' ) {
			$equines[] = get_the_ID();
		} elseif ( get_post_type() === 'post' ) {
			$news_posts[] = get_the_ID();
		} else {
			$other[] = get_the_ID();
		}
	endwhile;
endif;
$total = count( $equines ) + count( $news_posts ) + count( $other );
?>

<section class="search-results-intro">
  <div class="wrap">
    <h1 class="font-serif">Search Results</h1>
    <p class="search-results-count">
      <?php if ( $total > 0 ) : ?>
        <?php echo esc_html( $total ); ?> result<?php echo $total === 1 ? '' : 's'; ?> for "<?php echo esc_html( $query ); ?>"
      <?php else : ?>
        No results for "<?php echo esc_html( $query ); ?>"
      <?php endif; ?>
    </p>

    <form role="search" method="get" class="search-results-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
      <input type="search" name="s" placeholder="Try a different search…" value="<?php echo esc_attr( $query ); ?>">
      <button type="submit" class="btn btn-primary btn-small">Search</button>
    </form>
  </div>
</section>

<?php if ( ! empty( $equines ) ) : ?>
  <section class="search-results-group">
    <div class="wrap">
      <h2 class="font-serif">Equines</h2>
      <div class="equine-grid">
        <?php foreach ( $equines as $post_id ) : ?>
          <?php
          global $post;
          $post = get_post( $post_id );
          setup_postdata( $post );
          arch_render_equine_card();
          ?>
        <?php endforeach; wp_reset_postdata(); ?>
      </div>
    </div>
  </section>
<?php endif; ?>

<?php if ( ! empty( $news_posts ) ) : ?>
  <section class="search-results-group">
    <div class="wrap">
      <h2 class="font-serif">News</h2>
      <div class="news-grid">
        <?php foreach ( $news_posts as $post_id ) : ?>
          <?php
          global $post;
          $post = get_post( $post_id );
          setup_postdata( $post );
          arch_render_news_card();
          ?>
        <?php endforeach; wp_reset_postdata(); ?>
      </div>
    </div>
  </section>
<?php endif; ?>

<?php if ( ! empty( $other ) ) : ?>
  <section class="search-results-group">
    <div class="wrap">
      <h2 class="font-serif">Pages</h2>
      <div class="search-results-list">
        <?php foreach ( $other as $post_id ) : ?>
          <?php
          global $post;
          $post = get_post( $post_id );
          setup_postdata( $post );
          ?>
          <a href="<?php the_permalink(); ?>" class="search-results-item">
            <h3 class="font-serif"><?php the_title(); ?></h3>
            <p><?php echo esc_html( arch_short_excerpt( 24 ) ); ?></p>
          </a>
        <?php endforeach; wp_reset_postdata(); ?>
      </div>
    </div>
  </section>
<?php endif; ?>

<?php if ( $total === 0 ) : ?>
  <section class="search-results-empty">
    <div class="wrap">
      <p>Try a different search term, or browse:</p>
      <div class="search-results-empty-links">
        <a href="<?php echo esc_url( home_url( '/#equines' ) ); ?>" class="btn btn-outline-dark btn-small">All Equines</a>
        <a href="<?php echo esc_url( arch_get_news_page_url() ); ?>" class="btn btn-outline-dark btn-small">News</a>
        <a href="<?php echo esc_url( home_url( '/#contact' ) ); ?>" class="btn btn-outline-dark btn-small">Contact Us</a>
      </div>
    </div>
  </section>
<?php endif; ?>

<?php get_footer(); ?>
