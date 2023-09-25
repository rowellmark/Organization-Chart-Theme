<?php
	
	$queried_object = get_queried_object();
	$employee_list = get_employee_under_dep($queried_object->term_id);

	$dep_head = get_employee_by_field( "position", "Department Head", $employee_list );
	$tl = get_employee_by_field( "position", "Team Leader", $employee_list );
	$atl = get_employee_by_field( "position", "Assistant Team Leader", $employee_list );
	$members = get_employee_by_field( "position", "Member", $employee_list );

	$top_lvl = ( !empty($dep_head) ? $dep_head : ( !empty($tl) ? $tl : ( !empty($atl) ? $atl : '' ) ) );


?>

<?php
	
	if( !empty($top_lvl) ) {
		?>

		<ul id="org-list">
			<?php

				if( sizeof($top_lvl) == 1 ) {
					?>

					<li id="<?php echo $employee_list[$dep_head]->ID; ?>"><?php 
							echo '<span>'.$employee_list[$top_lvl[0]]->post_title.'</span> ' . '<em>'.get_post_meta($employee_list[$top_lvl[0]]->ID, 'employee_designation', true).'</em>'; 
						?>
						<ul>
							<?php
								if ( sizeof($atl) > 0 ){
									foreach($atl as $key => $leveled){
										?>
										<li><?php echo '<span>'.$employee_list[$leveled]->post_title.'</span> <em>'.get_post_meta($employee_list[$leveled]->ID, 'employee_designation', true).'</em>'; ?>
											<ul>
												<?php
													foreach ($members as $key => $member) {
														if ( $employee_list[$member]->employee_shift == $employee_list[$leveled]->employee_shift ){
															echo '<li><span>'.$employee_list[$member]->post_title.'</span> <em>'.get_post_meta($employee_list[$member]->ID, 'employee_designation', true).'</em></li>';
														}
													}
												?>
											</ul>
										</li>
										<?php
										
									}
								}else {
									foreach ($members as $key => $member) {
										echo '<li><span>'.$employee_list[$member]->post_title.'</span> <em>'.get_post_meta($employee_list[$member]->ID, 'employee_designation', true).'</em></li>';
									}
								}
							?>
						</ul>
					</li>

					<?php
				}else {
					// row level ** Create Top Level using Department ID
					?>

					<li data-class="node-static" data-link="#"><?php echo '<span>'.$queried_object->name.'</span>'; ?>
						<ul>
							<?php
								foreach($top_lvl as $key => $leveled){
									?>
									<li><?php echo '<span>'.$employee_list[$leveled]->post_title.'</span> <em>'.get_post_meta($employee_list[$leveled]->ID, 'employee_designation', true).'</em>'; ?>
										<ul>
											<?php
												foreach ($members as $key => $member) {
													if ( $employee_list[$member]->employee_shift == $employee_list[$leveled]->employee_shift ){
														echo '<li><span>'.$employee_list[$member]->post_title.'</span> <em>'.get_post_meta($employee_list[$member]->ID, 'employee_designation', true).'</em></li>';
													}
												}
											?>
										</ul>
									</li>
									<?php
									
								}
							?>
						</ul>
					</li>

					<?php
				}

			?>
		</ul>

		<div id="chart-container"></div>

		<?php 
	}else {
		echo 'Please appoint Department Head, TL or ATL in the department team.';
	}

?>

			




