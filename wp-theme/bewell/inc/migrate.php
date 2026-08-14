<?php
/**
 * WP-CLI commands for moving the Supabase data into WordPress.
 *
 * Only loaded under WP-CLI, so none of this exists on a web request.
 *
 * Two routes, because the Supabase tables are not equally reachable:
 *
 *   - `testimonials` and `farm_products` carry a public SELECT policy, so the
 *     browser (publishable) key can read them.
 *   - `program_applications`, `job_applications` and `contact_messages`
 *     deliberately have INSERT-only policies for anon. Nothing short of a
 *     service_role key or a dashboard CSV export can read them — that was the
 *     whole point of the original design, and it is worth preserving.
 *
 * So: `wp bewell pull` handles whatever the supplied key can actually see, and
 * `wp bewell import-csv` takes the CSVs exported from the Supabase dashboard.
 *
 * @package bewell
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	return;
}

/**
 * Migrate BE WELL data out of Supabase.
 */
class Bewell_Migrate_Command {

	/**
	 * Maps a Supabase table to its WordPress destination.
	 *
	 * `column_map` renames Supabase columns to the WordPress ones where they
	 * differ — `condition` is a reserved word in enough SQL dialects that the
	 * WordPress table calls it `health_condition`.
	 *
	 * @return array
	 */
	private function schema() {
		return array(
			'program_applications' => array(
				'target'     => 'program',
				'column_map' => array( 'condition' => 'health_condition' ),
			),
			'job_applications'     => array(
				'target'     => 'job',
				'column_map' => array(),
			),
			'contact_messages'     => array(
				'target'     => 'contact',
				'column_map' => array(),
			),
		);
	}

	/**
	 * Pull data from Supabase over the REST API.
	 *
	 * ## OPTIONS
	 *
	 * --supabase-url=<supabase-url>
	 * : Supabase project URL. Not --url: that is a reserved WP-CLI global flag.
	 *
	 * --key=<key>
	 * : API key. service_role reads everything; anon reaches only public tables.
	 *
	 * [--dry-run]
	 * : Report what would be imported without writing anything.
	 *
	 * ## EXAMPLES
	 *
	 *     wp bewell pull --supabase-url=https://abcd.supabase.co --key=eyJ... --dry-run
	 *
	 * @param array $args       Positional args.
	 * @param array $assoc_args Flags.
	 * @return void
	 */
	public function pull( $args, $assoc_args ) {
		// Checked explicitly rather than trusted to the synopsis: a mangled
		// docblock silently drops arguments, and the failure then looks like
		// "Supabase returned nothing" instead of "you passed no URL".
		if ( empty( $assoc_args['supabase-url'] ) || empty( $assoc_args['key'] ) ) {
			WP_CLI::error( 'Both --supabase-url and --key are required.' );
		}

		$url = untrailingslashit( $assoc_args['supabase-url'] );
		$key = $assoc_args['key'];
		$dry = isset( $assoc_args['dry-run'] );

		if ( ! wp_http_validate_url( $url ) ) {
			WP_CLI::error( sprintf( 'Not a usable URL: %s', $url ) );
		}

		if ( $dry ) {
			WP_CLI::log( 'Dry run — nothing will be written.' );
		}

		$totals = array();

		// Content first: testimonials and products become posts.
		$totals['testimonials']  = $this->pull_testimonials( $url, $key, $dry );
		$totals['farm_products'] = $this->pull_products( $url, $key, $dry );

		// Then the submission tables.
		foreach ( $this->schema() as $table => $config ) {
			$rows = $this->fetch( $url, $key, $table );

			if ( is_wp_error( $rows ) ) {
				WP_CLI::warning( sprintf( '%s: %s', $table, $rows->get_error_message() ) );
				$totals[ $table ] = 0;
				continue;
			}

			if ( ! $rows ) {
				WP_CLI::log( sprintf( '%s: nothing readable with this key.', $table ) );
				$totals[ $table ] = 0;
				continue;
			}

			$totals[ $table ] = $this->import_rows( $config['target'], $rows, $config['column_map'], $dry );
			WP_CLI::log( sprintf( '%s: imported %d.', $table, $totals[ $table ] ) );
		}

		WP_CLI::success( sprintf( 'Done. %d records total.', array_sum( $totals ) ) );

		if ( ! array_sum( $totals ) ) {
			WP_CLI::log( '' );
			WP_CLI::log( 'Nothing came across. If you used the publishable (sb_publishable_/anon) key' );
			WP_CLI::log( 'that is expected for the three application tables — they have no SELECT' );
			WP_CLI::log( 'policy for anon. Re-run with the service_role key, or export CSVs from the' );
			WP_CLI::log( 'Supabase dashboard and use: wp bewell import-csv' );
		}
	}

	/**
	 * Import a CSV exported from the Supabase dashboard.
	 *
	 * ## OPTIONS
	 *
	 * --type=<type>
	 * : One of program, job, contact, training, testimonials, products.
	 *
	 * --file=<file>
	 * : Path to the CSV file.
	 *
	 * [--dry-run]
	 * : Parse and report without writing.
	 *
	 * ## EXAMPLES
	 *
	 *     wp bewell import-csv --type=program --file=program_applications_rows.csv
	 *
	 * @subcommand import-csv
	 *
	 * @param array $args       Positional args.
	 * @param array $assoc_args Flags.
	 * @return void
	 */
	public function import_csv( $args, $assoc_args ) {
		$allowed = array( 'program', 'job', 'contact', 'training', 'testimonials', 'products' );

		if ( empty( $assoc_args['type'] ) || empty( $assoc_args['file'] ) ) {
			WP_CLI::error( 'Both --type and --file are required.' );
		}

		$type = $assoc_args['type'];
		$file = $assoc_args['file'];
		$dry  = isset( $assoc_args['dry-run'] );

		if ( ! in_array( $type, $allowed, true ) ) {
			WP_CLI::error( sprintf( 'Unknown --type "%s". Expected one of: %s', $type, implode( ', ', $allowed ) ) );
		}

		if ( ! file_exists( $file ) || ! is_readable( $file ) ) {
			WP_CLI::error( sprintf( 'Cannot read %s', $file ) );
		}

		$handle = fopen( $file, 'r' );
		if ( ! $handle ) {
			WP_CLI::error( 'Could not open the file.' );
		}

		$header = fgetcsv( $handle, 0, ',', '"', '' );
		if ( ! $header ) {
			WP_CLI::error( 'The file appears to be empty.' );
		}

		// Strip a UTF-8 BOM from the first heading or the column name will not
		// match and every row silently loses its first field.
		$header[0] = preg_replace( '/^\xEF\xBB\xBF/', '', $header[0] );

		$rows = array();
		while ( ( $line = fgetcsv( $handle, 0, ',', '"', '' ) ) !== false ) {
			if ( count( $line ) !== count( $header ) ) {
				continue;
			}
			$rows[] = array_combine( $header, $line );
		}
		fclose( $handle );

		WP_CLI::log( sprintf( 'Parsed %d rows from %s.', count( $rows ), basename( $file ) ) );

		if ( 'testimonials' === $type ) {
			$count = $this->import_testimonials( $rows, $dry );
		} elseif ( 'products' === $type ) {
			$count = $this->import_products( $rows, $dry );
		} else {
			$map   = 'program' === $type ? array( 'condition' => 'health_condition' ) : array();
			$count = $this->import_rows( $type, $rows, $map, $dry );
		}

		WP_CLI::success( sprintf( '%d records imported.', $count ) );
	}

	/**
	 * Whether a post of the given type already has this exact title.
	 *
	 * Replaces get_page_by_title(), deprecated in WordPress 6.2 — calling it
	 * would print a deprecation notice on every row of the import.
	 *
	 * @param string $title     Post title.
	 * @param string $post_type Post type.
	 * @return bool
	 */
	private function title_exists( $title, $post_type ) {
		$found = get_posts(
			array(
				'post_type'              => $post_type,
				'post_status'            => 'any',
				'title'                  => $title,
				'posts_per_page'         => 1,
				'fields'                 => 'ids',
				'no_found_rows'          => true,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
			)
		);

		return ! empty( $found );
	}

	/**
	 * GET rows from a Supabase table.
	 *
	 * @param string $url   Project URL.
	 * @param string $key   API key.
	 * @param string $table Table name.
	 * @return array|WP_Error
	 */
	private function fetch( $url, $key, $table ) {
		$response = wp_remote_get(
			sprintf( '%s/rest/v1/%s?select=*', $url, rawurlencode( $table ) ),
			array(
				'timeout' => 30,
				'headers' => array(
					'apikey'        => $key,
					'Authorization' => 'Bearer ' . $key,
					'Accept'        => 'application/json',
				),
			)
		);

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$code = wp_remote_retrieve_response_code( $response );
		$body = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( 200 !== $code ) {
			return new WP_Error(
				'bewell_supabase_http',
				sprintf( 'HTTP %d — %s', $code, is_array( $body ) && isset( $body['message'] ) ? $body['message'] : 'unexpected response' )
			);
		}

		return is_array( $body ) ? $body : array();
	}

	/**
	 * Insert submission rows, skipping ones already present.
	 *
	 * @param string $target Submission type.
	 * @param array  $rows   Rows from Supabase.
	 * @param array  $map    Supabase column => WordPress column.
	 * @param bool   $dry    Dry run.
	 * @return int Number imported.
	 */
	private function import_rows( $target, $rows, $map, $dry ) {
		global $wpdb;

		$table = bewell_table( $target );
		$count = 0;

		foreach ( $rows as $row ) {
			$data = array();

			foreach ( $row as $column => $value ) {
				$column = isset( $map[ $column ] ) ? $map[ $column ] : $column;
				$data[ $column ] = is_scalar( $value ) ? (string) $value : wp_json_encode( $value );
			}

			// Re-running the migration must not double up. Matching on the
			// original timestamp plus email is enough: Supabase stamped
			// created_at server-side, so it is stable across runs.
			$created = isset( $data['created_at'] ) ? gmdate( 'Y-m-d H:i:s', strtotime( $data['created_at'] ) ) : current_time( 'mysql' );

			// phpcs:ignore WordPress.DB -- fixed table name from bewell_table().
			$exists = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT id FROM {$table} WHERE created_at = %s AND name = %s LIMIT 1",
					$created,
					isset( $data['name'] ) ? $data['name'] : ''
				)
			);

			if ( $exists ) {
				continue;
			}

			if ( $dry ) {
				++$count;
				continue;
			}

			$insert = array(
				'status'     => isset( $data['status'] ) && $data['status'] ? $data['status'] : 'new',
				'created_at' => $created,
				'source_ip'  => '',
			);

			$columns = array(
				'program'  => array( 'name', 'email', 'phone', 'health_condition', 'program_length', 'message' ),
				'job'      => array( 'name', 'email', 'phone', 'position', 'experience', 'motivation' ),
				'contact'  => array( 'name', 'email', 'phone', 'subject', 'message' ),
				'training' => array( 'name', 'email', 'phone', 'background', 'message' ),
			);

			foreach ( $columns[ $target ] as $column ) {
				$insert[ $column ] = isset( $data[ $column ] ) ? $data[ $column ] : '';
			}

			if ( false !== $wpdb->insert( $table, $insert ) ) { // phpcs:ignore WordPress.DB.DirectDatabaseQuery
				++$count;
			}
		}

		return $count;
	}

	/**
	 * Pull testimonials from Supabase.
	 *
	 * @param string $url Project URL.
	 * @param string $key API key.
	 * @param bool   $dry Dry run.
	 * @return int
	 */
	private function pull_testimonials( $url, $key, $dry ) {
		$rows = $this->fetch( $url, $key, 'testimonials' );

		if ( is_wp_error( $rows ) ) {
			WP_CLI::warning( 'testimonials: ' . $rows->get_error_message() );
			return 0;
		}

		$count = $this->import_testimonials( $rows, $dry );
		WP_CLI::log( sprintf( 'testimonials: imported %d.', $count ) );

		return $count;
	}

	/**
	 * Create testimonial posts.
	 *
	 * @param array $rows Rows.
	 * @param bool  $dry  Dry run.
	 * @return int
	 */
	private function import_testimonials( $rows, $dry ) {
		$count = 0;

		foreach ( $rows as $row ) {
			$name = isset( $row['name'] ) ? $row['name'] : '';

			if ( ! $name || $this->title_exists( $name, 'bewell_testimonial' ) ) {
				continue;
			}

			if ( $dry ) {
				++$count;
				continue;
			}

			$id = wp_insert_post(
				array(
					'post_type'    => 'bewell_testimonial',
					'post_title'   => $name,
					'post_content' => isset( $row['content'] ) ? $row['content'] : '',
					'post_status'  => 'publish',
					'post_date'    => isset( $row['created_at'] ) ? gmdate( 'Y-m-d H:i:s', strtotime( $row['created_at'] ) ) : current_time( 'mysql' ),
				)
			);

			if ( is_wp_error( $id ) || ! $id ) {
				continue;
			}

			update_post_meta( $id, '_bewell_role', isset( $row['role'] ) ? $row['role'] : '' );
			update_post_meta( $id, '_bewell_program_type', isset( $row['program_type'] ) ? $row['program_type'] : 'general' );
			update_post_meta( $id, '_bewell_is_featured', ! empty( $row['is_featured'] ) && 'false' !== $row['is_featured'] ? '1' : '' );

			++$count;
		}

		return $count;
	}

	/**
	 * Pull farm products from Supabase.
	 *
	 * @param string $url Project URL.
	 * @param string $key API key.
	 * @param bool   $dry Dry run.
	 * @return int
	 */
	private function pull_products( $url, $key, $dry ) {
		$rows = $this->fetch( $url, $key, 'farm_products' );

		if ( is_wp_error( $rows ) ) {
			WP_CLI::warning( 'farm_products: ' . $rows->get_error_message() );
			return 0;
		}

		$count = $this->import_products( $rows, $dry );
		WP_CLI::log( sprintf( 'farm_products: imported %d.', $count ) );

		return $count;
	}

	/**
	 * Create farm product posts.
	 *
	 * @param array $rows Rows.
	 * @param bool  $dry  Dry run.
	 * @return int
	 */
	private function import_products( $rows, $dry ) {
		$count = 0;

		foreach ( $rows as $row ) {
			$name = isset( $row['name'] ) ? $row['name'] : '';

			if ( ! $name || $this->title_exists( $name, 'bewell_product' ) ) {
				continue;
			}

			if ( $dry ) {
				++$count;
				continue;
			}

			$id = wp_insert_post(
				array(
					'post_type'    => 'bewell_product',
					'post_title'   => $name,
					'post_content' => isset( $row['description'] ) ? $row['description'] : '',
					'post_status'  => 'publish',
				)
			);

			if ( is_wp_error( $id ) || ! $id ) {
				continue;
			}

			update_post_meta( $id, '_bewell_category', isset( $row['category'] ) ? $row['category'] : 'general' );
			update_post_meta( $id, '_bewell_price', isset( $row['price'] ) ? $row['price'] : '' );
			update_post_meta( $id, '_bewell_unit', isset( $row['unit'] ) ? $row['unit'] : '' );
			update_post_meta( $id, '_bewell_is_available', ! empty( $row['is_available'] ) && 'false' !== $row['is_available'] ? '1' : '' );

			++$count;
		}

		return $count;
	}
}

WP_CLI::add_command( 'bewell', 'Bewell_Migrate_Command' );
