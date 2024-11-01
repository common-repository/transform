<?php

global $nxf_transform, $nxf_dashboard;

require_once( 'models/nxf_dashboard_model.php' );
require_once( 'views/nxf_dashboard_view.php' );

define( 'NXF_MAX_INSTANCES', 5 );

// the name of the settings page for the license input to be displayed
define( 'NXF_LICENSE_PAGE', 'transform-license-handle' );

if ( ! class_exists( 'NXF_Dashboard' )) {
	class NXF_Dashboard {
		public function __construct() {
			add_action( 'admin_menu', array( 'NXF_Dashboard', 'transform_menu' ) );
			add_action( 'media_buttons', array( 'NXF_Dashboard', 'transform_media_buttons' ), 99 );
			add_action( 'admin_init', array( 'NXF_Dashboard', 'transform_register_option' ) );
			add_action( 'admin_init',  array( 'NXF_Dashboard', 'transform_activate_license' ) );
			add_action( 'admin_init',  array( 'NXF_Dashboard', 'transform_deactivate_license' ) );
			add_action( 'admin_enqueue_scripts', array( 'NXF_Dashboard', 'transform_enqueue_styles' ) );
			add_action( 'init', array( 'NXF_Dashboard', 'transform_block' ) );
		}
		
		public function admin_init() {
			global $wp_scripts;

                        /* AJAX actions for the admin */
                        add_action( 'wp_ajax_ajax-nxf-newfeed', array( 'NXF_Dashboard', 'transform_newfeed' ) );
                        add_action( 'wp_ajax_ajax-nxf-editfeed', array( 'NXF_Dashboard', 'transform_editfeed' ) );
                        add_action( 'wp_ajax_ajax-nxf-newinstance', array( 'NXF_Dashboard', 'transform_newinstance' ) );
                        add_action( 'wp_ajax_ajax-nxf-editinstance', array( 'NXF_Dashboard', 'transform_editinstance' ) );
                        add_action( 'wp_ajax_ajax-nxf-editfeedtype', array( 'NXF_Dashboard', 'transform_editfeedtype' ) );
                        add_action( 'wp_ajax_ajax-nxf-newfeedtype', array( 'NXF_Dashboard', 'transform_newfeedtype' ) );
                        add_action( 'wp_ajax_ajax-nxf-editdisplay', array( 'NXF_Dashboard', 'transform_editdisplay' ) );
                        add_action( 'wp_ajax_ajax-nxf-newdisplay', array( 'NXF_Dashboard', 'transform_newdisplay' ) );
                        add_action( 'wp_ajax_ajax-nxf-listfeeddisplays', array( 'NXF_Dashboard', 'transform_listfeeddisplays' ) );
                        add_action( 'wp_ajax_ajax-nxf-editsetting', array( 'NXF_Dashboard', 'transform_editsetting' ) );
                        add_action( 'wp_ajax_ajax-nxf-newsetting', array( 'NXF_Dashboard', 'transform_newsetting' ) );
                        add_action( 'wp_ajax_ajax-nxf-dialogsubmit', array( 'NXF_Dashboard', 'transform_dialogsubmit' ) );
                        add_action( 'wp_ajax_ajax-nxf-insertform', array( 'NXF_Dashboard', 'transform_insertform' ) );
                        add_action( 'wp_ajax_ajax-nxf-bulkdelete', array( 'NXF_Dashboard', 'transform_bulkdelete' ) );

	        	wp_register_style( 'nxfTransformStylesheet', plugins_url( 'transform/css/nxf_transform.css' ) );
	       	 	wp_enqueue_script( 'nxfTransformJavascript', plugins_url( 'transform/js/nxf_transform.js' ), array( 'jquery' ));
	        	//wp_register_style( 'nxfTransformStylesheet', plugins_url( 'css/nxf_transform.css', NXF_BASENAME ) );
	        	//wp_enqueue_script( 'nxfTransformJavascript', plugins_url( 'js/nxf_transform.js', NXF_BASENAME ), array( 'jquery' ));
	        	wp_enqueue_script( 'jquery-ui-core' );
	        	wp_enqueue_script( 'jquery-ui-datepicker' );
        	
	        	wp_localize_script( 'nxfTransformJavascript', 'PT_Ajax', array(
					'ajaxurl'       => admin_url( 'admin-ajax.php' ),
					'nextNonce'     => wp_create_nonce( 'nxf-next-nonce' ),
					
					/* setting popup buttons */
					'editTransformSetting'	=> __( 'Edit Transform Setting', 'nxf-transform' ),
					'updateSetting' => __( 'Update Setting', 'nxf-transform' ),
					'newTransformSetting' => __( 'New Transform Setting', 'nxf-transform' ),
					'addNewSetting' => __( 'Add New Setting', 'nxf-transform' ),
					
					/* feed popup buttons */
					'newTransformFeed' => __( 'New Transform Feed', 'nxf-transform' ),
					'addNewFeed' => __( 'Add New Feed', 'nxf-transform' ),
					'editTransformFeed' => __( 'Edit Transform Feed', 'nxf-transform' ),
					'updateFeed' => __( 'Update Feed', 'nxf-transform' ),
					'addTransformFeed' => __( 'Add Transform Feed', 'nxf-transform' ),
					'insert' => __( 'Insert', 'nxf-transform' ),
					
					/* instance popup buttons */
					'editTransformInstance' => __( 'Edit Transform Instance', 'nxf-transform' ),
					'updateInstance' => __( 'Update Instance', 'nxf-transform' ),
					'newTransformInstance' => __( 'New Transform Instance', 'nxf-transform' ),
					'addNewInstance' => __( 'Add New Instance', 'nxf-transform' ),
					
					/* display popup buttons */
					'newFeedDisplay' => __( 'New Feed Display', 'nxf-transform' ),
					'addNewDisplay' => __( 'Add New Display', 'nxf-transform' ),
					'editTransformDisplay' => __( 'Edit Transform Display', 'nxf-transform' ),
					'updateDisplay' => __( 'Update Display', 'nxf-transform' ),
					'feedTypeDisplays' => __( 'Feed Type Displays', 'nxf-transform' ),
					'selectTransformFeedDisplay' => __( 'Select Transform Feed Display', 'nxf-transform' ),
					'setFeedDisplay' => __( 'Set Feed Display', 'nxf-transform' ),
					
					/* feed type popup buttons */
					'editTransformFeedType' => __( 'Edit Transform Feed Type', 'nxf-transform' ),
					'updateFeedType' => __( 'Update Feed Type', 'nxf-transform' ),
					'newTransformFeedType' => __( 'New Transform Feed Type', 'nxf-transform' ),
					'addNewFeedType' => __( 'Add New Feed Type', 'nxf-transform' ),
					
					/* shortcode popup */
					'transformFeedShortcode' => __( 'Transform Feed Shortcode', 'nxf-transform' ),
					
					/* popup cancel button */
					'cancel' => __( 'Cancel', 'nxf-transform' ),
					'close' => __( 'Close', 'nxf-transform' ),
					
					/* other text in dialogs and javascript */
					'noParams' => __( 'This display does not have parameters', 'nxf-transform' ),
					'selectDisplay' => __( 'Please select a display', 'nxf-transform' ),
					'bulkSure' => __( 'Are you sure you want to delete the selected ', 'nxf-transform' ),
					'bulkQM' => __( '?', 'nxf-transform' ),
					'noneSelected' => __( 'Please check at least one checkbox', 'nxf-transform' ),
					
					'defaultResponse' => 'The response back was '
				)
			);
        }
		
		public static function transform_menu() {
			add_menu_page( __( 'Transform Feeds', 'nxf-transform' ), __( 'Transform', 'nxf-transform' ), 'manage_options', 'transform-handle', array( 'NXF_Dashboard', 'transform_instances' ) );
			add_submenu_page( 'transform-handle', __( 'Feeds', 'nxf-transform' ), __( 'Feeds', 'nxf-transform' ), 'manage_options', 'transform-listfeeds-handle', array( 'NXF_Dashboard', 'transform_feeds' ) );
			add_submenu_page( 'transform-handle', __( 'Displays', 'nxf-transform' ), __( 'Displays', 'nxf-transform' ), 'manage_options', 'transform-listdisplays-handle', array( 'NXF_Dashboard', 'transform_listdisplays' ) );
			add_submenu_page( 'transform-handle', __( 'Feed Types', 'nxf-transform' ), __( 'Feed Types', 'nxf-transform' ), 'manage_options', 'transform-listfeedtypes-handle', array( 'NXF_Dashboard', 'transform_listfeedtypes' ) );
			add_submenu_page( 'transform-handle', __( 'Settings', 'nxf-transform' ), __( 'Settings', 'nxf-transform' ), 'manage_options', 'transform-settings-handle', array( 'NXF_Dashboard', 'transform_settings' ) );
			add_submenu_page( 'transform-handle', __( 'License', 'nxf-transform' ), __( 'License', 'nxf-transform' ), 'manage_options', 'transform-license-handle', array( 'NXF_Dashboard', 'transform_license' ) );
		}
		
		public static function transform_enqueue_styles() {
			wp_enqueue_style( 'nxfTransformStylesheet' );
		}
		
		public static function transform_instances() {
			$nxf_dashboard_model = new NXF_Dashboard_Model( 'instancelist' );
			
			NXF_Dashboard_View::instances( $nxf_dashboard_model );
		}

		public static function transform_feeds() {
			$nxf_dashboard_model = new NXF_Dashboard_Model( 'feedlist' );
			
			NXF_Dashboard_View::page( $nxf_dashboard_model );
		}
		
		public static function transform_dialogsubmit() {
			$nonce = $_POST['nextNonce']; 	
			if ( ! wp_verify_nonce( $nonce, 'nxf-next-nonce' ) ) {
				header( 'Content-Type: application/json' );
				echo json_encode( array( 'error' => __( 'Nonce verification failed! Try reloading this page then resubmitting your request.', 'nxf-transform' ) ) );
			} else {
				// strip the slashes from posted data
				$_POST = stripslashes_deep( $_POST );
				
				switch ( $_POST[ 'data' ][ 'action' ] ) {
					/* Feed options */
					case 'new-feed':
						$nxf_dashboard_model = new NXF_Dashboard_Model( 'newfeed' );
						if ( '' != $nxf_dashboard_model->error ) {
							$response = json_encode( array( 'inline-error' => $nxf_dashboard_model->error ) );
						} else {
							$response = json_encode( array( 'window' => 'reload' ) );
						}
						break;
						
					case 'delete-feed':
						$nxf_dashboard_model = new NXF_Dashboard_Model( 'deletefeed', sanitize_text_field( $_POST[ 'data' ][ 'id' ] ) );
						if ( '' != $nxf_dashboard_model->error ) {
							$response = json_encode( array( 'inline-error' => $nxf_dashboard_model->error ) );
						} else {
							$response = json_encode( array( 'window' => 'reload' ) );
						}
						break;
					
					case 'edit-feed':
						$nxf_dashboard_model = new NXF_Dashboard_Model( 'editfeed', sanitize_text_field( $_POST[ 'data' ][ 'id' ] ) );
						if ( '' != $nxf_dashboard_model->error ) {
							$response = json_encode( array( 'inline-error' => $nxf_dashboard_model->error ) );
						} else {
							$response = json_encode( array( 'window' => 'reload' ) );
						}
						break;
						
					/* Instance options */
					case 'delete-instance':
						$nxf_dashboard_model = new NXF_Dashboard_Model( 'deleteinstance', sanitize_text_field( $_POST[ 'data' ][ 'id' ] ) );
						if ( '' != $nxf_dashboard_model->error ) {
							$response = json_encode( array( 'error' => $nxf_dashboard_model->error ) );
						} else {
							$response = json_encode( array( 'window' => 'reload' ) );
						}
						break;
						
					case 'edit-instance':
						$nxf_dashboard_model = new NXF_Dashboard_Model( 'editinstance', sanitize_text_field( $_POST[ 'data' ][ 'id' ] ) );
						if ( '' != $nxf_dashboard_model->error ) {
							$response = json_encode( array( 'inline-error' => $nxf_dashboard_model->error ) );
						} else {
							$response = json_encode( array( 'window' => 'reload' ) );
						}
						break;
						
					case 'new-instance':
						$nxf_dashboard_model = new NXF_Dashboard_Model( 'newinstance' );
						if ( '' != $nxf_dashboard_model->error ) {
							$response = json_encode( array( 'inline-error' => $nxf_dashboard_model->error ) );
						} else {
							$response = json_encode( array( 'window' => 'reload' ) );
						}
						break;
						
					/* Setting options */
					case 'delete-setting':
						$nxf_dashboard_model = new NXF_Dashboard_Model( 'deletesetting', sanitize_text_field( $_POST[ 'data' ][ 'id' ] ) );
						if ( '' != $nxf_dashboard_model->error ) {
							$response = json_encode( array( 'error' => $nxf_dashboard_model->error ) );
						} else {
							$response = json_encode( array( 'window' => 'reload' ) );
						}
						break;
						
					case 'edit-setting':
						$nxf_dashboard_model = new NXF_Dashboard_Model( 'editsetting', sanitize_text_field( $_POST[ 'data' ][ 'id' ] ) );
						if ( '' != $nxf_dashboard_model->error ) {
							$response = json_encode( array( 'inline-error' => $nxf_dashboard_model->error ) );
						} else {
							$response = json_encode( array( 'window' => 'reload' ) );
						}
						break;
						
					case 'new-setting':
						$nxf_dashboard_model = new NXF_Dashboard_Model( 'newsetting' );
						if ( '' != $nxf_dashboard_model->error ) {
							$response = json_encode( array( 'inline-error' => $nxf_dashboard_model->error ) );
						} else {
							$response = json_encode( array( 'window' => 'reload' ) );
						}
						break;
						
					/* Feed Type options */
					case 'delete-feedtype':
						$nxf_dashboard_model = new NXF_Dashboard_Model( 'deletefeedtype', sanitize_text_field( $_POST[ 'data' ][ 'id' ] ) );
						if ( '' != $nxf_dashboard_model->error ) {
							$response = json_encode( array( 'inline-error' => $nxf_dashboard_model->error ) );
						} else {
							$response = json_encode( array( 'window' => 'reload' ) );
						}
						break;
						
					case 'edit-feedtype':
						$nxf_dashboard_model = new NXF_Dashboard_Model( 'editfeedtype', sanitize_text_field( $_POST[ 'data' ][ 'id' ] ) );
						if ( '' != $nxf_dashboard_model->error ) {
							$response = json_encode( array( 'inline-error' => $nxf_dashboard_model->error ) );
						} else {
							$response = json_encode( array( 'window' => 'reload' ) );
						}
						break;
					
					case 'new-feedtype':
						$nxf_dashboard_model = new NXF_Dashboard_Model( 'newfeedtype' );
						if ( '' != $nxf_dashboard_model->error ) {
							$response = json_encode( array( 'inline-error' => $nxf_dashboard_model->error ) );
						} else {
							$response = json_encode( array( 'window' => 'reload' ) );
						}
						break;
						
					/* display options */
					case 'delete-display':
						$nxf_dashboard_model = new NXF_Dashboard_Model( 'deletedisplay', sanitize_text_field( $_POST[ 'data' ][ 'id' ] ) );
						if ( '' != $nxf_dashboard_model->error ) {
							$response = json_encode( array( 'inline-error' => $nxf_dashboard_model->error ) );
						} else {
							$response = json_encode( array( 'window' => 'reload' ) );
						}
						break;
						
					case 'edit-display':
						$nxf_dashboard_model = new NXF_Dashboard_Model( 'editdisplay', sanitize_text_field( $_POST[ 'data' ][ 'id' ] ) );
						if ( '' != $nxf_dashboard_model->error ) {
							$response = json_encode( array( 'inline-error' => $nxf_dashboard_model->error ) );
						} else {
							$response = json_encode( array( 'window' => 'reload' ) );
						}
						break;
					
					case 'new-display':
						$nxf_dashboard_model = new NXF_Dashboard_Model( 'newdisplay' );
						if ( '' != $nxf_dashboard_model->error ) {
							$response = json_encode( array( 'inline-error' => $nxf_dashboard_model->error ) );
						} else {
							$response = json_encode( array( 'window' => 'reload' ) );
						}
						break;
						
					/* shortcode options */
					case 'insert-shortcode':
						if ( '' == $_POST[ 'data' ][ 'instance' ] ) {
							$response = json_encode( array( 'inline-error' => __( 'Please select a valid instance', 'nxf-transform' ) ) );
						} else {
							$nxf_dashboard_model = new NXF_Dashboard_Model( 'newshortcode' );
							$response = json_encode( array( 'shortcode' => '[transform_feed instance="' . $nxf_dashboard_model->instance->ID . '"]' ) );
						}
						break;
						
					/* unknown option */
					default:
						$response = json_encode( array( 'inline-error' => sprintf( __( 'Unknown AJAX command: "%s"', 'nxf-transform' ), sanitize_text_field( $_POST[ 'data' ][ 'action' ] ) ) ) );
						break;
				
				}
			
				//$nxf_dashboard_model = new NXF_Dashboard_Model( 'feedlist' );
				//$response = NXF_Dashboard_View::newfeed( $nxf_dashboard_model );
				//$response = json_encode( array( 'html' => "This works..." ) );
 
				header( 'Content-Type: application/json' );
				echo $response;
			}

			exit;
		}
		
		public static function transform_insertform() {
			$nonce = $_POST['nextNonce']; 	
			if ( ! wp_verify_nonce( $nonce, 'nxf-next-nonce' ) ) {
				header( 'Content-Type: application/json' );
				echo json_encode( array( 'error' => __( 'Nonce verification failed! Try reloading this page then resubmitting your request.', 'nxf-transform' ) ) );
			} else {
				$nxf_dashboard_model = new NXF_Dashboard_Model( 'shortcode' );
				$response = NXF_Dashboard_View::insertform( $nxf_dashboard_model );
 
				header( 'Content-Type: application/json' );
				echo $response;
			}

			exit;
		}
		
		public static function transform_media_buttons() {
			wp_enqueue_script('jquery-ui-dialog');
			wp_enqueue_style( 'wp-jquery-ui-dialog' );
			wp_enqueue_style( 'nxfTransformStylesheet' );
			
			print '<a href="#" id="insert-transform-media" class="button">' . __( 'Add a Transform', 'nxf-transform' ) . '</a>';
		}
		
		public static function transform_register_option() {
			register_setting('nxf_license', 'nxf_license_key', array( 'NXF_Dashboard', 'transform_sanitize_license' ) );
		}
		
		function transform_sanitize_license( $new ) {
			$old = get_option( 'nxf_license_key' );
			if( $old && $old != $new ) {
				delete_option( 'nxf_license_status' ); // new license has been entered, so must reactivate
			}
			return $new;
		}
		
		public static function transform_activate_license() {
			if( isset( $_POST[ 'nxf_license_activate' ] ) ) {
				$nxf_dashboard_model = new NXF_Dashboard_Model( 'activate' );
			
				wp_redirect( admin_url( 'admin.php?page=' . NXF_LICENSE_PAGE . '&update=1' ) );
				exit();
			}
		}
		
		public static function transform_deactivate_license() {
			if( isset( $_POST[ 'nxf_license_deactivate' ] ) ) {
				$nxf_dashboard_model = new NXF_Dashboard_Model( 'deactivate' );
				
				wp_redirect( admin_url( 'admin.php?page=' . NXF_LICENSE_PAGE . '&deactivate=1' ) );
				exit();
			}
		}
		
		public static function transform_editinstance() {
			$nonce = $_POST['nextNonce']; 	
			if ( ! wp_verify_nonce( $nonce, 'nxf-next-nonce' ) ) {
				header( 'Content-Type: application/json' );
				echo json_encode( array( 'error' => __( 'Nonce verification failed! Try reloading this page then resubmitting your request.', 'nxf-transform' ) ) );
			} else {
				$nxf_dashboard_model = new NXF_Dashboard_Model( 'instance', sanitize_text_field( $_POST[ 'id' ] ) );
				$response = NXF_Dashboard_View::editinstance( $nxf_dashboard_model );
 
				header( 'Content-Type: application/json' );
				echo $response;
			}

			exit;
		}
		
		public static function transform_newinstance() {
			$nonce = $_POST['nextNonce']; 	
			if ( ! wp_verify_nonce( $nonce, 'nxf-next-nonce' ) ) {
				header( 'Content-Type: application/json' );
				echo json_encode( array( 'error' => __( 'Nonce verification failed! Try reloading this page then resubmitting your request.', 'nxf-transform' ) ) );
			} else {
				$nxf_dashboard_model = new NXF_Dashboard_Model( 'instanceoptions' );
				
				// if the license is not valid make sure this install doesn't have more than X instances defined
				if ( 'invalid' == $nxf_dashboard_model->license_check->license && $nxf_dashboard_model->total_instances >= NXF_MAX_INSTANCES ) {
					header( 'Content-Type: application/json' );
					echo json_encode( array( 'error' => sprintf( __( 'The unlicensed version of Transform only allows %d instances. Delete an instance to add a new one or purchase a Transform license.', 'nxf-transform' ), NXF_MAX_INSTANCES ) ) );			
				} else {
					$response = NXF_Dashboard_View::newinstance( $nxf_dashboard_model );
 
					header( 'Content-Type: application/json' );
					echo $response;
				}
			}

			exit;
		}

		public static function transform_newfeed() {
			$nonce = $_POST['nextNonce']; 	
			if ( ! wp_verify_nonce( $nonce, 'nxf-next-nonce' ) ) {
				header( 'Content-Type: application/json' );
				echo json_encode( array( 'error' => __( 'Nonce verification failed! Try reloading this page then resubmitting your request.', 'nxf-transform' ) ) );
			} else {
				$nxf_dashboard_model = new NXF_Dashboard_Model( 'listfeedtypes' );
				$response = NXF_Dashboard_View::newfeed( $nxf_dashboard_model );
 
				header( 'Content-Type: application/json' );
				echo $response;
			}
 
			exit;
		}
		
		/* ---- SETTING AJAX COMMANDS --- */
		public static function transform_editsetting() {
			$nonce = $_POST['nextNonce']; 	
			if ( ! wp_verify_nonce( $nonce, 'nxf-next-nonce' ) ) {
				header( 'Content-Type: application/json' );
				echo json_encode( array( 'error' => __( 'Nonce verification failed! Try reloading this page then resubmitting your request.', 'nxf-transform' ) ) );
			} else {
				$nxf_dashboard_model = new NXF_Dashboard_Model( 'setting', sanitize_text_field( $_POST[ 'id' ] ) );
				$response = NXF_Dashboard_View::editsetting( $nxf_dashboard_model );
 
				header( 'Content-Type: application/json' );
				echo $response;
			}
 
			exit;
		}
		
		public static function transform_newsetting() {
			$nonce = $_POST['nextNonce']; 	
			if ( ! wp_verify_nonce( $nonce, 'nxf-next-nonce' ) ) {
				header( 'Content-Type: application/json' );
				echo json_encode( array( 'error' => __( 'Nonce verification failed! Try reloading this page then resubmitting your request.', 'nxf-transform' ) ) );
			} else {
				$nxf_dashboard_model = new NXF_Dashboard_Model( 'settings' );
				$response = NXF_Dashboard_View::newsetting( $nxf_dashboard_model );
 
				header( 'Content-Type: application/json' );
				echo $response;
			}
 
			exit;
		}
		
		public static function transform_editfeed() {
			$nonce = $_POST['nextNonce']; 	
			if ( ! wp_verify_nonce( $nonce, 'nxf-next-nonce' ) ) {
				header( 'Content-Type: application/json' );
				echo json_encode( array( 'error' => __( 'Nonce verification failed! Try reloading this page then resubmitting your request.', 'nxf-transform' ) ) );
			} else {
				$nxf_dashboard_model = new NXF_Dashboard_Model( 'feed', sanitize_text_field( $_POST[ 'id' ] ) );
				$response = NXF_Dashboard_View::editfeed( $nxf_dashboard_model );
 
				header( 'Content-Type: application/json' );
				echo $response;
			}
 
			exit;
		}
		
		/*
		public static function transform_editfeeddisplay() {
			$nonce = $_POST['nextNonce']; 	
			if ( ! wp_verify_nonce( $nonce, 'nxf-next-nonce' ) ) {
				header( 'Content-Type: application/json' );
				echo json_encode( array( 'error' => __( 'Nonce verification failed! Try reloading this page then resubmitting your request.', 'nxf-transform' ) ) );
			} else {
				$nxf_dashboard_model = new NXF_Dashboard_Model( 'feeddisplay', $_POST[ 'id' ] );
				$response = NXF_Dashboard_View::editfeeddisplay( $nxf_dashboard_model );
 
				header( 'Content-Type: application/json' );
				echo $response;
			}

			exit;
		}
		*/
		
		public static function transform_editfeedtype() {
			$nonce = $_POST['nextNonce']; 	
			if ( ! wp_verify_nonce( $nonce, 'nxf-next-nonce' ) ) {
				header( 'Content-Type: application/json' );
				echo json_encode( array( 'error' => __( 'Nonce verification failed! Try reloading this page then resubmitting your request.', 'nxf-transform' ) ) );
			} else {
				$nxf_dashboard_model = new NXF_Dashboard_Model( 'feedtype', sanitize_text_field( $_POST[ 'id' ] ) );
				$response = NXF_Dashboard_View::editfeedtype( $nxf_dashboard_model );
 
				header( 'Content-Type: application/json' );
				echo $response;
			}
 
			exit;
		}
		
		public static function transform_newfeedtype() {
			$nonce = $_POST['nextNonce']; 	
			if ( ! wp_verify_nonce( $nonce, 'nxf-next-nonce' ) ) {
				header( 'Content-Type: application/json' );
				echo json_encode( array( 'error' => __( 'Nonce verification failed! Try reloading this page then resubmitting your request.', 'nxf-transform' ) ) );
			} else {
				$nxf_dashboard_model = new NXF_Dashboard_Model( 'settings' );
				$response = NXF_Dashboard_View::editfeedtype( $nxf_dashboard_model );
 
				header( 'Content-Type: application/json' );
				echo $response;
			}

			exit;
		}
		
		/* Display functions */
		public static function transform_editdisplay() {
			$nonce = $_POST['nextNonce']; 	
			if ( ! wp_verify_nonce( $nonce, 'nxf-next-nonce' ) ) {
				header( 'Content-Type: application/json' );
				echo json_encode( array( 'error' => __( 'Nonce verification failed! Try reloading this page then resubmitting your request.', 'nxf-transform' ) ) );
			} else {
				$nxf_dashboard_model = new NXF_Dashboard_Model( 'display', sanitize_text_field( $_POST[ 'id' ] ) );
				$response = NXF_Dashboard_View::newdisplay( $nxf_dashboard_model );
 
				header( 'Content-Type: application/json' );
				echo $response;
			}
 
			exit;
		}
		
		public static function transform_newdisplay() {
			$nonce = $_POST['nextNonce']; 	
			if ( ! wp_verify_nonce( $nonce, 'nxf-next-nonce' ) ) {
				header( 'Content-Type: application/json' );
				echo json_encode( array( 'error' => __( 'Nonce verification failed! Try reloading this page then resubmitting your request.', 'nxf-transform' ) ) );
			} else {
				// get a list of the feed types
				$nxf_dashboard_model = new NXF_Dashboard_Model( 'listfeedtypes' );
				$response = NXF_Dashboard_View::newdisplay( $nxf_dashboard_model );
 
				header( 'Content-Type: application/json' );
				echo $response;
			}

			exit;
		}
		
		/*
		public static function transform_feedshortcode() {
			$nonce = $_POST['nextNonce']; 	
			if ( ! wp_verify_nonce( $nonce, 'nxf-next-nonce' ) ) {
				header( 'Content-Type: application/json' );
				echo json_encode( array( 'error' => __( 'Nonce verification failed! Try reloading this page then resubmitting your request.', 'nxf-transform' ) ) );
			} else {
				$nxf_dashboard_model = new NXF_Dashboard_Model( 'feed', $_POST[ 'id' ] );
				$response = NXF_Dashboard_View::feedshortcode( $nxf_dashboard_model );
 
				header( 'Content-Type: application/json' );
				echo $response;
			}
 
			// IMPORTANT: don't forget to "exit"
			exit;
		}
		*/
		
		public static function transform_listfeeddisplays() {
			$nonce = $_POST['nextNonce']; 	
			if ( ! wp_verify_nonce( $nonce, 'nxf-next-nonce' ) ) {
				header( 'Content-Type: application/json' );
				echo json_encode( array( 'error' => __( 'Nonce verification failed! Try reloading this page then resubmitting your request.', 'nxf-transform' ) ) );
			} else {
				$nxf_dashboard_model = new NXF_Dashboard_Model( 'feedtypedisplays', sanitize_text_field( $_POST[ 'id' ] ) );
				$response = NXF_Dashboard_View::listfeeddisplays( $nxf_dashboard_model );
 
				header( 'Content-Type: application/json' );
				echo $response;
			}

			exit;
		}
		
		public static function transform_listdisplays() {
			$nxf_dashboard_model = new NXF_Dashboard_Model( 'displays' );
			
			NXF_Dashboard_View::displays( $nxf_dashboard_model );
		}
		
		public static function transform_listfeedtypes() {
			$nxf_dashboard_model = new NXF_Dashboard_Model( 'listfeedtypes' );
			
			NXF_Dashboard_View::listfeedtypes( $nxf_dashboard_model );
		}
		
		public static function transform_settings() {
			$nxf_dashboard_model = new NXF_Dashboard_Model( 'settings' );
			
			NXF_Dashboard_View::settings( $nxf_dashboard_model );
		}
		
		public static function transform_license() {
			$nxf_dashboard_model = new NXF_Dashboard_Model( 'license' );
			
			NXF_Dashboard_View::license( $nxf_dashboard_model );
		}
		
		public static function transform_bulkdelete() {
			header( 'Content-Type: application/json' );
			
			$nonce = $_POST['nextNonce']; 	
			if ( ! wp_verify_nonce( $nonce, 'nxf-next-nonce' ) ) {
				echo json_encode( array( 'error' => __( 'Nonce verification failed! Try reloading this page then resubmitting your request.', 'nxf-transform' ) ) );
			} else {
				$nxf_dashboard_model = $nxf_dashboard_model = new NXF_Dashboard_Model( 'bulkdelete' );
				
				if ( '' != $nxf_dashboard_model->error ) {
					echo json_encode( array( 'error' => __( 'An error occurred deleting a ' . $_POST[ 'type' ] . ': ', 'nxf-transform' ) . $nxf_dashboard_model->error ) );
				} else {
					echo json_encode( array( 'window' => 'reload' ) );
				}
			}

			exit;
		}

		public static function transform_block() {
			if ( ! function_exists( 'register_block_type' ) ) {
				// Gutenberg is not active.
				return;
			}

			wp_register_script(
				'nxf-transform',
				/* plugins_url( 'js/block.js', __FILE__ ), */
				plugins_url( 'js/block.js', plugin_dir_path( __FILE__ ) ),
				array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor', 'underscore' ),
				filemtime( plugin_dir_path( __DIR__ ) . 'js/block.js' )
			);

			register_block_type( 'nxf-transform/instance-insert', array(
				'script' => 'nxf-transform',
				'attributes' => array(
					'instance' => array(
						'type' => 'string'
					),
                ),
                'render_callback' => array( 'NXF_Transform', 'render_shortcode' ),
			) );
			
			error_log("NXF: finished with register block type");

			$nxf_dashboard_model = new NXF_Dashboard_Model( 'instancelist' );

			wp_add_inline_script(
				'nxf-transform',
				sprintf( 
					'var nxf_transform = { model: ' . json_encode( $nxf_dashboard_model ) . ' };', 
					json_encode( wp_set_script_translations( 'nxf-transform', 'nxf-transform' ) ) 
				),
				'before'
			);
		}
	}
}
