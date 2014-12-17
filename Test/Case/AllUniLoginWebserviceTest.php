<?php
/**
 * All UniLoginWebservice plugin tests
 */
class AllUniLoginWebserviceTest extends CakeTestCase {

/**
 * Suite define the tests for this plugin
 *
 * @return void
 */
	public static function suite() {
		$suite = new CakeTestSuite('All UniLoginWebservice test');

		$path = CakePlugin::path('UniLoginWebservice') . 'Test' . DS . 'Case' . DS;
		$suite->addTestDirectoryRecursive($path);

		return $suite;
	}

}
