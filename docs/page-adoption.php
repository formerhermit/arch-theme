<?php
/**
 * Template Name: Equine Adoption
 *
 * Assign this to a Page via Page Attributes → Template in wp-admin.
 * Layout: editable intro content (normal page content, block editor) →
 * "Apply to Adopt Now" button → the full equine library, grouped by status,
 * with "Available to Adopt" further split into Horses / Ponies / Donkeys & Mules.
 *
 * @package ARCH
 */

get_header();

$adoption_form_url = arch_get_field( 'adoption_form_url' );
$adoption_form_url = $adoption_form_url ? $adoption_form_url : arch_get_adoption_form_page_url();
?>

<section class="adoption-intro">
  <div class="wrap">
    <div class="adoption-intro-content">
      <h1 class="font-serif"><?php the_title(); ?></h1>
      <?php
      if ( have_posts() ) :
        while ( have_posts() ) : the_post();
          the_content();
        endwhile;
      else :
        ?>
        <p>At our rescue centre just outside Málaga, we try to rehome every equine once they're suitably rehabilitated. If you feel you can offer one of our equines a wonderful new home, view the galleries below and complete an Adoption Information form — we'll be in touch within 48 hours.</p>
        <?php
      endif;
      ?>
    </div>
    <div class="adoption-intro-cta">
      <a href="#equine-library" class="btn btn-primary">See Who's Available</a>
    </div>
  </div>
</section>

<section class="adoption-steps">
  <div class="wrap">
    <div class="adoption-steps-row">
      <div class="adoption-step">
        <span class="adoption-step-number">1</span>
        <span class="adoption-step-label">Browse the galleries</span>
      </div>
      <div class="adoption-step">
        <span class="adoption-step-number">2</span>
        <span class="adoption-step-label">Complete the adoption form</span>
      </div>
      <div class="adoption-step">
        <span class="adoption-step-number">3</span>
        <span class="adoption-step-label">We're in touch within 48 hours</span>
      </div>
    </div>
  </div>
</section>

<section class="adoption-expect">
  <div class="wrap">
    <p class="adoption-expect-label">What to Expect</p>
    <div class="adoption-expect-grid">
      <div class="adoption-expect-item">
        <span class="adoption-expect-icon" aria-hidden="true">🤝</span>
        <div><h4>A good match</h4><p>We make sure the animal suits you, and you suit them.</p></div>
      </div>
      <div class="adoption-expect-item">
        <span class="adoption-expect-icon" aria-hidden="true">🏡</span>
        <div><h4>Home check</h4><p>Required before any adoption goes ahead.</p></div>
      </div>
      <div class="adoption-expect-item">
        <span class="adoption-expect-icon" aria-hidden="true">📋</span>
        <div><h4>OCA license</h4><p>You'll need a valid OCA license to keep an equine at your own property, or use a livery that already holds one.</p></div>
      </div>
      <div class="adoption-expect-item">
        <span class="adoption-expect-icon" aria-hidden="true">🩺</span>
        <div><h4>The animal's health &amp; behaviour</h4><p>Rescue equines may have specific health or behavioural needs, which we'll discuss with you.</p></div>
      </div>
      <div class="adoption-expect-item">
        <span class="adoption-expect-icon" aria-hidden="true">💷</span>
        <div><h4>Adoption fee</h4><p>Discussed during the adoption process.</p></div>
      </div>
      <div class="adoption-expect-item">
        <span class="adoption-expect-icon" aria-hidden="true">🚚</span>
        <div><h4>Transport</h4><p>Arranged and paid for by the adopter.</p></div>
      </div>
      <div class="adoption-expect-item">
        <span class="adoption-expect-icon" aria-hidden="true">📝</span>
        <div><h4>Adoption contract</h4><p>Signed before your equine goes home with you.</p></div>
      </div>
      <div class="adoption-expect-item">
        <span class="adoption-expect-icon" aria-hidden="true">💌</span>
        <div><h4>Keeping us updated</h4><p>We'll ask you to share how your equine is getting on.</p></div>
      </div>
    </div>

    <blockquote class="adoption-thanks-note">
      Those who adopt our animals also deserve a big vote of thanks for giving a forever home to our beautiful equines. 🧡
    </blockquote>

    <div class="adoption-expect-cta">
      <a href="<?php echo esc_url( $adoption_form_url ); ?>" target="_blank" rel="noopener" class="btn btn-primary">Apply to Adopt Now</a>
    </div>
  </div>
</section>

<section class="equine-library" id="equine-library">
  <div class="wrap">
    <?php
    // Pull every equine once, then bucket them in PHP by status (and, for
    // "available", by type too) — simpler and cheaper than running six
    // separate WP_Query calls.
    $all_equines = new WP_Query( array(
      'post_type'      => 'equine',
      'posts_per_page' => -1,
      'orderby'        => 'menu_order title',
      'order'          => 'ASC',
    ) );

    $buckets = array(
      'available' => array( 'horse' => array(), 'pony' => array(), 'donkey_mule' => array() ),
      'foster'    => array(),
      'recovery'  => array(),
      'permanent' => array(),
      'adopted'   => array(),
      'memoriam'  => array(),
    );

    if ( $all_equines->have_posts() ) {
      foreach ( $all_equines->posts as $p ) {
        $status = arch_get_field( 'status', $p->ID );
        $status = $status ? $status : 'available';
        if ( ! isset( $buckets[ $status ] ) ) {
          $status = 'available'; // unknown/legacy value — don't lose the entry, just show it somewhere sensible.
        }
        if ( $status === 'available' ) {
          $type = arch_get_field( 'equine_type', $p->ID );
          $type = isset( $buckets['available'][ $type ] ) ? $type : 'horse';
          $buckets['available'][ $type ][] = $p->ID;
        } else {
          $buckets[ $status ][] = $p->ID;
        }
      }
    }
    wp_reset_postdata();

    $has_any_available = array_sum( array_map( 'count', $buckets['available'] ) ) > 0;
    ?>

    <?php if ( $has_any_available ) : ?>
      <div class="equine-status-section" data-reveal>
        <h2 class="equine-status-heading font-serif">Available to Adopt</h2>
        <?php foreach ( arch_equine_type_sections() as $type_key => $type_label ) : ?>
          <?php if ( empty( $buckets['available'][ $type_key ] ) ) continue; ?>
          <h3 class="equine-type-subheading"><?php echo esc_html( $type_label ); ?></h3>
          <div class="equine-grid">
            <?php foreach ( $buckets['available'][ $type_key ] as $post_id ) : ?>
              <?php
              global $post;
              $post = get_post( $post_id );
              setup_postdata( $post );
              arch_render_equine_card();
              ?>
            <?php endforeach; ?>
          </div>
        <?php endforeach; ?>
      </div>
      <?php wp_reset_postdata(); ?>
    <?php endif; ?>

    <?php foreach ( arch_equine_status_sections() as $status_key => $status_label ) : ?>
      <?php if ( empty( $buckets[ $status_key ] ) ) continue; ?>
      <div class="equine-status-section" data-reveal>
        <h2 class="equine-status-heading font-serif"><?php echo esc_html( $status_label ); ?></h2>
        <div class="equine-grid">
          <?php foreach ( $buckets[ $status_key ] as $post_id ) : ?>
            <?php
            global $post;
            $post = get_post( $post_id );
            setup_postdata( $post );
            arch_render_equine_card();
            ?>
          <?php endforeach; ?>
        </div>
      </div>
      <?php wp_reset_postdata(); ?>
    <?php endforeach; ?>

    <?php if ( ! $has_any_available && ! $all_equines->have_posts() ) : ?>
      <p class="equine-library-empty"><?php _e( 'No equines have been added yet. Add some from Equines &rarr; Add New in wp-admin.', 'arch' ); ?></p>
    <?php endif; ?>
  </div>
</section>

<section class="adoption-memoriam-link">
  <div class="wrap">
    <a href="<?php echo esc_url( arch_get_memoriam_page_url() ); ?>">🧡 In Loving Memory of Those We've Lost</a>
  </div>
</section>

<?php get_footer(); ?>
