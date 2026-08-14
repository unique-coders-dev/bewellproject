<?php
/**
 * Template Name: Training Program
 *
 * Ported from src/pages/TrainingProgram.tsx.
 *
 * Behaviour change, deliberate: the React form here never saved anything. Its
 * handleSubmit was `e.preventDefault(); setSubmitted(true)` — no request, no
 * table, no record. Every trainee who applied since launch was shown
 * "Application Received!" and their details were dropped. This version posts to
 * the training applications table like every other form on the site.
 *
 * @package bewell
 */

defined( 'ABSPATH' ) || exit;

get_header();

$bewell_modules = array(
	array( __( 'Foundations of Natural Health', 'bewell' ), __( 'The eight laws of health and their scientific basis. Understanding lifestyle as medicine.', 'bewell' ) ),
	array( __( 'Plant-Based Nutrition', 'bewell' ), __( 'Evidence-based nutrition science. Meal planning, cooking demonstrations, and dietary counseling.', 'bewell' ) ),
	array( __( 'Exercise Therapy', 'bewell' ), __( 'Therapeutic movement programs tailored to various conditions. Nature-based exercise protocols.', 'bewell' ) ),
	array( __( 'Stress & Mental Health', 'bewell' ), __( 'Mind-body connection, stress management techniques, sleep improvement strategies.', 'bewell' ) ),
	array( __( 'Water Therapy (Hydrotherapy)', 'bewell' ), __( 'Practical training in hydrotherapy applications for common conditions.', 'bewell' ) ),
	array( __( 'Community Health Outreach', 'bewell' ), __( 'How to apply lifestyle medicine principles in community and clinic settings.', 'bewell' ) ),
);

$bewell_audience = array(
	__( 'Nurses and community health workers', 'bewell' ),
	__( 'Doctors and medical professionals', 'bewell' ),
	__( 'Pastors and community leaders', 'bewell' ),
	__( 'Teachers and educators', 'bewell' ),
	__( 'Health-conscious individuals', 'bewell' ),
	__( 'Anyone passionate about natural health', 'bewell' ),
);

$bewell_outcomes = array(
	__( 'Certificate in Lifestyle Health Education', 'bewell' ),
	__( 'Practical skills in natural health counseling', 'bewell' ),
	__( 'Evidence-based nutritional guidance capability', 'bewell' ),
	__( 'Ability to lead health education sessions', 'bewell' ),
	__( 'Network of health professionals', 'bewell' ),
	__( 'Access to BE WELL curriculum resources', 'bewell' ),
);

$bewell_photos = array(
	'exercise/IMG_4011.JPG',
	'exercise/IMG_4013.JPG',
	'exercise/IMG_4018.JPG',
	'classes/IMG_4031.JPG',
);

$bewell_testimonials = bewell_get_testimonials( 'training', 6 );
?>

<!-- Hero -->
<section class="relative pt-16 min-h-[55vh] flex items-center">
	<div class="absolute inset-0 bg-cover bg-center" style="<?php echo esc_attr( bewell_bg( 'buildings/IMG_3865.JPG' ) ); ?>"></div>
	<div class="absolute inset-0 bg-foreground/75"></div>

	<div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
		<span class="bw-badge mb-4 bg-primary/80 text-primary-foreground border-0"><?php esc_html_e( 'Training Program', 'bewell' ); ?></span>
		<h1 class="text-4xl sm:text-5xl font-bold text-white mb-4 max-w-2xl leading-tight">
			<?php esc_html_e( 'Equip Yourself to', 'bewell' ); ?><br>
			<span class="text-primary"><?php esc_html_e( 'Help Others Heal', 'bewell' ); ?></span>
		</h1>
		<p class="text-white/85 text-lg max-w-xl leading-relaxed mb-6">
			<?php esc_html_e( 'Our comprehensive training program equips health professionals, educators, and community workers with practical natural health and lifestyle medicine skills.', 'bewell' ); ?>
		</p>
		<div class="flex gap-6 flex-wrap">
			<div class="flex items-center gap-2 text-white/80 text-sm">
				<?php bewell_the_icon( 'calendar', 'w-4 h-4 text-primary' ); ?>
				<?php esc_html_e( 'Sessions held throughout the year', 'bewell' ); ?>
			</div>
			<div class="flex items-center gap-2 text-white/80 text-sm">
				<?php bewell_the_icon( 'book-open', 'w-4 h-4 text-primary' ); ?>
				<?php esc_html_e( 'Practical & theoretical training', 'bewell' ); ?>
			</div>
			<div class="flex items-center gap-2 text-white/80 text-sm">
				<?php bewell_the_icon( 'graduation', 'w-4 h-4 text-primary' ); ?>
				<?php esc_html_e( 'Certificate awarded', 'bewell' ); ?>
			</div>
		</div>
	</div>
</section>

<!-- Who should attend -->
<section class="py-16 bg-background">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
		<div class="grid lg:grid-cols-2 gap-12 items-center">
			<div>
				<span class="bw-badge mb-4 bg-accent text-accent-foreground border-0"><?php esc_html_e( 'Who Should Attend', 'bewell' ); ?></span>
				<h2 class="text-3xl font-bold text-foreground mb-4"><?php esc_html_e( 'Built for Health Champions', 'bewell' ); ?></h2>
				<p class="text-muted-foreground mb-5 leading-relaxed">
					<?php esc_html_e( 'Whether you are a nurse, doctor, pastor, teacher, or community health worker — this program will give you practical tools to guide others toward better health.', 'bewell' ); ?>
				</p>
				<ul class="space-y-2.5">
					<?php foreach ( $bewell_audience as $bewell_item ) : ?>
						<li class="flex items-center gap-2.5 text-sm text-foreground">
							<?php bewell_the_icon( 'check', 'w-4 h-4 text-primary shrink-0' ); ?>
							<?php echo esc_html( $bewell_item ); ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>

			<div class="grid grid-cols-2 gap-3">
				<?php foreach ( $bewell_photos as $bewell_index => $bewell_photo ) : ?>
					<img src="<?php echo esc_url( bewell_img( $bewell_photo ) ); ?>"
						alt="<?php
						/* translators: %d: photo number. */
						echo esc_attr( sprintf( __( 'Training session %d', 'bewell' ), $bewell_index + 1 ) );
						?>"
						class="rounded-lg w-full object-cover aspect-square" loading="lazy" decoding="async">
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>

<!-- Curriculum -->
<section class="py-16 bg-muted">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
		<div class="text-center mb-10">
			<span class="bw-badge mb-4 bg-accent text-accent-foreground border-0"><?php esc_html_e( 'Curriculum', 'bewell' ); ?></span>
			<h2 class="text-3xl font-bold text-foreground mb-4"><?php esc_html_e( 'What You Will Learn', 'bewell' ); ?></h2>
			<p class="text-muted-foreground max-w-2xl mx-auto">
				<?php esc_html_e( 'Six comprehensive modules covering every aspect of natural lifestyle medicine.', 'bewell' ); ?>
			</p>
		</div>

		<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
			<?php foreach ( $bewell_modules as $bewell_index => $bewell_module ) : ?>
				<div class="bw-card border-border">
					<div class="p-5">
						<div class="w-8 h-8 rounded-md bg-primary/15 flex items-center justify-center mb-3">
							<span class="text-sm font-bold text-primary"><?php echo esc_html( sprintf( '%02d', $bewell_index + 1 ) ); ?></span>
						</div>
						<h3 class="font-semibold text-foreground mb-2"><?php echo esc_html( $bewell_module[0] ); ?></h3>
						<p class="text-sm text-muted-foreground leading-relaxed"><?php echo esc_html( $bewell_module[1] ); ?></p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- Outcomes -->
<section class="py-16 bg-background">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
		<div class="grid lg:grid-cols-2 gap-12 items-center">
			<div>
				<img src="<?php echo esc_url( bewell_img( 'classes/IMG_4034.JPG' ) ); ?>"
					alt="<?php esc_attr_e( 'Participants learning at BE WELL training', 'bewell' ); ?>"
					class="rounded-xl shadow-lg w-full object-cover aspect-[4/3]" loading="lazy" decoding="async">
			</div>
			<div>
				<span class="bw-badge mb-4 bg-accent text-accent-foreground border-0"><?php esc_html_e( 'Outcomes', 'bewell' ); ?></span>
				<h2 class="text-3xl font-bold text-foreground mb-4"><?php esc_html_e( 'What You Will Leave With', 'bewell' ); ?></h2>
				<p class="text-muted-foreground mb-6 leading-relaxed">
					<?php esc_html_e( 'Graduates of our training program are equipped to positively impact their communities through evidence-based natural health education.', 'bewell' ); ?>
				</p>
				<ul class="space-y-3">
					<?php foreach ( $bewell_outcomes as $bewell_outcome ) : ?>
						<li class="flex items-center gap-3 text-sm text-foreground">
							<div class="w-5 h-5 rounded-full bg-primary/15 flex items-center justify-center shrink-0">
								<?php bewell_the_icon( 'check', 'w-3 h-3 text-primary' ); ?>
							</div>
							<?php echo esc_html( $bewell_outcome ); ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</div>
</section>

<!-- Application form -->
<section class="py-16 bg-muted">
	<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
		<div class="text-center mb-10">
			<span class="bw-badge mb-4 bg-accent text-accent-foreground border-0"><?php esc_html_e( 'Apply', 'bewell' ); ?></span>
			<h2 class="text-3xl font-bold text-foreground mb-4"><?php esc_html_e( 'Apply for Training', 'bewell' ); ?></h2>
			<p class="text-muted-foreground"><?php esc_html_e( 'We will contact you about upcoming training dates.', 'bewell' ); ?></p>
		</div>

		<div id="bewell-form">
		<?php if ( bewell_form_succeeded( 'training' ) ) : ?>
			<?php
			bewell_success_panel(
				__( 'Application Received!', 'bewell' ),
				__( 'We will be in touch with upcoming training schedules and details.', 'bewell' )
			);
			?>
		<?php else : ?>
			<div class="bw-card border-border">
				<div class="p-6 sm:p-8">
					<form method="post" action="<?php echo esc_url( bewell_url( 'training' ) ); ?>#bewell-form" class="space-y-5" novalidate>
						<?php
						bewell_form_fields( 'training' );
						bewell_form_error_banner( 'training' );
						?>

						<div class="grid sm:grid-cols-2 gap-4">
							<?php
							bewell_text_field(
								array(
									'form'         => 'training',
									'name'         => 'name',
									'label'        => __( 'Full Name', 'bewell' ),
									'required'     => true,
									'placeholder'  => __( 'Your full name', 'bewell' ),
									'autocomplete' => 'name',
								)
							);
							bewell_text_field(
								array(
									'form'         => 'training',
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
								'form'         => 'training',
								'name'         => 'email',
								'label'        => __( 'Email Address', 'bewell' ),
								'type'         => 'email',
								'required'     => true,
								'placeholder'  => 'your@email.com',
								'autocomplete' => 'email',
							)
						);

						bewell_text_field(
							array(
								'form'        => 'training',
								'name'        => 'background',
								'label'       => __( 'Professional Background', 'bewell' ),
								'placeholder' => __( 'e.g. Nurse, Teacher, Community Worker…', 'bewell' ),
							)
						);

						bewell_textarea_field(
							array(
								'form'        => 'training',
								'name'        => 'message',
								'label'       => __( 'Why do you want to attend this training?', 'bewell' ),
								'rows'        => 4,
								'placeholder' => __( 'Tell us about your goals…', 'bewell' ),
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

<?php if ( $bewell_testimonials ) : ?>
	<section class="py-16 bg-background">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
			<div class="text-center mb-10">
				<h2 class="text-3xl font-bold text-foreground mb-4"><?php esc_html_e( 'What Trainees Say', 'bewell' ); ?></h2>
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
