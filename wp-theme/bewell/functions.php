<?php
/**
 * BE WELL theme bootstrap.
 *
 * @package bewell
 */

defined( 'ABSPATH' ) || exit;

define( 'BEWELL_VERSION', '1.0.0' );

/**
 * Load the theme's modules.
 *
 * Order matters: helpers and icons are used by everything, db defines the
 * tables that forms and admin write to, and roles defines the capability the
 * admin screens check.
 */
$bewell_modules = array(
	'helpers',
	'icons',
	'setup',
	'assets',
	'db',
	'roles',
	'content',
	'forms',
	'admin',
	'hardening',
	'migrate',
);

foreach ( $bewell_modules as $bewell_module ) {
	require_once get_template_directory() . '/inc/' . $bewell_module . '.php';
}
unset( $bewell_module );

/**
 * Warn loudly in the dashboard if the compiled stylesheet is missing.
 *
 * The theme's CSS is built by Tailwind, not committed as source. A deploy that
 * skips the build step produces an unstyled site that still returns HTTP 200 —
 * easy to miss, so say it plainly.
 *
 * @return void
 */
function bewell_warn_missing_css() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	if ( file_exists( get_template_directory() . '/assets/css/theme.css' ) ) {
		return;
	}

	printf(
		'<div class="notice notice-error"><p><strong>%s</strong> %s</p></div>',
		esc_html__( 'BE WELL theme:', 'bewell' ),
		esc_html__( 'assets/css/theme.css is missing, so the site is rendering unstyled. The Tailwind build did not run — deploy again with "npm run theme:build".', 'bewell' )
	);
}
add_action( 'admin_notices', 'bewell_warn_missing_css' );

/**
 * Trim WordPress head output the site does not use.
 *
 * @return void
 */
function bewell_clean_head() {
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
}
add_action( 'init', 'bewell_clean_head' );

/**
 * Excerpt helper used by the testimonial and product cards.
 *
 * @param WP_Post $post   Post.
 * @param int     $words  Word limit.
 * @return string
 */
function bewell_excerpt( $post, $words = 40 ) {
	$text = $post->post_excerpt ? $post->post_excerpt : $post->post_content;
	$text = strip_shortcodes( $text );
	$text = wp_strip_all_tags( $text );

	return wp_trim_words( $text, $words, '…' );
}
