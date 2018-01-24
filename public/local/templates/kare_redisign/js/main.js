// ASPRO.KSHOP JavaScript API v.1.0.0

$.expr[':'].findContent = function(obj, index, meta){
	var matchParams = meta[3].split(',');
	regexFlags = 'ig';
	regex = new RegExp('^' + $.trim(matchParams) + '$', regexFlags);
	return regex.test($(obj).text());
};

$.fn.equalizeHeights = function(){
	var maxHeight = this.map(function(i, e) {
		return $(e).height();
	}).get();
	return this.height(Math.max.apply(this, maxHeight));
};

(function($) {
    $.fn.animateNumbers = function(stop, duration, formatPrice, start, ease, callback){
        return this.each(function() {
            var $this = $(this);
            var start = (start === undefined) ? parseInt(delSpaces($this.text()).replace(/,/g, "")) : start;
			formatPrice = (formatPrice === undefined) ? false : formatPrice;
            $({value: start}).animate({value: stop}, {
            	duration: duration == undefined ? 1000 : duration,
            	easing: ease == undefined ? 'swing' : ease,
            	step: function(){
					if(formatPrice){
						$this.html(jsPriceFormat(Math.floor(this.value)));
					}
					else{
						$this.text(Math.floor(this.value));
					}
            	},
            	complete: function(){
					if(parseInt(delSpaces($this.text())) !== stop){
						if(formatPrice){
							$this.html(jsPriceFormat(stop));
						}
						else{
							$this.text(stop);
						}
					}
					if(typeof callback == 'function'){
						callback();
					}
            	}
            });
        });
    };
})(jQuery);

var isFunction = function(func){
	if(typeof func == 'function'){
		return true;
	}
	else{
		return false;
	}
}

if(!isFunction('fRand')){
	var fRand = function(){
		return Math.floor(arguments.length > 1 ? (999999 - 0 + 1) * Math.random() + 0 : (0 + 1) * Math.random());
	}
}

if(!isFunction('delSpaces')){
	var delSpaces = function delSpaces(str){
		str = str.replace(/\s/g, '');
		return str;
	}
}

if(!isFunction('waitForFinalEvent')){
	var waitForFinalEvent = (function(){
		var timers = {};
		return function(callback, ms, uniqueId){
			if(!uniqueId){
				uniqueId = fRand();
			}
			if(timers[uniqueId]) {
				clearTimeout(timers[uniqueId]);
			}
			timers[uniqueId] = setTimeout(callback, ms);
		};
	})();
}

if(!isFunction('onLoadjqm')){
	var onLoadjqm = function(name, hash, requestData, selector){
		hash.w.addClass('show').css({
			'margin-left': ($(window).width() > hash.w.outerWidth() ? '-' + hash.w.outerWidth() / 2 + 'px' : '-' + $(window).width() / 2 + 'px'),
			'top': $(document).scrollTop() + (($(window).height() > hash.w.outerHeight() ? ($(window).height() - hash.w.outerHeight()) / 2 : 10))   + 'px'
		});


		if(typeof(requestData) == 'undefined'){
			requestData = '';
		}
		if(typeof(selector) == 'undefined'){
			selector = false;
		}
		var width = $('.'+name+'_frame').width();
		$('.'+name+'_frame').css('margin-left', '-'+width/2+'px');

		if(name=='order-popup-call')
		{
		}
		else if(name=='order-button')
		{
			$(".order-button_frame").find("div[product_name]").find("input").val(hash.t.title).attr("readonly", "readonly").css({"overflow": "hidden", "text-overflow": "ellipsis"});
		}
		else if(name == "to-order" && selector)
		{
			$(".to-order_frame").find('[data-sid="PRODUCT_NAME"]').val($(selector).attr('alt')).attr("readonly", "readonly").css({"overflow": "hidden", "text-overflow": "ellipsis"});
			$(".to-order_frame").find('[data-sid="PRODUCT_ID"]').val($(selector).attr('data-item'));
		}
		else if (name == "enter")
		{
			if ($(hash.t).is(".reg"))
			{
				hash.w.find("[data-auth-type='auth']").hide();
				hash.w.find("[data-auth-type='register']").show();
			}
		}
		else if( name == 'one_click_buy')
		{
			$('#one_click_buy_form_button').on("click", function() { $("#one_click_buy_form").submit(); });
			$('#one_click_buy_form').submit( function()
			{
				if($('.'+name+'_frame form input.error').length || $('.'+name+'_frame form textarea.error').length) { return false }
				else
				{
					setPreloader(true);

					$.ajax({
						url: $(this).attr('action'),
						data: $(this).serialize(),
						type: 'POST',
						dataType: 'json',
						error: function(data) { alert('Error connecting server'); },
						success: function(data)
						{
							if(data.result=='Y') { $('.one_click_buy_result').show(); $('.one_click_buy_result_success').show(); }
							else { $('.one_click_buy_result').show(); $('.one_click_buy_result_fail').show(); $('.one_click_buy_result_text').text(data.message);}
							$('.one_click_buy_modules_button', self).removeClass('disabled');
							$('#one_click_buy_form').hide();
							$('#one_click_buy_form_result').show();
						},
						complete: function() {
							setPreloader(false);
						}
					});
				}
				return false;
			});
		}
		else if( name == 'one_click_buy_basket')
		{
			$('#one_click_buy_form_button').on("click", function() { $("#one_click_buy_form").submit(); }); //otherwise don't works
			$('#one_click_buy_form').live("submit", function()
			{
				$.ajax({
					url: $(this).attr('action'),
					data: $(this).serialize(),
					type: 'POST',
					dataType: 'json',
					error: function(data) { window.console&&console.log(data); },
					success: function(data)
					{
						if(data.result=='Y') { $('.one_click_buy_result').show(); $('.one_click_buy_result_success').show(); }
						else { $('.one_click_buy_result').show(); $('.one_click_buy_result_fail').show(); $('.one_click_buy_result_text').text(data.message);}
						$('.one_click_buy_modules_button', self).removeClass('disabled');
						$('#one_click_buy_form').hide();
						$('#one_click_buy_form_result').show();
					}
				});
				return false;
			});
		}
		else if( name == 'subscribe_product')
		{
			$('#product_subscribe_form_button').on("click", function()
			{
				$("#product_subscribe_form").submit(); //otherwise don't works

				if ($("#product_subscribe_form").valid())
				{
					$("#product_subscribe_form_description").slideUp(200);
					$("#product_subscribe_form_description .message").empty();
				}
			});
			$('#product_subscribe_form').on("submit", function()
			{
				if ($("#product_subscribe_form").valid())
				{
					setPreloader(true);

					$.ajax({
						url: $(this).attr('action'),
						data: $(this).serialize(),
						type: 'POST',
						dataType: 'json',
						error: function(data) { window.console&&console.log(data); },
						success: function(data)
						{
							if (data.SUCCESS)
							{
								$(".subscribe_product_frame .pop-up-title").text("Вы успешно подписались");
								$("#product_subscribe_form").hide();
								$("#product_subscribe_form_result").show();
							}
							else
							{
								if (typeof data.ERROR_TYPE != "undefined" && data.ERROR_TYPE == "subscribed")
								{
									$("#SUBSCRIBE_PRODUCT_USER_EMAIL").focus();
								}

								if (typeof data.ERROR != "undefined")
								{
									var errors = data.ERROR;
									errors.forEach( function(err, i, errors)
									{
										$("#product_subscribe_form_description .message").append( '<p>' +err+ '</p>' );
									});
								}
								$("#product_subscribe_form_description").slideDown(200);
							}
						},
						complete: function() {
							setPreloader(false);
						}
					});
				}

				return false;
			});
		}
		else if( name == 'unsubscribe_product')
		{
			$('#product_unsubscribe_form_button').on("click", function()
			{
				$("#product_subscribe_form").submit(); //otherwise don't works
				if ($("#product_subscribe_form").valid())
				{
					$("#product_subscribe_form_description").slideUp(200);
					$("#product_subscribe_form_description .message").empty();
				}
			});


			$('#product_subscribe_form').on("submit", function()
			{
				if ($("#product_subscribe_form").valid())
				{
					console.dir($(this).serialize());

					$.ajax({
						url: $(this).attr('action'),
						data: $(this).serialize(),
						type: 'POST',
						dataType: 'json',
						error: function(data) { window.console&&console.log(data); },
						success: function(data)
						{
							if (data.SUCCESS)
							{
								$(".unsubscribe_product_frame .pop-up-title").text("Вы успешно отписались");
								$("#product_subscribe_form").hide();
								$("#product_subscribe_form_result").show();
							}
							else
							{
								if (typeof data.ERROR != "undefined")
								{
									var errors = data.ERROR;
									errors.forEach( function(err, i, errors)
									{
										$("#product_unsubscribe_form_error").append( '<p>' +err+ '</p>' );
									});
									$("#product_subscribe_form").hide();
								}
								$("#product_unsubscribe_form_error").show();
							}
						}
					});
				}

				return false;
			});
		}

		$('.'+name+'_frame').show();
	}
}
if(!isFunction('onHidejqm')){
	var onHidejqm = function(name, hash){
		if(hash.w.find('.one_click_buy_result_success').is(':visible') && name=="one_click_buy_basket"){
			window.location.href = window.location.href;
		}
		hash.w.css('opacity', 0).hide();
		hash.w.empty();
		hash.o.remove();
		hash.w.removeClass('show');
	}
}

if(!isFunction("oneClickBuy"))
{
	var oneClickBuy = function (elementID, iblockID, that)
	{
		name = 'one_click_buy';
		if(typeof(that) !== 'undefined'){
			elementQuantity = $(that).attr('data-quantity');
		}
		$('body').find('.'+name+'_frame').remove();
		$('body').append('<div class="'+name+'_frame popup"></div>');
		$('.'+name+'_frame').jqm({trigger: '.'+name+'_frame.popup', onHide: function(hash) { onHidejqm(name,hash) }, toTop: false, onLoad: function( hash ){ onLoadjqm(name, hash ); }, ajax: arKShopOptions["SITE_DIR"]+'ajax/one_click_buy.php?ELEMENT_ID='+elementID+'&IBLOCK_ID='+iblockID+'&ELEMENT_QUANTITY='+elementQuantity});
		$('.'+name+'_frame.popup').click();
	}
}

if(!isFunction("oneClickBuyBasket"))
{
	var oneClickBuyBasket = function ()
	{
		name = 'one_click_buy_basket'
		$('body').find('.'+name+'_frame').remove();
		$('body').append('<div class="'+name+'_frame popup"></div>');
		$('.'+name+'_frame').jqm({trigger: '.'+name+'_frame.popup', onHide: function(hash) { onHidejqm(name,hash) }, onLoad: function( hash ){ onLoadjqm( name, hash ); }, ajax: arKShopOptions["SITE_DIR"]+'ajax/one_click_buy_basket.php'});
		$('.'+name+'_frame.popup').click();
	}
}

if(!isFunction("subscribeProduct"))
{
	var subscribeProduct = function (params)
	{
		if ($.isPlainObject(params) && typeof params.product_id != "undefined" && $.isArray(params.form_fields) && params.form_fields.length
				&& typeof params.subscription_type != "undefined" && (params.subscription_type == "PRICE_DOWN" || params.subscription_type == "PRODUCT_ARRIVAL") )
		{
			var ajaxQuery = "type=subscribe&form_fields="+encodeURIComponent(params.form_fields.toString())+"&subscription_type="+params.subscription_type+"&product_id="+params.product_id;
			if (params.subscription_type == "PRICE_DOWN")
			{
				if (typeof params.product_price != "undefined")
				{
					ajaxQuery = ajaxQuery + "&product_price="+params.product_price
				}
				if (typeof params.product_price_type != "undefined")
				{
					ajaxQuery = ajaxQuery + "&product_price_type="+params.product_price_type
				}
			}
			if (typeof params.product_article != "undefined")
			{
				ajaxQuery = ajaxQuery + "&product_article="+params.product_article
			}
			if (typeof params.product_available != "undefined")
			{
				ajaxQuery = ajaxQuery + "&product_available="+params.product_available
			}

			name = 'subscribe_product'
			$('body').find('.'+name+'_frame').remove();
			$('body').append('<div class="'+name+'_frame popup"></div>');
			$('.'+name+'_frame').jqm({trigger: '.'+name+'_frame.popup', onHide: function(hash) { onHidejqm(name,hash) }, onLoad: function( hash ){ onLoadjqm( name, hash ); }, ajax: arKShopOptions["SITE_DIR"]+'ajax/subscribe_product_popup.php?'+ajaxQuery});
			$('.'+name+'_frame.popup').click();
		}
		else
		{
			console.log("Error subscribe product call");
			return false;
		}
	}
}

if(!isFunction("unSubscribeProduct"))
{
	var unSubscribeProduct = function (params)
	{
		if ($.isPlainObject(params) && typeof params.user_email != "undefined" && typeof params.delete_code != "undefined"
			&& typeof params.subscription_type != "undefined" && (params.subscription_type == "PRICE_DOWN" || params.subscription_type == "PRODUCT_ARRIVAL") )
		{
			var ajaxQuery = "type=unsubscribe&subscription_type="+params.subscription_type+"&user_email="+params.user_email+"&delete_code="+params.delete_code;
			name = 'unsubscribe_product'
			$('body').find('.'+name+'_frame').remove();
			$('body').append('<div class="'+name+'_frame popup"></div>');
			$('.'+name+'_frame').jqm({trigger: '.'+name+'_frame.popup', onHide: function(hash) { onHidejqm(name,hash) }, onLoad: function( hash ){ onLoadjqm( name, hash ); }, ajax: arKShopOptions["SITE_DIR"]+'ajax/subscribe_product_popup.php?'+ajaxQuery});
			$('.'+name+'_frame.popup').click();
		}
		else
		{
			console.log("Error unsubscribe product call");
			return false;
		}
	}
}

if(!isFunction("jqmEd"))
{
	var jqmEd = function (name, form_id, open_trigger, requestData, selector)
	{
		if(typeof(requestData) == "undefined"){
			requestData = '';
		}
		if(typeof(selector) == "undefined"){
			selector = false;
		}
		$('body').find('.'+name+'_frame').remove();
		$('body').append('<div class="'+name+'_frame popup"></div>');
		if(typeof open_trigger == "undefined" )
		{
			$('.'+name+'_frame').jqm({trigger: '.'+name+'_frame.popup', onLoad: function( hash ){ onLoadjqm( name , hash , requestData, selector); }, ajax: arKShopOptions["SITE_DIR"]+'ajax/form.php?form_id='+form_id+(requestData.length ? '&' + requestData : '')});
		}
		else
		{
			if(name == 'enter'){
				$('.'+name+'_frame').jqm({trigger: open_trigger,  onLoad: function( hash ){ onLoadjqm( name , hash , requestData, selector); }, ajax: arKShopOptions["SITE_DIR"]+'ajax/auth.php'});
			}
			else if(name == 'js_city_change'){
				$('.'+name+'_frame').jqm({trigger: open_trigger,  onLoad: function( hash ){ onLoadjqm( name , hash , requestData, selector); }, ajax: arKShopOptions["SITE_DIR"]+'ajax/city_change.php'});
			}
			else{
				$('.'+name+'_frame').jqm({trigger: open_trigger,  onLoad: function( hash ){ onLoadjqm( name , hash , requestData, selector); }, ajax: arKShopOptions["SITE_DIR"]+'ajax/form.php?form_id='+form_id+(requestData.length ? '&' + requestData : '')});
			}
			$(open_trigger).dblclick(function(){return false;})
		}
		return true;
	}
}


if(!isFunction("animateBasketLine"))
{
	var animateBasketLine = function(speed, total_summ, total_count, wish_count)
	{
		if(typeof speed == "undefined") { speed = 200; } else {speed = parseInt(speed);}

		if($("#basket_line .cart").length)
		{
			if(typeof total_count == "undefined" || typeof total_summ == "undefined" || typeof wish_count == "undefined")
			{
				$.getJSON( arKShopOptions['SITE_DIR']+"ajax/get_basket_count.php", function(data)
				{
					if($("#basket_line .cart").length){
						var basketTotalSumm = parseFloat($('.basket_popup_wrapp input[name=total_price]').attr("value"));
						var basketTotalCount = parseInt($('.basket_popup_wrapp input[name=total_count]').attr("value"));
						var wishTotalCount = parseInt($('.basket_popup_wrapp input[name=delay_count]').attr("value"));

						// console.log(basketTotalSumm);

						if(data.WISH_COUNT > 0 && !$("#basket_line .cart_wrapp.with_delay").is(":visible")){
							setTimeout(function(){
								$("#basket_line .cart_wrapp:not(.with_delay)").fadeOut(333, function() {
									$('#basket_line .total_summ').html(jsPriceFormat(Math.floor(data.TOTAL_SUMM)));
								});
							}, 200);
							$("#basket_line .cart_wrapp.with_delay").fadeIn(333, "easeOutSine");
						}
						else if(data.WISH_COUNT == 0 && $("#basket_line .cart_wrapp.with_delay").is(":visible")){
							setTimeout(function(){$("#basket_line .cart_wrapp.with_delay").fadeOut(333);}, 200)
							$("#basket_line .cart_wrapp:not(.with_delay)").fadeIn(333, "easeOutSine");
							$('#basket_line .total_summ').animateNumbers(data.TOTAL_SUMM, speed, true, basketTotalSumm);
						}
						else{
							$('#basket_line .total_summ').animateNumbers(data.TOTAL_SUMM, speed, true, basketTotalSumm);
						}
						if(parseInt(data.TOTAL_COUNT) == 0){
							$("#basket_line .cart").addClass("empty_cart").find(".cart_wrapp a").removeClass("cart-call").attr("href", $("#basket_line input[name=path_to_basket]").attr("value"));
							$("#basket_line .cart_wrapp a.cart-call").unbind();
						}
						$('#basket_line .total_count').each(function(){$(this).animateNumbers(data.TOTAL_COUNT, speed, false, basketTotalCount);});
						$('#basket_line .with_delay .count').each(function(){$(this).animateNumbers(data.WISH_COUNT, speed, false, wishTotalCount);});
						$('.basket_popup_wrapp input[name=total_price]').attr("value", data.TOTAL_SUMM);
						$('.basket_popup_wrapp input[name=total_count]').attr("value", data.TOTAL_COUNT);
						$('.basket_popup_wrapp input[name=delay_count]').attr("value", data.WISH_COUNT);
					}
					if($("#basket_line .basket_fly").length){
						var basketTotalCount = parseInt($('.basket_popup_wrapp input[name=total_count]').attr("value"));
						var wishTotalCount = parseInt($('.basket_popup_wrapp input[name=delay_count]').attr("value"));
						if(data.TOTAL_COUNT>0 && $('#basket_line .basket_count').is(".empty")) { $('#basket_line .basket_count').removeClass("empty");  }
						else if(data.TOTAL_COUNT==0 && !$('#basket_line .basket_count').is(".empty")) { $('#basket_line .basket_count').addClass("empty"); }
						if(data.WISH_COUNT>0 && $('#basket_line .wish_count').is(".empty")) { $('#basket_line .wish_count').removeClass("empty"); }
						else if(data.WISH_COUNT==0 && !$('#basket_line .wish_count').is(".empty")) { $('#basket_line .wish_count').addClass("empty"); }
						$('#basket_line .basket_count .count').animateNumbers(data.TOTAL_COUNT, speed, false, basketTotalCount);
						$('#basket_line .wish_count .count').animateNumbers(data.WISH_COUNT, speed, false, wishTotalCount);
						$('.basket_popup_wrapp input[name=total_price]').attr("value", data.TOTAL_SUMM);
						$('.basket_popup_wrapp input[name=total_count]').attr("value", data.TOTAL_COUNT);
						$('.basket_popup_wrapp input[name=delay_count]').attr("value", data.WISH_COUNT);
					}
				});
			}
			else
			{
				if($("#basket_line .cart").length)
				{
					var basketTotalSumm = parseFloat($('.basket_popup_wrapp input[name=total_price]').attr("value"));
					var basketTotalCount = parseInt($('.basket_popup_wrapp input[name=total_count]').attr("value"));
					var wishTotalCount = parseInt($('.basket_popup_wrapp input[name=delay_count]').attr("value"));
					if(wish_count>0 && !$("#basket_line .cart_wrapp.with_delay").is(":visible"))
					{
						setTimeout(function(){$("#basket_line .cart_wrapp:not(.with_delay)").fadeOut(333,
								function() {
									$('#basket_line .total_summ').html(jsPriceFormat(Math.floor(total_summ)));
								});}, 200);
						$("#basket_line .cart_wrapp.with_delay").fadeIn(333, "easeOutSine");
					}
					else if(wish_count==0 && $("#basket_line .cart_wrapp.with_delay").is(":visible"))
					{
						setTimeout(function(){$("#basket_line .cart_wrapp.with_delay").fadeOut(333);}, 200)
						$("#basket_line .cart_wrapp:not(.with_delay)").fadeIn(333, "easeOutSine");
					}
					if(parseInt(total_count)==0)
					{
						$('#basket_line .total_summ').animateNumbers(0, speed, true, basketTotalSumm);
						$("#basket_line .cart").addClass("empty_cart").find(".cart_wrapp a.basket_link").removeClass("cart-call").attr("href", $("#basket_line input[name=path_to_basket]").attr("value"));
						$("#basket_line .cart_wrapp a.cart-call").unbind();
					}
					else { $('#basket_line .total_summ').animateNumbers(total_summ, speed, true, basketTotalSumm); }
					$('#basket_line .total_count').animateNumbers(total_count, speed, false, basketTotalCount);
					$('#basket_line .delay_count').animateNumbers(wish_count, speed, false, wishTotalCount);
				}
				if($("#basket_line .basket_fly").length)
				{
					var basketTotalCount = parseInt($('.basket_popup_wrapp input[name=total_count]').attr("value"));
					var wishTotalCount = parseInt($('.basket_popup_wrapp input[name=delay_count]').attr("value"));

					if(total_count>0 && $('#basket_line .basket_count').is(".empty")) { $('#basket_line .basket_count').removeClass("empty");  }
					else if(total_count==0 && !$('#basket_line .basket_count').is(".empty")) { $('#basket_line .basket_count').addClass("empty"); }
					if(wish_count>0 && $('#basket_line .wish_count').is(".empty")) { $('#basket_line .wish_count').removeClass("empty"); }
					else if(wish_count==0 && !$('#basket_line .wish_count').is(".empty")) { $('#basket_line .wish_count').addClass("empty"); }

					$('#basket_line .basket_count .count').animateNumbers(total_count, speed, false, basketTotalCount);
					$('#basket_line .wish_count .count').animateNumbers(wish_count, speed, false, wishTotalCount);
				}
			}
		}
		return true;
	}
}


if(!isFunction("replaceBasketPopup"))
{
	function replaceBasketPopup (hash)
	{
		if(typeof hash != "undefined")
		{
			hash.w.hide();
			hash.o.hide();
		}
	}
}

if(!isFunction("deleteFromBasketPopup"))
{
	function deleteFromBasketPopup (basketWindow, delay, speed, item)
	{
		if(typeof basketWindow != "undefined" && typeof item != "undefined")
		{
			var row = $(item).parents("tr.catalog_item");
			var total_count = parseInt($(basketWindow).find("input[name=total_count]").attr("value"))-1;

			if(total_count<3) { $(basketWindow).find(".cart_shell").css("height", ""); }
			else
			{
				if($(basketWindow).find(".cart_shell").attr("style"))
				{
					if(!$(basketWindow).find(".cart_shell").attr("style").match(/height/)) {
						$(basketWindow).find(".cart_shell").height($(basketWindow).find(".cart_shell").height());
					}
				}
			}

			if(total_count==0)
			{
				setTimeout(function(){$(basketWindow).find(".popup-intro:not(.grey)").fadeOut(333);}, 200)
				$(basketWindow).find(".popup-intro.grey").fadeIn(333, "easeOutSine");
				$(basketWindow).find(".total_wrapp .total").slideUp(speed);
				$(basketWindow).find(".total_wrapp hr").slideUp(speed);
				$(basketWindow).find(".basket_empty").slideDown(speed);
				$(basketWindow).find(".total_wrapp .but_row").addClass("no_border").animate({"marginTop": 0});
				if(parseInt($(basketWindow).find("input[name=delay_count]").attr("value"))>0)
				{
					setTimeout(function(){$(basketWindow).find(".but_row .to_basket").fadeOut(333);}, 200)
					$(basketWindow).find(".but_row .to_delay").fadeIn(333, "easeOutSine");
				} else { $(basketWindow).find(".but_row .to_basket").fadeOut(333); }

				setTimeout(function(){$(basketWindow).find(".but_row .checkout").fadeOut(333);}, 200)
				$(basketWindow).find(".but_row .close_btn").fadeIn(333, "easeOutSine");
				var newPrice = 0;
			}
			else
			{
				var itemPrice = $(row).find("input[name=item_price_"+$(row).attr('product-id')+"]").attr("value");
				var currentPrice = $(basketWindow).find("input[name=total_price]").attr("value");
				var newPrice = currentPrice - itemPrice;
			}

			//preanimate while waiting ajax response
			$(row).find(".cost-cell .price:not(.discount)").animateNumbers(0, (speed*2), true);
			$(basketWindow).find(".total_wrapp .total .price:not(.discount)").animateNumbers(newPrice, (speed*3), true);

			$(row).find("td").wrapInner('<div class="slide_out"></div>');
			$(row).fadeTo(speed, 0);
			$(row).find(".slide_out").slideUp(speed, function() { $(row).remove(); });
			$('.basket_button.in-cart[data-item='+$(row).attr("catalog-product-id")+']').hide();
			$('.basket_button.to-cart[data-item='+$(row).attr("catalog-product-id")+']').show();

			if($("#basket_line").find(".basket_hidden tr.catalog_item").length)
			{
				var addedRow = $("#basket_line").find(".basket_hidden tr.catalog_item").first();
				$(addedRow).attr("animated", "false").find("td").wrapInner('<div class="slide"></div>');
				$(basketWindow).find(".cart_shell tbody").append($(addedRow));

				$(basketWindow).find(".catalog_item[animated=false]").each(function(index, element)
				{
					$(element).fadeTo((speed*2), 1, function(){$(element).removeAttr("animated")});
					$(element).find(".slide").slideDown(speed);
				});
			}

			//correct data
			$.get( item.attr("href"), function()
			{ 	$.get( arKShopOptions['SITE_DIR']+"ajax/show_basket_popup.php", $.proxy
				(
					function(data)
					{
						var newBasket  = $.parseHTML(data);

						var newSummPrice = parseFloat($(newBasket).find("input[name=total_price]").attr("value"));
						animateBasketLine(200, newSummPrice, parseInt($(newBasket).find("input[name=total_count]").attr("value")), parseInt($(newBasket).find("input[name=delay_count]").attr("value")));

						$(basketWindow).find("input[name=total_count]").attr("value", $(newBasket).find("input[name=total_count]").attr("value"));
						$(basketWindow).find("input[name=delay_count]").attr("value", $(newBasket).find("input[name=delay_count]").attr("value"));
						$(basketWindow).find("input[name=total_price]").attr("value", $(newBasket).find("input[name=total_price]").attr("value"));

						$(basketWindow).find(".total_wrapp .total .price").animateNumbers(newSummPrice, (speed*3), true);

						if ($(newBasket).find(".total_wrapp .more_row").length)
						{
							if ($(basketWindow).find(".total_wrapp .more_row").length) {
								$(basketWindow).find(".total_wrapp .more_row .count_message").html($(newBasket).find(".total_wrapp .more_row .count_message").html());
								$(basketWindow).find(".total_wrapp .more_row .count").animateNumbers(parseInt(delSpaces($(newBasket).find(".total_wrapp .more_row .count").text()).replace(/,/g, "")), speed, false);
							}
						}
						else
						{
							var target = $(basketWindow).find(".total_wrapp .more_row");
							$(target).fadeTo(speed, 0, function(){$(target).remove();});
						}

						//correct all prices
						$(newBasket).find(".catalog_item").each(function(index, element)
						{
							var itemPrice = $(element).find("input[name^=item_price]").attr("value");
							if($(basketWindow).find(".catalog_item input[name=item_price_"+$(element).attr('product-id')+"]").length &&
								$(basketWindow).find(".catalog_item input[name=item_price_"+$(element).attr('product-id')+"]").attr("value") != itemPrice)
							{
								$(basketWindow).find(".catalog_item[product-id="+$(element).attr('product-id')+"] .price:not(.discount)").animateNumbers(itemPrice, speed, true);
							}
						});

						//save some hidden elements for animate deleting
						$("#basket_line").find(".basket_hidden").html($(newBasket).find(".basket_hidden").html());
					}
				));
			});
		}
	}
}

if(!isFunction("preAnimateBasketFly"))
{
	function preAnimateBasketFly (basketWindow, delay, speed, shift)
	{
		if(typeof basketWindow != "undefined")
		{
			if(typeof delay == "undefined") {delay = 100;} else {delay = parseInt(delay);}
			if(typeof speed == "undefined") {speed = 200;} else {speed = parseInt(speed);}

			if($(basketWindow).is(".basket_empty")) { $(basketWindow).removeClass("basket_empty"); }

			$.post( arKShopOptions['SITE_DIR']+"ajax/show_basket_fly.php", "PARAMS="+$(basketWindow).find("input#fly_basket_params").val(), $.proxy
			(
				function(data)
				{
					var newBasket  = $.parseHTML(data);
					$(basketWindow).find(".tabs_content.basket li").each(function(i, element)
					{
						if($(element).attr("item-section")!="AnDelCanBuy")
						{
							if($(newBasket).find(".tabs_content.basket li[item-section="+$(element).attr("item-section")+"]").length)
							{
								$(element).html($(newBasket).find(".tabs_content.basket li[item-section="+$(element).attr("item-section")+"]").html());
							}
							else
							{
								$(element).remove();
							}
						}
						else if($(element).find(".cart_empty").length)
						{
							$(element).find(".cart_empty").remove();
							$(element).html($(newBasket).find(".tabs_content.basket li[item-section=AnDelCanBuy]").html());
							$(element).find(".module-cart tbody").html("");
							$(element).find(".row_values div[data-type=price_discount] .price:not(.discount)").html("0");
							$(element).find(".row_values div[data-type=price_discount] .price.discount strike").html("0");
							$(element).find(".row_values div[data-type=price_normal] .price").html("0");
						}
					});

					$(basketWindow).find(".basket_sort").html($(newBasket).find(".basket_sort").html());
					$(basketWindow).find(".tabs_content li").first().addClass("cur").siblings().removeClass("cur");
					$(basketWindow).find(".tabs li").first().addClass("cur").siblings().removeClass("cur");


					$(newBasket).find(".tabs_content.basket li").each(function(i, element)
					{
						if(!$(basketWindow).find(".tabs_content.basket li[item-section="+$(element).attr("item-section")+"]").length)
						{
							$(basketWindow).find(".tabs_content.basket").append($(element));
						}
					});

					if(typeof shift == "undefined" || shift!=false)
					{
						if(parseInt($(basketWindow).css("right"))<0)
						{
							if(arKShopOptions['THEME']['SHOW_BASKET_ONADDTOCART'] !== 'N'){
								$(basketWindow).stop().animate({"right": "0"}, 333, function()
								{
									postAnimateBasketFly ($(basketWindow).find(".tabs_content.basket li[item-section=AnDelCanBuy]"), $(newBasket).find(".tabs_content.basket li[item-section=AnDelCanBuy]"), delay, speed);
								});
							}
							else{
								postAnimateBasketFly ($(basketWindow).find(".tabs_content.basket li[item-section=AnDelCanBuy]"), $(newBasket).find(".tabs_content.basket li[item-section=AnDelCanBuy]"), delay, speed);
							}
						}
						else
						{
							postAnimateBasketFly (basketWindow.find(".tabs_content.basket li[item-section=AnDelCanBuy]"), $(newBasket).find(".tabs_content.basket li[item-section=AnDelCanBuy]"), delay, speed);
						}
					}
				}
			));
		}
	}
}


if(!isFunction("postAnimateBasketFly"))
{
	function postAnimateBasketFly (oldBasket, newBasket, delay, speed)
	{
		setTimeout(function() //animation could be delayed
		{
			if(typeof oldBasket != "undefined" && typeof newBasket != "undefined")
			{
				var rows = $(newBasket).find(".module-cart tbody tr[data-id]:not(.hidden)");
				$(rows).each(function(i, element)
				{
					if(!$(oldBasket).find("tr[data-id="+$(element).attr("data-id")+"]").length)
					{
						var itemRow = $(element).clone();
						var itemPrice = $(itemRow).find("input[name=item_price_"+$(itemRow).attr('product-id')+"]").attr("value");
						var itemDiscountPrice = $(itemRow).find("input[name=item_price_"+$(itemRow).attr('product-id')+"]").attr("value");
						$(itemRow).attr("animated", "false").find("td").wrapInner('<div class="slide"></div>');
						$(oldBasket).find(".module-cart tbody").prepend($(itemRow));
					} else { if($(element).attr("animated")=="false") { $(element).removeAttr("animated"); } }
				});


				$(oldBasket).find("tbody tr[animated=false]").each(function(index, element)
				{
					$(element).find(".thumb-cell img").css({"maxHeight": "inherit", "maxWidth": "inherit"}).fadeTo((speed), 1, function() { $(element).removeAttr("animated") });
					$(element).find(".slide").slideDown(speed);

					$(element).find(".cost-cell .price:not(.discount)").html("0");
					$(element).find(".cost-cell .price:not(.discount)").animateNumbers($(newBasket).find("input[name=item_price_"+$(element).attr("data-id")+"]").attr("value"), (speed*2), true, 0, "", function () { $(element).removeAttr("animated"); });
					if($(element).find(".cost-cell .price.discount"))
					{
						$(element).find(".cost-cell .price.discount strike").html("0");
						$(element).find(".cost-cell .price.discount strike").animateNumbers($(newBasket).find("input[name=item_price_discount_"+$(element).attr("data-id")+"]").attr("value"), (speed*2), true, 0, "", function () { $(element).removeAttr("animated"); });
					}
					$(element).find(".summ-cell").html("0");
					$(element).find(".summ-cell").animateNumbers($(newBasket).find("input[name=item_summ_"+$(element).attr("data-id")+"]").attr("value"), (speed*2), true, 0, "", function () { $(element).removeAttr("animated"); });

				});

				var results = $(newBasket).find("tr[data-id=total_row]");
				$(results).find(".row_values div[data-type]").each(function(e, element)
				{
					if($(oldBasket).find("tr[data-id=total_row] .row_values div[data-type="+$(element).attr("data-type")+"]").length)
					{
						var newPrice = parseInt(delSpaces($(element).find("span.price").text()).replace(/,/g, ""));
						var newDiscountPrice = parseInt(delSpaces($(element).find("div.price.discount strike").text()).replace(/,/g, ""));
						var dataBlock = $(oldBasket).find("tr[data-id=total_row] .row_values div[data-type="+$(element).attr("data-type")+"]");
						if($(element).attr("data-type")=="price_discount")
							{
								$(dataBlock).find("span.price:not(.discount)").stop().animateNumbers(newPrice, speed, true);
								$(dataBlock).find("div.price.discount strike").stop().animateNumbers(newDiscountPrice, speed, true);
							}
						else if($(element).attr("data-type")=="price_normal")
							{ $(dataBlock).find("span.price").stop().animateNumbers(newPrice, speed, true); }
						else
							{ $(dataBlock).find("span.price").stop().animateNumbers(newPrice, speed, false); }
					}
					else
					{
						if($(element).attr("data-type")!= "price_discount" && $(element).attr("data-type") != "price_normal")
						{
							$(oldBasket).find("tr[data-id=total_row] .row_values").append($(element));
							$(oldBasket).find("tr[data-id=total_row] .row_values div[data-type="+$(element).attr("data-type")+"]").hide().fadeOut(0);
							$(oldBasket).find("tr[data-id=total_row] .row_values div[data-type="+$(element).attr("data-type")+"]").hide().slideDown(200).fadeIn(200);
						}
					}
				});
				$(oldBasket).find(".row_values div[data-type]").each(function(e, element)
				{
					if(!$(results).find(".row_values div[data-type="+$(this).attr("data-type")+"]").length)
					{
						if($(this).attr("data-type")=="price_discount" && $(results).find(".row_values div[data-type=price_normal]").length)
						{
							$(this).attr("data-type", "price_normal");
							$(this).find(".price.discount").fadeOut(200).slideUp(333, function(){$(this).find(".price.discount").remove();});
						}
						else if($(this).attr("data-type")=="price_normal" && $(results).find(".row_values div[data-type=price_discount]").length)
						{
							$(this).attr("data-type", "price_discount");
							$(this).append("<div class='price discount'><strike>"+$(results).find(".row_values div[data-type=price_discount] strike").html()+"</strike></div>").hide().fadeOut(0);
							$(this).find(".price.discount").slideDown(333).fadeIn(200);
						}
						else
						{
							$(element).fadeOut(200).slideUp(333, function(){$(element).remove();});
						}
					}
				});

				checkRowValuesFly(oldBasket);
			}
		}, delay);
	}
}

if(!isFunction("checkRowValuesFly"))
{
	function checkRowValuesFly(basketFly) {
		var h = $(basketFly).find('.goods table').height();
		if(h > 200){
			$(basketFly).find('.itog .row_values').addClass('mt3');
		}
		else if(h > 0){
			$(basketFly).find('.itog .row_values').removeClass('mt3');
		}
	}
}

if(!isFunction("preAnimateBasketPopup"))
{
	function preAnimateBasketPopup (hash, basketWindow, delay, speed)
	{
		if(typeof basketWindow != "undefined")
		{
			if($(basketWindow).find(".popup-intro.grey").css("display")=="block")
			{
				$(basketWindow).find(".popup-intro.grey").hide();
				$(basketWindow).find(".basket_empty").hide();
				$(basketWindow).find(".popup-intro:not(.grey)").show();
				$(basketWindow).find(".total_wrapp .total").show();
				$(basketWindow).find(".total_wrapp hr").show();
				$(basketWindow).find(".but_row .close_btn").hide();
				$(basketWindow).find(".but_row .checkout").show();
				$(basketWindow).find(".but_row .to_delay").hide();
				$(basketWindow).find(".but_row .to_basket").show();
				$(basketWindow).find(".total_wrapp .but_row").removeClass("no_border").css({"marginTop": "", "paddingTop": ""});
			}

			if(typeof delay == "undefined") {delay = 100;} else {delay = parseInt(delay);}
			if(typeof speed == "undefined") {speed = 200;} else {speed = parseInt(speed);}

			var popupWidth = $(basketWindow).width();
			var popupHeight = $(basketWindow).height();

			$(basketWindow).css({
				'margin-left': '-' + popupWidth / 2 + 'px',
				'display': 'block',
				'top': $(document).scrollTop() + (($(window).height() > popupHeight ? ($(window).height() - popupHeight) / 2 : 10))   + 'px'
			});

			if($(basketWindow).is("[animate]"))
			{
				$(basketWindow).removeAttr("animate");
				$.get( arKShopOptions['SITE_DIR']+"ajax/show_basket_popup.php", $.proxy
				(
					function(data)
					{
						var newBasket  = $.parseHTML(data);

						$(basketWindow).find("input[name=total_count]").attr("value", $(newBasket).find("input[name=total_count]").attr("value"));
						$(basketWindow).find("input[name=delay_count]").attr("value", $(newBasket).find("input[name=delay_count]").attr("value"));
						$(basketWindow).find("input[name=total_price]").attr("value", $(newBasket).find("input[name=total_price]").attr("value"));

						//save some hidden elements for animate deleting
						$("#basket_line").find(".basket_hidden").html($(newBasket).find(".basket_hidden").html());

						var newSummPrice = parseFloat($(newBasket).find("input[name=total_price]").attr("value"));
						var rows = $(newBasket).find(".cart_shell .catalog_item[product-id]");

						$(rows).each(function()
						{
							if(!$(basketWindow).find(".catalog_item[product-id="+$(this).attr("product-id")+"]").length)
							{
								var itemRow = $(this).clone();
								var itemPrice = $(itemRow).find("input[name=item_price_"+$(itemRow).attr('product-id')+"]").attr("value");
								$(itemRow).attr("animated", "false").find("td").wrapInner('<div class="slide"></div>');
								$(basketWindow).find(".cart_shell tbody").prepend($(itemRow));
							} else { if($(this).attr("animated")=="false") { $(this).removeAttr("animated"); } }
						});

						setTimeout(function() //animation could be delayed
						{
							if($(newBasket).find("input[name=total_count]").attr("value")>=3)
							{
								if($(basketWindow).find(".cart_shell").attr("style"))
								{
									if(!$(basketWindow).find(".cart_shell").attr("style").match(/height/)) {
										$(basketWindow).find(".cart_shell").height($(basketWindow).find(".cart_shell").height());
									}
								} else { $(basketWindow).find(".cart_shell").height($(basketWindow).find(".cart_shell").height()); }
							} else { $(basketWindow).find(".cart_shell").css("height", ""); }

							$(basketWindow).find("tr.catalog_item").each(function(index, element)
							{
								if(index<3) return true;
								else {
									$(element).find("td").wrapInner('<div class="slide_out"></div>');
									$(element).find(".slide_out").slideUp(speed, function() { $(element).remove(); });
								}
							});

							$(basketWindow).find(".catalog_item[animated=false]").each(function(index, element)
							{
								$(element).fadeTo((speed*2), 1, function(){$(element).removeAttr("animated")});
								$(element).find(".slide").slideDown(speed);
							});

							$(basketWindow).find(".catalog_item[animated=false]").each(function(index, element)
							{
								$(element).find(".cost-cell .price").html("0");
								$(element).find(".cost-cell .price").animateNumbers($(newBasket).find("input[name=item_price_"+$(element).attr("product-id")+"]").attr("value"), (speed*2), true, 0, "", function () { $(element).removeAttr("animated"); });
							});

							if ($(newBasket).find(".total_wrapp .more_row").length)
							{
								if ($(basketWindow).find(".total_wrapp .more_row").length)
								{
									$(basketWindow).find(".total_wrapp .more_row .count_message").html($(newBasket).find(".total_wrapp .more_row .count_message").html());
									$(basketWindow).find(".total_wrapp .more_row .count").animateNumbers(parseInt(delSpaces($(newBasket).find(".total_wrapp .more_row .count").text()).replace(/,/g, "")), speed, false);
								}
								else
								{
									$(basketWindow).find(".total_wrapp .price-all").prepend($(newBasket).find(".total_wrapp .more_row").fadeTo(0,0));
									$(basketWindow).find(".total_wrapp .more_row").fadeTo(speed, 1);
								}
							}

							//correct all prices
							$(newBasket).find(".catalog_item").each(function(index, element)
							{
								var itemPrice = $(element).find("input[name^=item_price]").attr("value");
								if($(basketWindow).find(".catalog_item input[name=item_price_"+$(element).attr('product-id')+"]").length &&
									$(basketWindow).find(".catalog_item input[name=item_price_"+$(element).attr('product-id')+"]").attr("value") != itemPrice)
								{
									$(basketWindow).find(".catalog_item[product-id="+$(element).attr('product-id')+"] .price:not(.discount)").animateNumbers(itemPrice, speed, true);
								}
							});

							$(basketWindow).find(".total_wrapp .total .price").animateNumbers(newSummPrice, (speed*3), true);
						}, delay);
					}
				));
			}
		}
	}
}
$(document).ready(function(){
	//some adaptive hacks
	$(window).resize(function(){
		waitForFinalEvent(function(){
			if ($(".news_detail_wrapp table.catalog_download").length)
			{
				$('.news_detail_wrapp table.catalog_download tr').equalize({children: 'td img:not(img[src *= "stock_index_down"])', reset: true});
			}

			if($(window).outerWidth()>600 && $(window).outerWidth()<768 && $(".catalog_detail .buy_buttons_wrapp a").length>1)
			{
				var adapt = false;
				var prev;
				$(".catalog_detail .buy_buttons_wrapp a").each(function(i, element)
				{
					prev = $(".catalog_detail .buy_buttons_wrapp a:eq("+(i-1)+")");
					if($(this).offset().top!=$(prev).offset().top && i>0) { $(".catalog_detail .buttons_block").addClass("adaptive"); }
				});
			} else { $(".catalog_detail .buttons_block").removeClass("adaptive"); }

			if($(window).outerWidth()>600)
			{
				$("#header ul.menu").removeClass("opened").css("display", "");

				if($(".authorization-cols").length)
				{
					$('.authorization-cols').equalize({children: '.col .auth-title', reset: true});
					$('.authorization-cols').equalize({children: '.col .form-block', reset: true});
				}
			}
			else
			{
				$('.authorization-cols .auth-title').css("height", "");
				$('.authorization-cols .form-block').css("height", "");
			}


			if($(window).width()>=400)
			{
				var textWrapper = $(".catalog_block .catalog_item .item-title").height();
				var textContent = $(".catalog_block .catalog_item .item-title a");
				$(textContent).each(function()
				{
					if($(this).outerHeight()>textWrapper)
					{
						$(this).text(function (index, text) { return text.replace(/\W*\s(\S)*$/, '...'); });
					}
				});
				$('.catalog_block').equalize({children: '.catalog_item .cost', reset: true});
				$('.catalog_block').equalize({children: '.catalog_item .item-title', reset: true});
				$('.catalog_block').equalize({children: '.catalog_item', reset: true});
			}
			else
			{
				$(".catalog_block .catalog_item").removeAttr("style");
				$(".catalog_block .catalog_item .item-title").removeAttr("style");
				$(".catalog_block .catalog_item .cost").removeAttr("style");
			}

			if($("#basket_form").length && $(window).outerWidth()<=600)
			{
				$("#basket_form .tabs_content.basket li.cur td").each(function() { $(this).css("width","");});
			}


			if($(".front_slider_wrapp").length)
			{
				$(".extended_pagination li i").each(function()
				{
					$(this).css({"borderBottomWidth": ($(this).parent("li").outerHeight()/2), "borderTopWidth": ($(this).parent("li").outerHeight()/2)});
				});
			}

		},
		50, fRand());
	});

	$('.avtorization-call.enter').live('click', function(e){
		e.preventDefault();
		$("body").append("<span class='evb-enter' style='display:none;'></span>");
		jqmEd('enter', 'auth', '.evb-enter', '', this);
		$("body .evb-enter").click();
		$("body .evb-enter").remove();
	});

//name, form_id, open_trigger, requestData, selector
	$('.to-order').live('click', function(e){
		e.preventDefault();
		$("body").append("<span class='evb-toorder' style='display:none;'></span>");
		jqmEd('to-order', arKShopOptions['FORM']['TOORDER_FORM_ID'], '.evb-toorder', '', this);
		$("body .evb-toorder").click();
		$("body .evb-toorder").remove();
	});

	$(".counter_block:not(.basket) .plus").live("click", function(){
		var input = $(this).parents(".counter_block").find("input[type=text]");
		input.val(parseInt(input.val())+1);
		input.change();
	});

	$(".counter_block:not(.basket) .minus").live("click", function(){
		var input = $(this).parents(".counter_block").find("input[type=text]");
		var currentValue = parseInt(input.val());
		if(currentValue > 1){
			input.val(parseInt(input.val())-1);
			$(this).parents(".counter_block").find("input[type=text]").change();
		}
	});

	$('.counter_block input[type=text]').numeric();

	$('.counter_block input[type=text]').live('change', function(e){
		var val = $(this).val();
		$(this).parents('.counter_block').parent().parent().find('.to-cart').attr('data-quantity', val);
		$(this).parents('.counter_block').parent().parent().find('.one_click').attr('data-quantity', val);
	});

	$('.to-subscribe').live('click', function(e){
		e.preventDefault();
		if($(this).is('.auth')){
			$('.avtorization-call.enter').click();
		}
		else{
			var item = $(this).attr('data-item');
			$(this).hide();
			$(this).parent().find('.in-subscribe').show();
			$.get(arKShopOptions['SITE_DIR'] + 'ajax/item.php?item=' + item + '&subscribe_item=Y',
				$.proxy(function(data){
					if($('#basket_line .basket_fly').length){
						preAnimateBasketFly($('#basket_line .basket_fly'), 0, 0, false);
					}
					$('.wish_item[data-item=' + item + ']').removeClass('added');
					$.getJSON(arKShopOptions['SITE_DIR']+'ajax/get_basket_count.php', function(data){
						animateBasketLine(200);
					});
				})
			);
		}
	})

	$('.in-subscribe').live('click', function(e){
		e.preventDefault();
		var item = $(this).attr('data-item');
		$(this).hide();
		$(this).parent().find('.to-subscribe').show();
		$.get(arKShopOptions['SITE_DIR'] + 'ajax/item.php?item=' + item + '&subscribe_item=Y',
			$.proxy(function(data){
				if($('#basket_line .basket_fly').length){
					preAnimateBasketFly($('#basket_line .basket_fly'), 0, 0, false);
				}
				$.getJSON(arKShopOptions['SITE_DIR'] + 'ajax/get_basket_count.php', function(data){
					animateBasketLine(200);
				});
			})
		);
	})

	$('.compare_item').live('click', function(e){
		e.preventDefault();
		var item = $(this).attr('data-item');
		var iblockID = $(this).attr('data-iblock');
		$(this).toggleClass('added');
		if($(this).find('.value.added').length){
			if($(this).find('.value.added').css('display') == 'none'){
				$(this).find('.value').hide();
				$(this).find('.value.added').show();
			}
			else{
				$(this).find('.value').show(); $(this).find('.value.added').hide();
			}
		}

		$.get(arKShopOptions['SITE_DIR'] + 'ajax/item.php?item=' + item + '&compare_item=Y&iblock_id=' + iblockID,
			$.proxy(function(data){
				jsAjaxUtil.InsertDataToNode(arKShopOptions['SITE_DIR'] + 'ajax/show_compare_preview.php', 'compare_small', false);
			})
		);
	});

	$('.compare_frame').remove();
	$('body').append('<span class="compare_frame popup"></span>');
	$('.compare_frame').jqm({trigger: '.compare_link', onLoad: function(hash){onLoadjqm('compare', hash); }, ajax: arKShopOptions['SITE_DIR']+'ajax/show_compare_list.php'});

	$('.fancy').fancybox({
		openEffect  : 'fade',
		closeEffect : 'fade',
		nextEffect : 'fade',
		prevEffect : 'fade',
		padding: [0]
	});
	$('.js-fancy').fancybox({
		openEffect  : 'fade',
		closeEffect : 'fade',
		nextEffect : 'fade',
		prevEffect : 'fade',
		padding: [0],
		wrapCss: 'custom-fancy'
	});

	jqmEd('callback', 5, '.callback');
	jqmEd('askquestion', 6, '.askquestion');

	$('.js-main-slider').slick({
		arrows: false,
		dots: true,
		dotsClass: 'main-slider__dots',
		autoplay: true,
		fade: true,
	});
	/*$('.js-subscribe-form').on('click', function(e) {
		e.preventDefault();
		var $input = $('.subscribe__input');
		var email = $input.val();
		var data = {'email': email};
		if (email != '' && $('#sub_personal_data').prop('checked')) {
			$.ajax({
				url: '/ajax/ajax-subscribe.php',
				data: data,
				dataType: 'json',
				success: function(data)
				{
					if (data) {
						$.fancybox('<h3 style="text-align:center">' + data.msg +'</h3>', {
							maxWidth: '95%',
							minWidth: '240px',
							padding: [30, 30, 20, 20],
							autoCenter: true,
							fixToView: false
						});

						setTimeout(function() {
							$.fancybox.close();
						}, 1500);
					}
				}
			});
		}
		return;
	})*/

	// new pages(order)

	//tabs
	$(document).on('click', '.js-tabs .js-tab-link', function(){
		var data_tab = $(this).attr('data-tab');
		if(!$(this).hasClass('active')) {
			$(this).addClass('active').siblings().removeClass('active');
			$(this).closest('.js-tabs').find('.js-tab-content').hide();
			$(this).closest('.js-tabs').find('.js-tab-content[data-tab='+data_tab+']').fadeIn();
		}
	});
	$(document).on('click', '.js-tabs-cart .js-tab-link', function(){
		var ind = $(this).index();
		var name = $(this).data('name');
		// console.log('index '+ ind);
		if(!$(this).hasClass('active')) {
			$(this).addClass('active').siblings().removeClass('active');
			$(this).closest('.js-tabs-cart').find('.js-tab-content').hide();
			$(this).closest('.js-tabs-cart').find('.js-tab-content').eq(ind).fadeIn();
			$('.js-cart-title').text(name);
		}
	});
	
	
	// js-print
	$(document).on('click', '.js-print', function(){
		window.print();
	});


	//collapse row

	$('.js-collapse-btn').on('click', function(){
		$(this).closest('.history-table__tr').toggleClass('active').next('.hidden-row').slideToggle(0, function() {
			if ($(this).is(':visible'))
				$(this).css('display','table-row');
		});
		$(this).toggleClass('active');
	});

	$('.js-cancel').on('click', function(){
		$(this).closest('.history-inner-ctrl').find('.js-cancel-block').slideToggle(400);
	});

	$('.js-change-btn').on('click', function(){
		$('.js-personal-change').removeClass('personal-change')
	});

	$('.js-personal-data-name-input').on('change', function(){
		$('.js-personal-data-name').text($(this).val());
	});
	/*$('.js-personal-save').on('click', function(){
		$('.js-personal-change').addClass('personal-change')
	});*/
	$('.js-history-ctrl a').on('click', function(){
		$('.js-history-ctrl a').addClass('inner-link');
		$(this).removeClass('inner-link');
	});

	//accordeon
	$('.js-accordion-btn').click(function(e) {
		e.preventDefault();

		var $this = $(this);

		if ($this.hasClass('show')) {
			$this.removeClass('show');
			$this.next().slideUp(350);
		} else {
			$this.closest('.js-accordion').find('.js-accordion-text').slideUp(350);
			$this.closest('.js-accordion').find('.js-accordion-btn').removeClass('show');
			$this.addClass('show');
			$this.next().slideDown(350);
		}
	});


	$('#change_password').validate({
		rules: {
			NEW_PASSWORD: {
				required: true,
				minlength: 6
			},
			NEW_PASSWORD_CONFIRM: {
				required: true,
				minlength: 6
			}
		},
		messages: {
			NEW_PASSWORD: {
				required: "Это поле обязательно для заполнения",
				minlength: "Длина пароля не менее 6 символов"
			},
			NEW_PASSWORD_CONFIRM: {
				required: "Это поле обязательно для заполнения",
				minlength: "Длина пароля не менее 6 символов"
			}
		}
	});


//basket scripts


	var actionBtn = '.js-cart-delete, .js-to-wishlist, .js-from-wishlist';
	var counterBtn = '.js-cart .js-minus, .js-cart .js-plus';
	var counterInput = '.js-counter-input';


//delete a product, product to wishlist, product from wishlist
	$(document).on('click', actionBtn, function(e) {
		e.preventDefault();
		var $this = $(this);
		var parent = $(this).closest('table');

		var $headerBasketFlag = $('.cart_wrapp_new').find('.total_count');
		var $headerBasketAmount = +($headerBasketFlag.text());
		var $headerWishesFlag = $('.wishes').find('.count');
		var $headerWishesAmount = +($headerWishesFlag.text());

		if ($this.hasClass('js-cart-delete')) {
			setTimeout(checkEmpty.bind(parent), 400);

			if (parent.hasClass('js-cart')) {
				$headerBasketAmount = $headerBasketAmount - 1;
				$headerBasketFlag.text($headerBasketAmount);
			} else if (parent.hasClass('js-fav')) {
				$headerWishesAmount = $headerWishesAmount - 1;
				$headerWishesFlag.text($headerWishesAmount);

			}

		}
		else if($this.hasClass('js-to-wishlist')) {
			var table = $('.js-fav');
			cloneRow.call($this, table);
		}
		else if($this.hasClass('js-from-wishlist')) {
			var table = $('.js-cart');
			cloneRow.call($this, table);
		}

		hideProduct($this);

		if($this.hasClass('js-to-wishlist') || $this.hasClass('js-from-wishlist')) {
			setTimeout(changeTotal.bind(table), 400);
			setTimeout(checkEmpty.bind(table), 400);
			setTimeout(checkEmpty.bind(parent), 400);
		}

		var ajaxUrl = $this.attr('href'); // url for ajax request
		updateItems(ajaxUrl);

	});


//change number of products
	$(document).on('click', counterBtn, function(e) {
		e.preventDefault();

		var $this = $(this);

		var oldVal = +$this.siblings('.js-counter-input').val();
		oldVal === 0 ? oldVal = 1 : false;
		var newVal = $this.hasClass('js-minus') ? oldVal - 1 : oldVal + 1;
		var direction = 'up';
		if (newVal < oldVal) {
			direction = 'down';
		}
		var id = $this.parent('.js-cart-counter').data('id');

		// this default bitrix function from template for change items count
		// can be carefully changed if it needed
		// do ajax request, wich recalculate cart,
		// return json object like $arResult in template
		setQuantity(id, 1, direction, false);


		if(newVal > 0) $(this).siblings('.js-counter-input').val(newVal).attr('value', newVal).change();
	});

	$(document).on('change', counterInput, function(e) {
		changeCost.call($(this));
	 });


	function hideProduct(btn) {
		btn.closest('tr').find('td').attr('style','opacity:0;padding:0').wrapInner('<div style="display:block;" />')
			.parent().find('td > div').slideUp(250, function(){$(this).parent().parent().remove();});

		var parent = btn.closest('table');
		setTimeout(changeTotal.bind(parent), 400);
	}

	function changeCost() {
		var wrap = this.closest('tr');
		var price = wrap.find('.cart__price-td .cart__price').attr('data-price');
		var count = +(wrap.find('.js-counter-input').val());
		var newSum = price * count;
		wrap.find('.js-sum').attr('data-sum', newSum);

		wrap.find('.js-sum').animateNumbers(newSum, 333, true, false, false, function() {});

		changeTotal.call(this);
	}

	function changeTotal() {

		var mainWrap = this.closest('.js-tab-content');

		var newTotal = 0;
		mainWrap.find('.js-sum').each(function(){
			newTotal += +$(this).attr('data-sum');
		});

		mainWrap.find('.js-total').animateNumbers(newTotal, 333, true, false, false, function() {});
	}

	function cloneRow(table) {
		//table - таблица, куда вставляем строку
		var trClone = $(this).closest('tr').clone();
		if(table.hasClass('js-cart')) {
			trClone.find('.cart__fav a.js-from-wishlist').addClass('hidden');
			trClone.find('.cart__fav a.js-to-wishlist').removeClass('hidden');
			trClone.find('.js-counter-input').removeAttr('readonly');
			trClone.find('.js-counter-hidden').removeAttr('disabled');
		}
		else {
			trClone.find('.cart__fav a.js-to-wishlist').addClass('hidden');
			trClone.find('.cart__fav a.js-from-wishlist').removeClass('hidden');
			trClone.find('.js-counter-input').attr('readonly', 'readonly');
			trClone.find('.js-counter-hidden').attr('disabled', 'disabled');
		}

		table.find('tbody').append(trClone);
	}

	function checkEmpty() {
		var mainWrap = this.closest('.js-cart__table-wrap');
		var emptyMes = this.closest('.js-tab-content').find('.js-empty-list-block');
		if(mainWrap.find('tbody tr').length < 1) {
			mainWrap.addClass('hidden');
			emptyMes.removeClass('hidden');
		}
		else {
			mainWrap.removeClass('hidden');
			emptyMes.addClass('hidden');
		}
	}

	// ajax request for add to wishlist/add to cart/delete item from cart
	// return cart template, div with class js-tabs-cart
	function updateItems(url) {
		var $activeTab = $('.js-cart__links').find('.js-tab-link.active');
		var $activeTabIndex = $activeTab.index();

		$.ajax({
			url: url,
			method: 'post',
			type: 'post',
			success: function(response){
				var $popupHeaderInfo = $(response).data('header-info');
				$('.js-header-basket-count').text($popupHeaderInfo.basket);
				$('.js-header-wish-count').text($popupHeaderInfo.wish);

				var $newData = $(response);
				var $newTabLinks = $newData.find('.js-tab-link');
				$newTabLinks.eq($activeTabIndex).addClass('active').siblings().removeClass('active');
				$newData.find('.js-tab-content').hide();
				var $newActiveTabContent = $newData.find('.js-tab-content').eq($activeTabIndex).show();

				$('.js-tabs-cart').html($newData.html());

				var newaActionBtn = '.js-cart-delete, .js-to-wishlist, .js-from-wishlist';
				var newCounterBtn = '.js-cart .js-minus, .js-cart .js-plus';
				var NewCounterInput = '.js-counter-input';

				actionBtn = newaActionBtn;
				counterBtn = newCounterBtn;
				counterInput = NewCounterInput;
			}
		});
	}

	// function calling in order ajax
	$(document).on('click', '.js-order-checkout', function(e) {
		e.preventDefault();
		var step2 = $(this).attr('data-step2');
		if (step2 != 'Y') {
			if (!$(".js-sms-confirm-input").hasClass("is-valid")) {
				$(".js-sms-confirm-input").addClass("is-invalid");
				$(".sms-msg--error").text("Сперва надо ввести этот код");
				return false;
			}
		}
		step2 == 'Y' ? submitForm('Y') : submitForm('N', 'Y');
	});

	$('#sms-code-input').on('click', function(){
		var LAST_NAME = $('#ORDER_PROP_1').val();
        var NAME = $('#ORDER_PROP_14').val();
        var SECOND_NAME = $('#ORDER_PROP_15').val();
        var EMAIL = $('#ORDER_PROP_2').val();
        $.ajax({
            type: "POST",
            url: '/ajax/ajax-change-profile.php',
            data: {action: 'change', LAST_NAME: LAST_NAME, NAME: NAME, SECOND_NAME: SECOND_NAME, EMAIL: EMAIL},
            dataType: 'json',
            success: function(data)
            {
				if(data == 'Y'){

				} else {

				}
            }
        });
	});

	//collapsed text

	$('.toggle-btn').on('click', function(){
		$(this).siblings('.hidden-block-collapsed').slideToggle();
		$(this).toggleClass('active');
	});

	//job name to fancybox
	$('.js-job').fancybox({
		openEffect  : 'fade',
		closeEffect : 'fade',
		padding: [0],
		height: '100%',
		width: '100%',
		maxWidth: 500,
		maxHeight: 540,
		autoSize: false,
		fitToView: false,
		scrolling: 'no'
	});
	$('.js-job').on('click', function(){
		$('#apply .job').val($(this).data('job'));
		$('#apply .job_id').val($(this).data('job-id'));
	});


	//custom file input
	;( function ( document, window, index )
	{
		var inputs = document.querySelectorAll( '.inputfile' );
		Array.prototype.forEach.call( inputs, function( input )
		{
			var label	 = input.previousElementSibling,
				labelVal = label.innerHTML;

			input.addEventListener( 'change', function( e )
			{
				var fileName = '';
				if( this.files && this.files.length > 1 )
					fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
				else
					fileName = e.target.value.split( '\\' ).pop();

				if( fileName )
					label.querySelector( 'span' ).innerHTML = fileName;
				else
					label.innerHTML = labelVal;

				if(fileName) {
					$('#form_field_helper').val(fileName);
					$('label[for=form_field_helper]').remove();
				}
				else
					$('#form_field_helper').val('');
			});

			// Firefox bug fix
			input.addEventListener( 'focus', function(){ input.classList.add( 'has-focus' ); });
			input.addEventListener( 'blur', function(){ input.classList.remove( 'has-focus' ); });
		});
	}( document, window, 0 ));



	//slider for gallery
	var gallery_slider = $('.js-gallery-slider');
	if (window.matchMedia("(max-width: 920px)").matches) {
		gallery_slider.slick({
			slidesToShow: 1
		});
	}

	$(window).on('resize', function(){
		if (window.matchMedia("(max-width: 920px)").matches) {
			gallery_slider.slick({
				slidesToShow: 1
			});
		}else {
			gallery_slider.slick('unslick');
		}
	});

	$(document).on('submit', '.js-auth-form-top', function (e) {
		e.preventDefault();
		setPreloader(true);

		var $form = $(this);
		var $authPopup = $('.js-auth-popup');

		$.ajax({
			url: $form.attr('action'),
			method: 'post',
			type: 'post',
			data: $form.serialize(),
			success: function (responseData) {
				try {
					var jsonResponse = JSON.parse(responseData);
					if (jsonResponse.result == 'Y') {
						window.location = '';
					}

				} catch (e) {
					var $html = $(responseData);
					var $newPopup = $html.find('.js-auth-popup');
					var $newForm = $html.find('.js-auth-form-top');
					$authPopup.children().remove();
					$authPopup.append($newPopup.children());

					$form = $newForm;

					$form.find('.js-phone').inputmask({
						"mask": "+7 (999) 999-9999",
						showMaskOnHover: false,
						removeMaskOnSubmit: true,
						autoUnmask: true,
						onUnMask: function(maskedValue, unmaskedValue) {
							unmaskedValue = "7" + unmaskedValue;
							return unmaskedValue;
						}
					});

				}
			},
			complete: function () {
				setPreloader(false);
			}
		});
	});



	//форма на странице Рестораторам

	$('.js-case').on('click', function(){
		var rest_case = $(this).data('case');
		var rest_case_id = $(this).data('case-id');
		$('#rest-order .case').val(rest_case);
		$('#rest-order .case_id').val(rest_case_id);
	});

	$('.js-case').fancybox({
		openEffect  : 'fade',
		closeEffect : 'fade',
		nextEffect : 'fade',
		prevEffect : 'fade',
		padding: [0],
		wrapCss: 'custom-fancy',
		afterShow: function() {
			$('.js-rest-form').validate({
				rules: {
					rest_name: "required",
					rest_tel: "required"
				},

				messages: {
					rest_name: "Введите Ваше имя",
					rest_tel: {
						required: "Введите Ваш контактный телефон",
						rest_tel: "Пожалуйста, введите номер полностью"
					},
				},

				onfocusin: function(field) {
					var $currentField = $(field);

					$currentField.addClass('is-full');
				},
				onfocusout: function(field) {
					var $currentField = $(field);

					if ($currentField.val() === '') {
						$currentField.removeClass('is-full');
					}
					$('.js-phone').inputmask({
						"mask": "+7 (999) 999-9999",
						showMaskOnHover: false,
						removeMaskOnSubmit: true,
						autoUnmask: true,
						onUnMask: function(maskedValue, unmaskedValue) {
							unmaskedValue = "7" + unmaskedValue;
							return unmaskedValue;
						}
					});
				},
				onmouseover: function(field) {
					var $currentField = $(field);

					$currentField.addClass('is-full');
				},
				invalidHandler: function(evt, validator) {
					$.each(validator.errorList, function(index, item) {
						var $field = $(item.element);

						if ($field.val() !== '') {
							$field.addClass('is-full');
						}
					});
					$('.js-phone').inputmask({
						"mask": "+7 (999) 999-9999",
						showMaskOnHover: false,
						removeMaskOnSubmit: true,
						autoUnmask: true,
						onUnMask: function(maskedValue, unmaskedValue) {
							unmaskedValue = "7" + unmaskedValue;
							return unmaskedValue;
						}
					});
				},
			});
		}
	});

});