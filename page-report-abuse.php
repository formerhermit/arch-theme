<?php
/**
 * Template Name: Report an Equine
 *
 * A dedicated page for reporting a neglected, mistreated, or abandoned
 * equine — safety-critical content, structured by what the reader actually
 * needs to do first (get to the right authority), then what ARCH can help
 * with, then the full set of considerations. Nothing from the source
 * content is condensed or dropped — only reorganised for readability.
 *
 * @package ARCH
 */

get_header();

$alertcops_url = arch_get_field( 'report_alertcops_url' );
if ( ! $alertcops_url ) {
	$alertcops_url = 'https://alertcops.ses.mir.es/publico/alertcops/en/';
}
?>

<section class="report-intro">
  <div class="wrap">
    <div class="report-intro-content">
      <h1 class="font-serif"><?php the_title(); ?></h1>
      <?php
      if ( have_posts() ) :
        while ( have_posts() ) : the_post();
          the_content();
        endwhile;
      else :
        ?>
        <p>We wish we could investigate every complaint we receive, but we are a small charity with few volunteers and limited resources. Andalucía is a huge region of over 87,000 square kilometres, so it is physically impossible for us to attend to every report. We rely on the public to get involved to the full extent of their capability.</p>
        <p>As a rescue centre, we do not have the necessary authorisation to take any action. We can usually only take action when requested to do so by Seprona or the Policía Local.</p>
        <p>The person who has identified the case personally and has first-hand information needs to get involved and report all of the necessary information in order for the relevant authority to act accordingly.</p>
        <?php
      endif;
      ?>
    </div>
  </div>
</section>

<section class="report-paths">
  <div class="wrap">
    <p class="report-section-label">Step 1 — Report to the Right Authority</p>
    <div class="report-paths-grid">
      <div class="report-path-card report-path-seprona">
        <span class="report-path-icon" aria-hidden="true">🚨</span>
        <h3>Injured, Mistreated, or Neglected</h3>
        <p>This is the responsibility of Seprona. Contact the Guardia Civil and request to report the case to Seprona — or use the AlertCops app and select the "Maltrato Animal" option.</p>
        <a href="<?php echo esc_url( $alertcops_url ); ?>" target="_blank" rel="noopener" class="btn btn-primary btn-small">Report via AlertCops</a>
      </div>
      <div class="report-path-card report-path-local">
        <span class="report-path-icon" aria-hidden="true">🏛️</span>
        <h3>Abandoned</h3>
        <p>This is the responsibility of the local Ayuntamiento. Please contact your Policía Local directly — the right one depends on where you are, so we can't link to a single number here.</p>
      </div>
    </div>
    <p class="report-paths-note">Please don't just share the post on social media — it has no benefit, and can sometimes hamper investigations if the owner decides to move or hide the animal(s) in question.</p>
  </div>
</section>

<section class="report-help">
  <div class="wrap">
    <div class="report-help-box">
      <h2 class="font-serif">Can't Reach the Authorities Yourself?</h2>
      <p>If you have a valid reason for not being able to contact the relevant authorities, or you believe that the animal's life is in immediate danger and urgent action is required, we will intervene and report the matter on your behalf. We'll need as much of the following as possible:</p>
      <div class="report-help-grid">
        <div class="info-row">
          <div class="icon-circle"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg></div>
          <div><h4>Why you're concerned</h4><p>Details of what you've seen and why it worries you.</p></div>
        </div>
        <div class="info-row">
          <div class="icon-circle"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg></div>
          <div><h4>Equine details</h4><p>Horse, donkey, or pony, and colour.</p></div>
        </div>
        <div class="info-row">
          <div class="icon-circle"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
          <div><h4>Name of owner</h4><p>If known.</p></div>
        </div>
        <div class="info-row">
          <div class="icon-circle"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg></div>
          <div><h4>Location</h4><p>The more precise the better — a pin or Google Maps location helps even if the exact address is unknown.</p></div>
        </div>
        <div class="info-row">
          <div class="icon-circle"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2Z"/><circle cx="12" cy="13" r="4"/></svg></div>
          <div><h4>Photos</h4><p>If you can, take pictures safely and without trespassing.</p></div>
        </div>
        <div class="info-row">
          <div class="icon-circle"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92Z"/></svg></div>
          <div><h4>Your contact details</h4><p>So the authorities can reach you if they need more information. These stay anonymous if you request it.</p></div>
        </div>
      </div>
      <div class="report-help-cta">
        <a href="<?php echo esc_url( home_url( '/#contact' ) ); ?>" class="btn btn-primary">Contact Us</a>
      </div>
    </div>
  </div>
</section>

<section class="report-considerations">
  <div class="wrap">
    <p class="report-section-label">Before You Report</p>

    <div class="report-rules-grid">
      <div class="report-rule-card">Only report what you have <strong>personally seen</strong> — we can't act on second-hand information, including things seen on social media.</div>
      <div class="report-rule-card">We can't respond to concerns reported through a shared post — please send a <strong>private message</strong> instead.</div>
      <div class="report-rule-card">If you've already reported to the authorities and they've confirmed they're investigating, please don't also contact other organisations or charities.</div>
      <div class="report-rule-card"><strong>Hobbling is illegal</strong> in Spain and should always be reported.</div>
    </div>

    <div class="report-context-box">
      <h4>Context worth knowing</h4>
      <p>Horses are managed and kept in many different ways across Spain. We may not always agree with how a horse is kept, but if its needs are being met and it isn't suffering, we're unlikely to be able to take any action. Many horses stand in the sun by choice, even when shade is available.</p>
      <p>In summer, water is often only given morning and evening — left out all day it heats up and can cause colic, so this alone isn't a sign of neglect.</p>
    </div>

    <div class="report-thin-box">
      <h4>If a horse seems thin, ask yourself:</h4>
      <ul>
        <li>Is it possible that it is old and has few teeth?</li>
        <li>Is it possible that it has been rescued and is being cared for?</li>
        <li>Is it possible that it has been sick?</li>
        <li>Is it thin by English or Spanish standards?</li>
        <li>Can you ask a neighbour, close to where the horse is kept, for details of who owns it?</li>
      </ul>
    </div>

    <div class="report-warning-box">
      <h3>⚠ Please Don't Feed or Water the Animal Yourself</h3>
      <p>Unless you have made every effort to report the matter through the relevant authorities and suspect that the animal's life is in immediate danger and urgent action is required.</p>
      <p>You don't know if the equine is on medication or already receiving treatment that could be affected by unintended contravention of veterinary instruction. If Seprona responds to a report of a "neglected" equine and finds evidence that food and water is being provided, they will not intervene — this may prejudice any chance of prosecuting an abusive owner.</p>
    </div>
  </div>
</section>

<?php get_footer(); ?>
