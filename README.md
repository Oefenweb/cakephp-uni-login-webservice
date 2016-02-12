# UniLoginWebservice plugin for CakePHP

[![Build Status](https://travis-ci.org/Oefenweb/cakephp-uni-login-webservice.png?branch=master)](https://travis-ci.org/Oefenweb/cakephp-uni-login-webservice) [![Coverage Status](https://coveralls.io/repos/Oefenweb/cakephp-uni-login-webservice/badge.png)](https://coveralls.io/r/Oefenweb/cakephp-uni-login-webservice) [![Packagist downloads](http://img.shields.io/packagist/dt/Oefenweb/cakephp-uni-login-webservice.svg)](https://packagist.org/packages/oefenweb/cakephp-uni-login-webservice) [![Code Climate](https://codeclimate.com/github/Oefenweb/cakephp-uni-login-webservice/badges/gpa.svg)](https://codeclimate.com/github/Oefenweb/cakephp-uni-login-webservice)

## Requirements

* CakePHP 2.4.2 or greater.
* PHP 5.4.16 or greater.

## Installation

Clone/Copy the files in this directory into `app/Plugin/UniLoginWebservice`

## Configuration

Ensure the plugin is loaded in `app/Config/bootstrap.php` by calling:

```
CakePlugin::load('UniLoginWebservice');
```

Ensure to configure the following lines in `app/Config/bootstrap.php`:

```
Configure::write('UniLoginWebservice.wsBrugerid', 'wsBrugerid');
Configure::write('UniLoginWebservice.wsPassword', 'wsPassword');
```

## Usage

```
```
