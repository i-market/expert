module.exports = (function(settings) {
  // TODO accept url scheme
  settings.test_settings.default.launch_url = 'http://' + (process.env.URL || 'localhost');
  return settings;
})(require('./nightwatch.json'));