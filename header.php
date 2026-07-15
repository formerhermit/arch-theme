<?php
/**
 * The header for the ARCH theme.
 * Outputs everything up to and including the sticky site nav.
 *
 * @package ARCH
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main-content">Skip to content</a>

<!-- =========================================================
     HEADER
========================================================= -->
<header class="site-header">
  <div class="header-inner">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo-badge">
      <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/arch-logo.png' ); ?>" alt="ARCH — Andalucian Rescue Centre for Horses">
    </a>
    <nav class="primary-nav">
      <?php
      wp_nav_menu( array(
        'theme_location' => 'primary',
        'container'      => false,
        'menu_class'     => 'nav-menu',
        'fallback_cb'    => 'arch_nav_fallback',
      ) );
      ?>
    </nav>
    <button class="search-toggle" id="search-toggle" aria-label="Search" aria-expanded="false">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    </button>
    <button class="menu-toggle" id="menu-toggle" aria-label="Toggle menu">
      <svg id="icon-menu" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="4" y1="6" x2="20" y2="6"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="18" x2="20" y2="18"/></svg>
      <svg id="icon-close" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" style="display:none"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
  </div>
  <div class="search-panel" id="search-panel">
    <form role="search" method="get" class="search-panel-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
      <input type="search" class="search-panel-input" id="search-panel-input" name="s" placeholder="Search horses, news, and more…" value="<?php echo esc_attr( get_search_query() ); ?>">
      <button type="submit" class="btn btn-primary btn-small">Search</button>
    </form>
  </div>
  <div class="mobile-nav" id="mobile-nav">
    <form role="search" method="get" class="mobile-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
      <input type="search" name="s" placeholder="Search horses, news, and more…" value="<?php echo esc_attr( get_search_query() ); ?>">
      <button type="submit" aria-label="Search"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg></button>
    </form>
    <?php
    wp_nav_menu( array(
      'theme_location' => 'primary',
      'container'      => false,
      'menu_class'     => 'nav-menu',
      'fallback_cb'    => 'arch_nav_fallback',
    ) );
    ?>
  </div>
</header>

<main id="main-content">
