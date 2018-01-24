;(function (window, $, core) {
  var eventPrefix = core.d.eventPrefix + '.user';
  var ajaxRoot = core.d.ajaxRoot + '/user';
  var urls = {
    verify: ajaxRoot + '/verify.php'
  };

  var user = {};
  user.verify = function (fields) {
    fields = fields || {};
    $.post(urls.verify, fields, function (data) {
      core.$.document.trigger(eventPrefix + '.afterVerified', data);
    }, 'json');
  };

  core.user = user;
}) (window, window.jQuery, window.prmedia.tc);
