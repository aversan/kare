;(function (window, $, core) {
  var eventPrefix = core.d.eventPrefix + '.comment';
  var ajaxRoot = core.d.ajaxRoot + '/comment';
  var urls = {
    add: ajaxRoot + '/add.php',
    update: ajaxRoot + '/update.php',
    get: ajaxRoot + '/get.php',
    vote: ajaxRoot + '/vote.php'
  };

  var comment = {};
  comment.add = function (fields) {
    fields = fields || {};
    $.post(urls.add, fields, function (data) {
      core.$.document.trigger(eventPrefix + '.afterAdd', data);
    }, 'json');
  };
  comment.update = function (id, fields) {
    id = parseInt(id);
    if (isNaN(id) || id <= 0) {
      return;
    }
    fields = fields || {};
    fields.id = id;
    $.post(urls.update, fields, function (data) {
      core.$.document.trigger(eventPrefix + '.afterUpdate', data);
    }, 'json');
  };
  comment.get = function (params) {
    params = params || {};
    $.get(urls.get, params, function (data) {
      core.$.document.trigger(eventPrefix + '.afterGet', data);
    }, 'json');
  };
  comment.vote = function (id, type) {
    id = parseInt(id) || 0;
    type = type || '';
    if (id <= 0 || !type.length) {
      return;
    }
    var fields = {
      id: id,
      type: type
    };
    $.post(urls.vote, fields, function (data) {
      core.$.document.trigger(eventPrefix + '.afterVote', data);
    }, 'json');
  };

  core.comment = comment;
}) (window, window.jQuery, window.prmedia.tc);
