<?php
/**
 * A single testimonial card — ported from src/components/TestimonialCard.tsx.
 *
 * @param array $args {
 *     @type WP_Post $post The testimonial post.
 * }
 *
 * @package bewell
 */

defined( 'ABSPATH' ) || exit;

$bewell_post = isset( $args['post'] ) ? $args['post'] : null;

if ( ! $bewell_post instanceof WP_Post ) {
	return;
}

$bewell_role = get_post_meta( $bewell_post->ID, '_bewell_role', true );
?>

<div class="bw-card h-full border-border">
	<div class="p-6 flex flex-col h-full">
		<?php bewell_the_icon( 'quote', 'w-8 h-8 text-primary/20 mb-4' ); ?>

		<p class="text-sm text-muted-foreground leading-relaxed flex-1">
			<?php echo esc_html( bewell_excerpt( $bewell_post, 60 ) ); ?>
		</p>

		<div class="mt-5 pt-5 border-t border-border">
			<p class="font-semibold text-foreground text-sm"><?php echo esc_html( get_the_title( $bewell_post ) ); ?></p>
			<?php if ( $bewell_role ) : ?>
				<p class="text-xs text-muted-foreground mt-0.5"><?php echo esc_html( $bewell_role ); ?></p>
			<?php endif; ?>
		</div>
	</div>
</div>
