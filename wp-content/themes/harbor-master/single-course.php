<?php
/**
 * The template for displaying all single course posts.
 *
 * @package     Harbor
 * @link        https://themebeans.com/themes/harbor
 */

// META
$video  = get_post_meta( $post->ID, '_bean_course_video_html', true );
$price  = get_post_meta( $post->ID, '_bean_course_price', true );
$button = get_post_meta( $post->ID, '_bean_course_button', true );

get_header();

designer_set_post_views( get_the_ID() );

$format = get_post_format();

if ( false === $format ) {
	$format = 'standard';
} ?>

<div id="post-<?php the_ID(); ?>" class="post single-post type-post status-publish format-standard hentry">

	<?php
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();
			?>
			<article class="entry-content">

				<div id="course-price">
					<div class="course-price-meta">
						<h1 class="entry-title"><?php the_title(); ?></h1>
					</div>
				</div>

				<?php if ( $video ) { ?>
					<div class="flex-video"><?php echo $video ?></div>
				<?php } ?>

				<p>Price: £<?php echo esc_html( $price ); ?></p>
				<button><a href="https://videotilehost.com/richmondtraining/freeTrial.php" target="_blank">Register For Free Trial</a></button><button><a href="https://videotilehost.com/richmondtraining/" target="_blank">Candidate Login</a></button><button class="richmond-buy"><a href="<?php echo $button ?>" target="_blank">Buy Now</a></button>

				<hr>

				<?php the_content(); ?>

				<hr>

				<div class="masonry-project">
					<?php // check if the post or page has a Featured Image assigned to it.
						if ( has_post_thumbnail() ) {
						    the_post_thumbnail( 'medium', array( 'class' => '' ) );
						} 
					?>
				</div>

				<div id="course-price">
					<div class="masonry-project course-price-meta">
						<h1 class="entry-title"><?php the_title(); ?></h1>
						<p>Price: £<?php echo esc_html( $price ); ?></p>
						<button><a href="https://videotilehost.com/richmondtraining/freeTrial.php" target="_blank">Free Trial</a></button><button class="richmond-buy"><a href="<?php echo $button ?>" target="_blank">Buy Now</a></button>
					</div>
				</div>

				<div style="display: block; padding: 1.5em 0; clear: both;"></div>

				<?php
				wp_link_pages(
					array(
						'before'         => '<p><strong>' . __( 'Pages:', 'harbor' ) . '</strong> ',
						'after'          => '</p>',
						'next_or_number' => 'number',
					)
				);
				?>
			</article>
			<?php
	endwhile;
endif;
	wp_reset_postdata();
?>

</div>

<?php
if ( true === get_theme_mod( 'post_meta' ) ) {
	get_template_part( 'content-post-meta' );
}

if ( true === get_theme_mod( 'post_next' ) ) {
	get_template_part( 'content-post-next' );
}

// If comments are open or we have at least one comment, load up the comment template.
if ( comments_open() || get_comments_number() ) :
	comments_template();
endif;

if ( true === get_theme_mod( 'post_sharing' ) ) {
	get_template_part( 'content-post-sharing' );
}

get_footer();
