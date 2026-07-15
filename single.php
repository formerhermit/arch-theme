<?php
/**
 * Single News post template.
 *
 * WordPress uses this automatically for any individual blog post URL.
 * Supports full block editor content — text, images, galleries, and
 * video (self-hosted via the Video block, or just pasting a YouTube/Vimeo
 * URL on its own line for an automatic embed).
 *
 * @package ARCH
 */

get_header();

while ( have_posts() ) :
	the_post();
	?>

	<section class="single-post-header">
		<div class="wrap">
			<a href="<?php echo esc_url( arch_get_news_page_url() ); ?>" class="single-post-back">
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
				All News
			</a>
			<div class="single-post-date"><?php echo esc_html( get_the_date( 'F j, Y' ) ); ?></div>
			<h1 class="single-post-title"><?php the_title(); ?></h1>
		</div>
	</section>

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="single-post-featured-image">
			<?php the_post_thumbnail( 'large', array( 'alt' => get_the_title() ) ); ?>
		</div>
	<?php endif; ?>

	<section class="single-post-body">
		<div class="wrap">
			<div class="single-post-content">
				<?php the_content(); ?>
			</div>
		</div>
	</section>

	<?php
endwhile;

get_footer();
