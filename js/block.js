( function() {
	
	var el = wp.element.createElement;
	var __ = wp.i18n.__;
	var i18n = wp.i18n;
	var blocks = wp.blocks;
	var SelectControl = wp.components;
	var BlockDescription  = wp.blocks.description;

	var blockStyle = {
		backgroundColor: '#900',
		color: '#fff',
		padding: '20px',
	};
	
	const attributes = {
		instance: {
			type: 'string',
		}
	};

	i18n.setLocaleData( window.nxf_transform.localeData, 'nxf_transform' );

	blocks.registerBlockType( 'nxf-transform/instance-insert', {
		title: __( 'Transform Instance', 'nxf_transform' ),
		description: __( 'Select an instance in the editor', 'nxf_transform' ),
		icon: 'admin-generic',
		category: 'embed',
		supportHTML: false,

		attributes,

		edit: function( props ) {
			var instance = props.attributes.instance || '', focus = props.focus;
			
            if ( instance == '' ) {
            	instance = nxf_transform.model.instances[0].ID;
            	props.setAttributes({ instance: instance });
            }

			function onChangeSelectedInstance(e) {
				props.setAttributes({ instance: e.target.value });
			}

			return (
				el(
					'div',
					{ class: 'nxf-instance-form' },
					el(
						'strong',
						null,
						__( "Transform Instances", "nxf_transform" )
					),
					el(
						'form',
						null,
						el(
							'select',
							{ value: props.attributes.instance, onChange: onChangeSelectedInstance },
							nxf_transform.model.instances.map(function(selectInstance) {
								return el(
									'option',
									{ key: selectInstance.ID, value: selectInstance.ID },
									selectInstance.feed_name
								)
							})
						)
					)
				)
			);
		},
		save: function( props ) {
			var attributes = props.attributes;
	
			return (
				el( 'div', { className: attributes.className }
				)
			);
		},
		transforms: {
    		from: [
				{
					type: 'shortcode',
					tag: 'transform_feed',
					attributes: {
						instance: {
							type: 'string',
							shortcode: function( parameter ) {
								var instance = parameter.named.instance ? parameter.named.instance : '';
								return instance;
							},
						},
					},
				},
			]
		},
	} );
})();

