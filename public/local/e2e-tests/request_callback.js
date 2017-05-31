module.exports = {
  'request callback': function(browser) {
    browser
      .url(browser.launch_url)
      .waitForElementVisible('.header .re_call', 1000)
      .click('.header .re_call')
      .waitForElementVisible('#re_call', 1000)
      .assert.containsText('#re_call h2', 'ОБРАТНЫЙ ЗВОНОК')
      // TODO ...
      .end();
  }
};