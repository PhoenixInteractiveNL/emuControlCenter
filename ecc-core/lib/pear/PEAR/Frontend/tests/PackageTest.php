<?php
require_once 'PHPUnit2/Framework/TestCase.php';
require_once 'PEAR/Frontend/Gtk2/Packages.php';
require_once dirname(__FILE__) . '/TimeDiff.php';

class PEAR_Frontend_Gtk2_Tests_PackageTest extends PHPUnit2_Framework_TestCase
{
    protected $nCallbackCount = 0;



    public function testPearCache()
    {
        $config     = PEAR_Config::singleton();
        $cachedir   = $config->get('cache_dir');
        $this->assertTrue(is_writable($cachedir), 'PEAR cache directory is NOT writable! ' . $cachedir);
    }//public function testPearCache()



    public function testGetRemotePackages()
    {
        //first, check without extra information
        $arPacks = PEAR_Frontend_Gtk2_Packages::getRemotePackages('gnope.org', false);

        $this->assertTrue(count($arPacks) > 0);
        $inspector = self::findPackage($arPacks, 'Dev_Inspector');
        $this->assertNotNull($inspector);
        $this->assertEquals('Dev_Inspector', $inspector->getName());
        $this->assertNull($inspector->getSummary());
        $this->assertNull($inspector->getDescription());
        $this->assertNull($inspector->getCategory());
        $this->assertEquals('gnope.org', $inspector->getChannel());


        //Now, with extra information loaded
        $arPacks = PEAR_Frontend_Gtk2_Packages::getRemotePackages('gnope.org', true);

        $this->assertTrue(count($arPacks) > 0);
        $inspector = self::findPackage($arPacks, 'Dev_Inspector');
        $this->assertNotNull($inspector);
        $this->assertEquals('Dev_Inspector', $inspector->getName());
        $this->assertNotNull($inspector->getSummary());
        $this->assertNotNull($inspector->getDescription());
        $this->assertNotNull($inspector->getCategory());
        $this->assertEquals('gnope.org', $inspector->getChannel());
    }//public function testGetRemotePackages()



    public function testGetLocalPackages()
    {
        $arPacks = PEAR_Frontend_Gtk2_Packages::getLocalPackages('pear');
        $this->assertTrue(count($arPacks) > 0);
        $this->assertNotNull($arPacks['PHPUnit']);
        $this->assertNotNull($arPacks['PEAR']);
    }//public function testGetLocalPackages()



    public function testMergePackageData()
    {
        $arPacks = PEAR_Frontend_Gtk2_Packages::getLocalPackages('pear');
        $arLocalPackIds = array();
        foreach ($arPacks as $strName => $objPack) {
            $arLocalPackIds[$strName] = $strName;
            $this->assertNotNull($objPack->getInstalledVersion());
            $this->assertNull($objPack->getLatestVersion());
        }
        $nCount = count($arPacks);

        $this->nCallbackCount = 0;
        $arAllPacks = PEAR_Frontend_Gtk2_Packages::getRemotePackages('pear', true, array($this, 'packageCallback'));
        $this->assertTrue(count($arAllPacks) >= $nCount);
        $this->assertEquals(count($arAllPacks), $this->nCallbackCount);

        foreach ($arLocalPackIds as $strName) {
            $this->assertTrue(isset($arAllPacks[$strName]));
            $this->assertNotNull($arPacks[$strName]->getInstalledVersion());
        }

        foreach ($arAllPacks as $strName => $package) {
            $this->assertNotNull($package->getLatestVersion());
        }
    }//public function testMergePackageData()



    public function testGuessCategory()
    {
        $this->assertEquals('Dev'   , PEAR_Frontend_Gtk2_Package::guessCategory('Dev_Inspector'));
        $this->assertEquals('PEAR'  , PEAR_Frontend_Gtk2_Package::guessCategory('PEAR_Frontend_Gtk'));
        $this->assertEquals(''      , PEAR_Frontend_Gtk2_Package::guessCategory('DevInspector'));
    }//public function testGuessCategory()



    public function testGetCategories()
    {
        $pcks         = new PEAR_Frontend_Gtk2_Packages('pear');
        $arCategories = $pcks->getCategories();
        $arPackages   = $pcks->getPackages();
        $this->assertTrue(count($arPackages) > 0);
        foreach ($arPackages as $package) {
            $this->assertTrue(isset($arCategories[$package->getCategory()]));
        }
    }//public function testGetCategories()



    public function packageCallback($nPackageCount, $nCurrentPackage)
    {
        if ($nPackageCount !== true) {
            $this->nCallbackCount++;
        }
//        echo $nCurrentPackage . '/' . $nPackageCount . "\r\n";
    }//public function packageCallback($nPackageCount, $nCurrentPackage)



    protected static function findPackage($arPackages, $strPackageName)
    {
        foreach ($arPackages as $nId => $package) {
            if ($package->getName() == 'Dev_Inspector') {
                break;
            }
        }
        if ($arPackages[$nId]->getName() !== $strPackageName) {
            return null;
        } else {
            return $arPackages[$nId];
        }
    }//protected static function findPackage($arPackages, $strPackageName)

}//class PEAR_Frontend_Gtk2_Tests_PackageTest extends PHPUnit_TestCase

?>