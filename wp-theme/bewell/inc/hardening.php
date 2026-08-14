<?php
/**
 * Closing off things WordPress exposes by default that this site has no use for.
 *
 * @package bewell
 */

defined( 'ABSPATH' ) || exit;

/**
 * Stop WordPress advertising who its users are.
 *
 * Out of the box, /?author=1 redirects to /author/<login-slug>/ and the core
 * sitemap lists every author archive. On the live site that published
 * `author/arnoldfamini21gmail-com/`, which hands an attacker the administrator's
 * login name — half of a credential pair — and lets search engines index it.
 *
 * This site has no bylines and no author pages worth reading, so the archives
 * are simply gone: the ?author= probe 404s instead of redirecting, and the
 * users section is dropped from the sitemap.
 *
 * @return void
 */
function bewell_block_author_enumeration() {
	if ( is_admin() ) {
		return;
	}

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- public URL probe, not a form.
	$probing = isset( $_GET['author'] );

	if ( $probing || is_author() ) {
		global $wp_query;

		$wp_query->set_404();
		status_header( 404 );
		nocache_headers();
	}
}
add_action( 'template_redirect', 'bewell_block_author_enumeration', 1 );

/**
 * Drop the users section from the core sitemap.
 *
 * @param WP_Sitemaps_Provider $provider Provider instance.
 * @param string               $name     Provider name.
 * @return WP_Sitemaps_Provider|false
 */
function bewell_trim_sitemap_providers( $provider, $name ) {
	if ( 'users' === $name ) {
		return false;
	}

	return $provider;
}
add_filter( 'wp_sitemaps_add_provider', 'bewell_trim_sitemap_providers', 10, 2 );

/**
 * Remove the author archive rewrite rules entirely, so nothing generates or
 * links to a URL that no longer resolves.
 *
 * @param array $rules Rewrite rules.
 * @return array
 */
function bewell_remove_author_rewrites( $rules ) {
	foreach ( array_keys( $rules ) as $rule ) {
		if ( false !== strpos( $rule, 'author' ) ) {
			unset( $rules[ $rule ] );
		}
	}

	return $rules;
}
add_filter( 'author_rewrite_rules', '__return_empty_array' );
add_filter( 'rewrite_rules_array', 'bewell_remove_author_rewrites' );

/**
 * Hide the REST users endpoint from anonymous callers.
 *
 * /wp-json/wp/v2/users is the other half of the same disclosure: it lists every
 * account's name and slug to anyone who asks. Logged-in editors still need it
 * for the block editor, so it is only closed to visitors.
 *
 * @param WP_REST_Response|WP_Error $response Response.
 * @param array                     $handler  Route handler.
 * @param WP_REST_Request           $request  Request.
 * @return WP_REST_Response|WP_Error
 */
function bewell_restrict_rest_users( $response, $handler, $request ) {
	if ( is_user_logged_in() ) {
		return $response;
	}

	$route = $request->get_route();

	if ( 0 === strpos( $route, '/wp/v2/users' ) ) {
		return new WP_Error(
			'rest_user_cannot_view',
			__( 'Sorry, you are not allowed to list users.', 'bewell' ),
			array( 'status' => 401 )
		);
	}

	return $response;
}
add_filter( 'rest_request_before_callbacks', 'bewell_restrict_rest_users', 10, 3 );

/**
 * Drop the XML-RPC endpoint.
 *
 * Nothing here uses it — no Jetpack, no mobile app publishing — and it remains
 * a standing brute-force surface because system.multicall lets an attacker try
 * many passwords in a single request.
 */
add_filter( 'xmlrpc_enabled', '__return_false' );

// The RSD link that advertises XML-RPC is already removed in
// bewell_clean_head(); it is not repeated here.
