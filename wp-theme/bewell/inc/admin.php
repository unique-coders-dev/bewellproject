<?php
/**
 * Dashboard screens for reading and triaging form submissions.
 *
 * This replaces the hidden `#admin` staff area the React site carried. That
 * page had to hand-roll authentication against a Supabase allowlist; here the
 * screen simply is not reachable without the capability, and WordPress handles
 * the login, session and password reset.
 *
 * @package bewell
 */

defined( 'ABSPATH' ) || exit;

/**
 * Screen definitions.
 *
 * @return array
 */
function bewell_admin_screens() {
	return array(
		'bewell-program-applications' => array(
			'which'   => 'program',
			'title'   => __( 'Program Applications', 'bewell' ),
			'columns' => array(
				'name'             => __( 'Name', 'bewell' ),
				'phone'            => __( 'Phone', 'bewell' ),
				'email'            => __( 'Email', 'bewell' ),
				'health_condition' => __( 'Health concern', 'bewell' ),
				'program_length'   => __( 'Length', 'bewell' ),
				'message'          => __( 'About them', 'bewell' ),
			),
		),
		'bewell-training-applications' => array(
			'which'   => 'training',
			'title'   => __( 'Training Applications', 'bewell' ),
			'columns' => array(
				'name'       => __( 'Name', 'bewell' ),
				'phone'      => __( 'Phone', 'bewell' ),
				'email'      => __( 'Email', 'bewell' ),
				'background' => __( 'Background', 'bewell' ),
				'message'    => __( 'Why attend', 'bewell' ),
			),
		),
		'bewell-job-applications'     => array(
			'which'   => 'job',
			'title'   => __( 'Job Applications', 'bewell' ),
			'columns' => array(
				'name'       => __( 'Name', 'bewell' ),
				'phone'      => __( 'Phone', 'bewell' ),
				'email'      => __( 'Email', 'bewell' ),
				'position'   => __( 'Position', 'bewell' ),
				'experience' => __( 'Experience', 'bewell' ),
				'motivation' => __( 'Motivation', 'bewell' ),
			),
		),
		'bewell-contact-messages'     => array(
			'which'   => 'contact',
			'title'   => __( 'Contact Messages', 'bewell' ),
			'columns' => array(
				'name'    => __( 'Name', 'bewell' ),
				'phone'   => __( 'Phone', 'bewell' ),
				'email'   => __( 'Email', 'bewell' ),
				'subject' => __( 'Subject', 'bewell' ),
				'message' => __( 'Message', 'bewell' ),
			),
		),
	);
}

/**
 * Register the menu.
 *
 * @return void
 */
function bewell_admin_menu() {
	$screens = bewell_admin_screens();

	add_menu_page(
		__( 'BE WELL', 'bewell' ),
		__( 'BE WELL', 'bewell' ),
		BEWELL_CAP,
		'bewell-program-applications',
		'bewell_render_admin_screen',
		'dashicons-heart',
		20
	);

	foreach ( $screens as $slug => $screen ) {
		add_submenu_page(
			'bewell-program-applications',
			$screen['title'],
			$screen['title'] . bewell_new_count_bubble( $screen['which'] ),
			BEWELL_CAP,
			$slug,
			'bewell_render_admin_screen'
		);
	}
}
add_action( 'admin_menu', 'bewell_admin_menu' );

/**
 * The "3 new" bubble next to a menu item.
 *
 * @param string $which Submission type.
 * @return string
 */
function bewell_new_count_bubble( $which ) {
	global $wpdb;

	$table = bewell_table( $which );
	if ( ! $table ) {
		return '';
	}

	// phpcs:ignore WordPress.DB -- fixed table name from bewell_table().
	$count = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$table} WHERE status = 'new'" );

	if ( ! $count ) {
		return '';
	}

	return ' <span class="awaiting-mod"><span class="pending-count">' . (int) $count . '</span></span>';
}

/**
 * Handle status changes, deletions and exports before the screen renders.
 *
 * @return void
 */
function bewell_handle_admin_actions() {
	if ( ! isset( $_GET['page'] ) ) {
		return;
	}

	$page    = sanitize_key( wp_unslash( $_GET['page'] ) );
	$screens = bewell_admin_screens();

	if ( ! isset( $screens[ $page ] ) ) {
		return;
	}

	if ( ! current_user_can( BEWELL_CAP ) ) {
		return;
	}

	$which = $screens[ $page ]['which'];

	// --- CSV export --------------------------------------------------------
	if ( isset( $_GET['bewell_export'] ) ) {
		check_admin_referer( 'bewell_export_' . $which );
		bewell_export_csv( $which, $screens[ $page ] );
	}

	// --- Status change -----------------------------------------------------
	if ( isset( $_POST['bewell_set_status'], $_POST['bewell_id'] ) ) {
		check_admin_referer( 'bewell_admin_' . $which );

		bewell_set_submission_status(
			$which,
			(int) $_POST['bewell_id'],
			sanitize_key( wp_unslash( $_POST['bewell_set_status'] ) )
		);

		wp_safe_redirect( add_query_arg( 'bewell_msg', 'updated', wp_get_referer() ? wp_get_referer() : admin_url( 'admin.php?page=' . $page ) ) );
		exit;
	}

	// --- Delete ------------------------------------------------------------
	if ( isset( $_POST['bewell_delete'], $_POST['bewell_id'] ) ) {
		check_admin_referer( 'bewell_admin_' . $which );

		bewell_delete_submission( $which, (int) $_POST['bewell_id'] );

		wp_safe_redirect( add_query_arg( 'bewell_msg', 'deleted', wp_get_referer() ? wp_get_referer() : admin_url( 'admin.php?page=' . $page ) ) );
		exit;
	}
}
add_action( 'admin_init', 'bewell_handle_admin_actions' );

/**
 * Stream all rows of a submission type as CSV.
 *
 * @param string $which  Submission type.
 * @param array  $screen Screen definition.
 * @return never
 */
function bewell_export_csv( $which, $screen ) {
	$result = bewell_get_submissions( $which, array( 'per_page' => 100000 ) );

	$filename = sprintf( 'bewell-%s-%s.csv', $which, gmdate( 'Y-m-d' ) );

	nocache_headers();
	header( 'Content-Type: text/csv; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename=' . $filename );

	$out = fopen( 'php://output', 'w' );

	// BOM so Excel opens UTF-8 (Bengali names) correctly instead of mojibake.
	fwrite( $out, "\xEF\xBB\xBF" );

	$headers = array_merge(
		array( 'id', 'created_at', 'status' ),
		array_keys( $screen['columns'] )
	);

	// The $escape argument is passed explicitly and set to "" on purpose. PHP
	// 8.4 deprecates relying on the default, and the historic default ("\\")
	// emits backslash escapes that are not valid RFC 4180 — Excel and Sheets
	// both mangle them. Leaving it implicit also printed a deprecation notice
	// into the middle of the download, corrupting the file.
	fputcsv( $out, $headers, ',', '"', '' );

	foreach ( $result['items'] as $row ) {
		$line = array();
		foreach ( $headers as $key ) {
			$line[] = isset( $row[ $key ] ) ? $row[ $key ] : '';
		}
		fputcsv( $out, $line, ',', '"', '' );
	}

	fclose( $out );
	exit;
}

/**
 * Render whichever submission screen is active.
 *
 * @return void
 */
function bewell_render_admin_screen() {
	if ( ! current_user_can( BEWELL_CAP ) ) {
		wp_die( esc_html__( 'You do not have permission to view submissions.', 'bewell' ) );
	}

	// phpcs:disable WordPress.Security.NonceVerification.Recommended -- read-only list filters.
	$page    = isset( $_GET['page'] ) ? sanitize_key( wp_unslash( $_GET['page'] ) ) : '';
	$screens = bewell_admin_screens();

	if ( ! isset( $screens[ $page ] ) ) {
		return;
	}

	$screen = $screens[ $page ];
	$which  = $screen['which'];

	$status   = isset( $_GET['status'] ) ? sanitize_key( wp_unslash( $_GET['status'] ) ) : 'all';
	$search   = isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '';
	$paged    = isset( $_GET['paged'] ) ? max( 1, (int) $_GET['paged'] ) : 1;
	$per_page = 25;
	// phpcs:enable WordPress.Security.NonceVerification.Recommended

	$result = bewell_get_submissions(
		$which,
		array(
			'status'   => $status,
			'search'   => $search,
			'per_page' => $per_page,
			'page'     => $paged,
		)
	);

	$total_pages = (int) ceil( $result['total'] / $per_page );
	$statuses    = bewell_statuses( $which );

	?>
	<div class="wrap">
		<h1 class="wp-heading-inline"><?php echo esc_html( $screen['title'] ); ?></h1>

		<a href="<?php echo esc_url( wp_nonce_url( add_query_arg( array( 'page' => $page, 'bewell_export' => 1 ), admin_url( 'admin.php' ) ), 'bewell_export_' . $which ) ); ?>"
			class="page-title-action"><?php esc_html_e( 'Export CSV', 'bewell' ); ?></a>

		<hr class="wp-header-end">

		<?php bewell_admin_notice(); ?>

		<?php if ( 'program' === $which ) : ?>
			<div class="notice notice-info inline" style="margin:1em 0">
				<p><?php esc_html_e( 'These applications contain health information. Do not forward them by email or share them outside the team.', 'bewell' ); ?></p>
			</div>
		<?php endif; ?>

		<ul class="subsubsub">
			<?php
			$views   = array_merge( array( 'all' ), $statuses );
			$rendered = array();

			foreach ( $views as $view ) {
				$url = add_query_arg(
					array(
						'page'   => $page,
						'status' => $view,
					),
					admin_url( 'admin.php' )
				);

				$rendered[] = sprintf(
					'<a href="%s" class="%s">%s</a>',
					esc_url( $url ),
					$status === $view ? 'current' : '',
					esc_html( ucfirst( $view ) )
				);
			}

			echo '<li>' . implode( ' | </li><li>', $rendered ) . '</li>'; // phpcs:ignore WordPress.Security.EscapeOutput
			?>
		</ul>

		<form method="get" style="float:right;margin:.5em 0">
			<input type="hidden" name="page" value="<?php echo esc_attr( $page ); ?>">
			<input type="hidden" name="status" value="<?php echo esc_attr( $status ); ?>">
			<p class="search-box">
				<label class="screen-reader-text" for="bewell-search"><?php esc_html_e( 'Search submissions', 'bewell' ); ?></label>
				<input type="search" id="bewell-search" name="s" value="<?php echo esc_attr( $search ); ?>" placeholder="<?php esc_attr_e( 'Name, email or phone', 'bewell' ); ?>">
				<?php submit_button( __( 'Search', 'bewell' ), '', '', false ); ?>
			</p>
		</form>

		<table class="wp-list-table widefat fixed striped" style="margin-top:3em">
			<thead>
				<tr>
					<th style="width:11%"><?php esc_html_e( 'Received', 'bewell' ); ?></th>
					<?php foreach ( $screen['columns'] as $label ) : ?>
						<th><?php echo esc_html( $label ); ?></th>
					<?php endforeach; ?>
					<th style="width:16%"><?php esc_html_e( 'Status', 'bewell' ); ?></th>
				</tr>
			</thead>
			<tbody>
			<?php if ( empty( $result['items'] ) ) : ?>
				<tr>
					<td colspan="<?php echo (int) ( count( $screen['columns'] ) + 2 ); ?>">
						<?php esc_html_e( 'Nothing here yet.', 'bewell' ); ?>
					</td>
				</tr>
			<?php else : ?>
				<?php foreach ( $result['items'] as $row ) : ?>
					<tr>
						<td>
							<?php
							echo esc_html(
								mysql2date( get_option( 'date_format' ) . ', H:i', $row['created_at'] )
							);
							?>
						</td>

						<?php foreach ( $screen['columns'] as $key => $label ) : ?>
							<td>
								<?php
								$value = isset( $row[ $key ] ) ? $row[ $key ] : '';

								if ( 'email' === $key && $value ) {
									printf( '<a href="mailto:%1$s">%1$s</a>', esc_attr( $value ) );
								} elseif ( 'phone' === $key && $value ) {
									printf( '<a href="%1$s">%2$s</a>', esc_attr( bewell_tel( $value ) ), esc_html( $value ) );
								} elseif ( strlen( $value ) > 90 ) {
									// Long free text is collapsed so one verbose
									// applicant does not make the table unusable.
									printf(
										'<details><summary>%s…</summary><p style="white-space:pre-wrap;margin:.5em 0 0">%s</p></details>',
										esc_html( mb_substr( $value, 0, 90 ) ),
										esc_html( $value )
									);
								} else {
									echo esc_html( $value ? $value : '—' );
								}
								?>
							</td>
						<?php endforeach; ?>

						<td>
							<form method="post" style="display:flex;gap:.25em;align-items:center">
								<?php wp_nonce_field( 'bewell_admin_' . $which ); ?>
								<input type="hidden" name="bewell_id" value="<?php echo (int) $row['id']; ?>">

								<select name="bewell_set_status" onchange="this.form.submit()" style="max-width:8.5em">
									<?php foreach ( $statuses as $option ) : ?>
										<option value="<?php echo esc_attr( $option ); ?>" <?php selected( $row['status'], $option ); ?>>
											<?php echo esc_html( ucfirst( $option ) ); ?>
										</option>
									<?php endforeach; ?>
								</select>

								<button type="submit" name="bewell_delete" value="1" class="button-link delete"
									onclick="return confirm('<?php echo esc_js( __( 'Permanently delete this submission? This cannot be undone.', 'bewell' ) ); ?>')"
									aria-label="<?php esc_attr_e( 'Delete submission', 'bewell' ); ?>">
									<?php esc_html_e( 'Delete', 'bewell' ); ?>
								</button>
							</form>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
			</tbody>
		</table>

		<?php if ( $total_pages > 1 ) : ?>
			<div class="tablenav bottom">
				<div class="tablenav-pages">
					<?php
					echo paginate_links( // phpcs:ignore WordPress.Security.EscapeOutput
						array(
							'base'      => add_query_arg( 'paged', '%#%' ),
							'format'    => '',
							'current'   => $paged,
							'total'     => $total_pages,
							'prev_text' => '‹',
							'next_text' => '›',
						)
					);
					?>
				</div>
			</div>
		<?php endif; ?>

		<p class="description" style="margin-top:1em">
			<?php
			printf(
				/* translators: %d: number of submissions. */
				esc_html( _n( '%d submission.', '%d submissions.', $result['total'], 'bewell' ) ),
				(int) $result['total']
			);
			?>
		</p>
	</div>
	<?php
}

/**
 * Success notices after a status change or deletion.
 *
 * @return void
 */
function bewell_admin_notice() {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- display only.
	$msg = isset( $_GET['bewell_msg'] ) ? sanitize_key( wp_unslash( $_GET['bewell_msg'] ) ) : '';

	if ( 'updated' === $msg ) {
		printf( '<div class="notice notice-success is-dismissible"><p>%s</p></div>', esc_html__( 'Status updated.', 'bewell' ) );
	}

	if ( 'deleted' === $msg ) {
		printf( '<div class="notice notice-success is-dismissible"><p>%s</p></div>', esc_html__( 'Submission deleted.', 'bewell' ) );
	}
}

/**
 * Dashboard widget summarising what is waiting.
 *
 * @return void
 */
function bewell_dashboard_widget() {
	if ( ! current_user_can( BEWELL_CAP ) ) {
		return;
	}

	wp_add_dashboard_widget(
		'bewell_summary',
		__( 'BE WELL — waiting for you', 'bewell' ),
		'bewell_render_dashboard_widget'
	);
}
add_action( 'wp_dashboard_setup', 'bewell_dashboard_widget' );

/**
 * Render the dashboard widget.
 *
 * @return void
 */
function bewell_render_dashboard_widget() {
	global $wpdb;

	echo '<ul>';

	foreach ( bewell_admin_screens() as $slug => $screen ) {
		$table = bewell_table( $screen['which'] );

		// phpcs:ignore WordPress.DB -- fixed table name.
		$new = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$table} WHERE status = 'new'" );

		printf(
			'<li style="display:flex;justify-content:space-between;padding:.35em 0;border-bottom:1px solid #f0f0f1"><a href="%s">%s</a> <strong>%s</strong></li>',
			esc_url( admin_url( 'admin.php?page=' . $slug ) ),
			esc_html( $screen['title'] ),
			$new
				? '<span style="color:#d63638">' . (int) $new . ' ' . esc_html__( 'new', 'bewell' ) . '</span>'
				: '<span style="color:#787c82">' . esc_html__( 'clear', 'bewell' ) . '</span>'
		);
	}

	echo '</ul>';
}
