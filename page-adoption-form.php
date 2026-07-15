<?php
/**
 * Template Name: Adoption Form
 *
 * The page an adopter actually fills in — distinct from the main Adoption
 * info page. Reframes the "what happens after you apply" steps as a
 * numbered sequence rather than repeating the main Adoption page's facts,
 * since someone landing here may not have read that page first, and the
 * fee/timeline details matter most right before they commit to the form.
 * The WPForms form itself is embedded below, untouched by this styling.
 *
 * @package ARCH
 */

get_header();
?>

<section class="adoption-form-intro">
  <div class="wrap">
    <h1 class="font-serif"><?php the_title(); ?></h1>
    <p class="adoption-form-lede">We're really excited to see you here. Here's exactly what happens when you submit the form below:</p>

    <div class="adoption-form-steps">
      <div class="adoption-form-step">
        <span class="adoption-form-step-number">1</span>
        <p>After you submit the form below, ARCH will assess your request.</p>
      </div>
      <div class="adoption-form-step">
        <span class="adoption-form-step-number">2</span>
        <p>If your request is approved, we'll draw up an adoption contract, prepare travel documents, and arrange transport.</p>
      </div>
      <div class="adoption-form-step">
        <span class="adoption-form-step-number">3</span>
        <p>You'll need to pay transport costs and the <strong>non-refundable adoption fee</strong> in full, upfront, before the animal is transported.</p>
      </div>
      <div class="adoption-form-step">
        <span class="adoption-form-step-number">4</span>
        <p>ARCH arranges change of ownership, if applicable.</p>
      </div>
      <div class="adoption-form-step">
        <span class="adoption-form-step-number">5</span>
        <p>We'll follow up with you periodically to find out how you're getting on.</p>
      </div>
    </div>

    <blockquote class="adoption-form-timeframe">
      The whole process can take anything from a few weeks to several months, depending on paperwork and individual circumstances.
    </blockquote>

    <div class="adoption-form-embed">
      <?php
      if ( have_posts() ) :
        while ( have_posts() ) : the_post();
          the_content();
        endwhile;
      else :
        ?>
        <p>Add the adoption form here by editing this page's content in wp-admin — paste in the WPForms shortcode the same way you already have been.</p>
        <?php
      endif;
      ?>
    </div>
  </div>
</section>

<?php get_footer(); ?>
