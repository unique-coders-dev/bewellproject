<?php
/**
 * Front-end asset loading.
 *
 * @package bewell
 */

defined( 'ABSPATH' ) || exit;

/**
 * Enqueue the compiled stylesheet and the small navigation script.
 *
 * Versions come from filemtime so a deploy busts the cache without anyone
 * having to remember to bump a constant — the previous site shipped a hashed
 * bundle and got this for free.
 *
 * @return void
 */
function bewell_enqueue_assets() {
	$dir = get_template_directory();
	$uri = get_template_directory_uri();

	$css_path = $dir . '/assets/css/theme.css';
	$js_path  = $dir . '/assets/js/site.js';

	wp_enqueue_style(
		'bewell',
		$uri . '/assets/css/theme.css',
		array(),
		file_exists( $css_path ) ? (string) filemtime( $css_path ) : '1.0.0'
	);

	wp_enqueue_script(
		'bewell',
		$uri . '/assets/js/site.js',
		array(),
		file_exists( $js_path ) ? (string) filemtime( $js_path ) : '1.0.0',
		true
	);
}
add_action( 'wp_enqueue_scripts', 'bewell_enqueue_assets' );

/**
 * Drop WordPress's default block library CSS.
 *
 * The theme renders no block content on the public pages, so this is ~90 KB of
 * stylesheet that only competes with Tailwind's reset. The editor still loads
 * its own copy, so Gutenberg is unaffected.
 *
 * @return void
 */
function bewell_dequeue_block_styles() {
	if ( is_admin() ) {
		return;
	}

	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'global-styles' );
	wp_dequeue_style( 'classic-theme-styles' );
}
add_action( 'wp_enqueue_scripts', 'bewell_dequeue_block_styles', 100 );

/**
 * Preconnect and preload the hero image on the front page.
 *
 * The hero is a full-viewport background image and is by definition the largest
 * contentful paint; without this hint the browser does not discover it until
 * the stylesheet has parsed.
 *
 * @return void
 */
function bewell_resource_hints() {
	if ( ! is_front_page() ) {
		return;
	}

	printf(
		'<link rel="preload" as="image" href="%s" fetchpriority="high">' . "\n",
		esc_url( bewell_img( 'buildings/IMG_3865.JPG' ) )
	);
}
add_action( 'wp_head', 'bewell_resource_hints', 2 );
