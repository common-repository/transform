<?php
/**
 * Transform
 *
 * @package   Transform
 * @author    Net Innovations LLC
 * @license   GPL-2.0+
 * @link      http://wordpress.org/plugins/transform
 * @copyright 2014-2018 Net Innovations LLC - all rights reserved
 *
 * @wordpress-plugin
 * Plugin Name:       Transform
 * Plugin URI:        http://wordpress.org/plugins/transform
 * Description:       Transform data feeds to HTML using the Dust or XSLT transformation language
 * Version:           1.4.1
 * Author:            Net Innovations LLC
 * Author URI:        keith@netinnovationsllc.com
 * License:           GPLv2+
 * Text Domain:       nxf-transform
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( defined( 'NXF_FOLDER' ) ) {
	require_once( dirname(__FILE__).'/lib/nxf_bootstrap.php' );
	nxf_error('multiple_nxf');
} else {
	define( 'NXF_FILE', __FILE__ );
	define( 'NXF_BASENAME', plugin_basename( __FILE__ ) );
	define( 'NXF_FOLDER', dirname( plugin_basename( __FILE__ ) ) );
	
	if ( !class_exists( 'NXF_Transform_Plugin' ) ) {
		class NXF_Transform_Plugin {
			public function __construct() {
				add_action( 'plugins_loaded', array( 'NXF_Transform_Plugin', 'nxf_act_load' ), -10, 0);
				register_activation_hook( __FILE__, array( 'NXF_Transform_Plugin', 'nxf_activate' ) );
				register_deactivation_hook( __FILE__, array( 'NXF_Transform_Plugin', 'nxf_deactivate' ) );
			}
			
			public function nxf_act_load() {
				$min_wp_version = '3.4';

				require_once( dirname(__FILE__).'/lib/nxf_bootstrap.php' );
		
				load_plugin_textdomain( 'nxf-transform', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

				global $wp_version;
				if ( version_compare( $wp_version, $min_wp_version, '<' ) ) {
					nxf_error( 'old_wp', $min_wp_version );
				}
		
				require_once( dirname(__FILE__).'/lib/nxf_transform.php' );
			}
			
			public function nxf_activate( $network_wide ) {
				global $wpdb;
		
				require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

				// get the site option to determine if this plugin has already been activated before
				$nxf_transform = get_site_option( 'nxf_transform_installed', '0' );

				$blogs=array();
		
				if ( $network_wide ) {
					/* create an array of all of the blogs on this network */
					$args = array(
						'network_id' => $wpdb->siteid,
						'public'     => null,
						'archived'   => null,
						'mature'     => null,
						'spam'       => null,
						'deleted'    => null,
						'limit'      => 10000,
						'offset'     => 0,
					);
			
					$siteblogs = wp_get_sites( $args );
					foreach ( $siteblogs as $blog ) {
						array_push( $blogs, $blog[ 'blog_id' ] );
					}
			
					/* error_log( "CCG: this is a network-wide activation" ); */
				} else {
					/* activating for this site only */
					array_push( $blogs, get_current_blog_id() );
				}
		
				$charset_collate = '';
	
				if ( ! empty( $wpdb->charset ) ) {
					$charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
				}
	
				if ( ! empty( $wpdb->collate ) ) {
					$charset_collate .= " COLLATE {$wpdb->collate}";
				}
		
				foreach ( $blogs as $blog ) {
					/* create the feeds type table */
					if ( $blog != 1 ) { $feed_types_table_name = $wpdb->base_prefix . $blog . '_nxf_feed_types'; }
						else { $feed_types_table_name = $wpdb->base_prefix . 'nxf_feed_types'; }
		
					$feed_types_sql = "CREATE TABLE $feed_types_table_name (
						ID bigint(20) NOT NULL AUTO_INCREMENT,
						feed_type_name text NOT NULL,
						feed_type_desc text NOT NULL,
						feed_type_mime ENUM('text/xml','application/json'),
						feed_type_params text,
						UNIQUE KEY ID (ID)
					) $charset_collate;";

					dbDelta( $feed_types_sql );

					if ( 0 == $nxf_transform ) {
						/* create the sample DB records for feed types if this is the first time this plugin has been activated */
						$data = array(
							'feed_type_name' => 'Flickr Feed',
							'feed_type_desc' => 'Flickr - Photos Share JSON feed',
							'feed_type_mime' => 'application/json'
						);

						$wpdb->insert( $feed_types_table_name, $data );

						/* get the ID of the last inserted record */
						$flickr_feed_type_id = $wpdb->insert_id;

						/* ignore errors for now */
						
						if ( class_exists( 'XSLTProcessor' ) ) {
							$data = array(
								'feed_type_name' => 'RSS Feed',
								'feed_type_desc' => 'RSS 2.0 feed',
								'feed_type_mime' => 'text/xml'
	
							);
						
							$wpdb->insert( $feed_types_table_name, $data );

							/* get the ID of the last inserted record */
							$rss_feed_type_id = $wpdb->insert_id;
						}
					}
			
					/* create the feeds table */
					if ( $blog != 1 ) { $feeds_table_name = $wpdb->base_prefix . $blog . '_nxf_feeds'; }
						else { $feeds_table_name = $wpdb->base_prefix . 'nxf_feeds'; }
		
					$feeds_sql = "CREATE TABLE $feeds_table_name (
						ID bigint(20) NOT NULL AUTO_INCREMENT,
						feed_type_id bigint(20) NOT NULL,
						feed_name text,
						feed_desc text,
						feed_source ENUM('web','file'),
						feed_uri text,
						feed_file text,
						method ENUM('GET','PUT','POST','DELETE'),
						blog_id bigint(20) DEFAULT NULL,
						UNIQUE KEY ID (ID)
					) $charset_collate;";

					dbDelta( $feeds_sql );

					if ( 0 == $nxf_transform ) {
						/* create the sample DB records for feeds if this is the first time this plugin has been activated */
						if ( class_exists( 'XSLTProcessor' ) ) {
							$data = array(
								'feed_type_id' => $rss_feed_type_id,
								'feed_name' => 'Weather for Sidney, ME',
								'feed_desc' => 'The weather forecast for Sidney, ME in RSS format',
								'feed_source' => 'web',
								'feed_uri' => 'http://www.rssweather.com/wx/us/me/sidney/rss.php',
								'method' => 'GET'
							);

							$wpdb->insert( $feeds_table_name, $data );

							/* get the ID of the last inserted record */
							$rss_feed_id = $wpdb->insert_id;

							/* ignore errors for now */
						}
						
						$data = array(
							'feed_type_id' => $flickr_feed_type_id,
							'feed_name' => 'Flickr images for mountains and trees',
							'feed_desc' => 'A Flickr feed that shows images that match the keywords "mountains" and "trees"',
							'feed_source' => 'web',
							'feed_uri' => 'http://api.flickr.com/services/feeds/photos_public.gne?tags=lakes,trees&tagmode=all&format=json&nojsoncallback=1',
							'method' => 'GET'
						);

						$wpdb->insert( $feeds_table_name, $data );

						/* get the ID of the last inserted record */
						$flickr_feed_id = $wpdb->insert_id;

						/* ignore errors for now */
					}
			
					/* create the displays table */
					if ( $blog != 1 ) { $displays_table_name = $wpdb->base_prefix . $blog . '_nxf_displays'; }
						else { $displays_table_name = $wpdb->base_prefix . 'nxf_displays'; }
			
					$displays_sql = "CREATE TABLE $displays_table_name (
						ID bigint(20) NOT NULL AUTO_INCREMENT,
						feed_type_id bigint(20) NOT NULL,
						display_name text,
						display_desc text,
						display longtext,
						display_type ENUM('FILE','URL','DB'),
						blog_id bigint(20) DEFAULT NULL,
						UNIQUE KEY ID (ID)
					) $charset_collate;";
			
					dbDelta( $displays_sql );

					if ( 0 == $nxf_transform ) {
						/* create the sample DB records for displays if this is the first time this plugin has been activated */
						if ( class_exists( 'XSLTProcessor' ) ) {
							$data = array(
								'feed_type_id' => $rss_feed_type_id,
								'display_name' => 'RSS 2.0 Feed',
								'display_desc' => 'XSL for an RSS 2.0 Feed',
								'display' => '<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet  xmlns:xsl="http://www.w3.org/1999/XSL/Transform"  version="1.0">
<xsl:output method="html"/>
  <xsl:template match="rss">
    <div>
      <h3><xsl:value-of select="channel/title"/></h3>
    </div>

    <xsl:for-each select="channel/item">
      <div>
      	<xsl:attribute name="style">font-weight: bold;</xsl:attribute>
      	<a>
      	  <xsl:attribute name="href"><xsl:value-of select="link" /></xsl:attribute>
      	  <xsl:attribute name="title"><xsl:value-of select="title" /></xsl:attribute>
          <xsl:value-of select="title" />
        </a>
      </div>

      <div>
        <xsl:attribute name="style">margin-bottom: 10px;</xsl:attribute>
        <xsl:value-of select="description" />
      </div>
    </xsl:for-each>
  </xsl:template>
</xsl:stylesheet>',
								'display_type' => 'DB',
								'blog_id' => ''
							);

							$wpdb->insert( $displays_table_name, $data );

							/* get the ID of the display that was inserted */
							$rss_display_id = $wpdb->insert_id;

							/* ignore errors for now */
                        }
                        
                        $data = array(
							'feed_type_id' => $flickr_feed_type_id,
							'display_name' => 'List of Flickr Images',
							'display_desc' => 'Displays a list of Flickr Images',
							'display' => '<h3>{title}</h3>
{#items}
{@lt key=$idx value="{parameters.flickrImages}"}
<h4>{title} - {$idx}</h4>
<div>{description}</div>
{/lt}
{/items}',
							'display_type' => 'DB',
							'blog_id' => ''
                        );

                        $wpdb->insert( $displays_table_name, $data );

						/* get the ID of the display that was inserted */
						$flickr_display_id = $wpdb->insert_id;

                        /* ignore errors for now */
                    }
			
					/* create the instances table */
					if ( $blog != 1 ) { $instances_table_name = $wpdb->base_prefix . $blog . '_nxf_instances'; }
						else { $instances_table_name = $wpdb->base_prefix . 'nxf_instances'; }
			
					$instances_sql = "CREATE TABLE $instances_table_name (
						ID bigint(20) NOT NULL AUTO_INCREMENT,
						feed_id bigint(20) NOT NULL,
						display_id bigint(20) NOT NULL,
						instance_name text NOT NULL,
						UNIQUE KEY ID (ID)
					) $charset_collate;";
			
					dbDelta( $instances_sql );

					if ( 0 == $nxf_transform ) {
						/* create the sample DB records for feeds if this is the first time this plugin has been activated */
						if ( class_exists( 'XSLTProcessor' ) ) {
							$data = array(
								'feed_id' => $rss_feed_id,
								'display_id' => $rss_display_id,
								'instance_name' => 'Forecast for Sidney, ME'
							);

							$wpdb->insert( $instances_table_name, $data );

							/* get the ID of the last inserted record */
							$rss_instance_id = $wpdb->insert_id;

							/* ignore errors for now */
						}
						
						$data = array(
							'feed_id' => $flickr_feed_id,
							'display_id' => $flickr_display_id,
							'instance_name' => 'Flickr Mountains and Trees Images'
						);

						$wpdb->insert( $instances_table_name, $data );

						/* get the ID of the last inserted record */
						$flickr_instance_id = $wpdb->insert_id;

						/* ignore errors for now */
					}
			
					/* create the feed parameters table */
					if ( $blog != 1 ) { $feed_params_table_name = $wpdb->base_prefix . $blog . '_nxf_fparams'; }
						else { $feed_params_table_name = $wpdb->base_prefix . 'nxf_fparams'; }
				
					$feed_params_sql = "CREATE TABLE $feed_params_table_name (
						ID bigint(20) NOT NULL AUTO_INCREMENT,
						feed_id bigint(20) NOT NULL,
						fparam_type_in ENUM('POST','GET','COOKIE','HEADER','FILE','STATIC','WP','SETTING'),
						fparam_name_in text NOT NULL,
						fparam_value_in text,
						fparam_type_out ENUM('POST','GET','COOKIE','HEADER','FILE'),
						fparam_name_out text NOT NULL,
						fparam_value_out text,
						UNIQUE KEY ID (ID)
					) $charset_collate;";
			
					dbDelta( $feed_params_sql );
			
					/* create the display parameters table */
					if ( $blog != 1 ) { $display_params_table_name = $wpdb->base_prefix . $blog . '_nxf_dparams'; }
						else { $display_params_table_name = $wpdb->base_prefix . 'nxf_dparams'; }
				
					$display_params_sql = "CREATE TABLE $display_params_table_name (
						ID bigint(20) NOT NULL AUTO_INCREMENT,
						instance_id bigint(20) NOT NULL,
						display_id bigint(20) NOT NULL,
						dparam_type ENUM('POST','GET','STATIC','SETTING'),
						dparam_name text NOT NULL,
						dparam_value text,
						dparam_parent_id bigint(20),
						UNIQUE KEY ID (ID)
					) $charset_collate;";
			
					dbDelta( $display_params_sql );

					if ( 0 == $nxf_transform ) {
                                                /* create the sample DB records for display parameters if this is the first time this plugin has been activated */
                                                $data = array(
							'display_id' => $flickr_display_id,
							'dparam_type' => 'SETTING',
							'dparam_name' => 'flickrImages',
							'dparam_parent_id' => 0
                                                );

                                                $wpdb->insert( $display_params_table_name, $data );

                                                /* ignore errors for now */
                                        }
			
					/* create the settings table */
					if ( $blog != 1 ) { $settings_table_name = $wpdb->base_prefix . $blog . '_nxf_settings'; }
						else { $settings_table_name = $wpdb->base_prefix . 'nxf_settings'; }
				
					$settings_sql = "CREATE TABLE $settings_table_name (
						ID bigint(20) NOT NULL AUTO_INCREMENT,
						setting_type ENUM('Text','Numeric','Date') NOT NULL DEFAULT 'Text',
						setting_name text NOT NULL,
						setting_value text,
						UNIQUE KEY ID (ID)
					) $charset_collate;";
			
					dbDelta( $settings_sql );
					
					if ( 0 == $nxf_transform ) {
						/* create the sample DB records for feeds if this is the first time this plugin has been activated */
						$data = array(
							'setting_type' => 'Numeric',
							'setting_name' => 'flickrImages',
							'setting_value' => '5'
						);

						$wpdb->insert( $settings_table_name, $data );

						/* ignore errors for now */
					}
				}

				/* set the site setting to indicate that transform has been previously activated */
				update_site_option( 'nxf_transform_installed', '1' );
		
				/* record the database version */
				add_option( "nxf_db_version", "1.0" );
			}

			public function nxf_deactivate( $network_wide ) {
				global $wpdb;
		
				/* do we need to do anything here? */
			}
		}
	}
	
	/* const SLUG = 'nxf-transform'; */

	$nxf_transform_plugin = new NXF_Transform_Plugin();
}
