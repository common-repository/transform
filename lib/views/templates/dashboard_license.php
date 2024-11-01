<div class="wrap">
	<h2><?php _e( 'Transform Licensing', 'nxf-transform' ); ?></h2>
	
	<?php if ( $_GET[ 'update' ] == '1' ) { ?>
		<div>License key successfully set</div>
	<?php } ?>
	
	<?php if ( $_GET[ 'sl_activation' ] == 'false' ) { ?>
		<div id="nxf-error">Activation failed - <?php echo $_GET[ 'message' ]; ?></div>
	<?php } ?>
	
	<?php if ( $_GET[ 'deactivate' ] == '1' ) { ?>
		<div>License deactivated</div>
	<?php } ?>
	
	<form method="post" action="options.php">
		<?php settings_fields('nxf_license'); ?>

		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row" valign="top">
						<?php _e( 'License Key', 'nxf-transform' ); ?>
					</th>
					<td>
						<input id="nxf_license_key" name="nxf_license_key" type="text" class="regular-text" value="<?php if ( 'invalid' != $nxf_dashboard_model->license_check->license ) { esc_attr_e( $nxf_dashboard_model->license ); } ?>" />
						<label class="description" for="nxf_license_key"><?php _e( 'Enter your license key', 'nxf-transform' ); ?></label>
					</td>
				</tr>
				<?php if( false !== $nxf_dashboard_model->license_check->license ) { ?>
					<tr valign="top">
						<th scope="row" valign="top">
							<?php _e( 'Activate License', 'nxf-transform' ); ?>
						</th>
						<td>
							<?php if( 'invalid' != $nxf_dashboard_model->license_check->license ) { ?>
								<span style="color:green;"><?php _e('active'); ?></span>
								<?php wp_nonce_field( 'nxf_nonce', 'nxf_nonce' ); ?>
								<input type="submit" class="button-secondary" name="nxf_license_deactivate" value="<?php _e( 'Deactivate License', 'nxf-transform' ); ?>"/>
							<?php } else {
								wp_nonce_field( 'nxf_nonce', 'nxf_nonce' ); ?>
								<input type="submit" class="button-secondary" name="nxf_license_activate" value="<?php _e( 'Activate License', 'nxf-transform' ); ?>"/>
							<?php } ?>
						</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</form>
</div>