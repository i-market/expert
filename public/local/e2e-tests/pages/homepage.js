module.exports = {
  url: function() {
    return this.api.launchUrl;
  },
  sections: {
    header: {
      selector: 'header.header',
      elements: {
        requestCallbackButton: {
          selector: '.re_call'
        }
      }
    },
    requestCallbackModal: {
      selector: '#re_call',
      elements: {
        heading: {
          selector: 'h2'
        }
      }
    }
  }
};