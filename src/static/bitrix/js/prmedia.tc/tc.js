;(function (window, $, BX, p) {
  p = p || {};
  var core = {
    $: {
      window: $(window),
      document: $(window.document)
    },
    d: {
      eventPrefix: 'prmedia.tc',
      ajaxRoot: '/bitrix/js/prmedia.tc/ajax',
    },
    onready: function (callback) {
      if (window.frameRequestStart === true) {
        BX.addCustomEvent('onFrameDataReceived', callback);
      } else {
        $(callback);
      }
    }
  };
  p.tc = core;
  window.prmedia = p;
}) (window, window.jQuery, window.BX, window.prmedia);
