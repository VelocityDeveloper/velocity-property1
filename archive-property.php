<?php
/**
 * The template for displaying archive pages
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package justg
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();

$container = velocitytheme_option( 'justg_container_type','container' );
?>

<div class="wrapper" id="archive-wrapper">

	<div class="<?php echo esc_attr( $container ); ?>" id="content" tabindex="-1">

		<div class="row">

			<!-- Do the left sidebar check -->
			<?php do_action('justg_before_content'); ?>

			<main class="site-main col order-2" id="main">

				<?php

				if ( have_posts() ) {
					?>
					<header class="page-header block-primary">
						<?php
						do_action('justg_before_title');				
						
						if ( is_post_type_archive() ) {
							$title  = post_type_archive_title( '', false );
							$prefix = _x( 'Archives:', 'post type archive title prefix' );
							echo '<h1 class="page-title">'.$title.'</h1>';
						} elseif ( is_tax() ) {
							$queried_object = get_queried_object();
							if ( $queried_object ) {
								$tax    = get_taxonomy( $queried_object->taxonomy );
								$title  = single_term_title( '', false );
								$prefix = sprintf(
									/* translators: %s: Taxonomy singular name. */
									_x( '%s:', 'taxonomy term archive title prefix' ),
									$tax->labels->singular_name
								);
								echo '<h1 class="page-title">'.$title.'</h1>';
							}
						}

						the_archive_description( '<div class="taxonomy-description">', '</div>' );

						do_action('justg_after_title');
						?>
					</header><!-- .page-header -->
					<?php
					// Start the loop.
					echo '<div class="text-center row">';
						while (have_posts()) {
							the_post();
							echo '<div class="col-sm-6 mb-4">';
								get_template_part('property','loop');
							echo '</div>';
						}
					echo '</div>';
					}
					?>
				<!-- Display the pagination component. -->
				<?php justg_pagination(); ?>
			</main><!-- #main -->

			<!-- Do the right sidebar check. -->
			<?php do_action('justg_after_content'); ?>

		</div><!-- .row -->

	</div><!-- #content -->

</div><!-- #archive-wrapper -->

<?php
get_footer();
