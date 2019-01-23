# UniLoginWebservice plugin for CakePHP

[![Build Status](https://travis-ci.org/Oefenweb/cakephp-uni-login-webservice.png?branch=master)](https://travis-ci.org/Oefenweb/cakephp-uni-login-webservice)
[![PHP 7 ready](http://php7ready.timesplinter.ch/Oefenweb/cakephp-uni-login-webservice/badge.svg)](https://travis-ci.org/Oefenweb/cakephp-uni-login-webservice)
[![Coverage Status](https://codecov.io/gh/Oefenweb/cakephp-uni-login-webservice/branch/master/graph/badge.svg)](https://codecov.io/gh/Oefenweb/cakephp-uni-login-webservice)
[![Packagist downloads](http://img.shields.io/packagist/dt/Oefenweb/cakephp-uni-login-webservice.svg)](https://packagist.org/packages/oefenweb/cakephp-uni-login-webservice)
[![Code Climate](https://codeclimate.com/github/Oefenweb/cakephp-uni-login-webservice/badges/gpa.svg)](https://codeclimate.com/github/Oefenweb/cakephp-uni-login-webservice)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Oefenweb/cakephp-uni-login-webservice/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Oefenweb/cakephp-uni-login-webservice/?branch=master)

This plugin communicates with the UNI•Login webservice (WS-02). This webservice provides basic information of UNI•Login
users (by institution). This service doesn't require a signed agreement of the institution.

## Requirements

* CakePHP 2.9.0 or greater.
* PHP 7.0.0 or greater.

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


Ensure to configure the following lines in `app/Config/database.php`:

```
public $uniLoginWebservice = [
	'datasource' => 'UniLoginWebservice.SoapSource',
	'wsdl' => 'https://ws02.infotjeneste.uni-c.dk/infotjeneste-ws/ws?WSDL'
];
```

## Usage

### Make UniLogin webservice available

```
public $uses = ['UniLoginWebservice.UniLogin'];
```

### Make a "helloWorld" call

```
$this->UniLogin->helloWorld();
```

### Make a "helloSOAPFaultDemo" call

```
$this->UniLogin->helloSOAPFaultDemo();
```

### Retrieve data of employees by calling "hentAnsatte"

```
$employees = $this->UniLogin->getEmployees($instid);
```

### Retrieve detailed data of employees by calling "hentAnsatte" and "hentPerson" for every employee

```
$employees = $this->UniLogin->getEmployeesWithDetails($instid);
```

### Retrieve data of an institution by calling "hentInstitution"

```
$institution = $this->UniLogin->getInstitution($instid);
```

### Retrieve data of institutions where the user "brugerid" has a relation by calling "hentInstitutionsliste"

```
$institutions = $this->UniLogin->getInstitutions($brugerid);
```

### Retrieve data of a person by calling "hentPerson"

```
$person = $this->UniLogin->getPerson($brugerid);
```

### Retrieve data of students by calling "hentAlleElever"

```
$students = $this->UniLogin->getStudents($instid);
```

### Retrieve detailed data of students by calling "hentAlleElever" and "hentPerson" for every student

```
$students = $this->UniLogin->getStudentsWithDetails($instid);
```
