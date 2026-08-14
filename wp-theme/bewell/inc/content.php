<?php
/**
 * Editable content types: testimonials and farm products.
 *
 * These two were Supabase tables the site read from but nobody could edit
 * without the dashboard. They are genuine content — Eugene should be able to
 * add a testimonial the way he adds a page — so unlike submissions they are
 * custom post types.
 *
 * @package bewell
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the post types.
 *
 * @return void
 */
function bewell_register_post_types() {
	register_post_type(
		'bewell_testimonial',
		array(
			'labels'          => array(
				'name'               => __( 'Testimonials', 'bewell' ),
				'singular_name'      => __( 'Testimonial', 'bewell' ),
				'add_new_item'       => __( 'Add Testimonial', 'bewell' ),
				'edit_item'          => __( 'Edit Testimonial', 'bewell' ),
				'search_items'       => __( 'Search testimonials', 'bewell' ),
				'not_found'          => __( 'No testimonials yet.', 'bewell' ),
			),
			'public'          => false,
			'show_ui'         => true,
			'show_in_menu'    => true,
			'menu_icon'       => 'dashicons-format-quote',
			'menu_position'   => 21,
			'supports'        => array( 'title', 'editor', 'page-attributes' ),
			'capability_type' => 'post',
			// Not public: a testimonial has no page of its own, it is rendered
			// inside the home and programme pages.
			'has_archive'     => false,
			'rewrite'         => false,
			'show_in_rest'    => false,
		)
	);

	register_post_type(
		'bewell_product',
		array(
			'labels'          => array(
				'name'          => __( 'Farm Products', 'bewell' ),
				'singular_name' => __( 'Farm Product', 'bewell' ),
				'add_new_item'  => __( 'Add Product', 'bewell' ),
				'edit_item'     => __( 'Edit Product', 'bewell' ),
				'search_items'  => __( 'Search products', 'bewell' ),
				'not_found'     => __( 'No products yet.', 'bewell' ),
			),
			'public'          => false,
			'show_ui'         => true,
			'show_in_menu'    => true,
			'menu_icon'       => 'dashicons-carrot',
			'menu_position'   => 22,
			'supports'        => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
			'capability_type' => 'post',
			'has_archive'     => false,
			'rewrite'         => false,
			'show_in_rest'    => false,
		)
	);
}
add_action( 'init', 'bewell_register_post_types' );

/**
 * Meta box definitions, keyed by post type.
 *
 * @return array
 */
function bewell_meta_fields() {
	return array(
		'bewell_testimonial' => array(
			'role'         => array(
				'label' => __( 'Role or condition treated', 'bewell' ),
				'type'  => 'text',
				'help'  => __( 'Shown under the name, e.g. "Recovered from diabetes".', 'bewell' ),
			),
			'program_type' => array(
				'label'   => __( 'Programme', 'bewell' ),
				'type'    => 'select',
				'options' => array(
					'general'   => __( 'General', 'bewell' ),
					'lifestyle' => __( 'Lifestyle Program', 'bewell' ),
					'training'  => __( 'Training Program', 'bewell' ),
					'hostel'    => __( 'Hostel', 'bewell' ),
				),
			),
			'is_featured'  => array(
				'label' => __( 'Feature on the home page', 'bewell' ),
				'type'  => 'checkbox',
				'help'  => __( 'The home page shows the three most recent featured testimonials.', 'bewell' ),
			),
		),
		'bewell_product'     => array(
			'category'     => array(
				'label'   => __( 'Category', 'bewell' ),
				'type'    => 'select',
				'options' => array(
					'fruits'     => __( 'Fruits', 'bewell' ),
					'vegetables' => __( 'Vegetables', 'bewell' ),
					'grains'     => __( 'Grains', 'bewell' ),
					'herbs'      => __( 'Herbs', 'bewell' ),
					'general'    => __( 'Other', 'bewell' ),
				),
			),
			'price'        => array(
				'label' => __( 'Price', 'bewell' ),
				'type'  => 'text',
				'help'  => __( 'Free text, e.g. "120 BDT".', 'bewell' ),
			),
			'unit'         => array(
				'label' => __( 'Unit', 'bewell' ),
				'type'  => 'text',
				'help'  => __( 'e.g. kg, bunch, piece.', 'bewell' ),
			),
			'is_available' => array(
				'label' => __( 'Currently available', 'bewell' ),
				'type'  => 'checkbox',
			),
		),
	);
}

/**
 * Add the meta boxes.
 *
 * @return void
 */
function bewell_add_meta_boxes() {
	foreach ( bewell_meta_fields() as $post_type => $fields ) {
		add_meta_box(
			'bewell_details',
			__( 'Details', 'bewell' ),
			'bewell_render_meta_box',
			$post_type,
			'side',
			'high'
		);
	}
}
add_action( 'add_meta_boxes', 'bewell_add_meta_boxes' );

/**
 * Render the meta box.
 *
 * @param WP_Post $post Current post.
 * @return void
 */
function bewell_render_meta_box( $post ) {
	$all = bewell_meta_fields();

	if ( ! isset( $all[ $post->post_type ] ) ) {
		return;
	}

	wp_nonce_field( 'bewell_save_meta_' . $post->ID, 'bewell_meta_nonce' );

	foreach ( $all[ $post->post_type ] as $key => $field ) {
		$value = get_post_meta( $post->ID, '_bewell_' . $key, true );
		$id    = 'bewell_' . $key;

		echo '<p style="margin-bottom:1em">';

		if ( 'checkbox' === $field['type'] ) {
			printf(
				'<label for="%1$s"><input type="checkbox" id="%1$s" name="%1$s" value="1" %2$s> %3$s</label>',
				esc_attr( $id ),
				checked( $value, '1', false ),
				esc_html( $field['label'] )
			);
		} else {
			printf(
				'<label for="%1$s" style="display:block;font-weight:600;margin-bottom:.25em">%2$s</label>',
				esc_attr( $id ),
				esc_html( $field['label'] )
			);

			if ( 'select' === $field['type'] ) {
				printf( '<select id="%1$s" name="%1$s" style="width:100%%">', esc_attr( $id ) );
				foreach ( $field['options'] as $option => $label ) {
					printf(
						'<option value="%s" %s>%s</option>',
						esc_attr( $option ),
						selected( $value, $option, false ),
						esc_html( $label )
					);
				}
				echo '</select>';
			} else {
				printf(
					'<input type="text" id="%1$s" name="%1$s" value="%2$s" style="width:100%%">',
					esc_attr( $id ),
					esc_attr( $value )
				);
			}
		}

		if ( ! empty( $field['help'] ) ) {
			printf( '<span class="description" style="display:block;margin-top:.25em">%s</span>', esc_html( $field['help'] ) );
		}

		echo '</p>';
	}
}

/**
 * Persist the meta box values.
 *
 * @param int $post_id Post ID.
 * @return void
 */
function bewell_save_meta( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	$nonce = isset( $_POST['bewell_meta_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['bewell_meta_nonce'] ) ) : '';
	if ( ! wp_verify_nonce( $nonce, 'bewell_save_meta_' . $post_id ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$all       = bewell_meta_fields();
	$post_type = get_post_type( $post_id );

	if ( ! isset( $all[ $post_type ] ) ) {
		return;
	}

	foreach ( $all[ $post_type ] as $key => $field ) {
		$name = 'bewell_' . $key;
		$meta = '_bewell_' . $key;

		if ( 'checkbox' === $field['type'] ) {
			update_post_meta( $post_id, $meta, isset( $_POST[ $name ] ) ? '1' : '' );
			continue;
		}

		$raw = isset( $_POST[ $name ] ) ? sanitize_text_field( wp_unslash( $_POST[ $name ] ) ) : '';

		// A select must only ever store one of its own options.
		if ( 'select' === $field['type'] && ! isset( $field['options'][ $raw ] ) ) {
			$raw = (string) array_key_first( $field['options'] );
		}

		update_post_meta( $post_id, $meta, $raw );
	}
}
add_action( 'save_post', 'bewell_save_meta' );

/**
 * Featured testimonials for the home page.
 *
 * @param int $limit Maximum to return.
 * @return WP_Post[]
 */
function bewell_get_featured_testimonials( $limit = 3 ) {
	return get_posts(
		array(
			'post_type'      => 'bewell_testimonial',
			'post_status'    => 'publish',
			'posts_per_page' => $limit,
			'orderby'        => 'date',
			'order'          => 'DESC',
			'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery
				array(
					'key'   => '_bewell_is_featured',
					'value' => '1',
				),
			),
		)
	);
}

/**
 * Testimonials for a given programme, used on the programme pages.
 *
 * @param string $program One of: lifestyle, training, hostel, general.
 * @param int    $limit   Maximum to return.
 * @return WP_Post[]
 */
function bewell_get_testimonials( $program = '', $limit = 3 ) {
	$args = array(
		'post_type'      => 'bewell_testimonial',
		'post_status'    => 'publish',
		'posts_per_page' => $limit,
		'orderby'        => 'date',
		'order'          => 'DESC',
	);

	if ( $program ) {
		$args['meta_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery
			array(
				'key'   => '_bewell_program_type',
				'value' => $program,
			),
		);
	}

	return get_posts( $args );
}

/**
 * Available farm products, newest first.
 *
 * @param int $limit Maximum to return.
 * @return WP_Post[]
 */
function bewell_get_products( $limit = 50 ) {
	return get_posts(
		array(
			'post_type'      => 'bewell_product',
			'post_status'    => 'publish',
			'posts_per_page' => $limit,
			'orderby'        => array(
				'menu_order' => 'ASC',
				'date'       => 'DESC',
			),
		)
	);
}

/**
 * Extra columns on the testimonial and product list screens.
 *
 * @param array $columns Existing columns.
 * @return array
 */
function bewell_testimonial_columns( $columns ) {
	$date = $columns['date'] ?? '';
	unset( $columns['date'] );

	$columns['bewell_program']  = __( 'Programme', 'bewell' );
	$columns['bewell_featured'] = __( 'Featured', 'bewell' );
	$columns['date']            = $date;

	return $columns;
}
add_filter( 'manage_bewell_testimonial_posts_columns', 'bewell_testimonial_columns' );

/**
 * Render the extra testimonial columns.
 *
 * @param string $column  Column key.
 * @param int    $post_id Post ID.
 * @return void
 */
function bewell_testimonial_column_content( $column, $post_id ) {
	if ( 'bewell_program' === $column ) {
		$fields  = bewell_meta_fields();
		$value   = get_post_meta( $post_id, '_bewell_program_type', true );
		$options = $fields['bewell_testimonial']['program_type']['options'];

		echo esc_html( $options[ $value ] ?? '—' );
	}

	if ( 'bewell_featured' === $column ) {
		echo get_post_meta( $post_id, '_bewell_is_featured', true ) ? '★' : '—';
	}
}
add_action( 'manage_bewell_testimonial_posts_custom_column', 'bewell_testimonial_column_content', 10, 2 );
