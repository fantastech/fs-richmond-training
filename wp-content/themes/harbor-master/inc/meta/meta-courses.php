<?php
/**
 * The file is for creating the team post type meta.
 * Meta output is defined on the team single editor.
 * Corresponding meta functions are located in framework/metaboxes.php
 *
 * @package     Harbor
 * @link        https://themebeans.com/themes/harbor
 */

function bean_metabox_course() {

	$prefix = '_bean_';

	/*
	===================================================================*/
	/*
	  TEAM META SETTINGS
	/*===================================================================*/
	$meta_box = array(
		'id'          => 'course-meta',
		'title'       => __( 'Course Settings', 'harbor' ),
		'description' => __( '', 'harbor' ),
		'page'        => 'course',
		'context'     => 'normal',
		'priority'    => 'high',
		'fields'      => array(
			array(
				'name' => __( 'Video:', 'harbor' ),
				'desc' => __( 'Paste the course video embed code here.', 'harbor' ),
				'id'   => $prefix . 'course_video_html',
				'type' => 'textarea',
				'std'  => '',
			),
			array(
				'name' => __( 'Price:', 'harbor' ),
				'desc' => __( 'Display this course course&#39;s price.', 'harbor' ),
				'id'   => $prefix . 'course_price',
				'type' => 'text',
				'std'  => '',
			),
			array(
				'name' => __( 'Button Link:', 'harbor' ),
				'desc' => __( 'Add the link to buy this course.', 'harbor' ),
				'id'   => $prefix . 'course_button',
				'type' => 'text',
				'std'  => '',
			),
		),
	);
	bean_add_meta_box( $meta_box );

}
add_action( 'add_meta_boxes', 'bean_metabox_course' );