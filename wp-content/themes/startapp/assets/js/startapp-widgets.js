(function ( $ ) {
	'use strict';

	/* Repeated */

	$( document ).on( 'click', '.startapp-repeat', function ( e ) {
		e.preventDefault();

		var $this = $( this );
		// Detect the wrapper, based on clicked button
		// May be some buttons per one page
		var $wrapper = $this.siblings( '.startapp-repeated-fields-wrap' );
		// Clone first element
		var $item = $wrapper.find( '.startapp-repeated-group' ).first().clone();

		// Append this item to container
		$item.appendTo( $wrapper );

		// clear values from the cloned control
		$.each( $item.children( ':input:enabled' ), function ( index, field ) {
			var $field = $( field );
			var type = $field.prop( 'type' ).toLowerCase();
			switch ( type ) {
				case 'text':
				case 'textarea':
					$field.val( '' );
					break;

				case 'select-one':
				case 'select-multi':
					$field.prop( 'selectedIndex', 0 );
					break;

				default:
					break;
			}
		} );

	} );

	$( document ).on( 'click', '.startapp-unrepeat', function ( e ) {
		e.preventDefault();

		var $this = $( this );
		// Find wrapper and groups to check if it possible to remove element
		var $wrapper = $this.parents( '.startapp-repeated-fields-wrap' );
		var $groups = $wrapper.find( '.startapp-repeated-group' );
		// Find group which user want to remove..
		var $group = $this.parent( '.startapp-repeated-group' );
		if ( $groups.length > 1 ) {
			// ..and remove it
			$group.remove();
		} else {
			// clear values from the cloned control
			$.each( $group.find( ':input:enabled' ), function ( index, field ) {
				var $field = $( field );
				var type = $field.prop( 'type' ).toLowerCase();
				switch ( type ) {
					case 'text':
					case 'textarea':
						$field.val( '' );
						break;

					case 'select-one':
					case 'select-multi':
						$field.prop( 'selectedIndex', 0 );
						break;

					default:
						break;
				}
			} );
		}
	} );

	/* Media Library Popup */

	$( document ).on( 'click', '.startapp-media-add', function ( e ) {
		var $this = $( this );
		e.preventDefault();

		var $control = $this.parent('.startapp-media-control');
		var $wrapper = $control.parents('.startapp-media-wrap');
		var $value = $wrapper.find('.startapp-media-value');
		var $items = $wrapper.find('.startapp-media-items');

		// Media Library Configuration
		// Control for adding images is inside preview
		var params = $this.data(),
			values = $value.val(),
			is_multiple = Boolean( params.multiple || 0 );

		// Store globally
		var mediaFrame = wp.media( {
			title: params.title,
			multiple: is_multiple,
			button: { text: params.button }
		} );

		mediaFrame.on( 'select', function () {
			var IDs;
			var attachments = mediaFrame.state().get( 'selection' ).toJSON();

			if ( is_multiple ) {
				IDs = startappHandleMultipleImages( values, attachments, $control );
			} else {
				IDs = startappHandleSingleImage( attachments, $items );
			}

			// Add IDs to hidden field
			$value.val( IDs );

			// trigger the change event
			$value.trigger('change');
		} );

		mediaFrame.open();
	} );

	$( document ).on( 'click', '.startapp-media-remove', function ( e ) {
		e.preventDefault();
		var $this = $( this );

		var attachmentID = $this.parents( '.startapp-media-item' ).data( 'id' ).toString(),
			$li = $this.parent( 'li' ),
			$wrapper = $li.parents( '.startapp-media-wrap' ),
			$value = $wrapper.find( '.startapp-media-value' );

		// Delete <li>
		$li.remove();

		// Remove ID from hidden field
		// This should work normally both for single and multiple images
		var IDs = $value.val().split( ',' );
		var position = IDs.indexOf( attachmentID );
		if ( ~ position ) {
			IDs.splice( position, 1 );
		}

		// Save new value
		$value.val( IDs.join( ',' ) );

		// trigger the change event
		$value.trigger('change');
	} );

	/**
	 * Showing up multiple images
	 *
	 * @param {string} values List of previously selected IDs
	 * @param {object} attachments List of selected attachments by user as object.
	 * @param {jQuery} $control Control for collecting IDs
	 *
	 * @returns {string} List of attachment IDs, separated by comma.
	 */
	function startappHandleMultipleImages( values, attachments, $control ) {
		var IDs = values || [],
			previewHTML = '';

		if ( 'string' === typeof IDs ) {
			IDs = IDs.split( ',' );
		}

		// Prepare preview
		$.each( attachments, function ( key, attachment ) {
			IDs.push( attachment.id );
			previewHTML += startappPreparePreview(attachment);
		} );

		// Display added images right before the add control,
		// because add control is a <li>, too
		$control.before( previewHTML );

		return IDs.join( ',' );
	}

	/**
	 * Select a single image
	 *
	 * @param attachments
	 * @param {jQuery} $items
	 *
	 * @returns {string}
	 */
	function startappHandleSingleImage( attachments, $items ) {
		var IDs = [],
			previewHTML = '';

		// Prepare preview
		$.each( attachments, function ( key, attachment ) {
			IDs.push( attachment.id );
			previewHTML += startappPreparePreview(attachment);
		} );

		// Prepend new li before "add" controller or
		// replace the first <li> under the preview ul with a new one
		var LIs = $items.find( 'li' );
		if ( LIs.length > 1 ) {
			LIs.first().replaceWith( previewHTML );
		} else {
			$items.prepend( previewHTML );
		}

		return IDs.join( '' );
	}

	/**
	 * Prepare the single item preview HTML
	 *
	 * @param attachment
	 *
	 * @returns {string} Single item preview HTML
	 */
	function startappPreparePreview( attachment ) {
		//noinspection CssUnknownTarget
		var tpl = '<li class="{{item}}" data-id="{{id}}" style="background-image: url({{src}});">'
			+ '<a href="#" class="{{remove}}">&times;</a>'
			+ '</li>';

		return tpl
			.replace( '{{item}}', 'startapp-media-item' )
			.replace( '{{id}}', attachment.id )
			.replace( '{{src}}', attachment.url )
			.replace( '{{remove}}', 'startapp-media-remove' );
	}

	/**
	 * Init the sortable fields
	 *
	 * @param {jQuery} $sortable
	 */
	function startappSortableInit( $sortable ) {
		$sortable.sortable( {
			cursor: 'move',
			items: '> .startapp-media-item',
			placeholder: 'startapp-media-item-highlight'
		} ).disableSelection();
	}

	/**
	 * Init the sortable fields on page load
	 */
	startappSortableInit( $( '[data-sortable=true] .startapp-media-items' ) );

	$( document ).on( 'widget-added widget-updated', function ( e, widget ) {
		var base = widget.find( 'input[name="id_base"]' ).val();
		var $sortable = widget.find( '[data-sortable=true] .startapp-media-items' );
		if ( $sortable.length ) {
			startappSortableInit( $sortable );
		}
	} );

	/**
	 * Update the values when user sort the images
	 */
	$( document ).on( 'sortupdate', '[data-sortable=true] .startapp-media-items', function ( e, ui ) {
		var $this = $( this );
		var $value = $this.siblings( '.startapp-media-value' );
		var IDs = $this.sortable( 'toArray', { attribute: 'data-id' } );

		$value.val( IDs.join( ',' ) );
	} );


	/* Limit widgets in sidebars */

	/**
	 * Restrict the allowed widgets in provided sidebar
	 *
	 * @param {jQuery} widget jQuery object
	 * @param {String} sidebar Sidebar selector
	 * @param {Array} allowed Allowed widgets for provided sidebar
	 */
	function startappRestrictWidgets( widget, sidebar, allowed ) {
		var base = widget.find( 'input[name="id_base"]' ).val();

		if ( - 1 === $.inArray( base, allowed ) ) {
			var $sidebar = widget.parent( sidebar );

			$sidebar.addClass( 'widget-not-allowed' );
			setTimeout( function () {
				$sidebar.removeClass( 'widget-not-allowed' );
			}, 700 );

			widget.find( 'a.widget-control-remove' ).click();
		}
	}

	$( document ).on( 'widget-added', function ( e, widget ) {
		// Header Buttons sidebar
		if ( widget.parent( '#sidebar-header-buttons' ).length ) {
			startappRestrictWidgets( widget, '#sidebar-header-buttons', [ 'startapp_button' ] );
		}

		// Mega Menu sidebar
		if ( widget.parent( '[id^="mega-menu-"]' ).length ) {
			startappRestrictWidgets( widget, '[id^="mega-menu-"]', [
				'startapp_button',
				'categories',
				'pages',
				'nav_menu',
				'black-studio-tinymce'
			] );
		}
	} );

	
	/* Restrict widgets when user drag widget from another sidebar */

	$( document ).on( 'sortreceive', '#sidebar-header-buttons', function ( e, ui ) {
		if ( ui.helper !== null ) {
			return;
		}

		var widget = $( ui.item );
		startappRestrictWidgets( widget, '#sidebar-header-buttons', [ 'startapp_button' ] );
	} );

	$( document ).on( 'sortreceive', '[id^="mega-menu-"]', function ( e, ui ) {
		if ( ui.helper !== null ) {
			return;
		}

		var widget = $( ui.item );
		startappRestrictWidgets( widget, '[id^="mega-menu-"]', [
			'startapp_button',
			'categories',
			'pages',
			'nav_menu',
			'black-studio-tinymce'
		] );
	} );

})( jQuery );
