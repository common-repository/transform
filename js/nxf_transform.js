function nxf_delete_setting( id ) {
	jQuery("span#spinner-"+id).show();
	
	var formJSON = {
		action: 'delete-setting',
		id: id
	};

	jQuery.post(
		PT_Ajax.ajaxurl,
		{
			/* wp ajax action */
			action : 'ajax-nxf-dialogsubmit',
				
			/* send the nonce along with the request */
			nextNonce : PT_Ajax.nextNonce,
			
			data: formJSON
		},
		function( response ) {
			if ( response.hasOwnProperty( 'error' ) ) {
				jQuery("span.nxf-spinner").hide();
				jQuery( '#nxf-error' ).html( response[ 'error' ] );
			} else if ( response.hasOwnProperty( 'inline-error' ) ) {
				jQuery("span.nxf-spinner").hide();
				alert( response[ 'inline-error' ] );
			} else if ( response.hasOwnProperty( 'window' ) ) {
				if ( response[ 'window' ] == 'reload' ) { location.reload( true ); }
					else { jQuery("span.nxf-spinner").hide(); }
			} else {
				jQuery("span.nxf-spinner").hide();
				alert( PT_Ajax.defaultResponse + response[ 'html' ] );
			}
		}
	).fail( function(xhr, status, error) { 
		jQuery("span.nxf-spinner").hide();
		alert( "An error occurred: " + error ); 
	} );
	
	return false;
}

function nxf_edit_setting_dialog_content( id ) {
	jQuery("span#spinner-"+id).show();
	jQuery.post(
		PT_Ajax.ajaxurl,
		{
			/* wp ajax action */
			action : 'ajax-nxf-editsetting',
			
			/* setting to edit */
			id : id,
				
			/* send the nonce along with the request */
			nextNonce : PT_Ajax.nextNonce
		},
		function( response ) {
			jQuery("span.nxf-spinner").hide();
			
			if ( response.hasOwnProperty( 'error' ) ) {
				jQuery( '#editsetting' ).html( response[ 'error' ] );
			} else {
				jQuery( '#editsetting' ).html( response[ 'html' ] );
			}
			
			nxf_show_dialog( PT_Ajax.editTransformSetting, '600px', PT_Ajax.updateSetting, 'editsetting' );
		}
	).fail( function(xhr, status, error) { 
		jQuery("span.nxf-spinner").hide();
		alert( "An error occurred: " + error );
	} );
	
	return false;
}

function nxf_delete_feed( id ) {
	jQuery("span#spinner-"+id).show();

	var formJSON = {
		action: 'delete-feed',
		id: id
	};

	jQuery.post(
		PT_Ajax.ajaxurl,
		{
			/* wp ajax action */
			action : 'ajax-nxf-dialogsubmit',
				
			/* send the nonce along with the request */
			nextNonce : PT_Ajax.nextNonce,
			
			data: formJSON
		},
		function( response ) {
			if ( response.hasOwnProperty( 'error' ) ) {
				jQuery("span.nxf-spinner").hide();
				jQuery( '#nxf-error' ).html( response[ 'error' ] );
			} else if ( response.hasOwnProperty( 'inline-error' ) ) {
				jQuery("span.nxf-spinner").hide();
				alert( response[ 'inline-error' ] );
			} else if ( response.hasOwnProperty( 'window' ) ) {
				if ( response[ 'window' ] == 'reload' ) { location.reload( true ); }
					else { jQuery("span.nxf-spinner").hide(); }
			} else {
				jQuery("span.nxf-spinner").hide();
				alert( PT_Ajax.defaultResponse  + response[ 'html' ] );
			}
		}
	).fail( function(xhr, status, error) { 
		jQuery("span.nxf-spinner").hide();
		alert( "An error occurred: " + error ); 
	} );
	
	return false;
}

function nxf_new_feed_dialog() {
	jQuery("#spinner").show();
	jQuery.post(
		PT_Ajax.ajaxurl,
		{
			/* wp ajax action */
			action : 'ajax-nxf-newfeed',
				
			/* send the nonce along with the request */
			nextNonce : PT_Ajax.nextNonce
		},
		function( response ) {
			jQuery("#spinner").hide();
			
			if ( response.hasOwnProperty( 'error' ) ) {
				jQuery( '#newfeed' ).html( response[ 'error' ] );
			} else {
				jQuery( '#newfeed' ).html( response[ 'html' ] );
			}
			
			nxf_show_dialog( PT_Ajax.newTransformFeed, '720px', PT_Ajax.addNewFeed, 'newfeed' );
		}
	).fail( function(xhr, status, error) { 
		jQuery("#spinner").hide();
		alert( "An error occurred: " + error );
	} );
	
	return false;
}

function nxf_edit_feed_dialog_content( id ) {
	jQuery("span#spinner-"+id).show();
	jQuery.post(
		PT_Ajax.ajaxurl,
		{
			/* wp ajax action */
			action : 'ajax-nxf-editfeed',
			
			/* id of the feed to edit */
			id: id,
				
			/* send the nonce along with the request */
			nextNonce : PT_Ajax.nextNonce
		},
		function( response ) {
			jQuery("span.nxf-spinner").hide();
			
			if ( response.hasOwnProperty( 'error' ) ) {
				jQuery( '#newfeed' ).html( response[ 'error' ] );
			} else {
				jQuery( '#newfeed' ).html( response[ 'html' ] );
			}
			
			nxf_show_dialog( PT_Ajax.editTransformFeed, '720px', PT_Ajax.updateFeed );
			
			if ( response.hasOwnProperty( 'error' ) ) {
				// remove the buttons from the dialog on error
				jQuery( '#newfeed' ).dialog("option", "buttons", {});
			}
		}
	).fail( function(xhr, status, error) { 
		jQuery("span.nxf-spinner").hide();
		alert( "An error occurred: " + error ); 
	} );
	
	return false;
}

/* Instance functions */

function nxf_delete_instance( id ) {
	jQuery("span#spinner-"+id).show();
	
	var formJSON = {
		action: 'delete-instance',
		id: id
	};

	jQuery.post(
		PT_Ajax.ajaxurl,
		{
			/* wp ajax action */
			action : 'ajax-nxf-dialogsubmit',
				
			/* send the nonce along with the request */
			nextNonce : PT_Ajax.nextNonce,
			
			data: formJSON
		},
		function( response ) {
			if ( response.hasOwnProperty( 'error' ) ) {
				jQuery("span.nxf-spinner").hide();
				jQuery( '#nxf-error' ).html( response[ 'error' ] );
			} else if ( response.hasOwnProperty( 'inline-error' ) ) {
				jQuery("span.nxf-spinner").hide();
				alert( response[ 'inline-error' ] );
			} else if ( response.hasOwnProperty( 'window' ) ) {
				if ( response[ 'window' ] == 'reload' ) { location.reload( true ); }
					else { jQuery("span.nxf-spinner").hide(); }
			} else {
				jQuery("span.nxf-spinner").hide();
				alert( PT_Ajax.defaultResponse  + response[ 'html' ] );
			}
		}
	).fail( function(xhr, status, error) { 
		jQuery("span.nxf-spinner").hide();
		alert( "An error occurred: " + error ); 
	} );
	
	return false;
}

function nxf_edit_instance_dialog_content( id ) {
	jQuery("span#spinner-"+id).show();
	jQuery.post(
		PT_Ajax.ajaxurl,
		{
			/* wp ajax action */
			action : 'ajax-nxf-editinstance',
			
			/* id of the instance to edit */
			id: id,
				
			/* send the nonce along with the request */
			nextNonce : PT_Ajax.nextNonce
		},
		function( response ) {
			jQuery("span.nxf-spinner").hide();
			
			if ( response.hasOwnProperty( 'error' ) ) {
				jQuery( '#newinstance' ).html( response[ 'error' ] );
			} else {
				jQuery( '#newinstance' ).html( response[ 'html' ] );
			}
			
			nxf_show_dialog( PT_Ajax.editTransformInstance, '600px', PT_Ajax.updateInstance, 'newinstance' );
			
			if ( response.hasOwnProperty( 'error' ) ) {
				// remove the buttons from the dialog on error
				jQuery( '#newinstance' ).dialog("option", "buttons", {});
			}
		}
	).fail( function(xhr, status, error) { 
		jQuery("span.nxf-spinner").hide();
		alert( "An error occurred: " + error ); 
	} );
	
	return false;
}

function nxf_new_instance_dialog() {
	jQuery("#spinner").show();
	jQuery.post(
		PT_Ajax.ajaxurl,
		{
			/* wp ajax action */
			action : 'ajax-nxf-newinstance',
				
			/* send the nonce along with the request */
			nextNonce : PT_Ajax.nextNonce
		},
		function( response ) {
			jQuery("#spinner").hide();
			
			if ( response.hasOwnProperty( 'error' ) ) {
				jQuery( '#newinstance' ).html( response[ 'error' ] );
			} else {
				jQuery( '#newinstance' ).html( response[ 'html' ] );
			}
			
			nxf_show_dialog( PT_Ajax.newTransformInstance, '600px', PT_Ajax.addNewInstance, 'newinstance' );
			
			// don't show the buttons if there was an error
			if ( response.hasOwnProperty( 'error' ) ) {
				jQuery( '#newinstance' ).dialog("option", "buttons", {});
			}
		}
	).fail( function(xhr, status, error) {
		jQuery("#spinner").hide();
		alert( "An error occurred: " + error );
	} );
	
	return false;
}

/* Stetting functions */

function nxf_new_setting_dialog() {
	jQuery("#spinner").show();
	jQuery.post(
		PT_Ajax.ajaxurl,
		{
			/* wp ajax action */
			action : 'ajax-nxf-newsetting',
				
			/* send the nonce along with the request */
			nextNonce : PT_Ajax.nextNonce
		},
		function( response ) {
			jQuery("#spinner").hide();
			
			if ( response.hasOwnProperty( 'error' ) ) {
				jQuery( '#newsetting' ).html( response[ 'error' ] );
			} else {
				jQuery( '#newsetting' ).html( response[ 'html' ] );
			}
			
			nxf_show_dialog( PT_Ajax.newTransformSetting, '600px', PT_Ajax.addNewSetting, 'newsetting' );
		}
	).fail( function(xhr, status, error) {
		jQuery("#spinner").hide();
		alert( "An error occurred: " + error );
	} );
	
	return false;
}

function nxf_new_display_dialog() {
	jQuery("#spinner").show();
	jQuery.post(
		PT_Ajax.ajaxurl,
		{
			/* wp ajax action */
			action : 'ajax-nxf-newdisplay',
				
			/* send the nonce along with the request */
			nextNonce : PT_Ajax.nextNonce
		},
		function( response ) {
			jQuery("#spinner").hide();
			
			if ( response.hasOwnProperty( 'error' ) ) {
				jQuery( '#newdisplay' ).html( response[ 'error' ] );
			} else {
				jQuery( '#newdisplay' ).html( response[ 'html' ] );
			}
			
			nxf_show_dialog( PT_Ajax.newFeedDisplay, '840px', PT_Ajax.addNewDisplay, 'newdisplay' );
		}
	).fail( function(xhr, status, error) {
		jQuery("#spinner").hide();
		alert( "An error occurred: " + error );
	} );
	
	return false;
}

function nxf_delete_display( id ) {
	jQuery("span#spinner-"+id).show();
	
	var formJSON = {
		action: 'delete-display',
		id: id
	};

	jQuery.post(
		PT_Ajax.ajaxurl,
		{
			/* wp ajax action */
			action : 'ajax-nxf-dialogsubmit',
				
			/* send the nonce along with the request */
			nextNonce : PT_Ajax.nextNonce,
			
			data: formJSON
		},
		function( response ) {
			if ( response.hasOwnProperty( 'error' ) ) {
				jQuery("span.nxf-spinner").hide();
				jQuery( '#nxf-error' ).html( response[ 'error' ] );
			} else if ( response.hasOwnProperty( 'inline-error' ) ) {
				jQuery("span.nxf-spinner").hide();
				alert( response[ 'inline-error' ] );
			} else if ( response.hasOwnProperty( 'window' ) ) {
				if ( response[ 'window' ] == 'reload' ) { location.reload( true ); }
					else { jQuery("span.nxf-spinner").hide(); }
			} else {
				jQuery("span.nxf-spinner").hide();
				alert( PT_Ajax.defaultResponse  + response[ 'html' ] );
			}
		}
	).fail( function(xhr, status, error) { 
		jQuery("span.nxf-spinner").hide();
		alert( "An error occurred: " + error ); 
	});
	
	return false;
}

function nxf_edit_display_dialog_content( id ) {
	jQuery("span#spinner-"+id).show();
	jQuery.post(
		PT_Ajax.ajaxurl,
		{
			/* wp ajax action */
			action : 'ajax-nxf-editdisplay',
			
			/* setting to edit */
			id : id,
				
			/* send the nonce along with the request */
			nextNonce : PT_Ajax.nextNonce
		},
		function( response ) {
			jQuery("span.nxf-spinner").hide();
			
			if ( response.hasOwnProperty( 'error' ) ) {
				jQuery( '#editdisplay' ).html( response[ 'error' ] );
			} else {
				jQuery( '#editdisplay' ).html( response[ 'html' ] );
			}
			
			nxf_show_dialog( PT_Ajax.editTransformDisplay, '840px', PT_Ajax.updateDisplay, 'editdisplay' );
		}
	).fail( function(xhr, status, error) { 
		jQuery("span.nxf-spinner").hide();
		alert( "An error occurred: " + error );
	} );
	
	return false;
}

function nxf_delete_feedtype( id ) {
	jQuery("span#spinner-"+id).show();
	
	var formJSON = {
		action: 'delete-feedtype',
		id: id
	};

	jQuery.post(
		PT_Ajax.ajaxurl,
		{
			/* wp ajax action */
			action : 'ajax-nxf-dialogsubmit',
				
			/* send the nonce along with the request */
			nextNonce : PT_Ajax.nextNonce,
			
			data: formJSON
		},
		function( response ) {
			if ( response.hasOwnProperty( 'error' ) ) {
				jQuery("span.nxf-spinner").hide();
				jQuery( '#nxf-error' ).html( response[ 'error' ] );
			} else if ( response.hasOwnProperty( 'inline-error' ) ) {
				jQuery("span.nxf-spinner").hide();
				alert( response[ 'inline-error' ] );
			} else if ( response.hasOwnProperty( 'window' ) ) {
				if ( response[ 'window' ] == 'reload' ) { location.reload( true ); }
					else { jQuery("span.nxf-spinner").hide(); }
			} else {
				jQuery("span.nxf-spinner").hide();
				alert( PT_Ajax.defaultResponse  + response[ 'html' ] );
			}
		}
	).fail( function(xhr, status, error) { 
		jQuery("span.nxf-spinner").hide();
		alert( "An error occurred: " + error ); 
	} );
	
	return false;
}

function nxf_edit_feedtype_dialog_content( id ) {
	jQuery("span#spinner-"+id).show();
	jQuery.post(
		PT_Ajax.ajaxurl,
		{
			/* wp ajax action */
			action : 'ajax-nxf-editfeedtype',
			
			/* id of the feed type to edit */
			id: id,
				
			/* send the nonce along with the request */
			nextNonce : PT_Ajax.nextNonce
		},
		function( response ) {
			jQuery("span.nxf-spinner").hide();
			
			if ( response.hasOwnProperty( 'error' ) ) {
				jQuery( '#editfeedtype' ).html( response[ 'error' ] );
			} else {
				jQuery( '#editfeedtype' ).html( response[ 'html' ] );
			}
			
			nxf_show_dialog( PT_Ajax.editTransformFeedType, '680px', PT_Ajax.updateFeedType, 'editfeedtype'  );
		}
	).fail( function(xhr, status, error) { 
		jQuery("span.nxf-spinner").hide();
		alert( "An error occurred: " + error );
	} );
	
	return false;
}

function nxf_new_feedtype_dialog() {
	jQuery("#spinner").show();
	jQuery.post(
		PT_Ajax.ajaxurl,
		{
			/* wp ajax action */
			action : 'ajax-nxf-newfeedtype',
				
			/* send the nonce along with the request */
			nextNonce : PT_Ajax.nextNonce
		},
		function( response ) {
			jQuery("#spinner").hide();
			
			if ( response.hasOwnProperty( 'error' ) ) {
				jQuery( '#newfeedtype' ).html( response[ 'error' ] );
			} else {
				jQuery( '#newfeedtype' ).html( response[ 'html' ] );
			}
			
			nxf_show_dialog( PT_Ajax.newTransformFeedType, '680px', PT_Ajax.addNewFeedType, 'newfeedtype' );
		}
	).fail( function(xhr, status, error) { 
		jQuery("#spinner").hide();
		alert( "An error occurred: " + error ); 
	} );
	
	return false;
}

/*
function list_feedtype_displays_dialog_content( id ) {
	jQuery.post(
		PT_Ajax.ajaxurl,
		{
			action : 'ajax-nxf-listfeeddisplays',

			id: id,
				
			nextNonce : PT_Ajax.nextNonce
		},
		function( response ) {
			if ( response.hasOwnProperty( 'error' ) ) {
				jQuery( '#newfeedtype' ).html( response[ 'error' ] );
			} else {
				jQuery( '#newfeedtype' ).html( response[ 'html' ] );
			}
			
			nxf_show_dialog( PT_Ajax.feedTypeDisplays, '600px', '', 'newfeedtype' );
		}
	).fail( function(xhr, status, error) { alert( "An error occurred: " + error ); } );
	
	return false;
}
*/

/*
function select_feed_display_dialog_content( id ) {
	jQuery.post(
		PT_Ajax.ajaxurl,
		{
			action : 'ajax-nxf-editfeeddisplay',
			
			id: id,
				
			nextNonce : PT_Ajax.nextNonce
		},
		function( response ) {
			if ( response.hasOwnProperty( 'error' ) ) {
				jQuery( '#newfeed' ).html( response[ 'error' ] );
			} else {
				jQuery( '#newfeed' ).html( response[ 'html' ] );
			}
			
			nxf_show_dialog( PT_Ajax.selectTransformFeedDisplay, '600px', PT_Ajax.setFeedDisplay );
		}
	).fail( function(xhr, status, error) { alert( "An error occurred: " + error ); } );
	
	return false;
}
*/

/*
function show_feed_shortcode_dialog_content( id ) {
	jQuery.post(
		PT_Ajax.ajaxurl,
		{
			action : 'ajax-nxf-feedshortcode',
			
			id: id,

			nextNonce : PT_Ajax.nextNonce
		},
		function( response ) {
			if ( response.hasOwnProperty( 'error' ) ) {
				jQuery( '#newfeed' ).html( response[ 'error' ] );
			} else {
				jQuery( '#newfeed' ).html( response[ 'html' ] );
			}
			
			nxf_show_dialog( PT_Ajax.transformFeedShortcode, '400px', '' );
		}
	).fail( function(xhr, status, error) { alert( "An error occurred: " + error ); } );
	
	return false;
}
*/

function nxf_show_dialog( title, width, action ) {
	var dialog = 'newfeed';
	if ( arguments.length > 3 ) {
		dialog = arguments[3];
	}
	
	if ( action == '' ) {
	  var buttons = [
			{
				'text' : PT_Ajax.close,
				'class' : 'button-primary',
				'click' : function() {
					jQuery(this).dialog('close');
				}
			}
		]
	} else {
	  var buttons = [
			{
				'text' : PT_Ajax.cancel,
				'class' : 'button-primary',
				'click' : function() {
					jQuery(this).dialog('close');
				}
			},
			{
				'text' : action,
				'class' : 'button-primary',
				'id' : 'button-primary-action',
				'click' : function() {
					// disable the action button to avoid multiple clicks
					jQuery( '#button-primary-action' ).button( 'disable' );
					
					// set the cursor
					jQuery( 'html,body' ).css( 'cursor', 'progress' );
					
					var formJSON = {};
					jQuery('#nxf-' + dialog + ' *').filter(':input').each(function(){
						var input = jQuery(this);
						formJSON[ input.attr( 'name' ) ] = input.val();
					});
					
					// Submit the data through AJAX
					jQuery.post(
						PT_Ajax.ajaxurl,
						{
							/* wp ajax action */
							action : 'ajax-nxf-dialogsubmit',
				
							/* send the nonce along with the request */
							nextNonce : PT_Ajax.nextNonce,
							
							/* data to go with the request */
							data: formJSON
						},
						function( response ) {
							if ( response.hasOwnProperty( 'error' ) ) {
								jQuery( 'html,body' ).css( 'cursor', 'default' );
								jQuery( '#nxf-error' ).html( response[ 'error' ] );
							} else if ( response.hasOwnProperty( 'inline-error' ) ) {
								jQuery( 'html,body' ).css( 'cursor', 'default' );
								alert( response[ 'inline-error' ] );
							} else if ( response.hasOwnProperty( 'window' ) ) {
								if ( response[ 'window' ] == 'reload' ) { location.reload( true ); }
							} else if ( response.hasOwnProperty( 'shortcode' ) ) {
								jQuery( 'body' ).css( 'cursor', 'default' );
								wp.media.editor.insert( response[ 'shortcode' ] );
								jQuery( '#' + dialog ).dialog('close');
							} else {
								alert( PT_Ajax.defaultResponse + JSON.stringify( response ) );
							}
						}
					).fail( function(xhr, status, error) { alert( "An error occurred: " + error ); } );
				}
			}
		];
	}
	
	jQuery('#' + dialog ).dialog({
		'dialogClass' : 'wp-dialog',
		'modal' : true,
		'title' : title,
		'autoOpen' : false,
		'closeOnEscape' : true,
		'width': width,
		'buttons' : buttons
	}).dialog('open');
}

function nxf_bulk_delete( itemtype ) {
	// how many checkboxes are checked?
	if ( jQuery( ".nxf-checkbox[type='checkbox']:checked" ).length == 0 ) {
		alert( PT_Ajax.noneSelected );
		return false;
	}
	
	if ( window.confirm( PT_Ajax.bulkSure + itemtype + PT_Ajax.bulkQM ) ) {
		// set the cursor
		jQuery("#spinner-bulk").show();
		jQuery( 'html,body' ).css( 'cursor', 'progress' );

		var ids=[];
		jQuery( '#nxf-'+itemtype+'-list input:checkbox:checked' ).each( function() {
			if ( jQuery( this ).attr( 'id' ) != 'nxf-select-all-1' ) {
				ids.push( jQuery( this ).val() );
			}
		});

		jQuery.post(
			PT_Ajax.ajaxurl,
			{
				/* wp ajax action */
				action : 'ajax-nxf-bulkdelete',
				type: itemtype,
				ids: ids,
				
				/* send the nonce along with the request */
				nextNonce : PT_Ajax.nextNonce
			},
		
			function( response ) {
			    jQuery("#spinner-bulk").hide();
				if ( response.hasOwnProperty( 'error' ) ) {
					jQuery( 'html,body' ).css( 'cursor', 'default' );
					jQuery( '#nxf-error' ).html( response[ 'error' ] );
				} else {
					location.reload( true );
				}
			}
		).fail( function(xhr, status, error) {
			jQuery("#spinner-bulk").hide();
			alert( "An error occurred: " + error ); 
		} );
	}
	
	return false;
}

jQuery(function($) {
    $(document).ready(function(){
		$( '#insert-transform-media' ).click(nxf_open_media_window);
    });

	function nxf_open_media_window() {
		// open a dialog then load the content via AJAX
		nxf_show_dialog( PT_Ajax.addTransformFeed, '600px', PT_Ajax.insert, 'nxf-dialog' );
		jQuery( '#nxf-dialog' ).html('<div style="width: 90px; vertical-align: middle;"><div class="nxf-loading">Loading </div><div class="nxf-spinner"></div></div><style>.nxf-spinner { float: right; background: url('+"'images/wpspin_light.gif'"+') no-repeat; background-size: 16px 16px; opacity: .7; filter: alpha(opacity=70); width: 16px; height: 16px; margin: 5px 5px 0; } .nxf-loading { float: left; margin: 4px 0px; } .ui-dialog-titlebar-close { visibility: hidden!important; } .wp-core-ui .button-primary { text-shadow: none!important; }</style>');
		
		jQuery.post(
		PT_Ajax.ajaxurl,
		{
			/* wp ajax action */
			action : 'ajax-nxf-insertform',
				
			/* send the nonce along with the request */
			nextNonce : PT_Ajax.nextNonce
		},
		function( response ) {
			if ( response.hasOwnProperty( 'error' ) ) {
				jQuery( '#nxf-dialog' ).html( response[ 'error' ] );
				//nxf_show_dialog( PT_Ajax.addTransformFeed, '600px', PT_Ajax.insert, 'nxf-dialog' );
			} else {
				//nxf_show_dialog( PT_Ajax.addTransformFeed, '600px', PT_Ajax.insert, 'nxf-dialog' );
				jQuery( '#nxf-dialog' ).html( response[ 'html' ] ).promise().done( function() {
						if ( jQuery( '#insert-shortcode' ).length == 0 ) {
							jQuery( '#nxf-dialog' ).ready( jQuery( '#nxf-dialog' ).dialog("option", "buttons", [{
								'text' : PT_Ajax.cancel,
								'class' : 'button-primary',
								'click' : function() {
									jQuery(this).dialog('close');
								}
							}]) );
						}
					}
				);
			}
		}).fail( function(xhr, status, error) { alert( "An error occurred: " + error ); } );
    }
});