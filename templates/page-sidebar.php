<?php
	/* Template Name: Sidebar */
	get_header(); 
?>
<div id="<?php echo ai_starter_theme_get_content_id('content-sidebar') ?>">
	<article id="content" class="hfeed">
		
		<?php do_action('aios_starter_theme_before_inner_page_content') ?>

		<h1 class="page-title">
			<?php echo the_title(); ?>
			<span><?php echo get_queried_object()->description; ?></span>
		</h1>
		
		<div class="chart-canvas">
			<?php if(have_posts()) : ?>
					
				<?php while(have_posts()) : the_post(); ?>
				
						<div class="entry">
							<?php the_content(); ?>
						</div>

				<?php endwhile; ?>

			<?php endif; ?>
		</div>
			
		<?php do_action('aios_starter_theme_after_inner_page_content') ?>
		
    </article><!-- end #content -->
    
    <?php get_sidebar(); ?>	
</div><!-- end #content-sidebar -->

<?php get_footer(); ?>
