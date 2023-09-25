<?php get_header(); ?>

	<section class="chart-wrapper">
		<div class="container">
			<div class="home-content-wrap">
			<!-- start of the content -->

				<div id="chart-container">
					<!-- do not put contents inside -->
				</div>
				
				<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('wp-homepage-chart-data') ) : ?>


					<ul id="homepage-chart" class="orgchart-data">
						<li id="d111" data-link="http://aios2-staging.agentimage.com/o/orgchart.thedesignpeople.net/htdocs/departments/board-of-directors/" data-class="uppercase-title node-large"><span>Board of Directors</span> <em>Executive Team</em>
							<ul>
								<li id="d69" data-link="http://aios2-staging.agentimage.com/o/orgchart.thedesignpeople.net/htdocs/departments/tdp-us/" data-class="node-red node-medium"><span>The Design People</span> <em>US</em></li>
								<li id="d128" data-link="http://aios2-staging.agentimage.com/o/orgchart.thedesignpeople.net/htdocs/departments/august-99/" data-class="node-medium"><span>August 99, Inc.</span> <em>Philippines</em>
									<ul>
										<li id="d115" data-link="http://aios2-staging.agentimage.com/o/orgchart.thedesignpeople.net/htdocs/departments/the-design-people/" data-class="node-red node-no-sub"><span>The Design People</span>
										</li>
										<li id="d116" data-link="http://aios2-staging.agentimage.com/o/orgchart.thedesignpeople.net/htdocs/departments/agentimage/" data-class="node-blue node-no-sub"><span>Agent Image</span></li>
										<li id="d125" data-link="http://aios2-staging.agentimage.com/o/orgchart.thedesignpeople.net/htdocs/departments/real/" data-class="node-green node-no-sub"><span>Real</span></li>
										<li id="d129" data-link="http://aios2-staging.agentimage.com/o/orgchart.thedesignpeople.net/htdocs/departments/loft/" data-class="node-grey node-no-sub"><span>Loft</span>
										</li>
									</ul>
								</li>
							</ul>
						</li>
					</ul>

				<?php endif; ?>

			<!-- end of the content -->
			</div>
		</div>
	</section>

<?php get_footer(); ?>