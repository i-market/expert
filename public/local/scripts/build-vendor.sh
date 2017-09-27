cp node_modules/blueimp-file-upload/js/{jquery.fileupload.js,jquery.iframe-transport.js,vendor/jquery.ui.widget.js} ${npm_package_config_dist}/js/vendor &&
cp node_modules/{lodash/lodash.js,intercooler/dist/intercooler.js} ${npm_package_config_dist}/js/vendor &&
# replace `mockup` slick with a newer version
cp node_modules/slick-carousel/slick/slick.js ${npm_package_config_dist}/js/vendor/slick.js
