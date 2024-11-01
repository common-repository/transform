<?php
if ( !class_exists( 'NXF_Transform_View' ) ) {
	class NXF_Transform_View {
		public function __construct() {
		}
		
		public static function render( $transform ) {
			if ( $transform->error ) {
				return "An error has occurred: " . $transform->error;
			} else {
				if ( $transform->feed->feed_type_mime == 'application/json' ) {
					try {
						// Compile and render a Dust template
						$json = json_decode( $transform->feed->data, true );

						require_once __DIR__ . '/../dust-php-0.1.0/vendor/autoload.php';
						$dust = new \Dust\Dust();
						$template = $dust->compile ($transform->display[ 'xsl' ] );
						$rendered = $dust->renderTemplate( $template, $json );
						
						// the "html_entity_decode" here should be an option of the feed
						return html_entity_decode( $rendered );
					} catch (Exception $e) {
						return sprintf( __( 'An error occurred displaying this feed: %s', 'nxf-transform' ), $e->getMessage() );
					}
				} else {
					set_error_handler(function($errno, $errstr, $errfile, $errline, array $errcontext) {
						if (0 === error_reporting()) {
							return false;
						}

						throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
					}, E_WARNING);
					
					try {
						$xp = new XSLTProcessor();
						$xsldoc = new DOMDocument();
						$xsldoc->loadXML( $transform->display[ 'xsl' ] );
						$xp->importStylesheet( $xsldoc );
				
						$xmldoc = new DOMDocument();
						$xmldoc->loadXML( $transform->feed->data );
						$xslt = $xp->transformToXml( $xmldoc );
						$xslt = str_replace('&amp;', '&', $xslt );
						$xslt = str_replace( '&gt;', '>', $xslt );
						$transform->transformed = str_replace( '&lt;', '<', $xslt );
						
						restore_error_handler();
						return $transform->transformed;
					} catch (Exception $e) {
						restore_error_handler();
						return sprintf( __( 'An error occurred displaying this feed: %s', 'nxf-transform' ), $e->getMessage() );
					}
				}
			}
		}
	}
}
