;(
	function ( $, window, document ) {
		'use strict';

		/**
		 * Socials Field
		 *
		 * Displays the control which allow to choose
		 * the social network and associate a link with it.
		 * For example, this may be used on the frontend to
		 * display the social networks with fancy icons.
		 *
		 * TODO: refactoring required!
		 * TODO: implement extra options
		 * TODO: support for more than one network in a set, e.g. 1+ facebook, more than one twitter, etc..
		 *
		 * @author 8guild
		 * @package Equip
		 *
		 * @param {Object} options
		 */
		$.fn.equipSocials = function ( options ) {
			var API = $.fn.equipSocials.API;

			return this.each( function () {
				var $field = $( this );
				var settings = $.extend( true, {}, $.fn.equipSocials.defaults, $field.data(), options || {} );
				var value = $field.val();

				var markup;

				if ( value !== '' ) {
					var pairs = value.split( ',' );
					$.each( pairs, function ( i, pair ) {
						var network, url;
						[network, url] = pair.split( '|' );

						var networksMarkup = '';
						$.each( settings.networks, function ( networkItem, name ) {
							networksMarkup += API.prepareTemplate( '<option value="{{network}}" {{isSelected}}>{{name}}</option>', {
								network: networkItem,
								isSelected: network === networkItem ? 'selected' : '',
								name: name
							} );
						} );

						if ( typeof markup === 'undefined' ) {
							markup = '';
						}

						markup += '<div class="equip-socials-group">' +
						          '<div class="equip-select-wrap"><select>' +
						          networksMarkup +
						          '</select></div>' +
						          '<input type="text" placeholder="' + settings.placeholder + '" value="' + url + '">' +
						          '<a href="#" class="equip-socials-group-remove" tabindex="-1"><i class="dashicons dashicons-no-alt"></i></a>' +
						          '</div>';
					} );
				} else {
					var networksMarkup = '';
					$.each( settings.networks, function ( network, name ) {
						networksMarkup += '<option value="' + network + '">' + name + '</option>';
					} );

					markup = '<div class="equip-socials-group">' +
					         '<div class="equip-select-wrap"><select>' +
					         networksMarkup +
					         '</select></div>' +
					         '<input type="text" placeholder="' + settings.placeholder + '">' +
					         '<a href="#" class="equip-socials-group-remove" tabindex="-1"><i class="dashicons dashicons-no-alt"></i></a>' +
					         '</div>';
				}

				var $markup = $( '<div/>', {
					'class': 'equip-socials-wrap',
					html: markup
				} );

				var $addButton = $( '<a/>', {
					href: '#',
					'class': 'equip-socials-add',
					html: settings.moreLabel
				} );

				$markup.data( 'field', $field );
				$markup.insertAfter( $field );
				$markup.after( $addButton );
			} );
		};

		$.fn.equipSocials.defaults = {
			networks: [],
			moreLabel: 'Add one more',
			placeholder: 'Network URL'
		};

		$.fn.equipSocials.API = {

			addFiled: function ( e, $button ) {
				e.preventDefault();

				// Detect the wrapper, based on clicked button
				// May be some buttons per one page
				var $wrapper = $button.siblings( '.equip-socials-wrap' );
				// Clone first element
				var $item = $wrapper.find( '.equip-socials-group' ).first().clone();

				// Append this item to container and clear the <input> field
				$item.appendTo( $wrapper ).children( 'input:text' ).val( '' );
			},

			removeField: function ( e, $button ) {
				var self = this;

				e.preventDefault();

				// Find wrapper and groups to check if it possible to remove element
				var $wrapper = $button.parents( '.equip-socials-wrap' );
				var $groups = $wrapper.find( '.equip-socials-group' );
				// Find group which user want to remove..
				var $group = $button.parent( '.equip-socials-group' );
				if ( $groups.length > 1 ) {
					// ..and remove it
					$group.remove();
				} else {
					// Do not remove last element, just reset values
					var selectField = $group.find( 'select' );
					var firstOptionsValue = $group.find( 'select option:first' ).val();

					selectField.val( firstOptionsValue );
					$group.find( 'input' ).val( '' );
				}

				// re-gather values
				self.setValues( e, $wrapper );
			},

			setValues: function ( e, $wrapper ) {
				var $field = $wrapper.data( 'field' );
				var values = [];
				$wrapper.find( '.equip-socials-group' ).each( function ( i, el ) {
					var $el = $( el );
					var selectVal = $el.find( 'select' ).val();
					var inputVal = $el.find( 'input' ).val();

					if ( inputVal !== '' ) {
						values.push( selectVal + '|' + inputVal );
					}
				} );

				$field.val( values.join( ',' ) );
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

		$( document ).on( 'click', '.equip-socials-add', function ( e ) {
			$.fn.equipSocials.API.addFiled( e, $( this ) );
		} );

		$( document ).on( 'click', '.equip-socials-group-remove', function ( e ) {
			$.fn.equipSocials.API.removeField( e, $( this ) );
		} );

		$( document ).on( 'change keyup', '.equip-socials-wrap', function ( e ) {
			$.fn.equipSocials.API.setValues( e, $( this ) );
		} );

		$( document ).on( 'change', '.equip-select-wrap > select', function ( e ) {
			var $input = $( this ).parents( '.equip-select-wrap' ).siblings( 'input' );
			$input.focus();
		} );

		// auto-init the plugin
		$( function () {
			$( '.equip-socials' ).equipSocials( {} );
		} );

	}
)( jQuery, window, document );
