// these should be global
var emptyError = 'Поле не может быть пустым.';

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
      .click('@submitButton')
      .waitForJqueryAjaxRequest()
      // TODO check specific inputs
      .assert.containsText('@errorMessage', emptyError);
      // TODO test for successful submission
    client.end();
  }
};