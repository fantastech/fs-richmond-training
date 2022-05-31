<?php

/**
 * Function to enable adding of custom post types easily.
 */

/**
 * Create new post type
 *
 * @param  string $cpt_slug the slug of CPT (required)
 * @param  string $cpt_name the name of CPT in singular (required)
 * @param  string $cpt_name_plural the name of CPT in plural (required)
 * @param  array  $args array for register_post_type function (optional)
 *
 * @return null
 */
if (!function_exists('fs_register_post_type')) {
    function fs_register_post_type($cpt_slug, $cpt_name, $cpt_name_plural, $args = [])
    {

        $labels = [
            'name'                => $cpt_name_plural,
            'singular_name'       => $cpt_name,
            'menu_name'           => $cpt_name_plural,
            'parent_item_colon'   => "Parent {$cpt_name}",
            'all_items'           => "All {$cpt_name_plural}",
            'view_item'           => "View {$cpt_name}",
            'add_new_item'        => "Add New {$cpt_name}",
            'add_new'             => 'Add New',
            'edit_item'           => "Edit {$cpt_name}",
            'update_item'         => "Update {$cpt_name}",
            'search_items'        => "Search {$cpt_name}",
            'not_found'           => "No {$cpt_name_plural} Found",
            'not_found_in_trash'  => "No {$cpt_name_plural} found in Trash"
        ];

        $args = wp_parse_args($args, [
            'label'               => $cpt_slug,
            'description'         => "{$cpt_name_plural} Post Type",
            'labels'              => $labels,
            'supports'            => [ 'title', 'editor', 'thumbnail', 'revisions'],
            'public'              => true,
            'hierarchical'        => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => true,
            'show_in_admin_bar'   => true,
            'has_archive'         => true,
            'can_export'          => true,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'show_in_rest'        => true,
            'capability_type'     => 'post'
        ]);

        register_post_type($cpt_slug, $args);
    }
}

/**
* Create new taxonomy for a post type
*
* @param  string $taxonomy_slug the slug of custom taxonomy (required)
* @param  string $taxonomy_name the name of custom taxonomy in singular (required)
* @param  string $taxonomy_name_plural the name of custom taxonomy in plural (required)
* @param  string $cpt_slug the slug of CPT (required)
* @param  array  $args array for register_taxonomy function (optional)
*
* @return null
*/
if (!function_exists('fs_register_taxonomy')) {
    function fs_register_taxonomy($taxonomy_slug, $taxonomy_name, $taxonomy_name_plural, $cpt_slug, $args = [])
    {

        $labels = [
            'name' => $taxonomy_name_plural,
            'singular_name' => $taxonomy_name,
            'search_items' =>  "Search {$taxonomy_name_plural}",
            'all_items' => "All {$taxonomy_name_plural}",
            'parent_item' => "Parent {$taxonomy_name}",
            'parent_item_colon' => "Parent {$taxonomy_name}:",
            'edit_item' => "Edit {$taxonomy_name}",
            'update_item' => "Update {$taxonomy_name}",
            'add_new_item' => "Add New {$taxonomy_name}",
            'new_item_name' => "New {$taxonomy_name} Name",
            'menu_name' => $taxonomy_name_plural,
        ];

        $args =  wp_parse_args($args, [
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'show_in_rest' => true,
            'rewrite' => [ 'slug' => $taxonomy_slug ]
        ]);

        register_taxonomy($taxonomy_slug, [ $cpt_slug ], $args);
    }
}

/**
* White Labeling Astra
*/

add_filter('astra_addon_get_white_labels', 'fs_modify_theme_labels');
function fs_modify_theme_labels($labels){
	$labels = array(
		'astra-agency' => array(
			'author'        => 'Fantastech',
			'author_url'    => 'https://fantastech.co/',
			'licence'       => '',
			'hide_branding' => true,
		),
		'astra'        => array(
			'name'        => 'FS Core',
			'description' => 'This is the main parent theme of your website. Do not delete.',
			'screenshot'  => get_stylesheet_directory_uri() . '/screenshot-parent.png',
		),
		'astra-pro'    => array(
			'name'        => 'FS Core',
			'description' => 'This is the core plugin required by FS Core theme to function properly.',
		),
	);
	return $labels;
}

/**
* To change default small
* screen break point to 767px
*/
add_filter('astra_mobile_breakpoint', function () {
    return 767;
});

/**
* To change default tablet
* screen break point to 1024px
*/
add_filter('astra_tablet_breakpoint', function () {
    return 1024;
});

/**
* To change default header
* breakpoint for hamburger icons
*/
add_filter('astra_header_break_point', function () {
    return 1024;
});

/**
 * `wp_parse_args`, but recursive.
 *
 * @param $a
 * @param $b
 *
 * @return array
 */
function fs_wp_parse_args_recursive($a, $b)
{
    $a = (array) $a;
    $b = (array) $b;
    $r = $b;

    foreach ($a as $k => &$v) {
        if (is_array($v) && isset($r[$k])) {
            $r[ $k ] = fs_wp_parse_args_recursive($v, $r[$k]);
        } else {
            $r[ $k ] = $v;
        }
    }

    return $r;
}
