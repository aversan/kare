import $ from 'jquery';

export default class ProductDetailSlider {
  constructor(el) {
    this.el = el;

    $(this.el).each(function () {
      const $el = $(this);

      $el.click((e) => {
        const fieldName = $(this).attr('data-field');
        const type = $(this).attr('data-type');
        const input = $(`input[name='${fieldName}']`);
        const currentVal = parseInt(input.val(), 10);

        e.preventDefault();

        if (!Number.isNaN(currentVal)) {
          if (type === 'minus') {
            if (currentVal > input.attr('min')) {
              input.val(currentVal - 1).change();
            }
            if (parseInt(input.val(), 10) === input.attr('min')) {
              $(this).attr('disabled', true);
            }
          } else if (type === 'plus') {
            if (currentVal < input.attr('max')) {
              input.val(currentVal + 1).change();
            }
            if (parseInt(input.val(), 10) === input.attr('max')) {
              $(this).attr('disabled', true);
            }
          }
        } else {
          input.val(0);
        }
      });

      $el.focusin(() => {
        $(this).data('old-value', $(this).val());
      });

      $el.change(() => {
        const minValue = parseInt($(this).attr('min'), 10);
        const maxValue = parseInt($(this).attr('max'), 10);
        const valueCurrent = parseInt($(this).val(), 10);
        const name = $(this).attr('name');

        if (valueCurrent >= minValue) {
          $(`.btn-number[data-type='minus'][data-field='${name}']`).removeAttr('disabled');
        } else {
          $(this).val($(this).data('old-value'));
        }
        if (valueCurrent <= maxValue) {
          $(`.btn-number[data-type='plus'][data-field='${name}']`).removeAttr('disabled');
        } else {
          $(this).val($(this).data('old-value'));
        }
      });

      $el.keydown((e) => {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
          // Allow: Ctrl+A
          (e.keyCode === 65 && e.ctrlKey === true) ||
          // Allow: home, end, left, right
          (e.keyCode >= 35 && e.keyCode <= 39)) {
          // let it happen, don't do anything
          return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
          e.preventDefault();
        }
      });
    });
  }
}

