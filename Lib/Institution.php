<?php
/**
 * Institution class.
 *
 */
class Institution {

/**
 * 6-char institution number (from Danmarks Statistik, e.g. 101001).
 *
 * @var string
 */
	public $instnr;

/**
 * Name of institution.
 *
 * @var string
 */
	public $navn;

/**
 * 3-digit number, e.g. 121 (se Appendix 1.6.1).
 *
 * @var string
 */
	public $type;

/**
 * Name of type in Danish e.g. ”Grundskoler” (see Appendix 1.6.1).
 *
 * @var string
 */
	public $typenavn;

/**
 * Address.
 *
 * @var string
 */
	public $adresse;

/**
 * City name.
 *
 * @var string
 */
	public $bynavn;

/**
 * Zip code.
 *
 * @var string
 */
	public $postnr;

/**
 * Phone number.
 *
 * @var string
 */
	public $telefonnr;

/**
 * Fax number.
 *
 * @var string
 */
	public $faxnr;

/**
 * Mail address of the institution as stated in "Institutionsregistret".
 *
 * @var string
 */
	public $mailadresse;

/**
 * URL of institution
 *
 * @var string
 */
	public $www;

/**
 * 6-char institution nummer of main institution (from Danmarks Statistik, e.g. 101001).
 * Null if the current institution is the main institution.
 *
 * @var string
 */
	public $hovedinstitutionsnr;

/**
 * 3-digit code identifying the municipal where the institution is located.
 *
 * @var string
 */
	public $kommunenr;

/**
 * Name of municipal where the institution is located.
 *
 * @var string
 */
	public $kommune;

/**
 * 3-dige code identifying the municipal of the administrating municipal (see Appendix 1.6.2).
 *
 * @var string
 */
	public $admkommunenr;

/**
 * Name of the administrating municipal (see Appendix 1.6.2).
 * May also be a 4-digit code identifying the region. (see Appendix 1.6.4).
 *
 * @var string
 */
	public $admkommune;

/**
 * 4-digit code identifying the region (see Appendix 1.6.4).
 *
 * @var string
 */
	public $regionsnr;

/**
 * Name of the region (see Appendix 1.6.4).
 *
 * @var string
 */
	public $region;

}
