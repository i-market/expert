#!/usr/bin/env sh

pushd mockup &&
gulp sass &&
gulp css &&
gulp dist &&
mkdir -p ../templates/main/build/assets &&
rsync -av --exclude='*.html' dist/* ../templates/main/build/assets
popd