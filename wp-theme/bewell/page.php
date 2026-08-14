<?php
/**
 * Generic page template.
 *
 * The seven public pages each have their own hand-ported template. This covers
 * anything Eugene adds later from the dashboard — a privacy policy, a schedule,
 * a notice — and renders the block editor's output inside the site chrome.
 *
 * @package bewell
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<article class="pt-16">
	<header class="bg-primary text-primary-foreground">
		<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
			<h1 class="text-4xl sm:text-5xl font-bold leading-tight"><?php the_title(); ?></h1>
		</div>
	</header>

	<div class="py-16 bg-background">
		<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
			<div class="bewell-content space-y-4 text-foreground">
				<?php
				while ( have_posts() ) {
					the_post();
					the_content();
				}

				wp_link_pages(
					array(
						'before' => '<nav class="mt-8 text-sm text-muted-foreground">',
						'after'  => '</nav>',
					)
				);
				?>
			</div>
		</div>
	</div>
</article>

<?php
get_footer();
