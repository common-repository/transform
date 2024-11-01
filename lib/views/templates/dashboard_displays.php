<div id="nxf-error"></div>

<div id="wpbody">
	<div id="wpbody-content" aria-label="Main content" tabindex="0">
		<div class="wrap">
			<h2><?php _e( 'Transform Displays', 'nxf-transform' ) ?> <a href="" onclick="return nxf_new_display_dialog();" class="add-new-h2"><?php _e( 'Add New', 'nxf-transform' ) ?></a><span id="spinner" style="display: none;"> <img src="<?php echo $nxf_dashboard_model->site_url ?>/wp-includes/images/spinner.gif" title="loading popup..." /></span></h2>
			<form id="nxf-displays-list" action="" method="post">
				<div class="tablenav top">
					<div class="alignleft actions bulkactions">
						<select name='action' id='action'>
							<option value='-1' selected='selected'><?php _e( 'Bulk Actions', 'nxf-transform' ) ?></option>
							<option value='delete'><?php _e( 'Delete', 'nxf-transform' ) ?></option>
						</select>
						<input type="submit" name="" id="doaction" class="button action" value="<?php _e( 'Apply', 'nxf-transform' ) ?>"  />
						<span id="spinner-bulk" style="display: none;"> <img src="<?php echo $nxf_dashboard_model->site_url ?>/wp-includes/images/spinner.gif" title="Applying bulk action..." /></span>
					</div>
				
					<br class="clear" />
				</div>
				
				<!-- main table goes here -->
				<table class="wp-list-table widefat fixed">
					<thead>
						<tr>
							<td scope='col' id='cb' class='manage-column column-cb check-column'  style=""><label class="screen-reader-text" for="cb-select-all-1"><?php _e( 'Select All', 'nxf-transform' ) ?></label><input id="nxf-select-all-1" type="checkbox" /></td>
							<th><?php _e( 'Name', 'nxf-transform' ) ?></th>
							<th><?php _e( 'Description', 'nxf-transform' ) ?></th>
							<th><?php _e( 'Feed Type', 'nxf-transform' ) ?></th>
						</tr>
					</thead>
					
					<tfoot>
						<tr>
							<td scope='col' id='cb' class='manage-column column-cb check-column'  style=""><label class="screen-reader-text" for="cb-select-all-1"><?php _e( 'Select All', 'nxf-transform' ) ?></label><input id="nxf-select-all-1" type="checkbox" /></td>
							<th><?php _e( 'Name', 'nxf-transform' ) ?></th>
							<th><?php _e( 'Description', 'nxf-transform' ) ?></th>
							<th><?php _e( 'Feed Type', 'nxf-transform' ) ?></th>
						</tr>
					</tfoot>

					<tbody id="the-list">
						<?php
						if ( $nxf_dashboard_model->displays ) {
							$i = 0;
							foreach ( $nxf_dashboard_model->displays as $display ) {
								$i++;
								$class = $i % 2 == 1 ? "alternate" : "";
						?>
								<tr class="<?php echo $class ?>">
									<th scope="row" class="check-column">
										<label class="screen-reader-text" for="cb-select-2"><?php echo $display->display_name ?></label>
										<input id="cb-select-2" type="checkbox" name="ids[]" value="<?php echo $display->ID; ?>" class="nxf-checkbox" />
										<div class="locked-indicator"></div>
									</th>
									<td>
										<strong><?php echo $display->display_name ?></strong><span id="spinner-<?php echo $display->ID ?>" class="nxf-spinner" style="display: none;"> <img src="<?php echo $nxf_dashboard_model->site_url ?>/wp-admin/images/wpspin_light.gif" title="loading..." /></span>
										<div class="row-actions">
											<span class=''><a href="" onclick="return nxf_edit_display_dialog_content( <?php echo $display->ID ?> );" title="<?php _e( 'Edit this display', 'nxf-transform' ) ?>"><?php _e( 'Edit', 'nxf-transform' ) ?></a> | </span>
											<span class='trash'><a class='submitdelete' title='<?php _e( 'Delete this display', 'nxf-transform' ) ?>' href='?page=transform-listdisplays-handle&id=<?php echo $display->ID ?>'  onclick="if ( confirm( '<?php _e( 'Are you sure you want to delete this display?', 'nxf-transform' ) ?>' ) ) { return nxf_delete_display( <?php echo $display->ID ?> ); } else { return false; }"><?php _e( 'Delete', 'nxf-transform' ) ?></a></span>
										</div>
									</td>
									<td>
										<?php echo $display->display_desc ?>
									</td>
									<td>
										<?php echo $display->feed_type_name ?>
									</td>
								</tr>		
						<?php
							}
						} else {
                                                        echo '<tr><td colspan="4">' . __( 'No displays have been defined for this site', 'nxf-transform' ) . '</td></tr>';
                                                }
						?>
					</tbody>
				</table>
				
				<div class="tablenav bottom">
					<div class="alignleft actions bulkactions">
						<select name='action2' id='action2'>
							<option value='-1' selected='selected'><?php _e( 'Bulk Actions', 'nxf-transform' ) ?></option>
							<option value='delete'><?php _e( 'Delete', 'nxf-transform' ) ?></option>
						</select>
						<input type="submit" name="" id="doaction2" class="button action" value="<?php _e( 'Apply', 'nxf-transform' ) ?>"  />
					</div>

					<br class="clear" />
				</div>
			
			</form>
		</div>
	</div>
</div>

<div id='newdisplay'>
<?php _e( 'Loading...', 'nxf-transform' ) ?>
</div>

<div id='editdisplay'>
<?php _e( 'Loading...', 'nxf-transform' ) ?>
</div>

<script type="text/javascript">
jQuery( document ).ready( function() {
	jQuery("form").submit(function() { 
        var id = jQuery("input[type=submit][clicked=true]").attr( 'id' );
        if ( id == 'doaction' && jQuery( '#action' ).val() == 'delete' ) {
        	return nxf_bulk_delete( 'displays' );
        } else if (  id == 'doaction2' && jQuery( '#action2' ).val() == 'delete' ) {
        	return nxf_bulk_delete( 'displays' );
        } else { return false; }
    });
    
    jQuery("form input[type=submit]").click(function() {
        jQuery("input[type=submit]", jQuery(this).parents("form")).removeAttr("clicked");
        jQuery(this).attr("clicked", "true");
    });
});
</script>
