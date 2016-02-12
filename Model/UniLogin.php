<?php
App::uses('UniLoginWebserviceAppModel', 'UniLoginWebservice.Model');

/**
 * UniLogin Model
 *
 * @package       UniLoginWebservice.Model
 */
class UniLogin extends UniLoginWebserviceAppModel {

/**
 * The name of the DataSource connection that this Model uses.
 *
 * @var string
 */
	public $useDbConfig = 'uniLoginWebservice';

/**
 * Use table.
 *
 * @var mixed False or table name
 */
	public $useTable = false;

/**
 * Returns authentication parameters
 *
 * @return array Authentication parameters
 */
	protected function _getAuthParameters() {
		$params = array(
			'wsBrugerid' => Configure::read('UniLoginWebservice.wsBrugerid'),
			'wsPassword' => Configure::read('UniLoginWebservice.wsPassword')
		);
		return $params;
	}

/**
 * Extracts "return"-property from given object
 *
 * @param object $data Data to extract "return"-property from
 * @return mixed The extracted property (mixed), or false (bool) on failure
 */
	protected function _extractResult($data) {
		$result = false;
		if (is_object($data)) {
			$property = 'return';
			if (property_exists($data, $property)) {
				$result = $data->{$property};
			}
		}
		return $result;
	}

/**
 * Test method that only requires that the firewall
 * is open for the calling IP-number.
 *
 * @return string
 */
	public function helloWorld() {
		return $this->query('helloWorld');
	}

/**
 * Test method that only requires that the firewall
 * is open for the calling IP-number.
 *
 * @return string
 */
	public function helloSOAPFaultDemo() {
		return $this->query('helloSOAPFaultDemo');
	}

/**
 * Returns most information about an institution.
 *
 * Wrapper for API call hentInstitution
 *
 * @param string $instid 6-char institution number (from Danmarks Statistik, e.g. 101001).
 * @return array Institution data
 */
	public function getInstitution($instid) {
		$params = $this->_getAuthParameters();
		$params['instid'] = $instid;
		$result = $this->query('hentInstitution', $params);
		if ($result = $this->_extractResult($result)) {
			$result = $this->_convertInstitution($result);
		}
		return $result;
	}

/**
 * Returns information about a person “brugerid”.
 *  “Institutionsnummer” is the user’s primary institution
 *  and “funktionsmarkering” is the relation
 *  to this institution. Both may be empty if the
 *  user has no primary institution. Use the method
 *  hentInstitutionsliste() to get a list of the institutions
 *  where the user has a relation.
 *
 *  Wrapper for API call hentPerson
 *
 * @param string $brugerid Unique UNI•Login user id.
 * @return array Person data
 */
	public function getPerson($brugerid) {
		$params = $this->_getAuthParameters();
		$params['brugerid'] = $brugerid;
		$result = $this->query('hentPerson', $params);
		if ($result = $this->_extractResult($result)) {
			$result = $this->_convertUser($result);
		}
		return $result;
	}

/**
 * Returns a list of employees at the institution “instnr”.
 *
 *  Wrapper for API call hentAnsatte
 *
 * @param string $instid 6-char institution number (from Danmarks Statistik, e.g. 101001).
 * @return array List of employees
 */
	public function getEmployees($instid) {
		$params = $this->_getAuthParameters();
		$params['instid'] = $instid;
		$result = $this->query('hentAnsatte', $params);
		if ($result = $this->_extractResult($result)) {
			$result = $this->_convertUserList($result);
		}
		return $result;
	}

/**
 * Returns a list of employees with detailed person information at the institution “instnr”.
 *
 * @param string $instid 6-char institution number (from Danmarks Statistik, e.g. 101001).
 * @return array List of employees
 */
	public function getEmployeesWithDetails($instid) {
		$result = array();
		$employees = $this->getEmployees($instid);
		if (!empty($employees)) {
			foreach ($employees as $employee) {
				$result[] = $this->getPerson($employee['uni_login_key']);
			}
		}
		return $result;
	}

/**
 * Returns a list of all pupils and students at the institution “instnr”.
 *
 *  Wrapper for API call hentAlleElever
 *
 * @param string $instid 6-char institution number (from Danmarks Statistik, e.g. 101001).
 * @return array List of pupils and students
 */
	public function getStudents($instid) {
		$params = $this->_getAuthParameters();
		$params['instid'] = $instid;
		$result = $this->query('hentAlleElever', $params);
		if ($result = $this->_extractResult($result)) {
			$result = $this->_convertUserList($result);
		}
		return $result;
	}

/**
 * Returns a list of students with detailed person information at the institution “instnr”.
 *
 * @param string $instid 6-char institution number (from Danmarks Statistik, e.g. 101001).
 * @return array List of students
 */
	public function getStudentsWithDetails($instid) {
		$result = array();
		$students = $this->getStudents($instid);
		if (!empty($students)) {
			foreach ($students as $student) {
				$result[] = $this->getPerson($student['uni_login_key']);
			}
		}
		return $result;
	}

/**
 * Converts array of Uni-Login PersonSimpel objects
 *
 * @param array $userList Array of Uni-Login PersonSimpel objects
 * @return mixed Converted institution data (array), or false (bool) on failure
 */
	protected function _convertUserList($userList) {
		$result = false;
		if (is_object($userList)) {
			$property = 'PersonSimpel';
			if (property_exists($userList, $property)) {
				if (is_array($userList->{$property})) {
					$minimal = true;
					$result = array();
					foreach ($userList->{$property} as $user) {
						if ($item = $this->_convertUser($user, $minimal)) {
							$result[] = $item;
						} else {
							$result = false;
							break;
						}
					}
				}
			}
		}
		return $result;
	}

/**
 * Converts Uni-Login Institution object
 *
 * @param stdClass $institution Uni-Login Institution object
 * @return mixed Converted institution data (array), or false (bool) on failure
 */
	protected function _convertInstitution($institution) {
		$mapping = array(
			'uni_login_key' => 'Instnr',
			'name' => 'Navn',
			'type' => 'Type',
			'type_name' => 'Typenavn',
			'address' => 'Adresse',
			'city' => 'Bynavn',
			'zip_code' => 'Postnr',
			'phone_number' => 'Telefonnr',
			'fax_number' => 'Faxnr',
			'mail_address' => 'Mailadresse',
			'website' => 'Www',
			'parent_institution_uni_login_key' => 'Hovedinstitutionsnr',
			'municipal' => 'Kommunenr',
			'municipal_name' => 'Kommune',
			'administrating_municipal' => 'Admkommunenr',
			'administrating_municipal_name' => 'Admkommune',
			'region' => 'Regionsnr',
			'region_name' => 'Region',
		);

		$result = false;
		if (is_object($institution)) {
			$result = array();
			foreach ($mapping as $name => $property) {
				if (!property_exists($institution, $property)) {
					$result = false;
					break;
				}
				$result[$name] = $institution->{$property};
			}
		}

		return $result;
	}

/**
 * Converts a Uni-Login role
 *
 * @param string $role Uni-Login role
 * @return mixed Converted role (string), or false (bool) on failure
 */
	protected function _convertRole($role) {
		$mapping = array(
			'lærer' => 'teacher',
			'tap' => 'technical / administrative employee',
			'pæd' => 'educator', // Pædagog
			'elev' => 'pupil',
			'stud' => 'student',
			'kursist' => 'anonymous user with limited lifespan',
			'klasse' => 'class',
			'skole' => 'common school login',
			'Instleder' => 'director at institution',
			'Instledelse' => 'board of directors',
			'Brugeradm' => 'user administrator',
			'brugeradm_sup' => 'additional user administrator',
			'Kontakt' => 'contact person at institution',
			'uni_server_adm' => 'UNI-Server administrator',
			'uni_server_indholds_adm' => 'UNI-Server Content administrator',
			'hjpc_ansv' => 'HomePC responsible',
			'hjpc_ansv_a' => 'HomePC responsible for A-leg',
			'hjpc_ansv_p' => 'HomePC responsible for P-leg'
		);
		return Hash::get($mapping, $role)?:false;
	}

/**
 * Converts Uni-Login Person or PersonSimpel object
 *
 * @param stdClass $user Uni-Login Person or PersonSimpel object
 * @param bool $minimal Whether or the given user object is a PersonSimpel object
 * @return array mixed Converted user data (array), or false (bool) on failure
 */
	protected function _convertUser($user, $minimal = false) {
		$mapping = array(
			'uni_login_key' => 'Brugerid',
			'full_name' => 'Navn',
			'first_name' => 'Fornavn',
			'last_name' => 'Efternavn',
			'username' => 'SkolekomNavn',
			'email' => 'Mailadresse',
			'school_uni_login_key' => 'Instnr',
			'role' => 'Funktionsmarkering',
			'date_of_birth' => 'Foedselsdag'
		);

		if ($minimal) {
			$mapping = array(
				'uni_login_key' => 'Brugerid',
				'full_name' => 'Navn'
			);
		}

		$result = false;
		if (is_object($user)) {
			$result = array();
			foreach ($mapping as $name => $property) {
				if (!property_exists($user, $property)) {
					$result = false;
					break;
				}
				$result[$name] = $user->{$property};
			}
		}

		if ($result) {
			if ($role = Hash::get($result, 'role')) {
				$result['role'] = $this->_convertRole($role);
			}
			if ($dateOfBirth = Hash::get($result, 'date_of_birth')) {
				$result['date_of_birth'] = $this->_parseDate($dateOfBirth);
			}
		}
		return $result;
	}

/**
 * Parse Uni-Login formatted date string
 *
 * @param string $dateString Uni-Login formatted date string (ddmmyy)
 * @return string Formatted date string (yyyy-mm-dd)
 * @return mixed Formatted date (string), or false (bool) on failure
 */
	protected function _parseDate($dateString) {
		$format = 'dmy';
		if ($result = date_create_from_format($format, $dateString)) {
			$result = date_format($result, 'Y-m-d');
		}
		return $result;
	}

}
