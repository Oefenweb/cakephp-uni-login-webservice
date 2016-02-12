<?php
/**
 * Person class
 *
 * @package       UniLoginWebservice.Lib
 */
class Person {

/**
 * Unique UNI•Login user id.
 *
 * @var string
 */
	public $brugerid;

/**
 * Full name of person
 *
 * @var string
 */
	public $navn;

/**
 * First name if available from administrative system. Otherwise defined
 * as the full name except the last word.
 * Note: May be an empty string If the full name only has one word.
 *
 * @var string
 */
	public $fornavn;

/**
 * Last name if available from administrative system. Otherwise defined
 * as the last word in the full name.
 *
 * @var string
 */
	public $efternavn;

/**
 * E.g.: ”Svend Hansen12”.
 *
 * @var string
 */
	public $skolekomNavn;

/**
 * User provided email address if available. Otherwise
 * SkoleKom mail address consisting of skolekomNavn with space
 * characters replaced by “.” and followed by ”@skolekom.dk”.
 * E.g.: ”Svend.Hansen12@skolekom.dk”.
 *
 * @var string
 */
	public $mailadresse;

/**
 * 6-char institution number of primary institution(from Danmarks
 * Statistik, e.g. 101001).
 * A user may be related to more than one institution but only has
 * one “primary” relation.
 *
 * @var string
 */
	public $instnr;

/**
 * Role at primary institution. “lærer”,” tap”, “pæd”, “elev”, “stud”,
 * “kursist”, “klasse”, “skole” (see description of roles in Appendix
 * 1.6.5). A user has a primary role, if there is a primary institution.
 *
 * @var string
 */
	public $funktionsmarkering;

/**
 * Birthday of the person. E.g.: 130597
 *
 * @var string
 */
	public $foedselsdag;

}

