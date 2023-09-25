<?php get_header(); ?>
								
<div id="<?php echo ai_starter_theme_get_content_id('content-full') ?>">


	<section id="content" class="hfeed chart-wrapper">

			<?php do_action('aios_starter_theme_before_inner_page_content') ?>

			<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
				<?php /* If this is a category archive */ if (is_category()) { ?>
					<h1 class="archive-title"><?php single_cat_title(); ?></h1>
				<?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
					<h1 class="archive-title">Posts Tagged &#8216;<?php single_tag_title(); ?>&#8217;</h1>
				<?php /* If this is a daily archive */ } elseif (is_day()) { ?>
					<h1 class="archive-title">Archive for <?php the_time( get_option( 'date_format' ) ); ?></h1>
				<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
					<h1 class="archive-title">Archive for <?php the_time( get_option( 'date_format' ) ); ?></h1>
				<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
					<h1 class="archive-title">Archive for <?php the_time( get_option( 'date_format' ) ); ?></h1>
				<?php /* If this is an author archive */ } elseif (is_author()) { ?>
					<h1 class="archive-title">Author Archive</h1>
				<?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
					<h1 class="archive-title">Blog Archives</h1>
			<?php } ?>

			<h1 class="page-title">
				<?php echo get_queried_object()->name; ?>
				<span><?php echo get_queried_object()->description; ?></span>
			</h1>
		
			<?php

				// Get current terms objects
				$terms = get_terms(get_queried_object()->taxonomy, array(
				       "orderby"    => "count",
				       "hide_empty" => false
				   )
				);


				// Get hierarchy
				$hierarchy = _get_term_hierarchy( get_queried_object()->taxonomy );

				$template_contents = get_post_template(get_queried_object()->term_id);

				$template_found =  check_template_activation($template_contents);

			?>

			<div class="chart-canvas <?php echo $template_contents[0]["template_division"]; ?>">
				<div class="canvas-loading-cover">
					<div id="circularG">
						<div id="circularG_1" class="circularG"></div>
						<div id="circularG_2" class="circularG"></div>
						<div id="circularG_3" class="circularG"></div>
						<div id="circularG_4" class="circularG"></div>
						<div id="circularG_5" class="circularG"></div>
						<div id="circularG_6" class="circularG"></div>
						<div id="circularG_7" class="circularG"></div>
						<div id="circularG_8" class="circularG"></div>
					</div>
				</div>
				<?php


					function check_template_activation( $haystack ){


						if ( !empty($haystack) ){

							foreach ($haystack as $key => $haystack_val) {
								if( $haystack_val['template_status'] == "publish" &&  $haystack_val['active_status'] == "Active" ) {
									return $key;
								}
							}

						}

					}


					if( $hierarchy[get_queried_object()->term_id] ) {

						if ( strval( $template_found ) != "" ){
							

								$hierarchy_level = get_post_meta( $template_contents[$template_found]['template_id'], 'template_hierarchy_text', true);

								$hierarchy_level = json_decode($hierarchy_level);

								echo parse_json_list( $hierarchy_level );

							 ?>		
							<div id="chart-container">
							</div>

						<?php
						}else {


							echo 'Coming Soon.. <br>This will show the list of departments under this category/department/division.';

							// get term list
							foreach($hierarchy[get_queried_object()->term_id] as $child) {

								$child = get_term($child, "departments");

								// show department list only
								/*if( !in_array($child->name, $orgchart_exclude_terms) ) {
									$cat_meta = get_option( "category_" . $child->term_id );
							    	echo '<a href="'.DEPARTMENTS_TAXONOMY.$child->slug.'" class="orgchart-node node-2 node-terms">
						    				
							    			<div class="orgchart-node-aligner">
							    				<div class="node-name">'.$child->name.'</div>
							    			</div>
							    		</a>';
								}*/

						    }

						}
						

					}else {


						if ( strval( $template_found ) != "" ){

							

								$hierarchy_level = get_post_meta( $template_contents[$template_found]['template_id'], 'template_hierarchy_text', true);

								$hierarchy_level = json_decode($hierarchy_level);

								echo parse_json_list( $hierarchy_level );


							?>	
							<div id="chart-container">
								
							</div>

							<?php

						}else {

							get_template_part("content","employee");

						}

					}

				?>

			</div>

			<?php do_action('aios_starter_theme_after_inner_page_content') ?>
	</section><!-- end of content -->

</div><!-- end #content-full -->

<?php get_footer(); ?>