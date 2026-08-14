<?php
/**
 * Home page — ported from src/pages/Home.tsx.
 *
 * @package bewell
 */

defined( 'ABSPATH' ) || exit;

get_header();

$bewell_conditions = array(
	__( 'Heart Disease', 'bewell' ),
	__( 'Diabetes', 'bewell' ),
	__( 'Cancer', 'bewell' ),
	__( 'High Blood Pressure', 'bewell' ),
	__( 'Depression', 'bewell' ),
	__( 'Obesity', 'bewell' ),
	__( 'Arthritis', 'bewell' ),
	__( 'Digestive Issues', 'bewell' ),
);

$bewell_principles = array(
	array( 'sun', __( 'Sunlight', 'bewell' ), __( 'Daily exposure to natural sunlight for healing and energy.', 'bewell' ) ),
	array( 'leaf', __( 'Nutrition', 'bewell' ), __( 'Whole plant-based foods from our own farm and gardens.', 'bewell' ) ),
	array( 'heart', __( 'Exercise', 'bewell' ), __( 'Gentle movement through our scenic hillside surroundings.', 'bewell' ) ),
	array( 'shield', __( 'Rest', 'bewell' ), __( 'Deep restorative rest in a quiet, peaceful environment.', 'bewell' ) ),
);

$bewell_services = array(
	array(
		'key'   => 'lifestyle',
		'icon'  => 'heart',
		'title' => __( 'Lifestyle Program', 'bewell' ),
		'desc'  => __( 'Two to three weeks of immersive care for heart disease, diabetes, cancer, high blood pressure, depression, and other lifestyle illnesses.', 'bewell' ),
		'badge' => __( 'Most Popular', 'bewell' ),
	),
	array(
		'key'   => 'training',
		'icon'  => 'users',
		'title' => __( 'Training Program', 'bewell' ),
		'desc'  => __( 'Comprehensive health education program teaching natural lifestyle medicine principles to healthcare workers and health-conscious individuals.', 'bewell' ),
		'badge' => '',
	),
	array(
		'key'   => 'hostel',
		'icon'  => 'sun',
		'title' => __( 'Hostel Services', 'bewell' ),
		'desc'  => __( 'Comfortable accommodation on our peaceful campus surrounded by hills, fruit trees, and flower gardens.', 'bewell' ),
		'badge' => '',
	),
	array(
		'key'   => 'farm',
		'icon'  => 'leaf',
		'title' => __( 'BE WELL Farm', 'bewell' ),
		'desc'  => __( 'Our best-practices farm grows organic fruits, vegetables, and herbs using sustainable methods. Fresh produce available for purchase.', 'bewell' ),
		'badge' => '',
	),
);

$bewell_testimonials = bewell_get_featured_testimonials( 3 );
?>

<!-- Hero -->
<section class="relative min-h-screen flex items-center justify-center overflow-hidden">
	<div class="absolute inset-0 bg-cover bg-center" style="<?php echo esc_attr( bewell_bg( 'buildings/IMG_3865.JPG' ) ); ?>"></div>
	<div class="absolute inset-0 bg-foreground/75"></div>

	<div class="relative z-10 text-center px-4 sm:px-6 max-w-4xl mx-auto">
		<span class="bw-badge mb-6 bg-primary/80 text-primary-foreground border-0 px-4 py-1.5 text-sm">
			<?php esc_html_e( 'Center of Health & Healing', 'bewell' ); ?>
		</span>
		<h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white mb-6 leading-tight">
			<?php esc_html_e( 'Find Healing in', 'bewell' ); ?><br>
			<span><?php esc_html_e( "Nature's Embrace", 'bewell' ); ?></span>
		</h1>
		<p class="text-lg sm:text-xl text-white/85 mb-8 max-w-2xl mx-auto leading-relaxed">
			<?php esc_html_e( 'BE WELL is a center of health and healing. We operate a lifestyle program where persons suffering with heart disease, diabetes, cancer, high blood pressure, depression, and other lifestyle illnesses may come and find healing in two or three weeks of special care.', 'bewell' ); ?>
		</p>
		<div class="flex flex-col sm:flex-row gap-4 justify-center">
			<a href="<?php echo esc_url( bewell_url( 'lifestyle' ) ); ?>" class="bw-btn bw-btn-lg bw-btn-primary text-base px-8">
				<?php esc_html_e( 'Start Your Healing Journey', 'bewell' ); ?>
				<?php bewell_the_icon( 'arrow-right', 'ml-2 w-5 h-5' ); ?>
			</a>
			<a href="<?php echo esc_url( bewell_url( 'contact' ) ); ?>" class="bw-btn bw-btn-lg text-base px-8 bg-white/10 text-white border border-white/40 hover:bg-white/20 hover:text-white">
				<?php esc_html_e( 'Contact Us', 'bewell' ); ?>
			</a>
		</div>
	</div>

	<div class="absolute bottom-8 left-1/2 -translate-x-1/2 bw-animate-bounce" aria-hidden="true">
		<div class="w-6 h-10 rounded-full border-2 border-white/50 flex items-start justify-center pt-2">
			<div class="w-1 h-3 bg-white/70 rounded-full"></div>
		</div>
	</div>
</section>

<!-- Conditions we treat -->
<section class="py-10 bg-primary text-primary-foreground">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
		<div class="flex flex-wrap items-center justify-center gap-3">
			<span class="text-sm font-medium opacity-80 mr-2"><?php esc_html_e( 'We help with:', 'bewell' ); ?></span>
			<?php foreach ( $bewell_conditions as $bewell_condition ) : ?>
				<span class="bw-badge bg-primary-foreground/15 text-primary-foreground border-primary-foreground/20 text-sm px-3 py-1">
					<?php echo esc_html( $bewell_condition ); ?>
				</span>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- About -->
<section class="py-20 bg-background">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
		<div class="grid lg:grid-cols-2 gap-12 items-center">
			<div>
				<span class="bw-badge mb-4 bg-accent text-accent-foreground border-0"><?php esc_html_e( 'About Us', 'bewell' ); ?></span>
				<h2 class="text-3xl sm:text-4xl font-bold text-foreground mb-6 leading-tight">
					<?php esc_html_e( 'Where Nature Meets', 'bewell' ); ?><br><?php esc_html_e( 'Modern Wellness', 'bewell' ); ?>
				</h2>
				<p class="text-muted-foreground leading-relaxed mb-4 font-semibold">
					<?php esc_html_e( 'Our campus is located in beautiful hills near Choto Daragar Hat. We have flowers and fruit trees growing in abundance, and are surrounded with nature on every side.', 'bewell' ); ?>
				</p>
				<p class="text-muted-foreground leading-relaxed mb-4 text-sm">
					<?php esc_html_e( "BE WELL ALWAYS LTD was founded and developed by Eugene and Heidi Prewitt. They worked previously at two of the world's premier wellness centers (Weimar Institute in Weimar California; Aenon Farm, in Malaysia.) And they have given lectures and spent time at several others including Our Home in Ukraine, TGM in Austria, Hergalia in Romania.", 'bewell' ); ?>
				</p>
				<p class="text-muted-foreground leading-relaxed mb-6 text-sm">
					<?php esc_html_e( 'After watching hundreds of critical patients healed from heart disease and diabetes and cancer, they decided to bring a service like this to Bangladesh.', 'bewell' ); ?>
				</p>
				<a href="<?php echo esc_url( bewell_url( 'lifestyle' ) ); ?>" class="bw-btn bw-btn-default bw-btn-outline">
					<?php esc_html_e( 'Learn About Our Program', 'bewell' ); ?>
					<?php bewell_the_icon( 'chevron-right', 'ml-2 w-4 h-4' ); ?>
				</a>
			</div>

			<div class="relative">
				<img src="<?php echo esc_url( bewell_img( 'buildings/IMG_3877.JPG' ) ); ?>"
					alt="<?php esc_attr_e( 'Beautiful green hillside at the BE WELL campus', 'bewell' ); ?>"
					class="rounded-xl shadow-lg w-full object-cover aspect-[4/3]" loading="lazy" decoding="async">
				<div class="absolute -bottom-5 -left-5 bg-card border border-border rounded-xl p-4 shadow-lg">
					<div class="text-3xl font-bold text-primary"><?php esc_html_e( '2–3', 'bewell' ); ?></div>
					<div class="text-sm text-muted-foreground"><?php esc_html_e( 'Weeks to transformation', 'bewell' ); ?></div>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- Health principles -->
<section class="py-20 bg-muted">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
		<div class="text-center mb-12">
			<span class="bw-badge mb-4 bg-accent text-accent-foreground border-0"><?php esc_html_e( 'Our Approach', 'bewell' ); ?></span>
			<h2 class="text-3xl sm:text-4xl font-bold text-foreground mb-4"><?php esc_html_e( 'Natural Healing Principles', 'bewell' ); ?></h2>
			<p class="text-muted-foreground max-w-2xl mx-auto">
				<?php esc_html_e( 'We use the eight laws of health — time-tested principles that the human body responds to powerfully.', 'bewell' ); ?>
			</p>
		</div>

		<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
			<?php foreach ( $bewell_principles as $bewell_principle ) : ?>
				<div class="bw-card text-center hover:shadow-md transition-shadow border-border">
					<div class="p-6">
						<div class="flex justify-center mb-4">
							<div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center">
								<?php bewell_the_icon( $bewell_principle[0], 'w-6 h-6 text-primary' ); ?>
							</div>
						</div>
						<h3 class="font-semibold text-foreground mb-2"><?php echo esc_html( $bewell_principle[1] ); ?></h3>
						<p class="text-sm text-muted-foreground"><?php echo esc_html( $bewell_principle[2] ); ?></p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- Services -->
<section class="py-20 bg-background">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
		<div class="text-center mb-12">
			<span class="bw-badge mb-4 bg-accent text-accent-foreground border-0"><?php esc_html_e( 'What We Offer', 'bewell' ); ?></span>
			<h2 class="text-3xl sm:text-4xl font-bold text-foreground mb-4"><?php esc_html_e( 'Our Programs & Services', 'bewell' ); ?></h2>
			<p class="text-muted-foreground max-w-2xl mx-auto">
				<?php esc_html_e( 'From intensive healing programs to training, lodging, and fresh farm produce — BE WELL is a complete wellness destination.', 'bewell' ); ?>
			</p>
		</div>

		<div class="grid sm:grid-cols-2 gap-6">
			<?php foreach ( $bewell_services as $bewell_service ) : ?>
				<a href="<?php echo esc_url( bewell_url( $bewell_service['key'] ) ); ?>" class="bw-card block hover:shadow-lg transition-all border-border group">
					<div class="p-6">
						<div class="flex items-start gap-4">
							<div class="w-11 h-11 rounded-lg bg-primary/10 flex items-center justify-center shrink-0 group-hover:bg-primary transition-colors">
								<?php bewell_the_icon( $bewell_service['icon'], 'w-5 h-5 text-primary group-hover:text-primary-foreground' ); ?>
							</div>
							<div class="flex-1">
								<div class="flex items-center gap-2 mb-1">
									<h3 class="font-semibold text-foreground"><?php echo esc_html( $bewell_service['title'] ); ?></h3>
									<?php if ( $bewell_service['badge'] ) : ?>
										<span class="bw-badge text-xs bg-primary/10 text-primary border-0"><?php echo esc_html( $bewell_service['badge'] ); ?></span>
									<?php endif; ?>
								</div>
								<p class="text-sm text-muted-foreground leading-relaxed"><?php echo esc_html( $bewell_service['desc'] ); ?></p>
								<div class="flex items-center gap-1 mt-3 text-primary text-sm font-medium group-hover:gap-2 transition-all">
									<?php esc_html_e( 'Learn more', 'bewell' ); ?>
									<?php bewell_the_icon( 'chevron-right', 'w-4 h-4' ); ?>
								</div>
							</div>
						</div>
					</div>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- Campus image -->
<section class="py-0">
	<div class="relative h-72 sm:h-96 overflow-hidden">
		<img src="<?php echo esc_url( bewell_img( 'scenery/IMG_3983.JPG' ) ); ?>"
			alt="<?php esc_attr_e( 'Lush green nature surrounding the BE WELL campus', 'bewell' ); ?>"
			class="w-full h-full object-cover" loading="lazy" decoding="async">
		<div class="absolute inset-0 bg-foreground/65 flex items-center justify-center">
			<div class="text-center text-white px-4">
				<h2 class="text-2xl sm:text-3xl font-bold mb-2"><?php esc_html_e( "Surrounded by Nature's Beauty", 'bewell' ); ?></h2>
				<p class="text-white/80 max-w-xl">
					<?php esc_html_e( "Our hillside campus features flowers, fruit trees, and fresh mountain air — nature's own medicine.", 'bewell' ); ?>
				</p>
			</div>
		</div>
	</div>
</section>

<?php if ( $bewell_testimonials ) : ?>
	<!-- Testimonials -->
	<section class="py-20 bg-muted">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
			<div class="text-center mb-12">
				<span class="bw-badge mb-4 bg-accent text-accent-foreground border-0"><?php esc_html_e( 'Success Stories', 'bewell' ); ?></span>
				<h2 class="text-3xl sm:text-4xl font-bold text-foreground mb-4"><?php esc_html_e( 'Lives Transformed', 'bewell' ); ?></h2>
				<p class="text-muted-foreground max-w-2xl mx-auto">
					<?php esc_html_e( 'Hear from guests who found healing at BE WELL.', 'bewell' ); ?>
				</p>
			</div>

			<div class="grid md:grid-cols-3 gap-6">
				<?php
				foreach ( $bewell_testimonials as $bewell_testimonial ) {
					get_template_part( 'template-parts/testimonial', null, array( 'post' => $bewell_testimonial ) );
				}
				?>
			</div>

			<div class="text-center mt-8">
				<a href="<?php echo esc_url( bewell_url( 'lifestyle' ) ); ?>" class="bw-btn bw-btn-default bw-btn-outline">
					<?php esc_html_e( 'Read More Stories', 'bewell' ); ?>
					<?php bewell_the_icon( 'chevron-right', 'ml-2 w-4 h-4' ); ?>
				</a>
			</div>
		</div>
	</section>
<?php endif; ?>

<!-- CTA -->
<section class="py-20 bg-primary text-primary-foreground">
	<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
		<h2 class="text-3xl sm:text-4xl font-bold mb-4"><?php esc_html_e( 'Ready to Start Your Healing Journey?', 'bewell' ); ?></h2>
		<p class="text-primary-foreground/80 text-lg mb-8 max-w-2xl mx-auto">
			<?php esc_html_e( 'Two to three weeks can change your life. Our team is ready to welcome you to our campus in the hills.', 'bewell' ); ?>
		</p>
		<div class="flex flex-col sm:flex-row gap-4 justify-center">
			<a href="<?php echo esc_url( bewell_url( 'lifestyle' ) ); ?>" class="bw-btn bw-btn-lg bg-primary-foreground text-primary hover:bg-primary-foreground/90 text-base px-8">
				<?php esc_html_e( 'Apply for the Lifestyle Program', 'bewell' ); ?>
			</a>
		</div>
	</div>
</section>

<?php
get_footer();
