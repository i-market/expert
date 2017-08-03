(function() {
  'use strict';

  // import jquery + plugins
  // import lodash
  // import intercooler
  // import Mockup from mockup/script.js

  $(document).ajaxError(function() {
    // TODO make it closeable
    $('#global-error-message').show();
  });

  function init($scope) {
    // calculators

    // TODO refactor: remove redundant calculator classes
    var sel = '.calculator, .calculator--monitoring, .calculator--inspection';
    $scope.find(sel).addBack(sel).each(function() {
      var $calc = $(this);
      $calc.find('input[type=radio][data-group]').on('change', function() {
        var group = $(this).data('group');
        $('.group_' + group).find('input[type=checkbox]').prop('checked', false);
      });
      var $distanceBlock = $calc.find('.distance-between-sites');
      $calc.find('input.site-count').on('change', function() {
        var siteCount = parseInt($(this).val(), 10);
        if (siteCount > 1) {
          // wait for a server response instead
          // Mockup.openBlock($distanceBlock);
        } else {
          Mockup.closeBlock($distanceBlock);
        }
      });
      $distanceBlock.find('select').on('change', function() {
        // TODO improve ux: animate
        $distanceBlock.find('.warning').toggle($(this).val() === '>3km');
      });
      $calc.find('.goals-filter').on('change', function() {
        var active = $(this).val();
        $calc.find('[data-goals-filter]').each(function() {
          $(this).toggle($(this).attr('data-goals-filter') === active);
          $(this).find('input[type=checkbox]').prop('checked', false);
        });
      });
      $calc.find('.construction-phase').each(function() {
        var $component = $(this);
        console.assert(typeof App !== 'undefined' && _.has(App, 'constructionPhases'));
        $component.find('input').on('change', function() {
          var $trigger = $(this);
          var checked = $component.find('input:checked').map(function() {
            return $(this).val();
          }).get();
          var disabled = _.uniq(_.flatMap(checked, function(id) {
            return _.has(App.constructionPhases.available, id)
              ? _.difference(_.without(App.constructionPhases.known, id), App.constructionPhases.available[id])
              : [];
          }));
          $component.find('input').each(function() {
            var dis = _.includes(disabled, $(this).val());
            $(this).prop('disabled', dis).closest('.wrap_checkbox').toggleClass('disabled', dis);
            if (!$(this).is($trigger) && $(this).prop('checked') && dis) {
              $(this).prop('checked', false);
            }
          });
        });
      });
    });
  }

  Intercooler.ready(function($el) {
    Mockup.initForms($el);
    initFormErrorMessage($el);
    init($el);
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
          // TODO refactor: .title works for `individual` calculator, but that's just a happy accident
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
    init($('body'));
  });
})();
