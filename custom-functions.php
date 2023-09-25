<?php
	
	// get post terms
	function get_child_post_terms($id){

		unset($terminals);
		unset($terminal_id);

		$departments = get_the_terms($id,'departments');

		$terminals = array();
		$terminal_id = array();

		foreach ($departments as $key=>$dep) {

			/* Get department ID */
			$id = $dep->term_id;

			/* Store all departments in a temp var */
			$others = $departments;

			/* If the employee only belongs to one department, we assume that this department is terminal. */
			if ( count($departments) == 1 ) {
				$terminals[] = $dep->name;
				$terminal_id[] = $dep->term_id;
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
					$terminal_id[] = $dep->term_id;
				}
			}

		}

		return $terminal_id;

	}

	function get_employee_under_dep($dep_id) {

		// return value as array
		$__employee_list = get_posts(
        	array( 
        		'showposts' => -1,
                'post_type' => 'tdp_employee',
                'post_status' => 'publish',
                'tax_query' => array(
                                    array(
                                    'taxonomy' => 'departments',
                                    'terms' => $dep_id,
                                    )
                                )
            )
        );
        wp_reset_postdata();

        return $__employee_list;
	}

	function get_employee_by_field( $field, $field_value, $data ){

		/*
			This function will return key value(index) of data array.
		*/

		$field = strtolower($field);

		$output = Array();

		switch ($field) {
			case 'position':
				foreach ($data as $key => $value) {

					if( get_post_meta($value->ID,'employee_position',true) == $field_value ){
						array_push($output, $key);
					}

				}
				break;

			case 'division':
				# code...
				break;

			case 'shift':
				# code...
				break;
			
			default:
				$output = 'invalid field / value given.';
				break;
		}

		return $output;

	}

	/*function get_department_heads($args, $terms,$posts,$position){
		unset($sets);
		$sets = array();


		//get_term_children( $term, $taxonomy );

		foreach ($args as $post_terms) {

			if ( in_array($post_terms, $terms) ){

				//$termi = get_term_by( 'id', $post_terms, 'departments' );

        		foreach ($posts as $post2) {

        			$post2_terms = get_child_post_terms($post2->ID);
        			$post2_terms = array_unique($post2_terms);

        			$fullname = get_post_meta($post2->ID, 'employee_first_name')[0] . ' ' . get_post_meta($post2->ID, 'employee_last_name')[0];

        			foreach ($post2_terms as $post2_term) {
        				if ( $post2_term == $post_terms && get_post_meta($post2->ID, 'employee_position')[0] == $position ) {
        					$sets[] = $post2->ID;
        				}
        			}
        		}


			}
		}
		return $sets;
	}*/

	function get_department_logo($id) {
		$cat_meta = get_option( "category_" . $id );
		return $cat_meta['department_logo'];
	}

	function get_post_template($id) {
		$posts_array = get_posts(
        	array( 
        		'showposts' => -1,
                'post_type' => 'template_content',
                'post_status' => 'publish',
            )
        );

        wp_reset_postdata();

        $post_meta = array();

        foreach ($posts_array as $post_meta_val) {

        	if( $id == get_post_meta($post_meta_val->ID,'template_department',true) && get_post_meta($post_meta_val->ID,'active_template',true) == "Active" &&  $post_meta_val->post_status == "publish") {

        		array_push( $post_meta, array(
        			'department_id'=> get_post_meta($post_meta_val->ID,'template_department',true), 
        			'active_status'=> get_post_meta($post_meta_val->ID,'active_template',true),
        			'template_id'=> $post_meta_val->ID,
        			'template_content'=> $post_meta_val->post_content,
        			'template_title'=> $post_meta_val->post_title,
        			'template_status'=> $post_meta_val->post_status,
        			'template_division'=> get_post_meta($post_meta_val->ID,'template_division',true)
        		) );

        	}
        	
        }

        return $post_meta;
        
	}

	
	function get_department_list() {
		// get list of departments
		$taxonomy = "departments";
		$__terms = get_terms($taxonomy, array(
		       "orderby"    => "name",
		       "order"		=> "ASC",
		       "hide_empty" => false
		   )
		);

		return $__terms;
	}


	function get_employee_list() {
		$__employee_list = get_posts(
        	array( 
        		'showposts' => -1,
                'post_type' => 'tdp_employee',
                'post_status' => 'publish',
            )
        );
        wp_reset_postdata();

        return $__employee_list;
	}


	/* Parse JSON Data to List Items */
	function parse_json_list( $obj ) {

		$html = "<ul id=\"org-list\">";
		foreach ($obj as $key => $value) {
			$html .= '<li id="'.$value->id.'" '.get_queried_term_link($value->id).'>'.json_data_meta_value($value->id).' ' . ( !empty($value->children) ? parse_json_data_list_child($value->children) : '') . '</li>';
		}

		$html .= "</ul>";

		return $html;
	}

	function parse_json_data_list_child( $obj ) {

		$html = "<ul>";

		foreach ($obj as $key => $value) {

			$html .= '<li id="'.$value->id.'" '. get_data_class($value->id) .' '.get_queried_term_link($value->id).'>'.json_data_meta_value($value->id).' ' . ( !empty($value->children) ? parse_json_data_list_child($value->children) : '' ) . '</li>';

		}

		$html .= "</ul>";
		
		return $html;
	}

	function get_data_class($id) {
		if ( substr($id, 0,1) == "#") {
			return 'data-class="node-static"';
		}	
	}

	function get_queried_term_link($id){

		if ( substr($id, 0,1) == "d") {
			$dep_link_obj = get_term(substr($id, 1),'departments');
			$dep_link_slug = $dep_link_obj->slug;

			return 'data-link="'.site_url().'/departments/'. $dep_link_slug.'"';
		}
	
	}

	function json_data_meta_value($id) {
		$output = '';

		$__department_list = get_department_list();
		$__employee_list = get_employee_list();

		if ( substr($id,0,1) == "d" ){
			// get department name
			foreach ($__department_list as $value) {
				if ( $value->term_id == substr($id,1) ){
					$output = '<span>'. $value->name . '</span>';
				}
			}
		} else if( substr($id,0,1) == "#" ) {
			$output = substr($id,1);
			$output = explode("-",$output);
			$output = implode(" ",$output);

		} else {
			// get employee post type title
			foreach ($__employee_list as $value) {
				if ( $value->ID == $id ){
					$output = '<span>'. $value->post_title . '</span> <em>'. get_post_meta( $value->ID, 'employee_designation', true) .'</em>';
				}
			}
			
		}

        return !empty($output) ? $output : '<i style="color:red">Record Deleted</i>';
	}
	/* End of JSON Data Parsing */

	
?>