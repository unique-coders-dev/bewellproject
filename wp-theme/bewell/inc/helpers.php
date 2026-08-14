<?php
/**
 * Small shared helpers: image URLs, page lookup, and escaping shorthands.
 *
 * @package bewell
 */

defined( 'ABSPATH' ) || exit;

/**
 * The seven public pages, keyed by the identifier the React app used for its
 * hash routes. Keeping the old keys means every ported template can be diffed
 * against its .tsx original without renaming anything.
 *
 * @return array<string, array{slug: string, title: string, template: string}>
 */
function bewell_pages() {
	return array(
		'home'      => array(
			'slug'     => 'home',
			'title'    => 'Home',
			'template' => '',
		),
		'lifestyle' => array(
			'slug'     => 'lifestyle-program',
			'title'    => 'Lifestyle Program',
			'template' => 'page-lifestyle.php',
		),
		'training'  => array(
			'slug'     => 'training-program',
			'title'    => 'Training Program',
			'template' => 'page-training.php',
		),
		'hostel'    => array(
			'slug'     => 'hostel-services',
			'title'    => 'Hostel Services',
			'template' => 'page-hostel.php',
		),
		'farm'      => array(
			'slug'     => 'farm',
			'title'    => 'BE WELL Farm',
			'template' => 'page-farm.php',
		),
		'work'      => array(
			'slug'     => 'work-with-us',
			'title'    => 'Work With Us',
			'template' => 'page-work.php',
		),
		'contact'   => array(
			'slug'     => 'contact',
			'title'    => 'Contact',
			'template' => 'page-contact.php',
		),
	);
}

/**
 * Resolve one of the keys above to a live URL.
 *
 * Falls back to home_url() rather than returning an empty href, so a missing
 * page produces a harmless link instead of one that reloads the current URL.
 *
 * @param string $key Page key, e.g. 'lifestyle'.
 * @return string
 */
function bewell_url( $key ) {
	if ( 'home' === $key ) {
		return home_url( '/' );
	}

	$pages = bewell_pages();
	if ( ! isset( $pages[ $key ] ) ) {
		return home_url( '/' );
	}

	// Cached per request; get_page_by_path() is a query and the nav calls this
	// a dozen times per page render.
	static $resolved = array();
	if ( isset( $resolved[ $key ] ) ) {
		return $resolved[ $key ];
	}

	$page = get_page_by_path( $pages[ $key ]['slug'] );
	$resolved[ $key ] = $page ? get_permalink( $page ) : home_url( '/' );

	return $resolved[ $key ];
}

/**
 * Whether the given page key is the one currently being viewed.
 *
 * @param string $key Page key.
 * @return bool
 */
function bewell_is_current( $key ) {
	if ( 'home' === $key ) {
		return is_front_page();
	}

	$pages = bewell_pages();
	if ( ! isset( $pages[ $key ] ) ) {
		return false;
	}

	$post = get_queried_object();

	return $post instanceof WP_Post && $post->post_name === $pages[ $key ]['slug'];
}

/**
 * URL for one of the site's photographs.
 *
 * The React site referenced these as absolute root paths (/images/…) and the
 * ~223 MB library already sits at the web root, outside WordPress. Routing every
 * reference through this one function means the library can later be moved into
 * the Media Library by filtering `bewell_image_base` — no template edits.
 *
 * @param string $path Path relative to the image root, e.g. 'buildings/IMG_3865.JPG'.
 * @return string
 */
function bewell_img( $path ) {
	$base = apply_filters( 'bewell_image_base', home_url( '/images' ) );

	return trailingslashit( $base ) . ltrim( $path, '/' );
}

/**
 * Escaped inline `background-image` value for a hero section.
 *
 * @param string $path Path relative to the image root.
 * @return string
 */
function bewell_bg( $path ) {
	return "background-image:url('" . esc_url( bewell_img( $path ) ) . "')";
}

/**
 * The site logo, falling back to the bundled asset when no custom logo is set.
 *
 * @return string
 */
function bewell_logo_url() {
	$custom = get_theme_mod( 'custom_logo' );
	if ( $custom ) {
		$src = wp_get_attachment_image_src( $custom, 'full' );
		if ( $src ) {
			return $src[0];
		}
	}

	return get_template_directory_uri() . '/assets/images/logo.png';
}

/**
 * Public contact details, editable in Appearance → Customize → Contact Details
 * so Eugene can correct the placeholder phone number without a deploy.
 *
 * @param string $key One of: phone, phone_alt, email, email_alt, address.
 * @return string
 */
function bewell_contact( $key ) {
	$defaults = array(
		'phone'     => '+880 17 0000-0000',
		'phone_alt' => '+880 18 0000-0000',
		'email'     => 'info@bewell.org',
		'email_alt' => 'programs@bewell.org',
		'address'   => 'Near Choto Daragar Hat, Beautiful Hills, Bangladesh',
	);

	$value = get_theme_mod( 'bewell_' . $key, $defaults[ $key ] ?? '' );

	return $value ? $value : ( $defaults[ $key ] ?? '' );
}

/**
 * `tel:` href for a display phone number — strips spaces and dashes.
 *
 * @param string $number Display number.
 * @return string
 */
function bewell_tel( $number ) {
	return 'tel:' . preg_replace( '/[^0-9+]/', '', $number );
}
