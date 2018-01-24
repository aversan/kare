;(function (window, $, core) {
  var eventPrefix = core.d.eventPrefix + '.component.verifierReCaptcha';

  window.prmediaTcComponentVerifierReCaptchaReady = function () {
    core.$.document.trigger(eventPrefix + '.afterReady');
  };
}) (window, window.jQuery, window.prmedia.tc);
