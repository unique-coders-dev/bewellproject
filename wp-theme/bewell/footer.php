<?php
/**
 * Site footer — ported from src/components/Footer.tsx.
 *
 * @package bewell
 */

defined( 'ABSPATH' ) || exit;
?>
</main>

<footer class="bg-foreground text-background">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">

			<div class="lg:col-span-1">
				<div class="flex items-center gap-2 mb-4">
					<div class="bg-white p-1 rounded-md">
						<img src="<?php echo esc_url( bewell_logo_url() ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" class="h-10 w-auto object-contain" width="120" height="40" loading="lazy">
					</div>
				</div>
				<p class="text-sm text-background/70 leading-relaxed mb-4">
					<?php esc_html_e( 'A center of health and healing nestled in the beautiful hills near Choto Daragar Hat. Transforming lives through natural lifestyle medicine.', 'bewell' ); ?>
				</p>
				<div class="flex items-center gap-3">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php esc_attr_e( 'Website', 'bewell' ); ?>" class="text-background/60 hover:text-primary transition-colors">
						<?php bewell_the_icon( 'globe', 'w-5 h-5' ); ?>
					</a>
					<a href="<?php echo esc_url( bewell_url( 'lifestyle' ) ); ?>" aria-label="<?php esc_attr_e( 'Programmes', 'bewell' ); ?>" class="text-background/60 hover:text-primary transition-colors">
						<?php bewell_the_icon( 'circle-play', 'w-5 h-5' ); ?>
					</a>
				</div>
			</div>

			<div>
				<h2 class="text-sm font-semibold text-background uppercase tracking-widest mb-4"><?php esc_html_e( 'Programs', 'bewell' ); ?></h2>
				<ul class="space-y-2">
					<?php
					$bewell_programs = array(
						'lifestyle' => __( 'Lifestyle Program', 'bewell' ),
						'training'  => __( 'Training Program', 'bewell' ),
						'hostel'    => __( 'Hostel Services', 'bewell' ),
						'farm'      => __( 'Our Farm', 'bewell' ),
					);
					foreach ( $bewell_programs as $bewell_key => $bewell_label ) :
						?>
						<li>
							<a href="<?php echo esc_url( bewell_url( $bewell_key ) ); ?>" class="text-sm text-background/70 hover:text-primary transition-colors">
								<?php echo esc_html( $bewell_label ); ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>

			<div>
				<h2 class="text-sm font-semibold text-background uppercase tracking-widest mb-4"><?php esc_html_e( 'Company', 'bewell' ); ?></h2>
				<ul class="space-y-2">
					<?php
					$bewell_company = array(
						'home'    => __( 'About Us', 'bewell' ),
						'work'    => __( 'Work With Us', 'bewell' ),
						'contact' => __( 'Contact Us', 'bewell' ),
					);
					foreach ( $bewell_company as $bewell_key => $bewell_label ) :
						?>
						<li>
							<a href="<?php echo esc_url( bewell_url( $bewell_key ) ); ?>" class="text-sm text-background/70 hover:text-primary transition-colors">
								<?php echo esc_html( $bewell_label ); ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>

			<div>
				<h2 class="text-sm font-semibold text-background uppercase tracking-widest mb-4"><?php esc_html_e( 'Find Us', 'bewell' ); ?></h2>
				<ul class="space-y-3">
					<li class="flex items-start gap-2.5 text-sm text-background/70">
						<?php bewell_the_icon( 'map-pin', 'w-4 h-4 mt-0.5 shrink-0 text-primary' ); ?>
						<span><?php echo esc_html( bewell_contact( 'address' ) ); ?></span>
					</li>
					<li class="flex items-center gap-2.5 text-sm text-background/70">
						<?php bewell_the_icon( 'phone', 'w-4 h-4 shrink-0 text-primary' ); ?>
						<a href="<?php echo esc_attr( bewell_tel( bewell_contact( 'phone' ) ) ); ?>" class="hover:text-primary transition-colors">
							<?php echo esc_html( bewell_contact( 'phone' ) ); ?>
						</a>
					</li>
					<li class="flex items-center gap-2.5 text-sm text-background/70">
						<?php bewell_the_icon( 'mail', 'w-4 h-4 shrink-0 text-primary' ); ?>
						<a href="mailto:<?php echo esc_attr( bewell_contact( 'email' ) ); ?>" class="hover:text-primary transition-colors">
							<?php echo esc_html( bewell_contact( 'email' ) ); ?>
						</a>
					</li>
				</ul>
			</div>
		</div>

		<hr class="my-8 border-0 h-px bg-background/10">

		<div class="flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-background/50">
			<p>
				<?php
				printf(
					/* translators: %s: current year. */
					esc_html__( '© %s BE WELL ALWAYS LTD. All rights reserved.', 'bewell' ),
					esc_html( gmdate( 'Y' ) )
				);
				?>
			</p>
			<p><?php esc_html_e( 'Center of Health & Healing', 'bewell' ); ?></p>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
