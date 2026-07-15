<?php
/**
 * The front page template for the ARCH theme.
 *
 * This is a single-page design, so the whole site lives on the front page:
 * header.php (nav) -> template-parts/content-home.php (all sections) -> footer.php
 *
 * @package ARCH
 */

get_header();
get_template_part( 'template-parts/content', 'home' );
get_footer();
