<?php
	
	add_shortcode( 'akvo_multistage_form', function( $atts ){
		ob_start();
		include('templates/multistage_form.php');
		return ob_get_clean();
	} );
	
	add_shortcode( 'akvo_map_tab', function( $atts ){
		ob_start();
		include('templates/map_tab.php');
		return ob_get_clean();
	} );
	
	add_shortcode( 'akvo_iframe_report', function( $atts ){
		ob_start();
		include('templates/akvo_iframe_report.php');
		return ob_get_clean();
	} );
	
	add_shortcode( 'akvo_process', function( $atts ){
		ob_start();
		include('templates/akvo_process.php');
		return ob_get_clean();
	} );
	
	add_shortcode( 'akvo_rotating_sentence', function( $atts ){
		ob_start();
		include('templates/rotating_sentence.php');
		return ob_get_clean();
	} );
	
	/*
	add_shortcode( 'akvo_sector_stories', function( $atts ){
		ob_start();
		include('templates/sector_stories.php');
		return ob_get_clean();
	} );
	
	add_shortcode( 'akvo_filter_stories', function( $atts ){
		ob_start();
		include('templates/filter_stories.php');
		return ob_get_clean();
	} );

	
	add_shortcode( 'akvo_testimonials', function( $atts ){
		ob_start();
		include('templates/testimonials.php');
		return ob_get_clean();
	} );
	*/