<?php
if ( ! class_exists('NXF_Error') ) {
	class NXF_Error {
		public static function old_nxf( $ext_title, $min_nxf_version ) {
			self::error_msg( sprintf( __('%1$s won&#39;t work until you upgrade Transforms to version %2$s or later.', 'pp'), $ext_title, $min_pp_version ) );
			return true;
		}

		public static function old_wp( $ext_title, $min_wp_version ) {
			self::error_msg( sprintf( __('%1$s won&#39;t work until you upgrade WordPress to version %2$s or later.', 'pp'), $ext_title, $min_wp_version ) );
			return false;
		}

		public static function error_notice( $err ) {
			switch( $err ) {
				case 'multiple_nxf' :
					print '<p>There are multiple copies of the Transform plugin installed, please be sure only one copy is installed in your "wp-content/plugins" folder.</p>';
					break;
				case 'noxslt_nxf':
					print '<p>Your PHP installation does not support XSLT transformations. Ask you system administrator to enable the "--with-xsl" option for PHP.</p>';
					break;
				default :
					print "<p>Unknown error: $err</p>";
					break;
			}
		}

		static function error_msg( $msg ) {
			global $pagenow;

			if ( isset( $pagenow ) && ( 'update.php' != $pagenow ) ) {
				$func_body = "echo '" .
				'<div id="message" class="error fade" style="color: black"><p><strong>' . $msg . '</strong></p></div>' .
				"';";

				add_action('all_admin_notices', create_function('', $func_body) );
			}
		}
	}
}