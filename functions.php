<?php
/**
 * ARCH theme functions and definitions.
 *
 * @package ARCH
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // No direct access.
}

/**
 * Theme setup.
 */
function arch_theme_setup() {
	// Let WordPress manage the document <title> via wp_head().
	add_theme_support( 'title-tag' );

	// Nice-to-haves if this theme grows beyond the single design page.
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

	// Makes YouTube/Vimeo embeds (and the video block) resize properly on
	// mobile in News post content, instead of staying a fixed pixel width.
	add_theme_support( 'responsive-embeds' );

	// Registers the "Primary Menu" location — manage it under
	// Appearance → Menus in wp-admin. Used for both the desktop and
	// mobile nav (same menu, rendered twice, CSS handles which shows).
	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'arch' ),
		)
	);
}
add_action( 'after_setup_theme', 'arch_theme_setup' );

/**
 * Fallback nav — used automatically by wp_nav_menu() until a menu has been
 * assigned to the "Primary Menu" location in Appearance → Menus. Matches
 * the original hardcoded links, so the site looks identical either way.
 *
 * Every link is home_url()-prefixed rather than a bare "#section" anchor —
 * these sections only exist on the homepage, but the header/footer render
 * on every page (like the Adoption page), so a bare "#about" would silently
 * do nothing if you weren't already on the homepage.
 */
function arch_nav_fallback() {
	$links = array(
		'/#about'      => 'About',
		'#EQUINES#'    => 'Adopt', // swapped for the real Adoption page URL below
		'/#wishlist'   => 'Wishlist',
		'#VOLUNTEER#'  => 'Get Involved', // swapped for the real Volunteer page URL below
		'#NEWS#'       => 'News', // swapped for the real News archive URL below
		'/#contact'    => 'Contact',
	);
	echo '<ul id="primary-menu" class="nav-menu">';
	foreach ( $links as $path => $label ) {
		if ( $path === '#EQUINES#' ) {
			$url = arch_get_adoption_page_url();
		} elseif ( $path === '#NEWS#' ) {
			$url = arch_get_news_page_url();
		} elseif ( $path === '#VOLUNTEER#' ) {
			$url = arch_get_volunteer_page_url();
		} else {
			$url = home_url( $path );
		}
		echo '<li><a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a></li>';
	}
	echo '</ul>';
}

/**
 * Enqueue theme styles and scripts the WordPress way (not raw <link>/<script> tags),
 * so caching, versioning, and plugin conflicts are handled correctly.
 */
function arch_enqueue_assets() {
	// Theme stylesheet (style.css) — required by WordPress for a valid theme,
	// kept here mostly for the theme metadata; the real design lives in arch.css below.
	wp_enqueue_style(
		'arch-style',
		get_stylesheet_uri(),
		array(),
		wp_get_theme()->get( 'Version' )
	);

	// The actual site design.
	wp_enqueue_style(
		'arch-main',
		get_template_directory_uri() . '/assets/arch.css',
		array( 'arch-style' ),
		filemtime( get_template_directory() . '/assets/arch.css' )
	);

	// Google Fonts (Fraunces / Caveat / Karla) used throughout the design.
	wp_enqueue_style(
		'arch-fonts',
		'https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&family=Caveat:wght@600;700&family=Karla:wght@400;500;600;700&display=swap',
		array(),
		null
	);

	// Mobile menu / scroll-reveal / demo-form JS. Loaded in the footer.
	wp_enqueue_script(
		'arch-main',
		get_template_directory_uri() . '/assets/arch.js',
		array(),
		filemtime( get_template_directory() . '/assets/arch.js' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'arch_enqueue_assets' );

/**
 * Outputs Open Graph and Twitter Card meta tags across the whole site, so
 * sharing any link on Facebook/WhatsApp/X/etc. shows a real photo, title,
 * and description instead of a blank or generic preview card. Equine pages
 * use that horse's own photo and story. Everywhere else falls back to the
 * About section photo — deliberately not the Hero photo, since that one
 * rotates with "Star of the Month" and would make old shared links show a
 * different horse than whoever's currently featured, undermining the
 * consistency this is meant to provide.
 */
function arch_social_meta() {
	if ( is_singular( 'equine' ) ) {
		$title       = get_the_title() . ' — ARCH';
		$description = wp_trim_words( wp_strip_all_tags( get_the_content() ), 30 );
		$url         = get_permalink();
		$image       = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'large' ) : '';
	} elseif ( is_front_page() ) {
		$title       = get_bloginfo( 'name' ) . ' — Andalucian Rescue Centre for Horses';
		$description = arch_home_text( 'hero_lede', "We're ARCH — a volunteer-powered rescue in Andalucía giving abandoned and abused equines a second chance at a good life." );
		$url         = home_url( '/' );
		$image       = arch_home_image_url( 'about_photo', get_template_directory_uri() . '/assets/images/about-photo-fallback.jpg' );
	} elseif ( is_singular( 'page' ) ) {
		$title       = get_the_title() . ' — ARCH';
		$description = has_excerpt() ? get_the_excerpt() : wp_trim_words( wp_strip_all_tags( get_the_content() ), 30 );
		$url         = get_permalink();
		$image       = arch_home_image_url( 'about_photo', get_template_directory_uri() . '/assets/images/about-photo-fallback.jpg' );
	} else {
		return;
	}

	echo "\n<!-- Social share preview tags (Open Graph + Twitter Card) -->\n";
	echo '<meta property="og:type" content="' . ( is_singular( 'equine' ) ? 'article' : 'website' ) . '">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
	echo '<meta property="og:description" content="' . esc_attr( $description ) . '">' . "\n";
	echo '<meta property="og:url" content="' . esc_url( $url ) . '">' . "\n";
	if ( $image ) {
		echo '<meta property="og:image" content="' . esc_url( $image ) . '">' . "\n";
	}
	echo '<meta name="twitter:card" content="' . ( $image ? 'summary_large_image' : 'summary' ) . '">' . "\n";
	echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '">' . "\n";
	echo '<meta name="twitter:description" content="' . esc_attr( $description ) . '">' . "\n";
	if ( $image ) {
		echo '<meta name="twitter:image" content="' . esc_url( $image ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'arch_social_meta' );

/**
 * -----------------------------------------------------------------------
 * CUSTOM POST TYPES
 * -----------------------------------------------------------------------
 * "Equine" and "Wishlist Item" are repeating content, so each entry gets
 * its own editable post in wp-admin rather than being hardcoded in a
 * template file. News deliberately does NOT get a custom post type —
 * WordPress's built-in Posts already do that job.
 */
function arch_register_post_types() {

	register_post_type(
		'equine',
		array(
			'labels'       => array(
				'name'               => __( 'Equines', 'arch' ),
				'singular_name'      => __( 'Equine', 'arch' ),
				'add_new_item'       => __( 'Add New Equine', 'arch' ),
				'edit_item'          => __( 'Edit Equine', 'arch' ),
				'all_items'          => __( 'All Equines', 'arch' ),
				'featured_image'     => __( 'Equine Photo', 'arch' ),
				'set_featured_image' => __( 'Set equine photo', 'arch' ),
			),
			'public'       => true,
			'show_in_rest' => true, // Needed for the block editor + featured image UI.
			'menu_icon'    => 'dashicons-pets',
			'supports'     => array( 'title', 'editor', 'thumbnail' ),
			'has_archive'  => false,
			'rewrite'      => array( 'slug' => 'equines' ),
		)
	);

	register_post_type(
		'wishlist_item',
		array(
			'labels'       => array(
				'name'          => __( 'Wishlist Items', 'arch' ),
				'singular_name' => __( 'Wishlist Item', 'arch' ),
				'add_new_item'  => __( 'Add New Wishlist Item', 'arch' ),
				'edit_item'     => __( 'Edit Wishlist Item', 'arch' ),
				'all_items'     => __( 'All Wishlist Items', 'arch' ),
			),
			'public'       => true,
			'show_in_rest' => true,
			'menu_icon'    => 'dashicons-cart',
			'supports'     => array( 'title', 'editor' ),
			'has_archive'  => false,
			'rewrite'      => array( 'slug' => 'wishlist' ),
		)
	);
	register_post_type(
		'sponsor',
		array(
			'labels'       => array(
				'name'               => __( 'Sponsors', 'arch' ),
				'singular_name'      => __( 'Sponsor', 'arch' ),
				'add_new_item'       => __( 'Add New Sponsor', 'arch' ),
				'edit_item'          => __( 'Edit Sponsor', 'arch' ),
				'all_items'          => __( 'All Sponsors', 'arch' ),
				'featured_image'     => __( 'Sponsor Logo', 'arch' ),
				'set_featured_image' => __( 'Set sponsor logo', 'arch' ),
			),
			'public'       => true,
			'show_in_rest' => true,
			'menu_icon'    => 'dashicons-heart',
			'supports'     => array( 'title', 'thumbnail' ),
			'has_archive'  => false,
			'rewrite'      => array( 'slug' => 'sponsors' ),
		)
	);

	register_post_type(
		'volunteer_photo',
		array(
			'labels'       => array(
				'name'               => __( 'Volunteer Photos', 'arch' ),
				'singular_name'      => __( 'Volunteer Photo', 'arch' ),
				'add_new_item'       => __( 'Add New Volunteer Photo', 'arch' ),
				'edit_item'          => __( 'Edit Volunteer Photo', 'arch' ),
				'all_items'          => __( 'All Volunteer Photos', 'arch' ),
				'featured_image'     => __( 'Photo', 'arch' ),
				'set_featured_image' => __( 'Set photo', 'arch' ),
			),
			'public'       => true,
			'show_in_rest' => true,
			'menu_icon'    => 'dashicons-camera',
			'supports'     => array( 'title', 'thumbnail' ),
			'has_archive'  => false,
			'rewrite'      => array( 'slug' => 'volunteer-photos' ),
		)
	);

	register_post_type(
		'donation_method',
		array(
			'labels'       => array(
				'name'          => __( 'Donation Methods', 'arch' ),
				'singular_name' => __( 'Donation Method', 'arch' ),
				'add_new_item'  => __( 'Add New Donation Method', 'arch' ),
				'edit_item'     => __( 'Edit Donation Method', 'arch' ),
				'all_items'     => __( 'All Donation Methods', 'arch' ),
			),
			'public'       => true,
			'show_in_rest' => true,
			'menu_icon'    => 'dashicons-money-alt',
			'supports'     => array( 'title', 'editor' ),
			'has_archive'  => false,
			'rewrite'      => array( 'slug' => 'donation-methods' ),
		)
	);
}
add_action( 'init', 'arch_register_post_types' );

/**
 * -----------------------------------------------------------------------
 * ACF FIELD GROUPS (registered in code, not the ACF admin UI)
 * -----------------------------------------------------------------------
 * Defining these in PHP rather than clicking them together in ACF's UI
 * means the fields exist automatically the moment ACF is active — nobody
 * has to manually rebuild them by hand after installing the theme.
 *
 * Requires the free "Advanced Custom Fields" plugin (search & install from
 * Plugins > Add New in wp-admin — no license key, no paid tier needed for
 * anything used here). Wrapped in function_exists() so the theme doesn't
 * fatal-error if ACF isn't installed yet — the fields just won't appear
 * in wp-admin until it is.
 */
function arch_register_acf_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	// ---- Equine fields ----
	acf_add_local_field_group(
		array(
			'key'      => 'group_equine',
			'title'    => 'Equine Details',
			'fields'   => array(
				array(
					'key'           => 'field_equine_status',
					'label'         => 'Status',
					'name'          => 'status',
					'type'          => 'select',
					'choices'       => array(
						'available' => 'Available to Adopt',
						'foster'    => 'Available to Foster',
						'recovery'  => 'In Recovery',
						'permanent' => 'Permanent Resident',
						'adopted'   => 'Adopted',
						'memoriam'  => 'In Memoriam',
					),
					'default_value' => 'available',
					'instructions'  => 'Which group this equine appears under on the Adoption page, and which coloured tag shows on their card.',
				),
				array(
					'key'           => 'field_equine_type',
					'label'         => 'Type',
					'name'          => 'equine_type',
					'type'          => 'select',
					'choices'       => array(
						'horse'       => 'Horse',
						'pony'        => 'Pony',
						'donkey_mule' => 'Donkey & Mule',
					),
					'default_value' => 'horse',
					'instructions'  => 'Only used to sub-group the "Available to Adopt" section on the Adoption page into Horses / Ponies / Donkeys & Mules.',
				),
				array(
					'key'          => 'field_equine_meta_line',
					'label'        => 'Meta Line',
					'name'         => 'meta_line',
					'type'         => 'text',
					'instructions' => 'The small line under the name on the Equine\'s CARD (homepage, Adoption page), e.g. "Dark bay mare · 145cm · Born 2013". Free text — kept separate from the detail fields below so you can phrase it however reads best for a card.',
				),
				array(
					'key'          => 'field_equine_tab_details',
					'label'        => 'Individual Page Details',
					'type'         => 'tab',
					'instructions' => 'These show as a facts strip on the horse\'s own page (not on cards elsewhere). All optional — leave any blank and that row just doesn\'t show, nothing breaks.',
				),
				array(
					'key'          => 'field_equine_sex',
					'label'        => 'Sex',
					'name'         => 'sex',
					'type'         => 'select',
					'allow_null'   => 1,
					'choices'      => array(
						'mare'     => 'Mare',
						'gelding'  => 'Gelding',
						'stallion' => 'Stallion',
					),
				),
				array(
					'key'          => 'field_equine_breed',
					'label'        => 'Breed',
					'name'         => 'breed',
					'type'         => 'text',
					'instructions' => 'e.g. "Andalusian". Leave blank if unknown/mixed.',
				),
				array(
					'key'          => 'field_equine_height',
					'label'        => 'Height (cm)',
					'name'         => 'height_cm',
					'type'         => 'number',
					'instructions' => 'Just the number, e.g. 145. Shown as "145cm".',
				),
				array(
					'key'          => 'field_equine_born_year',
					'label'        => 'Year Born',
					'name'         => 'born_year',
					'type'         => 'number',
					'instructions' => 'e.g. 2013. Used to show both "Born 2013" and the current age — this way the age stays correct automatically and nobody has to remember to update it every year.',
				),
				array(
					'key'          => 'field_equine_rescue_date',
					'label'        => 'Rescue Date',
					'name'         => 'rescue_date',
					'type'         => 'date_picker',
					'display_format' => 'F Y',
					'return_format'  => 'F Y',
					'instructions' => 'Shown as "Rescued: March 2023".',
				),
				array(
					'key'          => 'field_equine_passed_away_date',
					'label'        => 'Passed Away Date',
					'name'         => 'passed_away_date',
					'type'         => 'date_picker',
					'display_format' => 'Y',
					'return_format'  => 'Y',
					'instructions' => 'Only needed for horses with the "In Memoriam" status — used on the In Loving Memory page to show the year they passed. Leave blank for every other horse.',
				),
				array(
					'key'          => 'field_equine_favourite_food',
					'label'        => 'Favourite Food',
					'name'         => 'favourite_food',
					'type'         => 'text',
					'instructions' => 'Optional. e.g. "Apples & carrots". Leave blank to skip this row entirely.',
				),
				array(
					'key'          => 'field_equine_dislikes',
					'label'        => 'Dislikes',
					'name'         => 'dislikes',
					'type'         => 'text',
					'instructions' => 'Optional. e.g. "Puddles, having his mane brushed". Leave blank to skip this row entirely.',
				),
				array(
					'key'          => 'field_equine_quirk',
					'label'        => 'Quirk',
					'name'         => 'quirk',
					'type'         => 'text',
					'instructions' => 'Optional. A distinctive habit or party trick, e.g. "Can undo stable bolts". Leave blank to skip this row entirely.',
				),
				array(
					'key'          => 'field_equine_personality',
					'label'        => 'Personality',
					'name'         => 'personality',
					'type'         => 'textarea',
					'rows'         => 2,
					'instructions' => 'Optional. A sentence or two — this is the "voice" of the horse, shown as a highlighted note on their page. Leave blank to skip it.',
				),
				array(
					'key'          => 'field_equine_tab_rescue_story',
					'label'        => 'Rescue Story',
					'type'         => 'tab',
					'instructions' => 'Shown in a dedicated "My Rescue Story" section further down the page, with the photo on the left and the text on the right. This photo is separate from the main Equine Photo used on cards and at the top of this page.',
				),
				array(
					'key'           => 'field_equine_rescue_picture',
					'label'         => 'Rescue Picture',
					'name'          => 'rescue_picture',
					'type'          => 'image',
					'return_format' => 'url',
					'instructions'  => 'Photo shown alongside the rescue story. Different from the main Equine Photo above.',
				),
				array(
					'key'          => 'field_equine_rescue_story',
					'label'        => 'Rescue Story',
					'name'         => 'rescue_story',
					'type'         => 'textarea',
					'rows'         => 6,
					'instructions' => 'The horse\'s rescue story text, shown next to the Rescue Picture.',
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'equine',
					),
				),
			),
		)
	);

	// ---- Homepage photos (hero + about section) ----
	// Deliberately using ACF's Image field rather than a block-editor Image
	// block: it's the same reliable mechanism your Equine photos already use
	// (Featured Image), just attached to a normal field here instead, since
	// a page can only have one Featured Image but this page needs two photos.
	acf_add_local_field_group(
		array(
			'key'      => 'group_home_content_text',
			'title'    => 'Homepage Content Settings',
			'position' => 'acf_after_title',
			'fields'   => array(
				array(
					'key'          => 'field_hero_sticker',
					'label'        => 'Hero Sticker',
					'name'         => 'hero_sticker',
					'type'         => 'text',
					'default_value' => 'Officially Utilidad Pública 🎉',
					'instructions' => 'The small rotated badge in the top-left of the hero section.',
				),
				array(
					'key'          => 'field_hero_lede',
					'label'        => 'Hero Intro Text',
					'name'         => 'hero_lede',
					'type'         => 'textarea',
					'rows'         => 3,
					'default_value' => "We're ARCH — a volunteer-powered rescue in Andalucía giving abandoned and abused equines a second chance at a good life. You've been supporting us since 2009.",
					'instructions' => 'The paragraph under the big hero heading.',
				),
				array(
					'key'           => 'field_hero_photo',
					'label'         => 'Hero Photo',
					'name'          => 'hero_photo',
					'type'          => 'image',
					'return_format' => 'url',
					'instructions'  => 'The horse photo shown in the hero section at the top of the homepage.',
				),
				array(
					'key'          => 'field_hero_tag',
					'label'        => 'Hero Photo Sticker Text',
					'name'         => 'hero_tag',
					'type'         => 'text',
					'default_value' => 'Star of the Month',
					'instructions' => 'The rotated sticker on the hero photo (currently reads "Read My Story" once a photo link is set — this is the fallback label).',
				),
				array(
					'key'          => 'field_hero_photo_link',
					'label'        => 'Hero Photo Link',
					'name'         => 'hero_photo_link',
					'type'         => 'text',
					'instructions' => 'Where clicking the hero photo (the "Star of the Month" horse) sends people — a full URL (https://...) or an internal link like /#equines or /#contact both work. Leave this blank and the photo isn\'t clickable at all.',
				),
				array(
					'key'          => 'field_about_heading',
					'label'        => 'About Section Heading',
					'name'         => 'about_heading',
					'type'         => 'text',
					'default_value' => 'Transforming Equine Lives since 2009.',
				),
				array(
					'key'          => 'field_about_text_1',
					'label'        => 'About Section — Paragraph 1',
					'name'         => 'about_text_1',
					'type'         => 'textarea',
					'rows'         => 6,
					'default_value' => "We're the Andalusian Rescue Centre for Horses — registered as Centro Andalusí de Rescate de Caballos, but known locally as ARCH. Our mission is to <strong>rescue equines who have been abused, neglected, or abandoned</strong> – animals who have no one else to fight for their well-being. Most of our rescues come through SEPRONA (the animal welfare arm of the Guardia Civil), and abandoned animals are also brought in through the local police. Inevitably some of the older animals, or those with special needs, stay with us for a long time but <strong>the Centre is not a retirement home or a sanctuary</strong>, and we try to find suitable homes for all our rescue animals – the flow of deserving cases never stops, we have limited space, and it is hard to refuse to help.",
					'instructions' => 'Plain text. You can use <strong>...</strong> around a phrase to make it bold, same as the rest of this paragraph already does — just type the tags directly.',
				),
				array(
					'key'          => 'field_about_text_2',
					'label'        => 'About Section — Paragraph 2',
					'name'         => 'about_text_2',
					'type'         => 'textarea',
					'rows'         => 6,
					'default_value' => 'Rescue is only part of the story. Once animals arrive at the Rescue Centre we work with SEPRONA, local equine vets and with our animal rights lawyer, <strong>Aritz Toribio</strong>, to take owners to court. This is a <strong>costly, frustrating and long-drawn-out process</strong> (cases can drag on for years and we rarely recoup anything like our costs). New animal cruelty legislation has been slow to filter through to judges and public prosecutors, but <strong>there have been some successful cases recently which give us hope</strong> that we may eventually win the fight for justice.',
					'instructions' => 'Plain text. You can use <strong>...</strong> around a phrase to make it bold, same as the rest of this paragraph already does — just type the tags directly.',
				),
				array(
					'key'           => 'field_about_photo',
					'label'         => 'About Section Photo',
					'name'          => 'about_photo',
					'type'          => 'image',
					'return_format' => 'url',
					'instructions'  => 'The photo shown next to the "Founded in 2009" text further down the homepage.',
				),
				array(
					'key'          => 'field_contact_email',
					'label'        => 'Contact — Email',
					'name'         => 'contact_email',
					'type'         => 'text',
					'default_value' => 'info@horserescuespain.org',
				),
				array(
					'key'          => 'field_contact_address',
					'label'        => 'Contact — Address',
					'name'         => 'contact_address',
					'type'         => 'text',
					'default_value' => 'Viña Borrego (behind Miralmonte), A404, Alhaurín el Grande, Málaga, Spain',
				),
				array(
					'key'          => 'field_contact_hours',
					'label'        => 'Contact — Visiting Hours',
					'name'         => 'contact_hours',
					'type'         => 'text',
					'default_value' => 'Mon–Sat, 9:00–11:00am by appointment. First Sunday of the month, 10am–2pm (except July & August).',
				),
				array(
					'key'          => 'field_contact_visiting_info',
					'label'        => 'Contact — Visiting Info',
					'name'         => 'contact_visiting_info',
					'type'         => 'textarea',
					'rows'         => 2,
					'default_value' => 'Please wear closed shoes if you wish to groom or enter the paddocks. Visits are free of charge, but any donations are gratefully received!',
				),
				array(
					'key'          => 'field_contact_sponsorship',
					'label'        => 'Contact — Sponsorship Enquiries',
					'name'         => 'contact_sponsorship',
					'type'         => 'textarea',
					'rows'         => 2,
					'default_value' => 'Email sponsors@horserescuespain.org or use the form — just let us know which horse you\'d like to sponsor and we\'ll help you get set up.',
				),
				array(
					'key'           => 'field_contact_map_image',
					'label'         => 'Contact — "How to Find Us" Map',
					'name'          => 'contact_map_image',
					'type'          => 'image',
					'return_format' => 'url',
					'instructions'  => 'The illustrated directions map shown in the Contact section, below the enquiry form.',
				),
				array(
					'key'          => 'field_contact_maps_url',
					'label'        => 'Contact — Get Directions Link',
					'name'         => 'contact_maps_url',
					'type'         => 'text',
					'default_value' => 'https://maps.app.goo.gl/K9jnfUv3cWE3vKE47',
					'instructions' => 'Where the "Get Directions" button under the map sends people.',
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'page_type',
						'operator' => '==',
						'value'    => 'front_page',
					),
				),
			),
		)
	);

	// ---- Adoption page settings (the "Apply to Adopt Now" button target) ----
	acf_add_local_field_group(
		array(
			'key'      => 'group_adoption_page',
			'title'    => 'Adoption Page Settings',
			'position' => 'acf_after_title',
			'fields'   => array(
				array(
					'key'          => 'field_adoption_form_url',
					'label'        => 'Apply to Adopt — Button Link',
					'name'         => 'adoption_form_url',
					'type'         => 'text',
					'instructions' => 'Where the "Apply to Adopt Now" button sends people — e.g. a Google Form, an application page, or an internal link like /#contact. Leave blank and it\'ll safely link to the Contact section instead.',
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'page_template',
						'operator' => '==',
						'value'    => 'page-adoption.php',
					),
				),
			),
		)
	);

	// ---- Charity Shop page settings ----
	acf_add_local_field_group(
		array(
			'key'      => 'group_shop_page',
			'title'    => 'Charity Shop Page Settings',
			'position' => 'acf_after_title',
			'fields'   => array(
				array(
					'key'           => 'field_shop_photo',
					'label'         => 'Shop Photo',
					'name'          => 'shop_photo',
					'type'          => 'image',
					'return_format' => 'url',
					'instructions'  => 'A photo of the shop — exterior, interior, or the team. Shown alongside the intro text. Leave blank and the intro just displays centered with no photo, nothing breaks.',
				),
				array(
					'key'          => 'field_shop_address',
					'label'        => 'Address',
					'name'         => 'shop_address',
					'type'         => 'text',
					'default_value' => 'Calle Menéndez Pelayo 2, just off Calle Gerald Brenan, 29120 Alhaurín el Grande (Málaga)',
				),
				array(
					'key'          => 'field_shop_maps_url',
					'label'        => 'Google Maps Link',
					'name'         => 'shop_maps_url',
					'type'         => 'text',
					'default_value' => 'https://goo.gl/maps/Bvb2dCWw91MMShHR8',
					'instructions' => 'Used for the "Get Directions" button.',
				),
				array(
					'key'          => 'field_shop_hours',
					'label'        => 'Opening Hours',
					'name'         => 'shop_hours',
					'type'         => 'text',
					'default_value' => 'Mon–Fri, 10am–3pm. Sat, 10am–2pm.',
				),
				array(
					'key'          => 'field_shop_phone',
					'label'        => 'Phone / Messenger',
					'name'         => 'shop_phone',
					'type'         => 'text',
					'default_value' => '652 49 27 51 (Facebook Messenger or call, English spoken)',
				),
				array(
					'key'          => 'field_shop_email',
					'label'        => 'Email',
					'name'         => 'shop_email',
					'type'         => 'text',
					'default_value' => 'shop@horserescuespain.org',
				),
				array(
					'key'          => 'field_shop_facebook_url',
					'label'        => 'Facebook Page Link',
					'name'         => 'shop_facebook_url',
					'type'         => 'text',
					'default_value' => 'https://www.facebook.com/ARCH-Charity-Shop-1396767360436780/',
				),
				array(
					'key'          => 'field_shop_accepted_items',
					'label'        => 'Donations of Goods',
					'name'         => 'shop_accepted_items',
					'type'         => 'textarea',
					'rows'         => 5,
					'default_value' => "We're always in need of saleable items in good, clean condition — household goods, kitchenware, crockery, bric-a-brac, home decorations, small furniture, clothing, and more.\n\nDrop off any time during opening hours. Need us to collect? Get in touch — please allow a few days, especially if a van is required.",
					'instructions' => 'Shown in the "Donating Goods" section of the page.',
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'page_template',
						'operator' => '==',
						'value'    => 'page-shop.php',
					),
				),
			),
		)
	);

	// ---- Volunteer page settings ----
	acf_add_local_field_group(
		array(
			'key'      => 'group_volunteer_page',
			'title'    => 'Volunteer Page Settings',
			'position' => 'acf_after_title',
			'fields'   => array(
				array(
					'key'          => 'field_volunteer_apply_url',
					'label'        => 'Apply to Volunteer — Button Link',
					'name'         => 'volunteer_apply_url',
					'type'         => 'text',
					'instructions' => 'Where the "Apply to Volunteer" button sends people. Leave blank and it links to the Contact section — safe everywhere, unlike a mailto link which doesn\'t work for everyone. Only fill this in once a proper application form exists.',
				),
				array(
					'key'          => 'field_volunteer_video_url',
					'label'        => 'Volunteering Video (YouTube URL)',
					'name'         => 'volunteer_video_url',
					'type'         => 'text',
					'instructions' => 'Paste a plain YouTube link, e.g. https://www.youtube.com/watch?v=XXXXXXXXXXX. Leave blank to skip this section entirely.',
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'page_template',
						'operator' => '==',
						'value'    => 'page-volunteer.php',
					),
				),
			),
		)
	);

	// ---- Report an Equine page settings ----
	acf_add_local_field_group(
		array(
			'key'      => 'group_report_abuse_page',
			'title'    => 'Report an Equine Page Settings',
			'position' => 'acf_after_title',
			'fields'   => array(
				array(
					'key'          => 'field_report_alertcops_url',
					'label'        => 'AlertCops App Link',
					'name'         => 'report_alertcops_url',
					'type'         => 'text',
					'default_value' => 'https://alertcops.ses.mir.es/publico/alertcops/en/',
					'instructions' => 'Where the AlertCops button sends people, to report to Seprona via Guardia Civil.',
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'page_template',
						'operator' => '==',
						'value'    => 'page-report-abuse.php',
					),
				),
			),
		)
	);

	// ---- Content Page with Button settings (generic, reusable template) ----
	acf_add_local_field_group(
		array(
			'key'      => 'group_content_cta_page',
			'title'    => 'Page Button Settings',
			'position' => 'acf_after_title',
			'fields'   => array(
				array(
					'key'          => 'field_cta_label',
					'label'        => 'Button Text',
					'name'         => 'cta_label',
					'type'         => 'text',
					'instructions' => 'e.g. "Apply to Volunteer", "Get in Touch", "Download the Form". If left blank while a Button Link is set, "Learn More" is used instead.',
				),
				array(
					'key'          => 'field_cta_url',
					'label'        => 'Button Link',
					'name'         => 'cta_url',
					'type'         => 'text',
					'instructions' => 'Where the button sends people — a full URL (https://...) or an internal link like /#contact both work. Leave this blank and no button shows at all — just the page content above it.',
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'page_template',
						'operator' => '==',
						'value'    => 'page-content-cta.php',
					),
				),
			),
		)
	);

	// ---- Sponsor fields ----
	acf_add_local_field_group(
		array(
			'key'      => 'group_sponsor',
			'title'    => 'Sponsor Details',
			'fields'   => array(
				array(
					'key'          => 'field_sponsor_category',
					'label'        => 'Category',
					'name'         => 'category',
					'type'         => 'select',
					'choices'      => array(
						'sponsor'     => 'Sponsor',
						'subsidy'     => 'Subsidy',
						'affiliation' => 'Affiliation',
					),
					'default_value' => 'sponsor',
					'instructions' => 'Which group this logo appears under at the bottom of the homepage.',
				),
				array(
					'key'          => 'field_sponsor_url',
					'label'        => 'Website URL',
					'name'         => 'website_url',
					'type'         => 'url',
					'instructions' => 'Optional. If set, the logo links out to their site (opens in a new tab). Leave blank for a plain, unlinked logo.',
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'sponsor',
					),
				),
			),
		)
	);

	// ---- Donation Method fields ----
	acf_add_local_field_group(
		array(
			'key'      => 'group_donation_method',
			'title'    => 'Donation Method Details',
			'fields'   => array(
				array(
					'key'           => 'field_donation_icon',
					'label'         => 'Icon',
					'name'          => 'icon',
					'type'          => 'select',
					'choices'       => array(
						'card'        => 'Card / Online Payment',
						'bank'        => 'Bank Transfer',
						'shop'        => 'Shop',
						'fundraising' => 'Fundraising',
						'sponsorship' => 'Sponsorship',
						'teaming'     => 'Teaming',
						'heart'       => 'General / Other',
					),
					'default_value' => 'heart',
				),
				array(
					'key'          => 'field_donation_cta_label',
					'label'        => 'Button Text',
					'name'         => 'cta_label',
					'type'         => 'text',
					'instructions' => 'e.g. "Visit the Shop", "Join on Teaming". Leave blank along with the URL below for a method with no external link — e.g. bank transfer details that just need to be read, not clicked.',
				),
				array(
					'key'          => 'field_donation_cta_url',
					'label'        => 'Button Link',
					'name'         => 'cta_url',
					'type'         => 'text',
					'instructions' => 'Where the button goes — a shop, Teaming, a fundraising page, or an internal link like /#contact. Leave both this and Button Text blank if this method doesn\'t need one.',
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'donation_method',
					),
				),
			),
		)
	);

	// ---- Wishlist item fields ----
	acf_add_local_field_group(
		array(
			'key'      => 'group_wishlist_item',
			'title'    => 'Wishlist Item Details',
			'fields'   => array(
				array(
					'key'          => 'field_wishlist_price',
					'label'        => 'Price (EUR)',
					'name'         => 'price',
					'type'         => 'number',
					'instructions' => 'Just the number, e.g. 25 for €25. This also becomes the pre-filled amount on the Donorbox donation link.',
					'required'     => 1,
				),
				array(
					'key'          => 'field_wishlist_icon',
					'label'        => 'Icon',
					'name'         => 'icon',
					'type'         => 'select',
					'choices'      => array(
						'vet_wrap' => 'Vet Wrap (bandage roll)',
						'halter'   => 'Halter & Lead Rope',
						'spray'    => 'Spray bottle',
						'tooth'    => 'Tooth (dental)',
						'capsule'  => 'Capsule (deworming/medicine)',
						'bottle'   => 'Bottle (shampoo/liquid)',
					),
					'default_value' => 'vet_wrap',
					'instructions' => 'Picks from the small set of hand-drawn icons already built into the theme. Adding a genuinely new icon shape needs a short code change — see the README.',
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'wishlist_item',
					),
				),
			),
		)
	);
}
add_action( 'acf/init', 'arch_register_acf_fields' );

/**
 * Forces a short, consistent excerpt length for News cards, regardless of
 * whether the post has a manually-written Excerpt (which WordPress would
 * otherwise use verbatim, at whatever length someone typed) or none (which
 * WordPress auto-generates at 55 words by default — still long for a card).
 * Uses core's wp_trim_words() rather than manual string cutting, since it
 * correctly handles word boundaries, tags, and multibyte characters.
 */
function arch_short_excerpt( $word_limit = 18, $more = '…' ) {
	$source = has_excerpt() ? get_the_excerpt() : get_the_content();
	return wp_trim_words( wp_strip_all_tags( $source ), $word_limit, $more );
}

/**
 * Trims the Rescue Story field down to a short extract for use as the
 * hero quote on the equine's own page — same word-trim approach as
 * arch_short_excerpt(), just a shorter limit since it's sitting in a
 * hero rather than a card.
 */
function arch_equine_story_extract( $story, $word_limit = 22 ) {
	if ( ! $story ) {
		return '';
	}
	return wp_trim_words( wp_strip_all_tags( $story ), $word_limit, '…' );
}

/**
 * Small safety net: if someone activates this theme before installing ACF,
 * calling get_field() would fatal-error. This wrapper just returns an empty
 * string instead, so the site stays up (with blank fields) until ACF is
 * installed, rather than a white screen of death.
 */
function arch_get_field( $selector, $post_id = false ) {
	if ( function_exists( 'get_field' ) ) {
		return get_field( $selector, $post_id );
	}
	return '';
}

/**
 * Turns a stored birth year into a current age. Deliberately computed at
 * render time rather than stored as a flat "age" field, so nobody has to
 * remember to bump every horse's age by hand each January.
 */
function arch_equine_age( $born_year ) {
	if ( ! $born_year ) {
		return null;
	}
	$age = (int) gmdate( 'Y' ) - (int) $born_year;
	return ( $age >= 0 && $age < 60 ) ? $age : null; // sanity guard against typoed years
}

/**
 * Maps an Equine's "status" field value to its display label and the CSS
 * modifier class used for the coloured tag on its card.
 */
function arch_equine_status_meta( $status ) {
	$map = array(
		'available' => array( 'label' => 'Available!',        'class' => 'available' ),
		'foster'    => array( 'label' => 'Foster only',        'class' => 'foster' ),
		'recovery'  => array( 'label' => 'In Recovery',        'class' => 'recovery' ),
		'permanent' => array( 'label' => 'Permanent Resident', 'class' => 'permanent' ),
		'adopted'   => array( 'label' => 'Adopted',            'class' => 'adopted' ),
		'memoriam'  => array( 'label' => 'In Memoriam',        'class' => 'memoriam' ),
	);
	return isset( $map[ $status ] ) ? $map[ $status ] : $map['available'];
}

/**
 * Section headings for the Adoption page, in display order. "available" is
 * handled separately since it's sub-grouped by equine type.
 */
function arch_equine_status_sections() {
	return array(
		'foster'    => 'Available to Foster',
		'recovery'  => 'In Recovery',
		'permanent' => 'Permanent Residents',
		'adopted'   => 'Adopted',
	);
}

/**
 * Sub-group labels for the "Available to Adopt" section.
 */
function arch_equine_type_sections() {
	return array(
		'horse'       => 'Horses',
		'pony'        => 'Ponies',
		'donkey_mule' => 'Donkeys & Mules',
	);
}

/**
 * Renders a single equine card. Shared between the homepage teaser and the
 * full Adoption page library so the markup only exists in one place — must
 * be called inside a WP_Query loop, after the_post().
 *
 * @param string $link_url If set, the whole card becomes a link to this URL
 *                          (used on the homepage to link through to the
 *                          Adoption page). Leave empty for a plain,
 *                          non-clickable card (used on the Adoption page
 *                          itself, where linking would go nowhere useful).
 */
function arch_render_equine_card() {
	$status      = arch_get_field( 'status' );
	$status      = $status ? $status : 'available';
	$status_meta = arch_equine_status_meta( $status );
	$meta_line   = arch_get_field( 'meta_line' );
	$slug        = get_post_field( 'post_name' );
	$is_memoriam = ( $status === 'memoriam' );
	?>
	<div id="equine-<?php echo esc_attr( $slug ); ?>" class="equine-card<?php echo $is_memoriam ? ' is-memoriam' : ''; ?>" data-reveal>
		<a href="<?php the_permalink(); ?>" class="equine-card-link">
		<div class="equine-photo">
			<?php if ( has_post_thumbnail() ) : ?>
				<?php the_post_thumbnail( 'medium', array( 'alt' => get_the_title() ) ); ?>
			<?php else : ?>
				<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/placeholder-equine.svg' ); ?>" alt="<?php the_title_attribute(); ?>">
			<?php endif; ?>
		</div>
		<div class="equine-body">
			<div class="equine-name-row">
				<h4 class="font-serif"><?php the_title(); ?></h4>
				<span class="equine-tag <?php echo esc_attr( $status_meta['class'] ); ?>"><?php echo esc_html( $status_meta['label'] ); ?></span>
			</div>
			<?php if ( $meta_line ) : ?>
				<div class="equine-meta"><?php echo esc_html( $meta_line ); ?></div>
			<?php endif; ?>
			<p class="equine-desc"><?php echo esc_html( arch_short_excerpt( 18 ) ); ?></p>
		</div>
		</a>
	</div>
	<?php
}

/**
 * Renders a single News card — shared between the homepage teaser and the
 * full News archive, same reasoning as arch_render_equine_card(): keeping
 * the markup in one place avoids the two ever drifting out of sync.
 *
 * If the post has a Featured Image, it's shown at the top of the card.
 * If not, a colourful branded block with the horseshoe motif shows instead
 * — colour cycles through the brand palette per post so a run of photo-less
 * posts still reads as varied rather than repetitive. Must be called
 * inside a WP_Query loop, after the_post().
 */
/**
 * Renders a row of social share links for the given URL/title — used on
 * individual Equine pages. Same visual style as the footer's social icons.
 * No plugin, no external JS: these are just each platform's own share-URL
 * scheme, which is all a simple share button actually needs.
 */
function arch_render_share_buttons( $url, $title ) {
	$encoded_url   = rawurlencode( $url );
	$encoded_title = rawurlencode( $title );
	?>
	<div class="share-buttons">
		<span class="share-buttons-label">Share:</span>
		<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $encoded_url; ?>" target="_blank" rel="noopener" aria-label="Share on Facebook">
			<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M15 3h-2a4 4 0 0 0-4 4v3H7v4h2v7h4v-7h2.6l.7-4H13V7a1 1 0 0 1 1-1h2V3Z"/></svg>
		</a>
		<a href="https://wa.me/?text=<?php echo $encoded_title . '%20' . $encoded_url; ?>" target="_blank" rel="noopener" aria-label="Share on WhatsApp">
			<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 20l1.3-3.9A8 8 0 1 1 8.8 19L4 20Z"/><path d="M9 10c0 3 2.5 5 5 5.5.6.1 1-.5 1-1v-.9c0-.4-.3-.7-.6-.8l-1.3-.4c-.3-.1-.6 0-.8.2l-.3.4c-1-.5-1.9-1.4-2.4-2.4l.4-.3c.2-.2.3-.5.2-.8l-.4-1.3c-.1-.3-.4-.6-.8-.6H9c-.5 0-1 .4-1 1Z" fill="currentColor" stroke="none"/></svg>
		</a>
		<a href="https://twitter.com/intent/tweet?url=<?php echo $encoded_url; ?>&text=<?php echo $encoded_title; ?>" target="_blank" rel="noopener" aria-label="Share on X">
			<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="4" x2="20" y2="20"/><line x1="20" y1="4" x2="4" y2="20"/></svg>
		</a>
		<a href="mailto:?subject=<?php echo $encoded_title; ?>&body=<?php echo $encoded_url; ?>" aria-label="Share by email">
			<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 6-10 7L2 6"/></svg>
		</a>
	</div>
	<?php
}

function arch_render_news_card() {
	$palette      = array( 'forest', 'terracotta', 'hay' );
	$colour       = $palette[ absint( get_the_ID() ) % 3 ];
	$horseshoe    = '<svg width="40" height="40" viewBox="0 0 24 24" fill="none"><path d="M6.5 21c-1.4-4.2.2-9.6 2.9-12.2C11.3 6.9 12.7 6.9 14.6 8.8c2.7 2.6 4.3 8 2.9 12.2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/><circle cx="7.3" cy="19.2" r="0.5" fill="currentColor"/><circle cx="7.9" cy="16.6" r="0.5" fill="currentColor"/><circle cx="9.1" cy="14.2" r="0.5" fill="currentColor"/><circle cx="14.9" cy="14.2" r="0.5" fill="currentColor"/><circle cx="16.1" cy="16.6" r="0.5" fill="currentColor"/><circle cx="16.7" cy="19.2" r="0.5" fill="currentColor"/></svg>';
	?>
	<a href="<?php the_permalink(); ?>" class="news-card" data-reveal>
		<div class="news-card-media">
			<?php if ( has_post_thumbnail() ) : ?>
				<?php the_post_thumbnail( 'medium', array( 'alt' => get_the_title() ) ); ?>
			<?php else : ?>
				<div class="news-card-media-fallback news-card-media-<?php echo esc_attr( $colour ); ?>">
					<?php echo $horseshoe; // phpcs:ignore -- fixed, theme-defined SVG markup, not user input. ?>
				</div>
			<?php endif; ?>
			<span class="news-date-badge news-date-badge-<?php echo esc_attr( $colour ); ?>"><?php echo esc_html( get_the_date( 'M Y' ) ); ?></span>
		</div>
		<div class="news-card-body">
			<h4 class="font-serif"><?php the_title(); ?></h4>
			<p><?php echo esc_html( arch_short_excerpt( 18 ) ); ?></p>
			<span class="news-card-more">
				Read more
				<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
			</span>
		</div>
	</a>
	<?php
}

/**
 * Renders one Donation Method section on the Donate page — icon, title,
 * full content (bank details, shop info, whatever the admin wrote — full
 * rich content, not an excerpt, since these need to actually be readable),
 * and an optional external CTA button. Must be called inside a WP_Query
 * loop, after the_post().
 */
function arch_render_donation_method() {
	$icon      = arch_donation_icon( arch_get_field( 'icon' ) );
	$cta_label = arch_get_field( 'cta_label' );
	$cta_url   = arch_get_field( 'cta_url' );
	$slug      = get_post_field( 'post_name' );
	?>
	<div id="donate-<?php echo esc_attr( $slug ); ?>" class="donation-method" data-reveal>
		<div class="donation-method-icon" style="background:<?php echo esc_attr( $icon['bg'] ); ?>;">
			<?php echo $icon['svg']; // phpcs:ignore -- fixed, theme-defined SVG markup, not user input. ?>
		</div>
		<div class="donation-method-body">
			<h3 class="font-serif"><?php the_title(); ?></h3>
			<div class="donation-method-content"><?php the_content(); ?></div>
			<?php if ( $cta_url && $cta_label ) : ?>
				<a href="<?php echo esc_url( $cta_url ); ?>" target="_blank" rel="noopener" class="btn btn-primary"><?php echo esc_html( $cta_label ); ?></a>
			<?php endif; ?>
		</div>
	</div>
	<?php
}

/**
 * Finds the Page using the "Equine Adoption" page template, so homepage
 * equine cards can link straight through to it. Returns '#equines' (the
 * homepage teaser section) as a harmless fallback if that page hasn't been
 * created yet, rather than a dead link.
 */
function arch_get_adoption_page_url() {
	static $url = null;
	if ( $url !== null ) {
		return $url;
	}
	$pages = get_posts( array(
		'post_type'      => 'page',
		'posts_per_page' => 1,
		'meta_key'       => '_wp_page_template',
		'meta_value'     => 'page-adoption.php',
	) );
	$url = ! empty( $pages ) ? get_permalink( $pages[0] ) : '#equines';
	return $url;
}

/**
 * Fetches the Adoption page's own "Apply to Adopt Now" button URL, so an
 * individual Equine page's Adopt button reuses the exact same destination
 * rather than having its own separate, possibly-inconsistent setting.
 */
function arch_get_adoption_form_url() {
	$pages = get_posts( array(
		'post_type'      => 'page',
		'posts_per_page' => 1,
		'meta_key'       => '_wp_page_template',
		'meta_value'     => 'page-adoption.php',
	) );
	if ( empty( $pages ) ) {
		return arch_get_adoption_form_page_url();
	}
	$url = arch_get_field( 'adoption_form_url', $pages[0]->ID );
	return $url ? $url : arch_get_adoption_form_page_url();
}

/**
 * Fixed price for the small "buy [horse] some treats" ask on each equine's
 * own page. Same amount for every horse by design — the personalisation is
 * the horse's name in the button copy, not a different price per animal.
 * Change this one value to update the amount site-wide.
 */
if ( ! defined( 'ARCH_TREAT_DONATION_AMOUNT' ) ) {
	define( 'ARCH_TREAT_DONATION_AMOUNT', 5 );
}

/**
 * Builds the Donorbox link for the treat-donation button. This pre-fills
 * the amount only, the same way the Wishlist "Buy" buttons do — it does
 * NOT earmark the gift to that specific horse on Donorbox's side, it's a
 * personalised ask, not a tracked per-horse fund.
 */
function arch_get_treat_donation_url() {
	return 'https://donorbox.org/donate-to-arch?amount=' . absint( ARCH_TREAT_DONATION_AMOUNT );
}

/**
 * Finds the Page using the "Donate" page template, so every "Donate"
 * button site-wide can point to it. Falls back to the direct Donorbox
 * link if that page hasn't been created yet, rather than a dead link —
 * donations are too important to risk a broken button in the meantime.
 */
function arch_get_donate_page_url() {
	static $url = null;
	if ( $url !== null ) {
		return $url;
	}
	$pages = get_posts( array(
		'post_type'      => 'page',
		'posts_per_page' => 1,
		'meta_key'       => '_wp_page_template',
		'meta_value'     => 'page-donate.php',
	) );
	$url = ! empty( $pages ) ? get_permalink( $pages[0] ) : 'https://donorbox.org/donate-to-arch?amount=10';
	return $url;
}

/**
 * Finds the Page using the "Charity Shop" page template, so nav items,
 * the Donation Methods list, or anything else on the site can link to it
 * directly. Falls back to the homepage's Contact section if that page
 * hasn't been created yet, rather than a dead link.
 */
function arch_get_shop_page_url() {
	static $url = null;
	if ( $url !== null ) {
		return $url;
	}
	$pages = get_posts( array(
		'post_type'      => 'page',
		'posts_per_page' => 1,
		'meta_key'       => '_wp_page_template',
		'meta_value'     => 'page-shop.php',
	) );
	$url = ! empty( $pages ) ? get_permalink( $pages[0] ) : home_url( '/#contact' );
	return $url;
}

/**
 * Finds the Page using the "Volunteer" page template, so the "Get
 * Involved" / "Become a volunteer" links can point to it. Falls back to
 * the homepage's "Join the Herd" section if that page hasn't been created
 * yet, rather than a dead link.
 */
/**
 * Finds the site's Volunteer page by its URL slug ("volunteer"), not by
 * which template it uses — deliberately different from how the Adoption
 * and Donate pages are found. Those templates are each meant for exactly
 * one page, so "find the page using this template" works fine. The
 * Volunteer page uses the generic, reusable "Content Page with Button"
 * template, which could be applied to several different pages at once —
 * so there'd be no way to tell which one is "the" Volunteer page by
 * template alone. Give whichever page should be the Volunteer destination
 * the URL slug "volunteer" and this will find it correctly regardless of
 * how many other pages also use the same template for other purposes.
 */
/**
 * Finds the Page using the "Volunteer" page template, same pattern as
 * Donate/Adoption/Shop. Falls back to the homepage's Help section if that
 * page hasn't been created (or the template hasn't been assigned) yet.
 */
function arch_get_volunteer_page_url() {
	static $url = null;
	if ( $url !== null ) {
		return $url;
	}
	$pages = get_posts( array(
		'post_type'      => 'page',
		'posts_per_page' => 1,
		'meta_key'       => '_wp_page_template',
		'meta_value'     => 'page-volunteer.php',
	) );
	$url = ! empty( $pages ) ? get_permalink( $pages[0] ) : home_url( '/#help' );
	return $url;
}

/**
 * Finds the Page using the "Adoption Form" page template — the page an
 * adopter actually fills in, distinct from the main Adoption info page.
 * Falls back to a guessed URL if this page hasn't been created (or the
 * template hasn't been assigned) yet, rather than a dead link.
 */
function arch_get_adoption_form_page_url() {
	static $url = null;
	if ( $url !== null ) {
		return $url;
	}
	$pages = get_posts( array(
		'post_type'      => 'page',
		'posts_per_page' => 1,
		'meta_key'       => '_wp_page_template',
		'meta_value'     => 'page-adoption-form.php',
	) );
	$url = ! empty( $pages ) ? get_permalink( $pages[0] ) : home_url( '/adoption-form/' );
	return $url;
}

/**
 * Finds the Page using the "Report an Equine" page template, same pattern
 * as Donate/Adoption/Shop/Volunteer. Falls back to the homepage's Contact
 * section if that page hasn't been created (or the template assigned) yet.
 */
function arch_get_reporting_page_url() {
	static $url = null;
	if ( $url !== null ) {
		return $url;
	}
	$pages = get_posts( array(
		'post_type'      => 'page',
		'posts_per_page' => 1,
		'meta_key'       => '_wp_page_template',
		'meta_value'     => 'page-report-abuse.php',
	) );
	$url = ! empty( $pages ) ? get_permalink( $pages[0] ) : home_url( '/#contact' );
	return $url;
}

/**
 * Finds the Page using the "In Loving Memory" page template. Falls back
 * to the homepage's equine section if this one hasn't been created (or
 * the template assigned) yet, rather than a dead link.
 */
function arch_get_memoriam_page_url() {
	static $url = null;
	if ( $url !== null ) {
		return $url;
	}
	$pages = get_posts( array(
		'post_type'      => 'page',
		'posts_per_page' => 1,
		'meta_key'       => '_wp_page_template',
		'meta_value'     => 'page-memoriam.php',
	) );
	$url = ! empty( $pages ) ? get_permalink( $pages[0] ) : home_url( '/#equines' );
	return $url;
}

/**
 * Finds the site's News archive URL — this uses WordPress's own built-in
 * "Posts page" setting (Settings → Reading), not a custom page template
 * like the Adoption page, since a blog listing is something core WordPress
 * already handles natively. Falls back to the homepage's News section if
 * that setting hasn't been configured yet, rather than a dead link.
 */
function arch_get_news_page_url() {
	$page_id = (int) get_option( 'page_for_posts' );
	if ( $page_id ) {
		return get_permalink( $page_id );
	}
	return home_url( '/#news' );
}

/**
 * Admin notice nudging whoever set this up to install ACF, since several
 * theme features silently do nothing without it.
 */
function arch_acf_missing_notice() {
	if ( function_exists( 'get_field' ) ) {
		return;
	}
	echo '<div class="notice notice-warning"><p><strong>ARCH theme:</strong> install and activate the free <em>Advanced Custom Fields</em> plugin (Plugins &rarr; Add New &rarr; search "Advanced Custom Fields") to enable the Availability, Meta Line, Price, and Icon fields on Equines and Wishlist Items.</p></div>';
}
add_action( 'admin_notices', 'arch_acf_missing_notice' );

/**
 * Maps the Wishlist Item "icon" select field to one of the theme's
 * hand-drawn SVG icons. Returns an array with 'svg' and 'bg' keys.
 *
 * To add a genuinely new icon: add a new $icons entry here, then add the
 * matching option to the 'field_wishlist_icon' choices array above.
 */
function arch_wishlist_icon( $key ) {
	$icons = array(
		'vet_wrap' => array(
			'bg'   => 'rgba(181,86,60,.22)',
			'icon' => '<span class="wishlist-emoji" role="img" aria-label="Bandages">🩹</span>',
		),
		'halter' => array(
			'bg'   => 'rgba(199,154,86,.3)',
			'icon' => '<span class="wishlist-emoji" role="img" aria-label="Halter and lead rope">🪢</span>',
		),
		'spray' => array(
			'bg'   => 'rgba(62,74,52,.2)',
			'icon' => '<span class="wishlist-emoji" role="img" aria-label="Fly spray">🌿</span>',
		),
		'tooth' => array(
			'bg'   => 'rgba(181,86,60,.22)',
			'icon' => '<span class="wishlist-emoji" role="img" aria-label="Dental revision">🦷</span>',
		),
		'capsule' => array(
			'bg'   => 'rgba(199,154,86,.3)',
			'icon' => '<span class="wishlist-emoji" role="img" aria-label="Deworming">💊</span>',
		),
		'bottle' => array(
			'bg'   => 'rgba(62,74,52,.2)',
			'icon' => '<span class="wishlist-emoji" role="img" aria-label="Shampoo">🧴</span>',
		),
	);

	return isset( $icons[ $key ] ) ? $icons[ $key ] : $icons['vet_wrap'];
}

/**
 * Icon set for Donation Methods, same pattern as arch_wishlist_icon().
 */
function arch_donation_icon( $key ) {
	$icons = array(
		'card' => array(
			'bg'  => 'rgba(181,86,60,.1)',
			'svg' => '<svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#B5563C" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2.5"/><line x1="2" y1="10" x2="22" y2="10"/><line x1="6" y1="15" x2="10" y2="15"/></svg>',
		),
		'bank' => array(
			'bg'  => 'rgba(62,74,52,.1)',
			'svg' => '<svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#3E4A34" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10 12 4l9 6"/><line x1="4" y1="10" x2="4" y2="19"/><line x1="9" y1="10" x2="9" y2="19"/><line x1="15" y1="10" x2="15" y2="19"/><line x1="20" y1="10" x2="20" y2="19"/><line x1="2" y1="19" x2="22" y2="19"/></svg>',
		),
		'shop' => array(
			'bg'  => 'rgba(199,154,86,.15)',
			'svg' => '<svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#9A452F" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 8 5.5 3h13L20 8"/><path d="M4 8h16v11a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 4 19V8Z"/><path d="M9 12a3 3 0 0 0 6 0"/></svg>',
		),
		'fundraising' => array(
			'bg'  => 'rgba(181,86,60,.1)',
			'svg' => '<svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#B5563C" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21c-4-3-8-6.5-8-11a5 5 0 0 1 8-4 5 5 0 0 1 8 4c0 4.5-4 8-8 11Z"/><path d="M12 8v6M9 11h6"/></svg>',
		),
		'sponsorship' => array(
			'bg'  => 'rgba(199,154,86,.15)',
			'svg' => '<svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#9A452F" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2 14.5 8.5 21 9.5 16.5 14 17.7 20.5 12 17.3 6.3 20.5 7.5 14 3 9.5 9.5 8.5 12 2Z"/></svg>',
		),
		'teaming' => array(
			'bg'  => 'rgba(62,74,52,.1)',
			'svg' => '<svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#3E4A34" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="8" r="3.2"/><path d="M2.8 20c.5-3.3 3-5.5 6.2-5.5s5.7 2.2 6.2 5.5"/><circle cx="17" cy="8.5" r="2.4"/><path d="M16 14.8c2.6.3 4.4 2.2 4.8 5.2"/></svg>',
		),
		'heart' => array(
			'bg'  => 'rgba(181,86,60,.1)',
			'svg' => '<svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#B5563C" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>',
		),
	);

	return isset( $icons[ $key ] ) ? $icons[ $key ] : $icons['heart'];
}

/**
 * -----------------------------------------------------------------------
 * EDITABLE HOME CONTENT (Hero / About / Contact via ACF fields)
 * -----------------------------------------------------------------------
 * Homepage text is edited as plain ACF fields (see "Homepage Content
 * Settings" above), same as every other settings-driven page on this
 * site — Donate, Adoption, Shop, Volunteer, Report an Equine. Earlier
 * versions of this theme used named anchor blocks in the page content
 * instead; that approach was dropped because it required editing live
 * page content and a hidden "HTML anchor" setting, which was confusing
 * and easy to get wrong compared to a labelled field.
 */

/**
 * Gets a homepage text field's value from the "home-content" page. Falls
 * back to $default if the page doesn't exist, ACF isn't installed, or the
 * field is empty — the site never shows blank content.
 */
function arch_home_text( $field_name, $default = '' ) {
	$page = get_page_by_path( 'home-content' );
	if ( ! $page || ! function_exists( 'get_field' ) ) {
		return $default;
	}
	$value = get_field( $field_name, $page->ID );
	return $value ? $value : $default;
}

/**
 * Gets the URL of a homepage photo (hero_photo or about_photo) — these are
 * ACF Image fields on the "home-content" page, the same reliable mechanism
 * Equine photos already use (Featured Image), just via a named field instead
 * since one page can't have two Featured Images. Falls back to $default if
 * the page doesn't exist, ACF isn't installed, or the field is empty.
 */
function arch_home_image_url( $field_name, $default = '' ) {
	$page = get_page_by_path( 'home-content' );
	if ( ! $page || ! function_exists( 'get_field' ) ) {
		return $default;
	}
	$url = get_field( $field_name, $page->ID );
	return $url ? $url : $default;
}
/**
 * Gets the hero photo's click-through link, set via ACF on the "home-content"
 * page. Returns '' (not $default) if unset, ACF isn't installed, or the page
 * doesn't exist — an empty string is what content-home.php checks against to
 * decide whether to wrap the photo in a link at all.
 */
function arch_get_hero_photo_link() {
	$page = get_page_by_path( 'home-content' );
	if ( ! $page || ! function_exists( 'get_field' ) ) {
		return '';
	}
	$url = get_field( 'hero_photo_link', $page->ID );
	return $url ? $url : '';
}

add_action( 'admin_notices', 'arch_home_content_missing_notice' );

/**
 * Nudges whoever set the site up if the "home-content" page doesn't
 * exist yet, since the Hero/About/Contact text will silently fall back
 * to the original design text without it — not broken, just not editable.
 */
function arch_home_content_missing_notice() {
	if ( get_page_by_path( 'home-content' ) ) {
		return;
	}
	echo '<div class="notice notice-info"><p><strong>ARCH theme:</strong> create a Page with the slug <code>home-content</code> to make the Hero, About, and Contact text editable — the fields will appear automatically once the page is created (see the "Homepage Content Settings" box). Until then, the original design text is shown.</p></div>';
}

/**
 * ===========================================================
 * Admin-friendliness for non-technical volunteers
 * ===========================================================
 * Everything below this point changes how the wp-admin dashboard looks
 * and is organised — none of it touches the public-facing site. Aimed at
 * volunteers who aren't comfortable in WordPress, not at developers.
 */

/**
 * A "cheat sheet" dashboard widget — the handful of things about this
 * specific site that aren't obvious from the admin screens alone. Update
 * the HTML below directly if these conventions ever change.
 */
function arch_add_dashboard_widget() {
	wp_add_dashboard_widget(
		'arch_cheat_sheet',
		'ARCH Website — Quick Guide',
		'arch_render_dashboard_widget'
	);
}
add_action( 'wp_dashboard_setup', 'arch_add_dashboard_widget' );

function arch_render_dashboard_widget() {
	$home_page = get_page_by_path( 'home-content' );
	$shop_page = get_posts( array(
		'post_type'      => 'page',
		'posts_per_page' => 1,
		'meta_key'       => '_wp_page_template',
		'meta_value'     => 'page-shop.php',
	) );
	$shop_page = ! empty( $shop_page ) ? $shop_page[0] : null;
	?>
	<p style="margin-top:0;"><strong>Quick links to the two most-edited pages:</strong><br>
		<?php if ( $home_page ) : ?>
			<a href="<?php echo esc_url( get_edit_post_link( $home_page->ID ) ); ?>">Edit Homepage Content →</a> (Star of the Month, hero link, opening hours, contact text)<br>
		<?php else : ?>
			<em>Homepage Content page not found</em> — create a Page with the slug <code>home-content</code>.<br>
		<?php endif; ?>
		<?php if ( $shop_page ) : ?>
			<a href="<?php echo esc_url( get_edit_post_link( $shop_page->ID ) ); ?>">Edit Charity Shop Page →</a> (opening hours, donation info)
		<?php else : ?>
			<em>Charity Shop page not found or template not assigned.</em>
		<?php endif; ?>
	</p>
	<hr>
	<p><strong>Regular tasks:</strong></p>
	<p><strong>Adding a horse:</strong> go to <em>Equines &rarr; Add New</em>. Fill in the facts, an About the Horse photo, and (optional) a Rescue Story and Rescue Photo.</p>
	<p><strong>Updating a horse:</strong> go to <em>Equines</em>, click the horse's name, edit, then Update.</p>
	<p><strong>Adding news:</strong> go to <em>News</em> in the sidebar on the left, and create a new post.</p>
	<p><strong>Photos:</strong> for a horse's main photo, use a <em>landscape</em> photo (wider than tall) if you can — portrait photos get cropped oddly in the hero banner.</p>
	<p><strong>Occasional tasks:</strong> adding/removing a Sponsor, adding Volunteer Photos, and updating Donation Methods all have their own item in the sidebar on the left. <strong>Wishlist Items</strong> automatically show up on both the homepage and the Donate page — you only need to add them in one place.</p>
	<p><strong>Important, if you edit a Page directly:</strong> the Donate, Adoption, Charity Shop, Volunteer, and Report an Equine pages each need a specific Template selected (find it under <em>Page Attributes</em> on the right when editing that page). If one of these pages ever seems to stop working correctly, check that template setting first before assuming something's broken.</p>
	<p><strong>If you need a new page for content,</strong> then use the "Content Page with Button" template. This is a generic design which will allow you to add content blocks to the page yourself without the restriction of a template design.</p>
	<p style="margin-bottom:0;"><em>If anything here is unclear or something on the site looks broken, contact your web developer rather than guessing — most of this site's design decisions build on each other, so a well-intentioned fix in one place can sometimes affect another page unexpectedly.</em></p>
	<?php
}

/**
 * Reorders the admin sidebar menu so the content volunteers actually
 * manage sits at the top, and pushes developer/site-settings-type menus
 * (Appearance, Plugins, Users, Tools, Settings) to the bottom. Purely
 * cosmetic reordering — doesn't hide or restrict anything.
 */
function arch_custom_menu_order( $menu_order ) {
	if ( ! $menu_order ) {
		return true;
	}
	return array(
		'index.php',                          // Dashboard
		'edit.php?post_type=equine',          // Equines — most frequent task
		'edit.php',                           // Posts (News) — most frequent task
		'edit.php?post_type=wishlist_item',   // Wishlist Items
		'edit.php?post_type=sponsor',         // Sponsors — less frequent
		'edit.php?post_type=volunteer_photo', // Volunteer Photos — less frequent
		'edit.php?post_type=donation_method', // Donation Methods — less frequent
		'edit.php?post_type=page',            // Pages — rarely needed directly; see dashboard shortcuts instead
		'upload.php',                         // Media
		'separator1',
		'edit-comments.php',
		'themes.php',
		'plugins.php',
		'users.php',
		'tools.php',
		'options-general.php',
	);
}
add_filter( 'custom_menu_order', '__return_true' );
add_filter( 'menu_order', 'arch_custom_menu_order' );

/**
 * Relabels the built-in "Posts" menu to "News" throughout wp-admin, since
 * News on the public site is powered by core WordPress Posts, and that
 * isn't obvious to someone who's never used WordPress before.
 */
function arch_relabel_posts_menu() {
	global $menu, $submenu;
	foreach ( $menu as $key => $value ) {
		if ( $value[2] === 'edit.php' ) {
			$menu[ $key ][0] = 'News';
		}
	}
	if ( isset( $submenu['edit.php'] ) ) {
		foreach ( $submenu['edit.php'] as $key => $value ) {
			if ( $value[2] === 'edit.php' ) {
				$submenu['edit.php'][ $key ][0] = 'All News';
			} elseif ( $value[2] === 'post-new.php' ) {
				$submenu['edit.php'][ $key ][0] = 'Add New News Post';
			}
		}
	}
}
add_action( 'admin_menu', 'arch_relabel_posts_menu', 999 );

/**
 * Restricts which blocks are available in the editor for Pages, since most
 * of this theme's pages only ever need simple text/image/button content —
 * anything more (Columns, Embeds, Galleries, etc.) risks breaking the
 * page's intended layout if a volunteer adds it by accident.
 *
 * The one exception is the generic "Content Page with Button" template
 * (page-content-cta.php) — that page is meant to be a flexible, general-
 * purpose page, so it keeps the full block library.
 *
 * Only applies to the "page" post type — Equines, Wishlist Items, and the
 * other custom post types are untouched, since they're edited entirely
 * through their own ACF fields rather than free-form block content.
 */
function arch_restrict_page_blocks( $allowed_block_types, $editor_context ) {
	if ( empty( $editor_context->post ) || $editor_context->post->post_type !== 'page' ) {
		return $allowed_block_types;
	}
	if ( get_page_template_slug( $editor_context->post->ID ) === 'page-content-cta.php' ) {
		return $allowed_block_types;
	}
	return array(
		'core/paragraph',
		'core/heading',
		'core/image',
		'core/buttons', // the wrapper Button lives inside — needed for Button to work at all
		'core/button',
	);
}
add_filter( 'allowed_block_types_all', 'arch_restrict_page_blocks', 10, 2 );

/**
 * Styles ACF field group boxes on the Page edit screen so they're easier
 * to read — a coloured header bar, clearer borders, more breathing room.
 * This only ever loads on Pages (not Equines, Posts, or anywhere else in
 * wp-admin), and only targets ACF's own box markup (.acf-postbox), so
 * WordPress's own core metaboxes elsewhere are untouched. Admin-only:
 * nothing here can reach the public-facing site.
 */
function arch_admin_page_styles() {
	$screen = get_current_screen();
	if ( ! $screen || $screen->post_type !== 'page' || ! in_array( $screen->base, array( 'post', 'post-new' ), true ) ) {
		return;
	}
	?>
	<style>
		.acf-postbox.postbox { border: 1px solid #d8cdb4; border-radius: 6px; overflow: hidden; margin-bottom: 20px; }
		.acf-postbox.postbox > .postbox-header,
		.acf-postbox.postbox > h2.hndle,
		.acf-postbox.postbox > .postbox-header:hover,
		.acf-postbox.postbox > h2.hndle:hover {
			background: #2B3524 !important;
			border-bottom: 1px solid #d8cdb4;
		}
		.acf-postbox.postbox > .postbox-header .hndle,
		.acf-postbox.postbox > .postbox-header .hndle span,
		.acf-postbox.postbox > h2.hndle,
		.acf-postbox.postbox > h2.hndle span,
		.acf-postbox.postbox > h2.hndle:hover,
		.acf-postbox.postbox > .postbox-header .hndle:hover {
			color: #F6F1E6 !important;
			font-weight: 600;
			padding: 10px 4px;
		}
		.acf-postbox .inside { padding: 16px 12px; background: #FBF8F1; }
		.acf-postbox .acf-field { border-top-color: #EDE4D0 !important; padding: 14px 0 !important; }
		.acf-postbox .acf-label label { color: #2B3524; font-weight: 600; }
		.acf-postbox .acf-input input[type="text"],
		.acf-postbox .acf-input input[type="url"],
		.acf-postbox .acf-input textarea {
			border-color: #d8cdb4;
			background: #fff;
		}
		.acf-postbox .acf-input input[type="text"]:focus,
		.acf-postbox .acf-input input[type="url"]:focus,
		.acf-postbox .acf-input textarea:focus {
			border-color: #B5563C;
			box-shadow: 0 0 0 1px #B5563C;
		}
	</style>
	<?php
}
add_action( 'admin_head', 'arch_admin_page_styles' );
