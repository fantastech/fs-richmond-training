<?php
/**
 * To prevent accessing the file directly.
 */

if (! defined('ABSPATH')) {
    return;
}

/**
* Define Constants
*/
define('FS_THEME_VERSION', '2.1');
define('FS_DEV_MODE', true);
define('FS_THEME_USE_FONT_AWESOME', false); // Enable this only if BB plugin is active.
define('FS_THEME_USE_CUSTOM_JS', true); // This will enqueue script.js file.

/**
* Include core functions
*/
require_once 'includes/core-functions.php';
/**
* Enqueue styles
*/
add_action('wp_enqueue_scripts', function () {
    $version = FS_DEV_MODE ? time() : FS_THEME_VERSION;

    // Enqueue Beaver Builder's Font Awesome library file.
    if (FS_THEME_USE_FONT_AWESOME) {
        wp_enqueue_style('font-awesome-5');
    }

    // Enqueue this theme's style.css.
    wp_enqueue_style(
        'fs-theme-styles',
        get_stylesheet_directory_uri() . '/assets/css/style.css',
        [],
        $version,
    );

    // Enqueue script.js file.
    if (FS_THEME_USE_CUSTOM_JS) {
        wp_enqueue_script(
            'fs-theme-js',
            get_stylesheet_directory_uri() . '/assets/js/script.js',
            ['jquery'],
            $version,
            true,
        );
    }
});

/**
 * Create custom post types (if needed)
 * Delete the function or the lines if not needed
 *
 * @see /includes/core-functions.php for available paramters.
 */
function fs_define_post_types_taxonomies()
{

    fs_register_post_type('course', 'Course', 'Courses',['rewrite'=> [ 'slug' => 'courses'],
        'has_archive' => 'courses'
    ]);
    fs_register_taxonomy('courses_category', 'Course Tags', 'Course Category', 'course');
}
// Uncomment the lines below to add custom post type and taxonomies.
// phpcs:ignore Squiz.Commenting.InlineComment.InvalidEndChar
add_action('init', 'fs_define_post_types_taxonomies', 0);

/**
 * Gets and returns the generated config from SCSS variables.
 *
 * @return array
 */
function fs_get_theme_style_config(){
    return json_decode(file_get_contents(get_stylesheet_directory() . '/assets/palette.json'));
}

add_filter( 'get_the_archive_title', function ($title) {
    if (is_post_type_archive('course')) {
        $title = post_type_archive_title( '', false );
      }
      return $title;
});

add_shortcode('show_course_field', function () {
    if(is_single() && 'course' == get_post_type() ):
        if(has_post_thumbnail()) {
            $image = get_the_post_thumbnail_url();
        }
        else{
            $image = get_template_directory_uri().'/assets/images/default.svg';
        } 
        $price = get_field('_bean_course_price');
        $button = get_field('_bean_course_button');
    ob_start();
    ?>
    <div class="sidebar-container">
        <?php

        $args = array(
            'post_status'=>'publish',
            'post_type'=>'course',
            'posts_per_page'=> 3,
            'orderby'=>'modified',
            'order'=>'DSC',   
        );
        $post_id = get_the_ID(); 
        $tags = get_the_terms($post_id,'courses_category');
        
        
        ?>
        <div class="course-meta">
            <div class="course-price-meta">
                <img class="card-img-top" src="<?php echo $image; ?>">
                <h2 class="title">Online Course</h2>
                <?php if (!is_wp_error($tags) && !empty($tags)){
                    foreach($tags as $tag){?>
                        <div class="category">
                            <p>CATEGORY</p>
                            <h3 class="category-name"><?php echo $tag->name; ?></h3>
                        </div>
                    <?php }
                }?>
                <div class="price">
                    <p>PRICE</p>
                    <h3 class="course-price"> £<?php echo esc_html( $price ); ?></h3>
                </div>
                <div class="wp-block-button button-blue">
                    <a href="<?php echo $button ?>" target="_blank" class="wp-block-button__link">BUY COURSE NOW</a>
                </div>
                 <div class="wp-block-button button-transparent">
                    <a href="https://videotilehost.com/richmondtraining/freeTrial.php" target="_blank" class="wp-block-button__link">REGISTER FOR A FREE TRIAL</a>
                </div>
                 <div class="wp-block-button button-transparent">
                    <a href="https://videotilehost.com/richmondtraining/" target="_blank" class="wp-block-button__link">CANDIDATE LOGIN</a>
                </div>
                
            </div>
        </div>
            
    </div>
    <?php elseif (is_single() && 'post' == get_post_type()) :
        if(has_post_thumbnail()) {
            $image = get_the_post_thumbnail_url();
        }
        else{
           $image = get_template_directory_uri().'/assets/images/default.svg';
        } 
        $img_htm='<img src="'. esc_url(str_replace('<p>','',$image)).'">';
    ?>

        <div class="post-featured-image">
            <?php echo  $img_htm; ?>
        </div>

    <?php endif; ?>
    <?php
    $html = ob_get_clean();
    return $html;
});

add_filter('the_content', function($content){
    if(is_singular('course')){
        $video = get_field('_bean_course_video_html', get_the_ID());
        $video_html = '<div class="video-section">'. $video.'</div>';
        $content = $video_html . $content;
    }
    return $content;
});

if( function_exists('acf_add_options_page') ) {
    
    acf_add_options_page(array(
        'page_title'     => 'Global Settings',
        'menu_title'    => 'Global Settings',
        'menu_slug'     => 'theme-general-settings',
        'capability'    => 'edit_posts',
        'redirect'        => false
    ));
}

add_action( 'astra_primary_content_top', 'wpd_astra_primary_content_top' );
function wpd_astra_primary_content_top() {
    
    if(is_home()){
        $blog_sub_title = get_field('blog_sub_title','option');
        $blog_description = get_field('blog_description','option');
     ?>
    <div class="title-container">
        <div class="content-column">
            <h2 class="title"><?php echo $blog_sub_title; ?></h2>
            <p class="description"><?php echo $blog_description; ?></p>
        </div>
    </div>
     <?php   
        
    }
    else if(is_post_type_archive('course') || is_tax()){
        $course_sub_title = get_field('course_sub_title','option');
        $course_description = get_field('course_description','option');
        $category_title=get_field('category_title','option');

        $terms = get_terms(
            [
                'taxonomy'   => 'courses_category',
                'hide_empty' => false,
            ],
        );
        $post_id = get_the_ID(); 
        $tags = get_the_terms($post_id,'courses_category');
    ?>

        <div class="title-container course-archive">
            <div class="content-column">
                <div class="course-archive-content">
                    <h2 class="title"><?php echo $course_sub_title; ?></h2>
                    <p class="description"><?php echo $course_description; ?></p>
                </div>
            </div>
            <div class="content-column">
                <div class="category-select">
                    <select>
                        <option>Select a category to refine the list …</option>
                        <?php 
                            foreach ($terms as $term) {?>
                               <option value="<?php echo get_term_link($term); ?>"><?php echo  $term->name;?></option>
                           <?php }
                        ?>
                    </select>
                </div>
            </div>
        </div>
    <?php
    }

}

add_filter( 'astra_page_layout', function($layout){
    $post_type = get_post_type();
    if($post_type == 'course' && !is_single()){
           $layout = astra_get_option( 'archive-post-sidebar-layout' );
    }
    return $layout;
} );


add_action('astra_footer_before', function(){
    if(is_single() && 'course' == get_post_type() ){
        if(has_post_thumbnail()) {
            $image = get_the_post_thumbnail_url();
        }
        else{
            $image = get_template_directory_uri().'/assets/images/default.svg';
        } 
        $price = get_field('_bean_course_price');
        $button = get_field('_bean_course_button');
    ?>
    <div class="course-bottom-section">
        <?php

        $args = array(
            'post_status'=>'publish',
            'post_type'=>'course',
            'posts_per_page'=> 3,
            'orderby'=>'modified',
            'order'=>'DSC',   
        );
        $post_id = get_the_ID(); 
        $tags = get_the_terms($post_id,'courses_category');
        
        
        ?>
        <div class="course-column">
            <div class="course-info-column">
                <div class="price">
                    <h3 class="course-price">Online Course £<?php echo esc_html( $price ); ?></h3>
                </div>
                <div class="title">
                    <h2 class="course-title"><?php the_title(); ?></h2>
                </div>
                <div class="block-button">
                    <div class="wp-block-button button-white">
                    <a href="<?php echo $button ?>" target="_blank" class="wp-block-button__link">BUY COURSE NOW</a>
                    </div>
                     <div class="wp-block-button button-transparent">
                        <a href="https://videotilehost.com/richmondtraining/freeTrial.php" target="_blank" class="wp-block-button__link">REGISTER FOR A FREE TRIAL</a>
                    </div>
                </div>
            </div>
            <div class="course-featured-img">
                <img class="featured-image" src="<?php echo $image; ?>">
            </div>
        </div>
    </div>
    <?php
    }
});
