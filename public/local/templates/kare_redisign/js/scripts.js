'use strict';

var navMain = document.querySelector(".page-header__nav-container");
var navCatalog = document.querySelector(".catalog-nav__wrapper");
var mainNavBtn = document.querySelector(".nav-btn");
var catalogNavBtn = document.querySelector(".catalog-nav__btn");
var loginBtn = document.querySelector(".js-login-btn");
var loginPopup = document.querySelector(".user-block__popup");
var allPopup = document.querySelectorAll(".js-popup");
var closeBtn = document.querySelectorAll(".js-close-btn");
var callbackPopup = document.querySelector(".top-callback");
var callbackBtn = document.querySelector(".js-callback-btn");
var icon = document.querySelector(".icon");
var searchBtn = document.querySelector(".js-search-btn");
var searchField = document.querySelector(".js-search");
var mainNavMenu = document.querySelector(".main-nav__list");
var mainNavItem = document.querySelectorAll(".main-nav__item.has-children");
var showMapBtn = document.querySelector('.js-show-map-btn');
var mobileMapPopup = document.querySelector('.showroom-map-popup');
var mql = window.matchMedia("(min-width: 768px)");
var callBackWrap = $('.js-block-wrap');

if(mainNavBtn !== null) {
	mainNavBtn.addEventListener("click", function(evt) {
		navCatalog.classList.remove("js-closed");
		if (navMain.classList.contains("js-closed")) {
			navMain.classList.remove("js-closed");
			navMain.classList.add("js-opened");
			mainNavBtn.classList.add("opened");
		} else {
			navMain.classList.add("js-closed");
			navMain.classList.remove("js-opened");
			mainNavBtn.classList.remove("opened");
		}
	});
}

if(catalogNavBtn !== null) {
	catalogNavBtn.addEventListener("click", function () {
		if (navCatalog.classList.contains("js-closed")) {
			navCatalog.classList.remove("js-closed");
			navCatalog.classList.add("js-opened");
		} else {
			navCatalog.classList.add("js-closed");
			navCatalog.classList.remove("js-opened");
		}
	});
}

if (loginBtn) {
	loginBtn.addEventListener("click", function() {
		if (loginPopup.classList.contains("js-closed")) {
			loginPopup.classList.remove("js-closed");
			loginPopup.classList.add("js-opened");
			loginBtn.classList.add("active");
			setSmsCounter(true);

		} else {
			loginPopup.classList.add("js-closed");
			loginPopup.classList.remove("js-opened");
			loginBtn.classList.remove("active");
		}
	});
}

callbackBtn.addEventListener("click", function() {
	if (callbackPopup.classList.contains("js-closed")) {
		callbackPopup.classList.remove("js-closed");
		callbackPopup.classList.add("js-opened");
		callbackBtn.classList.add("active");
	} else {
		callbackPopup.classList.add("js-closed");
		callbackPopup.classList.remove("js-opened");
		callbackBtn.classList.remove("active");
	}
});

for (var i = 0; i < closeBtn.length; i++) {
	closeBtn[i].addEventListener("click", function() {
		if (loginPopup) {
			if (loginPopup.classList.contains("js-opened")) {
				loginPopup.classList.remove("js-opened");
				loginPopup.classList.add("js-closed");
				loginBtn.classList.remove("active");
			}
		};

		if (callbackPopup.classList.contains("js-opened")) {
			callbackPopup.classList.remove("js-opened");
			callbackPopup.classList.add("js-closed");
			callbackBtn.classList.remove("active");
		};

		if (searchField.classList.contains("js-opened")) {
			searchField.classList.remove("js-opened");
			searchField.classList.add("js-closed");
			searchBtn.classList.remove("active");
		}

		if($('.showroom-map-popup').length) {
			if (mobileMapPopup.classList.contains("js-opened")) {
				mobileMapPopup.classList.remove("js-opened");
			}
		}

	});
}

$(document).mouseup(function (e){
	if(loginPopup && loginPopup.classList.contains('js-opened')) {
		if (!$('.user-block').is(e.target) && $(e.target).closest('.user-block').length == 0) {
			loginPopup.classList.remove("js-opened");
			loginPopup.classList.add("js-closed");
			loginBtn.classList.remove("active");
		}
	}
	if(callbackPopup.classList.contains('js-opened')) {
		if (!callBackWrap.is(e.target) && $(e.target).closest('.js-block-wrap').length == 0) {
			callbackPopup.classList.remove("js-opened");
			callbackPopup.classList.add("js-closed");
			callbackBtn.classList.remove("active");
		}
	}
	if(searchField.classList.contains('js-opened')) {
		if ($(e.target).closest('.search-input-wrap').length == 0 && $(e.target).closest('.js-header-search').length == 0) {
			searchField.classList.remove("js-opened");
			searchField.classList.add("js-closed");
			searchBtn.classList.remove("active");
		}
	}
});


window.addEventListener("keydown", function(event) {
	if (event.keyCode === 27) {
		if (loginPopup) {
			if (loginPopup.classList.contains("js-opened")) {
			loginPopup.classList.remove("js-opened");
			loginPopup.classList.add("js-closed");
			loginBtn.classList.remove("active");
			}
		};

		if (callbackPopup.classList.contains("js-opened")) {
			callbackPopup.classList.remove("js-opened");
			callbackPopup.classList.add("js-closed");
			callbackBtn.classList.remove("active");
		};

		if (searchField.classList.contains("js-opened")) {
			searchField.classList.remove("js-opened");
			searchField.classList.add("js-closed");
			searchBtn.classList.remove("active");
		}

		if (mobileMapPopup.classList.contains("js-opened")) {
			mobileMapPopup.classList.remove("js-opened");
		}
	}
});

searchBtn.addEventListener("click", function(event) {
	if (this.classList.contains('active') ) {
		return true;
	}

	event.preventDefault();

	if (searchField.classList.contains("js-closed")) {
		searchField.classList.remove("js-closed");
		searchField.classList.add("js-opened");
		searchBtn.classList.add("active");
	}

});

setSmsCounter(false);

function setSmsCounter(head) {

	var $currentBtn = $('.js-pass-request');
	if($currentBtn.length) {
		var limit = localStorage.getItem('limit');

		if (limit !== null) {
			var now = new Date();
			now = +now.getTime();
			limit = +limit;

			var difference = Math.round((limit - now)/1000);

			if (difference <= 0) {
				localStorage.removeItem('limit');
			} else {

				$currentBtn.hide();

				var counter = difference;

				if(head === false) {
					var $sendPassMsg = $('.js-send-pass-msg');
				} else {
					var $sendPassMsg = $('.js-auth-form-top .js-send-pass-msg');
				}

				$sendPassMsg.text('Пароль придет в течение ' + counter + ' секунд');

				var interval = setInterval(function() {
					counter--;
					$sendPassMsg.text('Пароль придет в течение ' + counter + ' секунд');
					if (counter == 0) {
						$sendPassMsg.text('Если sms еще не пришло, повторите операцию');
						$currentBtn.text('Выслать пароль повторно').show();
						clearInterval(interval);
						localStorage.removeItem('limit');
					}
				}, 1000);
			}
		}
	}
}

setSmsCounter2();

function setSmsCounter2() {
	var $currentBtn = $('.js-sms-confirm');
	if($currentBtn.length) {
		var limit = localStorage.getItem('limit2');

		if (limit !== null) {
			var now = new Date();
			now = +now.getTime();
			limit = +limit;

			var difference = Math.round((limit - now)/1000);

			if (difference <= 0) {
				localStorage.removeItem('limit2');
			} else {
				$currentBtn.attr('disabled', true);

				var counter = difference;

				var $sendSmsMsg = $('.js-sms-confirm-msg');

				$sendSmsMsg.text('Пароль придет в течение ' + counter + ' секунд');

				var interval = setInterval(function() {
					counter--;
					$sendSmsMsg.text('Пароль придет в течение ' + counter + ' секунд');
					if (counter == 0) {
						$sendSmsMsg.text('');
						$currentBtn.attr('disabled', false);
						clearInterval(interval);
						localStorage.removeItem('limit2');
					}
				}, 1000);
			}
		}
	}
}

var subscribeBtn = document.querySelector(".btn_subscribe");

function changeSubscribeBtn() {
	if (window.innerWidth < 768) {
	subscribeBtn.innerHTML='Подписаться';
	} else if (window.innerWidth >= 1200) {
		subscribeBtn.innerHTML='Подписаться';
	} else {
		subscribeBtn.innerHTML='Ок';
	}
}

for (var i = 0; i < mainNavItem.length; i++) {
	mainNavItem[i].addEventListener("click", function(evt) {
		var currentTarget = evt.currentTarget;
		if (window.innerWidth < 1200) {
			$(currentTarget).toggleClass('active');
			$(this).find('.main-nav__sublist').slideToggle().toggleClass('active');
		}
	});
}
$(window).on('resize', function(){
	if (window.innerWidth > 1200) {
		$('.nav-btn').removeClass('opened');
		$('.main-nav__sublist').removeClass('active').removeAttr('style');
	}
});

if (subscribeBtn) {
	changeSubscribeBtn();
}

var showDesign = document.querySelector(".design-arrow");
var design = document.querySelector(".design__right");

if (showDesign) {
	showDesign.addEventListener("click", function() {
		if (design.classList.contains("is-open")) {
			design.classList.remove("is-open");
		} else {
			design.classList.add("is-open");
		}
	});
}

function newCatalogSlickActive() {
	var $newSlider = $('.js-slick-new');
	if (window.innerWidth < 768) {
		if (!$newSlider.hasClass('slick-initialized')) {
			$($newSlider).slick({
				prevArrow: $('.js-slick-new-prev'),
				nextArrow: $('.js-slick-new-next'),
				dots: false,
				infinite: false
			});
		}
	} else {
		if ($newSlider.hasClass('slick-initialized')) {
			$newSlider.slick('unslick');
		}
	}
}

// hitCatalogSlickActive();
newCatalogSlickActive();
showroomGallery();

var $showroomAbout = $('.js-mobile-showroom-about');
var $showroomAboutMore = $('.js-showroom-about-more');

function showroomAboutMobile() {
	if (window.innerWidth < 768) {
		if($showroomAbout) {
			if ($showroomAbout.height() < 620) {
				$showroomAboutMore.hide();
				$showroomAbout.trigger("destroy");
			} else {
				if (!$showroomAbout.hasClass('js-hidden')) {
					$showroomAbout.addClass('js-hidden');
				}
				$showroomAbout.dotdotdot();
				$showroomAboutMore.show();
				$showroomAboutMore.text('Читать далее');
			}
		}
	}
}

if($showroomAboutMore) {
	$showroomAboutMore.on("click", function() {
			if ($showroomAbout.hasClass('js-hidden')) {
				$showroomAbout.removeClass('js-hidden');
				$showroomAbout.trigger('destroy');
				$showroomAboutMore.text('Свернуть');
			} else {
				$showroomAbout.addClass('js-hidden');
				$showroomAbout.dotdotdot();
				$showroomAboutMore.text('Читать далее');
			}
		});
}

showroomAboutMobile();

if(showMapBtn) {
	showMapBtn.addEventListener("click", function() {
		$.fancybox($(mobileMapPopup).find('#showroom-detail-map'), {
			maxWidth  : 767,
			maxHeight : 280,
			fitToView : false,
			width   : '100%',
			height    : '100%',
			autoSize  : false,
			autoCenter: false,
			closeClick  : false,
			openEffect  : 'none',
			closeEffect : 'none',
			afterShow: function () {
				var center = map.getCenter();
				google.maps.event.trigger(map, 'resize');
				map.setCenter(center);
			}
		});
	});
}
var littleMap = document.getElementById('showroom-detail-map');

function showroomMapMobile() {
	if (littleMap) {
		if (window.innerWidth >= 768) {
			$.fancybox.close($(mobileMapPopup));
			littleMap.style.display = 'block';
		}
	}
}

var $showroomImageBtn = $('.js-showroom-gallery-fancybox');

if($showroomImageBtn.length) {
	$showroomImageBtn.on('click', function(e) {
		e.preventDefault();

		if ($(window).width() < 1200) {
			return false;
		} else {
			$showroomImageBtn.fancybox({
				fitToView: false,
				width: '60%',
				height: 'auto',
				autoSize: false,
				autoCenter: true,
				closeClick: false,
				openEffect: 'elastic',
				closeEffect: 'none'
			});
		}
	});
}

function showroomFancyboxGallery() {
		if (window.innerWidth < 1200) {
			$.fancybox.close();
		}
}

function showroomGalleryMore() {
	if (window.innerWidth >= 1200) {
		var $showroomMorePhoto = $('.js-showroom-gallery-more-btn');
		if ($showroomMorePhoto) {
			var $showroomPhoto = $(".js-showroom-gallery-list li").size();
			var x = 3;
			$('.js-showroom-gallery-list li:lt('+ x +')').show();
			$showroomMorePhoto.click(function () {
				x = (x + 3 <= $showroomPhoto) ? x + 3 : $showroomPhoto;
				$('.js-showroom-gallery-list li:lt('+ x +')').show();
				if(x + 3 >= $showroomPhoto) {
						$showroomMorePhoto.hide();
				}
			});
		}
	}
}
showroomGalleryMore();

function windowResize() {
	if (subscribeBtn) {
		changeSubscribeBtn();
	}

	if (window.innerWidth >= 1200) {
		if(mainNavItem!==null) {
			for (var i = 0; i < mainNavItem.length; i++) {
				if (mainNavItem[i].classList.contains('is-active')) {
					mainNavItem[i].classList.remove('is-active');
					mainNavMenu.classList.remove('is-hidden');
				}
			}
		}
		if(navMain!==null) {
			if (navMain.classList.contains('js-opened')) {
				navMain.classList.add("js-closed");
				navMain.classList.remove("js-opened");
			}
		}
		if(navCatalog!==null) {
			navCatalog.classList.remove("js-opened");
			navCatalog.classList.add("js-closed");
		}
	}

	newCatalogSlickActive();
	showroomGallery();
	showroomAboutMobile();
	showroomMapMobile();
	//showroomFancyboxGallery();
	showroomGalleryMore();
}

window.addEventListener('resize', windowResize);

$('.js-city-select').selectize({
	dropdownParent: 'body',
	onChange: function (value) {
		var goToShowRoom = $('.js-go-to-showroom');
		if (goToShowRoom.length) {
			goToShowRoom.attr('href', value);
		} else {
			window.location = value;
		}
	}
});
$('.js-jobs-select').selectize({
	dropdownParent: 'body',
	onChange: function (value) {
		if (value == '-') {
			window.location = window.location.pathname;
		} else {
			var queryStr = $.param({
				'city': value
			})
			window.location = window.location.pathname + '?' + queryStr;
		}
	}
});

// form validation //

$.validator.addMethod('minlenghtphone', function (value, element) {
	return value.replace(/\D+/g, '').length > 9;
}, ' Пожалуйста, введите номер полностью');

$.validator.addMethod('requiredphone', function (value, element) {
	return value.replace(/\D+/g, '').length >= 1;
}, ' Введите Ваш контактный телефон');

$.validator.addClassRules('js-phone', {
	requiredphone: true,
	minlenghtphone: true
});

$.validator.methods.email = function (value, elem) {
		return this.optional(elem) || (/\w+@[a-zA-Z0-9-]+?\.[a-zA-Z]{2,6}$/).test(value);
	};

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

$('.js-feedback-form').validate({
	ignore: [],
	rules: {
		form_text_11: "required",
		form_text_12: "required",
		form_textarea_14: "required"
	},

	messages: {
		form_text_11: "Введите Ваше имя",
		form_text_12: {
			required: "Введите Ваш контактный телефон",
			form_text_12: "Пожалуйста, введите номер полностью"
		},
		form_textarea_14: "Введите Ваш вопрос"
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
$('.js-inner-form').validate({
	ignore: [],
	rules: {
		form_text_11: "required",
		form_text_12: "required",
		form_textarea_14: "required"
	},

	messages: {
		form_text_11: "Введите Ваше имя",
		form_text_12: {
			required: "Введите Ваш контактный телефон",
			form_text_12: "Пожалуйста, введите номер полностью"
		},
		form_textarea_14: "Введите Ваш вопрос"
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
$('.js-auth-validate').validate({
	ignore: [],
	rules: {
		USER_LOGIN: "required",
		USER_PASSWORD: "required"
	},

	messages: {
		USER_PASSWORD: "Введите пароль",
		USER_LOGIN: {
			required: "Введите Ваш контактный телефон",
			USER_LOGIN: "Пожалуйста, введите номер полностью"
		}
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
$('.js-auth-form-top').validate({
	ignore: [],
	rules: {
		USER_LOGIN: "required",
		USER_PASSWORD: "required"
	},

	messages: {
		USER_PASSWORD: "Введите пароль",
		USER_LOGIN: {
			required: "Введите Ваш контактный телефон",
			USER_LOGIN: "Пожалуйста, введите номер полностью"
		}
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
$('.js-call-me-back').validate({
	ignore: [],
	rules: {
		form_text_37: "required",
		form_field_38: "required",
		form_field_41: {
			required: true,
			"email": true
		},
		form_field_helper: {
			required: true
		}
	},
	focusInvalid: false,

	messages: {
		form_text_37: "Введите Ваше имя",
		form_field_38: {
			required: "Введите Ваш контактный телефон",
			form_field_38: "Пожалуйста, введите номер полностью"
		},
		form_text_41: {
			required: "Введите Ваш email",
			form_text_41: "Некорректный email"
		},
		form_field_helper: "Прикрепите резюме"
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
$('.subscribe__form').validate({
	ignore: [],
	rules: {
		sf_EMAIL: "required"
	},

	messages: {
		sf_EMAIL: {
			required: "Введите Ваш email",
			sf_EMAIL: "Некорректный email"
		}
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
	},
	submitHandler: function(form) {
		var $input = $('.subscribe__input');
		var email = $input.val();
		var data = {'email': email};
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
});
$('.js-job-form').validate({
	ignore: [],
	rules: {
		form_text_37: "required",
		form_field_38: "required",
		form_field_41: {
			required: true,
			"email": true
		},
		form_field_helper: {
			required: true
		}
	},
	focusInvalid: false,

	messages: {
		form_text_37: "Введите Ваше имя",
		form_field_38: {
			required: "Введите Ваш контактный телефон",
			form_field_38: "Пожалуйста, введите номер полностью"
		},
		form_text_41: {
			required: "Введите Ваш email",
			form_text_41: "Некорректный email"
		},
		form_field_helper: "Прикрепите резюме"
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

$('.js-personal-change-form').validate({
	ignore: [],

	messages: {
		NAME: "Введите Ваше имя",
		PERSONAL_PHONE: {
			required: "Введите Ваш контактный телефон",
			PERSONAL_PHONE: "Пожалуйста, введите номер полностью"
		},
		EMAIL: {
			required: "Введите Ваш email",
			EMAIL: "Некорректный email"
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
// end of form validation //

var $productSlider = $('.js-product-detail-slider');
var $productSliderNav = $('.js-product-detail-slider-nav');

if ($productSlider.length && $productSliderNav.length) {

	$productSlider.slick({
		slidesToShow: 1,
		slidesToScroll: 1,
		arrows: false,
		fade: true,
		asNavFor: '.js-product-detail-slider-nav'
	});
	$productSliderNav.slick({
		slidesToShow: 3,
		slidesToScroll: 1,
		prevArrow: $('.js-product-detail-prev'),
		nextArrow: $('.js-product-detail-next'),
		dots: false,
		asNavFor: '.js-product-detail-slider',
		focusOnSelect: true
	});
}

// if (productSlider) {
// 	productSlider.addEventListener("click", function() {
// 		$('.product-detail-slider__link').fancybox({
// 				fitToView: false,
// 				width: '60%',
// 				height: 'auto',
// 				autoSize: false,
// 				autoCenter: true,
// 				closeClick: false,
// 				openEffect: 'elastic',
// 				closeEffect: 'none'
// 			});
// 	});
// }


(function () {
	var changeOrientation;
	var PageNav = function($elem) {
		this.$main    = $elem;
		this.$list    = this.$main.find('.js-pagenav-list');
		this.$moreBtn = this.$main.find('.js-pagenav-more-btn');
		this.$moreBtnPrimary = this.$moreBtn;

		this.COUNT_ITEMS = {
			DESKTOP: $elem.data('desktop-items-count'),
			MOBILE: $elem.data('mobile-items-count'),
		};
		this.MOBILE_WIDTH = this.$main.data('mobile-width');

		this.itemsCount = this.$list.children().length;
		this.itemsCache = [];
	};

	PageNav.DELAY = 600;

	var changing;

	PageNav.prototype.init = function(argument) {
		this.setItems(mql);
		this.bindEvents();
		changing = this.isMobile();
	};

	PageNav.prototype.bindEvents = function() {
		var self = this;
		var timeoutId = null;

		$(window).on('resize', function (argument) {
			clearTimeout(timeoutId);
			timeoutId = setTimeout(function() {
				if (changing != self.isMobile()) {
					changing = self.isMobile();

					window.location.reload();
				}

			}, PageNav.DELAY);
		});


		this.$main.on('click', '.js-pagenav-more-btn', this.onMoreBtnClicked.bind(this));
	};

	PageNav.prototype.setItems = function (breakpoint) {
		var self = this;

		if (this.$moreBtn.hasClass('last') && self.itemsCount <= self.COUNT_ITEMS.MOBILE) {
			this.$moreBtn.addClass('hidden');
		}

		if (!this.isMobile()) {
			if (self.itemsCount > self.COUNT_ITEMS.DESKTOP) {
				self.$list.children().each(function (index, elem) {
					if (index < self.COUNT_ITEMS.DESKTOP) {
						return;
					}

					$(elem).remove();
				});
			} else if (self.itemsCount < self.COUNT_ITEMS.DESKTOP) {
				self.showMoreItems(self.COUNT_ITEMS.DESKTOP - self.itemsCount)
			}
		} else {
			if (self.itemsCount > self.COUNT_ITEMS.MOBILE) {
				self.$list.children().each(function(index, elem) {
					if (index < self.COUNT_ITEMS.MOBILE) {
						return;
					}

					self.itemsCache.push(elem);
					$(elem).remove();

				});
			}
		}

		self.itemsCount = self.$list.children().size();
	};

	PageNav.prototype.onMoreBtnClicked = function() {
		if (this.itemsCache.length) {
			if (this.$moreBtn.hasClass('last')) {
				this.showMoreItems(this.COUNT_ITEMS.MOBILE);

				if (this.itemsCache.length === 0) {
					this.$moreBtn.addClass('hidden');
				}
			} else {
				this.showMoreItems(this.COUNT_ITEMS.MOBILE);
			}
		} else {
			this.$moreBtn.remove();
			this.sendRequestForGetItems();
		}
	};

	PageNav.prototype.showMoreItems = function (count) {
		if(count === undefined) {
			if (this.isMobile()) {
				count = this.COUNT_ITEMS.MOBILE;
			} else {
				count = this.COUNT_ITEMS.DESKTOP;
			}
		}

		this.$list.append(this.itemsCache.splice(0, count));
		this.itemsCount = this.$list.children().size();
	};

	PageNav.prototype.sendRequestForGetItems = function() {
		setPreloader(true);
		$.ajax({
			url: this.$moreBtn.data('url'),
			data: this.$moreBtn.data('ajax-data'),
			method: 'GET',
			success: this.parseNewItems.bind(this),
			complete: function() {
				setPreloader(false);
			}
		});
	};

	PageNav.prototype.parseNewItems = function(data) {
		var self = this;

		var $html = $(data);
		var $newItems = $html.find('.js-pagenav-list').children();
		var $newMoreBtn = $html.find('.js-pagenav-more-btn');

		this.$moreBtn = $newMoreBtn;
		this.$main.append($newMoreBtn);

		$newItems.each(function (index, elem) {
			self.itemsCache.push(elem);
		});

		self.showMoreItems();
	};

	PageNav.prototype.isMobile = function() {
		return $(window).width() < this.MOBILE_WIDTH;
	};

	return window.PageNav = PageNav;
})();

(function ($pagenav) {
	if ($pagenav.length === 0) {
		return false;
	}

	return (new window.PageNav($pagenav)).init();
})($('.js-pagenav'));

if ('.js-product-gallery') {
	$('.js-product-gallery').each(function() {
		var $el = $(this);

		var $prodSlider = $el.find('.js-catalog-product-slider');
		var $prodPrev = $el.find('.js-catalog-prev');
		var $prodNext = $el.find('.js-catalog-next');

		$prodSlider.slick({
			infinite: true,
			slidesToShow: 4,
			slidesToScroll: 4,
			dots: false,
			prevArrow: $prodPrev,
			nextArrow: $prodNext,
			responsive: [
				{
					breakpoint: 1199,
					settings: {
						slidesToShow: 2,
						slidesToScroll: 2
					}
				},
				{
					breakpoint: 767,
						settings: {
							slidesToShow: 1,
							slidesToScroll: 1
						}
					}
				]
		});
	});
}


// слайдеры товаров на главной

	// слайдер новинок

	if ($('.js-slick-new')) {
		var $newSlider = $('.js-slick-new');
		var $catalogNavNew = $('.js-catalog-new-nav');
		var newSliderCache = $newSlider.find('.js-product');
		var newSliderNavCache = $catalogNavNew.children();

		mql.addListener(newSliderTypeChange)
	}

	function newSliderTypeChange(breakpoint) {
		if (breakpoint.matches) {
				if ($newSlider.hasClass('slick-initialized')) {
					$newSlider.slick('unslick');
					$newSlider.children().remove();
					$newSlider.append(newSliderCache);
					$catalogNavNew.children().remove();
					$catalogNavNew.append(newSliderNavCache);
				}


		} else {
			if ($newSlider.hasClass('slick-initialized')) {
				$newSlider.slick('unslick');
			}
			$newSlider.children().remove();
			$newSlider.append(newSliderCache);
			$catalogNavNew.children().remove();
			$catalogNavNew.append(newSliderNavCache);

			$($newSlider).slick({
				prevArrow: $('.js-slick-new-prev'),
				nextArrow: $('.js-slick-new-next'),
				dots: false,
				infinite: true
			});
		}
	}

	newSliderTypeChange(mql);
	newSliderTypeChange(mql);

	// слайдер хитов
if ($('.js-slick-hit')) {
		var $hitSlider = $('.js-slick-hit');
		var $catalogNavHit = $('.js-catalog-hit-nav');
		var hitSliderCache = $hitSlider.find('.js-product');
		var hitSliderNavCache = $catalogNavHit.children();

		mql.addListener(hitSliderTypeChange)
	}

	function hitSliderTypeChange(breakpoint) {
		if (breakpoint.matches) {
				if ($hitSlider.hasClass('slick-initialized')) {
					$hitSlider.slick('unslick');
					$hitSlider.children().remove();
					$hitSlider.append(hitSliderCache);
					$catalogNavHit.children().remove();
					$catalogNavHit.append(hitSliderNavCache);
				}

		} else {
			if ($hitSlider.hasClass('slick-initialized')) {
				$hitSlider.slick('unslick');
			}
			$hitSlider.children().remove();
			$hitSlider.append(hitSliderCache);
			$catalogNavHit.children().remove();
			$catalogNavHit.append(hitSliderNavCache);

			$($hitSlider).slick({
				prevArrow: $('.js-slick-hit-prev'),
				nextArrow: $('.js-slick-hit-next'),
				dots: false,
				infinite: true
			});
		}
	}

	hitSliderTypeChange(mql);


	document.addEventListener('touchstart', function addtouchclass(e){ // first time user touches the screen
		document.documentElement.classList.add('can-touch') // add "can-touch" class to document root using classList API
		document.removeEventListener('touchstart', addtouchclass, false) // de-register touchstart event
}, false);

	var $numericInput = $('.js-numeric-input');

	if($numericInput) {
		$numericInput.on('change', function () {
			if ($numericInput.val() < 1) {
				$numericInput.attr('value', 1);
			}
		});
	}

function showMoreGallery($gallery, $count) {
	var $galleryList = $gallery.find('.js-gallery-list');
	var $galleryItem = $galleryList.find('.js-gallery-item').size();
	var $galleryMore = $gallery.find('.js-gallery-more');
	var x = $count;
	$gallery.find('.js-gallery-list .js-gallery-item:lt('+ x +')').show();
	$galleryMore.on('click', function (e) {
		e.preventDefault();
		x = (x + $count <= $galleryItem) ? x + $count : $galleryItem;
		$gallery.find('.js-gallery-list .js-gallery-item:lt('+ x +')').show();
		if(x >= $galleryItem) {
				$galleryMore.hide();
		}
	});
}

(function ($elem) {
	if (!$elem.length) {
		return false;
	}

	showMoreGallery($elem, 6)

})($('.js-interior-gallery'));

(function ($elem) {
	if (!$elem.length) {
		return false;
	}
	showMoreGallery($elem, 6);

})($('.js-interior-items-gallery'));

$(document).on('click', '.js-show-more-stores', function (e) {
	var $this = $(this);
	var $btnText = $this.find('.js-more-btn-text');
	var $storesList = $this.closest('.js-stores').find('.js-stores-list');
	var $storesHiddenItem = $storesList.find('.js-stores-hidden');

		if($storesHiddenItem.hasClass('hidden')) {
			$storesHiddenItem.removeClass('hidden');
			$btnText.text('Свернуть');
			$this.addClass('is-opened');
		} else {
			$storesHiddenItem.addClass('hidden');
			$btnText.text('Другие склады');
			$this.removeClass('is-opened');
		}

});

$(document).on('click', '.js-to-basket', function (e) {
	e.preventDefault();
	var $this = $(this);
	var $currentInBasketBtn =  $this.closest('.js-catalog-buttons').find('.js-in-basket-btn');
	var $currentToBasketBtn =  $this.closest('.js-catalog-buttons').find('.js-to-basket-btn');
	var $currentToWishBtn =  $this.closest('.js-catalog-buttons').find('.js-to-wish-btn');

	var $itemAmountBlock = $this.closest('.js-catalog-buttons').find('.js-amount-block');
	var $itemAmount = +($this.closest('.js-catalog-buttons').find('.js-amount').val());
	if ($itemAmount) {
		$this.data('quantity', $itemAmount);
	}

	var $buttonType = $this.data('type');
	var type = ($buttonType === 'wish') ? 'wish_item' : 'add_item';
	var data = {
			'quantity': $this.data('quantity'),
		}
		data[type] = $this.data('item');


	$.ajax({
		url: $this.attr('href'),
		method: 'post',
		type: 'post',
		data: data,
		success: function (data) {
			var $popupResultInfo = $(data).data('result');
			var $popupHeaderInfo = $(data).find('.js-basket-popup').data('header-info');

			if(($buttonType === 'basket') && ($popupResultInfo == true)) {
				$.fancybox(data, {
					maxWidth: '1330px',
					minWidth: '900px',
					maxHeight: '862px',
					width: '100%',
					height: 'auto',
					padding: 0,
					autoSize: false,
					autoCenter: true,
					fitToView: true,
					overlay: {
						locked: true
					},
					closeBtn: false,
				});

				$(document).on('click', '.js-close-popup', function (e) {
					e.preventDefault();
					$.fancybox.close();
				});
			}

			if(($buttonType === 'basket') && ($popupResultInfo == true)) {
				$this.addClass('hidden');
				$currentInBasketBtn.removeClass('hidden').addClass('added');
				if ($currentToWishBtn.hasClass('added')) {
					$currentToWishBtn.removeClass('added');
				}
				if($itemAmountBlock) {
					$itemAmountBlock.addClass('hidden');
				}
			}

			if(($buttonType === 'wish') && ($popupResultInfo == true)) {
				if ($currentToBasketBtn.hasClass('hidden')) {
					$currentToBasketBtn.removeClass('hidden');
				}
				if (!$currentInBasketBtn.hasClass('hidden')) {
					$currentInBasketBtn.addClass('hidden').removeClass('added');
				}
				$this.toggleClass('added');

				if($itemAmountBlock && $itemAmountBlock.hasClass('hidden')) {
					$itemAmountBlock.removeClass('hidden');
				}
			}

			if ($popupResultInfo == true) {
				$('.js-top-cart-num').text($popupHeaderInfo.basket);
				$('.js-top-fav-num').text($popupHeaderInfo.wish);
			}
		}
	});
});

$(document).on('click', '.js-popup-delete-item', function (evt) {
	evt.preventDefault();
	var $self = $(this);
	var $currentItem = $self.data('item');

	$.ajax({
		url: $self.attr('href'),
		method: 'get',
		type: 'get',
		success: function (response) {
			var $oldBasketPopup = $self.closest('.js-basket-popup');
			$oldBasketPopup.html($(response).find('.js-basket-popup').html());
			var $popupHeaderInfo = $(response).find('.js-basket-popup').data('header-info');
			$('.js-header-basket-count').text($popupHeaderInfo.basket);
			$('.js-header-wish-count').text($popupHeaderInfo.wish);
			$('.fancybox-inner').css('height', 'auto');
			$('.fancybox-wrap').css('height', 'auto');
			$('.js-in-basket-btn[data-item=' + $currentItem + ']').removeClass('added').addClass('hidden');
			$('.js-to-basket-btn[data-item=' + $currentItem + ']').removeClass('hidden');
		}
	});
});




$(function(){
	lazyBackground();
	$(window).on('scroll resize', function(){
		lazyBackground();
	});

	function lazyBackground() {
		if ($('.js-lazy-back').length) {
			$('.js-lazy-back').each(function () {
				var c = $(this).offset();
				var range = $(window).height();
				if(c.top - $(window).scrollTop() < range) {
					var back = $(this).attr('data-background');
					$(this).attr('style', 'background-image:url(' + back + ')');
				}

			});
		}
	}

	$('.js-main-actions-slider').slick({
		slidesToShow: 4,
		slidesToScroll: 1,
		lazyLoad: 'progressive',
		responsive: [
			{
				breakpoint: 1200,
				settings: {
					slidesToShow: 3
				}
			},
			{
				breakpoint: 1024,
				settings: {
					slidesToShow: 2,
					slidesToScroll: 2
				}
			},
			{
				breakpoint: 768,
				settings: {
					slidesToShow: 1
				}
			}
		]
	});

	$('.js-catalog-btn').on('click', function(){
		$(this).closest('.header__catalog-menu').toggleClass('open');
	});
});