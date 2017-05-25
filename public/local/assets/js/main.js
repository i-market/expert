(function() {
  'use strict';

  // import jquery + plugins
  // import lodash
  // import intercooler
  // import Mockup from mockup/script.js

  Intercooler.ready(function($el) {
    Mockup.initForms($el);
  });

  function initServiceRequestForm($form) {
    var apiEndpoint = $form.attr('data-api-endpoint');
    $form.on('submit', function(e) {
      // TODO wait for files to upload
      e.preventDefault();
      var $loader = $form.find('.form-loader');
      $loader.show();
      var data = $form.serialize();
      $.ajax({
        url: apiEndpoint,
        method: 'POST',
        data: data,
        dataType: 'text',
        headers: {
          'Accept': 'text/html-partial, */*; q=0.9'
        },
        success: function(data) {
          var $next = $(data);
          // don't swap stateful components
          var filterSel = ':not(.keep)';
          var pairs = _.zip($form.find('> *' + filterSel), $next.filter(filterSel));
          _.forEach(pairs, function(pair) {
            var prev = pair[0];
            var next = pair[1];
            prev.replaceWith(next);
          });
        },
        error: function() {
          // TODO handle errors
        },
        complete: function() {
          Mockup.initForms($form);
          // should probably re-init itself here
          $loader.hide();
        }
      });
    });
  }

  function initFileBlock($component) {
    var iconSvgTpl = $('#file-icon').text();
    function iconSvg(extension) {
      var ext = extension === null ? '' : extension;
      var $svg = $(iconSvgTpl);
      $svg.find('text tspan')[0].textContent = ext.toUpperCase();
      // TODO how is browser support for innerHTML on svg elements?
      return $svg[0].outerHTML;
    }
    function renderFile(file, session) {
      var fileId = session + '/' + file.filename;
      return '<div class="file">'
        + '<input type="hidden" name="fileIds[]" value="' + fileId + '"/>'
        + '<div class="left">'
        + iconSvg(file.extension)
        + '</div>'
        + '<div class="right">'
        + '<p class="info">' + (file.extension !== null ? file.extension.toUpperCase() + ', ' : '') + file.humanSize + ' <span class="remove red">Удалить</span></p>'
        + '<p class="title">' + file.name + '</p>'
        + '</div>'
        + '</div>';
    }
    function hideProgress($progress) {
      $progress.hide();
    }
    var state = {
      session: null
    };
    var $files = $component.find('.files');
    var $progress = $component.find('.progress');
    $component.find('.fileupload').fileupload({
      dataType: 'json',
      singleFileUploads: false,
      formData: function(form) {
        var data = form.serializeArray();
        // FormData supports only string values
        data.push({name: 'state', value: JSON.stringify(state)});
        return data;
      },
      add: function(e, data) {
        // show
        $progress.css('display', 'flex');
        data.submit();
      },
      done: function(e, data) {
        state.session = data.result.session;
        hideProgress($progress);
        $.each(data.result.files, function (index, file) {
          $(renderFile(file, state.session)).appendTo($files);
        });
      },
      fail: function(e, data) {
        // TODO notify the user
        hideProgress($progress);
      }
    });
  }

  $(document).ready(function() {
    $('.wrap_add_file').each(function() {
      initFileBlock($(this));
    });
    $('form.service-request').each(function() {
      initServiceRequestForm($(this));
    });
  });
})();
