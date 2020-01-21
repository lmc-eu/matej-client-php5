# PHP 5.6/7.0 compatible API Client for Matej recommendation engine

[![Latest Stable Version](https://img.shields.io/packagist/v/lmc/matej-client-php5.svg?style=flat-square)](https://packagist.org/packages/lmc/matej-client-php5)
[![Travis Build Status](https://img.shields.io/travis/lmc-eu/matej-client-php5/master.svg?style=flat-square)](https://travis-ci.org/lmc-eu/matej-client-php5)
[![Coverage Status](https://img.shields.io/coveralls/lmc-eu/matej-client-php5/master.svg?style=flat-square)](https://coveralls.io/r/lmc-eu/matej-client-php5?branch=master)

**Please note this version is transpiled from [PHP 7.1+ version][original] of the client. So:**

- If you use PHP 7.1+, use the [original library][original]. 
- If you still use PHP 5.6/7.0, temporarily use this library, but we encourage you to update PHP as soon as possible - the PHP 5.6/7.0 is [no longer actively supported](http://php.net/supported-versions.php) by the PHP Group.
- This repository is read-only, meaning all pull-requests, issues etc. must be directed to the [main repository][original].
- Note this version does not contain some features the original version has - mainly type checks, so it may by not as convenient to use as the original. So once again, we recommend using the PHP 7.1+ version.

## Installation

Follow steps from the [installation howto][original-installation] of the original library.

The only difference is you install via Composer package `lmc/matej-client-php5` instead of `lmc/matej-client`.

## Changelog
Version releases will be synced with [the main repository][original].

For latest changes refer to [CHANGELOG.md][original-changelog] of the PHP 7.1 repository. 

We follow [Semantic Versioning](http://semver.org/).

[original]: https://github.com/lmc-eu/matej-client-php
[original-changelog]: https://github.com/lmc-eu/matej-client-php/blob/master/CHANGELOG.md
[original-installation]: https://github.com/OndraM/matej-client-php#installation
