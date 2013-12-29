#!/bin/bash

mkdir -p log
mkdir -p temp
# Some files in cache may be locked and we cannot remove them
rm -rf temp/* 2> /dev/null
chmod -R ug+rwx log temp 2> /dev/null

mkdir -p www/bootstrap && cp -R vendor/twbs/bootstrap/dist/* www/bootstrap;
cp vendor/frameworks/jquery/jquery.min.js www/js;
cp vendor/nette/nette/client-side/netteForms.js www/js;