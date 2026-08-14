<?php
/**
 * Storage for form submissions.
 *
 * These are custom tables rather than custom post types on purpose. Submissions
 * are not content: they carry health information and personal contact details,
 * they must never appear in search, feeds, sitemaps or the REST API, and they
 * need to be exported and purged as a unit. A CPT leaks into all of those by
 * default and has to be locked down in a dozen places; a private table starts
 * closed. The columns mirror the Supabase schema one-for-one so the migration
 * is a straight copy.
 *
 * @package bewell
 */

defined( 'ABSPATH' ) || exit;

/**
 * Bumped whenever the schema changes so bewell_maybe_upgrade_tables() knows to
 * re-run dbDelta on an existing install.
 */
define( 'BEWELL_DB_VERSION', '1.0.0' );

/**
 * Fully-qualified table name for one of the submission stores.
 *
 * @param string $which One of: program, job, contact.
 * @return string
 */
function bewell_table( $which ) {
	global $wpdb;

	$map = array(
		'program'  => 'bewell_program_applications',
		'training' => 'bewell_training_applications',
		'job'      => 'bewell_job_applications',
		'contact'  => 'bewell_contact_messages',
	);

	if ( ! isset( $map[ $which ] ) ) {
		return '';
	}

	return $wpdb->prefix . $map[ $which ];
}

/**
 * Create or update the submission tables.
 *
 * @return void
 */
function bewell_install_tables() {
	global $wpdb;

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';

	$charset = $wpdb->get_charset_collate();

	$program  = bewell_table( 'program' );
	$training = bewell_table( 'training' );
	$job      = bewell_table( 'job' );
	$contact  = bewell_table( 'contact' );

	// Lengths are generous rather than exact: the Supabase columns were all
	// unbounded `text`, and truncating a patient's description of their
	// condition on the way in would be a silent data loss bug.
	$sql = array();

	$sql[] = "CREATE TABLE {$program} (
		id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		name varchar(191) NOT NULL DEFAULT '',
		email varchar(191) NOT NULL DEFAULT '',
		phone varchar(64) NOT NULL DEFAULT '',
		health_condition text NOT NULL,
		program_length varchar(64) NOT NULL DEFAULT '',
		message longtext NOT NULL,
		status varchar(32) NOT NULL DEFAULT 'new',
		source_ip varchar(100) NOT NULL DEFAULT '',
		created_at datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
		PRIMARY KEY  (id),
		KEY created_at (created_at),
		KEY status (status)
	) {$charset};";

	// The Training Program form had no table at all: its React handler called
	// setSubmitted(true) and returned, so every applicant since launch saw
	// "Application Received!" and nothing was stored anywhere.
	$sql[] = "CREATE TABLE {$training} (
		id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		name varchar(191) NOT NULL DEFAULT '',
		email varchar(191) NOT NULL DEFAULT '',
		phone varchar(64) NOT NULL DEFAULT '',
		background varchar(191) NOT NULL DEFAULT '',
		message longtext NOT NULL,
		status varchar(32) NOT NULL DEFAULT 'new',
		source_ip varchar(100) NOT NULL DEFAULT '',
		created_at datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
		PRIMARY KEY  (id),
		KEY created_at (created_at),
		KEY status (status)
	) {$charset};";

	$sql[] = "CREATE TABLE {$job} (
		id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		name varchar(191) NOT NULL DEFAULT '',
		email varchar(191) NOT NULL DEFAULT '',
		phone varchar(64) NOT NULL DEFAULT '',
		position varchar(191) NOT NULL DEFAULT '',
		experience longtext NOT NULL,
		motivation longtext NOT NULL,
		status varchar(32) NOT NULL DEFAULT 'new',
		source_ip varchar(100) NOT NULL DEFAULT '',
		created_at datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
		PRIMARY KEY  (id),
		KEY created_at (created_at),
		KEY status (status)
	) {$charset};";

	$sql[] = "CREATE TABLE {$contact} (
		id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		name varchar(191) NOT NULL DEFAULT '',
		email varchar(191) NOT NULL DEFAULT '',
		phone varchar(64) NOT NULL DEFAULT '',
		subject varchar(191) NOT NULL DEFAULT '',
		message longtext NOT NULL,
		status varchar(32) NOT NULL DEFAULT 'new',
		source_ip varchar(100) NOT NULL DEFAULT '',
		created_at datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
		PRIMARY KEY  (id),
		KEY created_at (created_at),
		KEY status (status)
	) {$charset};";

	foreach ( $sql as $statement ) {
		dbDelta( $statement );
	}

	update_option( 'bewell_db_version', BEWELL_DB_VERSION );
}

/**
 * Re-run the installer when the schema version moves.
 *
 * Activation hooks do not fire on plugin-less theme updates, so this runs on
 * every admin load. The option check makes it a single get_option() in the
 * common case.
 *
 * @return void
 */
function bewell_maybe_upgrade_tables() {
	if ( get_option( 'bewell_db_version' ) === BEWELL_DB_VERSION ) {
		return;
	}

	bewell_install_tables();
	bewell_register_roles();
}
add_action( 'admin_init', 'bewell_maybe_upgrade_tables' );

/**
 * Insert a submission.
 *
 * @param string $which One of: program, job, contact.
 * @param array  $data  Column => value. Unknown keys are dropped.
 * @return int|WP_Error Inserted row ID, or an error.
 */
function bewell_insert_submission( $which, $data ) {
	global $wpdb;

	$table = bewell_table( $which );
	if ( ! $table ) {
		return new WP_Error( 'bewell_bad_table', __( 'Unknown submission type.', 'bewell' ) );
	}

	$allowed = array(
		'program'  => array( 'name', 'email', 'phone', 'health_condition', 'program_length', 'message' ),
		'training' => array( 'name', 'email', 'phone', 'background', 'message' ),
		'job'      => array( 'name', 'email', 'phone', 'position', 'experience', 'motivation' ),
		'contact'  => array( 'name', 'email', 'phone', 'subject', 'message' ),
	);

	$row = array();
	foreach ( $allowed[ $which ] as $column ) {
		$row[ $column ] = isset( $data[ $column ] ) ? $data[ $column ] : '';
	}

	$row['status']     = 'new';
	$row['source_ip']  = bewell_client_ip();
	$row['created_at'] = current_time( 'mysql' );

	$inserted = $wpdb->insert( $table, $row ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery

	if ( false === $inserted ) {
		// Surfaced to the visitor as a failure rather than a success — the
		// original site's worst bug was telling people their application had
		// been received while discarding it.
		return new WP_Error(
			'bewell_insert_failed',
			__( 'Could not save the submission.', 'bewell' ),
			array( 'db_error' => $wpdb->last_error )
		);
	}

	return (int) $wpdb->insert_id;
}

/**
 * Fetch submissions for the admin screens.
 *
 * @param string $which  One of: program, job, contact.
 * @param array  $args   Optional. status, search, per_page, page.
 * @return array{items: array, total: int}
 */
function bewell_get_submissions( $which, $args = array() ) {
	global $wpdb;

	$table = bewell_table( $which );
	if ( ! $table ) {
		return array(
			'items' => array(),
			'total' => 0,
		);
	}

	$args = wp_parse_args(
		$args,
		array(
			'status'   => '',
			'search'   => '',
			'per_page' => 25,
			'page'     => 1,
		)
	);

	$where  = array( '1=1' );
	$params = array();

	if ( $args['status'] && 'all' !== $args['status'] ) {
		$where[]  = 'status = %s';
		$params[] = $args['status'];
	}

	if ( $args['search'] ) {
		$like     = '%' . $wpdb->esc_like( $args['search'] ) . '%';
		$where[]  = '(name LIKE %s OR email LIKE %s OR phone LIKE %s)';
		$params[] = $like;
		$params[] = $like;
		$params[] = $like;
	}

	$where_sql = implode( ' AND ', $where );

	// $table comes from the fixed map in bewell_table() and $where_sql is built
	// only from the literals above, so the only interpolated values are the
	// placeholders bound by prepare().
	$count_sql = "SELECT COUNT(*) FROM {$table} WHERE {$where_sql}";
	$total     = (int) ( $params
		? $wpdb->get_var( $wpdb->prepare( $count_sql, $params ) ) // phpcs:ignore WordPress.DB
		: $wpdb->get_var( $count_sql ) ); // phpcs:ignore WordPress.DB

	$per_page = max( 1, (int) $args['per_page'] );
	$offset   = max( 0, ( (int) $args['page'] - 1 ) * $per_page );

	$list_sql    = "SELECT * FROM {$table} WHERE {$where_sql} ORDER BY created_at DESC, id DESC LIMIT %d OFFSET %d";
	$list_params = array_merge( $params, array( $per_page, $offset ) );

	$items = $wpdb->get_results( $wpdb->prepare( $list_sql, $list_params ), ARRAY_A ); // phpcs:ignore WordPress.DB

	return array(
		'items' => $items ? $items : array(),
		'total' => $total,
	);
}

/**
 * Update a submission's triage status.
 *
 * @param string $which  One of: program, job, contact.
 * @param int    $id     Row ID.
 * @param string $status New status.
 * @return bool
 */
function bewell_set_submission_status( $which, $id, $status ) {
	global $wpdb;

	$table = bewell_table( $which );
	if ( ! $table || ! in_array( $status, bewell_statuses( $which ), true ) ) {
		return false;
	}

	return false !== $wpdb->update( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		$table,
		array( 'status' => $status ),
		array( 'id' => (int) $id ),
		array( '%s' ),
		array( '%d' )
	);
}

/**
 * Delete a submission.
 *
 * @param string $which One of: program, job, contact.
 * @param int    $id    Row ID.
 * @return bool
 */
function bewell_delete_submission( $which, $id ) {
	global $wpdb;

	$table = bewell_table( $which );
	if ( ! $table ) {
		return false;
	}

	return false !== $wpdb->delete( $table, array( 'id' => (int) $id ), array( '%d' ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
}

/**
 * Valid triage statuses per submission type.
 *
 * @param string $which One of: program, job, contact.
 * @return string[]
 */
function bewell_statuses( $which ) {
	$map = array(
		'program'  => array( 'new', 'contacted', 'accepted', 'declined' ),
		'training' => array( 'new', 'contacted', 'enrolled', 'declined' ),
		'job'      => array( 'new', 'contacted', 'hired', 'declined' ),
		'contact'  => array( 'new', 'replied', 'closed' ),
	);

	return isset( $map[ $which ] ) ? $map[ $which ] : array( 'new' );
}

/**
 * Best-effort client IP, used only for rate limiting and spam triage.
 *
 * Proxy headers are trusted only when the site explicitly opts in, because
 * anyone can send an X-Forwarded-For and a spoofed value would let a bot walk
 * straight past the rate limiter.
 *
 * @return string
 */
function bewell_client_ip() {
	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';

	if ( apply_filters( 'bewell_trust_proxy_headers', false ) && ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		$forwarded = sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) );
		$first     = trim( explode( ',', $forwarded )[0] );
		if ( filter_var( $first, FILTER_VALIDATE_IP ) ) {
			$ip = $first;
		}
	}

	return filter_var( $ip, FILTER_VALIDATE_IP ) ? $ip : '';
}
