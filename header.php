<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta id="viewport-tag" name="viewport" content="width=device-width"/>
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<link rel="shortcut icon" href="<?php bloginfo( 'stylesheet_directory' ); ?>/images/favicon.ico" type="image/x-icon">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php wp_head(); ?>
</head>

<body <?php body_class();?> >
	
	<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Mobile Header") ) : ?><?php endif ?>
	
	<div id="main-wrapper">

	<header id="main-header" class="animated">
		<h2 class="aios-starter-theme-hide-title">Header</h2>

		<div class="mobile-nav">
			<div class="hamburger">
			  <span class="hamburger-box">
			    <span class="hamburger-inner"></span>
			  </span>
			</div>
			<nav>
				<?php wp_nav_menu( array( 'sort_column' => 'menu_order', 'menu_id' => 'nav_mobile', 'theme_location' => 'primary-menu' ) ); ?>
			</nav>
		</div>

		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-sm-6">
					<div class="logo">

						<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('wp-main-logo') ) : ?>
							
							<a href="<?php echo do_shortcode('[blogurl]'); ?>">
								<img class="animated a99" src="<?php echo do_shortcode('[stylesheet_directory]'); ?>/images/august99.png" width="249" height="82" alt="August99"/>
							</a>

						<?php endif; ?>
					
					</div>
					<div class="mini-logo">

						<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('wp-mini-logo') ) : ?>
							
							<a href="#"><img src="<?php echo do_shortcode('[stylesheet_directory]'); ?>/images/logo-a99.png" width="140" height="127" alt="August 99"/></a>
							<a href="#"><img src="<?php echo do_shortcode('[stylesheet_directory]'); ?>/images/logo-tdp.png" width="140" height="138" alt="The Design People"/></a>
							<a href="#"><img src="<?php echo do_shortcode('[stylesheet_directory]'); ?>/images/logo-ai.png" width="140" height="138" alt="Agent Image"/></a>
							<a href="#"><img src="<?php echo do_shortcode('[stylesheet_directory]'); ?>/images/logo-real.png" width="142" height="142" alt="Real.ph"/></a>
							<a href="#"><img src="<?php echo do_shortcode('[stylesheet_directory]'); ?>/images/logo-loft.png" width="140" height="138" alt="Loft"/></a>

						<?php endif; ?>

						

					</div>
				</div>
				<div class="col-xs-12 col-sm-6 navigation animated">
					<nav>
						<?php wp_nav_menu( array( 'sort_column' => 'menu_order', 'menu_id' => 'nav', 'theme_location' => 'primary-menu' ) ); ?>
					</nav>
				</div>
			</div>
		</div>
		
		<div class="legend-wrap">
			<div class="org-chart-legend-list-wrap">

				<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('wp-legends') ) : ?>
					
					<table class="org-chart-legend-list">

						<tr>
							<td><strong>A99</strong></td>
							<td>August 99</td>
						</tr>

						<tr>
							<td><strong>AD</strong></td>
							<td>Art Director</td>
						</tr>

						<tr>
							<td><strong>AIM</strong></td>
							<td>Agent Image Marketing</td>
						</tr>

						<tr>
							<td><strong>BPO</strong></td>
							<td>Business Process Outsourcing</td>
						</tr>

						<tr>
							<td><strong>CEO</strong></td>
							<td>Chief Executive Officer</td>
						</tr>

						<tr>
							<td><strong>CFO</strong></td>
							<td>Chief Financial Officer</td>
						</tr>

						<tr>
							<td><strong>COO</strong></td>
							<td>Chief Operating Officer</td>
						</tr>

						<tr>
							<td><strong>CRM</strong></td>
							<td>Client Relations Manager</td>
						</tr>

						<tr>
							<td><strong>CTO</strong></td>
							<td>Chief Technology Officer</td>
						</tr>

						<tr>
							<td><strong>FEWD</strong></td>
							<td>Front-End Web Developer</td>
						</tr>

						<tr>
							<td><strong>OM</strong></td>
							<td>Operations Manager</td>
						</tr>

						<tr>
							<td><strong>PM</strong></td>
							<td>Project Manager</td>
						</tr>

						<tr>
							<td><strong>QA</strong></td>
							<td>Quality Assurance</td>
						</tr>	

						<tr>
							<td><strong>TDP</strong></td>
							<td>The Design People</td>
						</tr>
						<tr>

							<td><strong>WAD</strong></td>
							<td>Web Application Developer</td>
						</tr>

						<tr>
							<td><strong>WC</strong></td>
							<td>Web Consultant</td>
						</tr>


					</table>

				<?php endif; ?>

				
			</div>
		</div>

		
	</header>

	<!-- POP UP DETAILS PAGE -->
		<div class="popup-main-wrapper">
			<div class="popup-background"></div>
			<div class="popup-area-content-wrap">
				<div class="popup-content-area-inner">
					
					

					<div class="container">
						<div class="popup-close-btn"><em class="ai-font-x-sign"></em></div>
						
						<div class="popup-loader">
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
						
						<!-- start of container -->
						
						<div class="popup-inner">
							<!-- append content here -->
						</div>

						<!--  end of container -->
						<div class="popup-close-btn-wrap">
							<div class="popup-close-btn"><span>Close</span></div>
						</div>
					</div>
				</div>


			</div>
		</div>
	<!--  END OF POPUP DETAILS PAGE-->

	
	<main id="main-content">
		<h2 class="aios-starter-theme-hide-title">Main Content</h2>
		<?php if ( !is_home() ) : ?>
		<div id="inner-page-wrapper">
			<div class="container">
				<?php 
					if ( function_exists('yoast_breadcrumb') ) {
						yoast_breadcrumb('<p class="yoast-breadcrumbs">','</p>');
					} 
				?>
		<?php endif ?>