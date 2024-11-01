<div id="nxf-error"></div>

<?php $next = 0; ?>

<div id="nxf-nxf-dialog" style="height: 450px; overflow-y: yes; overflow-x: no;">
	<form name="nxf-<?php if ( $nxf_dashboard_model->feed->ID != '' ) { print "edit"; } else { print "new"; } ?>feed" id="nxf-newfeed" method="post">
		<input type="hidden" name="action" value="<?php if ( $nxf_dashboard_model->feed->ID != '' ) { print "edit"; } else { print "new"; } ?>-feed" />
		<?php if ( $nxf_dashboard_model->feed->ID != '' ) {  ?>
			<input type="hidden" name="id" value="<?php print $nxf_dashboard_model->feed->ID ?>" />
		<?php } ?>
		<table id="feed" width="100%">
			<tbody>
				<tr>
					<th><?php _e( 'Feed Type', 'nxf-transform' ); ?></th>
					<td>
						<select name="feed_type_id">
						  <?php
							foreach ( $nxf_dashboard_model->feedtypes as $feedtype ) { ?>
							  <option value="<?php echo $feedtype->ID ?>"<?php if ( $nxf_dashboard_model->feed->feedtypeid == $feedtype->ID ) { echo ' selected="selected"'; } ?>><?php echo $feedtype->feed_type_name ?></option>
							<?php }
						  ?>
						</select>
					</td>
				</tr>
				<tr>
					<th><?php _e( 'Feed Name', 'nxf-transform' ) ?></th>
					<td><input name="feed_name" value="<?php echo $nxf_dashboard_model->feed->feed_name ?>" size="40" maxlength="128" /></td>
				</tr>
				<tr>
					<th><?php _e( 'Feed Description', 'nxf-transform' ); ?></th>
					<td>
					  <textarea name="feed_desc" cols="40" rows="6"><?php echo $nxf_dashboard_model->feed->feed_desc ?></textarea>
					</td>
				</tr>
				<tr>
					<th><?php _e( 'Feed Source', 'nxf-transform' ); ?></th>
					<td>
						<select name="feed_source" onchange="setSource( this );">
							<option value="web"<?php if ( $nxf_dashboard_model->feed->feed_source == "web" ) { print " selected='selected'"; } ?>>Web</option>
							<option value="file"<?php if ( $nxf_dashboard_model->feed->feed_source == "file" ) { print " selected='selected'"; } ?>>File</option>
						</select>
					</td>
				</tr>
				<tr class="nxf-web"<?php if ( $nxf_dashboard_model->feed->feed_source == "file" ) { print ' style="display: none;"'; } ?>>
					<th><?php _e( 'Feed URI', 'nxf-transform' ); ?></th>
					<td><input name="feed_uri" value="<?php echo $nxf_dashboard_model->feed->feed_uri ?>" size="40" maxlength="256" /></td>
				</tr>
				<tr class="nxf-web"<?php if ( $nxf_dashboard_model->feed->feed_source == "file" ) { print ' style="display: none;"'; } ?>>
					<th><?php _e( 'Feed Method', 'nxf-transform' ); ?></th>
					<td>
					  <select name="method">
						<option value="GET"<?php if ( $nxf_dashboard_model->feed->method == "GET" ) { print " selected='selected'"; } ?>><?php _e( 'GET', 'nxf-transform' ); ?></option>
						<option value="POST"<?php if ( $nxf_dashboard_model->feed->method == "POST" ) { print " selected='selected'"; } ?>><?php _e( 'POST', 'nxf-transform' ); ?></option>
						<option value="PUT"<?php if ( $nxf_dashboard_model->feed->method == "PUT" ) { print " selected='selected'"; } ?>><?php _e( 'PUT', 'nxf-transform' ); ?></option>
						<option value="DELETE"<?php if ( $nxf_dashboard_model->feed->method == "DELETE" ) { print " selected='selected'"; } ?>><?php _e( 'DELETE', 'nxf-transform' ); ?></option>
					  </select>
					</td>
				</tr>
				<tr class="nxf-web"<?php if ( $nxf_dashboard_model->feed->feed_source == "file" ) { print ' style="display: none;"'; } ?>>
					<th><?php _e( 'Feed Parameters', 'nxf-transform' ) ?></th>
					<td><input type="button" value="Add Parameter" onclick="newParameter();" /></td>
				</tr>
				
				<tr class="nxf-web"<?php if ( $nxf_dashboard_model->feed->feed_source == "file" ) { print ' style="display: none;"'; } ?>>
					<td colspan="2">
						<hr />
					</td>
				</tr>
			
				<tr class="nxf-web"<?php if ( $nxf_dashboard_model->feed->feed_source == "file" ) { print ' style="display: none;"'; } ?>>
					<td colspan="2">
						<table id="parameters" width="100%">
							<tbody>
							<?php 
								foreach ( $nxf_dashboard_model->parameters as $parameter ) {
									$next++;
									
									print '<tr class="parameter-' . $next . '">';
									print '<th>' . __( 'Parameter:', 'nxf-transform' ) . '<input type="hidden" name="id-' . $next . '" value="' . $parameter->ID . '" /></th>';
									print '<td>' . __( ' Name ', 'nxf-transform' );
									print '<input id="name-out-' . $next . '" name="name-out-' . $next . '" size="20" maxlength="40" value="' . $parameter->fparam_name_out . '" />';
									print __( ' as type ', 'nxf-transform' );
									print '<select name="type-out-' . $next . '">';
									print '<option value="GET"';
									if ( 'GET' == $parameter->fparam_type_out ) { print  ' selected="selected"'; }
									print '>' . __( 'GET', 'nxf-transform' ) . '</option>';
									print '<option value="POST"';
									if ( 'POST' == $parameter->fparam_type_out ) { print ' selected="selected"'; }
									print '>' . __( 'POST', 'nxf-transform' ) . '</option>';
									print '<option value="HEADER"';
									if ( 'HEADER' == $parameter->fparam_type_out ) { print ' selected="selected"'; }
									print '>' . __( 'HEADER', 'nxf-transform' ) . '</option>';
									print '<option value="COOKIE"';
									if ( 'COOKIE' == $parameter->fparam_type_out ) { print ' selected="selected"'; }
									print '>' . __( 'COOKIE', 'nxf-transform' ) . '</option>';
									print '</select> <a href="" onclick="return deleteParam( ' . $next . ' );" style="text-decoration: none; float: right;"><span class="dashicons dashicons-dismiss"></span></a>';
									print '</td></tr>';
									print '<tr class="parameter-' . $next . '">';
									print '<td>&nbsp;</td>';
									print '<td>' . __( 'Value comes from ', 'nxf-transform' );
									print '<select name="type-in-' . $next . '" onchange="showHideNameInLabel( this, ' . $next . ' );">';
									print '<option value="GET"';
									if ( 'GET' == $parameter->fparam_type_in ) { print ' selected="selected"'; }
									print '>' . __( 'GET Variable', 'nxf-transform' ) . '</option>';
									print '<option value="POST"';
									if ( 'POST' == $parameter->fparam_type_in ) { print ' selected="selected"'; }
									print '>' . __( 'POST Variable', 'nxf-transform' ) . '</option>';
									print '<option value="HEADER"';
									if ( 'HEADER' == $parameter->fparam_type_in ) { print ' selected="selected"'; }
									print '>' . __( 'HTTP Header', 'nxf-transform' ) . '</option>';
									print '<option value="COOKIE"';
									if ( 'COOKIE' == $parameter->fparam_type_in ) { print ' selected="selected"'; }
									print '>' . __( 'Cookie', 'nxf-transform' ) . '</option>';
									print '<option value="SETTING"';
									if ( 'SETTING' == $parameter->fparam_type_in ) { print ' selected="selected"'; }
									print '>' . __( 'Setting', 'nxf-transform' ) . '</option>';
									print '<option value="WP"';
									if ( 'WP' == $parameter->fparam_type_in ) { print ' selected="selected"'; }
									print '>' . __( 'WP User Field', 'nxf-transform' ) . '</option>';
									print '<option value="STATIC"';
									if ( 'STATIC' == $parameter->fparam_type_in ) { print ' selected="selected"'; }
									print '>' . __( 'Static Text', 'nxf-transform' ) . '</option>';
									print '</select>';
									$display = 'none';
									if ( 'SETTING' != $parameter->fparam_type_in && 'WP' != $parameter->fparam_type_in ) { $display = 'inline'; }
									print __( ' <span id="name-in-label-' . $next . '" style="display: ' . $display . '">with name</span> ', 'nxf-transform' );
									print '<span id="name-in-input-' . $next . '">';
									if ( 'SETTING' != $parameter->fparam_type_in && 'WP' != $parameter->fparam_type_in ) {
										print '<input name="name-in-' . $next . '" size="20" maxlength="40" value="' . $parameter->fparam_name_in . '" />';
									} elseif ( 'WP' == $parameter->fparam_type_in ) {
										print '<select name="name-in-' . $next . '">';
										print '<option value="login"';
										if ( 'login' == $parameter->fparam_name_in ) { print ' selected="selected"'; }
										print '>' . __( 'User Login', 'nxf-transform' ) . '</option>';
										print '<option value="ID"';
										if ( 'ID' == $parameter->fparam_name_in ) { print ' selected="selected"'; }
										print '>' . __( 'User ID', 'nxf-transform' ) . '</option>';
										print '</select>';
									} else {
										print '<select name="name-in-' . $next . '">';
										foreach ( $nxf_dashboard_model->settings as $setting ) {
											print '<option value="' . $setting->setting_name . '"';
											if ( $setting->setting_name == $parameter->fparam_name_in ) { print ' selected="selected"'; }
											print '>' . $setting->setting_name . '</option>';
										}
										print '</select>';
									}
									print '</span></td></tr>';
								}
							?>
							</tbody>
						</table>
					</td>
				</tr>
				
				<tr class="nxf-feed_file"<?php if ( 'web' == $nxf_dashboard_model->feed->feed_source || '' == $nxf_dashboard_model->feed->feed_source ) { print ' style="display: none;"'; } ?>>
					<th><?php _e( 'File', 'nxf-transform' ); ?></th>
					<td><input name="feed_file" value="<?php echo $nxf_dashboard_model->feed->feed_file ?>" size="40" maxlength="256" /></td>
				</tr>
			</tbody>
		</table>
		
		<input type="hidden" id="next" name="next" value="<?php echo $next; ?>" />
	</form>
</div>

<script type="text/javascript">
function setSource( sel ) {
	if ( 'web' == jQuery( sel ).val() ) {
		jQuery( '.nxf-feed_file' ).hide();
		jQuery( '.nxf-web' ).show();
	} else {
		jQuery( '.nxf-web' ).hide();
		jQuery( '.nxf-feed_file' ).show();
	}
}

function newParameter() {
	var next = jQuery( '#next' ).val();
	next = parseInt(next) + 1;
	
	jQuery( 'table#parameters' ).find('tbody').append(
		jQuery( '<tr>' ).attr( 'class', 'parameter-' + next ).append(
			jQuery( '<th>' ).append(
				'<?php _e( 'Parameter:', 'nxf-transform' ); ?>'
			)
		).append(
			jQuery( '<td>' ).append(
				'<?php _e( ' Name ', 'nxf-transform' ); ?>'
			).append(
				jQuery( '<input>' ).attr( 'id', 'name-out-' + next ).attr( 'name', 'name-out-' + next ).attr( 'size', '20' ).attr( 'maxlength', '40' )
			).append(
				'<?php _e( ' as type ', 'nxf-transform' ); ?>'
			).append(
				jQuery( '<select>' ).attr( 'name', 'type-out-' + next ).append( 
					jQuery( '<option></option>' ).attr( 'value', 'GET' ).text( '<?php _e( 'GET', 'nxf-transform' ); ?>' ) 
				).append( 
					jQuery( '<option></option>' ).attr( 'value', 'POST' ).text( '<?php _e( 'POST', 'nxf-transform' ); ?>' ) 
				).append( 
					jQuery( '<option></option>' ).attr( 'value', 'HEADER' ).text( '<?php _e( 'HEADER', 'nxf-transform' ); ?>' ) 
				).append( 
					jQuery( '<option></option>' ).attr( 'value', 'COOKIE' ).text( '<?php _e( 'COOKIE', 'nxf-transform' ); ?>' ) 
				)
			).append(
				'<a href="" onclick="return deleteParam( ' + next + ' );" style="text-decoration: none; float: right;"><span class="dashicons dashicons-dismiss"></span></a>'
			)
		)
	)
		
	jQuery( 'table#parameters' ).find('tbody').append(
		jQuery( '<tr>' ).attr( 'class', 'parameter-' + next ).append(
			jQuery( '<td>' ).append(
				'&nbsp;'
			)
		).append(
			jQuery( '<td>' ).append(
				'<?php _e( 'Value comes from ', 'nxf-transform' ); ?>'
			).append(
				jQuery( '<select>' ).change( function() {
					showHideNameInLabel( this, next );
				}).attr( 'name', 'type-in-' + next ).append( 
					jQuery( '<option></option>' ).attr( 'value', 'GET' ).text( '<?php _e( 'GET Variable', 'nxf-transform' ); ?>' ) 
				).append( 
					jQuery( '<option></option>' ).attr( 'value', 'POST' ).text( '<?php _e( 'POST Variable', 'nxf-transform' ); ?>' ) 
				).append( 
					jQuery( '<option></option>' ).attr( 'value', 'HEADER' ).text( '<?php _e( 'HTTP Header', 'nxf-transform' ); ?>' ) 
				).append( 
					jQuery( '<option></option>' ).attr( 'value', 'COOKIE' ).text( '<?php _e( 'Cookie', 'nxf-transform' ); ?>' ) 
				).append( 
					jQuery( '<option></option>' ).attr( 'value', 'SETTING' ).text( '<?php _e( 'Setting', 'nxf-transform' ); ?>' ) 
				).append( 
					jQuery( '<option></option>' ).attr( 'value', 'WP' ).text( '<?php _e( 'WP User Field', 'nxf-transform' ); ?>' ) 
				).append( 
					jQuery( '<option></option>' ).attr( 'value', 'STATIC' ).text( '<?php _e( 'Static Text', 'nxf-transform' ); ?>' ) 
				)
			).append(
				'<?php _e( ' <span id="name-in-label-\'+next+\'">with name</span> ', 'nxf-transform' ); ?>'
			).append(
				jQuery( '<span>' ).attr( 'id', 'name-in-input-'+next ).append(
					jQuery( '<input>' ).attr( 'name', 'name-in-'+next ).attr( 'size', '20' ).attr( 'maxlength', '40' )
				)
			)
		)
	);
	
	jQuery( 'table#parameters' ).find('tbody').append(
		jQuery( '<tr>' ).attr( 'class', 'parameter-' + next ).append(
			jQuery( '<td>' ).attr( 'colspan', '2' ).append(
				'<hr />'
			)
		)
	);
	
	jQuery( '#next' ).val( next );
}

function deleteParam( which ) {
	jQuery( '#name-out-' + which ).val('NXF-PARAM-DELETED');
	jQuery( '.parameter-' + which ).hide();
	return false;
}

function showHideNameInLabel( sel, which ) {
	if ( 'STATIC' == jQuery( sel ).val() ) {
		jQuery( '#name-in-label-' + which ).hide();
		jQuery( '#name-in-input-' + which ).html(
			jQuery( '<input>' ).attr( 'name', 'name-in-'+which ).attr( 'size', '20' ).attr( 'maxlength', '40' )
		);
	} else if ( 'SETTING' == jQuery( sel ).val() ) {
		jQuery( '#name-in-label-' + which ).hide();
		jQuery( '#name-in-input-' + which ).html(
			jQuery( '<select>' ).attr( 'name', 'name-in-'+which )
			<?php foreach ( $nxf_dashboard_model->settings as $setting ) { ?>
			.append(
				jQuery( '<option></option>' ).attr( 'value', '<?php echo $setting->setting_name; ?>' ).text( '<?php echo $setting->setting_name; ?>' )
			)
			<?php } ?> 
		);
	} else if ( 'WP' == jQuery( sel ).val() ) {
		jQuery( '#name-in-label-' + which ).hide();
		jQuery( '#name-in-input-' + which ).html(
			jQuery( '<select>' ).attr( 'name', 'name-in-'+which ).append(
				jQuery( '<option></option>' ).attr( 'value', 'login' ).text( '<?php echo __( 'User Login', 'nxf-transform' ); ?>' )
			).append(
				jQuery( '<option></option>' ).attr( 'value', 'ID' ).text( '<?php echo __( 'User ID', 'nxf-transform' ); ?>' )
			)
		);
	} else {
		jQuery( '#name-in-label-' + which ).show();
		jQuery( '#name-in-input-' + which ).html(
			jQuery( '<input>' ).attr( 'name', 'name-in-'+which ).attr( 'size', '20' ).attr( 'maxlength', '40' )
		);
	}
}
</script>
