<?php 


	add_image_size( 'employee_photo', 500, 500 );


	if ( !defined( 'DEPARTMENTS_TAXONOMY' ) ) {
	define( 'DEPARTMENTS_TAXONOMY', site_url().'/departments/' );
	}


	/* Post Type */
	class employee_post_types {

		function __construct() {

			// enqueue assets
			add_action('admin_enqueue_scripts', array($this, 'load_assets') );

			//register post type
			add_action( 'init', array($this, 'register_employee_post_types') );

			// taxonomy
			add_action( 'init', array($this,'employee_taxonomy'), 0 );

			// custom metabox for job post type
			add_action( 'add_meta_boxes',  array( $this, 'employee_meta_boxes') );
			
			// update post type
			add_filter( 'save_post', array( $this, 'employee_meta_boxes_save'));

			// Custom Post Columns
			add_filter( 'manage_edit-tdp_employee_columns', array($this,'tdp_employee_custom_columns') ) ;
			add_action( 'manage_tdp_employee_posts_custom_column', array($this,'my_manage_tdp_employee_columns'), 10, 2 );
			add_filter( 'manage_edit-tdp_employee_sortable_columns', array($this, 'tdp_employee_sortable_columns') );

			add_filter( 'enter_title_here', array($this,'wpb_change_title_text') );

			add_filter( 'admin_body_class', array($this, 'se_154951_add_admin_body_class') );

			new archive_attributes();
			new template_post_types();
			new division_post_types();
			new ajax_functions();

		}

		function se_154951_add_admin_body_class( $classes ) {
			// class added to admin body to hide some options
			global $current_user;
			get_currentuserinfo();
		 
			if (is_user_logged_in() && $current_user->roles[0] == "author" || $current_user->roles[0] == "editor")  {
				return "$classes user-level-1";
			}
		    
		}

		function load_assets($hook) {

			wp_enqueue_media();
			if ( 'post.php' != $hook && 'post-new.php' != $hook) {
		        return;
		    }

		    wp_enqueue_script('jquery-ui-droppable');
		    wp_enqueue_script('jquery-ui-sortable');

		    wp_enqueue_style('jschart-style', get_stylesheet_directory_uri() . '/css/jquery.orgchart.css');
		    wp_enqueue_script('jschart-script', get_stylesheet_directory_uri() . '/js/jquery.orgchart-a99.js');

		 	wp_enqueue_script( 'wp-admin-custom-scripts', get_stylesheet_directory_uri(). '/lib/admin-scripts.js' );
		 	wp_enqueue_style( 'wp-admin-custom-styles', get_stylesheet_directory_uri(). '/lib/admin-styles.css' );

		}

		

		/** Taxonomy **/
		function employee_taxonomy() {

		    register_taxonomy( 
		    	'departments',  /* Taxonomy ID */
		    	'tdp_employee',
		    	array( 
		    		'hierarchical' => true,
		    		'label' => 'Departments', /* Taxonomy Name */
		    		'query_var' => true, 
		    		'rewrite' => true,
		    		'publicly_queryable' => true,
		    	)
		    );

		}

		/*** List of Functions ***/
		function register_employee_post_types() {

			register_post_type('tdp_employee',
				array(
					'labels' => array(
						'name' => 'Employees',
						'singular_name' => 'Employee',
						'add_new' => 'Add New Employee',
						'add_new_item' => 'Add New Employee',
						'edit_item' => 'Edit Employee',
						'new_item' => 'New Employee',
						'view_item' => 'View Employee',
						'search_items' => 'Search Employee',
						'not_found' =>  'Employee Not Found',
						'not_found_in_trash' => 'Nothing found in the Trash',
						'parent_item_colon' => ''
					),
					'public' => true,
					'taxonomies' => array('departments'),
					'description' => 'Employee Master List',
					'publicly_queryable' => false,
					'show_ui' => true,
					'query_var' => true,
					'menu_icon' => 'dashicons-groups',
					'rewrite' => true,
					'capability_type' => 'post',
					'hierarchical' => false,
					'menu_position' => 27,
					'supports' => array('title', 'revisions')
				)
			);

		}

		function employee_meta_boxes() {
			add_meta_box( "employee_meta", "Employee Information", array($this, 'employee_meta_boxes_function'), "tdp_employee", "normal", "high" );
		}

		function employee_meta_boxes_function($post){

			wp_nonce_field( 'employee_meta', 'employee_meta_nonce', true, true );

			?>

				<div class="metabox-holder">

					<div class="metabox-float-wrap">
						<div class="metabox-left">
							<div class="metabox-row">
								<label>Last Name:</label>
								<input type="text" name="employee_last_name" placeholder="" value="<?php echo get_post_meta($post->ID,'employee_last_name',true); ?>"/>
							</div>
							<div class="metabox-row">
								<label>First Name:</label>
								<input type="text" name="employee_first_name" placeholder="" value="<?php echo get_post_meta($post->ID,'employee_first_name',true); ?>"/>
							</div>
							<div class="metabox-row">
								<label>Email Address:</label>
								<input type="email" name="employee_email_address" placeholder="" value="<?php echo get_post_meta($post->ID,'employee_email_address',true); ?>"/>
							</div>
							<div class="metabox-row">
								<label>Extension Number:</label>
								<input type="text" name="employee_extension" placeholder="" value="<?php echo get_post_meta($post->ID,'employee_extension',true); ?>"/>
							</div>
							<div class="metabox-row">
								<label>Position: <em>(leave blank if not applicable)</em></label>
								<div class="metabox-radio-wrap">
									<input type="radio" value="Director" name="employee_position" <?php echo (get_post_meta($post->ID,'employee_position',true) == "Director" ? "checked" : "") ?> > <span>Director</span>
								</div>
								<div class="metabox-radio-wrap">
									<input type="radio" value="Operations Manager" name="employee_position" <?php echo (get_post_meta($post->ID,'employee_position',true) == "Operations Manager" ? "checked" : "") ?> > <span>Operations Manager</span>
								</div>

								<div class="metabox-radio-wrap">
									<input type="radio" value="Department Head" name="employee_position" <?php echo (get_post_meta($post->ID,'employee_position',true) == "Department Head" ? "checked" : "") ?> > <span>Manager / Department Head</span>
								</div>

								<div class="metabox-radio-wrap">
									<input type="radio" value="Team Leader" name="employee_position" <?php echo (get_post_meta($post->ID,'employee_position',true) == "Team Leader" ? "checked" : "") ?> > <span>Team Leader</span>
								</div>
								<div class="metabox-radio-wrap">
									<input type="radio" value="Assistant Team Leader" name="employee_position" <?php echo (get_post_meta($post->ID,'employee_position',true) == "Assistant Team Leader" ? "checked" : "") ?> > <span>Assistant Team Leader</span>
								</div>

								<div class="metabox-radio-wrap">
									<input type="radio" value="Member" name="employee_position" <?php echo (get_post_meta($post->ID,'employee_position',true) == "Member" ? "checked" : "") ?> > <span>Member</span>
								</div>
								
							</div>
							<div class="metabox-row">
								<label>Title / Designation: </label>
								<input type="text" name="employee_designation" placeholder="ex: Front End Web Developer / Internal Sr. FEWD / SEO Strategist - SME " value="<?php echo get_post_meta($post->ID,'employee_designation',true); ?>"/>
							</div>
							<div class="metabox-row">
								<label>Division: </label>

								<?php
									
									$division_set = array();
									$division_array = get_posts(
						            	array( 
						            		'showposts' => -1,
						                    'post_type' => 'division_content',
						                    'post_status' => 'publish',
						                )
						            );

						            wp_reset_postdata();

									$custom_meta = get_post_meta( $post->ID, 'division', true );

									foreach ($division_array as $value) {

										$visibility = '';

										$divi_slug =  get_post_meta( $value->ID, 'division_slug', true );

										if ( in_array( $divi_slug , $custom_meta) ) $visibility = 'checked="checked"';

										echo '
											<input type="checkbox" class="chk_division" name="divisions[]" id="division_tdp" value="'.$divi_slug.'" '. $visibility .'>'.$value->post_title.'<br>';

									}

								?>
							
							</div>
							<div class="metabox-row">
								<label>Slack Username</label>
								<input type="text" name="slack_username" placeholder="@slack_username " value="<?php echo get_post_meta($post->ID,'slack_username',true); ?>"/>
							</div>

							<div class="metabox-row">
								<label>Shift: </label>
								<div class="metabox-radio-wrap">
									<input type="radio" value="Day" name="employee_shift" <?php echo (get_post_meta($post->ID,'employee_shift',true) == "Day" ? "checked" : "") ?> > <span>Day</span>
								</div>
								<div class="metabox-radio-wrap">
									<input type="radio" value="Night" name="employee_shift" <?php echo (get_post_meta($post->ID,'employee_shift',true) == "Night" ? "checked" : "") ?> > <span>Night</span>
								</div>
							</div>
						</div><!-- end of left box -->
						<div class="metabox-right">
						
							<div class="metabox-row" data-connection="primary">
								<label>Profile Photo:</label>
								<input class="photo-field" name="employee_photo" type="hidden"  value="<?php echo get_post_meta($post->ID,'employee_photo',true); ?>"/>
								<div class="photo-holder primary-photo-holder">
									<?php 
										if( get_post_meta($post->ID,'employee_photo',true) != "" ) {

											$image = wp_get_attachment_image( get_post_meta($post->ID,'employee_photo',true), 'employee_photo');
											echo $image;
							
										}else {
											echo '<canvas></canvas>';
										}
									?>
								</div>
								<div class="logo-controls">
									<a data-title="Upload Profile Photo" data-connection="primary" href="javascript:;" class="meta-button upload">Upload Photo</a>
									<a data-connection="primary" href="javascript:;" class="meta-button delete">Remove Photo</a>
								</div>
							</div>

							<div class="metabox-row" data-connection="secondary">
								<label>Secondary Photo:</label>
								<input class="photo-field" name="employee_secondary_photo" type="hidden"  value="<?php echo get_post_meta($post->ID,'employee_secondary_photo',true); ?>"/>
								<div class="photo-holder secondary-photo-holder">
									<?php 
										if( get_post_meta($post->ID,'employee_secondary_photo',true) != "" ) {

											$image = wp_get_attachment_image( get_post_meta($post->ID,'employee_secondary_photo',true), 'employee_photo');
											echo $image;
							
										}else {
											echo '<canvas></canvas>';
										}
									?>
								</div>
								<div class="logo-controls">
									<a data-title="Upload Secondary Photo" data-connection="secondary" href="javascript:;" class="meta-button upload">Upload Photo</a>
									<a data-connection="secondary" href="javascript:;" class="meta-button delete">Remove Photo</a>
								</div>
							</div>
						</div><!-- end of right box -->
					</div>

					<div class="metabox-row">
						<label>About Employee: <em></em></label><br>
						<?php
							$content = get_post_meta($post->ID, 'employee_about', true);
							$empl_id = 'employee_about';

							$settings = array( 
								'wpautop' => true,
							);
							wp_editor( $content, $empl_id, $settings );
						?>
					</div>

				</div>

			<?php
		}

		function employee_meta_boxes_save( $post_id ) {

		  	if (! isset( $_POST['employee_meta_nonce'])) {
				return;
		  	}

		  	if (! wp_verify_nonce( $_POST['employee_meta_nonce'], 'employee_meta' )) {
		    	return;
		  	}

			if (defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		    	return;
			}

			$employee_photo = sanitize_text_field( $_POST[ 'employee_photo' ] );
			$employee_secondary_photo = sanitize_text_field( $_POST[ 'employee_secondary_photo' ] );

			$employee_last_name = sanitize_text_field( $_POST[ 'employee_last_name' ] );
			$employee_first_name = sanitize_text_field( $_POST[ 'employee_first_name' ] );
			$employee_email_address = sanitize_text_field( $_POST[ 'employee_email_address' ] );
			$employee_extension = sanitize_text_field( $_POST[ 'employee_extension' ] );
			$employee_position = sanitize_text_field( $_POST[ 'employee_position' ] );
			$employee_designation = sanitize_text_field( $_POST[ 'employee_designation' ] );
			$employee_shift = sanitize_text_field( $_POST[ 'employee_shift' ] );
			$slack_username = sanitize_text_field( $_POST[ 'slack_username' ] );
			$division = $_POST[ 'divisions' ];

			$employee_about = $_POST[ 'employee_about' ];

			update_post_meta( $post_id, 'employee_photo', $employee_photo );
			update_post_meta( $post_id, 'employee_secondary_photo', $employee_secondary_photo );

			update_post_meta( $post_id, 'employee_last_name', $employee_last_name );
			update_post_meta( $post_id, 'employee_first_name', $employee_first_name );
			update_post_meta( $post_id, 'employee_email_address', $employee_email_address );
			update_post_meta( $post_id, 'employee_extension', $employee_extension );
			update_post_meta( $post_id, 'employee_position', $employee_position );
			update_post_meta( $post_id, 'employee_designation', $employee_designation );
			update_post_meta( $post_id, 'employee_shift', $employee_shift );
			update_post_meta( $post_id, 'slack_username', $slack_username );

			update_post_meta( $post_id, 'division', $division);

			update_post_meta( $post_id, 'employee_about', $employee_about);			

		}


		/* Custom Post Columns */
		function tdp_employee_custom_columns( $columns ) {

			$columns = array(
				'cb' => '<input type="checkbox" />',
				'title' =>  __( 'Title' ),
				'name' => __( 'Name' ),
				'email_address' => __( 'Email Address' ),
				'designation' => __( 'Designation' ),
				'extension' => __( 'Extension Number' ),
				'date' => __( 'Date' )
			);

			return $columns;
		}

		/* Custom Post Column Contents */
		function my_manage_tdp_employee_columns( $column, $post_id ) {
			global $post;

			switch( $column ) {

				case 'name' :

					$employee_name = get_post_meta( $post_id, 'employee_first_name', true ) . ' ' . get_post_meta( $post_id, 'employee_last_name', true );

					if ( empty( $employee_name ) )
						echo __( ' ' );
					else
						echo $employee_name;
					
					break;

				case 'email_address' :

					$employee_email_address = get_post_meta( $post_id, 'employee_email_address', true );

					if ( empty( $employee_email_address ) )
						echo __( ' ' );
					else
						echo '<a target="_blank" href="mailto:'.$employee_email_address.'">'.$employee_email_address.'</a>';
					break;

				case 'designation' :

					$designation = get_post_meta( $post_id, 'employee_designation', true );

					if ( empty( $designation ) )
						echo __( '--' );
					else
						echo $designation;
					break;

				case 'extension' :

					$employee_extension = get_post_meta( $post_id, 'employee_extension', true );

					if ( empty( $employee_extension ) )
						echo __( '--' );
					else
						echo $employee_extension;
					break;
				
				default :
					break;
			}
		}

		/* Make Column Sortable */
		function tdp_employee_sortable_columns( $columns ) {

			$columns['name'] = 'name';
			$columns['designation'] = 'designation';
			$columns['date'] = 'date';

			return $columns;
		}

		// Change placeholder of title
		function wpb_change_title_text( $title ){
		     $screen = get_current_screen();

		     if  ( 'clients_db' == $screen->post_type ) {
		          $title = 'Department Head';
		     }
		     return $title;
		}


	}


	class archive_attributes {
		function __construct() {

			// enqueue assets
			add_action('admin_enqueue_scripts', array($this, 'load_assets') );

			// create new forms for ADD and EDIT category
			add_action( 'departments_add_form_fields', array($this, 'create_extra_departments_fields') );
			add_action( 'departments_edit_form_fields', array($this, 'edit_extra_departments_fields') );

			// save modified and newly created blog
			add_action( 'create_departments', array($this, 'save_extra_department_fileds') );
			add_action( 'edited_departments', array($this, 'save_extra_department_fileds') );
			
		}

		function load_assets($hook) {

			wp_enqueue_media();
	    	if ( 'term.php' != $hook && 'edit-tags.php' != $hook) {
	            return;
	        }

		 	wp_enqueue_script( 'wp-admin-custom-scripts', get_stylesheet_directory_uri(). '/lib/admin-scripts.js' );
		 	wp_enqueue_style( 'wp-admin-custom-styles', get_stylesheet_directory_uri(). '/lib/admin-styles.css' );

		}

		function create_extra_departments_fields() {
			$curr_screen = get_current_screen();
		    if( $curr_screen->id != "edit-departments" && $curr_screen->post_type != "tdp_employee" ) {
		    	return;
		    }

			?>


				<div class="form-field">
					<div scope="row" valign="top">
						<label for="term_meta[department_logo]">Department Logo</label>
					</div>
					<div>
						<div class="department-logo-wrap"></div>
						<input type="text" name="term_meta[department_logo]" id="term_meta[department_logo]" value="<?php echo $cat_meta['department_logo'] ? $cat_meta['department_logo'] : ''; ?>" class="department_logo_category"><br />

						<div class="archive-btn-holder">
							<a href="#" class="add-new-h2 department_logo_category_upload">Select Logo</a>
							<a href="#" class="add-new-h2 department_logo_category_remove">Remove Logo</a>
						</div>
				    	<span class="description">Custom Deparment Logo</span>
			        </div>
				</div>

				<!-- <div class="form-field">
					<div scope="row" valign="top">
						<label for="term_meta[bod-box]">Board of Director Box</label>
					</div>
					<div>
						<input class="bod-chk" type="checkbox" name="term_meta[bod-box]" id="term_meta[bod-box]" value="yes"> Hide Board of Director Box?<br />
				    	<span class="description">Option to Hide Board of Director Box in selected departments page</span>
			        </div>
				</div> -->

			<?php
		}

		function edit_extra_departments_fields( $tag ) {
		    $cat_meta = get_option( "category_" . $tag->term_id );

		    $curr_screen = get_current_screen();
		    if( $curr_screen->id != "edit-departments" && $curr_screen->post_type != "tdp_employee" ) {
		    	return;
		    }

			?>

			<tr class="form-field">
				<th scope="row" valign="top">
					<label for="term_meta[department_logo]">Department Logo</label>
				</th>
				<td>
					<div class="department-logo-wrap">
						<?php 
							echo ($cat_meta['department_logo'] ? '<img src="'.$cat_meta['department_logo'].'">' : '');
						?>
					</div>
					<input type="text" name="term_meta[department_logo]" id="term_meta[department_logo]" value="<?php echo $cat_meta['department_logo'] ? $cat_meta['department_logo'] : ''; ?>" class="department_logo_category"><br />
					<div class="archive-btn-holder">
						<a href="#" class="add-new-h2 department_logo_category_upload">Upload/Update Logo</a>
						<a href="#" class="add-new-h2 department_logo_category_remove">Remove Logo</a>
					</div>
			    	<span class="description">Custom Deparment Logo</span>
		        </td>
			</tr>

			<!-- <tr class="form-field">
				<th scope="row" valign="top">
					<label for="term_meta[bod-box]">Board of Director Box</label>
				</th>
				<td>
					<input type="hidden" class="bod-txt" name="term_meta[bod-box]" id="term_meta[bod-box]" <?php echo $cat_meta['bod-box'] ? $cat_meta['bod-box'] : ''; ?> >

					<input type="checkbox" class="bod-chk" name="term-chk" id="ter-chk" <?php echo $cat_meta['bod-box']=="yes" ? "checked" : ''; ?> > Hide Board of Director Box?<br />
			    	<span class="description">Option to Hide Board of Director Box in selected departments page</span>
		        </td>
			</tr> -->

			
			

			<?php
		}


		function save_extra_department_fileds( $term_id ) {
		    if ( isset( $_POST['term_meta'] ) ) {
		        $cat_meta = get_option( "category_" . $term_id );
		        $cate_term = array_keys( $_POST['term_meta'] );
		        foreach ( $cate_term as $key ){
		            if ( isset( $_POST['term_meta'][$key] ) ){
		                $cat_meta[$key] = $_POST['term_meta'][$key];
		            }else {
		            	if($key == "bod-box")
		            		$cat_meta[$key] = "off";
		            }
		        }

		        //save to option array
		        update_option( "category_" . $term_id, $cat_meta );
		    }
		}

	}

	class division_post_types {
		function __construct(){

			//register post type
			add_action( 'init', array($this, 'register_division_post_types') );

			// custom metabox for job post type
			add_action( 'add_meta_boxes',  array( $this, 'division_metabox') );
			
			// update post type
			add_filter( 'save_post', array( $this, 'division_metabox_save'));

			// Custom Post Columns
			add_filter( 'manage_edit-division_content_columns', array($this,'division_content_custom_columns') ) ;
			add_action( 'manage_division_content_posts_custom_column', array($this,'my_manage_division_content_columns'), 10, 2 );
			add_filter( 'manage_edit-division_content_sortable_columns', array($this, 'division_content_sortable_columns') );

		}

		function register_division_post_types() {

			register_post_type('division_content',
				array(
					'labels' => array(
						'name' => 'Divisions',
						'singular_name' => 'Division',
						'add_new' => 'Add Division',
						'add_new_item' => 'Add Division',
						'edit_item' => 'Edit Division',
						'new_item' => 'New Division',
						'view_item' => 'View Division',
						'search_items' => 'Search Division',
						'not_found' =>  'Division Not Found',
						'not_found_in_trash' => 'Nothing found in the Trash',
						'parent_item_colon' => ''
					),
					'public' => false,
					'description' => 'Division Set',
					'publicly_queryable' => false,
					'show_ui' => true,
					'query_var' => true,
					'menu_icon' => 'dashicons-media-document',
					'rewrite' => true,
					'capability_type' => 'post',
					'hierarchical' => false,
					'menu_position' => 27,
					'supports' => array('title', 'editor', 'revisions')
				)
			);

		}

		function division_metabox() {
			add_meta_box( "division_meta", "Division Settings", array($this, 'division_metabox_function'), "division_content", "side", "high" );
		}

		function division_metabox_function($post){

			wp_nonce_field( 'division_meta', 'division_meta_nonce', true, true );
			?>

				<div class="metabox-holder">

					<div class="metabox-row">
						<label style="font-weight:normal">Slug: <span style="color:red">*</span></label>
						
						<input class="divi-slug" type="text" name="division_slug" value="<?php echo get_post_meta($post->ID,'division_slug',true); ?>"/>						

					</div>

				</div>

			<?php
		}

		function division_metabox_save( $post_id ) {

		  	if (! isset( $_POST['division_meta_nonce'])) {
				return;
		  	}

		  	if (! wp_verify_nonce( $_POST['division_meta_nonce'], 'division_meta' )) {
		    	return;
		  	}

			if (defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		    	return;
			}

			$division_slug = sanitize_text_field( $_POST[ 'division_slug' ] );
			update_post_meta( $post_id, 'division_slug', $division_slug );

		}

		/* Custom Post Columns */
		function division_content_custom_columns( $columns ) {

			$columns = array(
				'cb' => '<input type="checkbox" />',
				'title' =>  __( 'Title' ),
				'division' => __( 'Slug' ),
				'date' => __( 'Date' )
			);

			return $columns;
		}

		/* Custom Post Column Contents */
		function my_manage_division_content_columns( $column, $post_id ) {
			global $post;

			switch( $column ) {

				case 'division' :

					$slug = get_post_meta( $post_id, 'division_slug', false );

					if ( empty( $slug ) )
						echo __( ' ' );
					else
						echo implode(',', $slug);
					
					break;

				default :
					break;
			}

		}

		/* Make Column Sortable */
		function division_content_sortable_columns( $columns ) {

			$columns['title'] = 'title';
			$columns['slug'] = 'slug';
			$columns['date'] = 'date';

			return $columns;
		}
	}

	class template_post_types {

		public $__employee_list;
		public $__department_list;

		function __construct(){


			//register post type
			add_action( 'init', array($this, 'register_template_post_types') );

			// custom metabox for job post type
			add_action( 'add_meta_boxes',  array( $this, 'template_metabox') );
			
			// update post type
			add_filter( 'save_post', array( $this, 'template_metabox_save'));
			add_filter( 'save_post', array( $this, 'template_metabox_hierarchy_save'));



			// Custom Post Columns
			add_filter( 'manage_edit-template_content_columns', array($this,'template_content_custom_columns') ) ;
			add_action( 'manage_template_content_posts_custom_column', array($this,'my_manage_template_content_columns'), 10, 2 );
			add_filter( 'manage_edit-template_content_sortable_columns', array($this, 'template_content_sortable_columns') );

		}

		function register_template_post_types() {

			register_post_type('template_content',
				array(
					'labels' => array(
						'name' => 'Department Templates',
						'singular_name' => 'Department Template',
						'add_new' => 'Add Department Template',
						'add_new_item' => 'Add Department Template',
						'edit_item' => 'Edit Department Template',
						'new_item' => 'New Department Template',
						'view_item' => 'View Template',
						'search_items' => 'Search Template',
						'not_found' =>  'Template Not Found',
						'not_found_in_trash' => 'Nothing found in the Trash',
						'parent_item_colon' => ''
					),
					'public' => false,
					'description' => 'Custom Template contents for departments',
					'publicly_queryable' => false,
					'show_ui' => true,
					'query_var' => true,
					'menu_icon' => 'dashicons-media-document',
					'rewrite' => true,
					'capability_type' => 'post',
					'hierarchical' => false,
					'menu_position' => 27,
					'supports' => array('title', /*'editor', 'revisions'*/)
				)
			);

		}

		function template_metabox() {
			add_meta_box( "template_meta", "Template Settings", array($this, 'template_metabox_function'), "template_content", "side", "high" );

			add_meta_box( "template_meta_hierarchy", "Deparment Hierarchy", array($this, 'template_metabox_hierarchy_function'), "template_content", "normal", "high" );

			add_meta_box( "template_meta_hierarchy_view", "Deparment Hierarchy Demo", array($this, 'template_metabox_hierarchy_view_function'), "template_content", "normal", "high" );
		}



		function template_metabox_function($post){

			wp_nonce_field( 'template_meta', 'template_meta_nonce', true, true );

			// get list of departments
			$taxonomy = "departments";
			$terms = get_terms($taxonomy, array(
			       "orderby"    => "name",
			       "order"		=> "ASC",
			       "hide_empty" => false
			   )
			);

            wp_reset_postdata();

			$custom_templates = get_posts(
            	array( 
            		'showposts' => -1,
                    'post_type' => 'template_content',
                    'post_status' => 'publish',
                )
            );

            wp_reset_postdata();

			$template_division_list = get_posts(
            	array( 
            		'showposts' => -1,
                    'post_type' => 'division_content',
                    'post_status' => 'publish',
                )
            );

            wp_reset_postdata();

            $filtered_list_of_departments = array();
            foreach ($custom_templates as $template) {
            	array_push($filtered_list_of_departments ,get_post_meta($template->ID,'template_department',true));
            }

			?>

				<div class="metabox-holder">

					<div class="metabox-row">
						<label style="font-weight:normal">Department: <span style="color:red">*</span></label>
						<select id="template_department" name="template_department" data-post-id="<?php echo $post->ID; ?>">
							<option value="0">Select</option>
							<?php

								foreach ($terms as $term ) {

									$remove_entry = array(get_post_meta($post->ID,'template_department',true));

									$filtered_list_of_departments = array_diff($filtered_list_of_departments,$remove_entry);

									//if( !in_array($term->term_id, $filtered_list_of_departments) ) {
										echo '<option value="'.$term->term_id.'"'.( get_post_meta($post->ID,'template_department',true) == $term->term_id ? 'selected' : '').'>'.$term->name.'</option>';
									//}
			
								}

							?>
						</select>
					</div>
					<div class="metabox-row">
						<label style="font-weight:normal">Use this template? <span style="color:red">*</span></label>
						<div class="metabox-radio-wrap">
							<input class="rdp-activate" type="radio" value="Active" name="active_template" <?php echo (get_post_meta($post->ID,'active_template',true) == "Active" ? "checked" : "") ?> > <span>Activate</span>
						</div>
						<div class="metabox-radio-wrap">
							<input class="rdo-deactivate" type="radio" value="Inactive" name="active_template" <?php echo (get_post_meta($post->ID,'active_template',true) == "Inactive" ? "checked" : "") ?> > <span>Deactivate</span>
						</div>
					</div>

					<div class="metabox-row">
						<label style="font-weight:normal">Division: </label>

						<select id="template_division" name="template_division" data-post-id="<?php echo $post->ID; ?>">
							<option value="">Select</option>
							<?php

								
								foreach ($template_division_list as $term_division ) {

									$division_temp = get_post_meta($term_division->ID,'division_slug',true);

									echo '<option value="'.$division_temp.'"'.( $division_temp == get_post_meta($post->ID,'template_division',true) ? 'selected' : '').'>'.$term_division->post_title.'</option>';
									
								}

							?>
						</select>
					</div>

				</div>

			<?php
		}

		function template_metabox_save( $post_id ) {

		  	if (! isset( $_POST['template_meta_nonce'])) {
				return;
		  	}

		  	if (! wp_verify_nonce( $_POST['template_meta_nonce'], 'template_meta' )) {
		    	return;
		  	}

			if (defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		    	return;
			}

			$department_sel = sanitize_text_field( $_POST[ 'template_department' ] );

			$active_sel = sanitize_text_field( $_POST[ 'active_template' ] == "" ? "Deactivate" : $_POST[ 'active_template' ] );

			$template_division = sanitize_text_field( $_POST[ 'template_division' ] );

			update_post_meta( $post_id, 'template_department', $department_sel );
			update_post_meta( $post_id, 'active_template', $active_sel );
			update_post_meta( $post_id, 'template_division', $template_division );

		}


		/* Metabox for Hierarchy */
		function template_metabox_hierarchy_view_function($post){
			?>
				<div class="metabox-row">
					<label style="font-weight:normal">Live Demo: <em>This is not the actual UI showned in frontend.</em></label>
					<p>
						Tips: You can pan to see exceeding areas of chart by clicking and holding on the whitespace area of the chart and move your mouse in any direction.<br>
					</p>
					<div class="text-draft"></div>
				</div>
			<?php
		}

		function template_metabox_hierarchy_function($post){
			wp_nonce_field( 'template_meta_hierarchy', 'template_meta_hierarchy_nonce', true, true );

			?>

			<div class="metabox-row">
				<label style="font-weight:normal">Hierarchy Level: </label>
				<p>Tips: You can drag the item ro re-arrange. (Items can only be moved within the group)</p>
				<?php

					if ( get_post_meta($post->ID,'template_hierarchy_text',true) == "" || get_post_meta($post->ID,'template_hierarchy_text',true) == "[]" || get_post_meta($post->ID,'template_hierarchy_text',true) == "[{}]") {
						echo '<div class="metabox-list">
								   <ul id="top-level">
									<li class="add-new top-level">
										<span class="h-add">Add Top Level</span>
									</li>
								  </ul>
							  </div>';
					}else {

						$hierarchy = get_post_meta($post->ID,'template_hierarchy_text',true);
						$hierarchy = json_decode($hierarchy);

						// do get post list
						$this->post_types_obj();

						?>

						<div class="metabox-list">
							<?php echo $this->parse_json_list( $hierarchy );  ?>
						</div>

						<?php

					}
				?>
			</div>

			<div class="metabox-row metabox-controls">
			<a href="javascript:;" class="meta-button scroll-demo">Scroll to Demo</a>
				<a href="javascript:;" class="meta-button scroll-hierarchy">Scroll to Hierarchy</a>
				<a href="javascript:;" class="meta-button scroll-publish">Scroll to Publish</a>
			</div>

			<div class="metabox-popup-wrapper">
				<div class="metabox-popup-bg"></div>
				<div class="metabox-content">
					<?php $this->post_types_obj(); ?>
					<div class="metabox-holder">
						<div class="metabox-row">
							<input name="static-box" type="checkbox"> Use Static Box?
							<div class="metabox-row static-box-details">
								<input name="static-box-text" type="text" placeholder="Enter Custom Box Title">
							</div>
						</div>
						<div class="metabox-row">
							<label>Select Data From Database:</label>
							<select class="data-type" name="data-type">
								<option value="employee">Employee</option>
								<option value="department">Department</option>
							</select>

							<input name="search_in_data_list" type="text" placeholder="Search" autocomplete="off">

							<div class="data-list-wrap employee">
								<select name="list_employee" size="12">
									<?php
										foreach ($this->__employee_list as $key => $value) {
											echo '<option data-identifier="'.$value->post_title.'" value="'.$value->ID.'">'.$value->post_title.'</option>';
										}
									?>
								</select>
							</div>

							<div class="data-list-wrap department">
								<select name="list_department" size="12">
									<?php
										foreach ($this->__department_list as $key => $value) {
											echo '<option data-identifier="'.$value->name.'" value="'.$value->term_id.'">'.$value->name.'</option>';
										}
									?>
								</select>
							</div>

						</div>
				
						<div class="metabox-row metabox-controls">
							<a href="javascript:;" class="meta-button confirm-selection">Select</a>
							<a href="javascript:;" class="meta-button close-popup">Close</a>
						</div>

					</div>
				</div>
			</div>

			<div class="metabox-row hidden-meta-box" >
				<textarea name="template_hierarchy_text"><?php echo get_post_meta($post->ID,'active_template',true) != "" ? get_post_meta($post->ID,'template_hierarchy_text',true) : '' ?></textarea>
			</div>

			<?php
		}

		
		/* Query Post Details */
		function post_types_obj(){
			/*public $__employee_list;
			public $__department_list;*/

			// get list of departments (this should be located in data_init but the taxonomy appears invalid)
			$this->__department_list = get_terms("departments", array(
			       "orderby"    => "name",
			       "order"		=> "ASC",
			       "hide_empty" => false
			   )
			);
            wp_reset_postdata();

			$this->__employee_list = get_posts(
            	array( 
            		'showposts' => -1,
            		"orderby"    => "name",
            		"order"		=> "ASC",
                    'post_type' => 'tdp_employee',
                    'post_status' => 'publish',
                )
            );
            wp_reset_postdata();

		}

		/* Parse JSON Data to List Items */
		function parse_json_list( $obj ) {

			$html = "<ul id=\"top-level\">";
			foreach ($obj as $key => $value) {
				$html .= '<li data-id="'.$value->id.'"><span>'.$this->json_data_meta_value($value->id).'</span>' . ( !empty($value->children) ? $this->parse_json_data_list_child($value->children) : '') . '</li>';
			}

			$html .= "</ul>";

			return $html;
		}

		function parse_json_data_list_child( $obj ) {

			$html = "<ul>";

			foreach ($obj as $key => $value) {

				$html .= '<li data-id="'.$value->id.'"><span>'.$this->json_data_meta_value($value->id).'</span>' . ( !empty($value->children) ? $this->parse_json_data_list_child($value->children) : '' ) . '</li>';

			}

			$html .= "</ul>";
			
			return $html;
		}

		function json_data_meta_value($id) {
			$output = '';

			if ( substr($id,0,1) == "d" ){
				// get department name
				foreach ($this->__department_list as $value) {
					if ( $value->term_id == substr($id,1) ){
						$output = $value->name;
					}
				}
			} else if( substr($id,0,1) == "#"  ){

				$output = substr($id,1);
				$output = explode("-",$output);
				$output = implode(" ",$output);

			} else {
				// get employee post type title
				foreach ($this->__employee_list as $value) {
					if ( $value->ID == $id ){
						$output = $value->post_title;
					}
				}
				
			}

            return !empty($output) ? $output : '<i style="color:red">'.$id.' is not registered as post/term.</i>';
		}


		/* End of JSON Data Parsing */



		/* save hierarchy metabox */
		function template_metabox_hierarchy_save( $post_id ) {

		  	if (! isset( $_POST['template_meta_hierarchy_nonce'])) {
				return;
		  	}

		  	if (! wp_verify_nonce( $_POST['template_meta_hierarchy_nonce'], 'template_meta_hierarchy' )) {
		    	return;
		  	}

			if (defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		    	return;
			}

			$hierarchy = sanitize_text_field( $_POST[ 'template_hierarchy_text' ] );
			update_post_meta( $post_id, 'template_hierarchy_text', $hierarchy );

		}


		/* Custom Post Columns */
		function template_content_custom_columns( $columns ) {

			$columns = array(
				'cb' => '<input type="checkbox" />',
				'title' =>  __( 'Title' ),
				'department' => __( 'Department' ),
				'division' => __( 'Division' ),
				'status' => __( 'Status' ),
				'date' => __( 'Date' )
			);

			return $columns;
		}

		/* Custom Post Column Contents */
		function my_manage_template_content_columns( $column, $post_id ) {
			global $post;

			switch( $column ) {

				case 'department' :

					$department = get_post_meta( $post_id, 'template_department', true );

					$department = get_term_by('id', $department, 'departments');

					if ( empty( $department->name ) )
						echo __( ' ' );
					else
						echo '<a href="'.DEPARTMENTS_TAXONOMY.$department->slug.'?division='. ( get_post_meta( $post_id, 'template_division', true ) != "" ? get_post_meta( $post_id, 'template_division', true ) : '') .'" target="_blank">'. $department->name . '</a>';
					
					break;

				case 'division' :

					$department = get_post_meta( $post_id, 'template_division', true );

					if ( empty( $department ) )
						echo __( ' ' );
					else
						echo $department;
					
					break;

				case 'status' :

					$status = get_post_meta( $post_id, 'active_template', true );

					if ( empty( $status ) )
						echo __( ' ' );
					else
						echo '<span style="color: '.($status == "Active" ? "green" : "red").'">'.$status.'</span>';
					break;

				default :
					break;
			}
		}

		/* Make Column Sortable */
		function template_content_sortable_columns( $columns ) {

			$columns['title'] = 'title';
			$columns['department'] = 'department';
			$columns['division'] = 'division';
			$columns['status'] = 'status';
			$columns['date'] = 'date';

			return $columns;
		}

	
	}


	class ajax_functions {
		function __construct(){
			add_action( 'wp_ajax_get_profiles', array($this, 'prefix_ajax_get_profiles') );
			add_action( 'wp_ajax_nopriv_get_profiles', array($this, 'prefix_ajax_get_profiles') );
		}

		function prefix_ajax_get_profiles() {
		    // Handle request then generate response using WP_Ajax_Response

			$output = false;

			if( isset($_POST['data']) && $_POST['data'] != "" ){

				$post_id = $_POST['data'];


					// Employee
					$employee_photo = wp_get_attachment_image_src( get_post_meta($post_id,'employee_photo',true), 'employee_photo');

					$employee_name = get_post_meta($post_id,'employee_first_name')[0] . ' ' . get_post_meta($post_id,'employee_last_name')[0];
					$employee_designation = get_post_meta($post_id,'employee_designation')[0];
					$employee_email_address = get_post_meta($post_id,'employee_email_address')[0];
					$employee_extension = get_post_meta($post_id,'employee_extension')[0];
					$employee_shift = get_post_meta($post_id,'employee_shift')[0];

					$employee_position = get_post_meta($post_id,'employee_position')[0];

					$employee_about = get_post_meta($post_id,'employee_about')[0];

					$output = '<div class="employee-post-wrap row">
								<div class="col-xs-12 col-md-3 employee-post-photo">
									 <img src="'.($employee_photo[0] == "" ? get_stylesheet_directory_uri()."/images/placeholder.png" : $employee_photo[0]).'" alt="'.$employee_name.'" width="500" height="500">
								</div>
								<div class="col-xs-12 col-md-9 employee-post-details">
									<h1 class="entry-title">
										'.$employee_name.'
										<span>'.$employee_designation.'</span>
									</h1>
									
									<div class="employee-detailed-info">
										
										'. ( $employee_email_address!="" ? '<div class="emp-email">
												<i class="ai-font-envelope"></i> <a href="mailto:'.$employee_email_address.'">'.$employee_email_address .'</a>
											</div>' : '' ) . ( $employee_extensio !="" ? '<div class="emp-email">
												<i class="ai-font-phone-a"></i> '.$employee_extension .'
											</div>' : '') . ( $employee_shift !="" ? '<div class="emp-email">
												<i class="ai-font-time-a"></i> '. $employee_shift .' Shift
											</div>' : '') .'
										
									</div>

									<div class="employee_more_details">'. ( !empty( $employee_about ) ? wpautop( do_shortcode($employee_about) ) : '' ) .'
									</div>

								</div>
							</div>';

				
			}

			echo json_encode( $output );

		    // Don't forget to stop execution afterward.
		    wp_die();
		}
	}

	new employee_post_types();
?>