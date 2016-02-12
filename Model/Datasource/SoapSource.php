<?php
/**
 * SOAP Datasource
 *
 * PHP Version 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         CakePHP Datasources v 0.3
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * SoapSource
 *
 */
class SoapSource extends DataSource {

/**
 * Description
 *
 * @var string
 */
	public $description = 'Soap Client DataSource';

/**
 * SoapClient instance
 *
 * @var SoapClient
 */
	public $client = null;

/**
 * Connection status
 *
 * @var bool
 */
	public $connected = false;

/**
 * Default configuration
 *
 * @var array
 */
	protected $_baseConfig = array(
		'wsdl' => null,
		'location' => '',
		'uri' => '',
		'login' => '',
		'password' => '',
		'authentication' => 'SOAP_AUTHENTICATION_BASIC');

/**
 * Constructor
 *
 * @param array $config An array defining the configuration settings
 */
	public function __construct($config = array()) {
		parent::__construct($config);
		$this->connect();
	}

/**
 * Setup Configuration options
 *
 * @return array Configuration options
 */
	protected function _parseConfig() {
		if (!class_exists('SoapClient')) {
			$this->showError('Class SoapClient not found, please enable Soap extensions');
			return false;
		}
		$options = array('trace' => Configure::read('debug') > 0);
		if (!empty($this->config['location'])) {
			$options['location'] = $this->config['location'];
		}
		if (!empty($this->config['uri'])) {
			$options['uri'] = $this->config['uri'];
		}
		if (!empty($this->config['login'])) {
			$options['login'] = $this->config['login'];
			$options['password'] = $this->config['password'];
			$options['authentication'] = $this->config['authentication'];
		}
		return $options;
	}

/**
 * Connects to the SOAP server using the WSDL in the configuration
 *
 * @return bool True on success, false on failure
 */
	public function connect() {
		$options = $this->_parseConfig();
		try {
			$this->client = new SoapClient($this->config['wsdl'], $options);
		} catch(SoapFault $fault) {
			$this->showError($fault->faultstring);
		}

		if ($this->client) {
			$this->connected = true;
		}
		return $this->connected;
	}

/**
 * Sets the SoapClient instance to null
 *
 * @return bool True
 */
	public function close() {
		$this->client = null;
		$this->connected = false;
		return true;
	}

/**
 * Returns the available SOAP methods
 *
 * @return array List of SOAP methods
 */
	public function listSources() {
		return $this->client->__getFunctions();
	}

/**
 * Query the SOAP server with the given method and parameters
 *
 * @param string $method Name of method to call
 * @param array $queryData A list with parameters to pass
 * @return mixed Returns the result on success, false on failure
 */
	public function query($method, $queryData = array()) {
		$this->error = false;
		if (!$this->connected) {
			return false;
		}

		if (!empty($queryData)) {
			$queryData = array($queryData);
		}

		try {
			$result = $this->client->__soapCall($method, $queryData);
		} catch (SoapFault $fault) {
			$this->showError($fault->faultstring);
			return false;
		}
		return $result;
	}

/**
 * Returns the last SOAP response
 *
 * @return string The last SOAP response
 */
	public function getResponse() {
		return $this->client->__getLastResponse();
	}

/**
 * Returns the last SOAP request
 *
 * @return string The last SOAP request
 */
	public function getRequest() {
		return $this->client->__getLastRequest();
	}

/**
 * Writes an error message to log file
 *
 * @param string $error Error message
 * @return string The last SOAP response
 */
	public function showError($error) {
		$message = __d('uni_login_webservice', 'SOAP Error: %s', $error);
		CakeLog::error($message);
	}

}
