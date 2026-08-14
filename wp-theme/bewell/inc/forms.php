<?php
/**
 * Front-end form handling for the contact, lifestyle-programme and job forms.
 *
 * Design rule inherited from the site this replaces: a form must never tell a
 * visitor their application was received unless a row was actually written. The
 * original build showed "Application Received!" and threw the data away; every
 * path here either persists the submission or reports a failure.
 *
 * @package bewell
 */

defined( 'ABSPATH' ) || exit;

/**
 * The input `name` attribute for a form field.
 *
 * Every field is namespaced, and it is not cosmetic. WP::parse_request() reads
 * $_POST for each public query var, so a plain <input name="name"> is taken as
 * the post-slug query var: WordPress looks for a post slugged with whatever the
 * visitor typed, finds nothing, and serves a 404 instead of the page — the form
 * silently stops working. `name`, `title`, `s`, `page`, `author`, `order` and a
 * few dozen others are all live query vars. Prefixing sidesteps the whole class.
 *
 * @param string $field Internal field key, matching the database column.
 * @return string
 */
function bewell_field_name( $field ) {
	return 'bw_' . $field;
}

/**
 * Per-request state for the form being rendered: errors and submitted values.
 *
 * @return array
 */
function &bewell_form_state() {
	static $state = array(
		'errors' => array(),
		'values' => array(),
		'form'   => '',
	);

	return $state;
}

/**
 * Validation errors for the current request.
 *
 * @param string $form Form key.
 * @return array<string, string>
 */
function bewell_form_errors( $form ) {
	$state = &bewell_form_state();

	return $state['form'] === $form ? $state['errors'] : array();
}

/**
 * A previously submitted value, for repopulating a failed form.
 *
 * @param string $form  Form key.
 * @param string $field Field name.
 * @param string $default Fallback.
 * @return string
 */
function bewell_form_value( $form, $field, $default = '' ) {
	$state = &bewell_form_state();

	if ( $state['form'] !== $form || ! isset( $state['values'][ $field ] ) ) {
		return $default;
	}

	return $state['values'][ $field ];
}

/**
 * Whether the given form was just submitted successfully.
 *
 * Read from the redirect query string, so a refresh after success does not
 * re-post the form.
 *
 * @param string $form Form key.
 * @return bool
 */
function bewell_form_succeeded( $form ) {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only display flag.
	return isset( $_GET['bw_sent'] ) && sanitize_key( wp_unslash( $_GET['bw_sent'] ) ) === $form;
}

/**
 * Field definitions per form: which are required, and how each is sanitized.
 *
 * @return array
 */
function bewell_form_schema() {
	return array(
		'contact' => array(
			'table'    => 'contact',
			'label'    => __( 'message', 'bewell' ),
			'fields'   => array(
				'name'    => array( 'required' => true,  'sanitize' => 'text',     'label' => 'Your Name' ),
				'email'   => array( 'required' => true,  'sanitize' => 'email',    'label' => 'Email Address' ),
				'phone'   => array( 'required' => false, 'sanitize' => 'text',     'label' => 'Phone Number' ),
				'subject' => array( 'required' => true,  'sanitize' => 'text',     'label' => 'Subject' ),
				'message' => array( 'required' => true,  'sanitize' => 'textarea', 'label' => 'Your Message' ),
			),
		),
		'program' => array(
			'table'  => 'program',
			'label'  => __( 'application', 'bewell' ),
			'fields' => array(
				'name'             => array( 'required' => true,  'sanitize' => 'text',     'label' => 'Full Name' ),
				'phone'            => array( 'required' => true,  'sanitize' => 'text',     'label' => 'Phone Number' ),
				'email'            => array( 'required' => false, 'sanitize' => 'email',    'label' => 'Email Address' ),
				'health_condition' => array( 'required' => true,  'sanitize' => 'textarea', 'label' => 'Primary Health Concern' ),
				'program_length'   => array( 'required' => false, 'sanitize' => 'text',     'label' => 'Preferred Length' ),
				'message'          => array( 'required' => false, 'sanitize' => 'textarea', 'label' => 'Tell Us About Yourself' ),
			),
		),
		'training' => array(
			'table'  => 'training',
			'label'  => __( 'application', 'bewell' ),
			'fields' => array(
				'name'       => array( 'required' => true,  'sanitize' => 'text',     'label' => 'Full Name' ),
				'phone'      => array( 'required' => true,  'sanitize' => 'text',     'label' => 'Phone Number' ),
				'email'      => array( 'required' => true,  'sanitize' => 'email',    'label' => 'Email Address' ),
				'background' => array( 'required' => false, 'sanitize' => 'text',     'label' => 'Professional Background' ),
				'message'    => array( 'required' => false, 'sanitize' => 'textarea', 'label' => 'Why attend' ),
			),
		),
		'job'     => array(
			'table'  => 'job',
			'label'  => __( 'application', 'bewell' ),
			'fields' => array(
				'name'       => array( 'required' => true,  'sanitize' => 'text',     'label' => 'Full Name' ),
				'email'      => array( 'required' => true,  'sanitize' => 'email',    'label' => 'Email Address' ),
				'phone'      => array( 'required' => true,  'sanitize' => 'text',     'label' => 'Phone Number' ),
				'position'   => array( 'required' => false, 'sanitize' => 'text',     'label' => 'Position' ),
				'experience' => array( 'required' => false, 'sanitize' => 'textarea', 'label' => 'Relevant Experience' ),
				'motivation' => array( 'required' => false, 'sanitize' => 'textarea', 'label' => 'Why BE WELL?' ),
			),
		),
	);
}

/**
 * Keep page caches away from any page that renders a form.
 *
 * Every form carries a nonce. For logged-out visitors WordPress issues one
 * shared nonce that rolls every 12 hours and stops verifying at 24. A full-page
 * cache will happily serve the same HTML for days — Hostinger ships LiteSpeed
 * with a long public TTL — so once a cached copy outlives its nonce, every
 * visitor gets a token that cannot verify and every submission is rejected.
 * Reloading does not help, because the reload is served from the same cache.
 * The result is all four forms failing silently for real people while the site
 * looks perfectly healthy.
 *
 * DONOTCACHEPAGE is honoured by LiteSpeed, WP Rocket, W3 Total Cache and WP
 * Super Cache; the litespeed_control_set_nocache action is the explicit API for
 * the plugin actually installed here. Both are set so this survives a change of
 * caching plugin.
 *
 * @return void
 */
function bewell_prevent_form_page_caching() {
	if ( ! is_page() ) {
		return;
	}

	$has_form = false;
	foreach ( array( 'contact', 'lifestyle', 'training', 'work' ) as $key ) {
		if ( bewell_is_current( $key ) ) {
			$has_form = true;
			break;
		}
	}

	if ( ! $has_form ) {
		return;
	}

	if ( ! defined( 'DONOTCACHEPAGE' ) ) {
		define( 'DONOTCACHEPAGE', true );
	}

	do_action( 'litespeed_control_set_nocache', 'BE WELL: form nonce must be freshly generated' );

	nocache_headers();
}
add_action( 'template_redirect', 'bewell_prevent_form_page_caching', 5 );

/**
 * Handle a submission before any output is sent.
 *
 * @return void
 */
function bewell_handle_form_submission() {
	// phpcs:ignore WordPress.Security.NonceVerification.Missing -- nonce checked below.
	if ( empty( $_POST['bewell_form'] ) ) {
		return;
	}

	$form   = sanitize_key( wp_unslash( $_POST['bewell_form'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
	$schema = bewell_form_schema();

	if ( ! isset( $schema[ $form ] ) ) {
		return;
	}

	$state          = &bewell_form_state();
	$state['form']  = $form;
	$definition     = $schema[ $form ];

	// --- Nonce -------------------------------------------------------------
	$nonce = isset( $_POST['bewell_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['bewell_nonce'] ) ) : '';

	if ( ! wp_verify_nonce( $nonce, 'bewell_form_' . $form ) ) {
		// Almost always an expired page rather than an attack, so the wording
		// tells the visitor what to do instead of accusing them of something.
		$state['errors']['_form'] = __( 'This page was open for a while and the security token expired. Please reload the page and send it again.', 'bewell' );
		return;
	}

	// --- Honeypot ----------------------------------------------------------
	// Bots fill every field they find. A human never sees this one. Silently
	// treated as success so the bot does not learn to skip it.
	$honeypot = isset( $_POST['website'] ) ? trim( (string) wp_unslash( $_POST['website'] ) ) : '';
	if ( '' !== $honeypot ) {
		bewell_redirect_after_success( $form );
	}

	// --- Rate limit --------------------------------------------------------
	if ( ! bewell_check_rate_limit( $form ) ) {
		$state['errors']['_form'] = __( 'You have sent several messages in a short time. Please wait a few minutes and try again.', 'bewell' );
		return;
	}

	// --- Collect and validate ---------------------------------------------
	$values = array();
	$errors = array();

	foreach ( $definition['fields'] as $field => $rules ) {
		$key = bewell_field_name( $field );
		$raw = isset( $_POST[ $key ] ) ? wp_unslash( $_POST[ $key ] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput

		switch ( $rules['sanitize'] ) {
			case 'email':
				$value = sanitize_email( $raw );
				break;
			case 'textarea':
				$value = sanitize_textarea_field( $raw );
				break;
			default:
				$value = sanitize_text_field( $raw );
		}

		$value = trim( $value );

		if ( $rules['required'] && '' === $value ) {
			/* translators: %s: field label. */
			$errors[ $field ] = sprintf( __( '%s is required.', 'bewell' ), $rules['label'] );
		}

		// An email that fails sanitize_email comes back empty; distinguish a
		// malformed address from a missing one so the message is actionable.
		if ( 'email' === $rules['sanitize'] && '' === $value && '' !== trim( (string) $raw ) ) {
			$errors[ $field ] = __( 'That email address does not look right.', 'bewell' );
		}

		$values[ $field ] = $value;
	}

	$state['values'] = $values;

	if ( $errors ) {
		$state['errors'] = $errors;
		return;
	}

	// --- Persist -----------------------------------------------------------
	$inserted = bewell_insert_submission( $definition['table'], $values );

	if ( is_wp_error( $inserted ) ) {
		// Log the underlying DB error for whoever debugs this later, but never
		// show it to the visitor.
		$data = $inserted->get_error_data();
		error_log( // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
			sprintf(
				'[bewell] Failed to save %s submission: %s',
				$form,
				isset( $data['db_error'] ) ? $data['db_error'] : $inserted->get_error_message()
			)
		);

		$state['errors']['_form'] = sprintf(
			/* translators: %s: phone number. */
			__( 'Something went wrong on our end and your %1$s was not saved. Please try again, or call us on %2$s.', 'bewell' ),
			$definition['label'],
			bewell_contact( 'phone' )
		);
		return;
	}

	bewell_notify_staff( $form, $inserted, $values );
	bewell_redirect_after_success( $form );
}
add_action( 'template_redirect', 'bewell_handle_form_submission' );

/**
 * Post/Redirect/Get so a browser refresh cannot resubmit.
 *
 * @param string $form Form key.
 * @return void
 */
function bewell_redirect_after_success( $form ) {
	$url = add_query_arg( 'bw_sent', $form, remove_query_arg( array( 'bw_sent' ) ) );

	wp_safe_redirect( $url . '#bewell-form' );
	exit;
}

/**
 * Crude per-IP rate limit: at most 5 submissions of a given form per 10 minutes.
 *
 * Transient-backed, so it survives across requests without a table and expires
 * on its own. Not a defence against a determined attacker — the honeypot and
 * nonce do more — but it stops a stuck retry loop from filling the table.
 *
 * @param string $form Form key.
 * @return bool True when the submission may proceed.
 */
function bewell_check_rate_limit( $form ) {
	$ip = bewell_client_ip();

	if ( ! $ip ) {
		return true;
	}

	$limit  = (int) apply_filters( 'bewell_form_rate_limit', 5, $form );
	$window = (int) apply_filters( 'bewell_form_rate_window', 10 * MINUTE_IN_SECONDS, $form );

	$key   = 'bewell_rl_' . $form . '_' . md5( $ip );
	$count = (int) get_transient( $key );

	if ( $count >= $limit ) {
		return false;
	}

	set_transient( $key, $count + 1, $window );

	return true;
}

/**
 * Email whoever handles submissions that a new one has arrived.
 *
 * Deliberately does not include the free-text health details in the body: the
 * notification travels over ordinary email, and the point of moving this data
 * into WordPress was to keep it behind a login. The email is a pointer.
 *
 * @param string $form   Form key.
 * @param int    $id     Row ID.
 * @param array  $values Submitted values.
 * @return void
 */
function bewell_notify_staff( $form, $id, $values ) {
	$to = get_theme_mod( 'bewell_notify_email' );
	$to = $to ? $to : get_option( 'admin_email' );

	if ( ! $to || ! apply_filters( 'bewell_send_notifications', true, $form ) ) {
		return;
	}

	$screens = array(
		'contact'  => 'bewell-contact-messages',
		'program'  => 'bewell-program-applications',
		'training' => 'bewell-training-applications',
		'job'      => 'bewell-job-applications',
	);

	$titles = array(
		'contact'  => __( 'New contact message', 'bewell' ),
		'program'  => __( 'New Lifestyle Program application', 'bewell' ),
		'training' => __( 'New Training Program application', 'bewell' ),
		'job'      => __( 'New job application', 'bewell' ),
	);

	$link = admin_url( 'admin.php?page=' . $screens[ $form ] );

	$subject = sprintf(
		'[%s] %s',
		wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ),
		$titles[ $form ]
	);

	$body = array(
		$titles[ $form ] . '.',
		'',
		sprintf( __( 'From: %s', 'bewell' ), $values['name'] ),
	);

	if ( ! empty( $values['phone'] ) ) {
		$body[] = sprintf( __( 'Phone: %s', 'bewell' ), $values['phone'] );
	}
	if ( ! empty( $values['email'] ) ) {
		$body[] = sprintf( __( 'Email: %s', 'bewell' ), $values['email'] );
	}

	$body[] = '';
	$body[] = __( 'Open it in the dashboard to read the full submission:', 'bewell' );
	$body[] = $link;

	wp_mail( $to, $subject, implode( "\n", $body ) );
}

/**
 * Render the hidden fields every form needs: nonce, form key and honeypot.
 *
 * @param string $form Form key.
 * @return void
 */
function bewell_form_fields( $form ) {
	wp_nonce_field( 'bewell_form_' . $form, 'bewell_nonce' );

	printf( '<input type="hidden" name="bewell_form" value="%s">', esc_attr( $form ) );

	// Matches the React build's honeypot: same field name, same off-screen
	// technique, so any bot already tuned to the old site still trips it.
	echo '<div style="position:absolute;left:-9999px;opacity:0;height:0;overflow:hidden" aria-hidden="true">';
	echo '<label>Website<input type="text" name="website" tabindex="-1" autocomplete="off" value=""></label>';
	echo '</div>';
}

/**
 * Render the form-level error banner, if any.
 *
 * @param string $form Form key.
 * @return void
 */
function bewell_form_error_banner( $form ) {
	$errors = bewell_form_errors( $form );

	if ( empty( $errors ) ) {
		return;
	}

	$message = isset( $errors['_form'] )
		? $errors['_form']
		: __( 'Please check the highlighted fields and try again.', 'bewell' );

	printf(
		'<div class="rounded-lg border border-destructive/30 bg-destructive/10 p-4 flex items-start gap-2.5" role="alert">%s<p class="text-sm text-destructive">%s</p></div>',
		bewell_icon( 'alert', 'w-4 h-4 text-destructive shrink-0 mt-0.5' ), // phpcs:ignore WordPress.Security.EscapeOutput
		esc_html( $message )
	);
}

/**
 * Render the inline error for a single field.
 *
 * @param string $form  Form key.
 * @param string $field Field name.
 * @return void
 */
function bewell_field_error( $form, $field ) {
	$errors = bewell_form_errors( $form );

	if ( empty( $errors[ $field ] ) ) {
		return;
	}

	printf( '<p class="text-sm text-destructive mt-1">%s</p>', esc_html( $errors[ $field ] ) );
}

/**
 * Convenience: `aria-invalid` attribute for a field with an error.
 *
 * @param string $form  Form key.
 * @param string $field Field name.
 * @return string
 */
function bewell_field_invalid( $form, $field ) {
	$errors = bewell_form_errors( $form );

	return empty( $errors[ $field ] ) ? '' : ' aria-invalid="true"';
}

/**
 * Render a labelled text/email/tel input.
 *
 * Wraps the label, control, and inline error in the same markup the React
 * build produced, so all three forms stay identical without repeating it.
 *
 * @param array $args {
 *     @type string $form        Form key. Required.
 *     @type string $name        Field name. Required.
 *     @type string $label       Visible label. Required.
 *     @type string $type        Input type. Default 'text'.
 *     @type bool   $required    Whether to mark and enforce required.
 *     @type string $placeholder Placeholder text.
 *     @type string $autocomplete Autocomplete token.
 * }
 * @return void
 */
function bewell_text_field( $args ) {
	$args = wp_parse_args(
		$args,
		array(
			'form'         => '',
			'name'         => '',
			'label'        => '',
			'type'         => 'text',
			'required'     => false,
			'placeholder'  => '',
			'autocomplete' => '',
		)
	);

	$id = 'bw-' . $args['form'] . '-' . $args['name'];

	echo '<div class="space-y-1.5">';

	printf(
		'<label class="bw-label" for="%s">%s%s</label>',
		esc_attr( $id ),
		esc_html( $args['label'] ),
		$args['required'] ? ' <span aria-hidden="true">*</span>' : ''
	);

	printf(
		'<input class="bw-input" type="%s" id="%s" name="%s" value="%s"%s%s%s%s>',
		esc_attr( $args['type'] ),
		esc_attr( $id ),
		esc_attr( bewell_field_name( $args['name'] ) ),
		esc_attr( bewell_form_value( $args['form'], $args['name'] ) ),
		$args['placeholder'] ? ' placeholder="' . esc_attr( $args['placeholder'] ) . '"' : '',
		$args['autocomplete'] ? ' autocomplete="' . esc_attr( $args['autocomplete'] ) . '"' : '',
		$args['required'] ? ' required' : '',
		bewell_field_invalid( $args['form'], $args['name'] ) // phpcs:ignore WordPress.Security.EscapeOutput
	);

	bewell_field_error( $args['form'], $args['name'] );

	echo '</div>';
}

/**
 * Render a labelled textarea.
 *
 * @param array $args See bewell_text_field(), plus `rows`.
 * @return void
 */
function bewell_textarea_field( $args ) {
	$args = wp_parse_args(
		$args,
		array(
			'form'        => '',
			'name'        => '',
			'label'       => '',
			'required'    => false,
			'placeholder' => '',
			'rows'        => 4,
		)
	);

	$id = 'bw-' . $args['form'] . '-' . $args['name'];

	echo '<div class="space-y-1.5">';

	printf(
		'<label class="bw-label" for="%s">%s%s</label>',
		esc_attr( $id ),
		esc_html( $args['label'] ),
		$args['required'] ? ' <span aria-hidden="true">*</span>' : ''
	);

	printf(
		'<textarea class="bw-textarea" id="%s" name="%s" rows="%d"%s%s%s>%s</textarea>',
		esc_attr( $id ),
		esc_attr( bewell_field_name( $args['name'] ) ),
		(int) $args['rows'],
		$args['placeholder'] ? ' placeholder="' . esc_attr( $args['placeholder'] ) . '"' : '',
		$args['required'] ? ' required' : '',
		bewell_field_invalid( $args['form'], $args['name'] ), // phpcs:ignore WordPress.Security.EscapeOutput
		esc_textarea( bewell_form_value( $args['form'], $args['name'] ) )
	);

	bewell_field_error( $args['form'], $args['name'] );

	echo '</div>';
}

/**
 * Render a labelled select.
 *
 * @param array $args {
 *     @type string $form     Form key.
 *     @type string $name     Field name.
 *     @type string $label    Visible label.
 *     @type array  $options  value => label.
 *     @type string $placeholder Empty first option.
 *     @type bool   $required Whether the field is required.
 * }
 * @return void
 */
function bewell_select_field( $args ) {
	$args = wp_parse_args(
		$args,
		array(
			'form'        => '',
			'name'        => '',
			'label'       => '',
			'options'     => array(),
			'placeholder' => '',
			'required'    => false,
		)
	);

	$id      = 'bw-' . $args['form'] . '-' . $args['name'];
	$current = bewell_form_value( $args['form'], $args['name'] );

	echo '<div class="space-y-1.5">';

	printf(
		'<label class="bw-label" for="%s">%s%s</label>',
		esc_attr( $id ),
		esc_html( $args['label'] ),
		$args['required'] ? ' <span aria-hidden="true">*</span>' : ''
	);

	printf(
		'<select class="bw-select" id="%s" name="%s"%s%s>',
		esc_attr( $id ),
		esc_attr( bewell_field_name( $args['name'] ) ),
		$args['required'] ? ' required' : '',
		bewell_field_invalid( $args['form'], $args['name'] ) // phpcs:ignore WordPress.Security.EscapeOutput
	);

	if ( $args['placeholder'] ) {
		printf(
			'<option value="" %s disabled>%s</option>',
			selected( $current, '', false ),
			esc_html( $args['placeholder'] )
		);
	}

	foreach ( $args['options'] as $value => $label ) {
		printf(
			'<option value="%s" %s>%s</option>',
			esc_attr( $value ),
			selected( $current, $value, false ),
			esc_html( $label )
		);
	}

	echo '</select>';

	bewell_field_error( $args['form'], $args['name'] );

	echo '</div>';
}

/**
 * The green tick panel shown after a successful submission.
 *
 * @param string $heading Heading text.
 * @param string $body    Body copy.
 * @return void
 */
function bewell_success_panel( $heading, $body ) {
	?>
	<div class="bw-card border-border" role="status">
		<div class="p-8 text-center">
			<div class="w-14 h-14 rounded-full bg-primary/15 flex items-center justify-center mx-auto mb-4">
				<?php bewell_the_icon( 'check', 'w-7 h-7 text-primary' ); ?>
			</div>
			<h3 class="text-xl font-semibold text-foreground mb-2"><?php echo esc_html( $heading ); ?></h3>
			<p class="text-muted-foreground max-w-sm mx-auto"><?php echo esc_html( $body ); ?></p>
		</div>
	</div>
	<?php
}
