module.exports = {
  'request callback': function(client) {
    var homepage = client.page.homepage();
    homepage.navigate()
      .section.header
      .waitForElementVisible('@requestCallbackButton', 1000)
      .click('@requestCallbackButton');
    homepage
      .waitForElementVisible(homepage.section.requestCallbackModal.selector, 1000);
    homepage
      .section.requestCallbackModal
      .assert.containsText('@heading', 'ОБРАТНЫЙ ЗВОНОК')
    // TODO ...
    client.end();
  }
};