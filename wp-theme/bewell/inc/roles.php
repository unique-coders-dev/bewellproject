<?php
/**
 * Roles, capabilities, and registration lockdown.
 *
 * The Supabase build gated the staff area on an allowlist table rather than on
 * `authenticated`, because public sign-ups were enabled and `TO authenticated`
 * alone would have exposed health data to anyone who registered. WordPress has
 * the same trap: `users_can_register` plus a default role is a public door.
 * This file closes it and defines who may read submissions.
 *
 * @package bewell
 */

defined( 'ABSPATH' ) || exit;

/**
 * The capability that guards every submission screen.
 */
define( 'BEWELL_CAP', 'bewell_manage_submissions' );

/**
 * Create the Staff role and grant the submissions capability.
 *
 * Staff can read and triage applications and edit site content, but cannot
 * install plugins, edit theme files, or manage users — so handing out a staff
 * login never hands out the site.
 *
 * @return void
 */
function bewell_register_roles() {
	// Removing first makes this idempotent: add_role() is a no-op when the role
	// already exists, which would otherwise freeze an outdated capability set.
	remove_role( 'bewell_staff' );

	add_role(
		'bewell_staff',
		__( 'BE WELL Staff', 'bewell' ),
		array(
			'read'                   => true,
			BEWELL_CAP               => true,

			// Content editing, equivalent to the built-in Editor role.
			'edit_posts'             => true,
			'edit_others_posts'      => true,
			'edit_published_posts'   => true,
			'publish_posts'          => true,
			'delete_posts'           => true,
			'delete_others_posts'    => true,
			'delete_published_posts' => true,
			'edit_pages'             => true,
			'edit_others_pages'      => true,
			'edit_published_pages'   => true,
			'publish_pages'          => true,
			'delete_pages'           => true,
			'delete_others_pages'    => true,
			'delete_published_pages' => true,
			'manage_categories'      => true,
			'upload_files'           => true,
		)
	);

	// Administrators (Eugene) get the capability too, or the owner would be
	// locked out of the screens his own staff can see.
	$admin = get_role( 'administrator' );
	if ( $admin ) {
		$admin->add_cap( BEWELL_CAP );
	}

	// Editors are given it as well, so an existing Editor account does not have
	// to be re-roled to do triage.
	$editor = get_role( 'editor' );
	if ( $editor ) {
		$editor->add_cap( BEWELL_CAP );
	}
}

/**
 * Disable public registration.
 *
 * Owner and staff accounts are created by an administrator from
 * Users → Add New. Nothing on the public site invites a visitor to register.
 *
 * @return void
 */
function bewell_lock_down_registration() {
	update_option( 'users_can_register', 0 );
	update_option( 'default_role', 'subscriber' );
}

/**
 * Belt and braces: refuse registration even if the option is flipped back on by
 * a plugin, an import, or a stray hand in Settings → General.
 *
 * @param WP_Error $errors Registration errors.
 * @return WP_Error
 */
function bewell_block_registration( $errors ) {
	if ( ! apply_filters( 'bewell_allow_public_registration', false ) ) {
		$errors->add(
			'registration_disabled',
			__( '<strong>Registration is closed.</strong> Accounts on this site are created by an administrator.', 'bewell' )
		);
	}

	return $errors;
}
add_filter( 'registration_errors', 'bewell_block_registration', 10, 1 );

/**
 * Hide the "Register" link on wp-login.php.
 *
 * @return void
 */
function bewell_hide_register_link() {
	if ( apply_filters( 'bewell_allow_public_registration', false ) ) {
		return;
	}

	echo '<style>#reg_passmail, #nav a[href*="action=register"] { display:none !important; }</style>';
}
add_action( 'login_head', 'bewell_hide_register_link' );

/**
 * Keep Staff out of the screens that would let them take over the site.
 *
 * The capability list above already withholds these, but an explicit menu prune
 * means a plugin that grants a broad capability does not quietly surface them.
 *
 * @return void
 */
function bewell_trim_staff_menu() {
	if ( ! current_user_can( 'bewell_staff' ) || current_user_can( 'manage_options' ) ) {
		return;
	}

	remove_menu_page( 'tools.php' );
	remove_menu_page( 'edit-comments.php' );
}
add_action( 'admin_menu', 'bewell_trim_staff_menu', 999 );

/**
 * Send Staff to the applications screen after login rather than the dashboard,
 * which for them is a page of empty widgets.
 *
 * @param string           $redirect Default redirect.
 * @param string           $request  Requested redirect.
 * @param WP_User|WP_Error $user     Logged-in user.
 * @return string
 */
function bewell_login_redirect( $redirect, $request, $user ) {
	if ( $user instanceof WP_User && in_array( 'bewell_staff', (array) $user->roles, true ) ) {
		// Honour an explicit destination (e.g. a link into a specific screen).
		if ( $request && $request !== admin_url() ) {
			return $redirect;
		}

		return admin_url( 'admin.php?page=bewell-program-applications' );
	}

	return $redirect;
}
add_filter( 'login_redirect', 'bewell_login_redirect', 10, 3 );

/**
 * Hide the admin bar for anyone who cannot edit content.
 *
 * @return void
 */
function bewell_maybe_hide_admin_bar() {
	if ( ! current_user_can( 'edit_posts' ) ) {
		show_admin_bar( false );
	}
}
add_action( 'after_setup_theme', 'bewell_maybe_hide_admin_bar' );
