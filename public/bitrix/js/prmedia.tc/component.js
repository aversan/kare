;(function (window, $, core) {
  var eventPrefix = core.d.eventPrefix + '.component';
  var ajaxRoot = core.d.ajaxRoot + '/component';
  var urls = {
    getItems: ajaxRoot + '/get_items.php',
    getCount: ajaxRoot + '/get_count.php',
    getPage: ajaxRoot + '/get_page.php',
    getItemPageNumber: ajaxRoot + '/get_item_page_number.php',
  };

  var component = {};
  component.getItems = function (fields) {
    fields = fields || {};
    $.post(urls.getItems, fields, function (data) {
      core.$.document.trigger(eventPrefix + '.afterGetItems', data);
    }, 'json');
  };
  component.getCount = function (fields) {
    $.post(urls.getCount, fields, function (data) {
      core.$.document.trigger(eventPrefix + '.afterGetCount', data);
    }, 'json');
  };
  component.getPage = function (fields) {
    fields = fields || {};
    $.post(urls.getPage, fields, function (data) {
      core.$.document.trigger(eventPrefix + '.afterGetPage', data);
    }, 'json');
  };
  component.lastPage = function (data) {
    data = data || {};
    core.$.document.trigger(eventPrefix + '.afterLastPage', data);
  };
  component.getItemPageNumber = function (fields, callback) {
    fields = fields || {};
    callback = callback || function () {};
    $.post(urls.getItemPageNumber, fields, function (data) {
      callback(data)
    }, 'json');
  };

  core.component = component;
}) (window, window.jQuery, window.prmedia.tc);
