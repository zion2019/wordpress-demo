(function ($) {
    'use strict';

    $(document).ready(function () {

        /**
         * May be collect custom colors attached to .btn
         *
         * And push them to head in style tag
         *
         * @param {String} selector
         */
        function startappCollectCustomColor(selector) {
            var $elements = $( selector );
            if ( $elements.length === 0 ) {
                return false;
            }

            var style = [];

            $.each( $elements, function (i, element) {
                var $element = $( element );
                style.push( $element.data( 'custom-color' ) );
            });

            var css = style.join( "\n" );
            $('head').append('<style type="text/css" class="startapp-custom-colors-css">' + css + '</style>');
        }

        startappCollectCustomColor('.btn-custom');


        /**
        * Carousel Function
        * @param {String} selector
        * @param {Number} itemsLG
        * @param {Number} itemsMD
        * @param {Number} itemsSM
        * @param {Number} itemsXS
        */
        function contentCarousel( selector, itemsLG, itemsMD, itemsSM, itemsXS ) {
          var $element = $( selector );
          if ( $element.length === 0 ) {
              return false;
          }

          $( selector ).each( function() {
            var self = $( this );

            self.slick( {
              slidesToShow: itemsLG,
              responsive: [
                {
                  breakpoint: 1200,
                  settings: {
                    slidesToShow: itemsMD
                  }
                },
                {
                  breakpoint: 991,
                  settings: {
                    slidesToShow: itemsSM
                  }
                },
                {
                  breakpoint: 480,
                  settings: {
                    slidesToShow: itemsXS
                  }
                }
              ]
            } );
          } );
        }

        // Image Carousel
        contentCarousel( '.image-carousel', 1, 1, 1, 1 );

        // Timeline Carousel
        contentCarousel( '.timeline', 4, 3, 2, 1 );

        // Testimonials Slider
        contentCarousel( '.testimonials-slider', 1, 1, 1, 1 );

        // Testimonials Carousel
        $('.testimonials-carousel').each(function() {
          var self = $( this );
          var itemsLG = self.data('items-lg'),
              itemsMD = self.data('items-md'),
              itemsSM = self.data('items-sm'),
              itemsXS = self.data('items-xs');
          contentCarousel( self, itemsLG, itemsMD, itemsSM, itemsXS );
        });

        // Logo Carousel
        $('.logo-carousel').each(function() {
          var self = $( this );
          var itemsLG = self.data('items-lg'),
              itemsMD = self.data('items-md'),
              itemsSM = self.data('items-sm'),
              itemsXS = self.data('items-xs');
          contentCarousel( self, itemsLG, itemsMD, itemsSM, itemsXS );
        });


        /**
         * Video Popup
         */
         var $videoPopup = $( '.video-popup-btn' );
         if( $videoPopup.length ) {
           $videoPopup.magnificPopup( {
             type: 'iframe'
           } );
         }

        /**
         * Progress Bars on Scroll Animation
         * @param {String} items
         */
      	function pbOnScrollAnimation( items, trigger ) {
      		items.each( function() {
      			var pbElement = $(this),
      				curVal = pbElement.attr('data-current-value');

      			var pbTrigger = ( trigger ) ? trigger : pbElement;

      			pbTrigger.waypoint(function(direction) {
      				pbElement.find('.progress-bar > .bar').css({'width': curVal + '%'});
      				pbElement.find('.progress-bar > .value').addClass('is-visible').css({'width': curVal + '%'});
      			},{
      				offset: '88%'
      			});
            pbElement.find('.progress-bar > .value > i').counterUp({
              delay: 10,
              time: 1100
            });
      		});
      	}

      	pbOnScrollAnimation( $('.progress-animated') );

        /**
         * Counters (Animated Digits)
         * @param {String} items
         */
      	function counterOnScrollAnimation( items ) {
      	  items.each( function() {
      	    var counterElement = $(this);
            counterElement.find('.digit').counterUp({
              delay: 10,
              time: 1100
            });
      	  });
      	}
      	counterOnScrollAnimation( $('.animated-digit-box') );

        /**
         * Countdown
         * @param {String} items
         */
      	function countDownFunc( items, trigger ) {
      		items.each( function() {
      			var countDown = $(this),
      				dateTime = $(this).data('date-time');

      			var countDownTrigger = ( trigger ) ? trigger : countDown;
      			countDownTrigger.downCount({
      				date: dateTime
      			});
      		});
      	}

      	countDownFunc( $('.countdown') );

        // Google Maps API
        var $googleMap = $('.google-map');
        if ($googleMap.length > 0 && typeof $.fn.gmap3 === 'function') {
            $googleMap.each(function () {
                var mapHeight = $(this).data('height') || 500,
                    address = $(this).data('address') || '',
                    zoom = $(this).data('zoom') || 14,
                    controls = $(this).data('disable-controls'),
                    scrollwheel = $(this).data('scrollwheel'),
                    marker = $(this).data('marker') || '',
                    markerTitle = $(this).data('marker-title') || false,
                    styles = $(this).data('styles') || '';
                $(this).height(mapHeight);
                $(this).gmap3({
                    marker: {
                        address: address,
                        data: markerTitle,
                        options: {
                            icon: marker
                        },
                        events: {
                            mouseover: function (marker, event, context) {
                                if (typeof markerTitle !== 'undefined' || false !== markerTitle) {
                                    var map = $(this).gmap3("get"),
                                        infowindow = $(this).gmap3({get: {name: "infowindow"}});
                                    if (infowindow) {
                                        infowindow.open(map, marker);
                                        infowindow.setContent(context.data);
                                    } else {
                                        $(this).gmap3({
                                            infowindow: {
                                                anchor: marker,
                                                options: {content: context.data}
                                            }
                                        });
                                    }
                                }
                            },
                            mouseout: function () {
                                var infowindow = $(this).gmap3({get: {name: "infowindow"}});
                                if (infowindow) {
                                    infowindow.close();
                                }
                            }
                        }
                    },
                    map: {
                        options: {
                            zoom: zoom,
                            disableDefaultUI: controls,
                            scrollwheel: scrollwheel,
                            styles: styles
                        }
                    }
                });
            });
        }

		/**
		 * Gallery preview Popup (photoSwipe)
		 * @param {String} gallerySelector
		 */
		var $gallery = $( '.gallery-grid' );
		$gallery.each( function () {
			var $this = $( this );
			var getItems = function () {
				var items = [];
				$this.find( 'a' ).each( function () {
					var $href = $( this ).attr( 'href' ),
						$size = $( this ).data( 'size' ).split( 'x' ),
						$width = $size[ 0 ],
						$height = $size[ 1 ],
						$caption = $( this ).find( '.wp-caption-text' );

					var item = {
						src: $href,
						w: $width,
						h: $height,
						title: $caption.clone().children().remove().end().text()
					};
					items.push( item );
				} );
				return items;
			};

			var items = $this.find( '.grid-item' );
			var images = getItems();
			var $pswp = $( '.pswp' )[ 0 ];

			$this.on( 'click', '.grid-item', function ( e ) {
				e.preventDefault();

				var $index = $.inArray( this, items );
				var options = {
					index: $index,
					bgOpacity: 0.9,
					showHideOpacity: true,
					closeOnScroll: false
				};

				// Initialize PhotoSwipe
				var lightBox = new PhotoSwipe( $pswp, PhotoSwipeUI_Default, images, options );
				lightBox.init();
			} );

		} );

    }); // End document ready

	function startappCoreMoreFail( $el, xhr, status, error ) {
		console.log( [ 'startapp.ajax.error', status, error, xhr, xhr.responseText ] );
		$el.removeClass( 'data-loading' );
	}

	function startappCoreParsePosts( posts ) {
		var p = $.map( posts, function ( post, i ) {
			return $.trim( post );
		} );

		return $( p.join('') );
	}

	function startappCoreUpdateMoreButton( $button, total, page, perpage ) {
		total = parseInt( total, 10 );
		page = parseInt( page, 10 );
		perpage = parseInt( perpage, 10 );

		var num = total - (page * perpage );
		if ( num <= 0 || total <= perpage ) {
			$button.parent( '.pagination' ).hide();
			return false;
		}

		num = ( num > perpage ) ? perpage : num;

		// replace the counter with new value
		$button.find( '.load-more-counter' ).text( num );

		return true;
	}

	/** Blog Shortcode: Load More */

	$( document ).on( 'click', '.core-load-more-posts', function ( e ) {
		e.preventDefault();

		var $button = $( this ),
			query = $button.data( 'query' ),
			type = $button.data('type'),
			page = $button.data( 'page' ),
			total = $button.data('total'),
			perpage = $button.data( 'perpage' ),
			maxPages = $button.data( 'max-pages' );

		var formdata = {
			action: 'startapp_core_load_posts',
			nonce: startappCore.nonce,
			query: query,
			page: page,
			type: type
		};

		$button.parent( '.pagination' ).addClass( 'data-loading' );
		$.post( startappCore.ajaxurl, formdata ).fail( function ( xhr, status, error ) {
			startappCoreMoreFail( $button, xhr, status, error );
		} ).done( function ( response ) {
			$button.parent( '.pagination' ).removeClass( 'data-loading' );
			if ( false === response.success ) {
				alert( response.data );
				return false;
			}

			$button.data( 'page', page + 1 );

			var $posts = startappCoreParsePosts( response.data );
			var $container = $button.parent( '.pagination' ).siblings( '.blog-posts' );
			var isIsotope = type === 'grid';
			if ( isIsotope ) {
				$container.append( $posts ).isotope( 'appended', $posts );
				$container.imagesLoaded().progress( function () {
					$container.isotope( 'layout' );
				} );
			} else {
				$container.append( $posts );
			}

			startappCoreUpdateMoreButton( $button, total, page, perpage );
		} );
	} );

	/** Portfolio Shortcode: Load More */

	$( document ).on( 'click', '.portfolio-load-more-posts', function ( e ) {
		e.preventDefault();

		var $button = $(this);
		var query = $button.data('query');
		var page = parseInt($button.data('page'), 10);
		var maxPages = parseInt($button.data('max-pages'), 10);
		var total = parseInt($button.data('total'), 10);
		var perpage = parseInt($button.data('perpage'), 10);
		var type = $button.data('type');
		var args = $button.data('args');
		var gridID = $button.data('grid-id');

		var formdata = {
			action: 'startapp_portfolio_load_posts',
			nonce: startappCore.nonce,
			query: query,
			page: page,
			type: type,
			template_args: args
		};

		$button.parent( '.pagination' ).addClass( 'data-loading' );
		$.post( startappCore.ajaxurl, formdata ).fail( function ( xhr, status, error ) {
			startappCoreMoreFail( $button, xhr, status, error );
		} ).done( function ( response ) {
			$button.parent( '.pagination' ).removeClass( 'data-loading' );
			if ( false === response.success ) {
				alert( response.data );
				return false;
			}

			$button.data( 'page', page + 1 );

			var $posts = startappCoreParsePosts(response.data);
			var $container = $('#' + gridID);

			$container.append( $posts ).isotope( 'appended', $posts );
			$container.imagesLoaded().progress( function () {
				$container.isotope( 'layout' );
			} );

			startappCoreUpdateMoreButton( $button, total, page, perpage );
		} );
	} );

	/** Shortcode Products: Load More */

	$( document ).on( 'click', '.load-more-products', function ( e ) {
		e.preventDefault();

		var $button = $(this);
		var query = $button.data('query');
		var page = parseInt($button.data('page'), 10);
		var total = parseInt($button.data('total'), 10);
		var perpage = parseInt($button.data('perpage'), 10);
		var gridID = $button.data('grid-id');

		var formdata = {
			action: 'startapp_load_products',
			nonce: startappCore.nonce,
			query: query,
			page: page
		};

		$button.parent( '.pagination' ).addClass( 'data-loading' );
		$.post( startappCore.ajaxurl, formdata ).fail( function ( xhr, status, error ) {
			startappCoreMoreFail( $button, xhr, status, error );
		} ).done( function ( response ) {
			$button.parent( '.pagination' ).removeClass( 'data-loading' );
			if ( false === response.success ) {
				alert( response.data );
				return false;
			}

			$button.data( 'page', page + 1 );

			var $posts = startappCoreParsePosts(response.data);
			var $container = $('#' + gridID);

			$container.append( $posts ).isotope( 'appended', $posts );
			$container.imagesLoaded().progress( function () {
				$container.isotope( 'layout' );
			} );

			startappCoreUpdateMoreButton( $button, total, page, perpage );
		} );
	} );

	/** Infinite Scroll */

	$( window ).on( 'load', function () {

		/** Shortcode Blog: Infinite Scroll */

		var blogInfinite = $( '.core-infinite-scroll' );
		if ( blogInfinite.length > 0 ) {
			$( '.pagination' ).waypoint( function ( direction ) {
				if ( 'up' === direction ) {
					return false;
				}

				var page = parseInt( blogInfinite.data( 'page' ), 10 );
				var maxPages = parseInt( blogInfinite.data( 'max-pages' ), 10 );
				var type = blogInfinite.data( 'type' );
				var query = blogInfinite.data('query');

				// do not load posts, if no more pages
				if ( page > maxPages ) {
					return false;
				}

				var formdata = {
					action: 'startapp_core_load_posts',
					nonce: startappCore.nonce,
					query: query,
					page: page,
					type: type
				};

				// do not load posts, if no more pages
				if ( page > maxPages ) {
					blogInfinite.parent( '.pagination' ).hide();
					return false;
				}

				blogInfinite.parent( '.pagination' ).addClass( 'data-loading' );
				$.post( startappCore.ajaxurl, formdata ).fail( function ( xhr, status, error ) {
					startappCoreMoreFail( blogInfinite, xhr, status, error );
				} ).done( function ( response ) {
					blogInfinite.parent( '.pagination' ).removeClass( 'data-loading' );
					if ( false === response.success ) {
						alert( response.data );
						return;
					}

					blogInfinite.data( 'page', page + 1 );

					var $posts = startappCoreParsePosts( response.data );
					var $container = blogInfinite.parent( '.pagination' ).siblings( '.blog-posts' );
					var isIsotope = type === 'grid';
					if ( isIsotope ) {
						$container.append( $posts ).isotope( 'appended', $posts );
						$container.imagesLoaded().progress( function () {
							$container.isotope( 'layout' );
						} );
					} else {
						$container.append( $posts );
					}

					// refresh waypoint for allow further loading
					Waypoint.refreshAll();
				} );

			}, {
				offset: 'bottom-in-view'
			} );
		}

		/** Shortcode Portfolio: Infinite Scroll */

		var portfolioInfinite = $( '.portfolio-infinite-scroll' );
		if ( portfolioInfinite.length > 0 ) {
			$( '.pagination' ).waypoint( function ( direction ) {
				if ( 'up' === direction ) {
					return false;
				}

				var page = parseInt(portfolioInfinite.data('page'), 10);
				var maxPages = parseInt(portfolioInfinite.data('max-pages'), 10);
				var type = portfolioInfinite.data('type');
				var query = portfolioInfinite.data('query');
				var args = portfolioInfinite.data('args');
				var gridID = portfolioInfinite.data('grid-id');

				// do not load posts, if no more pages
				if ( page > maxPages ) {
					return false;
				}

				var formdata = {
					action: 'startapp_portfolio_load_posts',
					nonce: startappCore.nonce,
					query: query,
					page: page,
					type: type,
					template_args: args
				};

				// do not load posts, if no more pages
				if ( page > maxPages ) {
					portfolioInfinite.parent( '.pagination' ).hide();
					return false;
				}

				portfolioInfinite.parent( '.pagination' ).addClass( 'data-loading' );
				$.post( startappCore.ajaxurl, formdata ).fail( function ( xhr, status, error ) {
					startappCoreMoreFail( blogInfinite, xhr, status, error );
				} ).done( function ( response ) {
					portfolioInfinite.parent( '.pagination' ).removeClass( 'data-loading' );
					if ( false === response.success ) {
						alert( response.data );
						return;
					}

					portfolioInfinite.data( 'page', page + 1 );

					var $posts = startappCoreParsePosts(response.data);
					var $container = $('#' + gridID);

					$container.append( $posts ).isotope( 'appended', $posts );
					$container.imagesLoaded().progress( function () {
						$container.isotope( 'layout' );
					} );

					// refresh waypoint for allow further loading
					Waypoint.refreshAll();
				} );

			}, {
				offset: 'bottom-in-view'
			} );
		}

	} );

	/** Shortcode Portfolio: Isotope Filters */

	$( document ).on( 'click', '.nav-filters a', function ( e ) {
		e.preventDefault();

		// handle click on filter item,

		var $this = $( this );
		var filter = $this.data( 'filter' );
		var $parent = $this.parent( 'li' );
		var $filters = $this.parents( '.nav-filters' );
		var gridID = $filters.data( 'grid-id' );
		var $grid = $( '#' + gridID );

		if ( $parent.hasClass( 'active' ) ) {
			return false;
		}

		// add class .active for recently clicked item
		$filters.find( '.active' ).removeClass( 'active' );
		$parent.addClass( 'active' );

		// make option object dynamically, i.e. { filter: '.my-filter-class' }
		// and apply new options to isotope containers
		$grid.isotope( { filter: filter } );

		return true;
	} );

})(jQuery);
