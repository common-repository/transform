<div id="nxf-error"></div>

<form id="nxf-<?php if ( $settingid == '' ) { print 'new'; } else { print 'edit'; } ?>setting" method="post">
	<input type="hidden" name="action" value="<?php if ( $settingid == '' ) { print 'new'; } else { print 'edit'; } ?>-setting" />
	<?php if ( $settingid != '' ) {  ?>
		<input type="hidden" name="id" value="<?php print $settingid ?>" />
	<?php } ?>
	<table>
		<tr>
			<th><?php _e( 'Setting Type', 'nxf-transform' ); ?></th>
			<td>
				<select name="settingtype" id="settingtype">
					<option value="Text"<?php if ( $settingtype == '' || $settingtype == 'Text' ) { ?> selected="selected"<?php } ?>><?php _e( 'Text', 'nxf-transform' ); ?></option>
					<option value="Date"<?php if ( $settingtype == 'Date' ) { ?> selected="selected"<?php } ?>><?php _e( 'Date (YYYY-MM-DD)', 'nxf-transform' ); ?></option>
					<option value="Numeric"<?php if ( $settingtype == 'Numeric' ) { ?> selected="selected"<?php } ?>><?php _e( 'Numeric', 'nxf-transform' ); ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<th><?php _e( 'Setting Name', 'nxf-transform' ); ?></th>
			<td><input type="text" name="settingname" value="<?php echo $settingname ?>" size="40" maxlength="128" /></td>
		</tr>
		<tr>
			<th><?php _e( 'Setting Value', 'nxf-transform' ); ?></th>
			<td>
			  <input type="text" name="settingvalue" id="settingvalue" value="<?php echo $settingvalue ?>" maxlength="128" />
			</td>
		</tr>
	</table>
</form>

<script type="text/javascript">
jQuery(document).ready(	
	function() {
		jQuery( '#settingtype' ).change( function () {
			if ( jQuery( '#settingtype' ).val() == 'Date' ) {
				jQuery( '#settingvalue' ).datepicker({ firstDay: 0, dateFormat: 'yy-mm-dd' });
			} else {
				jQuery( '#settingvalue' ).removeClass('calendarclass');
				jQuery( '#settingvalue' ).removeClass('hasDatepicker');
				jQuery( '#settingvalue' ).unbind();
			}
		} )
		
		if ( jQuery( '#settingtype' ).val() == 'Date' ) {
			jQuery( '#settingvalue' ).datepicker({ firstDay: 0, dateFormat: 'yy-mm-dd' });
		}
	}
);
</script>
