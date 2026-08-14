<?php
/**
 * Fallback template.
 *
 * WordPress requires index.php to exist. The site has no blog, so this only
 * ever renders if someone reaches an archive or a post directly.
 *
 * @package bewell
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<div class="pt-16">
	<header class="bg-primary text-primary-foreground">
		<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
			<h1 class="text-4xl sm:text-5xl font-bold leading-tight">
				<?php
				if ( is_search() ) {
					/* translators: %s: search query. */
					printf( esc_html__( 'Search results for “%s”', 'bewell' ), esc_html( get_search_query() ) );
				} elseif ( is_archive() ) {
					the_archive_title();
				} else {
					esc_html_e( 'Latest', 'bewell' );
				}
				?>
			</h1>
		</div>
	</header>

	<div class="py-16 bg-background">
		<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
			<?php if ( have_posts() ) : ?>
				<div class="space-y-6">
					<?php
					while ( have_posts() ) :
						the_post();
						?>
						<article class="bw-card border-border">
							<div class="p-6">
								<h2 class="font-semibold text-foreground text-lg mb-2">
									<a href="<?php the_permalink(); ?>" class="hover:text-primary transition-colors"><?php the_title(); ?></a>
								</h2>
								<p class="text-sm text-muted-foreground leading-relaxed"><?php echo esc_html( get_the_excerpt() ); ?></p>
							</div>
						</article>
					<?php endwhile; ?>
				</div>

				<div class="mt-8 text-sm text-muted-foreground">
					<?php the_posts_pagination( array( 'mid_size' => 1 ) ); ?>
				</div>
			<?php else : ?>
				<p class="text-muted-foreground"><?php esc_html_e( 'Nothing found.', 'bewell' ); ?></p>
			<?php endif; ?>
		</div>
	</div>
</div>

<?php
get_footer();
