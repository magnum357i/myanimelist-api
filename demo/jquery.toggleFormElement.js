/**
 * jquery.toggleFormElement.js
 *
 * @version 	v1.00
 * @author 		Magnum357 (https://github.com/magnum357i)
 * @license 	MIT
 */

;( function ( $ ) {

	'use strict';

	var pluginName = 'toggleFormElement';

	function Plugin( element ) {

		this.$element = $( element );

		this.init();
	}

	Plugin.prototype = {

		init: function() {

			var self    = this;
			var tagName = self.$element.prop( 'tagName' );
			var target, group;

			if ( tagName == 'INPUT' && self.$element.attr( 'type' ) == 'radio' ) {

				target = self.getAttrOption( self.$element, 'target' );
				group  = self.getAttrOption( self.$element, 'groups' );

				if ( target != undefined && group != undefined ) {

					var items = target.split( ',' );

					for( var i = 0; i < items.length; i++ ) {

						self.hideElement( items[ i ], group );

						if ( self.$element.prop( 'checked' ) ) self.showElement( items[ i ], group );
					}
				}
				else {

					console.log( '[toggleFormElement] Missing parameters for radio.' );
				}

				self.$element.on( 'click', $.proxy( self.eClick, self ) );
			}
			else if ( tagName == 'SELECT' ) {

				if ( !self.$element.prop( 'multiple' ) ) {

					self.$element.find( 'option' ).each(

						function() {

							var option = $( this );

							target = self.getAttrOption( option, 'target' );
							group  = self.getAttrOption( option, 'groups' );

							if ( target != undefined && group != undefined ) {

								var items = target.split( ',' );

								for( var i = 0; i < items.length; i++ ) {

									self.hideElement( items[ i ], group );

									if ( option.prop( 'selected' ) ) self.showElement( items[ i ], group );
								}
							}
							else {

								console.log( '[toggleFormElement] Missing parameters for select box.' );
							}
						}
					);

					self.$element.on( 'change', $.proxy( self.eChange, self ) );
				}
				else {

					console.log( '[toggleFormElement] The format of this element is not support.' );
				}
			}
			else {

				console.log( '[toggleFormElement] This element is not support.' );
			}
		},
		getAttrOption: function( elem, optionName ) {

			return elem.attr( 'data-toggleformelement-' + optionName );
		},
		hideAllOpenedElements: function( group ) {

			var s = '[data-toggleformelement-group="' + group + '"]' + '[data-toggleformelement-status="open"]';

			$( s + '[required]' ).prop( 'required', false ).attr( 'data-required', '' );
			$( s + ' [required]' ).prop( 'required', false ).attr( 'data-required', '' );

			$( s ).attr( 'data-toggleformelement-status', 'closed' ).toggle();
		},
		hideElement: function( selector, group ) {

			var s = selector;

			$( s )
			.attr( 'data-toggleformelement-group', group )
			.attr( 'data-toggleformelement-status', 'closed' )
			.toggle();

			$( s + '[required]' ).prop( 'required', false ).attr( 'data-required', '' );
			$( s + ' [required]' ).prop( 'required', false ).attr( 'data-required', '' );
		},
		showElement: function( selector, group ) {

			var s = selector + '[data-toggleformelement-group="' + group + '"]' + '[data-toggleformelement-status="closed"]';

			$( s + '[data-required]' ).prop( 'required', true ).removeAttr( 'data-required' );
			$( s + ' [data-required]' ).prop( 'required', true ).removeAttr( 'data-required' );

			$( s ).attr( 'data-toggleformelement-status', 'open' ).toggle();
		},
		eClick: function() {

			var self   = this;
			var target = self.getAttrOption( self.$element, 'target' );
			var group  = self.getAttrOption( self.$element, 'groups' );

			if ( target != undefined && group != undefined ) {

				self.hideAllOpenedElements( group );

				var items = target.split( ',' );

				for( var i = 0; i < items.length; i++ ) {

					self.showElement( items[ i ], group );
				}
			}
		},
		eChange: function() {

			var self     = this;
			var multiple = self.$element.prop( 'multiple' );
			var option   = $( 'option:selected', self.$element );

			if ( !multiple ) {

				var target = self.getAttrOption( option, 'target' );
				var group  = self.getAttrOption( option, 'groups' );

				if ( target != undefined && group != undefined ) {

					self.hideAllOpenedElements( group );

					var items = target.split( ',' );

					for( var i = 0; i < items.length; i++ ) {

						self.showElement( items[ i ], group );
					}
				}
			}
		}
	};

	$.fn[ pluginName ] = function() {

		return this.each( function() {

			if ( !$.data( this, pluginName ) ) $.data( this, pluginName, new Plugin( this ) );
		} );
	};

} ) ( jQuery );