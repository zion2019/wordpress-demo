;(
	function ( $, window, document ) {
		'use strict';

		$.fn.equipMedia = function ( options ) {

			return this.each( function () {
				var settings, data, value;
				var $field = $( this );
				var API = $.fn.equipMedia.API;

				// get field value and data
				value = $field.val();
				data = $field.data();

				// field settings
				settings = $.extend( {}, $.fn.equipMedia.defaults, data, options || {} );

				// append the markup and connect the markup
				// with field for easy access
				$field.after( API.getMarkup( value, settings ) );
				$field.siblings( '.equip-media-wrapper' ).data( 'field', $field.attr( 'id' ) );
				$field.siblings( '.equip-media-wrapper' ).addClass(
					true === settings.multiple ? 'equip-media-multiple' : 'equip-media-single'
				);

				if ( settings.sortable ) {
					var $sortableItems = $field.parent().find( '.equip-media-items' );
					$sortableItems.sortable( {
						cursor: 'move',
						items: '> .equip-media-item',
						placeholder: 'equip-media-item-highlight'
					} ).disableSelection();

					$sortableItems.on( 'sortupdate', function ( e, ui ) {
						var IDs = $sortableItems.sortable( 'toArray', {attribute: 'data-id'} );
						$field.val( IDs.join( ',' ) )
					} );
				}
			} );
		};

		$.fn.equipMedia.defaults = {
			multiple: false,
			sortable: false,
			preview: [],
			title: '',
			button: ''
		};

		$.fn.equipMedia.API = {

			openMediaFrame: function ( e, $this ) {
				e.preventDefault();

				var self = this;
				var $field = self.getField( $this );

				// field settings and value
				var settings = $.extend( {}, $.fn.equipMedia.defaults, $field.data() );
				var value = $field.val();

				var mediaFrame = wp.media( {
					title: settings.title,
					multiple: settings.multiple,
					button: {text: settings.button}
				} );

				mediaFrame.on( 'select', function () {
					var $markup;
					var $control = $this.parent( '.equip-media-control' );
					var IDs = value || [];
					var previewHTML = '';
					var attachments = mediaFrame.state().get( 'selection' ).toJSON();

					if ( 'string' === typeof IDs ) {
						IDs = IDs.split( ',' );
					}

					// prepare preview and IDs for field's value
					$.each( attachments, function ( key, attachment ) {
						var hasMedium = attachment.sizes.hasOwnProperty( 'medium' );
						IDs.push( attachment.id );
						previewHTML += self.getItem( {
							id: attachment.id,
							src: hasMedium ? attachment.sizes.medium.url : attachment.sizes.full.url
						} );
					} );

					$markup = $( previewHTML );

					// Append the HTML to preview area
					// In current implementation images added before the "add" control
					// If field declared as non-multiple first <li> will be replaced with new (selected) image
					if ( settings.multiple ) {
						$control.before( $markup );
					} else {
						var LIs = $this.parents( '.equip-media-items' ).find( 'li' );
						if ( LIs.length > 1 ) {
							LIs.first().replaceWith( $markup );
						} else {
							$control.before( $markup );
						}
					}

					// Add IDs to hidden field and trigger the change event
					$field.val( IDs.join( ',' ) );
					$field.trigger( 'change' );
				} );

				mediaFrame.open();
			},

			removeMedia: function ( e, $this ) {
				e.preventDefault();

				var self = this;
				var $container = $this.parent( '.equip-media-item' );
				var attachmentID = $container.data( 'id' ).toString();
				var $field = self.getField( $this );

				// Delete <li>
				$container.remove();

				// Remove ID from hidden field
				// This should work normally both for single and multiple images
				var IDs = $field.val().split( ',' );
				var position = IDs.indexOf( attachmentID );
				if ( ~ position ) {
					IDs.splice( position, 1 );
				}

				// Save new value and trigger the change event
				$field.val( IDs.join( ',' ) );
				$field.trigger( 'change' );
			},

			getMarkup: function ( value, settings ) {
				var self = this;
				var template = [
					'<div class="equip-media-wrapper">',
					'<ul class="equip-media-items">',
					'{{items}}',
					'<li class="equip-media-control"><a href="#" class="equip-media-add">&#43;</a></li>',
					'</ul>',
					'</div>'
				].join( '' );

				return $( self.prepareTemplate( template, {
					items: self.getItems( value, settings )
				} ) );
			},

			getItems: function ( value, settings ) {
				var self = this;

				if ( value === '' ) {
					return '';
				}

				if ( ~ value.indexOf( ',' ) ) {
					value = value.split( ',' );
				} else {
					value = [value];
				}

				var preview = settings.preview;
				var items = '';

				$.each( value, function ( i, v ) {
					items += self.getItem( {
						id: v,
						src: preview[i] // TODO: check if preview exists, use "no preview" placeholder
					} );
				} );

				// do not convert to jQuery object!
				return items;
			},

			getItem: function ( data ) {
				var self = this;
				//noinspection CssUnknownTarget
				var template = [
					'<li class="equip-media-item" data-id="{{id}}" style="background-image: url({{src}});">',
					'<a href="#" class="equip-media-remove">&times;</a>',
					'</li>'
				].join( '' );

				return self.prepareTemplate( template, {
					id: data.id,
					src: data.src
				} );
			},

			getField: function ( $this ) {
				var fieldId = $this.parents( '.equip-media-wrapper' ).data( 'field' );

				return $( '#' + fieldId );
			},

			prepareTemplate: function ( template, data ) {
				for ( var property in data ) {
					if ( data.hasOwnProperty( property ) ) {
						var search = new RegExp( '{{' + property + '}}', 'g' );
						template = template.replace( search, data[property] );
					}
				}

				return template;
			}
		};

		$( document ).on( 'click', '.equip-media-add', function ( e ) {
			$.fn.equipMedia.API.openMediaFrame( e, $( this ) );
		} );

		$( document ).on( 'click', '.equip-media-remove', function ( e ) {
			$.fn.equipMedia.API.removeMedia( e, $( this ) );
		} );

		$( function () {
			$( '.equip-media' ).equipMedia();
		} );

	}
)( jQuery, window, document );