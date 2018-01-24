import $ from 'jquery';

export default class CartItem {
  constructor(el) {
    this.el = el;

    $(this.el).each(function () {
      const $item = $(this);
      const $del = $item.find('.js-del');
      const $revert = $item.find('.js-revert');
      const $area = $item.find('.js-area');

      $del.on('click', (e) => {
        e.preventDefault();
        if (!$item.hasClass('is-deleted')) {
          $item.addClass('is-deleted');
          $area.addClass('show');
        }
      });

      $revert.on('click', (e) => {
        e.preventDefault();
        if ($item.hasClass('is-deleted')) {
          $item.removeClass('is-deleted');
          $area.removeClass('show');
        }
      });
    });
  }
}

