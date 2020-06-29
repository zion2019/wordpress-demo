(function ($) {
    'use strict';

  $(document).ready(function () {

      // Check if Page Scrollbar is visible
      //------------------------------------------------------------------
      var hasScrollbar = function () {
          // The Modern solution
          if (typeof window.innerWidth === 'number') {
              return window.innerWidth > document.documentElement.clientWidth;
          }

          // rootElem for quirksmode
          var rootElem = document.documentElement || document.body;

          // Check overflow style property on body for fauxscrollbars
          var overflowStyle;

          if (typeof rootElem.currentStyle !== 'undefined') {
              overflowStyle = rootElem.currentStyle.overflow;
          }

          overflowStyle = overflowStyle || window.getComputedStyle(rootElem, '').overflow;

          // Also need to check the Y axis overflow
          var overflowYStyle;

          if (typeof rootElem.currentStyle !== 'undefined') {
              overflowYStyle = rootElem.currentStyle.overflowY;
          }

          overflowYStyle = overflowYStyle || window.getComputedStyle(rootElem, '').overflowY;

          var contentOverflows = rootElem.scrollHeight > rootElem.clientHeight;
          var overflowShown = /^(visible|auto)$/.test(overflowStyle) || /^(visible|auto)$/.test(overflowYStyle);
          var alwaysShowScroll = overflowStyle === 'scroll' || overflowYStyle === 'scroll';

          return (contentOverflows && overflowShown) || (alwaysShowScroll);
      };
      if (hasScrollbar()) {
          $('body').addClass('hasScrollbar');
      }

    // Disable default link behavior for dummy links that have href='#'
		var $emptyLink = $( 'a[href="#"]' );
		$emptyLink.on( 'click', function ( e ) {
			e.preventDefault();
		} );

    // Stuck Navbar on scroll
    //---------------------------------------------------------
    var $stickyNavbar = $('.navbar-sticky .navbar');
  	if($stickyNavbar.length) {
  		var sticky = new Waypoint.Sticky({
  		  element: $stickyNavbar[0]
  		});
  	}


    // Ghost navbar on scroll
    //---------------------------------------------------------
    var $ghostNavbar = $( '.navbar-ghost-dark .navbar, .navbar-ghost-light .navbar' );
    if( $ghostNavbar.length ) {
      var $ghostNavbarH = $ghostNavbar.outerHeight();
      $( window ).on( 'scroll', function() {
        if ( $( window ).scrollTop() > $ghostNavbarH ) {
          $ghostNavbar.addClass( 'in-view' );
        } else {
          $ghostNavbar.removeClass( 'in-view' );
        }
      } );
    }

    // Fullscreen Menu
    //---------------------------------------------------------
   	var $fsMenuToggle = $('[data-toggle="fullscreen"]'),
  			$fsMenu = $('.fs-menu-wrap'),
        $fsMenuTools = $('.fs-menu-wrap .tools');
    $fsMenuTools.innerHeight($('.fs-menu').innerHeight());
		$fsMenuToggle.on('click', function() {
      var self = $(this);
		  var clicks = self.data('clicks');
		  if (clicks) {
				$fsMenu.removeClass('is-visible');
				setTimeout(function(){
          body.removeClass( 'menu-open' );
          self.removeClass('active');
					$fsMenuToggle.parent().removeClass('expanded');
				}, 400);
		  } else {
        body.addClass( 'menu-open' );
        self.addClass('active');
				$fsMenuToggle.parent().addClass('expanded');
				$fsMenu.addClass('is-visible');
		  }
		  self.data("clicks", !clicks);
		});


    // Highlight Parent Mega Menu Item
    //---------------------------------------------------------
    var megaMenu = $('.mega-menu'),
        cuurentItem = $('.current_page_item, .current-menu-item, .current-cat');
    if(megaMenu.length) {
      cuurentItem.parents('.has-mega-menu').addClass('current-menu-item');
    }


    // Parallax Backgrounds
    //---------------------------------------------------------
    var bgParallax = $( '.bg-parallax' );

    // Detect IE Browser Version
    function detectIE() {
      var ua = window.navigator.userAgent;

      var msie = ua.indexOf('MSIE ');
      if (msie > 0) {
        // IE 10 or older => return version number
        return parseInt(ua.substring(msie + 5, ua.indexOf('.', msie)), 10);
      }

      var trident = ua.indexOf('Trident/');
      if (trident > 0) {
        // IE 11 => return version number
        var rv = ua.indexOf('rv:');
        return parseInt(ua.substring(rv + 3, ua.indexOf('.', rv)), 10);
      }

      var edge = ua.indexOf('Edge/');
      if (edge > 0) {
        // Edge (IE 12+) => return version number
        return parseInt(ua.substring(edge + 5, ua.indexOf('.', edge)), 10);
      }

      // other browser
      return false;
    }

    var ieVersion = detectIE();

    if( ( bgParallax.length > 0 && ieVersion === false ) || (  bgParallax.length > 0 && ieVersion >= 12 ) ) {
      bgParallax.each( function() {
        var speed = $( this ).data('parallax-speed'),
            type = $( this ).data('parallax-type');
        $( this ).jarallax( {
          speed: speed,
          type: type,
          zIndex: 0,
          noAndroid: true,
          noIos: true
        } );
      } );
    }

    // Wraps all select elements with div.form-select
    //---------------------------------------------------------
    var $select = $('select:not([multiple])');
    $select.wrap("<div class='form-select'></div>");


    // Image Carousel Widget
    //---------------------------------------------------------
    var carouselWidget = $( '.widget_startapp_image_carousel .widget-inner' );
    if ( carouselWidget.length > 0 ) {
      carouselWidget.each(function() {
        var prevBtn = $( this ).parent().find( '.slick-prev' ),
            nextBtn = $( this ).parent().find( '.slick-next' );
        $( this ).slick({
          prevArrow: prevBtn,
          nextArrow: nextBtn
        });
      });
    }


    // Call / Dismiss Functions (Backdrop, Off-Canvas Sidebar / Menu, Search Form )
    //-----------------------------------------------------------------------------
    var backdrop = $( '.site-backdrop' ),
        sidebar = $( '.off-canvas-sidebar' ),
        offcanvasMenu = $( '.off-canvas-menu' ),
        searchForm = $( '.site-search-form' ),
        searchBox = $( '.site-search-form .search-box input' ),
        closeSearch = $( '.site-search-form .close-btn' ),
        closeSidebar = $( '.off-canvas-sidebar .close-btn' ),
        closeOffcanvasMenu = $( '.off-canvas-menu .close-btn' ),
        body = $( 'body' );

    function callBackdrop() {
      backdrop.addClass( 'active' );
    }
    function callSearch() {
      searchForm.addClass( 'open' );
      setTimeout( function() {
        searchBox.focus();
      }, 400 );
    }
    function callSidebar() {
      sidebar.addClass( 'open' );
      body.addClass( 'sidebar-open' );
    }
    function callOffcanvasMenu() {
      offcanvasMenu.addClass( 'open' );
      body.addClass( 'menu-open' );
    }
    function dismissBackdrop() {
      backdrop.removeClass( 'active' );
    }
    function dismissSearch() {
      searchForm.removeClass( 'open' );
    }
    function dismissSidebar() {
      sidebar.removeClass( 'open' );
      body.removeClass( 'sidebar-open' );
    }
    function dismissOffcanvasMenu() {
      offcanvasMenu.removeClass( 'open' );
      body.removeClass( 'menu-open' );
    }


    // Tools Toggles
    //---------------------------------------------------------

    // Language Toggle
    var langBtn = $( '.site-header .lang-switcher' );
    langBtn.on( 'click', function() {
      $( this ).toggleClass( 'active' );
    } );

    // Topbar Toggle
    var topbarBtn = $( '.topbar-btn' ),
        topbar = $( '.site-header .topbar' );
    topbarBtn.on( 'click', function( e ) {
      $( this ).toggleClass( 'active' );
      topbar.toggleClass( 'open' );
      e.preventDefault();
    } );

    // Search Toggle
    var searchBtn = $( '.site-search-btn' );
    searchBtn.on( 'click', function( e ) {
      callSearch();
      callBackdrop();
      e.preventDefault();
    } );

    // Off-Canvas Sidebar Toggle
    var sidebarBtn = $( '.sidebar-btn' );
    sidebarBtn.on( 'click', function( e ) {
      callSidebar();
      callBackdrop();
      e.preventDefault();
    } );

    // Off-Canvas Menu Toggle
    var menuBtn = $( '[data-toggle="offcanvas"]' );
    menuBtn.on( 'click', function( e ) {
      callOffcanvasMenu();
      callBackdrop();
      e.preventDefault();
    } );

    // Topbar Menu Dropdown
    var topbarDropdown = $( '.topbar-menu > ul > li.menu-item-has-children > a ' );
    topbarDropdown.on( 'click', function( e ) {
      $( this ).parent().toggleClass( 'active' );
      e.preventDefault();
    } );

    // Dismiss All Overlay Objects
    backdrop.on( 'click', function() {
      dismissSearch();
      dismissSidebar();
      dismissOffcanvasMenu();
      dismissBackdrop();
    } );
    closeSidebar.on( 'click', function() {
      dismissSidebar();
      dismissBackdrop();
    } );
    closeOffcanvasMenu.on( 'click', function() {
      dismissOffcanvasMenu();
      dismissBackdrop();
    } );
    closeSearch.on( 'click', function() {
      dismissSearch();
      dismissBackdrop();
    } );


    // Vertical Navigation Toggle Submenu
  	//----------------------------------------------------
  	var $hasSubmenu = $( '.menu-item-has-children > a > .arrow, .has-mega-menu > a > .arrow' );
    $hasSubmenu.click( function( e ) {
  		if( $( this ).parent().parent().hasClass( 'expanded' ) ) {
  			$( this ).parent().parent().removeClass( 'expanded' );
  		} else {
  			$( this ).parent().parent().siblings( 'li' ).removeClass( 'expanded' );
  			$( this ).parent().parent().toggleClass( 'expanded' );
  		}
      e.preventDefault();
  	} );


    // Scroller Navigation
    //---------------------------------------------------------
    var $scrollerMenuItem = $( '.scroller-menu > ul > li' ),
        $scrollerMenulink = $( '.scroller-menu > ul > li > a' );
    $scrollerMenuItem.each( function() {
      var $menuItemLink = $(this).find('a'),
          $menuItemLinkText = $menuItemLink.text();
      $menuItemLink.append( '<span class="scroller-tooltip">' + $menuItemLinkText + '</span>' );
      console.log($menuItemLinkText);
    });

    // Scroll Spy
    $( '.fw-section' ).scrollSpy();

    // Smooth scroll to element
		$( document ).on( 'click', '.scroller-menu a, .scroll-to', function ( event ) {
			var target = $( this ).attr( 'href' );
			if ( '#' === target ) {
				return false;
			}

			var $target = $( target );
			if( $target.length > 0 ) {
				var $elemOffsetTop = $target.data( 'offset-top' ) || 180;
				$( 'html' ).velocity( "scroll", {
					offset: $( this.hash ).offset().top - $elemOffsetTop,
					duration: 1000,
					easing: 'easeOutExpo',
					mobileHA: false
				} );
			}
			event.preventDefault();
		} );

  }); // End document ready

  /* Close the off-canvas menu for anchored navigation after clicking the menu item. */
  $( document ).on( 'click', '.off-canvas-menu.open .scroll-to', function ( e ) {
    $( '.off-canvas-menu.open > .close-btn' ).click();
  } );

	/**
	 * Twitter share window
	 *
	 * @uses Twitter Web Intents
	 * @link https://dev.twitter.com/web/tweet-button/web-intent
	 */
	$( document ).on( 'click', '.startapp-share-twitter', function ( e ) {
		e.preventDefault();
		var self = $( this ),
			query = {
				text: self.data( 'text' ),
				url: self.data( 'url' )
			};

		var uri = $.param( query );
		window.open( 'http://twitter.com/intent/tweet?' + uri, 'twitter', 'menubar=no,toolbar=no,resizable=yes,scrollbars=no,status=0,location=0,height=380,width=660' );
	} );

	/**
	 * Facebook share
	 *
	 * @link https://developers.google.com/+/web/share/#sharelink
	 */
	$( document ).on( 'click', '.startapp-share-facebook', function ( e ) {
		e.preventDefault();
		var self = $( this ),
			query = { u: self.data( 'url' ) };

		var uri = $.param( query );
		window.open( 'https://www.facebook.com/sharer/sharer.php?' + uri, 'facebook', 'menubar=yes,toolbar=yes,resizable=yes,scrollbars=yes,height=600,width=600' );
	} );

	/**
	 * Google+ share
	 *
	 * @link https://developers.google.com/+/web/share/#sharelink
	 */
	$( document ).on( 'click', '.startapp-share-googleplus', function ( e ) {
		e.preventDefault();
		var self = $( this ),
			query = { url: self.data( 'url' ) };

		var uri = $.param( query );
		window.open( 'https://plus.google.com/share?' + uri, 'googleplus', 'menubar=no,toolbar=no,resizable=yes,scrollbars=no,height=600,width=600' );
	} );

	/**
	 * Pinterest share
	 */
	$( document ).on( 'click', '.startapp-share-pinterest', function ( e ) {
		e.preventDefault();
		var self = $( this ),
			query = {
				url: self.data( 'url' ),
				media: self.data( 'thumb' ),
				description: self.data( 'text' )
			};

		var uri = $.param( query );
		window.open( 'https://pinterest.com/pin/create/button/?' + uri, 'pinterest', 'menubar=no,toolbar=no,resizable=yes,scrollbars=no,height=600,width=600' );
	} );

  // Animated Scroll to Top Button
  //---------------------------------------------------------
  var $scrollTop = $( '.scroll-to-top-btn' );
  if ( $scrollTop.length > 0 ) {
    $( window ).on( 'scroll', function () {
      if ( $( window ).scrollTop() > 700 ) {
        $scrollTop.addClass( 'visible' );
      } else {
        $scrollTop.removeClass( 'visible' );
      }
    } );
    $scrollTop.on( 'click', function ( e ) {
      e.preventDefault();
      $( 'html' ).velocity( "scroll", { offset: 0, duration: 1100, easing: 'easeOutExpo', mobileHA: false } );
    } );
  }

    /* Load More Posts */

    /**
     * Convert single post HTML markup to jQuery Object
     *
     * @param {String} post
     */
    function startappParsePost(post) {
        var parsed = $.parseHTML(post);

        return parsed[0];
    }

	/**
	 * Convert all set of posts to jQuery Objects
	 *
	 * @param {Array} posts
	 * @returns {Array}
	 */
	function startappParsePosts(posts) {
    	var $posts = [];
	    $.each(posts, function (index, post) {
		    $posts.push(startappParsePost(post));
	    });

	    return $posts;
    }

	/**
	 * Change the state of "Load More" button due some conditions.
	 *
	 * Button will be hidden if there are no posts to load.
	 * Or just update the text on the button with new number of entries that have to be loaded.
	 * This function can be used with multiple buttons with same structure and logic.
	 *
	 * @param {jQuery} $button
	 * @param {int} total
	 * @param {int} page
	 * @param {int} perpage
	 *
	 * @returns {boolean}
	 */
	function startappUpdateMoreButton( $button, total, page, perpage ) {
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

	function startappMoreFail( $el, xhr, status, error ) {
		console.log( [ 'startapp.ajax.error', status, error, xhr, xhr.responseText ] );
		$el.parent( '.pagination' ).removeClass( 'data-loading' );
	}

	$( document ).on( 'click', '.load-more-posts', function ( e ) {
		e.preventDefault();

		var $button = $( this ),
			page = $button.data( 'page' ),
			type = $button.data( 'type' ),
			total = $button.data( 'total' ),
			perpage = $button.data( 'perpage' );

		var formdata = {
			action: 'startapp_load_posts',
			nonce: startapp.nonce,
			page: page,
			type: type
		};

		$button.parent( '.pagination' ).addClass( 'data-loading' );
		$.post( startapp.ajaxurl, formdata ).fail( function ( xhr, status, error ) {
			startappMoreFail( $button, xhr, status, error );
		} ).done( function ( response ) {
			$button.parent( '.pagination' ).removeClass( 'data-loading' );

			if ( false === response.success ) {
				alert( response.data );
				return false;
			}

			// update page
			$button.data( 'page', page + 1 );

			var $posts = startappParsePosts( response.data );
			startappUpdateMoreButton( $button, total, page, perpage );

			var isIsotope = (-1 !== $.inArray( type, [ 'grid-left', 'grid-right', 'grid-no' ] ) );
			if ( isIsotope ) {
				var $container = $button.parent( '.pagination' ).siblings( '.masonry-grid' );
				$container.append( $posts ).isotope( 'appended', $posts );
				$container.imagesLoaded().progress( function () {
					$container.isotope( 'layout' );
				} );
			} else {
				$button.parent( '.pagination' ).before( $posts );
			}
		} );
	} );


	/** Infinite Scroll & Isotope init */
  var $masonryGrid = $( '.masonry-grid' );

	$( window ).on( 'load', function () {
		if ( $masonryGrid.length > 0 ) {

			$masonryGrid.isotope( {
				itemSelector: '.grid-item',
				transitionDuration: '0.7s',
				masonry: {
					columnWidth: '.grid-sizer',
					gutter: '.gutter-sizer'
				}
			} );

			setTimeout( function () {
				$masonryGrid.isotope( 'layout' );
			}, 1 );

		}

		/* Blog Infinite Scroll */
		var $infinite = $( '.infinite-scroll' );
		if ( $infinite.length > 0 ) {
			$( '.pagination' ).waypoint( function ( direction ) {
				if ( 'up' === direction ) {
					return false;
				}

				var page = parseInt( $infinite.data( 'page' ), 10 );
				var maxPages = parseInt( $infinite.data( 'max-pages' ), 10 );
				var type = $infinite.data( 'type' );

				// do not load posts, if no more pages
				if ( page > maxPages ) {
					$infinite.parent( '.pagination' ).hide();
					return false;
				}

				var formdata = {
					action: 'startapp_load_posts',
					nonce: startapp.nonce,
					page: page,
					type: type
				};

				$infinite.parent( '.pagination' ).addClass( 'data-loading' );

				$.post( startapp.ajaxurl, formdata ).fail( function ( xhr, status, error ) {
					startappMoreFail( $button, xhr, status, error );
				} ).done( function ( response ) {
					$infinite.parent( '.pagination' ).removeClass( 'data-loading' );
					if ( false === response.success ) {
						alert( response.data );
						return;
					}

					// update page
					$infinite.data( 'page', page + 1 );

					var $posts = startappParsePosts( response.data );
					var isIsotope = (- 1 !== $.inArray( type, [ 'grid-left', 'grid-right', 'grid-no' ] ) );
					if ( isIsotope ) {
						var $container = $infinite.parent( '.pagination' ).siblings( '.masonry-grid' );
						$container.append( $posts ).isotope( 'appended', $posts );
						$container.imagesLoaded().progress( function () {
							$container.isotope( 'layout' );
						} );
					} else {
						$infinite.parent( '.pagination' ).before( $posts );
					}

					// refresh waypoint for allow further loading
					Waypoint.refreshAll();
				} );

			}, {
				offset: 'bottom-in-view'
			} );
		}

	} );

  // Re-build Masonry Grid on Window Resize
  if ( $masonryGrid.length > 0 ) {
    $( window ).on( 'resize', function () {
        $masonryGrid.isotope( 'layout' );
    } );
  }

})(jQuery);
