;(function (window, $) {
  var core = {
    window: $(window),
    document: $(window.document),
    debug: false,
    component: {},
    onready: function (callback) {
      if (window.frameRequestStart === true) {
        BX.addCustomEvent('onFrameDataReceived', callback);
      } else {
        $(callback);
      }
    },
    triggerEvent: function (eventName, data) {
      if (core.debug) {
        console.info('Trigger', '`' + eventName + '`');
        console.info(data);
      }
      core.document.trigger(eventName, data);
    },
    send: function (data) {
      var method = data.method,
        namespace = data.namespace || '',
        fields = data.fields || {},
        type = data.type || 'get',
        eventName = 'prmedia.tc.' + namespace + '.after' + method.charAt(0).toUpperCase() + method.slice(1);
      if ($.isPlainObject(fields)) {
        fields.method = method;
        fields.namespace = namespace;
      } else {
        fields += '&method=' + method + '&namespace=' + namespace;
      }

      $.ajax({
        url: '/bitrix/js/prmedia.tc/api/',
        type: type,
        data: fields,
        dataType: 'json',
        success: function (data) {
          core.triggerEvent(eventName, data || {});
        },
        error: function () {
          core.triggerEvent(eventName, {
            status: 'error',
            errors: {
              general: [
                'Service responded with error'
              ]
            }
          });
        }
      });
    },
    initApi: function (api) {
      for (namespace in api) {
        core[namespace] = core[namespace] || {};
        var types = api[namespace];
        for (var type in types) {
          for (var i in types[type]) {
            var method = types[type][i];
            core[namespace][method] = (function (namespace, method, type) {
              return function (fields) {
                core.send({
                  method: method,
                  namespace: namespace,
                  type: type,
                  fields: fields
                });
              }
            }) (namespace, method, type);
          }
        }
      }
    }
  };

  window.prmediaTcComponentVerifierReCaptchaReady = function () {
    core.document.trigger('prmedia.tc.verifier.afterReCaptchaReady');
  };

  core.initApi({
    comment: {
      get: ['get', 'getCount', 'getItems', 'getPage', 'getItemPageNumber'],
      post: ['add', 'update', 'delete', 'publish', 'vote']
    },
    verifier: {
      post: ['verify', 'bxCaptchaReload']
    }
  });

  window.prmedia = window.prmedia || {};
  window.prmedia.tc = core;
})(window, window.jQuery);
