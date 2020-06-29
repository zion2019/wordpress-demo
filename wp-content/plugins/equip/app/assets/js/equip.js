/**
 * Equip
 *
 * @author 8guild
 */

(
	function ( $ ) {
		'use strict';

		/**
		 * Equip
		 *
		 * @constructor
		 */
		function Equip() {
			this.select.init();
			this.switch.init();
			this.slider.init();
			this.imageselect.init();
			this.stickyElement.init();
			this.tabbedNav.init();
			this.scrollSpy.init();
			this.required.init();
			this.master.init();
			this.options.init();
			this.codemirror.init();
		}

		/**
		 * Guild Utils Prototype
		 */
		Equip.prototype = {

			/**
			 * Select field
			 */
			select: {
				selector: '.equip-field .equip-combobox',

				init: function () {
					var self = this;

					if ( $( self.selector ).length === 0 ) {
						return false;
					}

					$( self.selector ).selectize();

					// if ( $( self.selector ).length > 0 && typeof $.fn.select2 === 'function' ) {
					// 	$( this.selector ).each( function () {
					// 		var $item = $( this ),
					// 			searchable = $item.data( 'searchable' ),
					// 			placeholder = ( $item.data( 'placeholder' ) === undefined ) ? '' : $item.data( 'placeholder' ),
					// 			isSearchable = ( searchable === false || searchable === undefined ) ? Infinity : 1;
					//
					// 		$item.select2( {
					// 			minimumResultsForSearch: isSearchable,
					// 			placeholder: placeholder,
					// 			containerCssClass: 'equip-select2-container',
					// 			dropdownCssClass: 'equip-select2-dropdown'
					// 		} );
					// 	} );
					// }
				}
			},

			/**
			 * Switch field
			 */
			switch: {
				selector: '.equip-field .equip-switch',

				init: function () {

					$( document ).on( 'click', this.selector, function () {
						if ( ! $( this ).hasClass( 'disabled' ) ) {

							var clicks = $( this ).data( 'clicks' ),
								inputVal = $( this ).find( 'input' ).attr( 'value' );

							if ( clicks && inputVal === '0' ) {
								$( this ).find( 'input' ).attr( 'value', '1' );
								$( this ).addClass( 'on' );
							} else if ( clicks && inputVal === '1' ) {
								$( this ).find( 'input' ).attr( 'value', '0' );
								$( this ).removeClass( 'on' );
							} else if ( ! clicks && inputVal === '0' ) {
								$( this ).find( 'input' ).attr( 'value', '1' );
								$( this ).addClass( 'on' );
							} else if ( ! clicks && inputVal === '1' ) {
								$( this ).find( 'input' ).attr( 'value', '0' );
								$( this ).removeClass( 'on' );
							}

							$( this ).data( 'clicks', ! clicks );

							$( this ).trigger( 'change' );
						}

					} );

				}
			},

			/**
			 * Slider
			 */
			slider: {
				sliderSelector: '.equip-slider',

				$sliders: [],

				init: function () {
					var self = this;

					self.$sliders = $( self.sliderSelector );

					if ( self.$sliders.length === 0 && typeof window.noUiSlider === "undefined" ) {
						return false;
					}

					for ( var i = 0; i < self.$sliders.length; i ++ ) {
						self.createSlider( i );
					}
				},

				createSlider: function ( index ) {
					var self = this;

					var minVal = parseInt( self.$sliders[index].dataset.min, 10 ),
						maxVal = parseInt( self.$sliders[index].dataset.max, 10 ),
						currentVal = parseInt( self.$sliders[index].dataset.current, 10 ),
						stepVal = parseInt( self.$sliders[index].dataset.step, 10 ),
						sliderID = self.$sliders[index].dataset.id;

					var slider = noUiSlider.create( self.$sliders[index], {
						start: currentVal,
						connect: "lower",
						step: stepVal,
						range: {
							'min': minVal,
							'max': maxVal
						},
						format: wNumb( {
							decimals: 0
						} )
					} );

					// add the slider object to the slider element
					// using data for future reference
					$( self.$sliders[index] ).data( 'slider', slider );

					// Update input value
					slider.on( 'update', function ( values, handle ) {
						$( '#' + sliderID ).val( values[handle] );
					} );

					// set the value and trigger change to call the "required" and "master" features
					slider.on( 'change', function ( values ) {
						$( '#' + sliderID ).trigger( 'change' );
					} );

					// Update slider position from input value
					$( '#' + sliderID ).on( 'change', function () {
						$( self.$sliders[index] ).data( 'slider' ).set( this.value );
					} );
				}
			},

			/**
			 * Image Select
			 */
			imageselect: {
				selector: '.equip-image-select',

				init: function () {

					var imageselect = this.selector;

					$( document ).on( 'click', imageselect, function () {
						var value = $( this ).data( 'value' );
						$( this ).parent().find( imageselect ).removeClass( 'active' );
						$( this ).addClass( 'active' );
						$( this ).parent().find( 'input[type=hidden]' ).val( value );

						// Trigger the "change" event to bubble up the dependencies
						$( this ).trigger( 'change' );
					} );
				}
			},

			/**
			 * Sticky Elements Footer + Side navigation
			 */
			stickyElement: {
				footerSelector: '.equip-footer',
				naviSelector: '.equip-navi',
				parentSelector: '.equip-page',

				init: function () {
					var self = this,
						resizeElement = document.querySelector( self.parentSelector );

					if ( $( self.parentSelector ).length === 0 ) {
						return false;
					}

					if ( $( self.footerSelector ).length > 0 && typeof $.fn.waypoint === 'function' ) {
						$( self.parentSelector ).waypoint( function ( direction ) {
							if ( direction === 'down' ) {
								$( self.footerSelector ).removeClass( 'stuck' );
							} else {
								$( self.footerSelector ).addClass( 'stuck' );
							}
						}, {offset: 'bottom-in-view'} );
					}

					var $navi = $( self.naviSelector );
					if ( $navi.length > 0 ) {
						var stickyNavi = new Waypoint.Sticky( {
							element: $navi[0]
						} );
					}

					self.footerWidth();
					addResizeListener( resizeElement, self.waypointRefresh );
				},

				footerWidth: function () {
					var $window = $( window ),
						$page = $( '.equip-page' ),
						$footer = $( this.footerSelector ).find( '.footer-inner' );

					$window.on( 'load', function () {
						$footer.width( $page.width() );
					} );
					$window.on( 'resize', function () {
						$footer.width( $page.width() );
					} );
				},

				waypointRefresh: function () {
					Waypoint.refreshAll();
				}
			},

			/**
			 * Tabbed Navigation
			 */
			tabbedNav: {
				selector: '.nav-tabs > li > a',

				init: function () {
					var self = this;
					var firstTab = $( $( self.selector ).parent()[0] ).find( 'a' ).attr( 'href' );
					//var lastTab = localStorage.getItem('lastTab') || firstTab;
					var lastTab = firstTab;
					$( '.tab-pane' ).removeClass( 'active in' );
					$( self.selector ).parent().removeClass( 'active' );
					$( self.selector + '[href="' + lastTab + '"]' ).parent().addClass( 'active' );
					$( lastTab ).addClass( 'active in' );
					$( self.selector ).on( 'click', function () {
						var tabID = $( this ).attr( 'href' );
						//localStorage.setItem('lastTab', tabID);
					} );
				}
			},

			/**
			 * Anchor Navigation + Scroll spy
			 */
			scrollSpy: {
				selector: '.scrollspy',

				init: function () {
					var self = this;
					$( self.selector ).scrollSpy();
				}
			},

			/**
			 * "required" fields
			 */
			required: {

				/**
				 * Dependent field selector
				 */
				dependentsSelector: '.equip-field[data-dependent="true"]',

				/**
				 * Field selector
				 */
				fieldSelector: '.equip-field[data-key="{{key}}"]',

				/**
				 * Element container selector
				 */
				containerSelector: '.equip-container',

				init: function () {
					var self = this;

					$( document ).on( 'change', self.containerSelector, function ( e ) {
						var $this = $( this );
						self.hookUp( e, $this );
					} );
				},

				/**
				 * Triggers when .equip-container is changed
				 *
				 * @param e
				 * @param $container
				 * @returns {boolean}
				 */
				hookUp: function ( e, $container ) {
					var self = this;

					// collect the dependent fields within current container
					var $fields = $container.find( self.dependentsSelector );
					if ( $fields.length === 0 ) {
						return false;
					}

					// collect master fields
					// and check if current e.target is a master field
					var $masters = self.getMasters( $fields, $container );
					var $target = $( e.target );
					var $master = $target.parents( '.equip-field' ); // master is a service wrapper
					if ( ! self.isMaster( $master, $masters ) ) {
						return false;
					}

					// collect all [dependent => [master]] relations
					// and dependent fields in appropriate format
					var $relations = self.getRelations( $fields );
					var $dependents = self.getDependents( $fields );

					// master field's data
					var master = $master.data( 'key' ); // master field key
					var value = self.getMasterValue( $master );

					// get dependents assigned to current $master
					var dependents = self.getDependent( $dependents, master );
					$.each( dependents, function ( i, item ) {
						// where
						// item is a dependent object from $dependents
						// dependent is a current dependent field key
						var dependent = item.key;
						var result = self.compare( value, item.operator, item.value );

						console.log( [
							'equip.compare.' + master + '.vs.' + dependent,
							value,
							item.operator,
							item.value,
							result,
							'outer',
							$container
						] );

						if ( result ) {
							// check if current item depends on other masters
							var relations = self.getRelation( $relations, dependent, master );
							if ( relations.length == 0 ) {
								// this item don't depends on other masters, just show it
								self.showDependent( item.field );
							} else {
								// var success will contain the result of comparisons with other masters
								// if at least one master fails item won't show up
								var success = false;
								$.each( relations, function ( i, m ) {
									// where
									// m is another master key
									// v is another master value
									// d is a dependent object of current item attached to another master
									// r is a result of comparison with other master
									var v = self.getMasterValue( m, $masters );
									var d = self.getDependent( $dependents, m, dependent );
									var r = self.compare( v, d.operator, d.value );

									console.log( [
										'equip.compare.' + m + '.vs.' + dependent,
										v,
										d.operator,
										d.value,
										r,
										'nested',
										$container
									] );

									if ( r ) {
										success = true;
									} else {
										success = false;

										// no need to check other masters
										// exit from $.each
										return false;
									}
								} );

								if ( success ) {
									self.showDependent( item.field );
								}
							}
						} else {
							self.hideDependent( item.field );
						}
					} );
				},

				/**
				 * Check if current target is a master field
				 *
				 * The problem is that target is a control inside the
				 * service wrapper .equip-field, which is required
				 *
				 * @param {jQuery} target Maybe a master
				 * @param {Array} masters
				 * @returns {boolean}
				 */
				isMaster: function ( target, masters ) {
					var result = $.grep( masters, function ( master ) {
						return target.is( master.object );
					} );

					return result.length > 0;
				},

				/**
				 * Parse masters collection and return specified master object
				 *
				 * @param master
				 * @param masters
				 * @returns {*|string|string}
				 */
				getMaster: function ( master, masters ) {
					var filtered = $.grep( masters, function ( m ) {
						return master === m.key;
					} );

					return filtered[0].object;
				},

				/**
				 * Collect master fields within current container
				 *
				 * Container is required to limit the search scope
				 * for master fields
				 *
				 * Return the array in format
				 * [ {key: "master", object: "jquery object"}, ...]
				 *
				 * @param {jQuery} fields
				 * @param {jQuery} $container
				 * @returns {Array}
				 */
				getMasters: function ( fields, $container ) {
					var self = this;

					var _masters = [];
					$.each( fields, function ( i, field ) {
						var $field = $( field );
						var required = $field.data( 'required' );

						if ( $.isArray( required[0] ) ) {
							// nested dependencies
							$.each( required, function ( index, nested ) {
								var master = nested[0];
								var selector = self.fieldSelector.replace( '{{key}}', master );

								_masters.push( {
									'key': master,
									'object': $container.find( selector )
								} );
							} );
						} else {
							var master = required[0]; // master key;
							var selector = self.fieldSelector.replace( '{{key}}', master );

							_masters.push( {
								'key': master,
								'object': $container.find( selector )
							} );
						}
					} );

					// filter masters for unique values
					var masters = [];
					var keys = [];
					$.map( _masters, function ( m, i ) {
						if ( $.inArray( m.key, keys ) == - 1 ) {
							masters.push( m );
							keys.push( m.key );
						}
					} );

					return masters;
				},

				/**
				 * Get all masters' keys, attached to a dependent field
				 * Specifying the second parameter you can remove this key from a result set
				 *
				 * @param {Array} relations An array of relations within current container
				 * @param {string} dependent
				 * @param {string} except
				 * @returns {*}
				 */
				getRelation: function ( relations, dependent, except ) {
					var self = this;

					relations = relations[dependent];

					if ( typeof except !== 'undefined' ) {
						relations = $.grep( relations, function ( r ) {
							return except != r;
						} );
					}

					return relations;
				},

				/**
				 * Collect relations [dependent => [masters]]
				 * within current element container
				 *
				 * @param {jQuery} $fields
				 * @returns {Array}
				 */
				getRelations: function ( $fields ) {
					var self = this;
					var relations = [];

					$.each( $fields, function ( i, field ) {
						var $field = $( field );
						var dependent = $field.data( 'key' ); // dependent key
						var required = $field.data( 'required' );

						if ( ! $.isArray( relations[dependent] ) ) {
							relations[dependent] = [];
						}

						if ( $.isArray( required[0] ) ) {
							// nested dependencies
							$.each( required, function ( index, nested ) {
								var master = nested[0];
								relations[dependent].push( master );
							} );
						} else {
							var master = required[0]; // master field key
							relations[dependent].push( master );
						}
					} );

					return relations;
				},

				/**
				 * Return a dependents object, attached to a master
				 *
				 * If second argument is specified function will return the single object
				 * of current dependent attached to a provided master
				 *
				 * @param {Array} dependents A list of all dependent fields within current container
				 * @param {String} master Master key
				 * @param exact Dependent field key
				 * @returns {*}
				 */
				getDependent: function ( dependents, master, exact ) {
					var self = this;

					if ( typeof exact !== 'undefined' ) {
						dependents = $.grep( dependents, function ( d ) {
							return master === d.master && exact === d.key;
						} );

						return dependents[0];
					}

					return $.grep( dependents, function ( d ) {
						return master == d.master;
					} );
				},

				/**
				 * Collect dependent fields within
				 * current element container
				 *
				 * @param {Array} fields
				 * @returns {Array}
				 */
				getDependents: function ( fields ) {
					var self = this;

					var dependents = [];
					$.each( fields, function ( i, field ) {
						var $field = $( field );
						var dependent = $field.data( 'key' ); // dependent key
						var required = $field.data( 'required' );

						if ( $.isArray( required[0] ) ) {
							// nested dependencies
							$.each( required, function ( index, nested ) {
								var master = nested[0];
								var operator = nested[1];
								var value = nested[2] || false;

								dependents.push( {
									field: $field,
									operator: operator,
									value: value,
									key: dependent,
									master: master
								} );

							} );
						} else {
							var master = required[0]; // master key
							var operator = required[1];
							var value = required[2];

							dependents.push( {
								field: $field,
								operator: operator,
								value: value,
								key: dependent,
								master: master
							} );
						}
					} );

					return dependents;
				},

				/**
				 * Returns the value of master field by provided master key
				 * or jQuery Object containing the master field
				 *
				 * @param {jQuery|string} master
				 * @param masters
				 * @returns {*}
				 */
				getMasterValue: function ( master, masters ) {
					var self = this;
					var $master, $control, value;

					if ( typeof master === 'string' && 'undefined' !== typeof masters ) {
						$master = self.getMaster( master, masters );
					} else if ( master instanceof $ ) {
						$master = master;
					} else {
						return false;
					}

					$control = $master.find( ':input:enabled' );
					value = $control.val();

					return value;
				},

				hideDependent: function ( $field ) {
					$field.addClass( 'hidden' );
				},

				showDependent: function ( $field ) {
					$field.removeClass( 'hidden' );
				},

				/**
				 * Test the dependent with master value by given operator
				 *
				 * @param masterValue
				 * @param operator
				 * @param compareValue
				 *
				 * @returns {boolean}
				 */
				compare: function ( masterValue, operator, compareValue ) {
					var result;

					switch ( operator ) {
						case '!=':
						case 'ne':
							result = (
								masterValue != compareValue
							);
							break;

						case '>':
						case 'gt':
						case 'greater':
							result = (
								masterValue > compareValue
							);
							break;

						case '>=':
						case 'ge':
						case 'greater_equal':
							result = (
								masterValue >= compareValue
							);
							break;

						case '<':
						case 'lt':
						case 'less':
							result = (
								masterValue < compareValue
							);
							break;

						case '<=':
						case 'le':
						case 'less_equal':
							result = (
								masterValue <= compareValue
							);
							break;

						case 'in_array':
							if ( $.isArray( compareValue ) ) {
								//noinspection JSDuplicatedDeclaration
								var converted = compareValue.map( function ( v ) {
									// convert all values to string
									return String( v );
								} );

								result = (
									$.inArray( masterValue, converted ) > - 1
								);
							} else {
								result = false;
							}

							break;

						case 'not_in_array':
							if ( $.isArray( compareValue ) ) {
								//noinspection JSDuplicatedDeclaration
								var converted = compareValue.map( function ( v ) {
									// convert all values to string
									return String( v );
								} );

								result = (
									$.inArray( masterValue, converted ) === - 1
								);
							} else {
								result = false;
							}
							break;

						case 'empty':
							result = (
								typeof masterValue === 'undefined'
								|| masterValue === ''
								|| masterValue === 0
								|| masterValue === "0"
								|| masterValue === null
								|| masterValue === false
							);
							break;

						case 'not_empty':
							result = (
								typeof masterValue !== 'undefined'
								&& masterValue !== ''
								&& masterValue !== 0
								&& masterValue !== "0"
								&& masterValue !== null
								&& masterValue !== false
							);

							break;

						case '=':
						case 'eq':
						case 'equal':
						case 'equals':
						default:
							result = (
								masterValue == compareValue
							);
							break;
					}

					return result;
				}
			},

			master: {
				containerSelector: '.equip-container',
				slaveSelector: '.equip-field[data-slave="true"]',
				fieldSelector: '.equip-field[data-key="{{key}}"]',

				init: function () {
					var self = this;

					$( document ).on( 'change', self.containerSelector, function ( e ) {
						var $this = $( this );
						self.hookUp( e, $this );
					} );
				},

				hookUp: function ( e, $container ) {
					var self = this;

					// collect the "slave" fields within current container
					var $fields = $container.find( self.slaveSelector );
					if ( $fields.length === 0 ) {
						return false;
					}

					// collect master fields
					// and check if current e.target is a master field
					var $masters = self.getMasterFields( $fields, $container );
					var $master = $( e.target ).parents( '.equip-field' ); // master is a service wrapper
					if ( ! self.isMaster( $master, $masters ) ) {
						return false; // do not proceed if currently changed field not master
					}

					// get master field value and key
					var value = self.getFieldValue( $master );
					var master = $master.data( 'key' );

					// iterate through the fields and change the values
					$.each( $fields, function ( i, field ) {
						var $field = $( field );
						var declaration = $field.data( 'master' );

						// skip fields attached to another master
						if ( master !== declaration[0] ) {
							return true; // skip to next iteration
						}

						var operator = declaration[1] || null;
						var argument = declaration[2] || null;
						var rounding = declaration[3] || 'none';

						if ( null !== operator && null !== argument ) {
							var computed = self.compute( Number( value ), operator, argument, rounding );
							self.setFieldValue( $field, computed );
						} else {
							self.setFieldValue( $field, value );
						}
					} );

					return true;
				},

				/**
				 * Compute the value. Make sense for numeric values.
				 *
				 * @param value The current value of the master field
				 * @param operator Operator
				 * @param argument Argument passed as a third parameter
				 * @param rounding Rounding direction. Supports "ceil" or "floor"
				 *
				 * @returns {int|float}
				 */
				compute: function ( value, operator, argument, rounding ) {
					switch ( operator ) {
						case '+':
							value = value + argument;
							break;

						case '-':
							value = value - argument;
							break;

						case '*':
							value = value * argument;
							break;

						case '/':
							value = value / argument;
							break;

						default:
							// do nothing.
							// don't know how to handle user-defined operators in JS?
							break;
					}

					if ( 'none' !== rounding ) {
						value = (
							'ceil' === rounding
						) ? Math.ceil( value ) : Math.floor( value );
					}


					return value;
				},

				/**
				 * Collect master fields within current container.
				 * Container is required to limit the search scope.
				 *
				 * Returns array in format [ master, master, ...]
				 *
				 * @param {jQuery} slaves
				 * @param {jQuery} $container
				 * @returns {Array}
				 */
				getMasterFields: function ( slaves, $container ) {
					var self = this;

					var _keys = [];
					var masters = [];
					var masterKeys = [];

					// first, get all master keys
					$.each( slaves, function ( i, slave ) {
						var $field = $( slave );
						var declaration = $field.data( 'master' );

						masterKeys.push( declaration[0] );
					} );

					// make sure we use the unique values
					$.map( masterKeys, function ( masterKey, i ) {
						if ( $.inArray( masterKey, _keys ) == - 1 ) {
							_keys.push( masterKey );

							var selector = self.fieldSelector.replace( '{{key}}', masterKey );
							var master = $container.find( selector );
							if ( master.length === 0 ) {
								return false;
							}

							masters.push( $( master ) );
						}
					} );

					return masters;
				},

				/**
				 * Check if current target is a master field
				 *
				 * @param {jQuery} target Maybe a master
				 * @param {Array} masters A list of all master objects
				 * @returns {boolean}
				 */
				isMaster: function ( target, masters ) {
					var result = $.grep( masters, function ( master ) {
						return target.is( master );
					} );

					return result.length > 0;
				},

				/**
				 * Returns the value of provided field
				 *
				 * NOTE: $field = .equip-field (service wrapper), not
				 * the control itself. So you have to find the control.
				 *
				 * @param {jQuery} $field
				 * @returns {*}
				 */
				getFieldValue: function ( $field ) {
					var self = this;
					var $control, value;

					$control = $field.find( ':input:enabled' );
					value = $control.val();

					return value;
				},

				/**
				 * Set a new value to given field
				 *
				 * NOTE: $field = .equip-field (service wrapper), not
				 * the control itself. So you have to find the control.
				 *
				 * @param {jQuery} $field
				 * @param {*} value
				 */
				setFieldValue: function ( $field, value ) {
					var self = this;
					var $control;

					$control = $field.find( ':input:enabled' );
					$control.val( value );

					// have to trigger the "change" to handle another events
					$control.trigger( 'change' );
				}
			},

			options: {

				/**
				 * Form selector for "Save Options"
				 */
				formSelector: '.equip-options-form',

				/**
				 * "Reset All" button selector
				 */
				resetAllSelector: '#equip-options-reset',

				/**
				 * "Reset Section" button selector
				 */
				resetSectionSelector: '#equip-options-reset-section',

				/**
				 * Toast defaults arguments
				 */
				toastDefaults: {
					loader: false,
					position: 'top-right'
				},

				init: function () {
					var self = this;

					$( document ).on( 'submit', self.formSelector, function ( e ) {
						var $form = $( this );
						self.save( e, $form );
					} );

					$( document ).on( 'click', self.resetAllSelector, function ( e ) {
						var $button = $( this );
						self.resetAll( e, $button );
					} );

					$( document ).on( 'click', self.resetSectionSelector, function ( e ) {
						var $button = $( this );
						self.resetSection( e, $button );
					} );
				},

				save: function ( e, $form ) {
					e.preventDefault();

					var self = this;
					var formdata = $form.serializeArray();
					var $submit = $form.find( ':submit' );
					var $loader = $submit.siblings( '.equip-loader' );

					// disable button until response
					$submit.prop( 'disabled', true );
					$loader.css( 'display', 'inline-block' );

					$.post( ajaxurl, formdata ).done( function ( response ) {

						// re-enable button
						$submit.prop( 'disabled', false );
						$loader.css( 'display', 'none' );

						if ( response.success ) {
							self.toast( {
								heading: equip.messages.optionsSaved,
								text: equip.messages.youAreAwesome,
								icon: 'success'
							} );
						} else {
							self.toast( {
								heading: equip.messages.error,
								text: response.data,
								icon: 'error'
							} );
						}
					} ).fail( self.fail );
				},

				resetAll: function ( e, $button ) {
					var self = this;

					e.preventDefault();
					var slug = $( '#equip-slug' ).val();
					var nonce = $( '#equip_reset_nonce' ).val();

					var formdata = {
						action: 'equip_reset_options',
						slug: slug,
						nonce: nonce,
						reset: 'all'
					};

					$.post( ajaxurl, formdata ).done( function ( response ) {
						if ( response.success ) {
							self.toast( {
								text: equip.messages.optionsReset,
								icon: 'warning'
							} );

							self.reload();
						} else {
							self.toast( {
								heading: equip.messages.error,
								text: response.data,
								icon: 'error'
							} );
						}
					} ).fail( self.fail );
				},

				resetSection: function ( e, $button ) {
					var self = this;

					e.preventDefault();
					var slug = $( '#equip-slug' ).val();
					var nonce = $( '#equip_reset_nonce' ).val();

					// find the active section and collect all field keys
					var keys = [];
					var active = $( '.active[data-element="section"]' );
					var fields = active.find( '[data-element="field"][data-key]' );
					fields.each( function ( i, field ) {
						keys.push( $( field ).data( 'key' ) );
					} );

					var formdata = {
						action: 'equip_reset_options',
						slug: slug,
						nonce: nonce,
						reset: 'section',
						keys: keys
					};

					$.post( ajaxurl, formdata ).done( function ( response ) {
						if ( response.success ) {
							self.toast( {
								text: equip.messages.sectionReset,
								icon: 'warning'
							} );

							self.reload();
						} else {
							self.toast( {
								heading: equip.messages.error,
								text: response.data,
								icon: 'error'
							} );
						}
					} ).fail( self.fail );
				},

				fail: function ( xhr, status, error ) {
					console.log( ['equip.options.error', status, error, xhr, xhr.responseText] );
					$.toast( {
						heading: equip.messages.error,
						text: equip.messages.fail,
						icon: 'error',
						loader: false,
						position: {top: 60, right: 30}
					} );
				},

				toast: function ( options ) {
					var self = this;
					var settings = $.extend( self.toastDefaults, options );

					$.toast( settings );
				},

				reload: function () {
					setTimeout( function () {
						location.reload();
					}, 1000 );
				}
			},

			codemirror: {

				selector: '.equip-field[data-field="custom_css"] textarea',

				init: function () {
					var self = this;
					var $editors = $( self.selector );

					$.each( $editors, function ( i, editor ) {
						var cm = CodeMirror.fromTextArea( editor, {
							mode: 'css',
							indentUnit: 2,
							indentWithTabs: true,
							tabSize: 2,
							autoCloseBrackets: true,
							matchBrackets: true,
							lineNumbers: true,
							extraKeys: {"Ctrl-Space": "autocomplete"}
						} );

						cm.refresh();
					} );
				}
			}
		};

		/**
		 * Initialize the Equip
		 */
		$( document ).ready( function () {
			// init the Equip
			new Equip();
		} );

	}
)( jQuery );
