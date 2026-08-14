<?php
/**
 * Inline SVG icons.
 *
 * The React build pulled these from lucide-react. Shipping a JS icon library to
 * render a dozen static glyphs would be absurd in a PHP theme, so the paths are
 * inlined here — same 24×24 grid, same 2px round stroke, so they render
 * identically to the site they replace.
 *
 * @package bewell
 */

defined( 'ABSPATH' ) || exit;

/**
 * Raw path data for each icon, keyed by its lucide name.
 *
 * @return array<string, string>
 */
function bewell_icon_paths() {
	static $paths = null;

	if ( null !== $paths ) {
		return $paths;
	}

	$paths = array(
		'arrow-right'   => '<path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>',
		'chevron-right' => '<path d="m9 18 6-6-6-6"/>',
		'chevron-down'  => '<path d="m6 9 6 6 6-6"/>',
		'check'         => '<path d="M20 6 9 17l-5-5"/>',
		'x'             => '<path d="M18 6 6 18"/><path d="m6 6 12 12"/>',
		'menu'          => '<line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/>',
		'heart'         => '<path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>',
		'leaf'          => '<path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/>',
		'users'         => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
		'sun'           => '<circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/>',
		'shield'        => '<path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/>',
		'phone'         => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>',
		'map-pin'       => '<path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/>',
		'mail'          => '<rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>',
		'globe'         => '<circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/>',
		'circle-play'   => '<circle cx="12" cy="12" r="10"/><polygon points="10 8 16 12 10 16 10 8"/>',
		'clock'         => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
		'quote'         => '<path d="M16 3a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2.5a.5.5 0 0 1 .5.5v.5a2 2 0 0 1-2 2 1 1 0 0 0 0 2 4 4 0 0 0 4-4V5a2 2 0 0 0-2-2z"/><path d="M6 3a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2.5a.5.5 0 0 1 .5.5v.5a2 2 0 0 1-2 2 1 1 0 0 0 0 2 4 4 0 0 0 4-4V5a2 2 0 0 0-2-2z"/>',
		'star'          => '<path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"/>',
		'calendar'      => '<path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/>',
		'utensils'      => '<path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/><path d="M7 2v20"/><path d="M21 15V2a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/>',
		'droplets'      => '<path d="M7 16.3c2.2 0 4-1.83 4-4.05 0-1.16-.57-2.26-1.71-3.19S7.29 6.75 7 5.3c-.29 1.45-1.14 2.84-2.29 3.76S3 11.1 3 12.25c0 2.22 1.8 4.05 4 4.05z"/><path d="M12.56 6.6A10.97 10.97 0 0 0 14 3.02c.5 2.5 2 4.9 4 6.5s3 3.5 3 5.5a6.98 6.98 0 0 1-11.91 4.97"/>',
		'wind'          => '<path d="M12.8 19.6A2 2 0 1 0 14 16H2"/><path d="M17.5 8a2.5 2.5 0 1 1 2 4H2"/><path d="M9.8 4.4A2 2 0 1 1 11 8H2"/>',
		'moon'          => '<path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/>',
		'book-open'     => '<path d="M12 7v14"/><path d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z"/>',
		'graduation'    => '<path d="M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z"/><path d="M22 10v6"/><path d="M6 12.5V16a6 3 0 0 0 12 0v-3.5"/>',
		'home'          => '<path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"/><path d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>',
		'bed'           => '<path d="M2 4v16"/><path d="M2 8h18a2 2 0 0 1 2 2v10"/><path d="M2 17h20"/><path d="M6 8v9"/>',
		'wifi'          => '<path d="M12 20h.01"/><path d="M2 8.82a15 15 0 0 1 20 0"/><path d="M5 12.859a10 10 0 0 1 14 0"/><path d="M8.5 16.429a5 5 0 0 1 7 0"/>',
		'sprout'        => '<path d="M7 20h10"/><path d="M10 20c5.5-2.5.8-6.4 3-10"/><path d="M9.5 9.4c1.1.8 1.8 2.2 2.3 3.7-2 .4-3.5.4-4.8-.3-1.2-.6-2.3-1.9-3-4.2 2.8-.5 4.4 0 5.5.8"/><path d="M14.1 6a7 7 0 0 0-1.1 4c1.9-.1 3.3-.6 4.3-1.4 1-1 1.6-2.3 1.7-4.6-2.7.1-4 1-4.9 2"/>',
		'apple'         => '<path d="M12 6.528V3a1 1 0 0 1 1-1h0"/><path d="M18.63 8.132A5.4 5.4 0 0 0 12 8a5.4 5.4 0 0 0-8 4.75c0 4.5 4 8.25 6.5 8.25 1.25 0 1.75-.5 1.5-.5s.25.5 1.5.5c2.5 0 6.5-3.75 6.5-8.25a5.4 5.4 0 0 0-1.37-4.618"/>',
		'briefcase'     => '<path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/><rect width="20" height="14" x="2" y="6" rx="2"/>',
		'hand-heart'    => '<path d="M11 14h2a2 2 0 0 0 0-4h-3c-.6 0-1.1.2-1.4.6L3 16"/><path d="m7 20 1.6-1.4c.3-.4.8-.6 1.4-.6h4c1.1 0 2.1-.4 2.8-1.2l4.6-4.4a2 2 0 0 0-2.75-2.91l-4.2 3.9"/><path d="m2 15 6 6"/><path d="M19.5 8.5c.7-.7 1.5-1.6 1.5-2.7A2.73 2.73 0 0 0 16 4a2.78 2.78 0 0 0-5 1.8c0 1.2.8 2 1.5 2.8L16 12Z"/>',
		'sparkles'      => '<path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"/>',
		'alert'         => '<path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/>',
	);

	return $paths;
}

/**
 * Render an inline SVG icon.
 *
 * @param string $name  Icon name, see bewell_icon_paths().
 * @param string $class Classes for the <svg> element.
 * @param array  $attrs Extra attributes, e.g. array( 'aria-hidden' => 'true' ).
 * @return string Escaped-safe SVG markup, or an empty string for unknown names.
 */
function bewell_icon( $name, $class = 'w-5 h-5', $attrs = array() ) {
	$paths = bewell_icon_paths();

	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}

	$defaults = array(
		'xmlns'        => 'http://www.w3.org/2000/svg',
		'width'        => '24',
		'height'       => '24',
		'viewBox'      => '0 0 24 24',
		'fill'         => 'none',
		'stroke'       => 'currentColor',
		'stroke-width' => '2',
		'stroke-linecap'  => 'round',
		'stroke-linejoin' => 'round',
		'class'        => $class,
		'aria-hidden'  => 'true',
		'focusable'    => 'false',
	);

	$attrs = array_merge( $defaults, $attrs );

	$rendered = '';
	foreach ( $attrs as $key => $value ) {
		if ( '' === $value || null === $value ) {
			continue;
		}
		$rendered .= sprintf( ' %s="%s"', $key, esc_attr( $value ) );
	}

	// The path data is a hard-coded constant above, never user input, so it is
	// emitted as-is; wp_kses would strip valid SVG shapes.
	return '<svg' . $rendered . '>' . $paths[ $name ] . '</svg>';
}

/**
 * Echo an icon.
 *
 * @param string $name  Icon name.
 * @param string $class Classes.
 * @param array  $attrs Extra attributes.
 * @return void
 */
function bewell_the_icon( $name, $class = 'w-5 h-5', $attrs = array() ) {
	echo bewell_icon( $name, $class, $attrs ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- constant SVG markup.
}
