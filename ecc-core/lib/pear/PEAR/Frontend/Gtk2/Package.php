<?php
/**
*   A single PEAR package with some of its data
*   like name, category, installed version, latest version,
*   summary and description
*
*   @author Christian Weiske <cweiske@php.net>
*/
class PEAR_Frontend_Gtk2_Package
{
    protected $bFullyLoaded         = false;

    protected $strName              = null;
    protected $strChannel           = null;
    protected $strCategory          = null;
    protected $strSummary           = null;
    protected $strDescription       = null;
    protected $strVersionLatest     = null;
    protected $strVersionInstalled  = null;

    /**
    *   Constructs a package object
    *
    *   @param string $strName  the package name
    */
    public function __construct($strName, $strChannel)
    {
        $this->setName($strName);
        $this->setChannel($strChannel);
        $this->setFullyLoaded(false);
    }//public function __construct($strName, $strChannel)



    /**
    *   Loads the package information array from
    *   PEAR_Registry::packageInfo() into the local variables
    */
    public function incorporateLocalInfo($arPackageInfo)
    {
        $this->setSummary($arPackageInfo['summary']);
        $this->setDescription($arPackageInfo['description']);

        if (is_array($arPackageInfo['version'])) {
            $this->setInstalledVersion($arPackageInfo['version']['release']);
        } else {
            $this->setInstalledVersion($arPackageInfo['version']);
        }
//require_once 'Gtk2/VarDump.php'; new Gtk2_VarDump($arPackageInfo, '$arPackageInfo');
    }//public function incorporateLocalInfo($arPackageInfo)



    /**
    *   Reloads the local package info.
    *   Useful if the package has been installed or uninstalled
    */
    public function refreshLocalInfo()
    {
        $config = PEAR_Config::singleton();
        $arData = $config->getRegistry()->packageInfo($this->getName(), null, $this->getChannel());

        if ($arData === null) {
            //no local package data -> uninstalled or not available
            $this->setInstalledVersion(null);
        } else {
            $this->incorporateLocalInfo($arData);
        }
    }//public function refreshLocalInfo()



    /**
    *   Tries to guess the category name from the package name
    *       e.g. Dev_Inspector should have "Dev" as category
    *   If no category can be guessed, "" (empty string) will
    *       be returned
    *
    *   @param  string  $strName    The package name
    *   @return string  The guessed category name
    */
    public static function guessCategory($strName)
    {
        $nPos = strpos($strName, '_');
        if ($nPos !== false) {
            $strCategory = substr($strName, 0, $nPos);
        } else {
            //no underscore
            $strCategory = $strName;
        }
        return $strCategory;
    }//public static function guessCategory($strName)



    public function getName() {
        return $this->strName;
    }



    public function getChannel() {
        return $this->strChannel;
    }



    public function getSummary() {
        return $this->strSummary;
    }



    public function getDescription() {
        return $this->strDescription;
    }



    public function getCategory()
    {
        return $this->strCategory;
    }//public function getCategory()



    function getInstalledVersion()
    {
        return $this->strVersionInstalled;
    }//function getInstalledVersion()



    function getLatestVersion()
    {
        return $this->strVersionLatest;
    }//function getLatestVersion()






    function setName($strName)
    {
        $this->strName = $strName;
    }//function setName($strName)



    function setCategory($strCategory)
    {
        $this->strCategory = $strCategory;
    }//function setCategory($strCategory)



    function setChannel($strChannel)
    {
        $this->strChannel = $strChannel;
    }//function setChannel($strChannel)



    function setSummary($strSummary)
    {
        $this->strSummary = $strSummary;
    }//function setSummary($strSummary)



    function setDescription($strDescription)
    {
        $this->strDescription = $strDescription;
    }//function setDescription($strDescription)



    function setFullyLoaded($bFullyLoaded)
    {
        $this->bFullyLoaded = $bFullyLoaded;
    }//function setFullyLoaded($bFullyLoaded)



    function setInstalledVersion($strVersionInstalled)
    {
        if ($strVersionInstalled == '') {
            $strVersionInstalled = null;
        }
        $this->strVersionInstalled = $strVersionInstalled;
    }//function setInstalledVersion($strVersionInstalled)



    function setLatestVersion($strVersionLatest)
    {
        if (!is_string($strVersionLatest)) {
            $strVersionLatest = '?';
        }
        if ($strVersionLatest == '') {
            $strVersionLatest = null;
        }
        $this->strVersionLatest = $strVersionLatest;
    }//function setInstalledVersion($strVersionLatest)

}//class PEAR_Frontend_Gtk2_Package
?>