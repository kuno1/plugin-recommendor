#!/usr/bin/env bash

set -e

# Rebuild libraries.
composer install --no-dev --prefer-dist

# Remove unwanted files.
rm -rf node_modules
rm -rf package-lock.json
rm -rf tests
rm -rf bin
rm -rf .git
rm -rf .travis.yml
rm -rf tests
rm -rf vendor
rm -rf composer.lock
rm -rf .gitignore
rm -rf phpunit.xml.dist
rm -rf .eslintrc
rm -rf webpack.config.js
