<?php
require_once 'PHPUnit2/Framework/TestCase.php';
require_once 'PEAR/Frontend/Gtk2/Config.php';
require_once dirname(__FILE__) . '/TimeDiff.php';

class PEAR_Frontend_Gtk2_Tests_ConfigTest extends PHPUnit2_Framework_TestCase
{
    public function testRead()
    {
        var_dump(PEAR_Frontend_Gtk2_Config::loadConfig());
    }//public function testRead()



    public function testSave()
    {
        PEAR_Frontend_Gtk2_Config::saveConfig();
    }//public function testSave()

}//class PEAR_Frontend_Gtk2_Tests_ConfigTest extends PHPUnit2_Framework_TestCase
?>