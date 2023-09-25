<?php 

/*Template Name: Directory*/

get_header();

?>

<div id="<?php echo ai_starter_theme_get_content_id('content-full') ?>">
	<article id="content" class="hfeed chart-wrapper">
		
		<?php do_action('aios_starter_theme_before_inner_page_content') ?>
			
			<h1 class="page-title">
				<?php echo the_title(); ?>
				<span><?php echo get_queried_object()->description; ?></span>
			</h1>

			<div class="category-wraps">
				<ul class="category-list">
					<li data-value="Board of Directors" class="c-list-block"><i class="sprite sprite-icon-boad-of-directors"></i><span>Board of Directors</span></li>
					<li data-value="Accounting"><i class="sprite sprite-icon-accounting"></i><span>Accounting</span></li>
					<li data-value="Client Relations Managers"><i class="sprite sprite-icon-crm"></i><span>CRM's</span></li>
					<li data-value="IDX"><i class="sprite sprite-icon-idx"></i><span>IDX</span></li>
					<li data-value="NS FEWD"><i class="sprite sprite-icon-fewd"></i><span>NS FEWD</span></li>
					<li data-value="U.S. Internal"><i class="sprite sprite-icon-us-internal"></i><span>U.S. Internal</span></li>
					<li data-value="Admin"><i class="sprite sprite-icon-admin"></i><span>Admin</span></li>
					<li data-value="Designers"><i class="sprite sprite-icon-designer"></i><span>Designers</span></li>
					<li data-value="Internal Marketing"><i class="sprite sprite-icon-im"></i><span>IM</span></li>
					<li data-value="NS WAD"><i class="sprite sprite-icon-wad"></i><span>NS WAD</span></li>
					<li data-value="U.S. Project Managers"><i class="sprite sprite-icon-pm"></i><span>U.S. PM's</span></li>
					<li data-value="AIM Project Managers"><i class="sprite sprite-icon-pm"></i><span>AIM PM's</span></li>
					<li data-value="Digital Strategists"><i class="sprite sprite-icon-digital-strategists"></i><span>Digital Strategists</span></li>
					<li data-value="Manila I.T."><i class="sprite sprite-icon-manila-it"></i><span>Manila I.T.</span></li>
					<li data-value="NS QA"><i class="sprite sprite-icon-qa"></i><span>NS QA</span></li>
					<li data-value="U.S. Sales Team"><i class="sprite sprite-icon-sales"></i><span>U.S Sales</span></li>
					<li data-value="AIM Production"><i class="sprite sprite-icon-aim-prod"></i><span>AIM Prod</span></li>
					<li data-value="Editors"><i class="sprite sprite-icon-editors"></i><span>Editors</span></li>
					<li data-value="Marketing / SEO"><i class="sprite sprite-icon-marketing-seo"></i><span>Marketing / SEO</span></li>
					<li data-value="Project Management"><i class="sprite sprite-icon-pm"></i><span>PM's</span></li>
					<li data-value="U.S. VIP Project Managers"><i class="sprite sprite-icon-pm"></i><span>U.S VIP PM's</span></li>
					<li data-value="Art Directors"><i class="sprite sprite-icon-art-directors"></i><span>Art Directors</span></li>
					<li data-value="Front End Web Developers, FEWD"><i class="sprite sprite-icon-fewd"></i><span>FEWD</span></li>
					<li data-value="Migrations"><i class="sprite sprite-icon-migrations"></i><span>Migrations</span></li>
					<li data-value="Quality Assurance, QA"><i class="sprite sprite-icon-qa"></i><span>QA</span></li>
					<li data-value="Web Application Developers, WAD"><i class="sprite sprite-icon-wad"></i><span>WAD</span></li>
					<li data-value="Copywriters"><i class="sprite sprite-icon-copywriters"></i><span>Copywriters</span></li>
					<li data-value="Human Resources"><i class="sprite sprite-icon-hr"></i><span>HR</span></li>
					<li data-value="NS Design"><i class="sprite sprite-icon-designer"></i><span>NS Design</span></li>
					<li data-value="Sales"><i class="sprite sprite-icon-sales"></i><span>Sales</span></li>
					<li data-value="Web Consultants"><i class="sprite sprite-icon-wc"></i><span>WC's</span></li>

					<li data-value="Account Management"><i class="sprite sprite-icon-wc"></i><span>Account Management</span></li>
					<li data-value="Real"><i class="sprite sprite-icon-sales"></i><span>Real</span></li>
					<li data-value="Developers"><i class="sprite sprite-icon-fewd"></i><span>Developers</span></li>
					<li data-value="Canstar"><i class="sprite sprite-icon-fewd"></i><span>Canstar</span></li>

				</ul>
			</div>

			<div class="category-selected">
				<strong>Search by Department: </strong>
				<span class="sel-dep-text"></span>
				<input class="sel-dep" type="hidden">
			</div>

			<script>
				jQuery(window).load(function(){

					jQuery('body #content-full th.divisions, body #content-full th.pic, body #content-full th.extension').unbind('click');
				});
				
				jQuery(document).ready(function(){


					 var results_table = jQuery('.display').DataTable({

						"order": [[ 2, "asc" ]],
						"paging":   false,
						"ordering": true,
						"info":     false,
						"columnDefs": [
						{
							"targets": [ 7 ],
							"visible": false,
							"searchable": false
						}
						],
						initComplete: function () {
				            //this.api().columns().every( function () {

            					jQuery('.sel-dep').on( 'change', function () {
                                    var val = jQuery(this).val();
                                    var sel_wrap = jQuery('.category-selected');


                                    if( val != "" ) {
                                    	sel_wrap.stop(true,true).fadeIn('fast');

                                    	val.split(',');

                                    	jQuery('.table-responsive tbody tr').removeClass('hide').each(function(){
                                    		
                                    		cdata = jQuery("td:nth-child(5)",this).html();
                                    	
                                    		if( val.indexOf(cdata) == -1 || cdata == "" ) {
                                    			jQuery(this).addClass('hide');
                                    		}

                                    	});
                                    }else {
                                    	sel_wrap.stop(true,true).fadeOut('fast');
                                    }

                                    //results_table.search( val ? val : '', true, false ).draw();
                                } );

				            //} );
				        }

					 });

					

					category_wrap = jQuery('.category-wraps');

					 
					jQuery('.dataTables_filter label input').attr("placeholder","Looking for someone?");
					jQuery('.dataTables_filter').append('<a class="search-by-dep" href="#">Search by Department</a>');
					jQuery('.category-wraps').detach().insertAfter('.dataTables_filter');
					jQuery('.category-selected').detach().appendTo('.dataTables_filter');

					jQuery('.table-responsive').on('click','.search-by-dep', function(event){
						event.preventDefault();
						event.stopPropagation();

						category_wrap.slideToggle();
					})

					jQuery('.table-responsive').on('click','.category-list li',function(event){

						jQuery('.category-list li').removeClass('selected');
						jQuery(this).addClass('selected');

						selected_deps = jQuery(this).data('value');


						jQuery('.category-selected span.sel-dep-text').html(selected_deps);
						jQuery('.category-selected .sel-dep').val(selected_deps).trigger('change');
						category_wrap.slideToggle();

						jQuery('html,body').stop(true,true).animate({
							scrollTop: jQuery('.dataTables_filter').offset().top - jQuery('#main-header').outerHeight()
						});
						
					})

					jQuery('.table-responsive').on('click','.sel-dep-text',function(){
						jQuery(this).html('');
						jQuery('.table-responsive tbody tr, .category-list li').removeClass('hide selected');
						jQuery('.category-selected').stop(true,true).fadeOut('fast');
					})

					jQuery('.dv').each(function(i,v){

						if(jQuery(v).find('div').length > 1){
							jQuery(v).parent().addClass('multi-division')
						}

					})

				})

				
			</script>

			<div class="directory-body">
				<div class="table-responsive">
					<table class="display table" cellspacing="0" width="100%">
					        <thead>
					            <tr>
					            	<th class="divisions">&nbsp;</th>
					                <th class="pic">&nbsp;</th>
					                <th>Full Name</th>
					                <th>Title</th>
					                <th>Department</th>
					                <th>Email Address</th>
					                <th class="extension">Extension</th>
					                <th>Order</th>
					                <th>Slack</th>
					            </tr>
					        </thead>
					        <!-- All employees -->
					        <tbody>
							<?php
								// query post 
								$directory_list = new WP_Query( array(
										'post_type' => 'tdp_employee',
										'showposts' => -1,
										'orderby' => 'DATE',
										'order' => 'ASC'
								) );

								/// display all post into data table
								foreach( $directory_list->posts as $post ) {

									$employee_id = $post->ID;

									$first_name = get_post_meta( $post->ID, 'employee_first_name', true );
									$last_name = get_post_meta( $post->ID, 'employee_last_name', true );
									//$employee_photo = get_post_meta( $post->ID, 'employee_photo', true );

									$employee_photo = wp_get_attachment_image_src( get_post_meta($post->ID,'employee_photo',true), 'thumbnail');


									$designation =  get_post_meta( $post->ID, 'employee_designation', true );
									$slackuser = get_post_meta( $post->ID, 'slack_username', true );
									/* Identify terminal deparments */
									$slug="";
									$departments = get_the_terms($post->ID,'departments');

									$terminals_exl = array(
										"Client Facers"
									);

									$terminals = array();

									foreach ($departments as $key=>$dep) {

										/* Get department ID */
										$id = $dep->term_id;

										/* Store all departments in a temp var */
										$others = $departments;

										/* If the employee only belongs to one department, we assume that this department is terminal. */
										if ( count($departments) == 1 ) {
											$terminals[] = $dep->name;
											break;
										}

										/* Prevent comparing with oneself so */
										unset($others[$key]);

										foreach ($others as $other) {

											/* If department has children, it means it is NOT terminal so ignore it */
											if ( $other->parent == $id ) {
												break;
											}
											/* If department has no children, it is terminal */



											else {
												$terminals[] = $dep->name;
											}
										}

									}

									$terminals = array_unique($terminals);

									// set order to 1 if client facer
									$order = in_array("Client Facers", $terminals) ? '1' : '0';

									// check if area is client facers 
									$add_class = in_array("Client Facers", $terminals) ? 'client-facers' : '';

									// remove client facer
									$terminals_final = array_diff($terminals, $terminals_exl);

									$email_address = get_post_meta( $post->ID, 'employee_email_address', true );
									$extension = get_post_meta( $post->ID, 'employee_extension', true );



									//if primary department is selected, exclude other departments
									$cat = new WPSEO_Primary_Term('departments', $post->ID);
									$primary_term = get_term($cat->get_primary_term());
									if ( $primary_term->name != "" ) {
										$terminals_final = array($primary_term->name);
									}

									// used to get all details of current terms belogns to
									$term_details = get_term_by('name', $terminals_final, 'departments');

									// get list of division
									$division_set = array();
									$output='';
									$division_array = get_posts(
						            	array( 
						            		'showposts' => -1,
						                    'post_type' => 'division_content',
						                    'post_status' => 'publish',
						                )
						            );

						            wp_reset_postdata();

						           	foreach ($division_array as $division) {

						            	$var = array(
						            		'slug'=> get_post_meta( $division->ID, 'division_slug', true ),
						            		'name'=> $division->post_title
						            	);

						            	array_push( $division_set, $var);
						            }

									/// set which division of the employee 
									$division = get_post_meta( $employee_id, 'division', true );

									foreach ($division_set as $divi) {
										if( in_array($divi["slug"], $division)){
											$output.= '<div class="division-'.$divi["slug"].'">'.$divi["name"].'</div>';
										}
										
									}

									echo 
									'<tr class="'.$add_class.'">
												<td class="division_holder" width="160"><div class="dv">'.$output.'</div></td>
											    <td width="60" ><a href="#'.$employee_id.'"><img class="pro-pic" src="'.($employee_photo[0] != "" ? $employee_photo[0] : do_shortcode('[stylesheet_directory]')."/images/placeholder.png").'" width="60" height="60"></a></td>
											    <td width="150" class="full-name"><a href="#'.$employee_id.'">'.$first_name.' '.$last_name.'</a></td>
											    <td width="300">'.$designation.'</td>
											    <td width="250">'.implode($terminals_final,", ").'</td>
											    <td><a href="mailto:'.$email_address.'">'.$email_address.'</a></td>
											    <td>'.$extension.'</td>
											    <td>'.$order.'</td>
											    <td width="60">'.$slackuser.'</td>
									</tr>';

								}

							?>
					        </tbody>
					        <!-- All employees -->


				    </table>
				</div>
			</div>
		
		<?php do_action('aios_starter_theme_after_inner_page_content') ?>
		
    </article><!-- end #content -->
    
</div><!-- end #content-full -->

<?php get_footer(); ?>