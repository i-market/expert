// TODO refactor the whole init/re-init mess
// TODO refactor: extract entity ids

if (typeof console === 'undefined') {
  var noop = function() {};
  console = {log: noop, warn: noop, error: noop};
}

window.App = {
  state: {}
};

/** called on load by recaptcha */
window.initRecaptcha = function($scope) {
  $scope = $scope || $('body');
  $scope.find('.recaptcha').each(function() {
    var $el = $(this);
    var state = {element: this};
    // handle intercooler elements
    var isIntercooler = $el.is('[ic-post-to]');
    if (isIntercooler) {
      $el.off('click');
    }
    state.widgetId = grecaptcha.render(this, {
      'sitekey': $el.attr('data-sitekey'),
      'size': 'invisible',
      'callback': function() {
        if (isIntercooler) {
          Intercooler.triggerRequest($el);
        } else {
          $el.closest('form').submit();
        }
      },
      'expired-callback': function() {
        grecaptcha.reset(state.widgetId);
        console.log('reset recaptcha', state)
      }
    });
    console.log('render recaptcha', state)
  });
};

(function() {
  'use strict';

  // import jquery + plugins
  // import lodash
  // import intercooler
  // import Mockup from mockup/script.js

  // TODO ux: stop `scrollTop` animation when the user scrolls
  // same as in mockup
  var scrollDuration = 700;

  var hash = window.location.hash.substr(1);
  App.hashQuery = hash.split('&').reduce(function(result, item) {
    var parts = item.split('=');
    result[parts[0]] = parts[1];
    return result;
  }, {});

  $(document).ajaxError(function() {
    // TODO make it closeable
    $('#global-error-message').show();
  });

  function scrollOffset(pos) {
    return pos - $(window).height() / 3;
  }

  function setAccordionItem($component, $item, _state, _opts) {
    var opts = _.defaults(_opts || {}, {animate: true});
    var $title = $item.find('.accordeon_title');
    var $inner = $item.find('.accordeon_inner');
    var state = _state === 'toggle'
      ? $title.hasClass('active') ? 'collapse' : 'expand'
      : _state;
    var duration = 100;
    if (state === 'expand') {
      $title.addClass('active');
      $component.find('.accordeon_item').not($item).each(function() {
        setAccordionItem($component, $(this), 'collapse', opts);
      });
      opts.animate ? $inner.slideDown(duration) : $inner.show();
    } else if (state === 'collapse') {
      $title.removeClass('active');
      opts.animate ? $inner.slideUp(duration) : $inner.hide();
    } else {
      conosle.error('unknown state', state);
    }
  }

  // --- selection constraints ---
  // "constraint" is a function mapping selected options to ones that should be disabled

  /** @deprecated */
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

  function getId($el) {
    return _.toInteger($el.val());
  }

  function checkboxSelection($inputs) {
    return $inputs.filter(':checked').map(function() {
      return getId($(this));
    }).get();
  }

  function updateCheckboxes($inputs, disabled) {
    $inputs.each(function() {
      var dis = _.includes(disabled, getId($(this)));
      $(this).prop('disabled', dis).closest('.wrap_checkbox').toggleClass('disabled', dis);
      if ($(this).prop('checked') && dis) {
        $(this).prop('checked', false);
      }
    });
  }

  function selectSelection($select) {
    if (_.isEmpty($select.val())) {
      return [];
    }
    return [getId($select)];
  }

  function updateSelect($select, disabled) {
    var shouldReset = _.includes(disabled, getId($select));
    if ($select.is('.fs-dropdown-element')) {
      var values = $select.find('option').map(function() {
        return $(this).val();
      }).get();
      _.forEach(_.reject(values, _.isEmpty), function(val) {
        var isDisabled = _.includes(disabled, _.toInteger(val));
        $select.dropdown(isDisabled ? 'disable' : 'enable', val);
      });
    } else {
      $select.find('option').each(function() {
        $(this).prop('disabled', _.includes(disabled, getId($(this))));
      });
    }
    if (shouldReset) {
      $select.val('');
      // notify fs-dropdown
      $select.trigger('change');
    }
  }

  function updateRadios($inputs, disabled) {
    $inputs.each(function() {
      var dis = _.includes(disabled, getId($(this)));
      $(this).prop('disabled', dis);
      $('label[for="' + $(this).attr('id') + '"').toggleClass('disabled', dis);
    });
  }

  function and(constraints) {
    return function(selected) {
      function loop(fns, result) {
        if (_.isEmpty(fns)) {
          return result;
        }
        var disabled = _.first(fns)(selected);
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

  function merge(constraints) {
    return function(selected) {
      return _.uniq(_.flatten(_.over(constraints)(selected)));
    };
  }

  function anyOf(pairs) {
    return function(selected) {
      return _.reduce(pairs, function(acc, pair) {
        var anyOf = pair[0];
        var disabled = pair[1];
        var isMatch = !_.isEmpty(_.intersection(anyOf, selected));
        return isMatch ? _.concat(acc, disabled) : acc;
      }, [])
    }
  }

  function allOf(pairs) {
    return function(selected) {
      return _.reduce(pairs, function(acc, pair) {
        var allOf = pair[0];
        var disabled = pair[1];
        var isMatch = _.isEmpty(_.difference(allOf, selected));
        return isMatch ? _.concat(acc, disabled) : acc;
      }, [])
    }
  }

  function equals(disabled) {
    return function(selected) {
      return _.flatMap(selected, function(id) {
        return _.get(disabled, id, []);
      })
    }
  }

  function rangeInc(start, end) {
    return _.range(start, end + 1)
  }

  function parseConstraintEntry(str) {
    // concise constraint format
    var match = str.match(/^(\S+) ((?:\d+(?:-\d+)?,?)+): (nil|(?:\d+(?:-\d+)?,?)+)$/);
    console.assert(match !== null);
    return {prefix: match[1], selected: match[2], disabled: match[3]};
  }

  function stretchToFit($textarea, initialHeight) {
    $textarea.css('height', 0);
    var hasMultipleLines = $textarea[0].scrollHeight > initialHeight;
    if (hasMultipleLines) {
      var padding = parseFloat($textarea.css('padding-top'));
      $textarea.css('height', $textarea[0].scrollHeight + padding);
    } else {
      $textarea.css('height', initialHeight);
    }
  }

  function initStretching($scope) {
    $scope.find('textarea.stretch-to-fit:visible').each(function() {
      var $textarea = $(this);
      var initialHeight = $textarea.innerHeight();
      var stretch = function() { stretchToFit($textarea, initialHeight) };
      stretch();
      $textarea.on('change input', function() {
        setTimeout(stretch, 0);
      });
    });
  }

  var examinationGoalsConstraint = (function() {
    var allOfEntries = [];
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
      '14.1 14-15,18,20,23,25,27,29: 1,3-4,5,7-8',
      '14.1 14,16,18,21,23,25,27,29: 1,3,4,5,7,8,9',
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
      '14.2 14-15,18,20,23,25,27,29: 1,3-4,5,7-8',
      '14.2 14,16,18,21,23,25,27,29: 1,3,4,5,7,8,9',
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
      '14.6 10: 11-39',
      '14.6 11-12,16,18,21,23,25,27: 1-2,4-5',
      '14.6 13-14,17,19,22,24,26,28: 1,4,6,7',
      '14.6 15,17,20,22,24,26,28: 1,4,6,7',
      '14.6 29-37: 1-3,8',
      '14.6 38-39: nil',
      '14.6 40-83: 1-3,10',

      '14.7 1: 2-4',
      '14.7 2: 1,3-4',
      '14.7 3: 1-2,4',
      '14.7 4: 1-3'
    ];
    function translate(prefix, n) {
      // TODO refactor: hardcoded entity offsets
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
    function pairs(entries) {
      return _.map(entries, function(entry) {
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
          _.map(ids(res.selected), _.partial(translate, res.prefix)),
          _.map(ids(res.disabled), _.partial(translate, res.prefix))
        ]
      });
    }
    return merge([
      allOf(pairs(allOfEntries)),
      anyOf(pairs(anyOfEntries))
    ]);
  })();

  // TODO refactor: split this monster
  function init($scope) {
    $('.work_examples_inner').each(function() {
      var $component = $(this);
      if (_.has(App.hashQuery, 'section')) {
        var sectionId = App.hashQuery['section'];
        // expand accordion item containing the section from which we navigated from
        var $section = $component.find('[data-id='+ sectionId +']');
        var $item = $section.closest('.accordeon_item');
        var $accordion = $item.closest('.accordeon');
        setAccordionItem($accordion, $item, 'expand', {animate: false});
        $('body, html').animate({
          scrollTop: scrollOffset($section.offset().top)
        }, scrollDuration);
      }
    });

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

    initStretching($scope);
    // textarea needs to be visible to calculate the initial height
    $scope.on('openModal.app', function(evt) {
      initStretching($(evt.target));
    });

    // TODO refactor: split up this monster of a function
    var calcSel = '.calculator, .calculator--monitoring, .calculator--inspection';
    $scope.find(calcSel).addBack(calcSel).each(function() {
      var $calc = $(this);
      (function () {
        function update(v, f) {
          return f(_.filter(v.split(','))).join(',');
        }
        // unbind intercooler change handler
        $calc.find('.ordered').unbind('change').on('change', function () {
          var $checkbox = $(this);
          var $input = $checkbox.closest('form').find('input[name=order]');
          $input.val($checkbox.is(':checked')
            ? update($input.val(), _.partialRight(_.concat, [$checkbox.val()]))
            : update($input.val(), _.partialRight(_.without, $checkbox.val())));
          Intercooler.triggerRequest($checkbox);
        });
      })();
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
      // deprecated: radio button groups
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
        var disable = $(this).val() === '>3km';
        // TODO disallow submitting the form
        var $warning = $distanceBlock.find('.warning');
        var duration = 200;
        if (disable) {
          $warning.slideDown(duration);
        } else {
          $warning.slideUp(duration);
        }
      });
      $calc.find('.goals-filter').each(function() {
        var $select = $(this);
        var prevValue = $select.val();
        $select.on('change', function() {
          var active = $(this).val();
          if (prevValue !== active) {
            $calc.find('[data-goals-filter]').each(function() {
              $(this).toggle($(this).attr('data-goals-filter') === active);
              $(this).find('input[type=checkbox]').prop('checked', false);
            });
            // mutate
            prevValue = active;
          }
        });
      });
      $calc.find('.structures-to-inspect').each(function() {
        var constraint = merge([
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
        var $inputs = $(this).find('input[type=checkbox]');
        function update() {
          var selected = checkboxSelection($inputs);
          updateCheckboxes($inputs, constraint(selected));
        }
        $inputs.on('change', update);
        update();
      });
      $calc.find('.construction-phase').each(function() {
        var constraint = merge([
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
        var $inputs = $(this).find('input[type=checkbox]');
        function update() {
          var selected = checkboxSelection($inputs);
          updateCheckboxes($inputs, constraint(selected));
        }
        $inputs.on('change', update);
        update();
      });
      var $examination = $calc.filter('.calculator--examination');
      $examination.find('.goals').each(function() {
        var $inputs = $(this).find('input[type=checkbox]');
        function update() {
          var selected = checkboxSelection($inputs);
          updateCheckboxes($inputs, examinationGoalsConstraint(selected));
        }
        $inputs.on('change', update);
        update();
      });
      $examination.each(function() {
        var $usedFor = $(this).find('select[name="USED_FOR"]');
        function checkCondition() {
          if ($usedFor.val() === '22') {
            Mockup.openModal($('#request-examination'));
          }
        }
        $usedFor.on('change', checkCondition);
        checkCondition();

        var $siteCategory = $(this).find('select[name="SITE_CATEGORY"]');
        var $goalsFilter = $(this).find('select[name="GOALS_FILTER"]');
        var $needsVisitRadios = $(this).find('input[name="NEEDS_VISIT"]');
        var goalsConstraint = merge([
          equals({
            1: [7],
            2: [1, 2, 4, 7],
            3: rangeInc(1, 6)
          })
        ]);
        var needsVisitConstraint = merge([
          equals({
            1: [0],
            2: [1],
            3: [1]
          })
        ]);
        function update() {
          var selected = selectSelection($siteCategory);
          updateSelect($goalsFilter, goalsConstraint(selected));

          updateRadios($needsVisitRadios, needsVisitConstraint(selected));
          var $enabled = $needsVisitRadios.filter(':enabled');
          if ($enabled.length === 1) {
            $enabled.prop('checked', true);
            $enabled.trigger('change');
          }

          var hideUsedFor = _.includes(selected, 3);
          // TODO ux: animate
          var $formItem = $usedFor.closest('.wrap_calc_item');
          $formItem.toggle(!hideUsedFor);
          if (hideUsedFor) {
            // TODO refactor: extract the function to reset inputs
            var $inputLikes = $formItem.find('input, select');
            $inputLikes.val('');
            // notify fs-dropdown with change event
            $inputLikes.trigger('change');
          }
        }
        $siteCategory.on('change', update);
        update();
      });
      // on closing a dropdown block reset its inputs
      $examination.find('.needs-visit-dropdown').on('closeBlock.app', function() {
        var $inputLikes = $(this).find('input, select');
        $inputLikes.val('');
        // notify fs-dropdown with change event
        $inputLikes.trigger('change');
      });
      $calc.filter('.calculator--monitoring').find('.structures').each(function() {
        constrainSelection($(this), [
          anyOf([
            [[1], rangeInc(2, 9)],
            [rangeInc(2, 9), [1]]
          ])
        ]);
      });
    });
  }

  Intercooler.ready(function($el) {
    Mockup.initForms($el);
    initFormErrorMessage($el);
    // TODO refactor: sort out intercooler.ready, document.ready and `init` stuff
    if (!$el.is('body')) {
      init($el);
      initRecaptcha($el);
    }
  });

  function initFormErrorMessage($form) {
    var $formMsg = $form.find('.form-message');
    $formMsg.filter('.error').on('click', function() {
      var $firstError = $form.find('.error:first');
      if ($firstError.length) {
        var $modal = $form.closest('.modal');
        if ($modal.length) {
          $modal.animate({
            scrollTop: scrollOffset($firstError.offset().top - $modal.find('.block').offset().top)
          }, scrollDuration);
        } else {
          // TODO refactor: .title works for `individual` calculator, but that's just a happy accident
          var $title = $firstError.closest(':has(.title)').find('.title');
          $('body, html').animate({
            scrollTop: scrollOffset(($title.length ? $title : $firstError).offset().top)
          }, scrollDuration);
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
          initStretching($form);
          initRecaptcha($form);
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
      if (_.get(file, 'error')) {
        return '';
      }
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
    var maxFileCount = 10;
    var maxFileErrorMessage = 'Извините, максимальное количество файлов ('+maxFileCount+') уже загружено.';
    var $files = $component.find('.files');
    var $progress = $component.find('.progress');
    var $errorMessage = $component.find('.error-message');
    var megabyte = 1000000;
    function setErrorMessage(message) {
      $errorMessage.text(message).toggle(!_.isEmpty(message));
    }
    $component.find('.fileupload').fileupload({
      dataType: 'json',
      singleFileUploads: false,
      // TODO validation
      // see -validation and `processQueue`, -process overrides `add` function below. not sure how to integrate it yet.
      // maxFileSize: 25 * megabyte,
      formData: function(form) {
        var data = form.serializeArray();
        // FormData supports only string values
        data.push({name: 'state', value: JSON.stringify(state)});
        return data;
      },
      add: function(e, data) {
        // surely there is a better way to get the number of uploaded files
        var uploadedFileCount = data.form.find('input[name="fileIds[]"]').length;
        if (uploadedFileCount < maxFileCount) {
          // TODO refactor: get the initial value beforehand
          $progress.css('display', 'flex');
          data.submit();
        } else {
          setErrorMessage(maxFileErrorMessage);
        }
      },
      done: function(e, data) {
        state.session = data.result.session;
        hideProgress($progress);
        $.each(data.result.files, function(index, file) {
          $(renderFile(file, state.session)).appendTo($files);
        });
        var errors = _.reduce(data.result.files, function(acc, file) {
          if (_.get(file, 'error')) {
            // TODO "filename: error" would be nice
            acc.push(file.error);
          }
          return acc;
        }, []);
        setErrorMessage(_.uniq(errors).join('\n'));
      },
      fail: function(e, data) {
        // ajax-level failure, won't be called on validation
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

    var $root = $('body');
    init($root);

    if (_.has(App.hashQuery, 'modal')) {
      var modalId = App.hashQuery['modal'];
      Mockup.openModal($('#' + modalId));
    }

    $('.opinion-galery').fancybox({});

    $('.gallery').fancybox({
      scrolling: 'yes'
    });

    function adaptiveOpinionImgHeight(){
      var opinionImgWidth = $(".info-img-img").width(),
        opinionImgHeight = opinionImgWidth * 283 / 370;
      //присваиваем высоту слайдеру и слайдам
      $(".info-img-img").css("height", opinionImgHeight);
    }


    $('.accordeon').each(function() {
      var $component = $(this);
      $component.find('.accordeon_item').each(function() {
        var $item = $(this);
        $item.find('.accordeon_title').on('click', function() {
          setAccordionItem($component, $item, 'toggle');
        });
      });
    });

    $('.our_objects .grid').slick({
      adaptiveHeight: true, // take margins into account
      rows: 2,
      slidesToShow: 3,
      slidesToScroll: 1,
      prevArrow: $('.our_objects_section .prev'),
      nextArrow: $('.our_objects_section .next'),
      responsive: [
        {
          breakpoint: 1025,
          settings: {
            rows: 2,
            slidesToShow: 2
          }
        },
        {
          breakpoint: 767,
          settings: {
            rows: 1,
            slidesToShow: 1
          }
        }
      ]
    });
    $('.banner_slider').slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      prevArrow: $('.wrap_banner_slider .prev'),
      nextArrow: $('.wrap_banner_slider .next')
    });
    $('.our_clients .grid').slick({
      adaptiveHeight: true,
      slidesToShow: 4,
      slidesToScroll: 1,
      prevArrow: $('.our_clients_section .prev'),
      nextArrow: $('.our_clients_section .next'),
      responsive: [
        {
          breakpoint: 1024,
          settings: {
            slidesToShow: 2
          }
        },
        {
          breakpoint: 767,
          settings: {
            slidesToShow: 1
          }
        }
      ]
    });
    $('.our_reviews .grid').slick({
      adaptiveHeight: true,
      slidesToShow: 5,
      slidesToScroll: 1,
      prevArrow: $('.our_reviews_section .prev'),
      nextArrow: $('.our_reviews_section .next'),
      responsive: [
        {
          breakpoint: 1024,
          settings: {
            slidesToShow: 4
          }
        },
        {
          breakpoint: 767,
          settings: {
            slidesToShow: 2
          }
        }
      ]
    });

    /*New script: start*/
    var $calcExamForm = $('.calculator_certain_types').filter('.calculator--examination').find('form'),
      $calcExamCategory = $calcExamForm.find('select[name="SITE_CATEGORY"]'),
      $calcExamGoalsFilter = $calcExamForm.find('select[name="GOALS_FILTER"]'),
      $goalsBlock = $calcExamForm.find('.goals');

    /*
    1-ая правка.
    Если в пункте  «Категория объектов экспертизы*» выбран пункт  «4.2. Проектная, рабочая,
  договорная документация», в разделе «Цели и задачи экспертизы*» выбран пункт  14.5.
  Определение (оценка) величины причиненного ущерба
  Должно быть:  – доступен для выбора только пункт: 14.5.5.

  2-я правка
    а если в разделе «Цели и задачи экспертизы*»
  выбран пункт  14.6. Определение объема и стоимости
  доступными для выбора должны быть пункты 14.6.10,
   и в выборочной документации Проектные работы – проектная документация
  Проектные работ – рабочая документация
  */
    $calcExamGoalsFilter.on('change', function(){
      var $this = $(this);

      $goalsBlock.find('[data-goals-filter=5]').find('.wrap_checkbox').each(function(){
        $(this).removeClass('disabled');
        $(this).find('input[type=checkbox]').removeAttr('disabled').prop('checked', false);
      });
      /*$goalsBlock.find('[data-goals-filter=6]').find('.wrap_checkbox').each(function(){
        $(this).removeClass('disabled');
        $(this).find('input[type=checkbox]').removeAttr('disabled').prop('checked', false);
      });*/

      if( ($this.val() == 5) && ($calcExamCategory.val() == 2) ){
        $goalsBlock.find('[data-goals-filter=5]').find('.wrap_checkbox').each(function(){
          $(this).addClass('disabled');
          $(this).find('input[type=checkbox]').attr('disabled', '').prop('checked', false);
        });

        $goalsBlock.find('[data-goals-filter=5]').find('.wrap_checkbox:nth-child(5)').removeClass('disabled').find('input[type=checkbox]').removeAttr('disabled').prop('checked', false);
      }
      /*else if( ($this.val() == 6) && ($calcExamCategory.val() == 2) ){
        $goalsBlock.find('[data-goals-filter=6]').find('.group_goals_6:first-child').find('.wrap_checkbox').each(function(){
          $(this).addClass('disabled');
          $(this).find('input[type=checkbox]').attr('disabled', '').prop('checked', false);
        });

        $goalsBlock.find('[data-goals-filter=6]').find('.wrap_checkbox:nth-child(10)').removeClass('disabled').find('input[type=checkbox]').removeAttr('disabled').prop('checked', false);
      }*/

      return false;
    });
    $calcExamCategory.on('change', function(){
      $calcExamGoalsFilter.trigger('change');
    });
    $goalsBlock.find('[data-goals-filter=5]').on('click', '.wrap_checkbox:nth-child(5)', function(e){
      if( ($calcExamGoalsFilter.val() == 5) && ($calcExamCategory.val() == 2) ){
        e.preventDefault();

        var $this = $(this),
          $checkBox = $this.find('input');

        if($checkBox.is(':checked')){
          $checkBox.prop('checked', false);
        }else{
          $checkBox.prop('checked', true);
        }
      }
    });
    /*$goalsBlock.find('[data-goals-filter=6]').on('click', '.wrap_checkbox:nth-child(10)', function(e){
      if( ($calcExamGoalsFilter.val() == 6) && ($calcExamCategory.val() == 2) ){
        e.preventDefault();

        var $this = $(this),
          $checkBox = $this.find('input');

        if($checkBox.is(':checked')){
          $checkBox.prop('checked', false);
        }else{
          $checkBox.prop('checked', true);
        }
      }
    });*/
    /*Правка: end*/


    /*New script: end*/
    /*При изменении размеров экрана*/
    $(window).resize(function (e) {
      adaptiveOpinionImgHeight();
    });
    /*при загрузке страницы*/
    $(function () {
      adaptiveOpinionImgHeight();
    });

  });

})();


