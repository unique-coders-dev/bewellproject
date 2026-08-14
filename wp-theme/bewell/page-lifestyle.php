<?php
/**
 * Template Name: Lifestyle Program
 *
 * Ported from src/pages/LifestyleProgram.tsx.
 *
 * @package bewell
 */

defined( 'ABSPATH' ) || exit;

get_header();

$bewell_included = array(
	__( 'Full medical assessment on arrival', 'bewell' ),
	__( 'Personalized lifestyle treatment plan', 'bewell' ),
	__( 'Plant-based whole food meals, 3x daily', 'bewell' ),
	__( 'Daily health lectures and education', 'bewell' ),
	__( 'Guided nature walks in the hills', 'bewell' ),
	__( 'Hydrotherapy treatments', 'bewell' ),
	__( 'Individual counseling sessions', 'bewell' ),
	__( 'Physical activity program', 'bewell' ),
	__( 'Discharge plan and follow-up guidance', 'bewell' ),
	__( 'Accommodation in our peaceful hostel', 'bewell' ),
);

$bewell_conditions = array(
	__( 'Heart Disease', 'bewell' ),
	__( 'Type 2 Diabetes', 'bewell' ),
	__( 'High Blood Pressure', 'bewell' ),
	__( 'Cancer (supportive care)', 'bewell' ),
	__( 'Depression & Anxiety', 'bewell' ),
	__( 'Obesity', 'bewell' ),
	__( 'Chronic Fatigue', 'bewell' ),
	__( 'Digestive Disorders', 'bewell' ),
	__( 'Arthritis', 'bewell' ),
	__( 'Kidney Disease', 'bewell' ),
	__( 'Liver Conditions', 'bewell' ),
	__( 'Other Lifestyle Illnesses', 'bewell' ),
);

$bewell_pricing = array(
	array(
		'name'        => __( 'Two-Week Program', 'bewell' ),
		'duration'    => __( '14 nights / 15 days', 'bewell' ),
		'price'       => __( 'Contact for pricing', 'bewell' ),
		'features'    => array(
			__( 'All meals included', 'bewell' ),
			__( 'Accommodation', 'bewell' ),
			__( 'Medical consultation', 'bewell' ),
			__( 'Daily programs', 'bewell' ),
			__( 'Follow-up plan', 'bewell' ),
		),
		'recommended' => false,
	),
	array(
		'name'        => __( 'Three-Week Program', 'bewell' ),
		'duration'    => __( '21 nights / 22 days', 'bewell' ),
		'price'       => __( 'Contact for pricing', 'bewell' ),
		'features'    => array(
			__( 'All meals included', 'bewell' ),
			__( 'Accommodation', 'bewell' ),
			__( 'Medical consultation', 'bewell' ),
			__( 'Daily programs', 'bewell' ),
			__( 'Follow-up plan', 'bewell' ),
			__( 'Additional deep-dive sessions', 'bewell' ),
			__( 'Extended counseling', 'bewell' ),
		),
		'recommended' => true,
	),
);

$bewell_testimonials = bewell_get_testimonials( 'lifestyle', 6 );
?>

<!-- Hero -->
<section class="relative pt-16 min-h-[60vh] flex items-center">
	<div class="absolute inset-0 bg-cover bg-center" style="<?php echo esc_attr( bewell_bg( 'scenery/IMG_3880.JPG' ) ); ?>"></div>
	<div class="absolute inset-0 bg-foreground/75"></div>

	<div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
		<span class="bw-badge mb-4 bg-primary/80 text-primary-foreground border-0"><?php esc_html_e( 'Lifestyle Program', 'bewell' ); ?></span>
		<h1 class="text-4xl sm:text-5xl font-bold text-white mb-4 max-w-2xl leading-tight">
			<?php esc_html_e( 'Reverse Chronic Illness', 'bewell' ); ?><br>
			<span class="text-primary"><?php esc_html_e( 'Naturally', 'bewell' ); ?></span>
		</h1>
		<p class="text-white/85 text-lg max-w-xl leading-relaxed mb-6">
			<?php esc_html_e( 'A focused two to three week intensive program where your body learns to heal. Expert care, natural food, fresh air, and daily education.', 'bewell' ); ?>
		</p>
		<div class="flex gap-6 flex-wrap">
			<div class="flex items-center gap-2 text-white/80 text-sm">
				<?php bewell_the_icon( 'clock', 'w-4 h-4 text-primary' ); ?>
				<?php esc_html_e( '2–3 weeks residential', 'bewell' ); ?>
			</div>
			<div class="flex items-center gap-2 text-white/80 text-sm">
				<?php bewell_the_icon( 'users', 'w-4 h-4 text-primary' ); ?>
				<?php esc_html_e( 'Small groups, personal care', 'bewell' ); ?>
			</div>
			<div class="flex items-center gap-2 text-white/80 text-sm">
				<?php bewell_the_icon( 'leaf', 'w-4 h-4 text-primary' ); ?>
				<?php esc_html_e( 'All meals included', 'bewell' ); ?>
			</div>
		</div>
	</div>
</section>

<!-- Conditions -->
<section class="py-16 bg-background">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
		<div class="grid lg:grid-cols-2 gap-12 items-center">
			<div>
				<span class="bw-badge mb-4 bg-accent text-accent-foreground border-0"><?php esc_html_e( 'Who We Help', 'bewell' ); ?></span>
				<h2 class="text-3xl font-bold text-foreground mb-4"><?php esc_html_e( 'Conditions We Address', 'bewell' ); ?></h2>
				<p class="text-muted-foreground mb-6 leading-relaxed">
					<?php esc_html_e( 'Our program has helped hundreds of people with serious lifestyle-related illnesses. Using a comprehensive approach, we help the body heal from the inside out.', 'bewell' ); ?>
				</p>
				<div class="grid grid-cols-2 gap-2">
					<?php foreach ( $bewell_conditions as $bewell_condition ) : ?>
						<div class="flex items-center gap-2 text-sm text-foreground">
							<?php bewell_the_icon( 'check', 'w-4 h-4 text-primary shrink-0' ); ?>
							<?php echo esc_html( $bewell_condition ); ?>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
			<div>
				<img src="<?php echo esc_url( bewell_img( 'meeting/IMG_4172.JPG' ) ); ?>"
					alt="<?php esc_attr_e( 'Fresh healthy food at BE WELL', 'bewell' ); ?>"
					class="rounded-xl shadow-lg w-full object-cover aspect-[4/3]" loading="lazy" decoding="async">
			</div>
		</div>
	</div>
</section>

<!-- What is included -->
<section class="py-16 bg-muted">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
		<div class="text-center mb-10">
			<span class="bw-badge mb-4 bg-accent text-accent-foreground border-0"><?php esc_html_e( 'Program Details', 'bewell' ); ?></span>
			<h2 class="text-3xl font-bold text-foreground mb-4"><?php esc_html_e( 'What Is Included', 'bewell' ); ?></h2>
			<p class="text-muted-foreground max-w-2xl mx-auto">
				<?php esc_html_e( 'Everything you need for a complete healing experience is provided.', 'bewell' ); ?>
			</p>
		</div>
		<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 max-w-4xl mx-auto">
			<?php foreach ( $bewell_included as $bewell_item ) : ?>
				<div class="flex items-center gap-3 bg-background rounded-lg px-4 py-3 border border-border">
					<div class="w-6 h-6 rounded-full bg-primary/15 flex items-center justify-center shrink-0">
						<?php bewell_the_icon( 'check', 'w-3.5 h-3.5 text-primary' ); ?>
					</div>
					<span class="text-sm text-foreground"><?php echo esc_html( $bewell_item ); ?></span>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- Pricing -->
<section class="py-16 bg-background">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
		<div class="text-center mb-10">
			<span class="bw-badge mb-4 bg-accent text-accent-foreground border-0"><?php esc_html_e( 'Pricing', 'bewell' ); ?></span>
			<h2 class="text-3xl font-bold text-foreground mb-4"><?php esc_html_e( 'Program Options', 'bewell' ); ?></h2>
			<p class="text-muted-foreground max-w-xl mx-auto">
				<?php esc_html_e( 'We offer two program lengths. Contact us for current pricing and availability.', 'bewell' ); ?>
			</p>
		</div>

		<div class="grid md:grid-cols-2 gap-6 max-w-3xl mx-auto">
			<?php foreach ( $bewell_pricing as $bewell_plan ) : ?>
				<div class="bw-card relative border-2 <?php echo $bewell_plan['recommended'] ? 'border-primary' : 'border-border'; ?>">
					<?php if ( $bewell_plan['recommended'] ) : ?>
						<div class="absolute -top-3 left-1/2 -translate-x-1/2">
							<span class="bw-badge bg-primary text-primary-foreground"><?php esc_html_e( 'Recommended', 'bewell' ); ?></span>
						</div>
					<?php endif; ?>

					<div class="p-6 pb-3">
						<h3 class="text-xl font-semibold text-foreground"><?php echo esc_html( $bewell_plan['name'] ); ?></h3>
						<p class="text-muted-foreground text-sm"><?php echo esc_html( $bewell_plan['duration'] ); ?></p>
						<p class="text-2xl font-bold text-primary mt-1"><?php echo esc_html( $bewell_plan['price'] ); ?></p>
					</div>

					<div class="p-6 pt-0">
						<ul class="space-y-2 mb-5">
							<?php foreach ( $bewell_plan['features'] as $bewell_feature ) : ?>
								<li class="flex items-center gap-2 text-sm text-foreground">
									<?php bewell_the_icon( 'check', 'w-4 h-4 text-primary shrink-0' ); ?>
									<?php echo esc_html( $bewell_feature ); ?>
								</li>
							<?php endforeach; ?>
						</ul>

						<a href="#apply-form" class="bw-btn bw-btn-default w-full <?php echo $bewell_plan['recommended'] ? 'bw-btn-primary' : 'bw-btn-outline'; ?>">
							<?php esc_html_e( 'Apply Now', 'bewell' ); ?>
							<?php bewell_the_icon( 'arrow-right', 'ml-2 w-4 h-4' ); ?>
						</a>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- Application form -->
<section id="apply-form" class="py-16 bg-muted">
	<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
		<div class="text-center mb-10">
			<span class="bw-badge mb-4 bg-accent text-accent-foreground border-0"><?php esc_html_e( 'Apply', 'bewell' ); ?></span>
			<h2 class="text-3xl font-bold text-foreground mb-4"><?php esc_html_e( 'Apply for the Program', 'bewell' ); ?></h2>
			<p class="text-muted-foreground">
				<?php esc_html_e( 'Fill out the form below and our team will contact you within 24–48 hours.', 'bewell' ); ?>
			</p>
		</div>

		<div id="bewell-form">
		<?php if ( bewell_form_succeeded( 'program' ) ) : ?>
			<?php
			bewell_success_panel(
				__( 'Application Received!', 'bewell' ),
				__( 'Thank you for applying. Our team will contact you shortly to discuss your needs and answer any questions.', 'bewell' )
			);
			?>
		<?php else : ?>
			<div class="bw-card border-border">
				<div class="p-6 sm:p-8">
					<form method="post" action="<?php echo esc_url( bewell_url( 'lifestyle' ) ); ?>#bewell-form" class="space-y-5" novalidate>
						<?php
						bewell_form_fields( 'program' );
						bewell_form_error_banner( 'program' );
						?>

						<div class="grid sm:grid-cols-2 gap-4">
							<?php
							bewell_text_field(
								array(
									'form'         => 'program',
									'name'         => 'name',
									'label'        => __( 'Full Name', 'bewell' ),
									'required'     => true,
									'placeholder'  => __( 'Your full name', 'bewell' ),
									'autocomplete' => 'name',
								)
							);
							bewell_text_field(
								array(
									'form'         => 'program',
									'name'         => 'phone',
									'label'        => __( 'Phone Number', 'bewell' ),
									'type'         => 'tel',
									'required'     => true,
									'placeholder'  => '+880…',
									'autocomplete' => 'tel',
								)
							);
							?>
						</div>

						<?php
						bewell_text_field(
							array(
								'form'         => 'program',
								'name'         => 'email',
								'label'        => __( 'Email Address', 'bewell' ),
								'type'         => 'email',
								'placeholder'  => 'your@email.com',
								'autocomplete' => 'email',
							)
						);

						bewell_text_field(
							array(
								'form'        => 'program',
								'name'        => 'health_condition',
								'label'       => __( 'Primary Health Condition', 'bewell' ),
								'required'    => true,
								'placeholder' => __( 'e.g. Diabetes, Heart Disease…', 'bewell' ),
							)
						);

						bewell_select_field(
							array(
								'form'        => 'program',
								'name'        => 'program_length',
								'label'       => __( 'Preferred Program Length', 'bewell' ),
								'placeholder' => __( 'Select program length', 'bewell' ),
								'options'     => array(
									'2-week' => __( 'Two-Week Program', 'bewell' ),
									'3-week' => __( 'Three-Week Program', 'bewell' ),
									'unsure' => __( 'Not sure yet', 'bewell' ),
								),
							)
						);

						bewell_textarea_field(
							array(
								'form'        => 'program',
								'name'        => 'message',
								'label'       => __( 'Tell Us About Yourself', 'bewell' ),
								'rows'        => 4,
								'placeholder' => __( 'Please share a brief description of your health situation and any questions you have…', 'bewell' ),
							)
						);
						?>

						<button type="submit" class="bw-btn bw-btn-lg bw-btn-primary w-full">
							<?php esc_html_e( 'Submit Application', 'bewell' ); ?>
							<?php bewell_the_icon( 'arrow-right', 'ml-2 w-4 h-4' ); ?>
						</button>

						<p class="text-xs text-muted-foreground text-center">
							<?php esc_html_e( 'Your health information is stored privately and seen only by our team.', 'bewell' ); ?>
						</p>
					</form>
				</div>
			</div>
		<?php endif; ?>
		</div>
	</div>
</section>

<?php if ( $bewell_testimonials ) : ?>
	<section class="py-16 bg-background">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
			<div class="text-center mb-10">
				<span class="bw-badge mb-4 bg-accent text-accent-foreground border-0"><?php esc_html_e( 'Testimonials', 'bewell' ); ?></span>
				<h2 class="text-3xl font-bold text-foreground mb-4"><?php esc_html_e( 'What Our Guests Say', 'bewell' ); ?></h2>
			</div>
			<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
				<?php
				foreach ( $bewell_testimonials as $bewell_testimonial ) {
					get_template_part( 'template-parts/testimonial', null, array( 'post' => $bewell_testimonial ) );
				}
				?>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php
get_footer();
