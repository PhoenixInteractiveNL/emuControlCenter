<?php
/**
*   PEAR_Frontend_Gtk2 unit tests
*   @author Christian Weiske <cweiske@cweiske.de>
*/

if (!defined('PHPUnit2_MAIN_METHOD')) {
    define('PHPUnit2_MAIN_METHOD', 'PEAR_Frontend_Gtk2_Tests_AllTests::main');
}

require_once 'PHPUnit2/Framework/TestSuite.php';
require_once 'PHPUnit2/TextUI/TestRunner.php';
require_once 'PackageTest.php';
require_once 'ConfigTest.php';

class PEAR_Frontend_Gtk2_Tests_AllTests
{

    public static function main()
    {
        PHPUnit2_TextUI_TestRunner::run(self::suite());
    }

    public static function suite()
    {
        $suite = new PHPUnit2_Framework_TestSuite('PHPUnit Framework');
//        $suite->addTestSuite('PEAR_Frontend_Gtk2_Tests_PackageTest');
//        $suite->addTestSuite('PEAR_Frontend_Gtk2_Tests_ConfigTest');

        return $suite;
    }
}

if (PHPUnit2_MAIN_METHOD == 'PEAR_Frontend_Gtk2_Tests_AllTests::main') {
    PEAR_Frontend_Gtk2_Tests_AllTests::main();
}
?>