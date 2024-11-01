<div id="nxf-nxf-dialog">
	<?php if ( 0 == count( $nxf_dashboard_model->instances ) ) { ?>
		<?php _e( 'There are no instances defined for this site. Please go to "Transform Instances" and add a new instance.' ); ?>
	<?php } else { ?>
		<form id="insert-shortcode" method="post">
			<input type="hidden" name="action" value="insert-shortcode" />
	
			<?php _e( 'Select a transform instance to add', 'nxf-transform' ); ?>:
	
			<p>
				<strong><?php _e( 'Transform Instance', 'nxf-transform' ); ?>:</strong>
				<select class="nxf-instance-select" id="instance" name="instance">
					<option value=""><?php _e( '- Select -', 'nxf-transform' ); ?> </option>
					<?php
					foreach ( $nxf_dashboard_model->instances as $instance ) {
						echo '<option value="' . $instance->ID . '">' . $instance->instance_name . ' - ' . $instance->feed_name . ' (' . $instance->display_name . ')</option>';
					}
					?>
				</select>
			</p>
		</form>
	<?php } ?>
</div>

<style>
.ui-dialog-titlebar-close {
    visibility: hidden!important;
}

.wp-core-ui .button-primary {
	text-shadow: none!important;
}
</style>