<?php
/**
 * Template Name: BE WELL Farm
 *
 * Ported from src/pages/Farm.tsx.
 *
 * One addition: the React page carried a `farm_products` table it never
 * rendered — there is a bare gap between the two sections where the grid used
 * to be. The products section below fills that gap, and renders nothing at all
 * when no products are published, so an empty site looks exactly like the page
 * it replaces.
 *
 * @package bewell
 */

defined( 'ABSPATH' ) || exit;

get_header();

$bewell_practices = array(
	__( 'No chemical pesticides or fertilizers', 'bewell' ),
	__( 'Composting and organic soil enrichment', 'bewell' ),
	__( 'Rainwater harvesting and conservation', 'bewell' ),
	__( 'Crop rotation for soil health', 'bewell' ),
	__( 'Intercropping for biodiversity', 'bewell' ),
	__( 'Hand-harvested daily for freshness', 'bewell' ),
);

$bewell_farm_images = array(
	array( 'garden/IMG_3887.JPG', __( 'Farm landscape at BE WELL', 'bewell' ) ),
	array( 'garden/IMG_3893.JPG', __( 'Fresh vegetables from our garden', 'bewell' ) ),
	array( 'garden/IMG_3895.JPG', __( 'Fruit trees on campus', 'bewell' ) ),
	array( 'garden/IMG_3914.JPG', __( 'Garden herbs growing at BE WELL', 'bewell' ) ),
);

$bewell_products = bewell_get_products();
?>

<!-- Hero -->
<section class="relative pt-16 min-h-[55vh] flex items-center">
	<div class="absolute inset-0 bg-cover bg-center" style="<?php echo esc_attr( bewell_bg( 'garden/IMG_3924.JPG' ) ); ?>"></div>
	<div class="absolute inset-0 bg-foreground/75"></div>

	<div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
		<span class="bw-badge mb-4 bg-primary/80 text-primary-foreground border-0"><?php esc_html_e( 'BE WELL Farm', 'bewell' ); ?></span>
		<h1 class="text-4xl sm:text-5xl font-bold text-white mb-4 max-w-2xl leading-tight">
			<?php esc_html_e( 'Fresh From Our', 'bewell' ); ?><br>
			<span class="text-primary"><?php esc_html_e( 'Best-Practices Farm', 'bewell' ); ?></span>
		</h1>
		<p class="text-white/85 text-lg max-w-xl leading-relaxed mb-6">
			<?php esc_html_e( 'Our organic farm grows fruits, vegetables, and medicinal herbs using sustainable, chemical-free methods. All produce feeds our guests and is available for purchase.', 'bewell' ); ?>
		</p>
		<div class="flex gap-6 flex-wrap">
			<div class="flex items-center gap-2 text-white/80 text-sm">
				<?php bewell_the_icon( 'leaf', 'w-4 h-4 text-primary' ); ?>
				<?php esc_html_e( '100% chemical free', 'bewell' ); ?>
			</div>
			<div class="flex items-center gap-2 text-white/80 text-sm">
				<?php bewell_the_icon( 'check', 'w-4 h-4 text-primary' ); ?>
				<?php esc_html_e( 'Hand-harvested daily', 'bewell' ); ?>
			</div>
		</div>
	</div>
</section>

<!-- About the farm -->
<section class="py-16 bg-background">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
		<div class="grid lg:grid-cols-2 gap-12 items-center">
			<div>
				<span class="bw-badge mb-4 bg-accent text-accent-foreground border-0"><?php esc_html_e( 'Our Approach', 'bewell' ); ?></span>
				<h2 class="text-3xl font-bold text-foreground mb-4"><?php esc_html_e( 'Best-Practices Farming', 'bewell' ); ?></h2>
				<p class="text-muted-foreground mb-5 leading-relaxed">
					<?php esc_html_e( 'Our farm is a demonstration of what agriculture can look like when it respects the natural order. We grow a wide variety of fruits, vegetables, and medicinal herbs without any chemical inputs.', 'bewell' ); ?>
				</p>
				<p class="text-muted-foreground mb-6 leading-relaxed">
					<?php esc_html_e( 'The food grown here feeds every guest on campus, and the surplus is available for purchase by the local community.', 'bewell' ); ?>
				</p>
				<ul class="space-y-2">
					<?php foreach ( $bewell_practices as $bewell_practice ) : ?>
						<li class="flex items-center gap-2.5 text-sm text-foreground">
							<?php bewell_the_icon( 'check', 'w-4 h-4 text-primary shrink-0' ); ?>
							<?php echo esc_html( $bewell_practice ); ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>

			<div class="grid grid-cols-2 gap-3">
				<?php foreach ( $bewell_farm_images as $bewell_image ) : ?>
					<img src="<?php echo esc_url( bewell_img( $bewell_image[0] ) ); ?>"
						alt="<?php echo esc_attr( $bewell_image[1] ); ?>"
						class="rounded-lg w-full object-cover aspect-square" loading="lazy" decoding="async">
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>

<?php if ( $bewell_products ) : ?>
	<!-- Produce currently available -->
	<section class="py-16 bg-muted">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
			<div class="text-center mb-10">
				<span class="bw-badge mb-4 bg-accent text-accent-foreground border-0"><?php esc_html_e( 'Our Produce', 'bewell' ); ?></span>
				<h2 class="text-3xl font-bold text-foreground mb-4"><?php esc_html_e( 'What We Grow', 'bewell' ); ?></h2>
				<p class="text-muted-foreground max-w-2xl mx-auto">
					<?php esc_html_e( 'Availability changes with the season. Contact us for the current harvest.', 'bewell' ); ?>
				</p>
			</div>

			<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
				<?php
				foreach ( $bewell_products as $bewell_product ) :
					$bewell_price     = get_post_meta( $bewell_product->ID, '_bewell_price', true );
					$bewell_unit      = get_post_meta( $bewell_product->ID, '_bewell_unit', true );
					$bewell_available = get_post_meta( $bewell_product->ID, '_bewell_is_available', true );
					?>
					<div class="bw-card border-border overflow-hidden">
						<?php if ( has_post_thumbnail( $bewell_product ) ) : ?>
							<?php echo get_the_post_thumbnail( $bewell_product, 'medium', array( 'class' => 'w-full object-cover aspect-[4/3]', 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						<?php endif; ?>

						<div class="p-5">
							<div class="flex items-start justify-between gap-2 mb-1">
								<h3 class="font-semibold text-foreground"><?php echo esc_html( get_the_title( $bewell_product ) ); ?></h3>
								<?php if ( ! $bewell_available ) : ?>
									<span class="bw-badge text-xs shrink-0 bg-muted text-muted-foreground"><?php esc_html_e( 'Out of season', 'bewell' ); ?></span>
								<?php endif; ?>
							</div>

							<?php if ( $bewell_product->post_content ) : ?>
								<p class="text-sm text-muted-foreground leading-relaxed"><?php echo esc_html( bewell_excerpt( $bewell_product, 22 ) ); ?></p>
							<?php endif; ?>

							<?php if ( $bewell_price ) : ?>
								<p class="text-primary font-semibold mt-3">
									<?php echo esc_html( $bewell_price ); ?><?php echo $bewell_unit ? esc_html( ' / ' . $bewell_unit ) : ''; ?>
								</p>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
<?php endif; ?>

<!-- How to order -->
<section class="py-16 bg-background">
	<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
		<div class="bw-card border-border">
			<div class="p-8">
				<div class="grid md:grid-cols-2 gap-8 items-center">
					<div>
						<span class="bw-badge mb-3 bg-accent text-accent-foreground border-0"><?php esc_html_e( 'How to Order', 'bewell' ); ?></span>
						<h2 class="text-2xl font-bold text-foreground mb-3"><?php esc_html_e( 'Get Fresh Farm Produce', 'bewell' ); ?></h2>
						<p class="text-muted-foreground mb-4 leading-relaxed">
							<?php esc_html_e( 'To purchase produce from the BE WELL farm, simply contact us by phone or visit our campus. Bulk orders welcome. We can arrange delivery for large orders.', 'bewell' ); ?>
						</p>
						<ul class="space-y-2 mb-5">
							<?php
							$bewell_order_points = array(
								__( 'Fresh harvest available most mornings', 'bewell' ),
								__( 'Walk-in purchases welcome', 'bewell' ),
								__( 'Bulk and wholesale inquiries welcome', 'bewell' ),
							);
							foreach ( $bewell_order_points as $bewell_point ) :
								?>
								<li class="flex items-center gap-2 text-sm text-foreground">
									<?php bewell_the_icon( 'check', 'w-4 h-4 text-primary shrink-0' ); ?>
									<?php echo esc_html( $bewell_point ); ?>
								</li>
							<?php endforeach; ?>
						</ul>

						<a href="<?php echo esc_url( bewell_url( 'contact' ) ); ?>" class="bw-btn bw-btn-default bw-btn-primary">
							<?php bewell_the_icon( 'phone', 'mr-2 w-4 h-4' ); ?>
							<?php esc_html_e( 'Contact to Order', 'bewell' ); ?>
						</a>
					</div>

					<div>
						<img src="<?php echo esc_url( bewell_img( 'garden/IMG_4028.JPG' ) ); ?>"
							alt="<?php esc_attr_e( 'Fresh farm produce available at BE WELL', 'bewell' ); ?>"
							class="rounded-xl w-full object-cover aspect-[4/3]" loading="lazy" decoding="async">
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
