(function() {
  'use strict';

  // import jquery + plugins
  // import lodash
  // import intercooler
  // import Mockup from mockup/script.js

  window.App = {
    state: {}
  };

  $(document).ajaxError(function() {
    // TODO make it closeable
    $('#global-error-message').show();
  });

  // --- checkbox selection constraints ---
  // "constraint" is a function mapping checked options to ones that should be disabled

  function constrainSelection($component, constraints) {
    function getId($input) {
      return _.toInteger($input.val());
    }
    var $checkboxes = $component.find('input[type=checkbox]');
    function update() {
      var checked = $checkboxes.filter(':checked').map(function() {
        return getId($(this));
      }).get();
      var disabled = _.uniq(_.flatten(_.over(constraints)(checked)));
      $checkboxes.each(function() {
        var dis = _.includes(disabled, getId($(this)));
        $(this).prop('disabled', dis).closest('.wrap_checkbox').toggleClass('disabled', dis);
        if ($(this).prop('checked') && dis) {
          $(this).prop('checked', false);
        }
      });
    }
    $checkboxes.on('change', update);
    update();
  }

  function and(constraints) {
    return function(checked) {
      function loop(fns, result) {
        if (_.isEmpty(fns)) {
          return result;
        }
        var disabled = _.first(fns)(checked);
        if (_.isEmpty(disabled)) {
          // if any constraint is "false" (returns an empty array), then the whole thing is false
          return [];
        } else {
          return loop(_.tail(fns), _.concat(result, disabled));
        }
      }
      return loop(constraints, []);
    }
  }

  function anyOf(pairs) {
    return function(checked) {
      return _.reduce(pairs, function(acc, pair) {
        var anyOf = pair[0];
        var disabled = pair[1];
        var isMatch = !_.isEmpty(_.intersection(anyOf, checked));
        return isMatch ? _.concat(acc, disabled) : acc;
      }, [])
    }
  }

  function allOf(pairs) {
    return function(checked) {
      return _.reduce(pairs, function(acc, pair) {
        var allOf = pair[0];
        var disabled = pair[1];
        var isMatch = _.isEmpty(_.difference(allOf, checked));
        return isMatch ? _.concat(acc, disabled) : acc;
      }, [])
    }
  }

  function equals(disabled) {
    return function(checked) {
      return _.flatMap(checked, function(id) {
        return _.get(disabled, id, []);
      })
    }
  }

  function rangeInc(start, end) {
    return _.range(start, end + 1)
  }

  function parseConstraintEntry(str) {
    // concise constraint format
    var match = str.match(/^(\S+) (\d+(?:-\d+)?,?)+: (nil|(\d+(?:-\d+)?,?)+)$/);
    return {prefix: match[1], checked: match[2], disabled: match[3]};
  }

  function init($scope) {
    $('.work_examples_inner').each(function() {
      var $component = $(this);
      var hash = window.location.hash.substr(1);
      var hashQuery = hash.split('&').reduce(function(result, item) {
        var parts = item.split('=');
        result[parts[0]] = parts[1];
        return result;
      }, {});
      if (_.has(hashQuery, 'backFrom')) {
        var sectionId = hashQuery['backFrom'];
        // expand accordion item containing the section from which we navigated from
        $component.find('[data-id='+ sectionId +']').closest('.accordeon_inner').prev().click();
      }
    });

    // calculators

    function setExpandableTo($el, state) {
      var targetSel = $el.attr('data-target');
      function saveState(val) {
        if (_.startsWith(targetSel, '#')) {
          _.set(App.state, ['expandable', targetSel], val);
        }
        return val;
      }
      var action = state === 'expanded'
        ? Mockup.openBlock
        : Mockup.closeBlock;
      action($(targetSel));
      $el.attr('data-state', saveState(state));
    }

    // TODO refactor: split up this monster of a function
    var sel = '.calculator, .calculator--monitoring, .calculator--inspection';
    $scope.find(sel).addBack(sel).each(function() {
      var $calc = $(this);
      $calc.find('.calculator__expandable-title').each(function() {
        // TODO refactor: maintaining state between ajax requests
        var targetSel = $(this).attr('data-target');
        var savedStateMaybe = _.get(App.state, ['expandable', targetSel]);
        if (!_.isUndefined(savedStateMaybe)) {
          $(this).attr('data-state', savedStateMaybe);
          $(targetSel).toggle(savedStateMaybe === 'expanded');
        }
        // TODO avoid binding multiple times
        $(this).unbind('click').on('click', function() {
          setExpandableTo($(this), $(this).attr('data-state') === 'collapsed' ? 'expanded' : 'collapsed');
        });
      });
      $calc.find('input[type=radio][data-group]').on('change', function() {
        var group = $(this).data('group');
        $('.group_' + group).find('input[type=checkbox]').prop('checked', false);
      });
      var $distanceBlock = $calc.find('.distance-between-sites');
      $calc.find('input.site-count').on('change', function() {
        var siteCount = parseInt($(this).val(), 10);
        if (siteCount > 1) {
          // wait for the server response instead
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
      $calc.find('.structures-to-inspect').each(function() {
        constrainSelection($(this), [
          equals({
            1: _.concat(rangeInc(2, 12), rangeInc(14, 18)),
            2: _.concat([1], rangeInc(4, 12)),
            3: _.concat([1], rangeInc(14, 18))
          }),
          allOf([
            [[2, 3], _.concat([1], rangeInc(4, 12), rangeInc(14, 18))]
          ]),
          anyOf([
            [rangeInc(4, 12), [1, 2]]
          ]),
          anyOf([
            [rangeInc(14, 18), [1, 3]]
          ]),
          and([
            anyOf([
              [rangeInc(4, 12), [1, 2, 3]]
            ]),
            anyOf([
              [rangeInc(14, 18), [1, 2, 3]]
            ])
          ])
        ]);
      });
      $calc.find('.construction-phase').each(function() {
        constrainSelection($(this), [
          equals({
            1: rangeInc(2, 9),
            // 2?
            3: _.concat([1], rangeInc(5, 9)),
            4: _.concat([1], rangeInc(6, 9)),
            5: [1, 6],
            6: [1],
            7: [1],
            8: [1],
            9: [1]
          })
        ]);
      });
      $calc.filter('.calculator--examination').find('.goals').each(function() {
        var anyOfEntries = [
          '14.1 1: 2-10,12-38',
          '14.1 2: 1,3-6,10,12-13,17,19,22,24,26,28,30,32,34,36,38',
          '14.1 3: 1-2,4-9,12-29',
          '14.1 4: 1-3,5-8,12-15,17,19-20,22,24,26,28',
          '14.1 5: 1-4,6-7,9,12-13,16-17,19,21-22,24,26,28',
          '14.1 6: 1-5,12-13,17,19,22,24,26,28',
          '14.1 7: 1,3-5,8-9,14-16,18,20-21,23,25,27,29',
          '14.1 8: 1,3-4,7,14-15,20,23,25',
          '14.1 9: 1,3,5,7,16,21,23',
          '14.1 10: 1-2,30-38',
          '14.1 11: nil',
          '14.1 39-40: nil',
          '14.1 12-13,17,19,22,24,26,28: 1-6',
          '14.1 14-15,18,20,23,25,27,29: 1,3-4,7-8',
          '14.1 14,16,18,21,23,25,27,29: 1,3,5,7,9',
          '14.1 30-38: 10',

          '14.2 1: 2-10,12-38',
          '14.2 2: 1,3-6,10,12-13,17,19,22,24,26,28,30,32,34,36,38',
          '14.2 3: 1-2,4-9,12-29',
          '14.2 4: 1-3,5-8,12-15,17,19-20,22,24,26,28',
          '14.2 5: 1-4,6-7,9,12-13,16-17,19,21-22,24,26,28',
          '14.2 6: 1-5,12-13,17,19,22,24,26,28',
          '14.2 7: 1,3-5,8-9,14-16,18,20-21,23,25,27,29',
          '14.2 8: 1,3-4,7,14-15,20,23,25',
          '14.2 9: 1,3,5,7,16,21,23',
          '14.2 10: 1-2,30-38',
          '14.2 11: nil',
          '14.2 39-40: nil',
          '14.2 12-13,17,19,22,24,26,28: 1-6',
          '14.2 14-15,18,20,23,25,27,29: 1,3-4,7-8',
          '14.2 14,16,18,21,23,25,27,29: 1,3,5,7,9',
          '14.2 30-38: 10',

          '14.3 1: 2-66',
          '14.3 2: 1,4-25,48-66',
          '14.3 3: 1,26-66',
          '14.3 4-25: 1-2,48-66',
          '14.3 26-47: 1,3,48-66',
          '14.3 48-66: 1-47',

          '14.4 1: 2-3,6-21',
          '14.4 2: 1,6-15',
          '14.4 3: 1,16-21',
          '14.4 4-5: nil',
          '14.4 6-15: 1-2',
          '14.4 16-21: 1,3',

          '14.5 1: 2-6',
          '14.5 2: 1,3-6',
          '14.5 3: 1-2,4-6',
          '14.5 4: 1-3,5-6',
          '14.5 5: 1-4,6',
          '14.5 6: 1-5',

          '14.6 1: 2-8,10,11-37,40-83',
          '14.6 2: 1,3-5,8,10,11-12,16,18,21,23,25,27,29,31,33,35,37,40-83',
          '14.6 3: 1-2,8,29-37,51-52,54-58,60-61,73-74,76-80,82-83',
          '14.6 4: 1-2,5-7,11-28',
          '14.6 5: 1-2,4,11-12,16,18,21,23,25,27',
          '14.6 6: 1,4,15,20',
          '14.6 7: 1,4,13,14,19,22',
          '14.6 8: 1-3,29-37',
          '14.6 9: nil',
          '14.6 10: 40-83',
          '14.6 11-12,16,18,21,23,25,27: 1-2,4-5',
          '14.6 13-14,17,19,22,24,26,28: 1,4,7',
          '14.6 15,17,20,22,24,26,28: 1,4,6',
          '14.6 29-37: 1-3,8',
          '14.6 38-39: nil',
          '14.6 40-83: 1-3,10',

          '14.7 1: 2-4',
          '14.7 2: 1,3-4',
          '14.7 3: 1-2,4',
          '14.7 4: 1-3'
        ];
        function translate(prefix, n) {
          var offsetByPrefix = {
            '14.1': 0,
            '14.2': 40,
            '14.3': 80,
            '14.4': 146,
            '14.5': 167,
            '14.6': 173
          };
          return n + offsetByPrefix[prefix];
        }
        var anyOfPairs = _.map(anyOfEntries, function(entry) {
          var res = parseConstraintEntry(entry);
          function ids(str) {
            if (str === 'nil') {
              return [];
            }
            return _.flatMap(_.split(str, ','), function(s) {
              if (_.includes(s, '-')) {
                var parts = _.map(_.split(s, '-'), _.toInteger);
                return rangeInc(parts[0], parts[1]);
              } else {
                return [_.toInteger(s)];
              }
            });
          }
          return [
            _.map(ids(res.checked), _.partial(translate, res.prefix)),
            _.map(ids(res.disabled), _.partial(translate, res.prefix))
          ]
        });
        constrainSelection($(this), [
          anyOf(anyOfPairs)
        ]);
      });
      $calc.filter('.calculator--monitoring').find('.structures').each(function() {
        constrainSelection($(this), [
          anyOf([
            [[1], rangeInc(2, 9)],
            [rangeInc(2, 9), [1]]
          ])
        ]);
      })
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
