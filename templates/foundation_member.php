<?php

	$staff_fields = array(
		'member_title'		=> '',
		'staff_twitter'		=> '',
		'staff_linkedin'	=> '',
		'staff_blog'		=> ''
	);

	foreach( $staff_fields as $slug => $field ){
		$staff_fields[ $slug ] = esc_html( get_post_meta( get_the_ID(), $slug, true ) );
	}

?>

	<!-- Display featured image in right-aligned floating div -->
	<div class="imgWrapper"><?php the_post_thumbnail(); ?></div>
	<!-- Display Title and Name -->
	<div class="staffName"><?php the_title(); ?></div>
	<p class="staffTitle"><?php _e( $staff_fields['member_title'] ); ?></p>
	<!-- Display LINKS -->
	<p class="links">
		<?php if( $staff_fields['staff_twitter'] ):?>
		<a href="<?php _e( $staff_fields['staff_twitter'] ); ?>" target="_blank"><i class="fab fa-twitter"></i></a>
		<?php endif;?>
		<?php if( $staff_fields['staff_linkedin'] ):?>
		<a href="<?php _e( $staff_fields['staff_linkedin'] ); ?>" target="_blank"><i class="fab fa-linkedin"></i></a>
		<?php endif;?>
		<?php if( $staff_fields['staff_blog'] ):?>
		<a href="<?php _e( $staff_fields['staff_blog'] ); ?>" style="float:right;margin-top:8px;padding-right:10px;">Read my blogs</a>
		<?php endif;?>
	</p>
