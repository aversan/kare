import './modules/common';
import './modules/index';

$(() => {
  const $sections = $('.js-catalog-filter').find('.catalog-filter__section_collapse');
  $sections.each(function () {
    const $section = $(this);
    const $title = $section.find('.catalog-filter__section-header');
    const $content = $section.find('.catalog-filter__section-body.collapse');

    $title.on('click', () => {
      if ($section.hasClass('catalog-filter__section_collapse_show')) {
        $content.collapse('hide');
        $section.removeClass('catalog-filter__section_collapse_show');
      } else {
        $content.collapse('show');
        $section.addClass('catalog-filter__section_collapse_show');
      }
    });
  });
});

