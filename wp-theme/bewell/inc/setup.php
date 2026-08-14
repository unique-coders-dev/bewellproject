<?php
/**
 * Theme supports, menus, page provisioning and Customizer options.
 *
 * @package bewell
 */

defined( 'ABSPATH' ) || exit;

/**
 * Standard theme supports.
 *
 * @return void
 */
function bewell_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
	);
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 80,
			'width'       => 240,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	register_nav_menus(
		array(
			'primary' => __( 'Primary Navigation', 'bewell' ),
		)
	);

	load_theme_textdomain( 'bewell', get_template_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'bewell_setup' );

/**
 * Create the seven public pages the first time the theme is activated, assign
 * their templates, and set the front page.
 *
 * Without this, activating the theme leaves a site with no pages and a blog
 * index at the root — the templates exist but nothing routes to them. Every
 * step is idempotent, so re-activating never duplicates a page.
 *
 * @return void
 */
function bewell_provision_pages() {
	$pages   = bewell_pages();
	$created = array();

	foreach ( $pages as $key => $page ) {
		$existing = get_page_by_path( $page['slug'] );

		if ( $existing ) {
			$created[ $key ] = $existing->ID;
		} else {
			$id = wp_insert_post(
				array(
					'post_type'      => 'page',
					'post_title'     => $page['title'],
					'post_name'      => $page['slug'],
					'post_status'    => 'publish',
					'post_content'   => '',
					'comment_status' => 'closed',
					'ping_status'    => 'closed',
				)
			);

			if ( is_wp_error( $id ) || ! $id ) {
				continue;
			}

			$created[ $key ] = $id;
		}

		// Templates are matched by filename for page-{slug}.php, but setting it
		// explicitly means renaming a page's slug later does not silently drop
		// it back to the generic page template.
		if ( $page['template'] ) {
			update_post_meta( $created[ $key ], '_wp_page_template', $page['template'] );
		}
	}

	if ( isset( $created['home'] ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $created['home'] );
	}

	// The React site had no blog. Pretty permalinks are still required or the
	// page URLs come out as ?page_id=N.
	if ( ! get_option( 'permalink_structure' ) ) {
		update_option( 'permalink_structure', '/%postname%/' );
	}

	flush_rewrite_rules();
}

/**
 * Run provisioning, table creation and role setup on activation.
 *
 * @return void
 */
function bewell_on_activate() {
	bewell_provision_pages();
	bewell_install_tables();
	bewell_register_roles();
	bewell_lock_down_registration();
}
add_action( 'after_switch_theme', 'bewell_on_activate' );

/**
 * Customizer: contact details.
 *
 * These are the values flagged as placeholders in the original site (the
 * +880 01700000000 phone and the unverified bewell.org addresses). Exposing
 * them here means Eugene can correct them himself instead of filing a change.
 *
 * @param WP_Customize_Manager $wp_customize Customizer instance.
 * @return void
 */
function bewell_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'bewell_contact',
		array(
			'title'       => __( 'Contact Details', 'bewell' ),
			'priority'    => 30,
			'description' => __( 'Shown in the header, footer and on the Contact page.', 'bewell' ),
		)
	);

	$fields = array(
		'phone'     => array( __( 'Primary phone', 'bewell' ), '+880 17 0000-0000' ),
		'phone_alt' => array( __( 'Secondary phone', 'bewell' ), '+880 18 0000-0000' ),
		'email'     => array( __( 'Primary email', 'bewell' ), 'info@bewell.org' ),
		'email_alt' => array( __( 'Programs email', 'bewell' ), 'programs@bewell.org' ),
		'address'   => array( __( 'Address', 'bewell' ), 'Near Choto Daragar Hat, Beautiful Hills, Bangladesh' ),
	);

	foreach ( $fields as $key => $field ) {
		$wp_customize->add_setting(
			'bewell_' . $key,
			array(
				'default'           => $field[1],
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			'bewell_' . $key,
			array(
				'label'   => $field[0],
				'section' => 'bewell_contact',
				'type'    => 'text',
			)
		);
	}

	// Where application notifications are sent. Defaults to the site admin
	// email so a fresh install never drops a notification on the floor.
	$wp_customize->add_setting(
		'bewell_notify_email',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_email',
		)
	);
	$wp_customize->add_control(
		'bewell_notify_email',
		array(
			'label'       => __( 'Send form notifications to', 'bewell' ),
			'description' => __( 'Leave blank to use the site administration email.', 'bewell' ),
			'section'     => 'bewell_contact',
			'type'        => 'email',
		)
	);
}
add_action( 'customize_register', 'bewell_customize_register' );

/**
 * Document title fallbacks matching the React app's per-page titles.
 *
 * @param array $parts Title parts.
 * @return array
 */
function bewell_document_title_parts( $parts ) {
	if ( is_front_page() ) {
		$parts['title']  = 'BE WELL';
		$parts['tagline'] = 'Center of Health & Healing';
	}

	return $parts;
}
add_filter( 'document_title_parts', 'bewell_document_title_parts' );

/**
 * Open Graph and description tags, carried over from the React index.html.
 *
 * @return void
 */
function bewell_meta_tags() {
	$description = get_bloginfo( 'description' );

	if ( is_front_page() || ! $description ) {
		$description = 'BE WELL is a center of health and healing. We operate a lifestyle program where persons suffering with heart disease, diabetes, cancer, high blood pressure, depression, and other lifestyle illnesses may come and find healing in two or three weeks of special care.';
	}

	$title = wp_get_document_title();
	$image = bewell_img( 'orphans/IMG_4180.JPG' );

	printf( '<meta name="description" content="%s">' . "\n", esc_attr( $description ) );
	printf( '<meta property="og:type" content="website">' . "\n" );
	printf( '<meta property="og:title" content="%s">' . "\n", esc_attr( $title ) );
	printf( '<meta property="og:description" content="%s">' . "\n", esc_attr( $description ) );
	printf( '<meta property="og:image" content="%s">' . "\n", esc_url( $image ) );
	printf( '<meta property="og:url" content="%s">' . "\n", esc_url( home_url( add_query_arg( array() ) ) ) );
	printf( '<meta name="twitter:card" content="summary_large_image">' . "\n" );
	printf( '<meta name="twitter:title" content="%s">' . "\n", esc_attr( $title ) );
	printf( '<meta name="twitter:description" content="%s">' . "\n", esc_attr( $description ) );
	printf( '<meta name="twitter:image" content="%s">' . "\n", esc_url( $image ) );
}
add_action( 'wp_head', 'bewell_meta_tags', 1 );
