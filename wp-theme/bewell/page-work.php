<?php
/**
 * Template Name: Work With Us
 *
 * Ported from src/pages/WorkAtBeWell.tsx.
 *
 * @package bewell
 */

defined( 'ABSPATH' ) || exit;

get_header();

$bewell_openings = array(
	array(
		'title' => __( 'Lifestyle Health Counselor', 'bewell' ),
		'type'  => __( 'Full-time', 'bewell' ),
		'desc'  => __( 'Guide program guests through their healing journey. Requires health background and passion for natural medicine.', 'bewell' ),
	),
	array(
		'title' => __( 'Farm Worker / Agricultural Assistant', 'bewell' ),
		'type'  => __( 'Full-time', 'bewell' ),
		'desc'  => __( 'Care for our organic farm, manage daily harvests, and maintain best-practice farming standards.', 'bewell' ),
	),
	array(
		'title' => __( 'Kitchen / Nutrition Staff', 'bewell' ),
		'type'  => __( 'Full-time', 'bewell' ),
		'desc'  => __( 'Prepare nutritious plant-based meals for our guests. Interest in therapeutic nutrition highly valued.', 'bewell' ),
	),
	array(
		'title' => __( 'Hostel / Guest Services', 'bewell' ),
		'type'  => __( 'Full-time', 'bewell' ),
		'desc'  => __( 'Ensure our guests feel welcomed, comfortable, and cared for throughout their stay.', 'bewell' ),
	),
	array(
		'title' => __( 'Health Educator / Lecturer', 'bewell' ),
		'type'  => __( 'Part-time', 'bewell' ),
		'desc'  => __( 'Teach our daily health lectures to program participants. Strong knowledge of natural health principles required.', 'bewell' ),
	),
	array(
		'title' => __( 'Administrative Staff', 'bewell' ),
		'type'  => __( 'Full-time', 'bewell' ),
		'desc'  => __( 'Support the administrative operations of BE WELL ALWAYS LTD. Organizational skills and hospitality mindset essential.', 'bewell' ),
	),
);

$bewell_values = array(
	array( 'heart', __( 'Compassionate Service', 'bewell' ), __( 'We serve our guests as we would serve our own family — with deep care and attention to each person.', 'bewell' ) ),
	array( 'leaf', __( 'Natural Principles', 'bewell' ), __( 'We believe in the healing power of nature and the importance of living in harmony with natural law.', 'bewell' ) ),
	array( 'book-open', __( 'Continual Learning', 'bewell' ), __( 'Our team is always growing. We encourage ongoing education and personal development for all staff.', 'bewell' ) ),
	array( 'star', __( 'Excellence in All Things', 'bewell' ), __( 'Whether it is the cleanliness of a room or the accuracy of health information, we aim for the highest standard.', 'bewell' ) ),
);

// The select must offer exactly the roles advertised above, so it is built from
// the same array rather than a second hand-maintained list.
$bewell_position_options = array();
foreach ( $bewell_openings as $bewell_opening ) {
	$bewell_position_options[ $bewell_opening['title'] ] = $bewell_opening['title'];
}
$bewell_position_options['other'] = __( 'Other / General Interest', 'bewell' );
?>

<!-- Hero -->
<section class="relative pt-16 min-h-[55vh] flex items-center">
	<div class="absolute inset-0 bg-cover bg-center" style="<?php echo esc_attr( bewell_bg( 'buildings/IMG_3865.JPG' ) ); ?>"></div>
	<div class="absolute inset-0 bg-foreground/75"></div>

	<div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
		<span class="bw-badge mb-4 bg-primary/80 text-primary-foreground border-0"><?php esc_html_e( 'Join Our Team', 'bewell' ); ?></span>
		<h1 class="text-4xl sm:text-5xl font-bold text-white mb-4 max-w-2xl leading-tight">
			<?php esc_html_e( 'Work With', 'bewell' ); ?><br>
			<span class="text-primary"><?php esc_html_e( 'BE WELL ALWAYS', 'bewell' ); ?></span>
		</h1>
		<p class="text-white/85 text-lg max-w-xl leading-relaxed">
			<?php esc_html_e( 'Join a mission-driven team dedicated to transforming lives through natural healing. We are more than an organization — we are a community of healers.', 'bewell' ); ?>
		</p>
	</div>
</section>

<!-- Philosophy -->
<section class="py-16 bg-background">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
		<div class="grid lg:grid-cols-2 gap-12 items-center">
			<div>
				<span class="bw-badge mb-4 bg-accent text-accent-foreground border-0"><?php esc_html_e( 'Our Philosophy', 'bewell' ); ?></span>
				<h2 class="text-3xl font-bold text-foreground mb-4"><?php esc_html_e( 'A Ministry of Healing', 'bewell' ); ?></h2>
				<p class="text-muted-foreground mb-4 leading-relaxed">
					<?php esc_html_e( "BE WELL ALWAYS LTD is rooted in the belief that true health is a gift meant for every person, and that we can cooperate with God's design for the human body to see remarkable healing.", 'bewell' ); ?>
				</p>
				<p class="text-muted-foreground mb-4 leading-relaxed">
					<?php esc_html_e( 'We operate from a faith-based foundation that values the sanctity of human life and the responsibility we have to care for our bodies as temples. Our program draws on the principles found in Scripture and confirmed by modern science.', 'bewell' ); ?>
				</p>
				<p class="text-muted-foreground leading-relaxed">
					<?php esc_html_e( 'Every member of our team is an integral part of this healing ministry. We believe that a warm, prayerful, compassionate environment is itself therapeutic — and our staff creates that environment every day.', 'bewell' ); ?>
				</p>
			</div>
			<div>
				<img src="<?php echo esc_url( bewell_img( 'buildings/IMG_3865.JPG' ) ); ?>"
					alt="<?php esc_attr_e( 'The team working together at BE WELL', 'bewell' ); ?>"
					class="rounded-xl shadow-lg w-full object-cover aspect-[4/3]" loading="lazy" decoding="async">
			</div>
		</div>
	</div>
</section>

<!-- Values -->
<section class="py-16 bg-muted">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
		<div class="text-center mb-10">
			<span class="bw-badge mb-4 bg-accent text-accent-foreground border-0"><?php esc_html_e( 'Our Values', 'bewell' ); ?></span>
			<h2 class="text-3xl font-bold text-foreground mb-4"><?php esc_html_e( 'What We Stand For', 'bewell' ); ?></h2>
		</div>
		<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
			<?php foreach ( $bewell_values as $bewell_value ) : ?>
				<div class="bw-card border-border text-center">
					<div class="p-5">
						<div class="w-11 h-11 rounded-full bg-primary/15 flex items-center justify-center mx-auto mb-3">
							<?php bewell_the_icon( $bewell_value[0], 'w-5 h-5 text-primary' ); ?>
						</div>
						<h3 class="font-semibold text-foreground mb-2"><?php echo esc_html( $bewell_value[1] ); ?></h3>
						<p class="text-sm text-muted-foreground leading-relaxed"><?php echo esc_html( $bewell_value[2] ); ?></p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- Faith foundation -->
<section class="py-16 bg-background">
	<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
		<div class="bw-card border-border">
			<div class="p-8">
				<span class="bw-badge mb-4 bg-accent text-accent-foreground border-0"><?php esc_html_e( 'Faith Foundation', 'bewell' ); ?></span>
				<h2 class="text-2xl font-bold text-foreground mb-4"><?php esc_html_e( 'Our Religious Foundation', 'bewell' ); ?></h2>
				<p class="text-muted-foreground mb-4 leading-relaxed">
					<?php esc_html_e( 'BE WELL is operated by a team of Seventh-day Adventist health professionals who believe that health is not merely the absence of disease, but a state of complete physical, mental, and spiritual wellbeing.', 'bewell' ); ?>
				</p>
				<p class="text-muted-foreground mb-4 leading-relaxed">
					<?php esc_html_e( 'We believe the Sabbath — the seventh day of the week — is a sacred time of rest and restoration. Our program incorporates spiritual dimensions of healing, including prayer, meditation on Scripture, and time in nature.', 'bewell' ); ?>
				</p>
				<p class="text-muted-foreground leading-relaxed">
					<?php esc_html_e( 'Staff members are expected to be in sympathy with our health philosophy and faith foundation. We welcome people of all backgrounds as guests, and we offer our ministry to all who seek healing.', 'bewell' ); ?>
				</p>
			</div>
		</div>
	</div>
</section>

<!-- Openings -->
<section class="py-16 bg-muted">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
		<div class="text-center mb-10">
			<span class="bw-badge mb-4 bg-accent text-accent-foreground border-0"><?php esc_html_e( 'Opportunities', 'bewell' ); ?></span>
			<h2 class="text-3xl font-bold text-foreground mb-4"><?php esc_html_e( 'Current Openings', 'bewell' ); ?></h2>
			<p class="text-muted-foreground max-w-2xl mx-auto">
				<?php esc_html_e( 'We are building our team. If you are passionate about natural health and serving others, we would love to hear from you.', 'bewell' ); ?>
			</p>
		</div>

		<div class="grid md:grid-cols-2 gap-4 max-w-5xl mx-auto">
			<?php foreach ( $bewell_openings as $bewell_job ) : ?>
				<div class="bw-card border-border hover:shadow-md transition-shadow">
					<div class="p-5">
						<div class="flex items-start justify-between mb-2">
							<h3 class="font-semibold text-foreground"><?php echo esc_html( $bewell_job['title'] ); ?></h3>
							<span class="bw-badge text-xs ml-2 shrink-0 bg-secondary text-secondary-foreground"><?php echo esc_html( $bewell_job['type'] ); ?></span>
						</div>
						<p class="text-sm text-muted-foreground leading-relaxed"><?php echo esc_html( $bewell_job['desc'] ); ?></p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- Application form -->
<section class="py-16 bg-background">
	<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
		<div class="text-center mb-10">
			<span class="bw-badge mb-4 bg-accent text-accent-foreground border-0"><?php esc_html_e( 'Apply', 'bewell' ); ?></span>
			<h2 class="text-3xl font-bold text-foreground mb-4"><?php esc_html_e( 'Apply to Join the Team', 'bewell' ); ?></h2>
			<p class="text-muted-foreground"><?php esc_html_e( 'Tell us about yourself and why you want to be part of BE WELL.', 'bewell' ); ?></p>
		</div>

		<div id="bewell-form">
		<?php if ( bewell_form_succeeded( 'job' ) ) : ?>
			<?php
			bewell_success_panel(
				__( 'Application Received!', 'bewell' ),
				__( 'Thank you for your interest. We will review your application and be in touch soon.', 'bewell' )
			);
			?>
		<?php else : ?>
			<div class="bw-card border-border">
				<div class="p-6 sm:p-8">
					<form method="post" action="<?php echo esc_url( bewell_url( 'work' ) ); ?>#bewell-form" class="space-y-5" novalidate>
						<?php
						bewell_form_fields( 'job' );
						bewell_form_error_banner( 'job' );
						?>

						<div class="grid sm:grid-cols-2 gap-4">
							<?php
							bewell_text_field(
								array(
									'form'         => 'job',
									'name'         => 'name',
									'label'        => __( 'Full Name', 'bewell' ),
									'required'     => true,
									'placeholder'  => __( 'Your full name', 'bewell' ),
									'autocomplete' => 'name',
								)
							);
							bewell_text_field(
								array(
									'form'         => 'job',
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
								'form'         => 'job',
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
								'form'        => 'job',
								'name'        => 'position',
								'label'       => __( 'Position of Interest', 'bewell' ),
								'placeholder' => __( 'Select a position', 'bewell' ),
								'options'     => $bewell_position_options,
							)
						);

						bewell_textarea_field(
							array(
								'form'        => 'job',
								'name'        => 'experience',
								'label'       => __( 'Relevant Experience', 'bewell' ),
								'rows'        => 3,
								'placeholder' => __( 'Describe your relevant background and skills…', 'bewell' ),
							)
						);

						bewell_textarea_field(
							array(
								'form'        => 'job',
								'name'        => 'motivation',
								'label'       => __( 'Why do you want to work at BE WELL?', 'bewell' ),
								'rows'        => 3,
								'placeholder' => __( 'Share your motivation and how you align with our mission…', 'bewell' ),
							)
						);
						?>

						<button type="submit" class="bw-btn bw-btn-lg bw-btn-primary w-full">
							<?php esc_html_e( 'Submit Application', 'bewell' ); ?>
							<?php bewell_the_icon( 'arrow-right', 'ml-2 w-4 h-4' ); ?>
						</button>
					</form>
				</div>
			</div>
		<?php endif; ?>
		</div>
	</div>
</section>

<?php
get_footer();
