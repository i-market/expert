#!/usr/bin/env sh

pushd mockup
&& gulp sass
&& gulp css
&& gulp dist
&& rsync -av --exclude='*.html' dist/* ../templates/main/build
popd