<?php
/**
 * The template for displaying the footer
 *
 * @package     Harbor
 * @link        https://themebeans.com/themes/harbor
 */

if ( ! is_404() && ! is_page_template( 'template-underconstruction.php' ) ) { ?>

			<?php if ( is_active_sidebar( 'page-bottom' ) ) { ?>
				<div class="page-bottom">
    				<div class="entry-content">
    					<?php dynamic_sidebar( 'page-bottom' ); ?>
    				</div>
    			</div>
			<?php } ?>

	<?php echo do_shortcode('[logocarousel id="1416"]'); ?>

	</div>

	</section>

	<section id="colophon" class="content-wide">
		<div id="project" class="projects entry-content">
			<?php if ( is_active_sidebar( 'footer-left' ) ) { ?>
    			<div class="masonry-projec footer-widget">
    				<?php dynamic_sidebar( 'footer-left' ); ?>
    			</div>
			<?php } ?>
			<?php if ( is_active_sidebar( 'footer-middle' ) ) { ?>
    			<div class="masonry-projec footer-widget">
    				<?php dynamic_sidebar( 'footer-middle' ); ?>
    			</div>
			<?php } ?>
			<?php if ( is_active_sidebar( 'footer-right' ) ) { ?>
    			<div class="masonry-projec footer-widget">
    				<?php dynamic_sidebar( 'footer-right' ); ?>
    			</div>
			<?php } ?>
		</div>
		<div style="clear: both;"></div>
		<div class="grid-sizer"></div>
	</section>

	<footer id="footer" class="footer">

		<?php if ( get_theme_mod( 'footer_copyright' ) ) { ?>
			<p class="copyright">&copy; <?php echo date( 'Y' ); ?> <?php echo get_theme_mod( 'footer_copyright' ); ?></p>
		<?php } else { ?>
			<p class="copyright">&copy; <?php echo date( 'Y' ); ?> Richmond Training Academy Ltd</p>
		<?php } ?>

		<ul class="footer-social">
			<?php if ( get_theme_mod( 'footer_social_twitter' ) ) { ?>
			<li><a href="<?php echo esc_url( get_theme_mod( 'footer_social_twitter' ) ); ?>" class="twitter-icon"></a></li>
			<?php } ?>

			<?php if ( get_theme_mod( 'footer_social_facebook' ) ) { ?>
			<li><a href="<?php echo esc_url( get_theme_mod( 'footer_social_facebook' ) ); ?>" class="facebook-icon"></a></li>
			<?php } ?>

			<?php if ( get_theme_mod( 'footer_social_dribbble' ) ) { ?>
			<li><a href="<?php echo esc_url( get_theme_mod( 'footer_social_dribbble' ) ); ?>" class="dribbble-icon"></a></li>
			<?php } ?>

			<?php if ( get_theme_mod( 'footer_social_email' ) ) { ?>
			<li><a href="mailto:<?php echo esc_url( get_theme_mod( 'footer_social_email' ) ); ?>" class="email-icon"></a></li>
			<?php } ?>

			<?php if ( get_theme_mod( 'footer_social_instagram' ) ) { ?>
			<li><a href="<?php echo esc_url( get_theme_mod( 'footer_social_instagram' ) ); ?>" class="instagram-icon"></a></li>
			<?php } ?>

			<?php if ( get_theme_mod( 'footer_social_linkedin' ) ) { ?>
			<li><a href="<?php echo esc_url( get_theme_mod( 'footer_social_linkedin' ) ); ?>" class="linkedin-icon"></a></li>
			<?php } ?>

			<?php if ( get_theme_mod( 'footer_social_behance' ) ) { ?>
			<li><a href="<?php echo esc_url( get_theme_mod( 'footer_social_behance' ) ); ?>" class="behance-icon"></a></li>
			<?php } ?>
		</ul>

		<?php if ( get_theme_mod( 'back_to_top' ) == true ) { ?>
			<p class="copyright to-top"><a href="#page-container" id="back-to-top"><?php echo __( 'To Top', 'harbor' ); ?></a></p>
		<?php } ?>

	</footer>

<?php
}

wp_footer();
?>

</body>
</html>
