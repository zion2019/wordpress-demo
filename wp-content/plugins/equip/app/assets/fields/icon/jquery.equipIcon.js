;(
	function ( $, window, document ) {
		'use strict';

		$.fn.equipIcon = function ( options ) {
			var API = $.fn.equipIcon.API;

			return this.each( function () {
				var $field = $( this );
				var settings = $.extend( true, {}, $.fn.equipIcon.defaults, $field.data(), options || {} );

				API.init( $field, $field.val(), settings );
			} );
		};

		$.fn.equipIcon.defaults = {
			showSearch: true,
			source: [],
			selectedSource: null,
			excludeSource: [],
			chooseText: 'Choose'
		};

		$.fn.equipIcon.filtrOptions = {
			wait: 50,
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

		$.fn.equipIcon.API = {

			$popup: null,

			/**
			 * Init the Icon Field
			 *
			 * Wrap the field with required markup, add a preview
			 * and icon library button.
			 *
			 * @param $field
			 * @param value
			 * @param settings
			 */
			init: function ( $field, value, settings ) {
				$field.wrap( '<div class="equip-icon-select"></div>' );
				$( '<i></i>' ).attr( 'class', value ).insertBefore( $field );
				$( '<a href="#" class="equip-icon-button" tabindex="-1"></a>' )
					.text( settings.chooseText )
					.data( 'field', $field ) // save field for further usage
					.insertAfter( $field );
			},

			callPopup: function ( e, $button ) {
				e.preventDefault();

				var self = this;
				var $field = $button.data( 'field' );
				var settings = $.extend( true, {}, $.fn.equipIcon.defaults, $field.data() );
				var markup = self.getPopupMarkup( settings, $button.data( 'field' ) );

				self.$popup = new $.Popup();
				self.$popup.open( markup, 'html' );
				self.updateSearch( settings );
			},

			/**
			 * Update icons list on "source" link click
			 *
			 * @param e
			 * @param $this
			 */
			changeIcons: function ( e, $this ) {
				e.preventDefault();

				var self = this;
				var source = $this.data( 'source' );
				var $container = $this.parents( '.equip-icon-library' );
				var $field = $container.data( 'field' );

				// get field settings
				var settings = $.extend( true, {}, $.fn.equipIcon.defaults, $field.data() );

				var icons = self.getIcons( source, '', settings );
				$container.find( '.equip-icons' ).html( '' ).append( icons );

				// reset active class
				$container.find( '.equip-icon-source-list li' ).removeClass( 'active' );
				$this.parent( 'li' ).addClass( 'active' );

				// reset the filtration
				self.updateSearch( settings );
			},

			// set the icons into the field
			setIcon: function ( e, $this ) {
				e.preventDefault();

				var self = this;
				var icon = $this.data( 'icon' );
				var $container = $this.parents( '.equip-icon-library' );
				var $field = $container.data( 'field' );

				$field.val( icon );
				$field.trigger( 'change' );

				// update the related field with a new source
				$field.data( 'selected-source', $container.find( '.equip-icon-source-list li.active a' ).data( 'source' ) );

				self.$popup.close();
			},

			/**
			 * Initialize a filtr plugin
			 *
			 * @param {Object} settings
			 */
			updateSearch: function ( settings ) {
				if ( settings.showSearch ) {
					var $filter = $( '.equip-icon-search>input' );

					$filter.val( '' );
					$filter.filtr( $( '.equip-icons>li' ), $.fn.equipIcon.filtrOptions );
				}
			},

			getPopupMarkup: function ( settings, $field ) {
				var self = this;

				var source;
				for ( source in settings.source ) {
					break;
				} // hack to get first key from object
				source = settings.selectedSource || source;

				var $markup = $( '<div class="equip-icon-library"></div>' );

				$markup.data( 'field', $field ); // connect current popup with certain field
				$markup.append( self.getPopupSourceSection( source, settings ) );
				$markup.append( self.getPopupIconsSection( source, $field.val(), settings ) );

				return $markup;
			},

			/**
			 * Prepare the HTML markup for Source Section
			 *
			 * @param {String} selectedSource
			 * @param {Object} settings
			 * @returns {void|*}
			 */
			getPopupSourceSection: function ( selectedSource, settings ) {
				var self = this;

				var $section = $( '<div class="equip-icon-source-list"></div>' );
				var t = '<li><a href="#" class="equip-icon-source" data-source="{{source}}">{{name}}</a></li>';
				var sourceList = [];
				$.each( settings.source, function ( k, n ) {
					// exclude items
					if ( $.inArray( k, settings.excludeSource ) !== - 1 ) {
						return true;
					}

					var $item = $( self.prepareTemplate( t, {source: k, name: n} ) );
					if ( selectedSource === k ) {
						$item.addClass( 'active' );
					}

					sourceList.push( $item );
				} );

				var $sources = $( '<ul></ul>' ).append( sourceList );

				return $section.append( $sources );
			},

			/**
			 * Prepare the HTML markup for Icons Section (including search field)
			 *
			 * @param {String} selectedSource
			 * @param {String} currentIcon
			 * @param {Object} settings
			 * @returns {*|HTMLElement}
			 */
			getPopupIconsSection: function ( selectedSource, currentIcon, settings ) {
				var self = this;
				var $section = $( '<div class="equip-icon-list"></div>' );

				var icons = self.getIcons( selectedSource, currentIcon, settings );
				var $icons = $( '<ul class="equip-icons"></ul>' ).append( icons );

				// enable or disable filtration, based on field settings
				if ( settings.showSearch ) {
					var $filter = $( '<div class="equip-icon-search"><input type="text" placeholder="Type to search icon"></div>' );
					$section.append( $filter );
				}

				return $section.append( $icons );
			},

			/**
			 * Prepare the icons list markup
			 *
			 * @param source
			 * @param currentIcon
			 * @param settings
			 * @returns {Array}
			 */
			getIcons: function ( source, currentIcon, settings ) {
				var self = this;

				var rawIcons = self.getIconsRaw( source );
				var t = [
					'<li data-filtr="{{icon}}">',
					'<a href="#" class="equip-set-icon" data-icon="{{icon}}" title="{{icon}}">',
					'<i class="{{icon}}"></i>',
					'</a>',
					'</li>'
				].join( '' );

				// TODO: case when user specify {icon:name}. Use "name" for filtration
				var iconsList = [];
				$.each( rawIcons, function ( i, icon ) {
					var $item = $( self.prepareTemplate( t, {icon: icon} ) );
					if ( '' !== currentIcon && currentIcon === icon ) {
						$item.addClass( 'active' );
					}

					iconsList.push( $item );
				} );

				return iconsList;
			},

			getIconsRaw: function ( source ) {
				return window['equip_icons_' + source] || [];
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

		$( document ).on( 'click', '.equip-icon-button', function ( e ) {
			$.fn.equipIcon.API.callPopup( e, $( this ) );
		} );

		$( document ).on( 'click', '.equip-icon-source', function ( e ) {
			$.fn.equipIcon.API.changeIcons( e, $( this ) );
		} );

		$( document ).on( 'click', '.equip-set-icon', function ( e ) {
			$.fn.equipIcon.API.setIcon( e, $( this ) );
		} );

		// close popup on esc key
		$( document ).on( 'keyup', function ( e ) {
			if ( e.keyCode === 27 && $.fn.equipIcon.API.$popup ) {
				$.fn.equipIcon.API.$popup.close();
				$.fn.equipIcon.API.$popup = null;
			}
		} );

		// update preview
		$( document ).on( 'blur change', '.equip-icon', function ( e ) {
			var $field = $( this );
			$field.siblings( 'i' ).attr( 'class', $field.val() );
		} );

		// auto-init the plugin
		$( function () {
			$( '.equip-icon' ).equipIcon();
		} );

	}
)( jQuery, window, document );