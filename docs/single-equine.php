<?php
/**
 * Single Equine page template.
 *
 * WordPress uses this automatically for any individual equine's URL
 * (e.g. /equines/torero/) — no page template assignment needed, this is
 * standard WordPress template hierarchy for a custom post type.
 *
 * Includes Open Graph / Twitter Card meta tags (see arch_equine_social_meta()
 * in functions.php) so sharing the link shows the horse's actual photo and
 * description, and share buttons using each platform's own share-link
 * scheme — no plugin, no external JS.
 *
 * @package ARCH
 */

get_header();

while ( have_posts() ) :
	the_post();

	$status         = arch_get_field( 'status' );
	$status         = $status ? $status : 'available';
	$status_meta    = arch_equine_status_meta( $status );
	$meta_line      = arch_get_field( 'meta_line' );
	$is_adoptable   = in_array( $status, array( 'available', 'foster' ), true );
	$is_memoriam    = ( $status === 'memoriam' );
	$share_url      = get_permalink();
	$share_title    = get_the_title() . ' — ARCH';

	// Structured detail fields — every one is optional, so each row below
	// only renders if that particular field has been filled in.
	$sex            = arch_get_field( 'sex' );
	$sex_labels     = array( 'mare' => 'Mare', 'gelding' => 'Gelding', 'stallion' => 'Stallion' );
	$sex_label      = isset( $sex_labels[ $sex ] ) ? $sex_labels[ $sex ] : '';
	$breed          = arch_get_field( 'breed' );
	$height_cm      = arch_get_field( 'height_cm' );
	$born_year      = arch_get_field( 'born_year' );
	$age            = arch_equine_age( $born_year );
	$rescue_date    = arch_get_field( 'rescue_date' );
	$favourite_food = arch_get_field( 'favourite_food' );
	$dislikes       = arch_get_field( 'dislikes' );
	$quirk          = arch_get_field( 'quirk' );
	$personality    = arch_get_field( 'personality' );
	$rescue_picture = arch_get_field( 'rescue_picture' );
	$rescue_story   = arch_get_field( 'rescue_story' );
	$story_extract  = arch_equine_story_extract( $rescue_story );
	// The hero quote prefers a short pull from the Rescue Story — it's the
	// stronger emotional hook right at the top of the page. Personality
	// only shows there as a fallback for any horse that doesn't have a
	// Rescue Story written yet, so the hero box is never left empty.
	$hero_quote     = $story_extract ? $story_extract : $personality;

	// Facts strip — built from whichever structured fields are actually set.
	$facts = array();
	if ( $age !== null ) {
		$facts[] = array( 'icon' => '🎂', 'label' => $age . ' years old' );
	}
	if ( $sex_label ) {
		$facts[] = array( 'icon' => ( $sex === 'mare' ? '♀' : '♂' ), 'label' => $sex_label );
	}
	if ( $breed ) {
		$facts[] = array( 'icon' => '🐴', 'label' => $breed );
	}
	if ( $height_cm ) {
		$facts[] = array( 'icon' => '📏', 'label' => $height_cm . 'cm' );
	}

	// Optional "getting to know" cards — only appears at all if at least one is filled in.
	$about_items = array();
	if ( $favourite_food ) {
		$about_items[] = array( 'icon' => '🥕', 'label' => 'Favourite treat', 'short' => 'Treat', 'value' => $favourite_food );
	}
	if ( $dislikes ) {
		$about_items[] = array( 'icon' => '🌧️', 'label' => 'Dislikes', 'short' => 'Dislikes', 'value' => $dislikes );
	}
	if ( $quirk ) {
		$about_items[] = array( 'icon' => '✨', 'label' => 'Quirk', 'short' => 'Quirk', 'value' => $quirk );
	}
	?>

	<section class="equine-hero<?php echo $is_memoriam ? ' is-memoriam' : ''; ?>">
		<div class="equine-hero-grid wrap">
			<div class="equine-hero-info">
				<a href="<?php echo esc_url( arch_get_adoption_page_url() ); ?>" class="single-post-back">
					<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
					All Equines
				</a>

				<span class="equine-hero-status <?php echo esc_attr( $status_meta['class'] ); ?>"><?php echo esc_html( $status_meta['label'] ); ?></span>

				<h1 class="font-serif equine-hero-name"><?php the_title(); ?></h1>

				<?php if ( $meta_line ) : ?>
					<p class="equine-hero-meta-line"><?php echo esc_html( $meta_line ); ?></p>
				<?php endif; ?>

				<?php if ( $facts ) : ?>
					<div class="equine-facts-row">
						<?php foreach ( $facts as $fact ) : ?>
							<span class="equine-fact"><span class="equine-fact-icon" aria-hidden="true"><?php echo esc_html( $fact['icon'] ); ?></span><?php echo esc_html( $fact['label'] ); ?></span>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<?php if ( $rescue_date ) : ?>
					<div class="equine-rescue-line">
						<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78Z"/></svg>
						Rescued: <?php echo esc_html( $rescue_date ); ?>
					</div>
				<?php endif; ?>

				<?php if ( $hero_quote ) : ?>
					<blockquote class="equine-personality">
						<?php echo esc_html( $hero_quote ); ?>
					</blockquote>
				<?php endif; ?>

				<div class="equine-hero-cta">
					<?php if ( $is_adoptable ) : ?>
						<a href="<?php echo esc_url( arch_get_adoption_form_url() ); ?>" target="_blank" rel="noopener" class="btn btn-hay">Adopt Me</a>
					<?php endif; ?>
					<?php if ( ! $is_memoriam ) : ?>
						<a href="<?php echo esc_url( home_url( '/#contact' ) ); ?>" class="btn btn-primary">Sponsor Me</a>
					<?php endif; ?>
					<a href="<?php echo esc_url( arch_get_donate_page_url() ); ?>" class="btn btn-outline btn-small">Other Ways to Help</a>
				</div>
			</div>

			<?php if ( has_post_thumbnail() ) : ?>
				<div class="equine-hero-photo<?php echo $is_memoriam ? ' is-memoriam' : ''; ?>">
					<?php the_post_thumbnail( 'large', array( 'alt' => get_the_title() ) ); ?>
				</div>
			<?php endif; ?>
		</div>
	</section>

	<?php if ( $rescue_picture || $rescue_story ) : ?>
		<section class="equine-rescue-story">
			<div class="wrap">
				<h2 class="font-serif equine-rescue-story-title">My Rescue Story</h2>
				<div class="equine-rescue-story-grid">
					<?php if ( $rescue_picture ) : ?>
						<div class="equine-rescue-story-photo">
							<img src="<?php echo esc_url( $rescue_picture ); ?>" alt="<?php echo esc_attr( get_the_title() . ' — rescue photo' ); ?>">
						</div>
					<?php endif; ?>

					<?php if ( $rescue_story ) : ?>
						<div class="equine-rescue-story-text">
							<?php echo wpautop( esc_html( $rescue_story ) ); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( ! $is_memoriam ) : ?>
		<section class="equine-treat-cta">
			<div class="wrap equine-treat-cta-inner">
				<span class="equine-treat-cta-icon" aria-hidden="true">🥕</span>
				<p class="equine-treat-cta-text">Buy <?php the_title(); ?> some treats — just &euro;<?php echo esc_html( ARCH_TREAT_DONATION_AMOUNT ); ?></p>
				<a href="<?php echo esc_url( arch_get_treat_donation_url() ); ?>" target="_blank" rel="noopener" class="btn btn-primary btn-small">Send a Treat</a>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( $about_items ) : ?>
		<section class="equine-about-strip">
			<div class="wrap equine-about-row">
				<?php foreach ( $about_items as $item ) : ?>
					<span class="equine-about-chip">
						<span class="equine-about-icon" aria-hidden="true"><?php echo esc_html( $item['icon'] ); ?></span>
						<span class="equine-about-label"><?php echo esc_html( $item['short'] ); ?>:</span>
						<?php echo esc_html( $item['value'] ); ?>
					</span>
				<?php endforeach; ?>
			</div>
		</section>
	<?php endif; ?>

	<section class="single-post-body">
		<div class="wrap">
			<div class="single-post-content">
				<?php the_content(); ?>
			</div>

			<?php arch_render_share_buttons( $share_url, $share_title ); ?>
		</div>
	</section>

	<?php
endwhile;

get_footer();
