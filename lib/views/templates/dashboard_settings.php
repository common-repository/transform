<div id="nxf-error"></div>

<div id="wpbody">
	<div id="wpbody-content" aria-label="Main content" tabindex="0">
		<div class="wrap">
			<h2><?php _e( 'Transform Settings', 'nxf-transform' ); ?> <a href="" onclick="return nxf_new_setting_dialog();" class="add-new-h2"><?php _e( 'Add New', 'nxf-transform' ); ?></a><span id="spinner" style="display: none;"> <img src="<?php echo $nxf_dashboard_model->site_url ?>/wp-includes/images/spinner.gif" title="loading popup..." /></span></h2>
			
			<form id="nxf-settings-list" action="" method="post">
				<div class="tablenav top">
					<div class="alignleft actions bulkactions">
						<select name='action' id='action'>
							<option value='-1' selected='selected'><?php _e( 'Bulk Actions', 'nxf-transform' ); ?></option>
							<option value='delete'><?php _e( 'Delete', 'nxf-transform' ); ?></option>
						</select>
						<input type="submit" name="" id="doaction" class="button action" value="<?php _e( 'Apply', 'nxf-transform' ); ?>"  />
						<span id="spinner-bulk" style="display: none;"> <img src="<?php echo $nxf_dashboard_model->site_url ?>/wp-includes/images/spinner.gif" title="Applying bulk action..." /></span>
					</div>

					<br class="clear" />
				</div>
				
				<!-- main table goes here -->
				<table class="wp-list-table widefat fixed">
					<thead>
						<tr>
							<td scope='col' id='cb' class='manage-column column-cb check-column'  style=""><label class="screen-reader-text" for="cb-select-all-1"><?php _e( 'Select All', 'nxf-transform' ); ?></label><input id="nxf-select-all-1" type="checkbox" /></td>
							<th><?php _e( 'Name', 'nxf-transform' ); ?></th>
							<th><?php _e( 'Value', 'nxf-transform' ); ?></th>
							<th><?php _e( 'Type', 'nxf-transform' ); ?></th>
						</tr>
					</thead>
					
					<tfoot>
						<tr>
							<td scope='col' id='cb' class='manage-column column-cb check-column'  style=""><label class="screen-reader-text" for="cb-select-all-1"><?php _e( 'Select All', 'nxf-transform' ); ?></label><input id="nxf-select-all-1" type="checkbox" /></td>
							<th><?php _e( 'Name', 'nxf-transform' ); ?></th>
							<th><?php _e( 'Value', 'nxf-transform' ); ?></th>
							<th><?php _e( 'Type', 'nxf-transform' ); ?></th>
						</tr>
					</tfoot>

					<tbody id="the-list">
						<?php
						if ( $nxf_dashboard_model->settings ) {
							$i = 0;
							foreach ( $nxf_dashboard_model->settings as $setting ) {
								$i++;
								$class = $i % 2 == 1 ? "alternate" : "";
								
						?>
								<tr class="<?php echo $class; ?>">
									<th scope="row" class="check-column">
										<label class="screen-reader-text" for="cb-select-2"><?php echo $setting->setting_name; ?></label>
										<input id="cb-select-2" type="checkbox" name="setting[]" value="<?php echo $setting->ID; ?>" class="nxf-checkbox" />
										<div class="locked-indicator"></div>
									</th>
									<td>
										<strong><?php echo $setting->setting_name; ?></strong><span id="spinner-<?php echo $setting->ID ?>" class="nxf-spinner" style="display: none;"> <img src="<?php echo $nxf_dashboard_model->site_url ?>/wp-admin/images/wpspin_light.gif" title="loading..." /></span>
										<div class="row-actions">
											<span class=''>
												<a href="" onclick="return nxf_edit_setting_dialog_content( <?php echo $setting->ID; ?> );" title="<?php _e( 'Edit this setting', 'nxf-transform' ); ?>"><?php _e( 'Edit', 'nxf-transform' ); ?></a> | 
											</span>
											<span class='trash'>
												<a class='submitdelete' title='<?php _e( 'Delete this setting', 'nxf-transform' ); ?>' href='?page=transform-settings-handle&id=<?php echo $setting->ID; ?>'  onclick="if ( confirm( '<?php _e( 'Are you sure you want to delete this setting?', 'nxf-transform' ); ?>' ) ) { return nxf_delete_setting( <?php echo $setting->ID; ?> ); } else { return false; }"><?php _e( 'Delete', 'nxf-transform' ); ?></a>
											</span>
										</div>
									</td>
									<td>
										<?php echo $setting->setting_value; ?>
									</td>
									<td>
										<?php echo $setting->setting_type; ?>
									</td>
								</tr>		
						<?php
							}
						} else {
							echo '<tr><td colspan="4">' . __( 'No settings have been defined for this site', 'nxf-transform' ) . '</td></tr>';
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

					<br class="clear" />
				</div>
			
			</form>
		</div>
	</div>
</div>

<div id='newsetting'>
<?php _e( 'Loading...', 'nxf-transform' ); ?>
</div>

<div id='editsetting'>
<?php _e( 'Loading...', 'nxf-transform' ); ?>
</div>

<script type="text/javascript">
jQuery( document ).ready( function() {
	jQuery("form").submit(function() { 
        var id = jQuery("input[type=submit][clicked=true]").attr( 'id' );
        if ( id == 'doaction' && jQuery( '#action' ).val() == 'delete' ) {
        	return nxf_bulk_delete( 'settings' );
        } else if (  id == 'doaction2' && jQuery( '#action2' ).val() == 'delete' ) {
        	return nxf_bulk_delete( 'settings' );
        } else { return false; }
    });
    
    jQuery("form input[type=submit]").click(function() {
        jQuery("input[type=submit]", jQuery(this).parents("form")).removeAttr("clicked");
        jQuery(this).attr("clicked", "true");
    });
});
</script>
