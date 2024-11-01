<div id="nxf-error"></div>

<div id="nxf-nxf-dialog" style="height: 300px; overflow-y: auto;">
	<form name="nxf-<?php if ( $nxf_dashboard_model->instance->ID != '' ) { print "edit"; } else { print "new"; } ?>instance" id="nxf-newinstance" method="post">
		<input type="hidden" name="action" value="<?php if ( $nxf_dashboard_model->instance->ID != '' ) { print "edit"; } else { print "new"; } ?>-instance" />
		<?php if ( $nxf_dashboard_model->instance->ID != '' ) {  ?>
			<input type="hidden" name="id" value="<?php print $nxf_dashboard_model->instance->ID ?>" />
		<?php } ?>
	
		<p>
			<strong><?php _e( 'Instance Name', 'nxf-transform' ); ?>:</strong> <input name="instance_name" id="instance_name" value="<?php echo $nxf_dashboard_model->instance->instance_name ?>" size="40" maxlength="128" />
		</p>
	
		<p>
			<?php _e( 'Select a transform feed and display below.', 'nxf-transform' ); ?>
		</p>
	
		<p>
			<strong><?php _e( 'Feed', 'nxf-transform' ); ?>:</strong>
			<select id="feed" name="feed">
				<option value=""><?php _e( '- Select -', 'nxf-transform' ); ?></option>
				<?php
				foreach ( $nxf_dashboard_model->feeds as $feed ) {
					echo '<option value="' . $feed->ID . '"';
					if ( $feed->ID ==  $nxf_dashboard_model->instance->feed_id ) { echo " selected='selected'"; }
					echo '>' . $feed->feed_name . '</option>';
				}
				?>
			</select>
		
			<?php
			foreach ( $nxf_dashboard_model->feeds as $feed ) {
				echo '<input type="hidden" id="displays' . $feed->ID . '" class="available-displays" name="displays' . $feed->ID . '" value=\'' . json_encode( $feed->displays ) . '\' />';
			}
			?>
		</p>
	
		<p>
			<strong><?php _e( 'Display', 'nxf-transform' ); ?>:</strong>
			<select id="display" name="display">
				<option value=""><?php _e( '- Select a Transform feed -', 'nxf-transform' ); ?></option>
			</select>
		</p>
	
		<p><strong><?php _e( 'Display Parameters', 'nxf-transform' ); ?>:</strong></p>
	
		<table id="parameters">
			<tbody>
				<tr><td><?php _e( 'Please select a display', 'nxf-transform' ); ?></td></tr>
			</tbody>
		</table>
	</form>
</div>

<script type="text/javascript">
var settings = <?php echo json_encode( $nxf_dashboard_model->settings ) ?>;

function show_dparams( feedid, displayid ) {
	if ( feedid != '' ) {
		// alert( "json for " + feedid + " is " + jQuery( '#displays' + feedid ).val() );
		var displays = JSON.parse( jQuery( '#displays' + feedid ).val() );
		for ( j = 0; j < displays.length; j++ ) {
			if ( displays[ j ].ID == displayid ) {
				// build the display parameters table rows
				jQuery( '#parameters tbody' ).empty();
				if ( displays[ j ].params.length > 0 ) {
					for ( k = 0; k < displays[ j ].params.length; k++ ) {
						if ( "SETTING" == displays[j].params[ k ].dparam_type ) {
							var value = '<?php _e( 'undefined', 'nxf-transform' ); ?>';
							for ( l=0; l < settings.length; l++ ) {
								if ( settings[ l ].setting_name == displays[ j ].params[ k ].dparam_name ) {
									value = settings[ l ].setting_value;
								}
							}
							
							jQuery( 'table#parameters' ).find('tbody').append(
								jQuery( '<tr>' ).append(
									jQuery( '<td style="font-weight: bold">' ).text( displays[ j ].params[ k ].dparam_name + ':' )
								).append(
									jQuery( '<td>' ).append(
										jQuery( '<input>' ).attr( 'id', displays[ j ].params[ k ].dparam_name ).attr( 'name', displays[ j ].params[ k ].dparam_name ).attr( 'type', 'hidden' )
									)
								).append(
									jQuery( '<td>' ).append(
										'<?php printf( __( "Uses the value of the setting \"' + displays[ j ].params[ k ].dparam_name + '\" (currently ' + value + ')", 'nxf-transform' ) ); ?>'
									)
								)
							);
						} else {
							jQuery( 'table#parameters' ).find('tbody').append(
								jQuery( '<tr>' ).append(
									jQuery( '<td style="font-weight: bold">' ).text( displays[ j ].params[ k ].dparam_name + ':' )
								).append(
									jQuery( '<td>' ).append(
										jQuery( '<input>' ).attr( 'id', displays[ j ].params[ k ].dparam_name ).attr( 'name', displays[j].params[k].dparam_name ).attr( 'size', '20' ) //.attr( 'value', displays[k].params[k].dparam_value )
									)
								).append(
									jQuery( '<td>' ).append(
										'<?php printf( __( "Blank = use default of \"' + displays[ j ].params[ k ].dparam_value + '\"" ), 'nxf-transform' ); ?>'
									)
								)
							);
						
							if ( displays[ j ].params[ k ].hasOwnProperty( 'dparam_user_value' ) ) {
								jQuery( '#'+displays[ j ].params[ k ].dparam_name ).val( displays[ j ].params[ k ].dparam_user_value );
							}
						}
					}
				} else {
					jQuery( 'table#parameters' ).find('tbody').append(
						jQuery( '<tr>' ).append(
							jQuery( '<td>' ).text( "<?php _e( 'This display does not have parameters', 'nxf-transform' ); ?>" )
						)
					);
				}
			}
		}
	} else {
		jQuery( '#parameters tbody' ).empty();
		jQuery( 'table#parameters' ).find('tbody').append(
			jQuery( '<tr>' ).append(
				jQuery( '<td>' ).text( "<?php _e( 'Please select a display', 'nxf-transform' ); ?>" )
			)
		);
	}
}

jQuery( document ).ready( function() {
	jQuery( '#feed' ).change( function() {
		if ( jQuery( '#feed' ).val() != '' ) {
			// clear the display menu and display parameters table
			jQuery( '#display' ).find('option').remove().end();
			jQuery( '#parameters tbody' ).empty();
			jQuery( 'table#parameters' ).find('tbody').append(
				jQuery( '<tr>' ).append(
					jQuery( '<td>' ).text( "<?php _e( 'Please select a display', 'nxf-transform' ); ?>" )
				)
			);
			
			// get the displays for the select menu
			var displaysJSON = jQuery( '#displays' + jQuery( '#feed' ).val() ).val();
			
			if ( displaysJSON == '' ) {
				// no displays for this feed, how odd
				alert( '<?php _e( 'No displays are available for this feed', 'nxf-transform' ); ?>' );
				jQuery( '#display' ).append( jQuery( '<option></option>' ).attr( 'value', '' ).text( '<?php _e( '- Select a Transform Feed -', 'nxf-transform' ); ?>' ) );
			} else {
				jQuery( '#display' ).append( jQuery( '<option></option>' ).attr( 'value', '' ).text( '<?php _e( '- Select a Display -', 'nxf-transform' ); ?>' ) );
				var displays = JSON.parse( displaysJSON );
				for ( var i = 0; i < displays.length; i++ ) {
					// add the display the the menu
					jQuery( '#display' ).append( jQuery( '<option></option>' ).attr( 'value', displays[ i ].ID ).text( displays[ i ].display_name ) );
				}
				jQuery( '#display' ).change( function() {
					show_dparams( jQuery( '#feed' ).val(), jQuery( '#display' ).val() );
				});
			}
		} else {
			// reset the display menu and display parameters table
			jQuery( '#display' ).find('option').remove().end();
			jQuery( '#display' ).append( jQuery( '<option></option>' ).attr( 'value', '' ).text( '<?php _e( '- Select a Transform Feed -', 'nxf-transform' ); ?>' ) );
			jQuery( '#parameters tbody' ).empty();
			jQuery( 'table#parameters' ).find('tbody').append(
				jQuery( '<tr>' ).append(
					jQuery( '<td>' ).text( "<?php _e( 'Please select a display', 'nxf-transform' ); ?>" )
				)
			);
		}
	});
	
	<?php if ( $nxf_dashboard_model->instance->ID != '' ) {  ?>
	jQuery( '#feed' ).trigger('change');
	jQuery( '#display > option' ).each( function() {
		if ( jQuery( this ).val() == "<?php echo $nxf_dashboard_model->instance->display_id ?>" ) {
			jQuery( this ).prop('selected', true);
			jQuery( '#display' ).trigger('change');
		} 
	});
	<?php } ?>
});
</script>