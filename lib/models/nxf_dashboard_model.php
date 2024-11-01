<?php

if ( !class_exists( NXF_Dashboard_Model ) ) {
	class NXF_Dashboard_Model {
		public function __construct( $area = '', $id = 0 ) {
			global $wpdb;
			
			/* does PHP support XSLT? */
			$this->has_xslt = class_exists( 'XSLTProcessor' );
			
			/* get the license information for this installation of transform */
			$this->license_check = $this->_license_check();
			
			switch ( $area ) {
				/* setting data */
				case 'deletesetting':
					$this->error = $this->_delete_setting( $id );
					break;
					
				case 'editsetting':
					$this->error = $this->_edit_setting( $id );
					break;

 				case 'newsetting':
					/* create a new setting in the database */
					$this->error = $this->_new_setting( $id );
					break;
					
				case 'setting':
					/* get the specified setting */
					$this->setting = $this->_setting( $id );
					break;
					
				case 'settings':
					/* show settings */
					$this->settings = $this->_settings();
					$this->total_settings = count( $this->settings );
					$this->site_url = get_site_url();
					break;
				
				/* feed data */
				case 'deletefeed':
					$this->error = $this->_delete_feed( $id );
					break;
					
				case 'editfeed':
					/* edit the feed */
					$this->error = $this->_edit_feed( $id );
					break;
					
				case 'feedlist':
					/* get a list of feeds and total number of feeds */
					$this->feeds = $this->_get_feeds( get_current_blog_id() );
					$this->total_feeds = count( $this->feeds );
					$this->site_url = get_site_url();
					break;
					
				case 'feed':
					/* get a specific feed */
					$this->feed = $this->_get_feed( $id );
					
					/* get the parameters for this feed */
					$this->parameters = $this->_feed_parameters( $id );
					
					/* get the possible feed types */
					$this->feedtypes = $this->_get_feed_types();
					
					/* get all of the settings */
					$this->settings = $this->_settings();
					break;
					
				case 'newfeed':
					/* create a new feed in the database */
					$this->error = $this->_new_feed();
					break;
					
				/* instance data */
				case 'deleteinstance':
					$this->error = $this->_delete_instance( $id );
					break;
					
				case 'editinstance':
					/* edit the instance */
					$this->error = $this->_edit_instance( $id );
					break;
					
				case 'instanceoptions':
					/* get the list of feeds and their displays and the settings */
					$this->feeds = $this->_get_feeds( get_current_blog_id(), true );
					$this->total_feeds = count( $this->feeds );
					$this->settings = $this->_settings();
					
					$this->total_instances = count( $this->_get_instances( get_current_blog_id() ) );
					
					break;
					
				case 'instance':
					/* get a specific instance, all feeds, all displays and all settings */
					$this->instance = $this->_get_instance( sanitize_text_field( $_POST[ 'id' ] ) );
					// error_log( "KAM: way up from instance ID=".$this->instance->ID );
					$this->feeds = $this->_get_feeds( get_current_blog_id(), true, $this->instance->ID );
					$this->total_feeds = count( $this->feeds );
					$this->settings = $this->_settings();
					break;
					
				case 'instancelist':
					/* get a list of instances and the total number of instances */
					$this->instances = $this->_get_instances( get_current_blog_id() );
					$this->total_instances = count( $this->instances );
					$this->site_url = get_site_url();
					break;
					
				case 'newinstance':
					/* create a new instance in the database */
					$this->error = $this->_new_instance( sanitize_text_field( $_POST[ 'data' ][ 'feed' ] ), sanitize_text_field( $_POST[ 'data' ][ 'display' ] ), sanitize_text_field( $_POST[ 'data' ][ 'instance_name' ] ) );
					break;
				
				/* display data */
				case 'feeddisplay':
					/* get the specific feed */
					$this->feed = $this->_get_feed( $id );
					
					/* get the feed displays for this feed type */
					$this->displays = $this->_get_feed_displays( $this->feed->feedtypeid );
					break;

				case 'deletedisplay':
					/* delete a display */
					$this->error = $this->_delete_display( $id );
					break;
					
				case 'display':
					/* get the specified display */
					$this->display = $this->_display( $id );
					$this->feedtypes = $this->_get_feed_types();
					$this->parameters = $this->_display_parameters( 0, $id );
					$this->settings = $this->_settings();
					break;
					
				case 'editdisplay':
					/* edit the display */
					$this->error = $this->_edit_display( $id );
					break;
					
				case 'newdisplay':
					/* create a new display */
					$this->error = $this->_new_display( $id );
					break;
					
				case 'displays':
					/* get data for all displays and the count */
					$this->displays = $this->_displays();
					$this->total_displays = count( $this->displays );
					$this->site_url = get_site_url();
					break;
				
				/* feed type data */	
				case 'deletefeedtype':
					/* delete a feed type */
					$this->error = $this->_delete_feedtype( $id );
					break;
					
				case 'editfeedtype':
					/* update a feed type */
					$this->error = $this->_edit_feedtype( $id );
					break;
					
				case 'feedtypedisplays':
					/* get the feed displays for this feed type */
					$this->displays = $this->_get_feed_displays( $id );
					break;
					
				case 'listfeedtypes':
					/* get the list of feed types */
					$this->feedtypes = $this->_get_feed_types();
					$this->total_feedtypes = count( $this->feedtypes );
					$this->parameters = $this->_display_parameters( 0, $id );
					$this->settings = $this->_settings();
					$this->site_url = get_site_url();
					break;
					
				case 'newfeedtype':
					/* create a new feed type in the database */
					$this->error = $this->_new_feed_type( $id );
					
				case 'feedtype':
					/* get a feed type */
					$this->feedtype = $this->_get_feed_type( $id );
					break;
									
				/* shortcode data */
				case 'shortcode':
					/* get the list of feeds and their displays */
					/* get a list of instances and the total number of instances */
					$this->instances = $this->_get_instances( get_current_blog_id() );
					$this->total_instances = count( $this->instances );
					break;
					
				case 'newshortcode':
					/* create a new instance of a shortcode */
					$this->instance = $this->_get_instance( sanitize_text_field( $_POST[ 'data' ][ 'instance' ] ) );
					break;
					
				case 'bulkdelete':
					/* handle a bulk delete request */
					$this->error = $this->_bulk_delete( sanitize_text_field( $_POST[ 'type' ] ), $_POST[ 'ids' ] );
					break;
					
				case 'license':
					/* get the current license */
					$this->license = get_option( 'nxf_license_key' );
					break;
					
				case 'activate':
					$this->error = $this->_activate();
					break;
					
				case 'deactivate':
					$this->error = $this->_deactivate();
					break;
			}
		}
		
		/* Internal functions for manipulating displays */
		private function _display( $display_id ) {
			global $wpdb;
			
			return $wpdb->get_row(
				$wpdb->prepare(
					'SELECT ' . $wpdb->prefix . 'nxf_displays.ID,
						    ' . $wpdb->prefix . 'nxf_displays.feed_type_id,
						    ' . $wpdb->prefix . 'nxf_displays.display_name,
						    ' . $wpdb->prefix . 'nxf_displays.display_desc,
						    ' . $wpdb->prefix . 'nxf_displays.display,
						    ' . $wpdb->prefix . 'nxf_displays.display_type
				       FROM ' . $wpdb->prefix . 'nxf_displays
				      WHERE ' . $wpdb->prefix . 'nxf_displays.ID=%d', $display_id
				)
			);
		}
		
		private function _displays() {
			global $wpdb;
			
			return $wpdb->get_results(
				'SELECT ' . $wpdb->prefix . 'nxf_displays.ID,
						' . $wpdb->prefix . 'nxf_displays.display_name,
						' . $wpdb->prefix . 'nxf_displays.display_desc,
						' . $wpdb->prefix . 'nxf_displays.blog_id,
						' . $wpdb->prefix . 'nxf_feed_types.feed_type_name
				   FROM ' . $wpdb->prefix . 'nxf_displays
				   JOIN ' . $wpdb->prefix . 'nxf_feed_types ON ' . $wpdb->prefix . 'nxf_feed_types.ID = ' . $wpdb->prefix . 'nxf_displays.feed_type_id
			   ORDER BY ' . $wpdb->prefix . 'nxf_displays.display_name'
			);
		}
		
		private function _delete_display( $display_id ) {
			global $wpdb;
			
			// make sure no instances are using this display
			$instances = $wpdb->get_results(
				$wpdb->prepare(
					'SELECT ' . $wpdb->prefix . 'nxf_instances.ID
					   FROM ' . $wpdb->prefix . 'nxf_instances
					  WHERE ' . $wpdb->prefix . 'nxf_instances.display_id=%d', $display_id
				)
			);
			
			if ( $instances ) {
				return __( 'There are instances using this display. Please delete those instances before deleting this display.' );
			}
			
			$wpdb->delete( $wpdb->prefix . "nxf_displays", array( 'ID' => $display_id ) );
			
			if ( $wpdb->last_error != '' ) {
				return sprintf( __( 'A database error occurred deleting the display: %s', 'nxf-transform' ), $wpdb->last_error );
			}
			
			return '';
		}
		
		private function _edit_display( $display_id ) {
			global $wpdb;
			
			//return 'Delete input is ' . $_POST[ 'data' ][ 'delete' ];
			
			if ( $_POST[ 'data' ][ 'feed_type_id' ] == '' ) { return __( 'A feed type was not selected', 'nxf-transform' ); }
			if ( $_POST[ 'data' ][ 'display_name' ] == '' ) { return __( 'A display name was not entered', 'nxf-transform' ); }
			if ( strlen( $_POST[ 'data' ][ 'display_name' ] ) > 128 ) { return __( 'The length of the display name is greater than 128 characters', 'nxf-transform' ); }
			if ( $_POST[ 'data' ][ 'display_desc' ] == '' ) { return __( 'A display description was not entered', 'nxf-transform' ); }
			if ( strlen( $_POST[ 'data' ][ 'display_desc' ] ) > 128 ) { return __( 'The length of the display description is greater than 128 characters', 'nxf-transform' ); }
			if ( $_POST[ 'data' ][ 'display_type' ] == '' ) { return __( 'A display type was not selected', 'nxf-transform' ); }
			if ( $_POST[ 'data' ][ 'display_type' ] == 'FILE' && $_POST[ 'data' ][ 'location' ] == '' ) { return __( 'A display location was not entered.', 'nxf-transform' ); }
			if ( $_POST[ 'data' ][ 'display_type' ] == 'URL' && $_POST[ 'data' ][ 'url' ] == '' ) { return __( 'A display URL was not entered.', 'nxf-transform' ); }
			if ( $_POST[ 'data' ][ 'display_type' ] == 'DB' && $_POST[ 'data' ][ 'db' ] == '' ) { return __( 'Text for the display was not entered.', 'nxf-transform' ); }
			
			// check display parameters
			$current = 1;
			for ( $i = 0; $i < $_POST[ 'data' ][ 'next' ]; $i++ ) {
				if ( $_POST[ 'data' ][ 'type-' . $i ] != '' ) {
					if ( '' == $_POST[ 'data' ][ 'name-' . $i ] ) { return sprintf( __( 'Display parameter %s does not have a name', 'nxf-transform' ), $current ); }
					if ( strlen( $_POST[ 'data' ][ 'name-' . $i ] ) > 40 ) { return __( 'Parameter names must be 40 characters or less', 'nxf-transform' ); }
					if ( '' == $_POST[ 'data' ][ 'value-' . $i ] && 'SETTING' != $_POST[ 'data' ][ 'type-' . $i ] ) { return sprintf( __( 'Display parameter %s does not have a value', 'nxf-transform' ), $current ); }
					if ( strlen( $_POST[ 'data' ][ 'value-' . $i ] ) > 128 ) { return __( 'Parameter values must be 128 characters or less', 'nxf-transform' ); }
					$current++;
				}
			}
			
			if ( $_POST[ 'data' ][ 'display_type' ] == 'FILE' ) { $display = sanitize_text_field( $_POST[ 'data' ][ 'location'] ); }
			if ( $_POST[ 'data' ][ 'display_type' ] == 'URL' ) { $display = sanitize_text_field( $_POST[ 'data' ][ 'url'] ); }
			if ( $_POST[ 'data' ][ 'display_type' ] == 'DB' ) { $display = $_POST[ 'data' ][ 'db' ]; }	// this will have HTML, don't sanitize
			
			$data = array(
				'feed_type_id' => sanitize_text_field( $_POST[ 'data' ][ 'feed_type_id' ] ),
				'display_name' => sanitize_text_field( $_POST[ 'data' ][ 'display_name' ] ),
				'display_desc' => sanitize_text_field( $_POST[ 'data' ][ 'display_desc' ] ),
				'display' => $display,
				'display_type' => sanitize_text_field( $_POST[ 'data' ][ 'display_type' ] )
				
			);
			
			$wpdb->update( $wpdb->prefix . 'nxf_displays', $data, array( 'ID' => $display_id ) );
			
			if ( $wpdb->last_error != '' ) {
				return sprintf( __( 'A database error occurred updating the display: %s', 'nxf-transform' ), $wpdb->last_error );
			}
			
			// add new settings / update existing settings
			for ( $i = 0; $i < $_POST[ 'data' ][ 'next' ]; $i++ ) {
				if ( '' != $_POST[ 'data' ][ 'type-' . $i ] ) {
					$data = array(
						'instance_id' => 0,
						'display_id' => $display_id,
						'dparam_type' => sanitize_text_field( $_POST[ 'data' ][ 'type-' . $i ] ),
						'dparam_name' => sanitize_text_field( $_POST[ 'data' ][ 'name-' . $i ] ),
						'dparam_value' => sanitize_text_field( $_POST[ 'data' ][ 'value-' . $i ] ),
						'dparam_parent_id' => 0
					);
					
					if ( '' != $_POST[ 'data' ][ 'id-' . $i ] ) {
						$wpdb->update( $wpdb->prefix . 'nxf_dparams', $data, array( 'ID' => $_POST[ 'data' ][ 'id-' . $i ] ) );
						
						$wpdb->query( 
							$wpdb->prepare( 
								'UPDATE ' . $wpdb->prefix . 'nxf_dparams SET dparam_type=%s, dparam_name=%s, dparam_value=%s WHERE dparam_parent_id=%d',
								sanitize_text_field( $_POST[ 'data' ][ 'type-' . $i ] ), sanitize_text_field( $_POST[ 'data' ][ 'name-' . $i ] ), sanitize_text_field( $_POST[ 'data' ][ 'value-' . $i ] ), sanitize_text_field( $_POST[ 'data' ][ 'id-' . $i ] )
							)
						);
					} else {
						$wpdb->insert( $wpdb->prefix . 'nxf_dparams', $data );
					}
					
					if ( '' != $wpdb->last_error ) {
						return sprintf( __( 'A database error occurred updating the display: %s', 'nxf-transform' ), $wpdb->last_error );
					}
				}
			}
			
			// delete old settings
			$ids = explode( ',', sanitize_text_field( $_POST[ 'data' ][ 'delete' ] ) );
			foreach ( $ids as &$id ) {
				$wpdb->delete( $wpdb->prefix . "nxf_dparams", array( 'ID' => $id ) );
			}
			
			return '';
		}
		
		private function _new_display() {
			global $wpdb;
			
			if ( $_POST[ 'data' ][ 'feed_type_id' ] == '' ) { return __( 'A feed type was not selected', 'nxf-transform' ); }
			if ( $_POST[ 'data' ][ 'display_name' ] == '' ) { return __( 'A display name was not entered', 'nxf-transform' ); }
			if ( strlen( $_POST[ 'data' ][ 'display_name' ] ) > 128 ) { return __( 'The length of the display name is greater than 128 characters', 'nxf-transform' ); }
			if ( $_POST[ 'data' ][ 'display_desc' ] == '' ) { return __( 'A display description was not entered', 'nxf-transform' ); }
			if ( strlen( $_POST[ 'data' ][ 'display_desc' ] ) > 128 ) { return __( 'The length of the display description is greater than 128 characters', 'nxf-transform' ); }
			if ( $_POST[ 'data' ][ 'display_type' ] == '' ) { return __( 'A display type was not selected', 'nxf-transform' ); }
			
			$display = '';
			if ( $_POST[ 'data' ][ 'display_type' ] == 'FILE' ) { $display = $_POST[ 'data' ][ 'location' ]; }
			elseif ( $_POST[ 'data' ][ 'display_type' ] == 'URL' ) { $display = $_POST[ 'data' ][ 'url' ]; }
			elseif ( $_POST[ 'data' ][ 'display_type' ] == 'DB' ) { $display = $_POST[ 'data' ][ 'db' ]; }
			if ( $display == '' ) { return __( 'A display was not entered (file, url or text)', 'nxf-transform' ); }
			
			// check display parameters
			$current = 1;
			for ( $i = 0; $i < $_POST[ 'data' ][ 'next' ]; $i++ ) {
				if ( $_POST[ 'data' ][ 'type-' . $i ] != '' ) {
					if ( '' == $_POST[ 'data' ][ 'name-' . $i ] ) { return sprintf( __( 'Display parameter %s does not have a name', 'nxf-transform' ), $current ); }
					if ( strlen( $_POST[ 'data' ][ 'name-' . $i ] ) > 40 ) { return __( 'Parameter names must be 40 characters or less', 'nxf-transform' ); }
					if ( '' == $_POST[ 'data' ][ 'value-' . $i ] && 'SETTING' != $_POST[ 'data' ][ 'type-' . $i ] ) { return sprintf( __( 'Display parameter %s does not have a value', 'nxf-transform' ), $current ); }
					if ( strlen( $_POST[ 'data' ][ 'value-' . $i ] ) > 128 ) { return __( 'Parameter values must be 128 characters or less', 'nxf-transform' ); }
					$current++;
				}
			}		
			
			$data = array(
				'feed_type_id' => sanitize_text_field( $_POST[ 'data' ][ 'feed_type_id' ] ),
				'display_name' => sanitize_text_field( $_POST[ 'data' ][ 'display_name' ] ),
				'display_desc' => sanitize_text_field( $_POST[ 'data' ][ 'display_desc' ] ),
				'display' => sanitize_text_field( $display ),
				'display_type'  => sanitize_text_field( $_POST[ 'data' ][ 'display_type' ] )
			);
			
			$wpdb->insert( $wpdb->prefix . 'nxf_displays', $data );
			
			if ( $wpdb->last_error != '' ) {
				return sprintf( __( 'A database error occurred creating the display: %s', 'nxf-transform' ), $wpdb->last_error );
			}
			
			$display_id = $wpdb->insert_id;
			
			// add new settings
			for ( $i = 0; $i < $_POST[ 'data' ][ 'next' ]; $i++ ) {
				if ( '' != $_POST[ 'data' ][ 'type-' . $i ] ) {
					$data = array(
						'instance_id' => 0,
						'display_id' => $display_id,
						'dparam_type' => sanitize_text_field( $_POST[ 'data' ][ 'type-' . $i ] ),
						'dparam_name' => sanitize_text_field( $_POST[ 'data' ][ 'name-' . $i ] ),
						'dparam_value' => sanitize_text_field( $_POST[ 'data' ][ 'value-' . $i ] ),
						'dparam_parent_id' => 0
					);
					
					$wpdb->insert( $wpdb->prefix . 'nxf_dparams', $data );
					
					if ( '' != $wpdb->last_error ) {
						return sprintf( __( 'A database error occurred updating the display: %s', 'nxf-transform' ), $wpdb->last_error );
					}
				}
			}
			
			return '';
		}
		
		private function _display_parameters( $instance_id, $display_id ) {
			global $wpdb;
			
			/* get the defaults for the feed parameters (feed_id = 0) */
			$defaults = $wpdb->get_results(
				$wpdb->prepare(
					'SELECT ' . $wpdb->prefix . 'nxf_dparams.ID,
						    ' . $wpdb->prefix . 'nxf_dparams.instance_id,
						    ' . $wpdb->prefix . 'nxf_dparams.display_id,
						    ' . $wpdb->prefix . 'nxf_dparams.dparam_type,
						    ' . $wpdb->prefix . 'nxf_dparams.dparam_name,
						    ' . $wpdb->prefix . 'nxf_dparams.dparam_value,
						    ' . $wpdb->prefix . 'nxf_dparams.dparam_parent_id
				       FROM ' . $wpdb->prefix . 'nxf_dparams
				      WHERE ' . $wpdb->prefix . 'nxf_dparams.display_id=%d and ' . $wpdb->prefix . 'nxf_dparams.instance_id=0', $display_id
				)
			);
			
			/* get the parameters for this instance */ 
			if ( $instance_id ) {
				$params = $wpdb->get_results(
					$wpdb->prepare(
						'SELECT ' . $wpdb->prefix . 'nxf_dparams.ID,
								' . $wpdb->prefix . 'nxf_dparams.instance_id,
								' . $wpdb->prefix . 'nxf_dparams.display_id,
								' . $wpdb->prefix . 'nxf_dparams.dparam_type,
								' . $wpdb->prefix . 'nxf_dparams.dparam_name,
								' . $wpdb->prefix . 'nxf_dparams.dparam_value,
						        ' . $wpdb->prefix . 'nxf_dparams.dparam_parent_id
						   FROM ' . $wpdb->prefix . 'nxf_dparams
						  WHERE ' . $wpdb->prefix . 'nxf_dparams.display_id=%d and ' . $wpdb->prefix . 'nxf_dparams.instance_id=%d', $display_id, $instance_id
					)
				);
				
				foreach ( $params as $param ) {
					foreach ( $defaults as $default ) {
						if ( $param->dparam_type == $default->dparam_type && $param->dparam_name == $default->dparam_name && '' != $param->dparam_value ) {
							$default->dparam_user_value = $param->dparam_value;
						}
					}
				}
			}
			
			return $defaults;
		}
		
		/* Instance options */
		private function _new_instance( $feed_id, $display_id, $instance_name ) {
			global $wpdb;
			
			if ( $instance_name == '' ) { return __( 'An instance name was not specified', 'nxf-transform' ); }
			if ( strlen( $instance_name ) > 128 ) { return __( 'The instance name needs to be 128 character or less', 'nxf-transform' ); }
			if ( $feed_id == '' ) { return __( 'A feed was not specified', 'nxf-transform' ); }
			if ( $display_id == '' ) { return __( 'A display was not specified', 'nxf-transform' ); }
			
			$data = array(
				'feed_id' => $feed_id,
				'display_id' => $display_id,
				'instance_name' => $instance_name
			);
			
			$wpdb->insert( $wpdb->prefix . 'nxf_instances', $data );
			
			if ( $wpdb->last_error != '' ) {
				return sprintf( __( 'A database error occurred creating the feed instance: %s', 'nxf-transform' ), $wpdb->last_error );
			}
			
			$instance_id = $wpdb->insert_id;
			
			$instance = $wpdb->get_row(
				$wpdb->prepare(
					'SELECT ' . $wpdb->prefix . 'nxf_instances.ID,
							' . $wpdb->prefix . 'nxf_instances.feed_id,
							' . $wpdb->prefix . 'nxf_instances.display_id,
							' . $wpdb->prefix . 'nxf_instances.instance_name
					   FROM ' . $wpdb->prefix . 'nxf_instances
					  WHERE ' . $wpdb->prefix . 'nxf_instances.ID=%d', $instance_id
				)
			);
			
			/* Get the default settings for this display */
			$dparams = $this->_display_parameters( 0, $display_id );
			foreach ( $dparams as $dparam ) {
				if ( '' != $_POST[ 'data' ][ $dparam->dparam_name ] ) {
					$data = array(
						'instance_id' => $instance_id,
						'display_id' => $display_id,
						'dparam_type' => $dparam->dparam_type,
						'dparam_name' => $dparam->dparam_name,
						'dparam_value' => sanitize_text_field( $_POST[ 'data' ][ $dparam->dparam_name ] ),
						'dparam_parent_id' => $dparam->ID
					);
					
					$wpdb->insert( $wpdb->prefix . 'nxf_dparams', $data );
				}
			}
			
			return '';
		}
		
		/* Setting options */
		private function _delete_setting( $setting_id ) {
			global $wpdb;
			
			$wpdb->delete( $wpdb->prefix . "nxf_settings", array( 'ID' => $setting_id ) );
			
			if ( $wpdb->last_error != '' ) {
				return sprintf( __( 'A database error occurred deleting the setting: %s', 'nxf-transform' ), $wpdb->last_error );
			}
			
			return '';
		}
		
		private function _edit_setting( $setting_id ) {
			global $wpdb;
			
			if ( $_POST[ 'data' ][ 'settingtype' ] == '' ) { return __( 'A setting type was not selected', 'nxf-transform' ); }
			if ( $_POST[ 'data' ][ 'settingname' ] == '' ) { return __( 'A setting name was not entered', 'nxf-transform' ); }
			if ( strlen( $_POST[ 'data' ][ 'settingname' ] ) > 128 ) { return __( 'The setting name must be 128 characters or less', 'nxf-transform' ); }
			if ( $_POST[ 'data' ][ 'settingvalue' ] == '' ) { return __( 'A setting value was not entered', 'nxf-transform' ); }
			if ( strlen( $_POST[ 'data' ][ 'settingvalue' ] ) > 128 ) { return __( 'The setting value must be 128 characters or less', 'nxf-transform' ); }
			if ( $_POST[ 'data' ][ 'settingtype' ] == 'Numeric' && !is_numeric( $_POST[ 'data' ][ 'settingvalue' ] ) ) { return __( 'The value entered is not numeric', 'nxf-transform' ); }
			if ( $_POST[ 'data' ][ 'settingtype' ] == 'Date' && !preg_match( '/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $_POST[ 'data' ][ 'settingvalue' ] ) ) { return __( 'The value entered is not a date (make sure you use the yyyy-mm-dd format)', 'nxf-transform' ); }

			$data = array(
				'setting_type' => sanitize_text_field( $_POST[ 'data' ][ 'settingtype' ] ),
				'setting_name' => sanitize_text_field( $_POST[ 'data' ][ 'settingname' ] ),
				'setting_value' => sanitize_text_field( $_POST[ 'data' ][ 'settingvalue' ] )
			);
			
			$wpdb->update( $wpdb->prefix . 'nxf_settings', $data, array( 'ID' => $setting_id ) );
			
			if ( $wpdb->last_error != '' ) {
				return sprintf( __( 'A database error occurred updating the setting: %s', 'nxf-transform' ), $wpdb->last_error );
			}
			
			return '';
		}
		
		private function _new_setting() {
			global $wpdb;
			
			if ( $_POST[ 'data' ][ 'settingtype' ] == '' ) { return __( 'A setting type was not selected', 'nxf-transform' ); }
			if ( $_POST[ 'data' ][ 'settingname' ] == '' ) { return __( 'A setting name was not entered', 'nxf-transform' ); }
			if ( strlen( $_POST[ 'data' ][ 'settingname' ] ) > 128 ) { return __( 'The setting name must be 128 characters or less', 'nxf-transform' ); }
			if ( $_POST[ 'data' ][ 'settingvalue' ] == '' ) { return __( 'A setting value was not entered', 'nxf-transform' ); }
			if ( strlen( $_POST[ 'data' ][ 'settingvalue' ] ) > 128 ) { return __( 'The setting value must be 128 characters or less', 'nxf-transform' ); }
			if ( $_POST[ 'data' ][ 'settingtype' ] == 'Numeric' && !is_numeric( $_POST[ 'data' ][ 'settingvalue' ] ) ) { return __( 'The value entered is not numeric', 'nxf-transform' ); }
			if ( $_POST[ 'data' ][ 'settingtype' ] == 'Date' && !preg_match( '/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $_POST[ 'data' ][ 'settingvalue' ] ) ) { return __( 'The value entered is not a date (make sure you use the yyyy-mm-dd format)', 'nxf-transform' ); }
			
			$data = array(
				'setting_type' => sanitize_text_field( $_POST[ 'data' ][ 'settingtype' ] ),
				'setting_name' => sanitize_text_field( $_POST[ 'data' ][ 'settingname' ] ),
				'setting_value' => sanitize_text_field( $_POST[ 'data' ][ 'settingvalue' ] )
			);
			
			$wpdb->insert( $wpdb->prefix . 'nxf_settings', $data );
			
			if ( $wpdb->last_error != '' ) {
				return sprintf( __( 'A database error occurred creating the setting: %s', 'nxf-transform' ), $wpdb->last_error );
			}
			
			return '';
		}
		
		/* instance private subroutings */
		private function _delete_instance( $instance_id ) {
			global $wpdb;
			
			$wpdb->delete( $wpdb->prefix . "nxf_instances", array( 'ID' => $instance_id ) );
			
			if ( $wpdb->last_error != '' ) {
				return sprintf( __( 'A database error occurred deleting the feed instance: %s', 'nxf-transform' ), $wpdb->last_error );
			}
			
			/* also delete any display parameters for this instance */
			$wpdb->delete( $wpdb->prefix . "nxf_dparams", array( 'instance_id' => $instance_id ) );
			
			if ( $wpdb->last_error != '' ) {
				return sprintf( __( 'A database error occurred deleting the feed instance display parameters: %s', 'nxf-transform' ), $wpdb->last_error );
			}
			
			return '';
		}
		
		private function _edit_instance( $instance_id ) {
			global $wpdb;
			
			if ( $_POST[ 'data' ][ 'feed' ] == '' ) { return __( 'A feed was not specified', 'nxf-transform' ); }
			if ( $_POST[ 'data' ][ 'display' ] == '' ) { return __( 'A display was not specified', 'nxf-transform' ); }
			if ( $_POST[ 'data' ][ 'instance_name' ] == '' ) { return __( 'An instance name was not entered', 'nxf-transform' ); }
			if ( strlen(  $_POST[ 'data' ][ 'instance_name' ] ) > 128 ) { return __( 'The instance name needs to be 128 character or less', 'nxf-transform' ); }

			$data = array(
				'feed_id' => sanitize_text_field( $_POST[ 'data' ][ 'feed' ] ),
				'display_id' => sanitize_text_field( $_POST[ 'data' ][ 'display' ] ),
				'instance_name' => sanitize_text_field( $_POST[ 'data' ][ 'instance_name' ] )
			);
			
			$wpdb->update( $wpdb->prefix . 'nxf_instances', $data, array( 'ID' => $instance_id ) );
			
			if ( $wpdb->last_error != '' ) {
				return sprintf( __( 'A database error occurred updating the instance: %s', 'nxf-transform' ), $wpdb->last_error );
			}
			
			/* get the default display parameters for the selected display */
			$dparams = $this->_display_parameters( 0, sanitize_text_field( $_POST[ 'data' ][ 'display' ] ) );
			foreach ( $dparams as $dparam ) {
				if ( $_POST[ 'data' ][ $dparam->dparam_name ] != '' ) {
					/* is there already a display parameter with this name for this instance? */
					$instance_dparam = $wpdb->get_row(
						$wpdb->prepare(
							'SELECT ' . $wpdb->prefix . 'nxf_dparams.ID
							   FROM ' . $wpdb->prefix . 'nxf_dparams
							  WHERE ' . $wpdb->prefix . 'nxf_dparams.instance_id=%d
							    AND ' . $wpdb->prefix . 'nxf_dparams.dparam_name=%s', $instance_id, $dparam->dparam_name
						)
					);
					
					if ( $instance_dparam->ID == '' ) {
						/* no, insert a new display parameter */
						$data = array(
							'instance_id' => $instance_id,
							'display_id' => sanitize_text_field( $_POST[ 'data' ][ 'display' ] ),
							'dparam_type' => $dparam->dparam_type,
							'dparam_name' => $dparam->dparam_name,
							'dparam_value' => sanitize_text_field( $_POST[ 'data' ][ $dparam->dparam_name ] ),
							'dparam_parent_id' => $dparam->ID
						);
						
						$wpdb->insert( $wpdb->prefix . 'nxf_dparams', $data );
						
					} else {
					/* yes, update the parameter for this instance to the new value */
						$data = array(
							'dparam_value' => sanitize_text_field( $_POST[ 'data' ][ $dparam->dparam_name ] )
						);
					
						$wpdb->update( $wpdb->prefix . 'nxf_dparams', $data, array( 'instance_id' => $instance_id, 'dparam_parent_id' => $dparam->ID ) );
					}
				} else {
					/* delete the parameter for this instance so that the default will be used */
					$wpdb->delete( $wpdb->prefix . "nxf_dparams", array( 'instance_id' => $instance_id, 'dparam_parent_id' => $dparam->ID  ) );
				}
			}
			
			return '';
		}
		
		private function _get_instances( $blog_id, $displays = FALSE ) {
			global $wpdb;
			
			$instances = $wpdb->get_results(
				$wpdb->prepare(
					'SELECT ' . $wpdb->prefix . 'nxf_instances.ID,
							' . $wpdb->prefix . 'nxf_instances.feed_id,
							' . $wpdb->prefix . 'nxf_instances.display_id,
							' . $wpdb->prefix . 'nxf_instances.instance_name,
							' . $wpdb->prefix . 'nxf_feeds.feed_name,
							' . $wpdb->prefix . 'nxf_feed_types.feed_type_name,
							' . $wpdb->prefix . 'nxf_displays.display_name
					   FROM ' . $wpdb->prefix . 'nxf_instances
					   JOIN ' . $wpdb->prefix . 'nxf_feeds ON '  . $wpdb->prefix . 'nxf_feeds.ID = ' . $wpdb->prefix . 'nxf_instances.feed_id
					   JOIN ' . $wpdb->prefix . 'nxf_feed_types ON ' . $wpdb->prefix . 'nxf_feed_types.ID = ' . $wpdb->prefix . 'nxf_feeds.feed_type_id
					   JOIN ' . $wpdb->prefix . 'nxf_displays ON ' . $wpdb->prefix . 'nxf_displays.ID = ' . $wpdb->prefix . 'nxf_instances.display_id
					  WHERE ' . $wpdb->prefix . 'nxf_feeds.blog_id IS NULL
						 OR ' . $wpdb->prefix . 'nxf_feeds.blog_id=%d 
					  ORDER BY ' . $wpdb->prefix . 'nxf_instances.instance_name', $blog_id
				)
			);
			
			return $instances;
		}
		
		private function _get_instance( $instance_id ) {
			global $wpdb;
			
			return $wpdb->get_row(
				$wpdb->prepare(
					'SELECT ' . $wpdb->prefix . 'nxf_instances.ID,
							' . $wpdb->prefix . 'nxf_instances.feed_id,
							' . $wpdb->prefix . 'nxf_instances.display_id,
							' . $wpdb->prefix . 'nxf_instances.instance_name
					   FROM ' . $wpdb->prefix . 'nxf_instances
					  WHERE ' . $wpdb->prefix . 'nxf_instances.ID=%d', $instance_id
				)
			);
		}
		
		/* feed private subroutines */
		private function _delete_feed( $feed_id ) {
			global $wpdb;
			
			// make sure no instances are using this feed
			$instances = $wpdb->get_results(
				$wpdb->prepare(
					'SELECT ' . $wpdb->prefix . 'nxf_instances.ID
					   FROM ' . $wpdb->prefix . 'nxf_instances
					  WHERE ' . $wpdb->prefix . 'nxf_instances.feed_id=%d', $feed_id
				)
			);
			
			if ( $instances ) {
				return __( 'There are instances using this feed. Please delete those instances before deleting this feed.' );
			}
			
			$wpdb->delete( $wpdb->prefix . "nxf_feeds", array( 'ID' => $feed_id ) );
			
			if ( $wpdb->last_error != '' ) {
				return sprintf( __( 'A database error occurred deleting the feed: %s', 'nxf-transform' ), $wpdb->last_error );
			}
			
			return '';
		}
		
		private function _edit_feed( $feed_id ) {
			global $wpdb;
			
			if ( '' == $feed_id ) { return __( 'A feed ID was not specified', 'nxf-transform' ); }
			if ( '' == $_POST[ 'data' ][ 'feed_source' ] ) { return __( 'A feed source was not selected', 'nxf-transform' ); }
			if ( '' == $_POST[ 'data' ][ 'feed_type_id' ] ) { return __( 'A feed type was not selected', 'nxf-transform' ); }
			if ( '' == $_POST[ 'data' ][ 'feed_name' ] ) { return __( 'A feed name was not entered', 'nxf-transform' ); }
			if ( strlen( $_POST[ 'data' ][ 'feed_name' ] ) > 128 ) { return __( 'The feed name needs to be 128 character or less', 'nxf-transform' ); }
			if ( '' == $_POST[ 'data' ][ 'feed_desc' ] ) { return __( 'A feed description was not entered', 'nxf-transform' ); }
			if ( 'web' == $_POST[ 'data' ][ 'feed_source' ] && '' == $_POST[ 'data' ][ 'feed_uri' ] ) { return __( 'A feed URI was not entered', 'nxf-transform' ); }
			if ( 'web' == $_POST[ 'data' ][ 'feed_source' ] && '' == $_POST[ 'data' ][ 'method' ] ) { return __( 'A feed method was not selected', 'nxf-transform' ); }
			if ( 'file' == $_POST[ 'data' ][ 'feed_source' ] && '' == $_POST[ 'data' ][ 'feed_file' ] ) { return __( 'A feed file was not entered', 'nxf-transform' ); }
			
			# verify the parameters for the feed if it has a source of "web"
			if ( 'web' == $_POST[ 'data' ][ 'feed_source' ] ) {
				for ( $i = 1; $i <= $_POST[ 'data' ][ 'next' ]; $i = $i + 1 ) {
					if ( 'NXF-PARAM-DELETED' != $_POST[ 'data' ][ 'name-out-' . $i ] ) {
						if ( '' == $_POST[ 'data' ][ 'name-out-' . $i ] ) { return __( 'All parameters must have a name entered', 'nxf-transform' ); }
						if ( '' == $_POST[ 'data' ][ 'type-out-' . $i ] ) { return __( 'All parameters must have a type selected', 'nxf-transform' ); }
						if ( '' == $_POST[ 'data' ][ 'type-in-' . $i ] ) { return __( 'All parameter values must have a type', 'nxf-transform' ); }
						if ( '' == $_POST[ 'data' ][ 'name-in-' . $i ] ) { return __( 'All parameter values must have a name', 'nxf-transform' ); }
					}
				}
			}

			$data = array(
				'feed_type_id' => sanitize_text_field( $_POST[ 'data' ][ 'feed_type_id' ] ),
				'feed_name' => sanitize_text_field( $_POST[ 'data' ][ 'feed_name' ] ),
				'feed_desc' => sanitize_text_field( $_POST[ 'data' ][ 'feed_desc' ] ),
				'feed_source' => sanitize_text_field( $_POST[ 'data' ][ 'feed_source' ] ),
				'feed_uri'  => sanitize_text_field( $_POST[ 'data' ][ 'feed_uri' ] ),
				'feed_file' => sanitize_text_field( $_POST[ 'data' ][ 'feed_file' ] ),
				'method'  => sanitize_text_field( $_POST[ 'data' ][ 'method' ] )
			);
			
			$wpdb->update( $wpdb->prefix . 'nxf_feeds', $data, array( 'ID' => $feed_id ) );
			
			if ( $wpdb->last_error != '' ) {
				return sprintf( __( 'A database error occurred updating the feed: %s', 'nxf-transform' ), $wpdb->last_error );
			}
			
			if ( 'web' == $_POST[ 'data' ][ 'feed_source' ] ) {
				for ( $i = 1; $i <= $_POST[ 'data' ][ 'next' ]; $i = $i + 1 ) {
					if ( 'NXF-PARAM-DELETED' != $_POST[ 'data' ][ 'name-out-' . $i ] ) {
						#--- does a record exist with this name for this feed?
						if ( '' != $_POST['data']['id-'.$i] ) {
							$data = array(
								fparam_type_in => $_POST[ 'data' ][ 'type-in-' . $i ],
								fparam_name_in => $_POST[ 'data' ][ 'name-in-' . $i ],
								fparam_type_out => $_POST[ 'data' ][ 'type-out-' . $i ],
								fparam_name_out => $_POST[ 'data' ][ 'name-out-' . $i ]
							);
							
							$wpdb->update( $wpdb->prefix . 'nxf_fparams', $data, array( 'ID' => $_POST['data']['id-'.$i] ) );
							
							if ( '' != $wpdb->last_error ) {
								return sprintf( __( 'A database error occurred updating the feed parameter %s: %s', 'nxf-transform' ), $_POST[ 'data' ][ 'name-out-' . $i ], $wpdb->last_error );
							}
						} else {
							$data = array(
								feed_id => $feed_id,
								fparam_type_in => $_POST[ 'data' ][ 'type-in-' . $i ],
								fparam_name_in => $_POST[ 'data' ][ 'name-in-' . $i ],
								fparam_type_out => $_POST[ 'data' ][ 'type-out-' . $i ],
								fparam_name_out => $_POST[ 'data' ][ 'name-out-' . $i ]
							);
						
							$wpdb->insert( $wpdb->prefix . 'nxf_fparams', $data );
							
							if ( '' != $wpdb->last_error ) {
								return sprintf( __( 'A database error occurred creating the feed parameter %s: %s', 'nxf-transform' ), $_POST[ 'data' ][ 'name-out-' . $i ], $wpdb->last_error );
							}
						}
					} else if ( '' != $_POST['data']['id-'.$i] ) {
						#--- delete this fparam
						$wpdb->delete( $wpdb->prefix . "nxf_fparams", array( 'ID' => $_POST['data']['id-'.$i] ) );
						
						if ( '' != $wpdb->last_error ) {
							return sprintf( __( 'A database error occurred deleting the feed parameter %s: %s', 'nxf-transform' ), $_POST[ 'data' ][ 'name-out-' . $i ], $wpdb->last_error );
						}
					}
				} 
			}
			
			return '';
		}
		
		private function _feed_parameters( $feed_id ) {
			global $wpdb;
			
			/* get the defaults for the feed parameters (feed_id = 0) */
			$fparams = $wpdb->get_results(
				$wpdb->prepare(
					'SELECT ' . $wpdb->prefix . 'nxf_fparams.ID,
						    ' . $wpdb->prefix . 'nxf_fparams.feed_id,
						    ' . $wpdb->prefix . 'nxf_fparams.fparam_type_in,
						    ' . $wpdb->prefix . 'nxf_fparams.fparam_name_in,
						    ' . $wpdb->prefix . 'nxf_fparams.fparam_type_out,
						    ' . $wpdb->prefix . 'nxf_fparams.fparam_name_out
				       FROM ' . $wpdb->prefix . 'nxf_fparams
				      WHERE ' . $wpdb->prefix . 'nxf_fparams.feed_id=%d', $feed_id
				)
			);
			
			return $fparams;
		}
			
		private function _get_feeds( $blog_id, $displays = FALSE, $instance_id = 0 ) {
			global $wpdb;
			
			$feeds = $wpdb->get_results(
				$wpdb->prepare(
					'SELECT ' . $wpdb->prefix . 'nxf_feeds.ID,
							' . $wpdb->prefix . 'nxf_feeds.feed_name,
							' . $wpdb->prefix . 'nxf_feeds.feed_desc,
							' . $wpdb->prefix . 'nxf_feed_types.feed_type_name,
							' . $wpdb->prefix . 'nxf_feeds.feed_type_id
					   FROM ' . $wpdb->prefix . 'nxf_feeds
					   JOIN ' . $wpdb->prefix . 'nxf_feed_types ON ' . $wpdb->prefix . 'nxf_feed_types.ID = ' . $wpdb->prefix . 'nxf_feeds.feed_type_id
					  WHERE ' . $wpdb->prefix . 'nxf_feeds.blog_id IS NULL
						 OR ' . $wpdb->prefix . 'nxf_feeds.blog_id=%d
					  ORDER BY ' . $wpdb->prefix . 'nxf_feeds.feed_name', $blog_id
				)
			);
			
			if ( $displays ) {
				foreach ( $feeds as $feed ) {
					$feed->displays = $this->_get_feed_displays( $feed->feed_type_id );
					foreach ( $feed->displays as $display ) {
						$display->params = $this->_display_parameters( $instance_id, $display->ID );
					}
				}
			}
			
			return $feeds;
		}
	
		private function _get_feed( $feed_id ) {
			global $wpdb;
			
			return $wpdb->get_row(
				$wpdb->prepare(
					'SELECT ' . $wpdb->prefix . 'nxf_feeds.ID,
							' . $wpdb->prefix . 'nxf_feeds.feed_name,
							' . $wpdb->prefix . 'nxf_feeds.feed_desc,
							' . $wpdb->prefix . 'nxf_feeds.feed_source,
							' . $wpdb->prefix . 'nxf_feeds.feed_uri,
							' . $wpdb->prefix . 'nxf_feeds.feed_file,
							' . $wpdb->prefix . 'nxf_feeds.method,
							' . $wpdb->prefix . 'nxf_feeds.blog_id,
							' . $wpdb->prefix . 'nxf_feed_types.ID as feedtypeid,
							' . $wpdb->prefix . 'nxf_feed_types.feed_type_name
					   FROM ' . $wpdb->prefix . 'nxf_feeds
					   JOIN ' . $wpdb->prefix . 'nxf_feed_types ON ' . $wpdb->prefix . 'nxf_feed_types.ID = ' . $wpdb->prefix . 'nxf_feeds.feed_type_id
					  WHERE ' . $wpdb->prefix . 'nxf_feeds.ID=%d', $feed_id
				)
			);
		}
		
		private function _new_feed() {
			global $wpdb;
			
			if ( '' == $_POST[ 'data' ][ 'feed_source' ] ) { return __( 'A feed source was not selected', 'nxf-transform' ); }
			if ( '' == $_POST[ 'data' ][ 'feed_type_id' ] ) { return __( 'A feed type was not selected', 'nxf-transform' ); }
			if ( '' == $_POST[ 'data' ][ 'feed_name' ] ) { return __( 'A feed name was not entered', 'nxf-transform' ); }
			if ( strlen( $_POST[ 'data' ][ 'feed_name' ] ) > 128 ) { return __( 'The feed name needs to be 128 character or less', 'nxf-transform' ); }
			if ( '' == $_POST[ 'data' ][ 'feed_desc' ] ) { return __( 'A feed description was not entered', 'nxf-transform' ); }
			if ( 'web' == $_POST[ 'data' ][ 'feed_source' ] && '' == $_POST[ 'data' ][ 'feed_uri' ] ) { return __( 'A feed URI was not entered', 'nxf-transform' ); }
			if ( 'web' == $_POST[ 'data' ][ 'feed_source' ] && '' == $_POST[ 'data' ][ 'method' ] ) { return __( 'A feed method was not selected', 'nxf-transform' ); }
			if ( 'file' == $_POST[ 'data' ][ 'feed_source' ] && '' == $_POST[ 'data' ][ 'feed_file' ] ) { return __( 'A feed file was not entered', 'nxf-transform' ); }
			if ( strlen( $_POST[ 'data' ][ 'feed_file' ] ) > 256 ) { return __( 'The feed file length needs to be 256 characters or less', 'nxf-transform' ); }
			if ( strlen( $_POST[ 'data' ][ 'feed_uri' ] ) > 256 ) { return __( 'The feed URI length needs to be 256 characters or less', 'nxf-transform' ); }
			
			# verify the parameters for the feed if it has a source of "web"
			if ( 'web' == $_POST[ 'data' ][ 'feed_source' ] ) {
				for ( $i = 1; $i <= $_POST[ 'data' ][ 'next' ]; $i = $i + 1 ) {
					if ( 'NXF-PARAM-DELETED' != $_POST[ 'data' ][ 'name-out-' . $i ] ) {
						if ( '' == $_POST[ 'data' ][ 'name-out-' . $i ] ) { return __( 'All parameters must have a name entered', 'nxf-transform' ); }
						if ( '' == $_POST[ 'data' ][ 'type-out-' . $i ] ) { return __( 'All parameters must have a type selected', 'nxf-transform' ); }
						if ( '' == $_POST[ 'data' ][ 'type-in-' . $i ] ) { return __( 'All parameter values must have a type', 'nxf-transform' ); }
						if ( '' == $_POST[ 'data' ][ 'name-in-' . $i ] ) { return __( 'All parameter values must have a name', 'nxf-transform' ); }
					}
				}
			}
			
			$data = array(
				'feed_type_id' => sanitize_text_field( $_POST[ 'data' ][ 'feed_type_id' ] ),
				'feed_name' => sanitize_text_field( $_POST[ 'data' ][ 'feed_name' ] ),
				'feed_desc' => sanitize_text_field( $_POST[ 'data' ][ 'feed_desc' ] ),
				'feed_source' => sanitize_text_field( $_POST[ 'data' ][ 'feed_source' ] ),
				'feed_uri'  => sanitize_text_field( $_POST[ 'data' ][ 'feed_uri' ] ),
				'feed_file' => sanitize_text_field( $_POST[ 'data' ][ 'feed_file' ] ),
				'method'  => sanitize_text_field( $_POST[ 'data' ][ 'method' ] )
			);
			
			$wpdb->insert( $wpdb->prefix . 'nxf_feeds', $data );
			
			if ( '' != $wpdb->last_error ) {
				return sprintf( __( 'A database error occurred creating the feed: %s', 'nxf-transform' ), $wpdb->last_error );
			}
			
			if ( 'web' == $_POST[ 'data' ][ 'feed_source' ] ) {
				for ( $i = 1; $i <= $_POST[ 'data' ][ 'next' ]; $i = $i + 1 ) {
					if ( 'NXF-PARAM-DELETED' != $_POST[ 'data' ][ 'name-out-' . $i ] ) {
						#--- define the data for the new feed
						$data = array(
							feed_id => $feed_id,
							fparam_type_in => $_POST[ 'data' ][ 'type-in-' . $i ],
							fparam_name_in => $_POST[ 'data' ][ 'name-in-' . $i ],
							fparam_type_out => $_POST[ 'data' ][ 'type-out-' . $i ],
							fparam_name_out => $_POST[ 'data' ][ 'name-out-' . $i ]
						);
						
						$wpdb->insert( $wpdb->prefix . 'nxf_fparams', $data );
						if ( '' != $wpdb->last_error ) {
							return sprintf( __( 'A database error occurred creating the feed parameter %s: %s', 'nxf-transform' ), $_POST[ 'data' ][ 'name-out-' . $i ], $wpdb->last_error );
						}
					}
				}
			}
			
			return '';
		}
		
		private function _get_feed_displays( $feed_type_id ) {
			global $wpdb;
			
			return $wpdb->get_results(
				$wpdb->prepare(
					'SELECT ' . $wpdb->prefix . 'nxf_displays.ID,
							' . $wpdb->prefix . 'nxf_displays.display_name,
							' . $wpdb->prefix . 'nxf_displays.display_desc,
							' . $wpdb->prefix . 'nxf_displays.blog_id
					   FROM ' . $wpdb->prefix . 'nxf_displays
					  WHERE ' . $wpdb->prefix . 'nxf_displays.feed_type_id=%d
				   ORDER BY ' . $wpdb->prefix . 'nxf_displays.display_name', $feed_type_id
				)
			);
		}
		
		/* feed type options */
		private function _delete_feedtype( $feedtype_id ) {
			global $wpdb;
			
			// make sure no feeds or displays are using this feed type
			$feeds = $wpdb->get_results(
				$wpdb->prepare(
					'SELECT ' . $wpdb->prefix . 'nxf_feeds.ID
					   FROM ' . $wpdb->prefix . 'nxf_feeds
					  WHERE ' . $wpdb->prefix . 'nxf_feeds.feed_type_id=%d', $feedtype_id
				)
			);

			if ( $feeds ) {
				return __( 'There are feeds using this feed type. Please delete those feeds before deleting this feed type: ' . $feedtype_id );
			}
			
			$displays = $wpdb->get_results(
				$wpdb->prepare(
					'SELECT ' . $wpdb->prefix . 'nxf_displays.ID
					   FROM ' . $wpdb->prefix . 'nxf_instances
					  WHERE ' . $wpdb->prefix . 'nxf_displays.feed_type_id=%d', $feedtype_id
				)
			);
			
			if ( $displays ) {
				return __( 'There are displays using this feed type. Please delete those displays before deleting this feed type.' );
			}
			
			$wpdb->delete( $wpdb->prefix . "nxf_feed_types", array( 'ID' => $feedtype_id ) );
			
			if ( $wpdb->last_error != '' ) {
				return sprintf( __( 'A database error occurred deleting the feed type: %s', 'nxf-transform' ), $wpdb->last_error );
			}
			
			return '';
		}
		
		private function _edit_feedtype( $feedtype_id ) {
			global $wpdb;
			
			if ( $_POST[ 'data' ][ 'feed_type_name' ] == '' ) { return __( 'A feed type name was not entered', 'nxf-transform' ); }
			if ( strlen( $_POST[ 'data' ][ 'feed_type_name' ] ) > 128 ) { return __( 'The feed type name must be 128 characters or less', 'nxf-transform' ); }
			if ( $_POST[ 'data' ][ 'feed_type_desc' ] == '' ) { return __( 'A feed type description was not entered', 'nxf-transform' ); }
			if ( strlen( $_POST[ 'data' ][ 'feed_type_desc' ] ) > 128 ) { return __( 'The feed type description must be 128 characters or less', 'nxf-transform' ); }
			if ( $_POST[ 'data' ][ 'feed_type_mime' ] == '' ) { return __( 'A MIME type was not selected', 'nxf-transform' ); }

			$data = array(
				'feed_type_name' => sanitize_text_field( $_POST[ 'data' ][ 'feed_type_name' ] ),
				'feed_type_desc' => sanitize_text_field( $_POST[ 'data' ][ 'feed_type_desc' ] ),
				'feed_type_mime' => sanitize_text_field( $_POST[ 'data' ][ 'feed_type_mime' ] )
			);
			
			$wpdb->update( $wpdb->prefix . 'nxf_feed_types', $data, array( 'ID' => $feedtype_id ) );
			
			if ( $wpdb->last_error != '' ) {
				return sprintf( __( 'A database error occurred updating the feed type: %s', 'nxf-transform' ), $wpdb->last_error );
			}
			
			return '';
		}
	
		private function _get_feed_types() {
			global $wpdb;
			
			return $wpdb->get_results(
				'SELECT ' . $wpdb->prefix . 'nxf_feed_types.ID,
						' . $wpdb->prefix . 'nxf_feed_types.feed_type_name,
						' . $wpdb->prefix . 'nxf_feed_types.feed_type_desc,
						' . $wpdb->prefix . 'nxf_feed_types.feed_type_mime,
						' . $wpdb->prefix . 'nxf_feed_types.feed_type_params
				   FROM ' . $wpdb->prefix . 'nxf_feed_types
			   ORDER BY ' . $wpdb->prefix . 'nxf_feed_types.feed_type_name'
			);
		}
		
		private function _get_feed_type( $feed_type_id ) {
			global $wpdb;
			
			return $wpdb->get_row(
				$wpdb->prepare(
					'SELECT ' . $wpdb->prefix . 'nxf_feed_types.ID,
					        ' . $wpdb->prefix . 'nxf_feed_types.feed_type_name,
					        ' . $wpdb->prefix . 'nxf_feed_types.feed_type_desc,
					        ' . $wpdb->prefix . 'nxf_feed_types.feed_type_mime,
					        ' . $wpdb->prefix . 'nxf_feed_types.feed_type_params
					   FROM ' . $wpdb->prefix . 'nxf_feed_types
					  WHERE ' . $wpdb->prefix . 'nxf_feed_types.ID=%d', $feed_type_id
				)
			);
		}
		
		private function _new_feed_type() {
			global $wpdb;
			
			if ( $_POST[ 'data' ][ 'feed_type_name' ] == '' ) { return __( 'A feed type name was not entered', 'nxf-transform' ); }
			if ( strlen( $_POST[ 'data' ][ 'feed_type_name' ] ) > 128 ) { return __( 'The feed type name must be 128 characters or less', 'nxf-transform' ); }
			if ( $_POST[ 'data' ][ 'feed_type_desc' ] == '' ) { return __( 'A feed type description was not entered', 'nxf-transform' ); }
			if ( strlen( $_POST[ 'data' ][ 'feed_type_desc' ] ) > 128 ) { return __( 'The feed type description must be 128 characters or less', 'nxf-transform' ); }
			if ( $_POST[ 'data' ][ 'feed_type_mime' ] == '' ) { return __( 'A MIME type was not selected', 'nxf-transform' ); }
			
			$data = array(
				'feed_type_name' => sanitize_text_field( $_POST[ 'data' ][ 'feed_type_name' ] ),
				'feed_type_desc' => sanitize_text_field( $_POST[ 'data' ][ 'feed_type_desc' ] ),
				'feed_type_mime' => sanitize_text_field( $_POST[ 'data' ][ 'feed_type_mime' ] )
			);
			
			$wpdb->insert( $wpdb->prefix . 'nxf_feed_types', $data );
			
			if ( $wpdb->last_error != '' ) {
				return sprintf( __( 'A database error occurred creating the feed type: %s', 'nxf-transform' ), $wpdb->last_error );
			}
			
			return '';
		}
		
		private function _setting( $setting_id ) {
			global $wpdb;
			
			return $wpdb->get_row(
				$wpdb->prepare(
					'SELECT ' . $wpdb->prefix . 'nxf_settings.ID,
						    ' . $wpdb->prefix . 'nxf_settings.setting_type,
						    ' . $wpdb->prefix . 'nxf_settings.setting_name,
						    ' . $wpdb->prefix . 'nxf_settings.setting_value
				       FROM ' . $wpdb->prefix . 'nxf_settings
				      WHERE ' . $wpdb->prefix . 'nxf_settings.ID=%d', $setting_id
				)
			);
		}
		
		private function _settings() {
			global $wpdb;
			
			return $wpdb->get_results(
				'SELECT ' . $wpdb->prefix . 'nxf_settings.ID,
						' . $wpdb->prefix . 'nxf_settings.setting_type,
						' . $wpdb->prefix . 'nxf_settings.setting_name,
						' . $wpdb->prefix . 'nxf_settings.setting_value
				   FROM ' . $wpdb->prefix . 'nxf_settings
			   ORDER BY ' . $wpdb->prefix . 'nxf_settings.setting_name'
			);
		}
		
		private function _bulk_delete( $type, $ids ) {
			$error = '';
			foreach ( $ids as $id ) {
				if ( $error == '' ) {
					switch ( $type ) {
						case 'displays':
							$error = $this->_delete_display( $id );
							break;
							
						case 'feeds':
							$error = $this->_delete_feed( $id );
							break;
						
						case 'feedtypes':
							$error = $this->_delete_feedtype( $id );
							break;
							
						case 'instances':
							$error = $this->_delete_instance( $id );
							break;
							
						case 'settings':
							$error = $this->_delete_setting( $id );
							break;
					}
				}
			}
			
			return $error;
		}
		
		public function _license_check() {
			$license = get_site_option( 'nxf_license_key' );
		
			if ( false === ( $license_data = get_site_transient( 'nxf_transform_license' ) ) ) {
				$api_params = array(
					'edd_action' => 'check_license',
					'license' => $license, // this is the license entered by the user, NEED TO FIX THIS!!!
					'item_name' => urlencode( 'Transform' ),
					'url'       => home_url()
				);

				// Call the custom API.
				$response = wp_remote_post( 'https://netinnovationsllc.com/transform/', array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

				if ( is_wp_error( $response ) )
					return $response;

				$license_data = json_decode( wp_remote_retrieve_body( $response ) );
				
				set_site_transient( 'nxf_transform_license', $license_data, 60 );
			}
			
			return $license_data;
		}
		
		public function _activate() {
			// run a quick security check
			if( ! check_admin_referer( 'nxf_nonce', 'nxf_nonce' ) ) return; // get out if we didn't click the Activate button
			
			// data to send in our API request
			$api_params = array(
				'edd_action' => 'activate_license',
				'license'    => $_POST['nxf_license_key'],
				'item_name'  => urlencode( 'Transform' ), // the name of our product in EDD
				'url'        => home_url()
			);

			// Call the custom API.
			$response = wp_remote_post( 'https://netinnovationsllc.com/transform/', array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

			// make sure the response came back okay
			if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
				if ( is_wp_error( $response ) ) {
					$message = $response->get_error_message();
				} else {
					$message = __( 'An error occurred, please try again.' );
				}

			} else {
				$license_data = json_decode( wp_remote_retrieve_body( $response ) );

				if ( false === $license_data->success ) {
					switch( $license_data->error ) {
						case 'expired' :
							$message = sprintf(
								__( 'Your license key expired on %s.', 'nxf-transform' ),
								date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
							);
							break;

						case 'revoked' :
							$message = __( 'Your license key has been disabled.', 'nxf-transform' );
							break;

						case 'missing' :
							$message = __( 'Invalid license.', 'nxf-transform' );
							break;

						case 'invalid' :
						case 'site_inactive' :
							$message = __( 'Your license is not active for this URL.', 'nxf-transform' );
							break;

						case 'item_name_mismatch' :
							$message = sprintf( __( 'This appears to be an invalid license key for %s.', 'nxf-transform' ), 'Transform' );
							break;

						case 'no_activations_left':
							$message = __( 'Your license key has reached its activation limit.', 'nxf-transform' );
							break;

						default :
							$message = __( 'An error occurred, please try again.', 'nxf-transform' );
							break;
					}
				}
			}
			
			// Check if anything passed on a message constituting a failure
			if ( ! empty( $message ) ) {
				$base_url = admin_url( 'admin.php?page=' . NXF_LICENSE_PAGE );
				$redirect = add_query_arg( array( 'sl_activation' => 'false', 'message' => urlencode( $message ) ), $base_url );

				wp_redirect( $redirect );
				exit();
			}
			
			// Delete the license transient so it will reforce a check
			delete_site_transient( 'nxf_transform_license' );
			
			// set the license key for the site
			update_site_option( 'nxf_license_key', $_POST['nxf_license_key'] );
		
			update_option( 'nxf_license_status', $license_data->license );
			
			$this->license_check = $this->_license_check();
		}
		
		public function _deactivate() {
			// run a quick security check
			if( ! check_admin_referer( 'nxf_nonce', 'nxf_nonce' ) ) return; // get out if we didn't click the Activate button

			// retrieve the license from the database
			$license = trim( get_site_option( 'nxf_license_key' ) );


			// data to send in our API request
			$api_params = array(
				'edd_action' => 'deactivate_license',
				'license'    => $license,
				'item_name'  => urlencode( 'Transform' ), // the name of our product in EDD
				'url'        => home_url()
			);

			// Call the custom API.
			$response = wp_remote_post( 'https://netinnovationsllc.com/transform/', array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

			// make sure the response came back okay
			if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

				if ( is_wp_error( $response ) ) {
					$message = $response->get_error_message();
				} else {
					$message = __( 'An error occurred, please try again.' );
				}

				$base_url = admin_url( 'admin.php?page=' . NXF_LICENSE_PAGE );
				$redirect = add_query_arg( array( 'sl_activation' => 'false', 'message' => urlencode( $message ) ), $base_url );

				wp_redirect( $redirect );
				exit();
			}

			// decode the license data
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			// $license_data->license will be either "deactivated" or "failed"
			if( $license_data->license == 'deactivated' ) {
				// Delete the license transient so it will reforce a check
				delete_site_transient( 'nxf_transform_license' );
				
				// Delete the license key
				delete_site_option( 'nxf_license_key' );
			
				// Remove the status of the license
				delete_option( 'nxf_license_status' );
				
				// Update the license check
				$this->license_check = $this->_license_check();
			}
		}
	}
}
