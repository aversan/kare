var sendQueryTimer = null;
$.fn.keyupDelay = function( cb, delay ){
	if( delay == null ){
		delay = 1000;
	}
	var timer = 0;
	return $( this ).on( 'keyup', function( e ){
		clearTimeout( timer );
		timer = setTimeout( cb, delay );
	} );
};

function sendQuery( send_query, aid, delay, event ){
	if( typeof delay === "undefined" || delay === false ){
		delay = 1000;
	}

	if( typeof event === "undefined" || event === false ){
		event = false;
	}

	if( sendQueryTimer ){
		clearTimeout( sendQueryTimer );
	}
	sendQueryTimer = setTimeout( function(){ _sendQuery( send_query, aid, event ) }, delay );
}

function _sendQuery( send_query, aid, event ){
	if( typeof aid === "undefined" || aid === false ){
		aid = "catalog";
	}

	jsAjaxUtil.ShowLocalWaitWindow( 'id', aid, true );
	if( typeof send_query === 'undefined' || send_query == '' ){
		query = decodeURIComponent( $(".smartfilter").serialize() );
		
		
		console.log(query);
		
		if( query.length > 0 ){
			query = location.pathname + '?' + query + '&set_filter=Y';
		}else{
			query = location.pathname;
		}
	}else{
		query = decodeURIComponent( send_query );
	}
	
	
	
	$.ajax({
		url: query,
		type: "POST"
	}).done(function( html ){
		$( '#' + aid ).html( html );
		if( window.History.enabled || window.history.pushState != null ){
			window.History.pushState( null, document.title, query );
		}else{
			location.href = query;
		}

		if( event ){
			BX.onCustomEvent( event, [ html ] );
		}
		jsAjaxUtil.CloseLocalWaitWindow( 'id', aid );
		equalizeHeight($('.block_items .prod_item'), '.item-equalize');
		equalizeHeight($('.block_items .prod_item:not(.big_item)'), '.name');
		equalizeHeight($('.block_items .prod_item:not(.big_item)'), '.price_bl');
		$('.input-radio .input').on('click', function() {
			$(this).parents('.input-radio').find('label').trigger('click');
		});
		$('.input-checkbox span').on('click', function() {
			$(this).parents('.input-checkbox').find('label').trigger('click');
		});

		$('.category_list_prod .item-row .table_item.item').each(function(indx,element){
			if ($(this).find('.notefy').height() == 0) {
				$(this).find('.notefy').css('margin','0');
			}
		});
	});
}

$.fn.filterSlider = function(){
	if( !$( this ).length ){
		return this;
	}

	$( this ).each( function(){
		var _this = $( this );

		var slider = _this.find( '.slider' );
		var min = _this.find( '.js-min' );
		var max = _this.find( '.js-max' );

		var callback = _this.data( 'callback' );

		var abs_min_val = parseInt( _this.data( 'abs-min' ) );
		var abs_max_val = parseInt( _this.data( 'abs-max' ) );

		var cur_min_val = parseInt( _this.data( 'min' ) );
		cur_min_val = !cur_min_val ? abs_min_val : cur_min_val;
		var cur_min_virtual = cur_min_val;
		var cur_min_val_reinit = cur_min_val;

		var cur_max_val = parseInt( _this.data( 'max' ) );
		cur_max_val = !cur_max_val ? abs_max_val : cur_max_val;
		var cur_max_virtual = cur_max_val;
		var cur_max_val_reinit = cur_max_val;

		var step_count = parseInt( _this.data( 'step-count' ) );
		var step_val = 0;
		if( step_count ){
			step_val = ( abs_max_val - abs_min_val ) / step_count;

			// create pips
			_this.append( '<div class="pips"></div>' );
			var pips = _this.find('.pips');
			var delimeters = _this.data( 'delimeters' );
			delimeters = unserialize( delimeters );

			var virtual = [];
			var virtual_val = 0;

			for( var i = 0; i <= step_count; i++ ){
				var percent = parseInt( ( 100 / step_count ) ) * i;
				if( i == 0 ){
					pips.append('<div class="pip min">'+abs_min_val+'</div>');
				}else if( i == step_count ){
					pips.append('<div class="pip max">'+abs_max_val+'</div>');
				}else if( i == delimeters[0][0] ){
					pips.append('<div class="pip float center" style="left: '+percent+'%;">'+delimeters[0][1]+'</div>');
				}else{
					//pips.append('<div class="pip float" style="left: '+percent+'%;"></div>');
				}

				if( i == 0 ){
					virtual.push( [ abs_min_val, abs_min_val ] );
				}else if( i < delimeters[0][0] ){
					virtual_val = parseInt( ( delimeters[0][1] - abs_min_val ) / ( delimeters[0][0] ) );
					virtual_val *= i;
					virtual.push( [ step_val * i, virtual_val ] );
				}else if( i == delimeters[0][0] ){
					virtual.push( [ step_val * i, delimeters[0][1] ] );
				}else if( i == step_count ){
					virtual.push( [ abs_max_val, abs_max_val ] );
				}else{
					virtual_val = parseInt( ( abs_max_val - delimeters[0][1] ) / ( step_count - delimeters[0][0] ) );
					virtual_val = delimeters[0][1] + ( virtual_val * ( ( i - delimeters[0][0] ) + 1 ) );
					virtual.push( [ step_val * i, virtual_val ] );
				}
			}

			virtual.push( [ abs_max_val + 9999999999, abs_max_val + 9999999999 ] );

			// calc cur_min and cur_max
			var cur_min_inx = 0;
			var tmp_min_dist = 9999999999;
			var cur_max_inx = 0;
			var tmp_max_dist = 9999999999;

			for( var i = 0; i <= step_count; i++ ){
				var l1 = Math.abs( virtual[i][1] - cur_min_val );
				var l2 = Math.abs( virtual[i+1][1] - cur_min_val );
				if( l1 < l2 ){
					if( l1 <= tmp_min_dist ){
						tmp_min_dist = l1;
						cur_min_inx = i;
					}
				}else{
					if( l2 <= tmp_min_dist ){
						tmp_min_dist = l2;
						cur_min_inx = i;
					}
				}

				var k1 = Math.abs( virtual[i][1] - cur_max_val );
				var k2 = Math.abs( virtual[i+1][1] - cur_max_val );

				if( k1 < k2 ){
					if( k1 <= tmp_max_dist ){
						tmp_max_dist = k1;
						cur_max_inx = i;
					}
				}else{
					if( k2 <= tmp_max_dist ){
						tmp_max_dist = k2;
						cur_max_inx = i;
					}
				}
			}

			cur_min_val_reinit = virtual[cur_min_inx][0];
			cur_min_virtual = virtual[cur_min_inx][1];
			cur_max_val_reinit = virtual[cur_max_inx][0];
			cur_max_virtual = virtual[cur_max_inx][1];
		}else{
			step_val = parseInt( _this.data( 'step' ) );
			if( !step_val ){
				step_val = 10;
			}
		}

		var is_min = false;
		var is_max = false;

		var fixed_values = [];
		var slide_values = [];

		if( abs_min_val != cur_min_virtual ){
			min.val( cur_min_virtual );
		}else{
			min.attr( 'placeholder', abs_min_val ).attr( 'prename', min.attr( 'name' ) ).removeAttr( 'name' ).change();
		}

		if( abs_max_val != cur_max_virtual ){
			max.val( cur_max_virtual );
		}else{
			max.attr( 'placeholder', abs_max_val ).attr( 'prename', max.attr( 'name' ) ).removeAttr( 'name' ).change();
		}

		slider.slider( {
			min: abs_min_val,
			max: abs_max_val,
			values: [ cur_min_val_reinit, cur_max_val_reinit ],
			range: true,
			stop: function( event, ui ){
				/*if( !parseInt( slide_values[0] ) || !parseInt( slide_values[1] ) ){
					return false;
				}*/
				if( ( is_min && fixed_values[0] == slide_values[0] ) || ( is_max && fixed_values[1] == slide_values[1] ) ){
					return false;
				}

				is_min = false;
				is_max = false;

				if( slide_values[0] == abs_min_val && slide_values[1] == abs_max_val ){
					min.val( '' ).attr( 'prename', min.attr( 'name' ) ).removeAttr( 'name' ).change();
					max.val( '' ).attr( 'prename', max.attr( 'name' ) ).removeAttr( 'name' ).change();
				}else{
					min.val( slide_values[0] ).attr( 'name', min.attr( 'prename' ) ).removeAttr( 'prename' ).change();
					max.val( slide_values[1] ).attr( 'name', max.attr( 'prename' ) ).removeAttr( 'prename' ).change();
				}
				
				$(".smartfilter input[type='radio']").removeAttr( 'name' );

				if( ui.values[0] == ui.values[1] )
					return false;

				eval( callback );

				return true;
			},
			slide: function( event, ui ){
				if( parseInt( ui.values[0] + 6 ) > ui.values[1] )
					return false;

				slide_values = ui.values;
				
				if( step_count ){
					var cur_min_pos = Math.floor( ( slide_values[0] - abs_min_val ) / step_val );
					slide_values[0] = virtual[cur_min_pos][1];

					var cur_max_pos = Math.floor( ( slide_values[1] - abs_min_val ) / step_val );
					slide_values[1] = virtual[cur_max_pos][1];
				}

				min.val( slide_values[0] ).change();
				max.val( slide_values[1] ).change();

				return true;
			},
			change: function( event, ui ){
				if( parseInt( ui.values[0] + 6 ) > ui.values[1] )
					return false;

				slide_values = ui.values;
				
				if( slide_values[0] == abs_min_val && slide_values[1] == abs_max_val ){
					min.val( '' ).attr( 'prename', min.attr( 'name' ) ).removeAttr( 'name' ).change();
					max.val( '' ).attr( 'prename', max.attr( 'name' ) ).removeAttr( 'name' ).change();
				}else{
					min.val( slide_values[0] ).attr( 'name', min.attr( 'prename' ) ).removeAttr( 'prename' ).change();
					max.val( slide_values[1] ).attr( 'name', max.attr( 'prename' ) ).removeAttr( 'prename' ).change();
				}
				
				$(".smartfilter input[type='radio']").removeAttr( 'name' );

				if( ui.values[0] == ui.values[1] )
					return false;

				eval( callback );

				return true;
			},
			step: step_val
		} );

		slider.find( '.ui-slider-handle' ).on('mousedown', function(){
			if( $(this ).index() == 1 ){
				is_min = true;
			}else if( $(this ).index() == 2 ){
				is_max = true;
			}

			var vals = slider.slider( 'option', 'values' );
			fixed_values[0] = parseInt( vals[0] );
			fixed_values[1] = parseInt( vals[1] );
		});
		
		min.keypress( function( e ){
			var code = 0;
			if( e.keyCode ){
				code = e.keyCode;
			}else if( e.charCode ){
				code = e.charCode;
			}
			if( e.altKey || e.ctrlKey || e.metaKey || e.shiftKey ){
				return false;
			}
			if(
				( code >= 48 && code <= 57 ) || // numbers
				( code >= 37 && code <= 40 ) || // arrows
				code == 46 || // delete
				code == 8 || // backspace
				code == 9 //tab
			){
				return true;
			}else{
				return false;
			}
		});

		min.keyupDelay( function( e ){
			var ch_min_val = min.val();
			if( ch_min_val ){
				ch_min_val = parseInt( ch_min_val.replace( /\D+/g, '' ) );
			}else{
				ch_min_val = parseInt( min.attr('placeholder').replace( /\D+/g, '' ) );
			}
			var ch_max_val = max.val();
			if( ch_max_val ){
				ch_max_val = parseInt( ch_max_val.replace( /\D+/g, '' ) );
			}else{
				ch_max_val = parseInt( max.attr('placeholder').replace( /\D+/g, '' ) );
			}

			if( ch_min_val < abs_min_val ){
				ch_min_val = abs_min_val;
				min.val( abs_min_val );
			}

			if( ch_min_val > ch_max_val ){
				ch_min_val = ch_max_val;
				min.val( ch_min_val );
			}

			if( ch_min_val == abs_min_val && ch_max_val == abs_max_val ){
				min.val( '' ).attr( 'prename', min.attr( 'name' ) ).removeAttr( 'name' ).change();
				max.val( '' ).attr( 'prename', max.attr( 'name' ) ).removeAttr( 'name' ).change();
			}else{
				min.attr( 'name', min.attr( 'prename' ) ).removeAttr( 'prename' ).change();
				max.attr( 'name', max.attr( 'prename' ) ).removeAttr( 'prename' ).change();
			}
			
			$(".smartfilter input[type='radio']").removeAttr( 'name' );

			eval( callback );
			slider.slider( "values", 0, ch_min_val );
		}, 1000 );
		
		max.keypress( function( e ){
			var code = 0;
			if( e.keyCode ){
				code = e.keyCode;
			}else if( e.charCode ){
				code = e.charCode;
			}
			if( e.altKey || e.ctrlKey || e.metaKey || e.shiftKey ){
				return false;
			}
			if(
				( code >= 48 && code <= 57 ) || // numbers
				( code >= 37 && code <= 40 ) || // arrows
				code == 46 || // delete
				code == 8 || // backspace
				code == 9 //tab
			){
				return true;
			}else{
				return false;
			}
		});

		max.keyupDelay( function( e ){
			var ch_min_val = min.val();
			if( ch_min_val ){
				ch_min_val = parseInt( ch_min_val.replace( /\D+/g, '' ) );
			}else{
				ch_min_val = parseInt( min.attr('placeholder').replace( /\D+/g, '' ) );
			}
			var ch_max_val = max.val();
			if( ch_max_val ){
				ch_max_val = parseInt( ch_max_val.replace( /\D+/g, '' ) );
			}else{
				ch_max_val = parseInt( max.attr('placeholder').replace( /\D+/g, '' ) );
			}

			if( ch_max_val > abs_max_val ){
				ch_max_val = abs_max_val;
				max.val( abs_max_val );
			}

			if( ch_min_val > ch_max_val ){
				ch_max_val = ch_min_val;
				max.val( ch_max_val );
			}

			if( ch_min_val == abs_min_val && ch_max_val == abs_max_val ){
				min.val( '' ).attr( 'prename', min.attr( 'name' ) ).removeAttr( 'name' ).change();
				max.val( '' ).attr( 'prename', max.attr( 'name' ) ).removeAttr( 'name' ).change();
			}else{
				min.attr( 'name', min.attr( 'prename' ) ).removeAttr( 'prename' ).change();
				max.attr( 'name', max.attr( 'prename' ) ).removeAttr( 'prename' ).change();
			}
			
			$(".smartfilter input[type='radio']").removeAttr( 'name' );
			
			eval( callback );
			slider.slider( "values", 1, ch_max_val );
		}, 1000 );
	} );

	return this;
};