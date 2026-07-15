<?php
/**
 * The blog/News archive template.
 *
 * WordPress uses this automatically once Settings → Reading → "Posts page"
 * is set to a Page — no page template assignment needed, unlike the
 * Adoption page. This is the standard WordPress way a "blog index" works.
 *
 * @package ARCH
 */

get_header();
?>

<section class="news-archive-header">
  <div class="wrap">
    <p class="eyebrow">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M6.5 21c-1.4-4.2.2-9.6 2.9-12.2C11.3 6.9 12.7 6.9 14.6 8.8c2.7 2.6 4.3 8 2.9 12.2" stroke="#9A452F" stroke-width="2.1" stroke-linecap="round"/><circle cx="7.3" cy="19.2" r="0.55" fill="#9A452F"/><circle cx="7.9" cy="16.6" r="0.55" fill="#9A452F"/><circle cx="9.1" cy="14.2" r="0.55" fill="#9A452F"/><circle cx="14.9" cy="14.2" r="0.55" fill="#9A452F"/><circle cx="16.1" cy="16.6" r="0.55" fill="#9A452F"/><circle cx="16.7" cy="19.2" r="0.55" fill="#9A452F"/></svg>
      Tales From ARCH
    </p>
    <h1 class="font-serif" style="margin-top:8px; color:var(--forest-deep); font-size:clamp(2rem,4vw,2.75rem);">All the news</h1>
  </div>
</section>

<section class="news-archive">
  <div class="wrap">
    <?php if ( have_posts() ) : ?>
      <div class="news-grid">
        <?php while ( have_posts() ) : the_post(); ?>
          <?php arch_render_news_card(); ?>
        <?php endwhile; ?>
      </div>

      <?php
      the_posts_pagination( array(
        'prev_text' => '&larr; Newer',
        'next_text' => 'Older &rarr;',
        'class'     => 'news-pagination',
      ) );
      ?>

    <?php else : ?>
      <p class="news-archive-empty"><?php _e( 'No news posts yet. Write one from Posts &rarr; Add New in wp-admin.', 'arch' ); ?></p>
    <?php endif; ?>
  </div>
</section>

<?php get_footer(); ?>
