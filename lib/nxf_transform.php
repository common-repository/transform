<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require_once( 'models/nxf_transform_model.php' );
require_once( 'views/nxf_transform_view.php' );

global $nxf_transform, $nxf_dashboard;

if ( ! class_exists( 'NXF_Transform' )) {
	class NXF_Transform {
        public function __construct() {
        	$dir = dirname( __FILE__ );
			require_once( "$dir/nxf_dashboard.php" );
			
			$nxf_dashboard = new NXF_Dashboard();
			
			if ( !is_admin() ) {
				add_action( 'wp', array( 'NXF_Transform', 'init' ) );
			} else {
				add_action( 'admin_init', array( 'NXF_Dashboard', 'admin_init' ) );
			}
        }
        
        public function init() {
        	add_shortcode( 'transform_feed', array( 'NXF_Transform', 'transform_feed' ) );
        }
        
        public function transform_feed( $atts ) {
        	extract( shortcode_atts( array(
				'instance' => '',
				'debug' => '0',
			), $atts ) );
			
			if ( '' == $instance ) {
				return __( '["instance" attribute not specified]', 'nxf-transform' );
			}
			
			$transform = new NXF_Transform_Model( $instance, $debug );
			return NXF_Transform_View::render( $transform );
        }
        
        public static function render_shortcode( $atts ) {
        	if ( $atts{'instance'} == '' ) { return ''; }
        	
			$content = NXF_Transform::transform_feed( $atts );
			return $content;
		}
	}
}

$nxf_transform = new NXF_Transform();
