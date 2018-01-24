/*
You can use this file with your scripts.
It will not be overwritten when you upgrade solution.
*/
function footerToBottom() {
	var browserHeight = $(window).height(),
	footerOuterHeight = $('footer').outerHeight(true),
	mainHeightMarginPaddingBorder = $('#cover').outerHeight(true) - $('#cover').height();
	$('#cover').css({
		'min-height': browserHeight - footerOuterHeight - mainHeightMarginPaddingBorder,
	});
};
function isInteger(num) {
	return (num ^ 0) === num;
}
function getMaxValue(array){
	var max = array[0];
	for (var i = 0; i < array.length; i++) {
		if (max < array[i]) max = array[i];
	}
	return max;
}

function setPreloader(bool) {
	var $preloader = $('.preloader');
	var $body      = $(document.body);
	switch (bool) {
		case true:
			$body.addClass('is-fixed');
			$preloader.addClass('is-active');
			break;
		case false:
			$body.removeClass('is-fixed');
			$preloader.removeClass('is-active');
			break;
	}
}


function equalizeHeight(el,title) {
	el.find(title).height('auto');
	var elSize = el.length,
		elW = 0,
		inlineSize,
		arr = new Array(),
		arr2 = new Array();
		var wrapperWidth = el.parent().outerWidth(false,false);
	el.each(function(indx) {
		elW += parseInt($(this).outerWidth(true,true))-1;
		if (elW <= wrapperWidth) {
			inlineSize = indx+1;
		}
	});
	if (inlineSize<=elSize) {
		for (var n=0;n<elSize;n++) {
			arr[n] = el.eq(n).find(title).outerHeight(true,true);
			if (isInteger(arr.length/inlineSize)) {
				var j=0;
				for (var i=n-inlineSize+1;i<=n;i++) {
					arr2[j] = arr[i];
					j++;
				}
				var max = getMaxValue(arr2);
				arr2.length = 0;
				for (var i=n-inlineSize+1;i<=n;i++) {
					el.eq(i).find(title).outerHeight(max,true);

				}
			}
			if (n>=inlineSize*parseInt((elSize)/inlineSize)) {
				var arr3 = new Array();
				j = 0;
				for (j;n<=elSize-1;n++) {
					arr3[j] = el.eq(n).find(title).outerHeight(true,true);
					j++;
				}
				max = getMaxValue(arr3);
				for (var z = elSize - arr3.length;z<=elSize-1;z++) {
					el.eq(z).find(title).outerHeight(max,true);
				}

			}
		}

	} else {
		for (var n=0;n<elSize;n++) {
			arr[n] = el.eq(n).find(title).outerHeight(true,true);
		}
		var max = getMaxValue(arr);
		el.find(title).outerHeight(max,true);
	}

}
function js_input_del(el, parent) {
	var input = el.closest(parent).find('input');
	input.val('');
	input.focus();
	el.hide();
}
function js_input_keyup(el, parent, del) {
	if (el.val() != '')
		el.closest(parent).find(del).show();
	else
		el.closest(parent).find(del).hide();
}

/*jquery mobile events*/
"use strict";!function(e){function t(){var e=s();e!==r&&(r=e,u.trigger("orientationchange"))}function a(t,a,o,n){var i=o.type;o.type=a,e.event.dispatch.call(t,o,n),o.type=i}e.attrFn=e.attrFn||{};var o=navigator.userAgent.toLowerCase(),n=o.indexOf("chrome")>-1&&(o.indexOf("windows")>-1||o.indexOf("macintosh")>-1||o.indexOf("linux")>-1)&&o.indexOf("mobile")<0&&o.indexOf("android")<0,i={tap_pixel_range:5,swipe_h_threshold:50,swipe_v_threshold:50,taphold_threshold:750,doubletap_int:500,touch_capable:"ontouchstart"in window&&!n,orientation_support:"orientation"in window&&"onorientationchange"in window,startevent:"ontouchstart"in window&&!n?"touchstart":"mousedown",endevent:"ontouchstart"in window&&!n?"touchend":"mouseup",moveevent:"ontouchstart"in window&&!n?"touchmove":"mousemove",tapevent:"ontouchstart"in window&&!n?"tap":"click",scrollevent:"ontouchstart"in window&&!n?"touchmove":"scroll",hold_timer:null,tap_timer:null};e.isTouchCapable=function(){return i.touch_capable},e.getStartEvent=function(){return i.startevent},e.getEndEvent=function(){return i.endevent},e.getMoveEvent=function(){return i.moveevent},e.getTapEvent=function(){return i.tapevent},e.getScrollEvent=function(){return i.scrollevent},e.each(["tapstart","tapend","tapmove","tap","tap2","tap3","tap4","singletap","doubletap","taphold","swipe","swipeup","swiperight","swipedown","swipeleft","swipeend","scrollstart","scrollend","orientationchange"],function(t,a){e.fn[a]=function(e){return e?this.on(a,e):this.trigger(a)},e.attrFn[a]=!0}),e.event.special.tapstart={setup:function(){var t=this,o=e(t);o.on(i.startevent,function n(e){if(o.data("callee",n),e.which&&1!==e.which)return!1;var c=e.originalEvent,s={position:{x:i.touch_capable?c.touches[0].screenX:e.screenX,y:i.touch_capable?c.touches[0].screenY:e.screenY},offset:{x:Math.round(i.touch_capable?c.changedTouches[0].pageX-o.offset().left:e.pageX-o.offset().left),y:Math.round(i.touch_capable?c.changedTouches[0].pageY-o.offset().top:e.pageY-o.offset().top)},time:Date.now(),target:e.target};return a(t,"tapstart",e,s),!0})},remove:function(){e(this).off(i.startevent,e(this).data.callee)}},e.event.special.tapmove={setup:function(){var t=this,o=e(t);o.on(i.moveevent,function n(e){o.data("callee",n);var c=e.originalEvent,s={position:{x:i.touch_capable?c.touches[0].screenX:e.screenX,y:i.touch_capable?c.touches[0].screenY:e.screenY},offset:{x:Math.round(i.touch_capable?c.changedTouches[0].pageX-o.offset().left:e.pageX-o.offset().left),y:Math.round(i.touch_capable?c.changedTouches[0].pageY-o.offset().top:e.pageY-o.offset().top)},time:Date.now(),target:e.target};return a(t,"tapmove",e,s),!0})},remove:function(){e(this).off(i.moveevent,e(this).data.callee)}},e.event.special.tapend={setup:function(){var t=this,o=e(t);o.on(i.endevent,function n(e){o.data("callee",n);var c=e.originalEvent,s={position:{x:i.touch_capable?c.changedTouches[0].screenX:e.screenX,y:i.touch_capable?c.changedTouches[0].screenY:e.screenY},offset:{x:Math.round(i.touch_capable?c.changedTouches[0].pageX-o.offset().left:e.pageX-o.offset().left),y:Math.round(i.touch_capable?c.changedTouches[0].pageY-o.offset().top:e.pageY-o.offset().top)},time:Date.now(),target:e.target};return a(t,"tapend",e,s),!0})},remove:function(){e(this).off(i.endevent,e(this).data.callee)}},e.event.special.taphold={setup:function(){var t,o=this,n=e(o),c={x:0,y:0},s=0,r=0;n.on(i.startevent,function p(e){if(e.which&&1!==e.which)return!1;n.data("tapheld",!1),t=e.target;var h=e.originalEvent,u=Date.now(),l={x:i.touch_capable?h.touches[0].screenX:e.screenX,y:i.touch_capable?h.touches[0].screenY:e.screenY},f={x:i.touch_capable?h.touches[0].pageX-h.touches[0].target.offsetLeft:e.offsetX,y:i.touch_capable?h.touches[0].pageY-h.touches[0].target.offsetTop:e.offsetY};return c.x=e.originalEvent.targetTouches?e.originalEvent.targetTouches[0].pageX:e.pageX,c.y=e.originalEvent.targetTouches?e.originalEvent.targetTouches[0].pageY:e.pageY,s=c.x,r=c.y,i.hold_timer=window.setTimeout(function(){var g=c.x-s,d=c.y-r;if(e.target==t&&(c.x==s&&c.y==r||g>=-i.tap_pixel_range&&g<=i.tap_pixel_range&&d>=-i.tap_pixel_range&&d<=i.tap_pixel_range)){n.data("tapheld",!0);var v=Date.now(),w={x:i.touch_capable?h.touches[0].screenX:e.screenX,y:i.touch_capable?h.touches[0].screenY:e.screenY},_={x:Math.round(i.touch_capable?h.changedTouches[0].pageX-n.offset().left:e.pageX-n.offset().left),y:Math.round(i.touch_capable?h.changedTouches[0].pageY-n.offset().top:e.pageY-n.offset().top)},T=v-u,x={startTime:u,endTime:v,startPosition:l,startOffset:f,endPosition:w,endOffset:_,duration:T,target:e.target};n.data("callee1",p),a(o,"taphold",e,x)}},i.taphold_threshold),!0}).on(i.endevent,function h(){n.data("callee2",h),n.data("tapheld",!1),window.clearTimeout(i.hold_timer)}).on(i.moveevent,function u(e){n.data("callee3",u),s=e.originalEvent.targetTouches?e.originalEvent.targetTouches[0].pageX:e.pageX,r=e.originalEvent.targetTouches?e.originalEvent.targetTouches[0].pageY:e.pageY})},remove:function(){e(this).off(i.startevent,e(this).data.callee1).off(i.endevent,e(this).data.callee2).off(i.moveevent,e(this).data.callee3)}},e.event.special.doubletap={setup:function(){var t,o,n,c,s=this,r=e(s),p=null,h=!1;r.on(i.startevent,function u(e){return e.which&&1!==e.which?!1:(r.data("doubletapped",!1),t=e.target,r.data("callee1",u),n=e.originalEvent,p||(p={position:{x:i.touch_capable?n.touches[0].screenX:e.screenX,y:i.touch_capable?n.touches[0].screenY:e.screenY},offset:{x:Math.round(i.touch_capable?n.changedTouches[0].pageX-r.offset().left:e.pageX-r.offset().left),y:Math.round(i.touch_capable?n.changedTouches[0].pageY-r.offset().top:e.pageY-r.offset().top)},time:Date.now(),target:e.target}),!0)}).on(i.endevent,function l(e){var u=Date.now(),f=r.data("lastTouch")||u+1,g=u-f;if(window.clearTimeout(o),r.data("callee2",l),g<i.doubletap_int&&e.target==t&&g>100){r.data("doubletapped",!0),window.clearTimeout(i.tap_timer);var d={position:{x:i.touch_capable?e.originalEvent.changedTouches[0].screenX:e.screenX,y:i.touch_capable?e.originalEvent.changedTouches[0].screenY:e.screenY},offset:{x:Math.round(i.touch_capable?n.changedTouches[0].pageX-r.offset().left:e.pageX-r.offset().left),y:Math.round(i.touch_capable?n.changedTouches[0].pageY-r.offset().top:e.pageY-r.offset().top)},time:Date.now(),target:e.target},v={firstTap:p,secondTap:d,interval:d.time-p.time};h||(a(s,"doubletap",e,v),p=null),h=!0,c=window.setTimeout(function(){h=!1},i.doubletap_int)}else r.data("lastTouch",u),o=window.setTimeout(function(){p=null,window.clearTimeout(o)},i.doubletap_int,[e]);r.data("lastTouch",u)})},remove:function(){e(this).off(i.startevent,e(this).data.callee1).off(i.endevent,e(this).data.callee2)}},e.event.special.singletap={setup:function(){var t=this,o=e(t),n=null,c=null,s={x:0,y:0};o.on(i.startevent,function r(e){return e.which&&1!==e.which?!1:(c=Date.now(),n=e.target,o.data("callee1",r),s.x=e.originalEvent.targetTouches?e.originalEvent.targetTouches[0].pageX:e.pageX,s.y=e.originalEvent.targetTouches?e.originalEvent.targetTouches[0].pageY:e.pageY,!0)}).on(i.endevent,function p(e){if(o.data("callee2",p),e.target==n){var r=e.originalEvent.changedTouches?e.originalEvent.changedTouches[0].pageX:e.pageX,h=e.originalEvent.changedTouches?e.originalEvent.changedTouches[0].pageY:e.pageY;i.tap_timer=window.setTimeout(function(){if(!o.data("doubletapped")&&!o.data("tapheld")&&s.x==r&&s.y==h){var n=e.originalEvent,p={position:{x:i.touch_capable?n.changedTouches[0].screenX:e.screenX,y:i.touch_capable?n.changedTouches[0].screenY:e.screenY},offset:{x:Math.round(i.touch_capable?n.changedTouches[0].pageX-o.offset().left:e.pageX-o.offset().left),y:Math.round(i.touch_capable?n.changedTouches[0].pageY-o.offset().top:e.pageY-o.offset().top)},time:Date.now(),target:e.target};p.time-c<i.taphold_threshold&&a(t,"singletap",e,p)}},i.doubletap_int)}})},remove:function(){e(this).off(i.startevent,e(this).data.callee1).off(i.endevent,e(this).data.callee2)}},e.event.special.tap={setup:function(){var t,o,n=this,c=e(n),s=!1,r=null,p={x:0,y:0};c.on(i.startevent,function h(e){return c.data("callee1",h),e.which&&1!==e.which?!1:(s=!0,p.x=e.originalEvent.targetTouches?e.originalEvent.targetTouches[0].pageX:e.pageX,p.y=e.originalEvent.targetTouches?e.originalEvent.targetTouches[0].pageY:e.pageY,t=Date.now(),r=e.target,o=e.originalEvent.targetTouches?e.originalEvent.targetTouches:[e],!0)}).on(i.endevent,function u(e){c.data("callee2",u);var h=e.originalEvent.targetTouches?e.originalEvent.changedTouches[0].pageX:e.pageX,l=e.originalEvent.targetTouches?e.originalEvent.changedTouches[0].pageY:e.pageY,f=p.x-h,g=p.y-l;if(r==e.target&&s&&Date.now()-t<i.taphold_threshold&&(p.x==h&&p.y==l||f>=-i.tap_pixel_range&&f<=i.tap_pixel_range&&g>=-i.tap_pixel_range&&g<=i.tap_pixel_range)){for(var d=e.originalEvent,v=[],w=0;w<o.length;w++){var _={position:{x:i.touch_capable?d.changedTouches[w].screenX:e.screenX,y:i.touch_capable?d.changedTouches[w].screenY:e.screenY},offset:{x:Math.round(i.touch_capable?d.changedTouches[w].pageX-c.offset().left:e.pageX-c.offset().left),y:Math.round(i.touch_capable?d.changedTouches[w].pageY-c.offset().top:e.pageY-c.offset().top)},time:Date.now(),target:e.target};v.push(_)}a(n,"tap",e,v)}})},remove:function(){e(this).off(i.startevent,e(this).data.callee1).off(i.endevent,e(this).data.callee2)}},e.event.special.swipe={setup:function(){function t(a){s=e(a.currentTarget),s.data("callee1",t),h.x=a.originalEvent.targetTouches?a.originalEvent.targetTouches[0].pageX:a.pageX,h.y=a.originalEvent.targetTouches?a.originalEvent.targetTouches[0].pageY:a.pageY,u.x=h.x,u.y=h.y,r=!0;var o=a.originalEvent;n={position:{x:i.touch_capable?o.touches[0].screenX:a.screenX,y:i.touch_capable?o.touches[0].screenY:a.screenY},offset:{x:Math.round(i.touch_capable?o.changedTouches[0].pageX-s.offset().left:a.pageX-s.offset().left),y:Math.round(i.touch_capable?o.changedTouches[0].pageY-s.offset().top:a.pageY-s.offset().top)},time:Date.now(),target:a.target}}function a(t){s=e(t.currentTarget),s.data("callee2",a),u.x=t.originalEvent.targetTouches?t.originalEvent.targetTouches[0].pageX:t.pageX,u.y=t.originalEvent.targetTouches?t.originalEvent.targetTouches[0].pageY:t.pageY;var o,c=s.parent().data("xthreshold")?s.parent().data("xthreshold"):s.data("xthreshold"),l=s.parent().data("ythreshold")?s.parent().data("ythreshold"):s.data("ythreshold"),f="undefined"!=typeof c&&c!==!1&&parseInt(c)?parseInt(c):i.swipe_h_threshold,g="undefined"!=typeof l&&l!==!1&&parseInt(l)?parseInt(l):i.swipe_v_threshold;if(h.y>u.y&&h.y-u.y>g&&(o="swipeup"),h.x<u.x&&u.x-h.x>f&&(o="swiperight"),h.y<u.y&&u.y-h.y>g&&(o="swipedown"),h.x>u.x&&h.x-u.x>f&&(o="swipeleft"),void 0!=o&&r){h.x=0,h.y=0,u.x=0,u.y=0,r=!1;var d=t.originalEvent,v={position:{x:i.touch_capable?d.touches[0].screenX:t.screenX,y:i.touch_capable?d.touches[0].screenY:t.screenY},offset:{x:Math.round(i.touch_capable?d.changedTouches[0].pageX-s.offset().left:t.pageX-s.offset().left),y:Math.round(i.touch_capable?d.changedTouches[0].pageY-s.offset().top:t.pageY-s.offset().top)},time:Date.now(),target:t.target},w=Math.abs(n.position.x-v.position.x),_=Math.abs(n.position.y-v.position.y),T={startEvnt:n,endEvnt:v,direction:o.replace("swipe",""),xAmount:w,yAmount:_,duration:v.time-n.time};p=!0,s.trigger("swipe",T).trigger(o,T)}}function o(t){s=e(t.currentTarget);var a="";if(s.data("callee3",o),p){var c=s.data("xthreshold"),h=s.data("ythreshold"),u="undefined"!=typeof c&&c!==!1&&parseInt(c)?parseInt(c):i.swipe_h_threshold,l="undefined"!=typeof h&&h!==!1&&parseInt(h)?parseInt(h):i.swipe_v_threshold,f=t.originalEvent,g={position:{x:i.touch_capable?f.changedTouches[0].screenX:t.screenX,y:i.touch_capable?f.changedTouches[0].screenY:t.screenY},offset:{x:Math.round(i.touch_capable?f.changedTouches[0].pageX-s.offset().left:t.pageX-s.offset().left),y:Math.round(i.touch_capable?f.changedTouches[0].pageY-s.offset().top:t.pageY-s.offset().top)},time:Date.now(),target:t.target};n.position.y>g.position.y&&n.position.y-g.position.y>l&&(a="swipeup"),n.position.x<g.position.x&&g.position.x-n.position.x>u&&(a="swiperight"),n.position.y<g.position.y&&g.position.y-n.position.y>l&&(a="swipedown"),n.position.x>g.position.x&&n.position.x-g.position.x>u&&(a="swipeleft");var d=Math.abs(n.position.x-g.position.x),v=Math.abs(n.position.y-g.position.y),w={startEvnt:n,endEvnt:g,direction:a.replace("swipe",""),xAmount:d,yAmount:v,duration:g.time-n.time};s.trigger("swipeend",w)}r=!1,p=!1}var n,c=this,s=e(c),r=!1,p=!1,h={x:0,y:0},u={x:0,y:0};s.on(i.startevent,t),s.on(i.moveevent,a),s.on(i.endevent,o)},remove:function(){e(this).off(i.startevent,e(this).data.callee1).off(i.moveevent,e(this).data.callee2).off(i.endevent,e(this).data.callee3)}},e.event.special.scrollstart={setup:function(){function t(e,t){o=t,a(c,o?"scrollstart":"scrollend",e)}var o,n,c=this,s=e(c);s.on(i.scrollevent,function r(e){s.data("callee",r),o||t(e,!0),clearTimeout(n),n=setTimeout(function(){t(e,!1)},50)})},remove:function(){e(this).off(i.scrollevent,e(this).data.callee)}};var c,s,r,p,h,u=e(window),l={0:!0,180:!0};if(i.orientation_support){var f=window.innerWidth||u.width(),g=window.innerHeight||u.height(),d=50;p=f>g&&f-g>d,h=l[window.orientation],(p&&h||!p&&!h)&&(l={"-90":!0,90:!0})}e.event.special.orientationchange=c={setup:function(){return i.orientation_support?!1:(r=s(),u.on("throttledresize",t),!0)},teardown:function(){return i.orientation_support?!1:(u.off("throttledresize",t),!0)},add:function(e){var t=e.handler;e.handler=function(e){return e.orientation=s(),t.apply(this,arguments)}}},e.event.special.orientationchange.orientation=s=function(){var e=!0,t=document.documentElement;return e=i.orientation_support?l[window.orientation]:t&&t.clientWidth/t.clientHeight<1.1,e?"portrait":"landscape"},e.event.special.throttledresize={setup:function(){e(this).on("resize",x)},teardown:function(){e(this).off("resize",x)}};var v,w,_,T=250,x=function(){w=Date.now(),_=w-y,_>=T?(y=w,e(this).trigger("throttledresize")):(v&&window.clearTimeout(v),v=window.setTimeout(t,T-_))},y=0;e.each({scrollend:"scrollstart",swipeup:"swipe",swiperight:"swipe",swipedown:"swipe",swipeleft:"swipe",swipeend:"swipe",tap2:"tap"},function(t,a){e.event.special[t]={setup:function(){e(this).on(a,e.noop)}}})}(jQuery);


function is_touch_device() {
	return (('ontouchstart' in window)
		|| (navigator.MaxTouchPoints > 0)
		|| (navigator.msMaxTouchPoints > 0));
}


function replaceSessid() {
// 	$('.js-need-sessid [name="sessid"]').value(BX.bitrix_sessid());
}

$(document).ready(function() {
	replaceSessid();
	footerToBottom();
	var countElementHit = 6;
	$('body').on('click', '.js-more-product', function() {
		$(this).addClass('loading');
		countElementHit = countElementHit + 6;
		$.ajax({
			url: "/ajax/more_item.php?countElementHit="+countElementHit,
			method: 'POST',
			success: function(html){
				$('#hit-products-main').html(html);
				equalizeHeight($('.block_items .prod_item'), '.item-equalize');
				equalizeHeight($('.block_items .prod_item:not(.big_item)'), '.name');
				$(this).removeClass('loading');
			}
		});
	});
	equalizeHeight($('.block_ite .prod_item'), '.item-equalize');
	equalizeHeight($('.block_items .prod_item:not(.big_item)'), '.name');
	equalizeHeight($('.block_items .prod_item:not(.big_item)'), '.price_bl');
	if (device.mobile()) {
		$('.prod_item .basket_button,.prod_item .wish_item').css({'opacity' : '1', 'visibility' : 'visible'});
	}

	$('body').on('click', '.js_input_del', function() {
		js_input_del($(this), '.input');
	});
	$('body').on('keyup', 'input', function() {
		js_input_keyup($(this), '.input', '.js_input_del');
	});
	$('*[data-event="jqm"]').live('click', function(e){
		$("body").append("<span class='"+$(this).data('name')+"' style='display:none;'></span>");
		jqmEd($(this).data('name'), $(this).data('param-id'), '.' + $(this).data('param-id'), '', this);
		$("body ."+$(this).data('name')).click();
	});
	$(document).click(function(e){
		var container = $('.bx-ui-sls-container');
		if(container.is(e.target) || container.has(e.target).length || $('.js_city_change').is(e.target) || $('.ORDER_PROP_6_val_div').is(e.target)){
		}else{
			var time = 400,
				delay = 450;
			$(this).find('.js_city_value').delay(delay).fadeIn(time);
			$(this).find('.js_city_change').delay(delay).fadeIn(time);
			$(this).find('.city_change_input').delay(delay).hide();
		}
	});
	$('body').on('click', '.js_city_change', function() {
		var time = 400;
		$(this).closest('.input').find('.js_city_change').hide();
		$(this).closest('.input').find('.js_city_value').hide();
		$(this).closest('.input').find('.city_change_input').fadeIn(time);
	});

	equalizeHeight($('.sale_order_table.paysystem .ps_logo'), '.input_radio');

	$(document).on('submit', '.js-feedback-form', function (e) {
		setPreloader(true);
		e.preventDefault();

		var $form = $(this);
		var name = $form.attr("name");
		var ajaxUrl = $form.data('ajax');
		var formData = $form.serialize() + '&web_form_submit=Y';

		$.ajax({
			type: 'post',
			method: 'post',
			data: formData,
			url: ajaxUrl,
			success: function (data) {
				if (data) {
					var $data = $(data);
					$.fancybox($data.find('.js-feedback-form-msg'), {
						maxWidth: '95%',
						minWidth: '240px',
						padding: [20, 20, 20, 20],
						autoCenter: false,
						fixToView: true,
						overlay: {
							locked: true
						}
					});
					
					if (name == "restaurateur") {
    					ga('send', 'event', 'Рестораторам', 'Отправка формы');
					}
					else if (name == "b2b") {
    					ga('send', 'event', 'B2B', 'Отправка формы');
					}
					else if (name == "design") {
    					ga('send', 'event', 'Дизайнерам', 'Отправка формы');
					}

					setTimeout(function() {
						$.fancybox.close();
					}, 1500);

					setTimeout(function() {
						window.location = '';
					}, 1600);
				}
			},
			complete: function() {
				setPreloader(false);
			}
		});

		return;
	});



	$(document).on('submit', '.js-job-form', function (e) {
		setPreloader(true);
		e.preventDefault();

		var ajaxUrl = $(this).data('ajax');

		var data = new FormData(document.querySelector(".js-job-form"));

		data.append('web_form_submit', 'Y');

		$.ajax({
			type: 'post',
			method: 'post',
			data: data,
			url: ajaxUrl,
			processData: false,
			contentType: false,
			success: function (data) {
				if (data) {
					var $data = $(data);
					$.fancybox($data.find('.js-feedback-form-msg'), {
						maxWidth: '95%',
						minWidth: '240px',
						padding: [20, 20, 20, 20],
						autoCenter: false,
						fixToView: true,
						overlay: {
							locked: true
						}
					});

					setTimeout(function() {
						$.fancybox.close();
					}, 1500);

					setTimeout(function() {
						window.location = '';
					}, 1600);
				}
			},
			complete: function() {
				setPreloader(false);
			}
		});

		return;
	});


	if ($('.js-question-form')) {

		$('.js-question-form').validate({
			ignore: [],
			rules: {
				form_text_7: "required",
				form_text_8: "required",
				form_text_9: "required",
				form_textarea_10: "required",
			},

			messages: {
				form_text_7: "Введите Ваше имя",
				form_text_8: {
					required: "Введите Ваш контактный телефон",
					form_text_8: "Пожайлуста, введите номер полностью"
				},
				form_text_9: {
					required: "Введите Ваш email",
					form_text_9: "Некорректный email"
				},
				form_textarea_10: "Введите Ваш вопрос"
			},

			focusInvalid: false,

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
			}
		});
	}

	if ($('.js-reviews-form')) {

		$('.js-reviews-form').validate({
			ignore: [],

			rules: {
				'new_review[AUTHOR_NAME]': "required",
				'new_review[AUTHOR_EMAIL]': "required"
			},

			messages: {
				'new_review[AUTHOR_NAME]': "Введите Ваше имя",
				'new_review[AUTHOR_EMAIL]': {
					required: "Введите Ваш email",
					'new_review[AUTHOR_EMAIL]': "Некорректный email"
				}
			},

			focusInvalid: false,

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
			}
		});

		var $ratingBtn = $('.js-reviews-checkbox');
		var $allLabel = $('.reviews-check').find('label');

		$ratingBtn.on('change', function(evt) {
			var $label = $(evt.target).closest('label');
			$allLabel.removeClass('yellow');

			$label
				.addClass('yellow')
				.prevAll()
				.addClass('yellow');
		});
	}

	$(document).on('change', '.js-same-cbox input[type="checkbox"]', function (e) {
		$('.js-same-cbox--content').toggleClass('hidden');
	});

	$('body').on('click', '.js-change-reserve-date', function(e) {
		e.preventDefault();
		var data = $(this).data();
		$.ajax({
			url: data.ajaxUrl,
			method: 'POST',
			type: 'POST',
			data: data.request,
			dataType: 'json',
			success: function(response){
				$.fancybox(response.message, {
					maxWidth: '95%',
					minWidth: '320px',
					padding: [45, 45, 0, 20],
					fixToView: false
				});

				setTimeout(function() {
					$.fancybox.close();
				}, 2500);
			}
		});
	});

	function getBonusInfo() {
		$.ajax({
			url: requestBonusInfo.url,
			method: 'POST',
			type: 'POST',
			data: requestBonusInfo.data,
			dataType: 'json',
			success: function(response){
				$('.js-bonus-balance').text('На Вашем счету ' + response.Kare + ' бонусов');
				$('.js-bonus-payment').attr('data-max', response.Kare);
			}
		});
	}
	if (window.requestBonusInfo !== undefined) {
		getBonusInfo();
	}

	$('body').on('submit', '.js-bonus-payment', function(e) {
		setPreloader(true);
		e.preventDefault();
		var $this = $(this);
		var url = $this.data('ajax-url');
		var data = $this.serialize();
		var maxValue = $(this).data('max');
		data += '&maxValue=' + maxValue;

		$.ajax({
			url: url,
			method: 'POST',
			type: 'POST',
			data: data,
			dataType: 'json',
			success: function(response){
				$.fancybox(response.message, {
					maxWidth: '95%',
					minWidth: '320px',
					padding: [45, 45, 0, 20],
					fixToView: false
				});

				setTimeout(function() {
					$.fancybox.close();
				}, 3000);
				if (response.result) {
					window.location = '';
				}
			},
			complete: function() {
				setPreloader(false);
			}
		});
	});

	$('.js-radio-toggle').on('change', onTradeInChanged);

	function onTradeInChanged(evt) {
		var $radio = $(evt.currentTarget);
		var $target = $($radio.data('target'));
		var action = $radio.data('action');


		if (action === 'open') {
			$target.css({ display: 'inline-block' });
		} else {
			$target.css({ display: 'none' });
		}
	}

	$('.js-radio-friendly').on('click', setFriendlyDirection);

	function setFriendlyDirection(evt) {
		//evt.preventDefault();

		//var $radio = $(evt.currentTarget);
		// var ajaxUrl = $radio.data('ajax-url');
		// var data = {
		// 	'value': $radio.val(),
		// 	'id': $radio.data('id'),
		// 	'prop-id': $radio.data('prop-id'),
		// 	'uid' : $radio.data('uid')
		// };
        //
		// $.ajax({
		// 	url: ajaxUrl,
		// 	method: 'POST',
		// 	type: 'POST',
		// 	data: data,
		// 	dataType: 'json',
		// 	success: function (response) {
		// 		if (response.status) {
		// 			$radio.prop('checked', true);
		// 		}
		// 	}
		// });
        $(this).prop('checked', true);
	}

	$(document).on('click', '.js-pass-request', function (e) {
		e.preventDefault();
		var $currentBtn = $(e.currentTarget);
		var $link = $currentBtn.attr('href');
		var $currentAuthForm = $currentBtn.closest('form');
		var $login = $currentAuthForm.find('input[type="tel"]').val();
		var $sendPassMsg = $('.js-send-pass-msg');

		if($login.length == 11) { // 11 is length of any phone number
			$.ajax({
				url: $link,
				data: {
					login:$login
				},
				method: 'post',
				type: 'post',
				success: function(message){
					message = JSON.parse(message);

					var counter;

					if(message.status === false) {
						$sendPassMsg.text(message.message);

						if (message.hasOwnProperty('seconds')){
							counter = message.seconds;

							$sendPassMsg.text('Пароль придет в течение ' + counter + ' секунд');
							$('.js-pass-request').hide();

							var interval = setInterval(function() {
								counter--;
								$sendPassMsg.text('Пароль придет в течение ' + counter + ' секунд');
								if (counter == 0) {
									$sendPassMsg.text('Если sms еще не пришло, повторите операцию');
									$('.js-pass-request').text('Выслать пароль повторно').show();
									clearInterval(interval);
								}
							}, 1000);
						}
					} else {
						var time = new Date();
						time = time.getTime();

						var limit = time + 60000;
						localStorage.setItem('limit', limit);

						$('.js-pass-request').hide();
						$sendPassMsg.show();

						counter = 60;
						$sendPassMsg.text('Пароль придет в течение ' + counter + ' секунд');

						var interval = setInterval(function() {
							counter--;
							$sendPassMsg.text('Пароль придет в течение ' + counter + ' секунд');
							if (counter == 0) {
								$sendPassMsg.text('Если sms еще не пришло, повторите операцию');
								$('.js-pass-request').text('Выслать пароль повторно').show();
								clearInterval(interval);
								localStorage.removeItem('limit');
							}
						}, 1000);
					}
				}
			});
		} else {
			$sendPassMsg.text('Заполните поле Телефон');
			$currentAuthForm.find('input[type="tel"]').focus();
		}

	});


	$(document).on('click', '.js-auth-request', function (e) {
		e.preventDefault();
		setPreloader(true);
		var link = $(this).attr('href');
		var curPhone = $(this).closest('form').find('input[type="tel"]').val();

		$.ajax({
			url: link,
			method: 'get',
			type: 'get',
			success: function (responseData) {
				var $data = responseData;
				$.fancybox($data, {
					maxWidth: '95%',
					minWidth: '240px',
					padding: [20, 20, 20, 20],
					fixToView: true,
					arrows : false,
					overlay: {
						locked: true
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

				$('.js-forgotpasswd').find('input[type="tel"]').val(curPhone);

				$("form#registraion-page-form").validate({
					rules: {
						'REGISTER[EMAIL]': "email"
					}
				});
			},
			complete: function () {
				setPreloader(false);
			}
		});
	});

	$(document).on('submit', '.js-auth-block', function (e) {
		e.preventDefault();

		if($('#recall_personal_data_reg').is(':checked')){

			setPreloader(true);

			var $form = $(this);
			var $oldFormBlock = $('.js-registration');

			$.ajax({
				url: $form.attr('action'),
				method: 'post',
				type: 'post',
				data: $form.serialize(),
				success: function (responseData) {
					try {
						var jsonResponse = JSON.parse(responseData);

						$.fancybox('<h3>' + jsonResponse.message + '</h3>');
						setTimeout(function() {
							$.fancybox.close();

							if (jsonResponse.result == 'Y') {
								window.location = '';
							}
						}, 1500);

					} catch (e) {
						var $html = $(responseData);
						var $newFormBlock = $html.find('.js-registration');

						$oldFormBlock.children().remove();
						$oldFormBlock.append($newFormBlock.children());
						$form = $oldFormBlock.find('.js-auth-block');

						if ($html.length) {
							$.fancybox($html.html());
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

					}
				},
				complete: function () {
					setPreloader(false);
				}
			});

		} else {
			$('#registraion-page-form .js-personal-data').addClass('error');
			$('#registraion-page-form .js-personal-data').after('<label for="recall_personal_data" generated="true" class="error">Обязательное поле</label>');
		}
	});

	$('body').on('submit', '.js-form', function(e) {
		setPreloader(true);
		e.preventDefault();
		var $this = $(this);
		var url = $this.data('ajax');
		var name = $this.attr('name');
		var data = $this.serialize();

		$.ajax({
			url: url,
			method: 'POST',
			type: 'POST',
			data: data,
			success: function(response) {
    			if (name == "CALLBACK") {
        			ga('send', 'event', 'Callback', 'Отправка формы');
    			}
				$.fancybox(response);

				setTimeout(function() {
					$.fancybox.close();
					window.location = '';
				}, 3000);
			},
			complete: function() {
				setPreloader(false);
			}
		});
	});

	$(document).on('click', '.js-more-news', function (e) {
		e.preventDefault();
		var $this = $(this);
		var url = $this.attr('href');
		$.ajax({
			url: url,
			success: function (response) {
				var $response = $(response);
				var $newNewsList = $response.find('.js-news-list').children();
				var $newBtn = $response.closest('.js-more-block');
				var $newsList = $('.js-news-list');
				$newNewsList.each(function (e) {
					$newsList.append(this);
				});
				$('.js-more-block').replaceWith($newBtn);
			}
		})
	});


	$(document).on('click', '.js-gallery-slider-more', function (e) {
		e.preventDefault();
		$(this).closest('.js-slider-wrap').find('.js-gallery-slide').css('display', 'block');
		$(this).remove();
	});

});

$(document).on('submit', '.js-personal-change-form', function (e) {
	setPreloader(true);
	e.preventDefault();

	var $form = $(this);
	var ajaxUrl = $form.data('ajax');
	var formData = $form.serialize() + '&web_form_submit=Y';

	$.ajax({
		type: 'post',
		method: 'post',
		data: formData,
		url: ajaxUrl,
		success: function (data) {
			$.fancybox($(data).find('.js-form-msg'), {
				maxWidth: '95%',
				minWidth: '240px',
				padding: [20, 20, 20, 20],
				autoCenter: false,
				fixToView: true,
				overlay: {
					locked: true
				}
			});

			setTimeout(function() {
				$.fancybox.close();
			}, 1500);

			setTimeout(function() {
				window.location = '';
			}, 1600);
		},
		complete: function() {
			setPreloader(false);
		}
	});

	return;
});

$(document).on('submit', '.js-change-pass-form', function (e) {
		setPreloader(true);
		e.preventDefault();

		var $form = $(this);
		var ajaxUrl = $form.data('ajax');
		var formData = $form.serialize() + '&web_form_submit=Y';

		$.ajax({
			type: 'post',
			method: 'post',
			data: formData,
			url: ajaxUrl,
			success: function (data) {
				var $data = $(data);
				$.fancybox($data.find('.js-form-msg'), {
					maxWidth: '95%',
					minWidth: '240px',
					padding: [20, 20, 20, 20],
					autoCenter: false,
					fixToView: true,
					overlay: {
						locked: true
					}
				});

				setTimeout(function() {
					$.fancybox.close();
				}, 1600);

				setTimeout(function() {
					window.location = '';
				}, 1700);
			},
			complete: function() {
				setPreloader(false);
			}
		});

		return;
	});

	$(document).on('click', '.js-sms-confirm', function(e) {
		e.preventDefault();
		var $this = $(this);
		$this.attr('disabled', true);

		var $url = $this.data('ajaxUrl');
		var $sendSmsMsg = $('.js-sms-confirm-msg');
		var $checkSmsMsg = $('.js-sms-check-msg');
		var $login = $('#ORDER_PROP_3').val();
		var $codeField = $('.js-sms-confirm-input');
		var $code;
		var $type = 'send';
		var $data = {
			login: $login,
			type: $type,
		};

		$.ajax({
			url: $url,
			method: 'POST',
			type: 'POST',
			data: $data,
			success: function(message) {
				message = JSON.parse(message);
				var counter;

				if(message.status === false) {
					$sendSmsMsg.text(message.message);
					$this.attr('disabled', false);

					if (message.hasOwnProperty('seconds')) {
						counter = message.seconds;

						$sendSmsMsg.text('Следующий запрос можно повторить через ' + counter + ' секунд');
						$this.attr('disabled', true);

						var interval = setInterval(function() {
							counter--;
							$sendSmsMsg.text('Следующий запрос можно повторить через ' + counter + ' секунд');
							if (counter == 0) {
								$sendSmsMsg.text('');
								clearInterval(interval);
								$('.js-sms-confirm').attr('disabled', false);
							}
						}, 1000);
					}
				} else {
					var time = new Date();
					time = +time.getTime();

					var limit = time + 60000;
					var difference = Math.round((limit - time)/1000);
					localStorage.setItem('limit2', limit);

					counter = difference;
					$sendSmsMsg.text('Следующий запрос можно повторить через ' + counter + ' секунд');

					var interval = setInterval(function() {
							counter--;
							$sendSmsMsg.text('Следующий запрос можно повторить через ' + counter + ' секунд');
							if (counter == 0) {
									$sendSmsMsg.text('');
									clearInterval(interval);
									localStorage.removeItem('limit2');
									$this.attr('disabled', false);
							}
					}, 1000);
				}
			}
		});
	});

	$(document).on('keyup', '.js-sms-confirm-input', function() {
		var $codeField = $('.js-sms-confirm-input');
		var $code =$codeField.val();
		if ($code.length != 4) {
			return true;
		}
		var $this = $(this);
		var $url = $('.js-sms-confirm').data('ajaxUrl');
		var $sendSmsMsg = $('.js-sms-confirm-msg');
		var $checkSmsMsg = $('.js-sms-check-msg');
		var $login = $('#ORDER_PROP_3').val();
		var $type = 'check';
		var $data = {
				code: $code,
				type: $type,
			}

			$.ajax({
				url: $url,
				method: 'POST',
				type: 'POST',
				data: $data,
				success: function(message) {
				message = JSON.parse(message);

				if(message.status === false) {
					$checkSmsMsg.show();
					$checkSmsMsg.text(message.message);
					$codeField.addClass('is-invalid').val("");
				} else {
					$checkSmsMsg.hide();
					$codeField.removeClass('is-invalid');
					$codeField.addClass('is-valid').attr("disabled", true);
				}
			}
			});
		});


$(window).resize(function() {
	footerToBottom();
	equalizeHeight($('.block_items .prod_item'), '.item-equalize');
	equalizeHeight($('.block_items .prod_item:not(.big_item)'), '.name');
	equalizeHeight($('.block_items .prod_item:not(.big_item)'), '.price_bl');
	equalizeHeight($('.sale_order_table.paysystem .ps_logo'), '.input_radio');
});

function debounce (func, wait, immediate) {
    var timeout;
    return function () {
        var context = this, args = arguments;
        var later = function () {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        var callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
}

function setResizeDebounce(func, wait, immediate){
    wait = wait || 250;
    immediate = immediate || false;
    var thisD = this;
    var debouncedFunc = thisD.debounce(func, wait, immediate);
    $(window).resize(debouncedFunc);
}

jQuery(function(){

    // чтобы в иОС можно было скрывать выпадающее при клике в пустое место
    if (navigator.userAgent.match(/iPhone|iPad|iPod/i) != null)
        $('body').css('cursor', 'pointer');


    // показать меню при клике по кнопке
    $('.js-menu-catalog-button').click(function(){
        var $this = $(this);
        var $parent = $this.closest('.menu-catalog');
        var $menu = $parent.find('.menu-catalog__list');

        if ($menu.hasClass('_shown'))
        {
            $menu.removeClass('_shown');
        }
        else
        {
            $menu.addClass('_shown');
        }

    });

    // скрыть меню при клике по крестику
    $('.js-menu-catalog-close').click(function(){
        var $this = $(this);
        var $menu = $this.closest('.menu-catalog__list');

        $menu.removeClass('_shown');
    });
    
    
    // скрыть меню при клике вне его
    $(document).click(function(e){
        var $target = $(e.target);

        if ( $target.is('.js-menu-catalog-button') || ($target.closest('.js-menu-catalog-button').length > 0) )
            return;

        if ( $target.is('.js-menu-catalog-close') || ($target.closest('.js-menu-catalog-close').length > 0) )
            return;

        if ( $target.is('.menu-catalog__list') || ($target.closest('.menu-catalog__list').length > 0) )
            return;

        $('.menu-catalog__list').removeClass('_shown');
    });

    // раскрывающееся меню каталога на мобильном разрешении
    $('.js-menu-catalog-show-dropdown').click(function(e){

        if (window.innerWidth > 1023)
            return;

        e.preventDefault();
        var $this = $(this);
        var $parent = $this.closest('.menu-catalog__list-item');
        var $dropdown = $parent.find('.menu-catalog__dropdown');

        if ($dropdown.hasClass('_shown'))
        {
            $this.removeClass('_active');
            $dropdown.slideUp(300, function(){
                $dropdown.removeClass('_shown');
            });
        }
        else
        {
            $this.addClass('_active');
            $dropdown.slideDown(300, function(){
                $dropdown.addClass('_shown');
            });
        }
    });
    // скрыть меню при клике по стрелке
    $('.menu-catalog_button').click(function(){
        $(this).parents('.menu-catalog__list-item').find('.menu-catalog__dropdown').slideUp();  
        $(this).parents('.menu-catalog__list-item').find('.menu-catalog__dropdown').removeClass('_shown');
    });


    // раскрывающееся верхнее меню (черное) на мобильном разрешении
    $('.js-menu-top-show-dropdown').click(function(e){

        if (window.innerWidth > 767)
            return;

        e.preventDefault();
        var $this = $(this);
        var $parent = $this.closest('.menu-top__list-item');
        var $dropdown = $parent.find('.menu-top__dropdown');

        if ($dropdown.hasClass('_shown'))
        {
            $this.removeClass('_active');
            $dropdown.slideUp(300, function(){
                $dropdown.removeClass('_shown');
            });
        }
        else
        {
            $this.addClass('_active');
            $dropdown.slideDown(300, function(){
                $dropdown.addClass('_shown');
            });
        }
    });

    // подчистить всякое при смене разрешения
    function onResizeHandler(){

        // переместить див с верхним меню в зависимости от разрешения
        var $headerTop = $('.header__top');
        var $menuCatalog = $('.menu-catalog__top-menu');
        var $header = $('.header');

        if (window.innerWidth <= 767)
        {
			if($menuCatalog.find('.header__top').length === 0) {
				$menuCatalog.append($headerTop);
			}
        }
        else
        {
            $header.prepend($headerTop);
        }


        // убрать классы и стили, проставленные скриптами на разршениях, где они не нужны
        if (window.innerWidth > 767)
        {
            $('.menu-top__dropdown').removeClass('_shown').removeAttr('style');
            $('.js-menu-top-show-dropdown').removeClass('_active');

            $('.menu-catalog__dropdown').removeClass('_shown').removeAttr('style');
            $('.js-menu-catalog-show-dropdown').removeClass('_active');
        }

        if (window.innerWidth > 1500)
        {
            $('.menu-catalog__list').removeClass('_shown');
        }
    }

    // при ресайзе и при первом запуске
    setResizeDebounce(onResizeHandler);
    onResizeHandler();

});