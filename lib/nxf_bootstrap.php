<?php
if ( !defined( 'NXF_DEBUG' ) )
        define( 'NXF_DEBUG', false );

if ( ! function_exists('nxf_error') ) {
	function nxf_error( $err_slug, $arg2 = '' ) {
		include_once( dirname(__FILE__).'/nxf_error.php');
		return ( 'old_wp' == $err_slug ) ? NXF_Error::old_wp( 'Transform', $arg2 ) : NXF_Error::error_notice( $err_slug );
	}
}
