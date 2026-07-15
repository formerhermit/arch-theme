<?php
/**
 * The main template file — required by every WordPress theme.
 *
 * WordPress always needs an index.php as the ultimate fallback template.
 * Since this is a single-page design, it renders the same content as
 * front-page.php so the site still works correctly even if "Settings ->
 * Reading -> Your homepage displays" isn't configured to a static front page.
 *
 * @package ARCH
 */

get_header();
get_template_part( 'template-parts/content', 'home' );
get_footer();
