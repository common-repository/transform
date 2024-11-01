<div id="nxf-error"></div>

<div id="wpbody">
	<div id="wpbody-content" aria-label="Main content" tabindex="0">
		<div class="wrap">
			<h2><?php _e( 'Transform Instances', 'nxf-transform' ); ?> <a href="" onclick="return nxf_new_instance_dialog();" class="add-new-h2"><?php _e( 'Add New', 'nxf-transform' ); ?></a><span id="spinner" style="display: none;"> <img src="<?php echo $nxf_dashboard_model->site_url ?>/wp-includes/images/spinner.gif" title="loading popup..." /></span></h2>
			<form id="nxf-instances-list" action="" method="post">
				<div class="tablenav top">
					<div class="alignleft actions bulkactions">
						<select name='action' id='action'>
							<option value='-1' selected='selected'><?php _e( 'Bulk Actions', 'nxf-transform' ); ?></option>
							<option value='delete'><?php _e( 'Delete', 'nxf-transform' ); ?></option>
						</select>
						<input type="submit" name="" id="doaction" class="button action" value="<?php _e( 'Apply', 'nxf-transform' ); ?>"  />
						<span id="spinner-bulk" style="display: none;"> <img src="<?php echo $nxf_dashboard_model->site_url ?>/wp-includes/images/spinner.gif" title="Applying bulk action..." /></span>
					</div>
				</div>
				
				<!-- main table goes here -->
				<table class="wp-list-table widefat fixed">
					<thead>
						<tr>
							<td scope='col' id='cb' class='manage-column column-cb check-column'  style=""><label class="screen-reader-text" for="cb-select-all-1"><?php _e( 'Select All', 'nxf-transform' ); ?></label><input id="nxf-select-all-1" type="checkbox" /></td>
							<th><?php _e( 'Instance', 'nxf-transform' ); ?></th>
							<th><?php _e( 'ID', 'nxf-transform' ); ?></th>
							<th><?php _e( 'Feed', 'nxf-transform' ); ?></th>
							<th><?php _e( 'Type', 'nxf-transform' ); ?></th>
							<th><?php _e( 'Display', 'nxf-transform' ); ?></th>
						</tr>
					</thead>
					
					<tfoot>
						<tr>
							<td scope='col' id='cb' class='manage-column column-cb check-column'  style=""><label class="screen-reader-text" for="cb-select-all-1"><?php _e( 'Select All', 'nxf-transform' ); ?></label><input id="nxf-select-all-1" type="checkbox" /></td>
							<th><?php _e( 'Instance', 'nxf-transform' ); ?></th>
							<th><?php _e( 'ID', 'nxf-transform' ); ?></th>
							<th><?php _e( 'Feed', 'nxf-transform' ); ?></th>
							<th><?php _e( 'Type', 'nxf-transform' ); ?></th>
							<th><?php _e( 'Display', 'nxf-transform' ); ?></th>
						</tr>
					</tfoot>

					<tbody id="the-list">
						<?php
						if ( $nxf_dashboard_model->instances ) {
							$i=0;
							foreach ( $nxf_dashboard_model->instances as $instance ) {
								$i++;
								$class = $i % 2 == 1 ? "alternate" : "";
								
								print <<<EOT
								<tr class="$class">
									<th scope="row" class="check-column">
										<label class="screen-reader-text" for="cb-select-2">$instance->instance_name</label>
										<input id="cb-select-2" type="checkbox" name="ids[]" value="$instance->ID" class="nxf-checkbox" />
										<div class="locked-indicator"></div>
									</th>
									<td>
										<strong>$instance->instance_name</strong><span id="spinner-$instance->ID" class="nxf-spinner" style="display: none;"> <img src="$nxf_dashboard_model->site_url/wp-admin/images/wpspin_light.gif" title="loading..." /></span>
										<div class="row-actions">
											<span class=''>
												<a href="" onclick="return nxf_edit_instance_dialog_content( $instance->ID );" title="Edit this instance">Edit</a> | 
											</span>
											<span class='trash'>
												<a class='submitdelete' title='Delete this instance' href='?page=transform-handle&id=$instance->ID' onclick="if ( confirm( 'Any pages using this instance will not show this feed. Are you sure you want to delete this instance?' ) ) { return nxf_delete_instance( $instance->ID ); } else { return false; }">Delete</a>
											</span>
										</div>
									</td>
									<td>
										<strong>$instance->ID</strong>
									</td>
									<td>
										<strong>$instance->feed_name</strong>
									</td>
									<td>
										<strong>$instance->feed_type_name</strong>
									</td>
									<td>
										<strong>$instance->display_name</strong>
									</td>
								</tr>
EOT;
							}
						} else {
                                                        echo '<tr><td colspan="6">' . __( 'No feed instances have been defined for this site', 'nxf-transform' ) . '</td></tr>';
                                                }
						?>
					</tbody>
				</table>
				
				<div class="tablenav bottom">
					<div class="alignleft actions bulkactions">
						<select name='action2' id='action2'>
							<option value='-1' selected='selected'><?php _e( 'Bulk Actions', 'nxf-transform' ); ?></option>
							<option value='delete'><?php _e( 'Delete', 'nxf-transform' ); ?></option>
						</select>
						<input type="submit" name="" id="doaction2" class="button action" value="<?php _e( 'Apply', 'nxf-transform' ); ?>"  />
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<div id='newinstance'>
<?php _e( 'Loading...', 'nxf-transform' ); ?>
</div>

<script type="text/javascript">
jQuery( document ).ready( function() {
	jQuery("form").submit(function() { 
        var id = jQuery("input[type=submit][clicked=true]").attr( 'id' );
        if ( id == 'doaction' && jQuery( '#action' ).val() == 'delete' ) {
        	return nxf_bulk_delete( 'instances' );
        } else if (  id == 'doaction2' && jQuery( '#action2' ).val() == 'delete' ) {
        	return nxf_bulk_delete( 'instances' );
        } else { return false; }
    });
    
    jQuery("form input[type=submit]").click(function() {
        jQuery("input[type=submit]", jQuery(this).parents("form")).removeAttr("clicked");
        jQuery(this).attr("clicked", "true");
    });
});
</script>
