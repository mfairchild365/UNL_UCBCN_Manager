<?php
/**
 * Test suite for UNL_UCBCN_Manager
 *
 * PHP versions 5
 *
 * @category Events
 * @package  UNL_UCBCN_Manager
 * @author   Brett Bieber <brett.bieber@gmail.com>
 */

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'UNL_UCBCN_Manager_AllTests::main');
}

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';

require_once dirname(__FILE__) . '/UNL/UCBCN/ManagerTest.php';
require_once dirname(__FILE__) . '/CreateEventTest.php';
require_once dirname(__FILE__) . '/EventActionsTest.php';

class UNL_UCBCN_Manager_AllTests
{
	/**
     * Runs the test suite.
     *
     * @return unknown
     */
    public static function main()
    {

        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    /**
     * Adds the UNL_UCBCN_ManagerTest suite.
     *
     * @return $suite
     */
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('UNL_UCBCN_Manager tests');
        /** Add testsuites, if there is. */
        $suite->addTestSuite('UNL_UCBCN_ManagerTest');
        $suite->addTestSuite('UNL_UCBCN_Manager_CreateEventTest');
        $suite->addTestSuite('UNL_UCBCN_Manager_EventActionsTest');
        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'UNL_UCBCN_Manager_AllTests::main') {
    UNL_UCBCN_Manager_AllTests::main();
}
?>