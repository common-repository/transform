<div style="height: 540px; overflow-y: auto;">
	<div id="nxf-error"></div>

	<form id="nxf-<?php if ( $nxf_dashboard_model->display->ID == '' ) { print 'new'; } else { print 'edit'; } ?>display" method="post">
		<input type="hidden" name="action" value="<?php if ( $nxf_dashboard_model->display->ID == '' ) { print 'new'; } else { print 'edit'; } ?>-display" />
		<?php if ( $nxf_dashboard_model->display->ID != '' ) {  ?>
			<input type="hidden" name="id" value="<?php print $nxf_dashboard_model->display->ID ?>" />
		<?php } ?>
		<table>
			<tr>
				<th><?php _e( 'Feed Type', 'nxf-transform' ) ?></th>
				<td>
					<select name="feed_type_id" id="feed_type_id">
						<?php foreach ( $nxf_dashboard_model->feedtypes as $feedtype ) {
							print '<option value="' . $feedtype->ID . '"';
							if ( $feedtype->ID == $nxf_dashboard_model->display->feed_type_id ) { print ' selected="selected"'; }
							print '>' . $feedtype->feed_type_name . '</option>';
						} ?>
					</select>
				</td>
			</tr>
		
			<tr>
				<th><?php _e( 'Display Name', 'nxf-transform' ) ?></th>
				<td><input type="text" name="display_name" value="<?php echo htmlspecialchars( $nxf_dashboard_model->display->display_name ) ?>" size="40" maxlength="128" /></td>
			</tr>
		
			<tr>
				<th><?php _e( 'Display Description', 'nxf-transform' ) ?></th>
				<td><input type="text" name="display_desc" value="<?php echo htmlspecialchars( $nxf_dashboard_model->display->display_desc ) ?>" size="40" maxlength="128" /></td>
			</tr>
		
			<tr>
				<th><?php _e( 'Display Type', 'nxf-transform' ) ?></th>
				<td>
				  <select name="display_type" id="display_type" onchange="selectType(this,'<?php if ( $nxf_dashboard_model->display->ID == '' ) { print 'new'; } else { print 'edit'; } ?>')">
					<option value="FILE"<?php if ( $nxf_dashboard_model->display->display_type == 'FILE' ) { print " selected='selected'"; } ?>><?php _e( 'File on this server', 'nxf-transform' ) ?></option>
					<option value="URL"<?php if ( $nxf_dashboard_model->display->display_type == 'URL' ) { print " selected='selected'"; } ?>><?php _e( 'URL', 'nxf-transform' ) ?></option>
					<option value="DB"<?php if ( $nxf_dashboard_model->display->display_type == 'DB' ) { print " selected='selected'"; } ?>><?php _e( 'Text', 'nxf-transform' ) ?></option>
				  </select>
				</td>
			</tr>
		
			<tr id="file-<?php if ( $nxf_dashboard_model->display->ID == '' ) { print 'new'; } else { print 'edit'; } ?>" class="display" style="display: <?php if ( $nxf_dashboard_model->display->display_type == 'FILE' || $nxf_dashboard_model->display->ID == '' ) { print 'table-row'; } else { print 'none'; } ?>;">
				<th><?php _e( 'File Location', 'nxf-transform' ) ?></th>
				<td>
					<input name="location" size="40" value="<?php if ( $nxf_dashboard_model->display->display_type == 'FILE' ) { print $nxf_dashboard_model->display->display; } ?>" />
					<div><?php _e( 'Full path to the file on this server', 'nxf-transform' ) ?></div>
				</td>
			</tr>
		
			<tr id="url-<?php if ( $nxf_dashboard_model->display->ID == '' ) { print 'new'; } else { print 'edit'; } ?>" class="display" style="display: <?php if ( $nxf_dashboard_model->display->display_type == 'URL' ) { print 'table-row'; } else { print 'none'; } ?>;">
				<th><?php _e( 'URL', 'nxf-transform' ) ?></th>
				<td>
					<input name="url" size="40" value="<?php if ( $nxf_dashboard_model->display->display_type == 'URL' ) { print $nxf_dashboard_model->display->display; } ?>"  />
				</td>
			</tr>
		
			<tr id="db-<?php if ( $nxf_dashboard_model->display->ID == '' ) { print 'new'; } else { print 'edit'; } ?>" class="display" style="display: <?php if ( $nxf_dashboard_model->display->display_type == 'DB' ) { print 'table-row'; } else { print 'none'; } ?>;">
				<th><?php _e( 'Text', 'nxf-transform' ) ?></th>
				<td>
					<textarea name="db" rows="10" cols="60"><?php if ( $nxf_dashboard_model->display->display_type == 'DB' ) { print htmlspecialchars( $nxf_dashboard_model->display->display, ENT_XML1 ); } ?></textarea>
				</td>
			</tr>
		
			<tr>
				<th style="text-align: left;"><?php _e( 'Display Parameters', 'nxf-transform' ) ?>:</th>
				<td>
					<button type="button" id="add" class="button"><?php _e( 'Add New', 'nxf-transform' ) ?></button>
				</td>
			</tr>
		
			<tr>
				<td colspan="2">
					<table id="parameters">
						<tbody>
						<?php $next = 0; foreach ( $nxf_dashboard_model->parameters as $parameter ) { ?>
							<tr id="tr-<?php print $next ?>">
								<th style="text-align: left;"><?php _e( 'Type', 'nxf-transform' ) ?></th>
								<td>
									<input type="hidden" id="id-<?php print $next ?>" name="id-<?php print $next ?>" value="<?php print $parameter->ID ?>" />
									<select name="type-<?php print $next ?>" onchange="settingType( this, <?php print $next ?> )">
										<option value="STATIC"<?php if ( $parameter->dparam_type == "STATIC" ) { print ' selected="selected"'; } ?>><?php _e( 'Text', 'nxf-transform' ) ?></option>
										<option value="SETTING"<?php if ( $parameter->dparam_type == "SETTING" ) { print ' selected="selected"'; } ?>><?php _e( 'Transform Setting', 'nxf-transform' ) ?></option>
										<option value="POST"<?php if ( $parameter->dparam_type == "POST" ) { print ' selected="selected"'; } ?>><?php _e( 'Form Parameter', 'nxf-transform' ) ?></option>
										<option value="GET"<?php if ( $parameter->dparam_type == "GET" ) { print ' selected="selected"'; } ?>><?php _e( 'URL Parameter', 'nxf-transform' ) ?></option>
									</select>
								</td>
								<th id="name-label-<?php print $next ?>"><?php _e( 'Name', 'nxf-transform' ) ?></th>
								<td id="name-input-<?php print $next ?>">
									<?php if ( $parameter->dparam_type == 'SETTING' ) {
										print '<select name="name-' . $next . '" id="name-' . $next . '" onchange="settingValue(' . $next . ');">';
										foreach ( $nxf_dashboard_model->settings as $setting ) {
											print '<option value="' . $setting->setting_name . '"';
											if ( $parameter->dparam_name == $setting->setting_name ) { print ' selected="selected"'; }
											print '>' . $setting->setting_name . '</option>';
										}
										print '</select>';
									} else { ?>
										<input name="name-<?php print $next ?>" value="<?php print $parameter->dparam_name ?>" size="20" maxlength="40" />
									<?php } ?>
								</td>
								<th id="value-label-<?php print $next ?>">
									<?php if ( $parameter->dparam_type == 'SETTING' ) {
										_e( 'Setting Value', 'nxf-transform' );
									} else {
										_e( 'Default Value', 'nxf-transform' );
									} ?>
								</th>
								<td id="value-input-<?php print $next ?>">
									<?php if ( $parameter->dparam_type == 'SETTING' ) {
										foreach ( $nxf_dashboard_model->settings as $setting ) {
											if ( $parameter->dparam_name == $setting->setting_name ) {
												print $setting->setting_value;
											}
										} ?>
										<input id="value-<?php print $next ?>" name="value-<?php print $next ?>" type="hidden" value="" />
									<?php } else { ?>
										<input id="value-<?php print $next ?>" name="value-<?php print $next ?>" value="<?php print $parameter->dparam_value ?>" size="20" maxlength="128" />
									<?php } ?>
								</td>
								<td>
									<button type="button" class="button delete" onclick="deleteParam( <?php print $next ?> );"><?php _e( 'Delete', 'nxf-transform' ) ?></button>
								</td>
							</tr>
						<?php $next++; } ?>
						</tbody>
					</table>
				</td>
			</tr>
		</table>
		<input type="hidden" name="next" id="next" value="<?php print $next; ?>" />
		<input type="hidden" name="delete" id="delete" value="" />
	</form>
</div>

<script type="text/javascript">
var settings = <?php print json_encode( $nxf_dashboard_model->settings ) ?>

function selectType( sel, type ) {
	jQuery( '.display' ).hide();
	switch ( jQuery( sel ).val() ) {
		case 'FILE':
			jQuery( '#file-'+type ).css( 'display', 'table-row' );
			break;
			
		case 'URL':
			jQuery( '#url-'+type ).css( 'display', 'table-row' );
			break;
			
		case 'DB':
			jQuery( '#db-'+type ).css( 'display', 'table-row' );
			break;
	}
}

jQuery(document).ready(	
	function() {		
		jQuery( '#add' ).click(
			function() {
				var which = jQuery( '#next' ).val();
				
				// add a table row
				jQuery( "table#parameters" ).find('tbody').append(
					jQuery( '<tr>' ).attr( 'id', 'tr-'+which ).append(
						jQuery( '<th>' ).append(
							'Type'
						)
					).append(
						jQuery( '<td>' ).append(
							jQuery( '<select> ' ).attr( 'name', 'type-' + which).attr( 'onchange', 'settingType( this, '+which+' )' ).append(
								jQuery( '<option>' ).attr( 'value', 'STATIC' ).text( '<?php _e( 'Text', 'nxf-transform' ) ?>' )
							).append (
								jQuery( '<option>' ).attr( 'value', 'SETTING' ).text( '<?php _e( 'Transform Setting', 'nxf-transform' ) ?>' )
							).append (
								jQuery( '<option>' ).attr( 'value', 'POST' ).text( '<?php _e( 'Form Field', 'nxf-transform' ) ?>' )
							).append (
								jQuery( '<option>' ).attr( 'value', 'GET' ).text( '<?php _e( 'URL Parameter', 'nxf-transform' ) ?>' )
							)
						).append(
							jQuery( '<input>' ).attr( 'type', 'hidden' ).attr( 'name', 'id-' + which ).attr( 'id', 'id-' + which ).attr( 'value', '' )
						)
					).append(
						jQuery( '<th>' ).attr( 'id', 'name-label-'+which ).append(
							'<?php _e( 'Name', 'nxf-transform' ) ?>'
						)
					).append(
						jQuery( '<td>' ).attr( 'id', 'name-input-'+which ).append(
							jQuery( '<input>' ).attr( 'name', 'name-' + which ).attr( 'size', '20' )
						)
					).append(
						jQuery( '<th>' ).attr( 'id', 'value-label-'+which ).append(
							'<?php _e( 'Default Value', 'nxf-transform' ) ?>'
						)
					).append(
						jQuery( '<td>' ).attr( 'id', 'value-input-'+which ).append(
							jQuery( "<input>" ).attr( 'name', 'value-' + which ).attr( 'size', '20' )
						)
					).append(
						jQuery( '<td>' ).append(
							jQuery( '<button>' ).attr( 'type', 'button' ).attr( 'class', 'button delete' ).text( '<?php _e( 'Delete', 'nxf-transform' ) ?>' ).click(
								function() {
									deleteParam( which );
								}
							)
						)
					)
				);
				jQuery( '#next' ).val( parseInt(which)+1 );
			}
		);
	}
);

function settingValue( row ) {
	for ( var i = 0; i< settings.length; i++ ) {
		if ( settings[i].setting_name == jQuery( '#name-'+row ).val() ) {
			jQuery( '#value-input-'+row ).html( settings[i].setting_value );
		}
	}
}

function deleteParam( which ) {
	var id = jQuery( '#id-' + which ).val();
	jQuery( 'table#parameters tr#tr-' + which ).remove();
	if ( id != '' ) {
		if ( jQuery( '#delete' ).val() == '' ) {
			jQuery( '#delete' ).val( id );
		} else {
			jQuery( '#delete' ).val( jQuery( '#delete' ).val() + ',' + id );
		}
	}
}

function settingType( sel, row ) {
	jQuery( '#name-input-'+row ).empty();
	jQuery( '#value-input-'+row ).empty();
	
	if ( jQuery(sel).val() == "SETTING" ) {
		jQuery( '#name-label-'+row ).html( "<?php _e( 'Setting', 'nxf-transform' ) ?>" );
		jQuery( '#value-label-'+row ).html( "<?php _e( 'Setting Value', 'nxf-transform' ) ?>" );
		
		jQuery( '#name-input-'+row ).append(
	  		jQuery( '<select>' ).attr( 'name', 'name-' + row ).attr( 'id', 'name-' + row )
	  		<?php 
	  		$selected = 'true';
	  		foreach ( $nxf_dashboard_model->settings as $setting ) {
	  			print ".append( jQuery( '<option>', { value: '" . $setting->setting_name . "', text: '" . $setting->setting_name . "', selected: $selected } ) )";
	  			$selected = 'false';
	  		}
	  		?>
		).attr( 'onchange', 'settingValue( ' + row + ' )' );
		
		settingValue( row );

		jQuery( '#value-input-'+row ).append(
			jQuery( '<input>' ).attr( 'name', 'value-' + row ).attr( 'value', '' ).attr( 'type', 'hidden' )
		);
		
	} else {
		jQuery( '#name-label-'+row ).html( "<?php _e( 'Name', 'nxf-transform' ) ?>" );
		jQuery( '#value-label-'+row ).html( "<?php _e( 'Default Value', 'nxf-transform' ) ?>" );
		
		jQuery( '#name-input-'+row ).append(
			jQuery( '<input>' ).attr( 'name', 'name-' + row ).attr( 'value', '' ).attr( 'size', '20' )
		);
		
		jQuery( '#value-input-'+row ).append(
			jQuery( '<input>' ).attr( 'name', 'value-' + row ).attr( 'value', '' ).attr( 'size', '20' )
		);
	}
}
</script>
