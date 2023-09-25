<?php get_header(); ?>
<div id="<?php echo ai_starter_theme_get_content_id('content-full') ?>">
	<article id="content" class="hfeed">
		
		<?php do_action('aios_starter_theme_before_inner_page_content') ?>
		
		<?php if(have_posts()) : ?>
				
			<?php while(have_posts()) : the_post(); 

				//$employee_photo = get_post_meta(get_the_ID(),'employee_photo')[0]	

				$employee_photo = wp_get_attachment_image_src( get_post_meta($post->ID,'employee_photo',true), 'employee_photo');


				$employee_name = get_post_meta(get_the_ID(),'employee_first_name')[0] . ' ' . get_post_meta(get_the_ID(),'employee_last_name')[0];
				$employee_designation = get_post_meta(get_the_ID(),'employee_designation')[0];
				$employee_email_address = get_post_meta(get_the_ID(),'employee_email_address')[0];
				$employee_extension = get_post_meta(get_the_ID(),'employee_extension')[0];
				$employee_shift = get_post_meta(get_the_ID(),'employee_shift')[0];

				$employee_position = get_post_meta(get_the_ID(),'employee_position')[0];;

			?>

				<div class="employee-post-wrap row">
					<div class="col-xs-12 col-md-3 employee-post-photo">
						<?php

							echo '<img src="'.($employee_photo[0] == "" ? get_stylesheet_directory_uri()."/images/placeholder.png" : $employee_photo[0]).'" alt="'.get_the_title().'">';

						?>
					</div>
					<div class="col-xs-12 col-md-9 employee-post-details">
						<h1 class="entry-title">
							<?php echo $employee_name; ?>
							<span><?php echo $employee_designation; ?></span>
						</h1>
						
						<div class="employee-detailed-info">
							<?php 

								if( $employee_email_address!=""){
									echo '<div class="emp-email">
										<i class="ai-font-envelope"></i> <a href="mailto:'.$employee_email_address.'">'.$employee_email_address .'</a>
									</div>';
								}

								if( $employee_extension!=""){
									echo '<div class="emp-email">
										<i class="ai-font-phone-a"></i> '.$employee_extension .'
									</div>';
								}

								if( $employee_shift !=""){
									echo '<div class="emp-email">
										<i class="ai-font-time-a"></i> '. $employee_shift .' Shift
									</div>';
								}
									
							?>

							
						</div>

					</div>
				</div>

			<?php endwhile; ?>

		<?php endif; ?>

		
		<?php do_action('aios_starter_theme_after_inner_page_content') ?>
		
    </article><!-- end #content -->
    
</div><!-- end #content-full -->

<?php get_footer(); ?>
