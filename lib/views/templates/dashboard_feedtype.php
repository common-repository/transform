<div id="nxf-error"></div>

<form id="nxf-<?php if ( $nxf_dashboard_model->feedtype->ID == '' ) { print 'new'; } else { print 'edit'; } ?>feedtype" method="post">
	<?php if ( $nxf_dashboard_model->feedtype->ID != '' ) {  ?>
		<input type="hidden" name="id" value="<?php print $nxf_dashboard_model->feedtype->ID ?>" />
	<?php } ?>
	<input type="hidden" name="action" value="<?php if ( $nxf_dashboard_model->feedtype->ID == '' ) { print 'new'; } else { print 'edit'; } ?>-feedtype" />
	<table>
		<tr>
			<th><?php _e( 'Feed Type Name', 'nxf-transform' ); ?></th>
			<td>
				<input name="feed_type_name" value="<?php echo $nxf_dashboard_model->feedtype->feed_type_name ?>" size="40" maxlength="128" />
			</td>
		</tr>
		<tr>
			<th><?php _e( 'Feed Type Description', 'nxf-transform' ); ?></th>
			<td>
				<input name="feed_type_desc" value="<?php echo $nxf_dashboard_model->feedtype->feed_type_desc ?>" size="40" maxlength="128" />
			</td>
		</tr>
		<tr>
			<th><?php _e( 'Feed Type MIME Type', 'nxf-transform' ); ?></th>
			<td>
				<select name="feed_type_mime">
					<option value="application/json"<?php if ( $nxf_dashboard_model->feedtype->feed_type_mime == "application/json" ) { echo " selected='selected'"; } ?>>application/json</option>
					<?php if ( $nxf_dashboard_model->has_xslt ) { ?>
						<option value="text/xml"<?php if ( $nxf_dashboard_model->feedtype->feed_type_mime == "text/xml" ) { echo " selected='selected'"; } ?>>text/xml</option>
					<?php } ?>
				</select>
			</td>
		</tr>
		
		<?php if ( !$nxf_dashboard_model->has_xslt ) { ?>
			<tr>
				<th></th>
				<td>
					<div>Enable XSLT in PHP if you need support for XML/XSL transformations</div>
				</td>
			</tr>
		<?php } ?>
	</table>
</form>