(function(data) {
  console.log(data);
  function wrap(text) {
    return '<div style="position: absolute; z-index: 1; background: royalblue; color: white; padding: 5px; font-size: 14px; font-weight: bold;">'+text+'</div>';
  }
  function firstMatch(selectors) {
    var result = $(_.first(selectors)).first();
    if (result.length) {
      return result;
    } else {
      return firstMatch(_.tail(selectors));
    }
  }
  function display() {
    var other = {};
    _.forEach(data.multipliers, function(mult, name) {
      var factors = _.get(data, ['factors', name], []);
      var selectors = _.split('[name="'+name+'"], [name="'+name+'[]"] + label, [name="'+name+'[]"]', ',');
      var $inputs = firstMatch(selectors);
      var text = mult + (!_.isEmpty(factors)
        ? ' = ' + factors.join(' * ')
        : '');
      $inputs.after(wrap(text));
      if ($inputs.length === 0) {
        other[name] = text;
      }
    });
    if (_.has(data, 'price_per_square_meter')) {
      other['Цена за метр'] = data.price_per_square_meter;
    }
    $('.total_price').prepend(wrap(_.map(other, function(mult, name) { return name+' = '+mult; }).join(', ')));
  }
  $(document).one('ajaxStop', display);
  display();
})(window._data);