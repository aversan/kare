import $ from 'jquery';
import Swiper from 'swiper/dist/js/swiper.js';

export default class ProductSlider {
  constructor(el) {
    this.el = el;

    $(this.el).each(function () {
      const $wrapper = $(this);
      const $swiperContainer = $wrapper.find('.js-swiper-container');
      const $prev = $wrapper.find('.js-swiper-prev');
      const $next = $wrapper.find('.js-swiper-next');

      const swiper = new Swiper($swiperContainer, {
        navigation: {
          prevEl: $prev,
          nextEl: $next,
        },
        slidesPerView: 'auto',
        spaceBetween: 0,
        autoHeight: true,
        observer: true,
        observeParents: true,
        breakpoints: {
          768: {
            grabCursor: true,
            freeMode: true,
          },
        },
      });
    });
  }
}
