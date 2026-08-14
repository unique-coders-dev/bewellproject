<?php
/**
 * Template Name: Contact
 *
 * Ported from src/pages/Contact.tsx.
 *
 * @package bewell
 */

defined( 'ABSPATH' ) || exit;

get_header();

$bewell_subjects = array(
	'Lifestyle Program Inquiry' => __( 'Lifestyle Program Inquiry', 'bewell' ),
	'Training Program Inquiry'  => __( 'Training Program Inquiry', 'bewell' ),
	'Hostel / Accommodation'    => __( 'Hostel / Accommodation', 'bewell' ),
	'Farm Products Order'       => __( 'Farm Products Order', 'bewell' ),
	'Employment Inquiry'        => __( 'Employment Inquiry', 'bewell' ),
	'General Question'          => __( 'General Question', 'bewell' ),
	'Other'                     => __( 'Other', 'bewell' ),
);

$bewell_contact_info = array(
	array(
		'icon'  => 'map-pin',
		'title' => __( 'Our Location', 'bewell' ),
		'lines' => array(
			__( 'Near Choto Daragar Hat', 'bewell' ),
			__( 'Beautiful hillside campus', 'bewell' ),
			__( 'Bangladesh', 'bewell' ),
		),
	),
	array(
		'icon'  => 'phone',
		'title' => __( 'Phone', 'bewell' ),
		'lines' => array( bewell_contact( 'phone' ), bewell_contact( 'phone_alt' ) ),
	),
	array(
		'icon'  => 'mail',
		'title' => __( 'Email', 'bewell' ),
		'lines' => array( bewell_contact( 'email' ), bewell_contact( 'email_alt' ) ),
	),
	array(
		'icon'  => 'clock',
		'title' => __( 'Office Hours', 'bewell' ),
		'lines' => array(
			__( 'Sunday – Thursday', 'bewell' ),
			__( '8:00 AM – 5:00 PM', 'bewell' ),
			__( 'Closed Friday evening & Saturday', 'bewell' ),
		),
	),
);
?>

<!-- Hero -->
<section class="relative pt-16 min-h-[40vh] flex items-center bg-primary">
	<div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
		<span class="bw-badge mb-4 bg-primary-foreground/15 text-primary-foreground border-primary-foreground/20"><?php esc_html_e( 'Contact Us', 'bewell' ); ?></span>
		<h1 class="text-4xl sm:text-5xl font-bold text-primary-foreground mb-4 max-w-2xl leading-tight">
			<?php esc_html_e( "We'd Love to Hear From You", 'bewell' ); ?>
		</h1>
		<p class="text-primary-foreground/80 text-lg max-w-xl leading-relaxed">
			<?php esc_html_e( 'Whether you have questions about our programs, want to book a stay, or just want to learn more — we are here to help.', 'bewell' ); ?>
		</p>
	</div>
</section>

<!-- Contact info + form -->
<section class="py-16 bg-background">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
		<div class="grid lg:grid-cols-3 gap-10">

			<div class="lg:col-span-1">
				<h2 class="text-2xl font-bold text-foreground mb-6"><?php esc_html_e( 'Get in Touch', 'bewell' ); ?></h2>

				<div class="space-y-5">
					<?php foreach ( $bewell_contact_info as $bewell_info ) : ?>
						<div class="flex items-start gap-3">
							<div class="w-9 h-9 rounded-lg bg-primary/10 flex items-center justify-center shrink-0 mt-0.5">
								<?php bewell_the_icon( $bewell_info['icon'], 'w-4 h-4 text-primary' ); ?>
							</div>
							<div>
								<p class="text-sm font-semibold text-foreground mb-0.5"><?php echo esc_html( $bewell_info['title'] ); ?></p>
								<?php foreach ( $bewell_info['lines'] as $bewell_line ) : ?>
									<p class="text-sm text-muted-foreground"><?php echo esc_html( $bewell_line ); ?></p>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>

				<div class="mt-8 p-4 rounded-lg bg-muted border border-border">
					<p class="text-sm font-medium text-foreground mb-1"><?php esc_html_e( 'Note on Sabbath Observance', 'bewell' ); ?></p>
					<p class="text-sm text-muted-foreground leading-relaxed">
						<?php esc_html_e( 'We observe the biblical Sabbath from Friday at sundown to Saturday at sundown. Our office is closed during this time. We will respond to all inquiries on Sunday.', 'bewell' ); ?>
					</p>
				</div>
			</div>

			<div class="lg:col-span-2">
				<h2 class="text-2xl font-bold text-foreground mb-6"><?php esc_html_e( 'Send Us a Message', 'bewell' ); ?></h2>

				<div id="bewell-form">
				<?php if ( bewell_form_succeeded( 'contact' ) ) : ?>
					<?php
					bewell_success_panel(
						__( 'Message Sent!', 'bewell' ),
						__( 'Thank you for reaching out. We will respond to your message within 1–2 business days.', 'bewell' )
					);
					?>
				<?php else : ?>
					<div class="bw-card border-border">
						<div class="p-6 sm:p-8">
							<form method="post" action="<?php echo esc_url( bewell_url( 'contact' ) ); ?>#bewell-form" class="space-y-5" novalidate>
								<?php
								bewell_form_fields( 'contact' );
								bewell_form_error_banner( 'contact' );
								?>

								<div class="grid sm:grid-cols-2 gap-4">
									<?php
									bewell_text_field(
										array(
											'form'         => 'contact',
											'name'         => 'name',
											'label'        => __( 'Your Name', 'bewell' ),
											'required'     => true,
											'placeholder'  => __( 'Full name', 'bewell' ),
											'autocomplete' => 'name',
										)
									);
									bewell_text_field(
										array(
											'form'         => 'contact',
											'name'         => 'phone',
											'label'        => __( 'Phone Number', 'bewell' ),
											'type'         => 'tel',
											'placeholder'  => '+880…',
											'autocomplete' => 'tel',
										)
									);
									?>
								</div>

								<?php
								bewell_text_field(
									array(
										'form'         => 'contact',
										'name'         => 'email',
										'label'        => __( 'Email Address', 'bewell' ),
										'type'         => 'email',
										'required'     => true,
										'placeholder'  => 'your@email.com',
										'autocomplete' => 'email',
									)
								);

								bewell_select_field(
									array(
										'form'        => 'contact',
										'name'        => 'subject',
										'label'       => __( 'Subject', 'bewell' ),
										'required'    => true,
										'placeholder' => __( 'What is this regarding?', 'bewell' ),
										'options'     => $bewell_subjects,
									)
								);

								bewell_textarea_field(
									array(
										'form'        => 'contact',
										'name'        => 'message',
										'label'       => __( 'Your Message', 'bewell' ),
										'required'    => true,
										'rows'        => 5,
										'placeholder' => __( 'Please describe how we can help you…', 'bewell' ),
									)
								);
								?>

								<button type="submit" class="bw-btn bw-btn-lg bw-btn-primary w-full">
									<?php esc_html_e( 'Send Message', 'bewell' ); ?>
									<?php bewell_the_icon( 'arrow-right', 'ml-2 w-4 h-4' ); ?>
								</button>

								<p class="text-xs text-muted-foreground text-center">
									<?php esc_html_e( 'Your message is kept private. We do not share your contact information.', 'bewell' ); ?>
								</p>
							</form>
						</div>
					</div>
				<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- Location -->
<section class="py-0">
	<div class="h-64 bg-muted flex items-center justify-center border-t border-border">
		<div class="text-center">
			<?php bewell_the_icon( 'map-pin', 'w-8 h-8 text-primary mx-auto mb-2' ); ?>
			<p class="text-foreground font-medium"><?php esc_html_e( 'Near Choto Daragar Hat', 'bewell' ); ?></p>
			<p class="text-muted-foreground text-sm mt-1"><?php esc_html_e( 'Directions available upon inquiry', 'bewell' ); ?></p>
		</div>
	</div>
</section>

<?php
get_footer();
