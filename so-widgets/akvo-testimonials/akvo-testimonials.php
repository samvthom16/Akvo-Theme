<?php
/*
	Widget Name: Akvo Testimonials
	Description: AKVO SOW for using Akvo Testimonials within the page builder
	Author: Samuel Thomas, Akvo
	Author URI: 
	Widget URI: 
	Video URI: 
*/
class Akvo_Testimonials extends SiteOrigin_Widget {
	
	function __construct() {
		//Here you can do any preparation required before calling the parent constructor, such as including additional files or initializing variables.
		//Call the parent constructor with the required arguments.
		parent::__construct(
			// The unique id for your widget.
			'akvo-testimonials',
			// The name of the widget for display purposes.
			__('Akvo Testimonials', 'siteorigin-widgets'),
			// The $widget_options array, which is passed through to WP_Widget.
			// It has a couple of extras like the optional help URL, which should link to your sites help or support page.
			array(
				'description' => __('AKVO SOW for using Akvo Testimonials within the page builder', 'siteorigin-widgets'),
				'help'        => '',
			),
			//The $control_options array, which is passed through to WP_Widget
			array(),
			//The $form_options array, which describes the form fields used to configure SiteOrigin widgets. We'll explain these in more detail later.
			array(
				'testimonials' => array(
					'type' 	=> 'repeater',
					'label' => __( 'Testimonials' , 'siteorigin-widgets' ),
					'item_name'  => __( 'Testimonial', 'siteorigin-widgets' ),
					'fields' => array(
						'content' => array(
							'type' 	=> 'textarea',
							'label' => __( 'Content', 'siteorigin-widgets' )
						),
						'name' => array(
							'type' 	=> 'text',
							'label' => __( 'Name', 'siteorigin-widgets' )
						),
						'image' => array(
							'type' 		=> 'media',
							'label' 	=> __( 'Choose Image', 'siteorigin-widgets' ),
							'choose' 	=> __( 'Choose image', 'siteorigin-widgets' ),
							'update' 	=> __( 'Set image', 'siteorigin-widgets' ),
							'library' 	=> 'image',
							'fallback' 	=> false
						),
					)
				)
			),
			//The $base_folder path string.
			get_template_directory()."/so-widgets/akvo-testimonials"
		);
	}
	
	function get_template_name($instance) {
		return 'template';
	}
	function get_template_dir($instance) {
		return 'templates';
	}
    function get_style_name($instance) {
        return '';
    }
}
siteorigin_widget_register('akvo-testimonials', __FILE__, 'Akvo_Testimonials');