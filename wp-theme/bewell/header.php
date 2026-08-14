<?php
/**
 * Site header — ported from src/components/Navbar.tsx.
 *
 * The React version tracked scroll position in state but never used the result
 * (the `scrolled` flag was set and then only logged, after an unreachable
 * return). The header was always the same solid white bar, so that is what this
 * renders — no script needed.
 *
 * @package bewell
 */

defined( 'ABSPATH' ) || exit;

$bewell_nav = array(
	'home'      => __( 'Home', 'bewell' ),
	'lifestyle' => __( 'Lifestyle Program', 'bewell' ),
	'training'  => __( 'Training', 'bewell' ),
	'hostel'    => __( 'Hostel', 'bewell' ),
	'farm'      => __( 'Farm', 'bewell' ),
	'work'      => __( 'Work With Us', 'bewell' ),
	'contact'   => __( 'Contact', 'bewell' ),
);
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class( 'min-h-screen flex flex-col bg-background text-foreground' ); ?>>
<?php wp_body_open(); ?>

<a class="sr-only focus:not-sr-only focus:absolute focus:z-[70] focus:top-2 focus:left-2 focus:bg-white focus:text-primary focus:px-4 focus:py-2 focus:rounded-md focus:shadow-lg"
	href="#bewell-main"><?php esc_html_e( 'Skip to content', 'bewell' ); ?></a>

<header class="bw-header fixed top-0 left-0 right-0 z-50 bg-white border-b border-border shadow-sm transition-all duration-300">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
		<div class="flex items-center justify-between h-16">

			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex items-center gap-2 focus:outline-none group">
				<img src="<?php echo esc_url( bewell_logo_url() ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" class="h-10 w-auto object-contain" width="120" height="40">
			</a>

			<nav class="hidden lg:flex items-center gap-1" aria-label="<?php esc_attr_e( 'Primary', 'bewell' ); ?>">
				<?php foreach ( $bewell_nav as $bewell_key => $bewell_label ) : ?>
					<a href="<?php echo esc_url( bewell_url( $bewell_key ) ); ?>"
						class="px-3 py-2 text-sm font-medium rounded-md transition-colors <?php echo bewell_is_current( $bewell_key ) ? 'text-primary bg-primary/10' : 'text-slate-700 hover:text-primary hover:bg-slate-100'; ?>"
						<?php echo bewell_is_current( $bewell_key ) ? 'aria-current="page"' : ''; ?>>
						<?php echo esc_html( $bewell_label ); ?>
					</a>
				<?php endforeach; ?>
			</nav>

			<div class="flex items-center gap-2">
				<a href="<?php echo esc_attr( bewell_tel( bewell_contact( 'phone' ) ) ); ?>" class="hidden sm:flex items-center gap-1.5 text-sm text-slate-500 hover:text-primary transition-colors">
					<?php bewell_the_icon( 'phone', 'w-4 h-4' ); ?>
					<span class="hidden md:inline"><?php esc_html_e( 'Call Us', 'bewell' ); ?></span>
				</a>

				<a href="<?php echo esc_url( bewell_url( 'lifestyle' ) ); ?>" class="bw-btn bw-btn-sm bw-btn-primary hidden sm:flex">
					<?php esc_html_e( 'Apply Now', 'bewell' ); ?>
				</a>

				<button type="button" class="bw-btn bw-btn-icon bw-btn-ghost lg:hidden text-slate-700 hover:bg-slate-100"
					data-bewell-drawer-open aria-expanded="false" aria-controls="bewell-drawer">
					<?php bewell_the_icon( 'menu', 'w-5 h-5' ); ?>
					<span class="sr-only"><?php esc_html_e( 'Open menu', 'bewell' ); ?></span>
				</button>
			</div>
		</div>
	</div>
</header>

<div class="bw-drawer lg:hidden" id="bewell-drawer" data-open="false" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Menu', 'bewell' ); ?>">
	<div class="bw-drawer-overlay" data-bewell-drawer-close></div>
	<div class="bw-drawer-panel">
		<div class="flex items-center justify-between gap-2 mb-8 pt-2">
			<img src="<?php echo esc_url( bewell_logo_url() ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" class="h-10 w-auto object-contain" width="120" height="40">
			<button type="button" class="bw-btn bw-btn-icon bw-btn-ghost text-slate-700" data-bewell-drawer-close>
				<?php bewell_the_icon( 'x', 'w-5 h-5' ); ?>
				<span class="sr-only"><?php esc_html_e( 'Close menu', 'bewell' ); ?></span>
			</button>
		</div>

		<nav class="flex flex-col gap-1" aria-label="<?php esc_attr_e( 'Mobile', 'bewell' ); ?>">
			<?php foreach ( $bewell_nav as $bewell_key => $bewell_label ) : ?>
				<a href="<?php echo esc_url( bewell_url( $bewell_key ) ); ?>"
					class="px-4 py-3 text-sm font-medium rounded-md text-left transition-colors <?php echo bewell_is_current( $bewell_key ) ? 'text-primary bg-primary/10' : 'text-slate-700 hover:text-primary hover:bg-slate-100'; ?>">
					<?php echo esc_html( $bewell_label ); ?>
				</a>
			<?php endforeach; ?>
		</nav>

		<div class="mt-6 pt-6 border-t border-slate-200">
			<a href="<?php echo esc_url( bewell_url( 'contact' ) ); ?>" class="bw-btn bw-btn-default bw-btn-primary w-full">
				<?php esc_html_e( 'Contact Us', 'bewell' ); ?>
			</a>
		</div>
	</div>
</div>

<main id="bewell-main" class="flex-1">
