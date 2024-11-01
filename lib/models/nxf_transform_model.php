<?php

if ( !class_exists( 'NXF_Transform_Model' ) ) {
	class NXF_Transform_Model {
		public function __construct( $instance_id, $debug ) {
			global $wpdb;
			
			$this->transformed='';
			$this->error = '';
			$this->post = array();
			$this->cookies = array();
			$this->files = array();
			$this->headers = array();
			
			$this->debug = $debug;
			
			$this->instance = $wpdb->get_row(
				$wpdb->prepare(
					'SELECT ' . $wpdb->prefix . 'nxf_instances.ID,
					        ' . $wpdb->prefix . 'nxf_instances.feed_id,
					        ' . $wpdb->prefix . 'nxf_instances.display_id
					   FROM ' . $wpdb->prefix . 'nxf_instances
					  WHERE ' . $wpdb->prefix . 'nxf_instances.ID=%d', $instance_id
				)
			);
			
			/* get feed information from the database */
			$this->feed = $wpdb->get_row(
				$wpdb->prepare(
					'SELECT ' . $wpdb->prefix . 'nxf_feeds.ID,
							' . $wpdb->prefix . 'nxf_feeds.feed_source,
					        ' . $wpdb->prefix . 'nxf_feeds.feed_uri,
					        ' . $wpdb->prefix . 'nxf_feeds.feed_file,
					        ' . $wpdb->prefix . 'nxf_feeds.feed_type_id, 
					        ' . $wpdb->prefix . 'nxf_feeds.method,
					        ' . $wpdb->prefix . 'nxf_feeds.blog_id,
					        ' . $wpdb->prefix . 'nxf_feed_types.feed_type_mime
					   FROM ' . $wpdb->prefix . 'nxf_feeds
					   JOIN ' . $wpdb->prefix . 'nxf_feed_types ON ' . $wpdb->prefix . 'nxf_feed_types.id=' . $wpdb->prefix . 'nxf_feeds.feed_type_id
					  WHERE ' . $wpdb->prefix . 'nxf_feeds.ID=%d', $this->instance->feed_id
				)
			);

			/* get feed parameters for this feed from the database */
			$this->fparams = $wpdb->get_results(
				'SELECT ' . $wpdb->prefix . 'nxf_fparams.ID,
				        ' . $wpdb->prefix . 'nxf_fparams.fparam_type_in,
				        ' . $wpdb->prefix . 'nxf_fparams.fparam_name_in,
				        ' . $wpdb->prefix . 'nxf_fparams.fparam_type_out,
				        ' . $wpdb->prefix . 'nxf_fparams.fparam_name_out
				   FROM ' . $wpdb->prefix . 'nxf_fparams
				  WHERE ' . $wpdb->prefix . 'nxf_fparams.feed_id=' . $this->feed->ID
			);
			
			if ( $this->feed ) {
				/* get feed type information from the database */
			
				/* do variable substitutions on the feed URI */
				if ( 'web' == $this->feed->feed_source ) {
				
					$this->feed->feed_uri = $this->feed_parameters( $this->feed->feed_uri );
					
					/* Add the feed parameters (cookies, post variables, etc) to the request */
					$args = array();
					if ( count( $this->post ) ) {
						$args[ 'body' ] = $this->post;
					}
					
					if ( count( $this->cookies ) ) {
						$args[ 'cookies' ] = $this->cookies;
					}
					
					if ( count( $this->headers ) ) {
						$args[ 'headers' ] = $this->headers;
					}
					
					switch ( $this->feed->method ) {
						case 'GET':
							$response = wp_remote_get( $this->feed->feed_uri, $args );
							//$this->feed->data = wp_remote_retrieve_body( wp_remote_get( $this->feed->feed_uri, $args ) );
							break;
							
						case 'POST':
							$response =  wp_remote_post( $this->feed->feed_uri, $args );
							break;
							
						case 'PUT':
							$args[ 'method' ] = 'PUT';
							$response = wp_remote_request( $this->feed->feed_uri, $args );
							//$this->feed->data = wp_remote_retrieve_body( wp_remote_request( $this->feed->feed_uri, $args ) );
							break;
							
						case 'DELETE':
							$args[ 'method' ] = 'DELETE';
							$response = wp_remote_request( $this->feed->feed_uri, $args );
							//$this->feed->data = wp_remote_retrieve_body( wp_remote_request( $this->feed->feed_uri, $args ) );
							break;
					}
					
					if ( "200" == wp_remote_retrieve_response_code( $response ) ) {
						$this->feed->data = wp_remote_retrieve_body( $response );
					} else {
						$this->feed->data = '';
						$this->error = sprintf( __( 'The web request for this feed instance returned a status code of %s', 'nxf-tranform' ), wp_remote_retrieve_response_code( $response ) );
					}
				} else if ( file_exists( $this->file_uri_sub( $this->feed->feed_file ) ) ) {
					$this->feed->data = file_get_contents( $this->file_uri_sub( $this->feed->feed_file ) );
				} else {
					$this->feed->data = '';
					$this->error = __( 'The file for this feed instance does not exist on this server', 'nxf-tranform' );
				}
				
				/* get display information from the database */
				$this->display_rec = $wpdb->get_row(
					$wpdb->prepare(
						'SELECT ID, display, display_type, blog_id FROM ' . $wpdb->prefix . 'nxf_displays WHERE ID=%d and feed_type_id=%d', $this->instance->display_id, $this->feed->feed_type_id
					)
				);
				
				if ( $this->display_rec ) {
					$this->display = array();
					
					if ( $this->display_rec->display_type == 'DB' ) {
						$this->display[ 'xsl' ] = $this->display_rec->display;
					}
					else if ( $this->display_rec->display_type == 'FILE' ) {
						/* get the display */
						$this->display[ 'xsl' ] = file_get_contents( $this->display_rec->display );
					}
					
					/* get the variables for this display */
					$this->dparams = $wpdb->get_results(
						$wpdb->prepare(
							'SELECT dparam_type, dparam_name, dparam_value FROM ' . $wpdb->prefix . 'nxf_dparams WHERE instance_id=%d OR instance_id=0 ORDER BY instance_id', $instance_id
						)
					);
					
					$dparams = [];
					$dparamsstring = '';
					if ( $this->dparams ) {
						foreach ( $this->dparams as $dparam ) {
							switch ( $dparam->dparam_type ) {
								case 'STATIC':
									$value = $dparam->dparam_value;
									break;
									
								case 'POST':
									if ( sanitize_text_field( $_POST[ $dparam->dparam_name ] ) == '' ) { $value = $dparam->dparam_value; }
										else { $value = sanitize_text_field( $_POST[ $dparam->dparam_name ] ); }
									break;
									
								case 'GET':
									if ( sanitize_text_field( $_GET[ $dparam->dparam_name ] ) == '' ) { $value = $dparam->dparam_value; }
										else { $value = sanitize_text_field( $_GET[ $dparam->dparam_name ] ); }
									break;
								
								case 'SETTING':
									$setting = $wpdb->get_row(
										$wpdb->prepare(
											'SELECT ' . $wpdb->prefix . 'nxf_settings.ID,
													' . $wpdb->prefix . 'nxf_settings.setting_type,
													' . $wpdb->prefix . 'nxf_settings.setting_name,
													' . $wpdb->prefix . 'nxf_settings.setting_value
											   FROM ' . $wpdb->prefix . 'nxf_settings
											  WHERE ' . $wpdb->prefix . 'nxf_settings.setting_name=%s', $dparam->dparam_name
										)
									);
									
									if ( $setting && $setting->setting_value != '' ) {
										$value = $setting->setting_value;
									} else {
										$value = $dparam->dparam_value;
									}
									break;	
							}
							
							if ( $this->feed->feed_type_mime == 'application/json' ) {
								$dparams[ preg_replace( '/\s+/', '_', $dparam->dparam_name ) ] = $value;
							} else {
								$dparamsstring.='<xsl:variable name="' . preg_replace( '/\s+/', '_', $dparam->dparam_name ) . '" select="' . $value . '" />';
							}
						}
						
						if ( $this->feed->feed_type_mime == 'application/json' ) {
							// $this->dparams_array = $dparams;
							/* append the display parameters to the feed data */
							$this->feed->data = preg_replace( '/\{/', '{ "parameters": ' . json_encode( $dparams ) . ', ', $this->feed->data, 1 );
						} else {
							/* do variable substitutions on the display */
							$this->display[ 'xsl' ] = preg_replace( '/(<xsl:template\s+[^>]+>)/', '$1' . $dparamstring, $this->display[ 'xsl' ], 1 );
						}
					}
				} else {
					$this->error = sprintf( __( 'The display was not found for instance ID %s', 'nxf-transform' ), $instance_id );
				}
				
			} else {
				$this->error = sprintf( __( 'The feed for instance ID %s was not found', 'nxf-transform' ), $instance_id );
			}
		}
		
		private function feed_parameters( $uri ) {
			/* if this feed has parameters processes them */
			if ( $this->fparams ) {
				$has_get = 0;
				
				foreach ( $this->fparams as $fparam ) {
					switch( $fparam->fparam_type_out ) {
						case 'GET':
							$uri .= $has_get ? '&' : '?';
							$uri .= $fparam->fparam_name_out . '=' . $this->parameter_value( $fparam );
							$has_get = 1;
							break;
							
						case 'POST':
							/* use the POST parameter */
							$this->post[ $fparam->fparam_name_out ] = $this->parameter_value( $fparam );
							break;
							
						case 'COOKIE':
							/* use the COOKIE parameter */
							$this->cookies[ $fparam->fparam_name_out ] = $this->parameter_value( $fparam );
							break;
							
						case 'HEADER':
							/* use the HEADER parameter */
							//$this->headers[ $param[ 'name_out' ] ] = $this->parameter_value( $param, $param[ 'type_out' ] );
							// this was commented previously -> array_push( $this->headers, $param[ 'name_out'] . ': ' . $this->parameter_value( $param, $param[ 'type_out' ] ) );
							
							$this->headers[ $fparam->fparam_name_out ] = $this->parameter_value( $fparam );
							break;
							
						case 'FILE':
							/* use the FILE parameteri, not implemented */
							break;
					}
				}
			}
			
			$uri = $this->file_uri_sub( $uri );

			return $uri;
		}
		
		private function file_uri_sub( $subject ) {
			global $wpdb;
			
			/* process the feed subject for parameters as well */
			$subject = preg_replace_callback( '/(\$\{GET\.([^\}]+)\})/', function( $m ) {
					foreach ( $m as $match ) {
							if ( substr( $match, 0, 6 ) != '${GET.' ) {
									return $_GET[$match];
							}
					}
			}, $subject );

			$subject = preg_replace_callback( '/(\$\{POST\.([^\}]+)\})/', function( $m ) {
					foreach ( $m as $match ) {
							if ( substr( $match, 0, 7 ) != '${POST.' ) {
									return $_POST[$match];
							}
					}
			}, $subject );

			$subject = preg_replace_callback( '/(\$\{COOKIE\.([^\}]+)\})/', function( $m ) {
					foreach ( $m as $match ) {
							if ( substr( $match, 0, 9 ) != '${COOKIE.' ) {
									return $_GET[$match];
							}
					}
			}, $subject );

			$subject = preg_replace_callback( '/(\$\{WP\.login\})/', function( $m ) {
					$user = wp_get_current_user();
					foreach ( $m as $match ) {
							return $user->user_login;
					}
			}, $subject );

			$subject = preg_replace_callback( '/(\$\{WP\.ID\})/', function( $m ) {
					$user = wp_get_current_user();
					foreach ( $m as $match ) {
							return $user->ID;
					}
			}, $subject );
			
			$subject = preg_replace_callback( '/(\$\{SETTING\.([^\}]+)\})/', function ( $m ) {	
				global $wpdb;
				foreach ( $m as $match ) {
					if ( substr( $match, 0, 10 ) != '${SETTING.' ) {
						$setting = $wpdb->get_row(
							$wpdb->prepare(
								'SELECT ' . $wpdb->prefix . 'nxf_settings.ID,
										' . $wpdb->prefix . 'nxf_settings.setting_type,
										' . $wpdb->prefix . 'nxf_settings.setting_name,
										' . $wpdb->prefix . 'nxf_settings.setting_value
								   FROM ' . $wpdb->prefix . 'nxf_settings
								  WHERE ' . $wpdb->prefix . 'nxf_settings.setting_name=%s', $match
							)
						);
						return $setting->setting_value;
					}
				}
			}, $subject );
			
			/* sanitize the URI */
			$subject = sanitize_text_field( $subject );
			/* remove any "../" characters in the URI */
			$subject = str_replace( '../', '', $subject );

			return $subject;
		}
		
		private function parameter_value( $fparam ) {
			global $wpdb;
			
			$value = '';
			
			switch( $fparam->fparam_type_in ) {
				case 'GET':
					$value = sanitize_text_field( $_GET[ $fparam->fparam_name_in ] );
					break;
					
				case 'POST':
					$value = sanitize_text_field( $_POST[ $fparam->fparam_name_in ] );
					break;
				
				case 'COOKIE':
					$value = sanitize_text_field( $_COOKIE[ $fparam->fparam_name_in ] );
					break;
				
				case 'HEADER':
					$headers = getallheaders();
					$value = sanitize_text_field( $headers[ $fparam->fparam_name_in ] );
					break;
					
				case 'FILE':
					break;
					
				case 'SETTING':
					$setting = $wpdb->get_row(
						$wpdb->prepare(
							'SELECT ' . $wpdb->prefix . 'nxf_settings.setting_value
							   FROM ' . $wpdb->prefix . 'nxf_settings
							  WHERE ' . $wpdb->prefix . 'nxf_settings.setting_name=%s', $fparam->fparam_name_in
						)
					);
					
					$value = $setting->setting_value;
					break;
					
				case 'STATIC':
					$value = $fparam->fparam_name_in;
					break;
					
				case 'WP':
					$user = wp_get_current_user();
					if ( $fparam->fparam_name_in == 'login' ) {
						$value = $user->user_login;
					} else if ( $fparam->fparam_name_in == 'ID' ) { 
						$value = $user->ID;
					}
					break;
			}
			
			if ( strcmp( $type_out, 'GET' ) == 0 ) { 
				$value = rawurlencode( $value );
				if ( strcmp( $value, '' ) != 0 ) { $value = '=' . $value; }
			}
			
			return $value;
		}
	}
}
