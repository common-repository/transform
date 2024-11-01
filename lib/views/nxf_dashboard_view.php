<?php

if ( !class_exists( NXF_Dashboard_View ) ) {
	class NXF_Dashboard_View {
		public static function page( $nxf_dashboard_model ) {
			wp_enqueue_script('jquery-ui-dialog');
			wp_enqueue_style( 'wp-jquery-ui-dialog' );
			wp_enqueue_style( 'nxfTransformStylesheet' );
			
			$items = 1 == $nxf_dashboard_model->total_feeds ? __( '1 Item', 'nxf-transform' ) : sprintf( __( '%s Items', 'nxf-transform' ), $nxf_dashboard_model->total_feeds );
			
			include( 'templates/dashboard_feeds.php' );
		}
		
		public static function newfeed( $nxf_dashboard_model ) {
			ob_start();
			include( 'templates/dashboard_feed.php' );
			$html = ob_get_clean();
			
			return json_encode( array( 'html' => $html ) );
		}
		
		public static function editfeed( $nxf_dashboard_model ) {
			ob_start();
			include( 'templates/dashboard_feed.php' );
			$html = ob_get_clean();

			return json_encode( array( 'html' => $html ) );
		}
		
		public static function newinstance( $nxf_dashboard_model ) {
			ob_start();
			include( 'templates/dashboard_instance.php' );
			$html = ob_get_clean();
			
			return json_encode( array( 'html' => $html ) );
		}
		
		public static function editinstance( $nxf_dashboard_model ) {
			ob_start();
			include( 'templates/dashboard_instance.php' );
			$html = ob_get_clean();

			return json_encode( array( 'html' => $html ) );
		}
		
		public static function insertform( $nxf_dashboard_model ) {
			ob_start();
			include( 'templates/dashboard_insertform.php' );
			$html = ob_get_clean();
			
			return json_encode( array( 'html' => $html ) );
		}
		
		public static function instances( $nxf_dashboard_model ) {
			wp_enqueue_script('jquery-ui-dialog');
			wp_enqueue_style( 'wp-jquery-ui-dialog' );
			wp_enqueue_style( 'nxfTransformStylesheet' );
			
			include( 'templates/dashboard_instances.php' );
		}
		
		public static function editsetting( $nxf_dashboard_model ) {
			$settingid = $nxf_dashboard_model->setting->ID;
			$settingtype = $nxf_dashboard_model->setting->setting_type;
			$settingname = $nxf_dashboard_model->setting->setting_name;
			$settingvalue = $nxf_dashboard_model->setting->setting_value;
			
			ob_start();
			include( 'templates/dashboard_setting.php' );
			$html = ob_get_clean();
			
			return json_encode( array( 'html' => $html ) );
		}
		
		public static function newsetting( $nxf_dashboard_model ) {
			$settingid = '';
			$settingtype = '';
			$settingname = '';
			$settingvalue = '';
			
			ob_start();
			include( 'templates/dashboard_setting.php' );
			$html = ob_get_clean();
			
			return json_encode( array( 'html' => $html ) );
		}
		
		/*
		public static function editfeeddisplay( $nxf_dashboard_model ) {
			$feedname = $nxf_dashboard_model->feed->feed_name;
			
			ob_start();
			include( 'templates/dashboard_feeddisplay.php' );
			$html = ob_get_clean();
			
			return json_encode( array( 'html' => $html ) );
		}
		*/
		
		/*
		public static function feedshortcode( $nxf_dashboard_model ) {
			ob_start();
			include( 'templates/dashboard_feedshortcode.php' );
			$html = ob_get_clean();

			return json_encode( array( 'html' => $html ) );
		}
		*/
		
		/*
		public static function listfeeddisplays( $nxf_dashboard_model ) {
			ob_start();
			include( 'templates/dashboard_feedtypedisplayslist.php' );
			$html = ob_get_clean();
			
			return json_encode( array( 'html' => $html ) );
		}
		*/
		
		public static function listfeedtypes( $nxf_dashboard_model ) {
			wp_enqueue_script('jquery-ui-dialog');
			wp_enqueue_style( 'wp-jquery-ui-dialog' );
			wp_enqueue_style( 'nxfTransformStylesheet' );
			
			$feedtypes = $nxf_dashboard_model->feedtypes;
			$items = 1 == $nxf_dashboard_model->total_feeds ? __( '1 Item', 'nxf-transform' ) : sprintf( __( '%s Items', 'nxf-transform' ), $nxf_dashboard_model->total_feeds );

			include( 'templates/dashboard_feedtypes.php' );
		}
		
		public static function editfeedtype( $nxf_dashboard_model ) {
			ob_start();
			include( 'templates/dashboard_feedtype.php' );
			$html = ob_get_clean();

			return json_encode( array( 'html' => $html ) );
		}
		
		public static function newdisplay( $nxf_dashboard_model ) {
			ob_start();
			include( 'templates/dashboard_display.php' );
			$html = ob_get_clean();
			
			return json_encode( array( 'html' => $html ) );
		}
		
		public static function displays( $nxf_dashboard_model ) {
			wp_enqueue_script('jquery-ui-dialog');
			wp_enqueue_style( 'wp-jquery-ui-dialog' );
			wp_enqueue_style( 'nxfTransformStylesheet' );
			
			$displays = $nxf_dashboard_model->displays;
			$items = 1 == $nxf_dashboard_model->total_displays ? __( '1 Item', 'nxf-transform' ) : sprintf( __( '%s Items', 'nxf-transform' ), $nxf_dashboard_model->total_displays );

			include( 'templates/dashboard_displays.php' );
		}
		
		public static function settings( $nxf_dashboard_model ) {
			wp_enqueue_script('jquery-ui-dialog');
			wp_enqueue_style( 'wp-jquery-ui-dialog' );
			wp_enqueue_style( 'nxfTransformStylesheet' );
			
			$settings = $nxf_dashboard_model->settings;
			$items = 1 == $nxf_dashboard_model->total_settings ? __( '1 Item', 'nxf-transform' ) : sprintf( __( '%s Items', 'nxf-transform' ), $nxf_dashboard_model->total_settings );
			
			include( 'templates/dashboard_settings.php' );
		}
		
		public static function license( $nxf_dashboard_model ) {
			include( 'templates/dashboard_license.php' );
		}
	}
}
