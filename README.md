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


Ensure to configure the following lines in `app/Config/database.php`:

```
public $uniLoginWebservice = [
	'datasource' => 'UniLoginWebservice.SoapSource',
	'wsdl' => 'https://ws02.infotjeneste.uni-c.dk/infotjeneste-ws/ws?WSDL'
];
```

## Usage

### Make UniLogin webservice available (in a controller)

```
public $uses = ['UniLoginWebservice.UniLogin'];
```

### Make a "helloWorld" call to UniLogin webservice (in a controller)

```
$this->UniLogin->helloWorld();
```

### Make a "helloSOAPFaultDemo" call to UniLogin webservice (in a controller)

```
$this->UniLogin->helloSOAPFaultDemo();
```

### Retrieve data of employees from UniLogin webservice by calling "hentAnsatte" (in a controller)

```
$person = $this->UniLogin->getEmployees($instid);
```

### Retrieve detailed data of employees from UniLogin webservice by calling "hentAnsatte" and "hentPerson" for every employee (in a controller)

```
$person = $this->UniLogin->getEmployeesWithDetails($instid);
```

### Retrieve data of an institution from UniLogin webservice by calling "hentInstitution" (in a controller)

```
$person = $this->UniLogin->getInstitution($instid);
```

### Retrieve data of a person from UniLogin webservice by calling "hentPerson" (in a controller)

```
$person = $this->UniLogin->getPerson($brugerid);
```

### Retrieve data of students from UniLogin webservice by calling "hentAlleElever" (in a controller)

```
$person = $this->UniLogin->getStudents($instid);
```

### Retrieve detailed data of students from UniLogin webservice by calling "hentAlleElever" and "hentPerson" for every student (in a controller)

```
$person = $this->UniLogin->getStudentsWithDetails($instid);
```
