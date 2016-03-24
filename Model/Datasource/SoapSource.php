<?php
App::uses('DataSource', 'Model/Datasource');

/**
 * SOAP DataSource.
 *
 */
class SoapSource extends DataSource {

/**
 * Description.
 *
 * @var string
 */
	public $description = 'Soap Client DataSource';

/**
 * SoapClient instance.
 *
 * @var SoapClient
 */
	public $client = null;

/**
 * Connection status.
 *
 * @var bool
 */
	public $connected = false;

/**
 * Default configuration.
 *
 * @var array
 */
	protected $_baseConfig = [
		'wsdl' => null,
		'location' => '',
		'uri' => '',
		'login' => '',
		'password' => '',
		'authentication' => 'SOAP_AUTHENTICATION_BASIC'
	];

/**
 * Constructor.
 *
 * @param array $config An array defining the configuration settings
 */
	public function __construct($config = []) {
		parent::__construct($config);

		$this->connected = $this->connect();
	}

/**
 * Setup Configuration options.
 *
 * @return array Configuration options
 */
	protected function _parseConfig() {
		if (!class_exists('SoapClient')) {
			$this->showError('Class SoapClient not found, please enable Soap extensions');
			return false;
		}

		$options = ['trace' => Configure::read('debug') > 0];
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
 * Connects to the SOAP server using the WSDL in the configuration.
 *
 * @return bool True on success, false on failure
 */
	public function connect() {
		$options = $this->_parseConfig();

		if (!empty($this->config['wsdl'])) {
			try {
				$this->client = new SoapClient($this->config['wsdl'], $options);
				return (bool)$this->client;
			} catch(SoapFault $fault) {
				$this->showError($fault->faultstring);
			}
		}

		return false;
	}

/**
 * Sets the SoapClient instance to null.
 *
 * @return bool True
 */
	public function close() {
		$this->client = null;
		$this->connected = false;

		return true;
	}

/**
 * Returns the available SOAP methods.
 *
 * @param mixed $data Unused in this class.
 * @return array List of SOAP methods
 */
	public function listSources($data = null) {
		return $this->client->__getFunctions();
	}

/**
 * Query the SOAP server with the given method and parameters.
 *
 * @param string $method Name of method to call
 * @param array $queryData A list with parameters to pass
 * @return mixed Returns the result on success, false on failure
 */
	public function query($method, $queryData = []) {
		$this->error = false;
		if (!$this->connected) {
			return false;
		}

		if (!empty($queryData)) {
			$queryData = [$queryData];
		}

		try {
			return $this->client->__soapCall($method, $queryData);
		} catch (SoapFault $fault) {
			$this->showError($fault->faultstring);
			return false;
		}
	}

/**
 * Returns the last SOAP response.
 *
 * @return string The last SOAP response
 */
	public function getResponse() {
		return $this->client->__getLastResponse();
	}

/**
 * Returns the last SOAP request.
 *
 * @return string The last SOAP request
 */
	public function getRequest() {
		return $this->client->__getLastRequest();
	}

/**
 * Writes an error message to log file.
 *
 * @param string $error Error message
 * @return string The last SOAP response
 */
	public function showError($error) {
		$message = __d('uni_login_webservice', 'SOAP Error: %s', $error);
		CakeLog::error($message);
	}

}
