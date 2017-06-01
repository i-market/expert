(function() {
  'use strict';

  // import jquery + plugins
  // import lodash
  // import intercooler
  // import Mockup from mockup/script.js

  Intercooler.ready(function($el) {
    Mockup.initForms($el);
    initFormErrorMessage($el);
  });

  // TODO refactor
  function initFormErrorMessage($form) {
    var $formMsg = $form.find('.form-message');
    $formMsg.filter('.error').on('click', function() {
      var $firstError = $form.find('.error:first');
      if ($firstError.length) {
        var $modal = $form.closest('.modal');
        // TODO extract scrolling
        var duration = 700;
        if ($modal.length) {
          $modal.animate({
            scrollTop: $firstError.offset().top - $modal.find('.block').offset().top
          }, duration);
        } else {
          var $title = $firstError.closest(':has(.title)').find('.title');
          $('body, html').animate({
            scrollTop: ($title.length ? $title : $firstError).offset().top
          }, duration);
        }
      }
    });
  }

  function initServiceRequestForm($form) {
    var apiEndpoint = $form.attr('data-api-endpoint');
    $form.on('submit', function(e) {
      // TODO wait for files to upload
      e.preventDefault();
      function setLoading(toggle) {
        // imitate intercooler behavior
        $form.toggleClass('disabled', toggle);
        $form.find('.form-loader').toggle(toggle);
      }
      setLoading(true);
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
          var $prevSwap = $form.find('> *' + filterSel);
          var $nextSwap = $next.filter(filterSel);
          // TODO refactor: brittle
          if ($prevSwap.length === $nextSwap.length) {
            _.forEach(_.zip($prevSwap, $nextSwap), function(pair) {
              var prev = pair[0];
              var next = pair[1];
              prev.replaceWith(next);
            });
          } else {
            $form.replaceWith($next);
          }
        },
        error: function() {
          // TODO handle errors
        },
        complete: function() {
          Mockup.initForms($form);
          setLoading(false);
          initFormErrorMessage($form);
        }
      });
    });
  }

  function initFileBlock($component) {
    function iconImg(extension) {
      var ext = extension === null ? '' : extension;
      return '<img src="/images/file-icon.svg.php?extension=' + ext + '">';
    }
    function renderFile(file, session) {
      var fileId = session + '/' + file.filename;
      return '<div class="file">'
        + '<input type="hidden" name="fileIds[]" value="' + fileId + '"/>'
        + '<div class="left">'
        + iconImg(file.extension)
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
    $('.service-request form').each(function() {
      initServiceRequestForm($(this));
    });
  });
})();
