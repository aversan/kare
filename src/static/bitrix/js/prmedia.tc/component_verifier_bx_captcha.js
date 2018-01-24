;(function (window, $, core) {
  var eventPrefix = core.d.eventPrefix + '.component.verifierBxCaptcha';
  var ajaxRoot = core.d.ajaxRoot + '/component/verifier_bx_captcha';
  var urls = {
    reload: ajaxRoot + '/reload.php'
  };

  var verifierBxCaptcha = {};
  verifierBxCaptcha.reload = function (fields) {
    fields = fields || {};
    $.post(urls.reload, fields, function (data) {
      core.$.document.trigger(eventPrefix + '.afterReload', data);
    }, 'json');
  };

  core.component.verifierBxCaptcha = verifierBxCaptcha;
}) (window, window.jQuery, window.prmedia.tc);
