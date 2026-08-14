<?php
/**
 * Template Name: Hostel Services
 *
 * Ported from src/pages/HostelServices.tsx.
 *
 * @package bewell
 */

defined( 'ABSPATH' ) || exit;

get_header();

$bewell_amenities = array(
	__( 'Clean, comfortable private rooms', 'bewell' ),
	__( 'Fresh plant-based meals included', 'bewell' ),
	__( 'Daily room cleaning', 'bewell' ),
	__( 'Nature walking trails nearby', 'bewell' ),
	__( 'Quiet prayer and meditation areas', 'bewell' ),
	__( 'Fresh filtered water', 'bewell' ),
	__( 'Beautiful garden views', 'bewell' ),
	__( 'Proximity to the BE WELL campus', 'bewell' ),
);

$bewell_rooms = array(
	array(
		'title'    => __( 'Single Room', 'bewell' ),
		'desc'     => __( 'A peaceful private room with garden view, ideal for individuals attending our programs.', 'bewell' ),
		'image'    => 'buildings/IMG_3865.JPG',
		'features' => array(
			__( 'Private room', 'bewell' ),
			__( 'Single bed', 'bewell' ),
			__( 'Natural light', 'bewell' ),
			__( 'Clean bathroom', 'bewell' ),
		),
	),
	array(
		'title'    => __( 'Double Room', 'bewell' ),
		'desc'     => __( 'Perfect for couples or two guests attending together. Shared spaces for relaxation.', 'bewell' ),
		'image'    => 'buildings/IMG_3877.JPG',
		'features' => array(
			__( 'Private room', 'bewell' ),
			__( 'Two beds', 'bewell' ),
			__( 'Natural light', 'bewell' ),
			__( 'Clean bathroom', 'bewell' ),
		),
	),
	array(
		'title'    => __( 'Family Room', 'bewell' ),
		'desc'     => __( 'Spacious accommodation for families with children attending the program together.', 'bewell' ),
		'image'    => 'buildings/IMG_3865.JPG',
		'features' => array(
			__( 'Larger room', 'bewell' ),
			__( 'Multiple beds', 'bewell' ),
			__( 'Family friendly', 'bewell' ),
			__( 'Extra space', 'bewell' ),
		),
	),
);

$bewell_gallery = array(
	array( 'scenery/IMG_3880.JPG', __( 'Hostel garden view', 'bewell' ) ),
	array( 'scenery/IMG_3882.JPG', __( 'Peaceful surroundings at BE WELL', 'bewell' ) ),
	array( 'scenery/IMG_3949.JPG', __( 'Nature trails on campus', 'bewell' ) ),
	array( 'scenery/IMG_3984.JPG', __( 'Beautiful surroundings', 'bewell' ) ),
	array( 'garden/IMG_3895.JPG', __( 'Fruit trees on campus', 'bewell' ) ),
	array( 'scenery/IMG_4022.JPG', __( 'Scenic view from campus', 'bewell' ) ),
);

$bewell_testimonials = bewell_get_testimonials( 'hostel', 6 );
?>

<!-- Hero -->
<section class="relative pt-16 min-h-[55vh] flex items-center">
	<div class="absolute inset-0 bg-cover bg-center" style="<?php echo esc_attr( bewell_bg( 'scenery/IMG_3989.JPG' ) ); ?>"></div>
	<div class="absolute inset-0 bg-foreground/75"></div>

	<div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
		<span class="bw-badge mb-4 bg-primary/80 text-primary-foreground border-0"><?php esc_html_e( 'Hostel Services', 'bewell' ); ?></span>
		<h1 class="text-4xl sm:text-5xl font-bold text-white mb-4 max-w-2xl leading-tight">
			<?php esc_html_e( 'Rest & Recover in', 'bewell' ); ?><br>
			<span class="text-primary"><?php esc_html_e( 'Natural Tranquility', 'bewell' ); ?></span>
		</h1>
		<p class="text-white/85 text-lg max-w-xl leading-relaxed mb-6">
			<?php esc_html_e( 'Our hillside hostel provides peaceful, comfortable accommodation for program participants and guests seeking rest in a natural healing environment.', 'bewell' ); ?>
		</p>
		<div class="flex gap-6 flex-wrap">
			<div class="flex items-center gap-2 text-white/80 text-sm">
				<?php bewell_the_icon( 'leaf', 'w-4 h-4 text-primary' ); ?>
				<?php esc_html_e( 'Nature surroundings', 'bewell' ); ?>
			</div>
			<div class="flex items-center gap-2 text-white/80 text-sm">
				<?php bewell_the_icon( 'star', 'w-4 h-4 text-primary' ); ?>
				<?php esc_html_e( 'Meals included', 'bewell' ); ?>
			</div>
			<div class="flex items-center gap-2 text-white/80 text-sm">
				<?php bewell_the_icon( 'map-pin', 'w-4 h-4 text-primary' ); ?>
				<?php esc_html_e( 'Near Choto Daragar Hat', 'bewell' ); ?>
			</div>
		</div>
	</div>
</section>

<!-- Amenities -->
<section class="py-16 bg-background">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
		<div class="grid lg:grid-cols-2 gap-12 items-center">
			<div>
				<span class="bw-badge mb-4 bg-accent text-accent-foreground border-0"><?php esc_html_e( 'Facilities', 'bewell' ); ?></span>
				<h2 class="text-3xl font-bold text-foreground mb-4"><?php esc_html_e( 'A Peaceful Home Away From Home', 'bewell' ); ?></h2>
				<p class="text-muted-foreground mb-6 leading-relaxed">
					<?php esc_html_e( 'Our hostel is designed to support healing. From the clean, simply furnished rooms to the beautiful garden pathways, every detail promotes rest and restoration.', 'bewell' ); ?>
				</p>
				<div class="grid sm:grid-cols-2 gap-2.5">
					<?php foreach ( $bewell_amenities as $bewell_item ) : ?>
						<div class="flex items-center gap-2 text-sm text-foreground">
							<?php bewell_the_icon( 'check', 'w-4 h-4 text-primary shrink-0' ); ?>
							<?php echo esc_html( $bewell_item ); ?>
						</div>
					<?php endforeach; ?>
				</div>
			</div>

			<div class="grid grid-cols-2 gap-3">
				<?php foreach ( array_slice( $bewell_gallery, 0, 4 ) as $bewell_image ) : ?>
					<img src="<?php echo esc_url( bewell_img( $bewell_image[0] ) ); ?>"
						alt="<?php echo esc_attr( $bewell_image[1] ); ?>"
						class="rounded-lg w-full object-cover aspect-square" loading="lazy" decoding="async">
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>

<!-- Room types -->
<section class="py-16 bg-muted">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
		<div class="text-center mb-10">
			<span class="bw-badge mb-4 bg-accent text-accent-foreground border-0"><?php esc_html_e( 'Accommodation', 'bewell' ); ?></span>
			<h2 class="text-3xl font-bold text-foreground mb-4"><?php esc_html_e( 'Room Options', 'bewell' ); ?></h2>
			<p class="text-muted-foreground max-w-2xl mx-auto">
				<?php esc_html_e( 'We offer several room types to suit individuals, couples, and families.', 'bewell' ); ?>
			</p>
		</div>

		<div class="grid md:grid-cols-3 gap-6">
			<?php foreach ( $bewell_rooms as $bewell_room ) : ?>
				<div class="bw-card overflow-hidden border-border hover:shadow-md transition-shadow">
					<img src="<?php echo esc_url( bewell_img( $bewell_room['image'] ) ); ?>"
						alt="<?php echo esc_attr( $bewell_room['title'] ); ?>"
						class="w-full h-48 object-cover" loading="lazy" decoding="async">
					<div class="p-5">
						<h3 class="font-semibold text-foreground text-lg mb-2"><?php echo esc_html( $bewell_room['title'] ); ?></h3>
						<p class="text-sm text-muted-foreground mb-4 leading-relaxed"><?php echo esc_html( $bewell_room['desc'] ); ?></p>
						<ul class="space-y-1.5">
							<?php foreach ( $bewell_room['features'] as $bewell_feature ) : ?>
								<li class="flex items-center gap-2 text-xs text-foreground">
									<?php bewell_the_icon( 'check', 'w-3.5 h-3.5 text-primary shrink-0' ); ?>
									<?php echo esc_html( $bewell_feature ); ?>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- Campus gallery -->
<section class="py-16 bg-background">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
		<div class="text-center mb-8">
			<h2 class="text-3xl font-bold text-foreground mb-3"><?php esc_html_e( 'Our Campus', 'bewell' ); ?></h2>
			<p class="text-muted-foreground"><?php esc_html_e( 'Flowers, fruit trees, and nature on every side.', 'bewell' ); ?></p>
		</div>
		<div class="grid grid-cols-2 md:grid-cols-3 gap-4">
			<?php foreach ( $bewell_gallery as $bewell_image ) : ?>
				<img src="<?php echo esc_url( bewell_img( $bewell_image[0] ) ); ?>"
					alt="<?php echo esc_attr( $bewell_image[1] ); ?>"
					class="rounded-lg w-full object-cover aspect-[4/3]" loading="lazy" decoding="async">
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php if ( $bewell_testimonials ) : ?>
	<section class="py-16 bg-muted">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
			<div class="text-center mb-10">
				<h2 class="text-3xl font-bold text-foreground mb-4"><?php esc_html_e( 'Guest Experiences', 'bewell' ); ?></h2>
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

<!-- CTA -->
<section class="py-16 bg-primary text-primary-foreground">
	<div class="max-w-3xl mx-auto px-4 text-center">
		<h2 class="text-3xl font-bold mb-4"><?php esc_html_e( 'Ready to Book Your Stay?', 'bewell' ); ?></h2>
		<p class="text-primary-foreground/80 mb-6">
			<?php esc_html_e( 'Contact us to check availability and learn about hostel rates. Accommodation is included for all program participants.', 'bewell' ); ?>
		</p>
		<a href="<?php echo esc_url( bewell_url( 'contact' ) ); ?>" class="bw-btn bw-btn-lg bg-primary-foreground text-primary hover:bg-primary-foreground/90">
			<?php esc_html_e( 'Contact Us for Booking', 'bewell' ); ?>
		</a>
	</div>
</section>

<?php
get_footer();
