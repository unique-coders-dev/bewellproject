<?php
/**
 * 404 page.
 *
 * Matters more than usual here: the old site used hash routes (#lifestyle), so
 * anything linking to those old URLs lands on the home page rather than a real
 * path. Anyone who does hit a dead URL gets pointed back at the programmes
 * instead of a bare error.
 *
 * @package bewell
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<section class="pt-16 min-h-[60vh] flex items-center bg-background">
	<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
		<span class="bw-badge mb-4 bg-accent text-accent-foreground border-0"><?php esc_html_e( 'Page not found', 'bewell' ); ?></span>

		<h1 class="text-4xl sm:text-5xl font-bold text-foreground mb-4 leading-tight">
			<?php esc_html_e( 'We could not find that page', 'bewell' ); ?>
		</h1>

		<p class="text-muted-foreground mb-8 leading-relaxed">
			<?php esc_html_e( 'The page may have moved. Here is where most people are heading:', 'bewell' ); ?>
		</p>

		<div class="grid sm:grid-cols-2 gap-3 text-left mb-8">
			<?php
			$bewell_suggestions = array(
				'lifestyle' => __( 'Lifestyle Program', 'bewell' ),
				'training'  => __( 'Training Program', 'bewell' ),
				'hostel'    => __( 'Hostel Services', 'bewell' ),
				'farm'      => __( 'BE WELL Farm', 'bewell' ),
			);

			foreach ( $bewell_suggestions as $bewell_key => $bewell_label ) :
				?>
				<a href="<?php echo esc_url( bewell_url( $bewell_key ) ); ?>" class="bw-card border-border p-4 hover:shadow-md transition-shadow flex items-center justify-between gap-2">
					<span class="text-sm font-medium text-foreground"><?php echo esc_html( $bewell_label ); ?></span>
					<?php bewell_the_icon( 'chevron-right', 'w-4 h-4 text-primary shrink-0' ); ?>
				</a>
			<?php endforeach; ?>
		</div>

		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="bw-btn bw-btn-default bw-btn-primary">
			<?php esc_html_e( 'Back to the home page', 'bewell' ); ?>
		</a>
	</div>
</section>

<?php
get_footer();
