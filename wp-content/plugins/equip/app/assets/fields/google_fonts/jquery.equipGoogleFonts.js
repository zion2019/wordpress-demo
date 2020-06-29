;(
	function ( $, window, document ) {
		'use strict';

		/**
		 * Implement the Google Fonts library
		 *
		 * TODO: include-fonts
		 * TODO: exclude-fonts http://stackoverflow.com/questions/33232823/javascript-compare-two-objects-and-get-key-value-pair
		 * TODO: empty variants highlight?
		 * TODO: disabled button highlight
		 * TODO: form reload / refresh?
		 * TODO: maybe perform a filtr (search) refactoring
		 * TODO: optimize the google-fonts.json (a lot of unnecessary data)
		 *
		 * @author 8guild
		 * @package Equip
		 *
		 * @param {Object} options
		 */
		$.fn.equipGoogleFont = function ( options ) {
			return this.each( function () {
				var $button = $( this );
				var settings = $.extend( true, {}, $.fn.equipGoogleFont.defaults, $button.data(), options || {} );

				// connect the link and font-family fields to current button
				var $linkField = $button.siblings( '.equip-font-link' );
				var $ffField = $button.siblings( '.equip-font-family' );

				$button.data( 'link-field', $linkField ).data( 'ff-field', $ffField );
			} );
		};

		$.fn.equipGoogleFont.defaults = {
			showSearch: true,
			includeFonts: [],
			excludeFonts: [],
			googleFonts: ''
		};

		$.fn.equipGoogleFont.filtrOptions = {
			wait: 100,
			show: function ( $item ) {
				$item.removeClass( 'hide' );
			},
			hide: function ( $item ) {
				$item.addClass( 'hide' );
			},
			checkItem: function ( value, item ) {
				// original regexp pattern: value.split('').join('[\\w\\W]*');
				// caused too much memory and cpu usage
				return item.data.toLowerCase().search( value.toLowerCase() ) >= 0;
			}
		};

		$.fn.equipGoogleFont.API = {

			$popup: null,

			openPopup: function ( e, $button ) {
				e.preventDefault();

				var self = this;
				var settings = $.extend( true, {}, $.fn.equipGoogleFont.defaults, $button.data() );

				// markup for fonts library
				var $markup = $( '<div/>', {
					'class': 'equip-font-library',
					html: '<div class="equip-font-list"></div>' +
					      '<div class="equip-font-settings">' +
					      '<div class="equip-font-settings-header">' +
					      '<h3></h3>' +
					      '<a href="#" class="equip-font-live" target="_blank" tabindex="-1">Live Preview</a>' +
					      '</div>' +
					      '<form class="equip-font-generate" action>' +
					      '<input type="hidden" name="ff">' +
					      '<input type="hidden" name="category">' +
					      '<div class="equip-font-settings-scroll">' +
					      '<h4>Variants</h4>' +
					      '<fieldset></fieldset>' +
					      '<h4>Languages</h4>' +
					      '<fieldset></fieldset>' +
					      '</div>' +
					      '<div class="equip-font-settings-footer">' +
					      '<hr>' +
					      '<button type="submit" class="equip-btn btn-primary btn-block">Insert</button>' +
					      '</div>' +
					      '</form>' +
					      '</div>'
				} );

				// append a list of google fonts
				var xhr = $.getJSON( settings.googleFonts, function ( data ) {
					var t = '<li data-filtr="{{name}}">' +
					        '<a href="#" class="equip-font-select" ' +
					        'data-font-family="{{name}}" ' +
					        'data-category="{{category}}" ' +
					        'data-variants="{{variants}}" ' +
					        'data-subsets="{{subsets}}"' +
					        '>{{name}}</a>' +
					        '</li>';

					var items = [];
					$.each( data, function ( family, font ) {
						var variants = [];

						if ( font.variants ) {
							if ( font.variants.normal ) {
								$.each( font.variants.normal, function ( variant, v ) {
									variants.push( variant )
								} );
							}

							if ( font.variants.italic ) {
								$.each( font.variants.italic, function ( variant, v ) {
									variants.push( variant + 'i' );
								} );
							}
						}

						var item = self.prepareTemplate( t, {
							name: family,
							category: font.category,
							subsets: font.subsets.sort().join( ',' ),
							variants: variants.sort().join( ',' )
						} );

						items.push( item );
					} );

					$( '<ul/>', {
						'class': 'equip-fonts',
						html: items.join( '' )
					} ).appendTo( $markup.find( '.equip-font-list' ) );
				} );

				// maybe add a search
				$.when( xhr ).then( function () {
					if ( settings.showSearch ) {
						var $search = $( '<div/>', {
							'class': 'equip-font-search',
							html: '<input type="text" placeholder="Type to search..." autofocus>'
						} );

						var $filter = $search.find( 'input' );

						$filter.val( '' );
						$filter.filtr( $( '.equip-fonts>li' ), $.fn.equipGoogleFont.filtrOptions );
						$markup.find( '.equip-font-list' ).prepend( $search );
					}
				} );

				self.$popup = new $.Popup();
				self.$popup.open( $markup, 'html', $button );
			},

			closePopup: function () {
				var self = this;
				self.$popup.close();
			},

			/**
			 * Open the Customization panel
			 *
			 * @param e
			 * @param $font
			 */
			customizeFont: function ( e, $font ) {
				e.preventDefault();

				// clear active class
				$( '.equip-fonts>li' ).removeClass( 'active' );
				$font.parent( 'li' ).addClass( 'active' );

				var self = this;
				var t = '<label><input type="checkbox" name="{{name}}[]" value="{{value}}" {{checked}}> {{value}} </label>';
				var $settings = $( '.equip-font-settings' );
				var $wrapper = $( '.equip-font-library' );

				var fontFamily = $font.data( 'font-family' );
				var category = $font.data( 'category' );
				var _variants = String( $font.data( 'variants' ) );
				var _subsets = String( $font.data( 'subsets' ) );

				// add variants
				var variants = [];
				$.each( _variants.split( ',' ) || [], function ( i, variant ) {
					variants.push( self.prepareTemplate( t, {
						value: variant,
						name: 'variants',
						checked: (
							variant === '400'
						) ? 'checked' : ''
					} ) );
				} );

				// add subsets
				var subsets = [];
				$.each( _subsets.split( ',' ) || [], function ( i, subset ) {
					subsets.push( self.prepareTemplate( t, {
						value: subset,
						name: 'subsets',
						checked: (
							subset === 'latin'
						) ? 'checked disabled' : ''
					} ) );
				} );

				$settings.find( 'h3' ).text( fontFamily );
				$settings.find( '.equip-font-live' ).attr( 'href', 'https://fonts.google.com/specimen/' + fontFamily.replace( / /g, '+' ) );
				$settings.find( 'input[name="ff"]' ).val( fontFamily );
				$settings.find( 'input[name="category"]' ).val( category );
				$settings.find( 'fieldset' ).first().html( variants );
				$settings.find( 'fieldset' ).last().html( subsets );
				$wrapper.addClass( 'settings-open' );
			},

			/**
			 * Generate the Google Fonts link and font-family for selected font
			 *
			 * @param e
			 * @param $form
			 */
			insertFont: function ( e, $form ) {
				e.preventDefault();

				var self = this;
				var $button = self.$popup.ele;
				var baseURL = '//fonts.googleapis.com/css?family=';
				var $linkField = $button.data( 'link-field' );
				var $ffField = $button.data( 'ff-field' );

				var fontFamily = $form.find( 'input[name="ff"]' ).val();
				var category = $form.find( 'input[name="category"]' ).val();

				// replace the "display" and "handwriting" category with "cursive"
				if ( category === 'display' || category === 'handwriting' ) {
					category = 'cursive';
				}

				var variants = [];
				$form.find( 'input[name^="variants"]:checked' ).each( function () {
					variants.push( String( $( this ).val() ) );
				} );

				var subsets = [];
				$form.find( 'input[name^="subsets"]:checked' ).each( function () {
					subsets.push( String( $( this ).val() ) );
				} );

				// build a URL
				var URL = baseURL + fontFamily.replace( / /g, '+' );

				// add variants if user select more than only 400
				// Google Fonts ignores the only ":400" variant
				if ( variants.length > 0 ) {
					if ( variants.length === 1 && variants[0] === '400' ) {
						// do nothing
					} else {
						URL += ':' + variants.join( ',' );
					}
				}

				// add subsets, if user select more than one
				// do not add "latin" in the set, it is supported by all font by default
				if ( subsets.length > 1 ) {
					var i = subsets.indexOf( 'latin' );
					subsets.splice( i, 1 );
					URL += '&subset=' + subsets.join( ',' );
				}

				// set values and close popup
				$linkField.val( URL );
				$ffField.val( '"' + fontFamily + '", ' + category );
				self.closePopup();
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

		$( document ).on( 'click', '.equip-font-button', function ( e ) {
			$.fn.equipGoogleFont.API.openPopup( e, $( this ) );
		} );

		$( document ).on( 'click', '.equip-font-select', function ( e ) {
			$.fn.equipGoogleFont.API.customizeFont( e, $( this ) );
		} );

		$( document ).on( 'submit', '.equip-font-generate', function ( e ) {
			$.fn.equipGoogleFont.API.insertFont( e, $( this ) );
		} );

		// do not allow empty variants
		$( document ).on( 'change', '.equip-font-generate fieldset:first', function ( e ) {
			var $fieldset = $( this );
			var $form = $fieldset.parents( 'form' );
			var checkedVariants = $fieldset.find( 'input[name^="variants"]:checked' );

			if ( checkedVariants.length === 0 ) {
				$form.find( 'button[type="submit"]' ).prop( 'disabled', true );
			} else {
				$form.find( 'button[type="submit"]' ).prop( 'disabled', false );
			}
		} );

		// close popup on esc key
		$( document ).on( 'keyup', function ( e ) {
			if ( e.keyCode === 27 && $.fn.equipGoogleFont.API.$popup ) {
				$.fn.equipGoogleFont.API.$popup.close();
				$.fn.equipGoogleFont.API.$popup = null;
			}
		} );

		// auto-init the plugin
		$( function () {
			$( '.equip-font-button' ).equipGoogleFont( {} );
		} );

	}
)( jQuery, window, document );
