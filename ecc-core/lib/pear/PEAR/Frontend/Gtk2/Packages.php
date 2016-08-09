<?php
require_once 'PEAR/Frontend/Gtk2/Package.php';
require_once 'PEAR/Config.php';

/**
*   This class deals with packages: local package loading,
*   remote package loading and so.
*
*   The callback is called after everything has been finished 
*   with parameters (true, true) to signal that it's done.
*
*   @author Christian Weiske <cweiske@php.net>
*/
class PEAR_Frontend_Gtk2_Packages
{
    /**
    *   The currently active channel name
    *   @var string
    */
    protected $strActiveChannel = null;

    /**
    *   Array of channel => package arrays
    *   @var array
    */
    protected $arPackages = null;

    /**
    *   Array of channel => category strings
    *   @var array
    */
    protected $arCategories = null;

    public static $EXCEPTION_NO_REST = 451;



    public function __construct($strActiveChannel = null)
    {
        $this->setActiveChannel($strActiveChannel);
    }//public function __construct($strActiveChannel = null)



    public function setActiveChannel($strActiveChannel)
    {
        $this->strActiveChannel = $strActiveChannel;
    }//public function setActiveChannel($strActiveChannel)



    public function getActiveChannel()
    {
        return $this->strActiveChannel;
    }//public function getActiveChannel()



    /**
    *   Returns a list with all packages, or all packages in the given category.
    *   Package list is cached, so that it's faster after the first call.
    *
    *   @param string   $strCategory    If given, the list is filtered by category name
    *   @param callback $callback       The function to call before a package information is downloaded
    *   @param booleab  $bWorkOffline   If the user wants to work offline (don't connect to the internet)
    *
    *   @return array   Array of PEAR_Frontend_Gtk2_Package's
    */
    public function getPackages($strCategory = null, $callback = null, $bWorkOffline = false)
    {
        if (!$this->packagesLoaded()) {
            $this->arPackages[$this->strActiveChannel] = PEAR_Frontend_Gtk2_Packages::getAllPackages($this->strActiveChannel, $callback, $bWorkOffline);
        }
        if ($strCategory === null) {
            return $this->arPackages[$this->strActiveChannel];
        }

        $arSelectedPackages = array();
        foreach ($this->arPackages[$this->strActiveChannel] as $strName => $package) {
            if ($package->getCategory() == $strCategory) {
                $arSelectedPackages[$strName] = $package;
            }
        }
        return $arSelectedPackages;
    }//public function getPackages($strCategory = null, $callback = null, $bWorkOffline = false)



    /**
    *   Gets an array of packages for the active channel
    *   (local and remote packages)
    *
    *   @param string   $strChannel     Channel to get packages for
    *   @param callback $callback       The callback to call for each single remote package
    *   @param boolean  $bWorkOffline   If the users wants to work offline
    *
    *   @return array   Array of Packages
    */
    public static function getAllPackages($strChannel, $callback = null, $bWorkOffline = false)
    {
        return PEAR_Frontend_Gtk2_Packages::getRemotePackages(
                    $strChannel,
                    true,
                    $callback,
                    PEAR_Frontend_Gtk2_Packages::getLocalPackages($strChannel),
                    $bWorkOffline
                );
    }//public static function getAllPackages($strChannel, $callback = null, $bWorkOffline = false)



    /**
    *   Loads the locally installed packages of the given
    *   channel
    *
    *   @param  string  $strChannel         The channel name
    *   @param  array   $arLocalPackages    Array with already existing local packages. If set, package info will be incorporated.
    *   @return array   Array of locally installed packages
    *   @static
    */
    public static function getLocalPackages($strChannel, $arLocalPackages = array())
    {
        $config        = PEAR_Config::singleton();
        $arRawPackages = $config->getRegistry()->packageInfo(null, null, $strChannel);
        $arExisting    = is_null($arLocalPackages) ? array() : array_keys($arLocalPackages);
        $arFound       = array();

        foreach ($arRawPackages as $strPackageId => $dontuseit) {
            if (isset($arRawPackages[$strPackageId]['name'])) {
                $strPackageName = $arRawPackages[$strPackageId]['name'];
            } else {
                $strPackageName = $arRawPackages[$strPackageId]['package'];
            }
            if (!isset($arLocalPackages[$strPackageName])) {
                $arLocalPackages[$strPackageName] = new PEAR_Frontend_Gtk2_Package($strPackageName, $strChannel);
                $arLocalPackages[$strPackageName]->setCategory(PEAR_Frontend_Gtk2_Package::guessCategory($strPackageName));
            }
            $arLocalPackages[$strPackageName]->incorporateLocalInfo($arRawPackages[$strPackageId]);
            $arFound[$strPackageName] = true;
        }

        foreach ($arExisting as $strId => $strPackageName) {
            if (isset($arFound[$strPackageName])) {
                unset($arExisting[$strId]);
            }
        }

        //The ones still in here aren't installed locally any more
        foreach ($arExisting as $strPackageName) {
            $arLocalPackages[$strPackageName]->setInstalledVersion(null);
        }

        return $arLocalPackages;
    }//public static function getLocalPackages($strChannel, $arLocalPackages = array())



    /**
    *   Loads the remotely available packages for the given channel
    *
    *   @param string   $strChannel         The channel name
    *   @param boolean  $bLoadPackageData   If all the package data shall be loaded, not only the names
    *   @param callback $callback           The callback to call after a package info has been loaded
    *   @param array    $arExistingPackages Array with local packages already loaded
    *   @param boolean  $bWorkOffline       If we should use the local cache if possible
    *
    *   @return array   Array of remotely available packages (PEAR_Frontend_Gtk2_Package)
    *   @throws Exception If the channel has no REST support or if the connection failed
    *   @static
    */
    public static function getRemotePackages($strChannel, $bLoadPackageData = true, $callback = null, $arExistingPackages = array(), $bWorkOffline = false)
    {
        $config     = PEAR_Config::singleton();
        $channel    = $config->getRegistry()->getChannel($strChannel);
        $strBaseUrl = $channel->getBaseURL('REST1.0');
        $strServer  = $channel->getServer();
        $rest       = $config->getREST('1.0');

        if ($channel->supportsREST($strServer) && $strBaseUrl) {
            $arXmlPackages = PEAR_Frontend_Gtk2_Packages::listPackages($strChannel, $bWorkOffline);
            if (PEAR::isError($arXmlPackages)) {
                throw new Exception(
                    'Error listing packages on channel ' . $strChannel . ":\r\n"
                        . $arXmlPackages->getMessage(),
                    $arXmlPackages->getCode()
                );
            }
        } else {
            throw new Exception($strChannel . ' has no REST support', PEAR_Frontend_Gtk2_Packages::$EXCEPTION_NO_REST);
        }

        if (count($arXmlPackages) == 0
            || (count($arXmlPackages) == 1 && $arXmlPackages[0] === null)
        ) {
            //No packages...
            return $arExistingPackages;
        }

        foreach ($arXmlPackages as $id => $strName) {
            if (!isset($arExistingPackages[$strName])) {
                $arExistingPackages[$strName] = new PEAR_Frontend_Gtk2_Package($strName, $strChannel);
            }
        }

        if ($bLoadPackageData === false) {
            return $arExistingPackages;
        } else {
            return PEAR_Frontend_Gtk2_Packages::loadRemotePackageData($arExistingPackages, $strChannel, $callback, $bWorkOffline);
        }
    }//public static function getRemotePackages($strChannel, $bLoadPackageData = true, $callback = null, $arExistingPackages = array(), $bWorkOffline = false)



    /**
    *   Copy of the PEAR_REST#listPackages function with the change
    *   that this one is able to get the list from the local cache.
    *
    *   @param string   $strChannel     The channel which packages shall be listed
    *   @param boolean  $bWorkOffline   If false, the local cache is used - if true, the online list is loaded
    *
    *   @return mixed   PEAR_Error or array (package list)
    */
    public static function listPackages($strChannel, $bWorkOffline = false)
    {
        $config     = PEAR_Config::singleton();
        $channel    = $config->getRegistry()->getChannel($strChannel);
        $strBaseUrl = $channel->getBaseURL('REST1.0');
        $strServer  = $channel->getServer();
        $rest       = $config->getREST('1.0');

        if ($bWorkOffline) {
            //We can't use the retrieveCacheFirst function as it would
            //try to connect if the local cache is empty
            $url       = $strBaseUrl . 'p/packages.xml';
            $cachefile = $config->get('cache_dir') . DIRECTORY_SEPARATOR . md5($url) . 'rest.cachefile';
            if (@file_exists($cachefile)) {
                //local package list cache file
                $arCache = unserialize(implode('', file($cachefile)));
                if (PEAR::isError($arCache)) {
                    return $arCache;
                }
                if (!is_array($arCache) || !isset($arCache['p'])) {
                    return array();
                }
                if (!is_array($arCache['p'])) {
                    $arCache['p'] = array($arCache['p']);
                }
                return $arCache['p'];

            } else {
                //no local cache -> return empty package list
                return array();
            }
        } else {
            //online => list online packages
            return $rest->listPackages($strBaseUrl);
        }
    }//public static function listPackages($strChannel, $bWorkOffline = false)




    /**
    *   Loads all available information for the given packages
    *
    *   @param array    $arRemotePackages   Array with PEAR_Frontend_Gtk2_Package-s
    *   @param string   $strChannel         The channel name to retrieve packages from
    *   @param callback $callback           The callback to call after a package info has been loaded
    *   @param boolean  $bWorkOffline       If the local cache *only* shall be used, or (if no local cache is there) 
    *                                           an connection to the internet server shall be made
    *   @param boolean  $bUseCache          If the cache shall be used. If not, the only chance to get package data is
    *                                           reading them from the online server (if $bWorkOffline is false)
    *
    *   @return array   Array of packages (locally installed and remotely available) (PEAR_Frontend_Gtk2_Package)
    *   @static
    */
    public static function loadRemotePackageData($arRemotePackages, $strChannel, $callback = null, $bWorkOffline = false, $bUseCache = true)
    {
        $config     = PEAR_Config::singleton();
        $rest10     = $config->getREST('1.0');
        $rest       = $rest10->_rest;//FIXME: could change in future pear versions
        $channel    = $config->getRegistry()->getChannel($strChannel);
        $strBaseUrl = $channel->getBaseURL('REST1.0');

        $nNumberPackages = count($arRemotePackages);
        $nCurrentPackageNumber = 0;

        foreach ($arRemotePackages as $strId => $dontuseit) {
            if ($callback !== null) {
                call_user_func_array($callback, array($nNumberPackages, ++$nCurrentPackageNumber));
            }
            $package = $arRemotePackages[$strId];
            $inf = PEAR_Frontend_Gtk2_Packages::retrieveCacheFirst(
                        PEAR_Frontend_Gtk2_Packages::getInfoUrl($strBaseUrl, $package->getName()),
                        $config,
                        $rest,
                        $bWorkOffline,
                        $bUseCache
                    );
            if (PEAR::isError($inf)) {
                if (strpos(strtolower($inf->message), '404 not found') !== false) {
                    //FIXME: That is a hack because the error hasn't an error code yet.
                    //We continue because it's an 404 error, maybe the package isn't there yet.
                    //Happens if you installed new packages which haven't been uploaded to the server yet.
                    $package->setCategory($package->guessCategory($package->getName()));
                    continue;
                }
                throw new Exception($inf->getMessage());
            }
            if ($inf['ca']['_content'] !== null) {
                $package->setCategory(   $inf['ca']['_content']);
            }
            $package->setSummary(    $inf['s']);
            $package->setDescription($inf['d']);
            $package->setLatestVersion(
                //FIXME: use preferred_state config option
                PEAR_Frontend_Gtk2_Packages::retrieveCacheFirst(
                    PEAR_Frontend_Gtk2_Packages::getLatestVersionUrl($strBaseUrl, $package->getName()),
                    $config,
                    $rest,
                    $bWorkOffline,
                    $bUseCache
                )
            );
        }

        if ($callback !== null) {
            call_user_func_array($callback, array(true, true));
        }

        return $arRemotePackages;
    }//public static function loadRemotePackageData($arRemotePackages, $strChannel, $callback = null, $bWorkOffline = false, $bUseCache = true)



    /**
    *   Copy of the PEAR_REST#retrieveCacheFirst() function with the addition
    *   that one can specify if the cache only shall be loaded, or if the cache
    *   is not available, an connection shall be made.
    *
    *   If the user works offline, and no cache is there, NULL is returned.
    *
    *   @param string       $url            The URL to get the data from
    *   @param PEAR_Config  $config         The PEAR configuration
    *   @param PEAR_REST    $rest           The REST object which can be used to retrieve the data
    *   @param boolean      $bWorkOffline   If the local cache *only* shall be used, or (if no local cache is there) 
    *                                           an connection to the internet server shall be made
    *   @param boolean      $bUseCache      If the cache shall be used. If not, the only chance to get package data is
    *                                           reading them from the online server (if $bWorkOffline is false)
    */
    public static function retrieveCacheFirst($url, $config, $rest, $bWorkOffline = false, $bUseCache = true)
    {
        if ($bUseCache) {
            $cachefile = $config->get('cache_dir') . DIRECTORY_SEPARATOR . md5($url) . 'rest.cachefile';
            if (@file_exists($cachefile)) {
                return unserialize(implode('', file($cachefile)));
            }
        }
        if ($bWorkOffline) {
            return null;
        } else {
            return $rest->retrieveData($url);
        }
    }//public static function retrieveCacheFirst($url, $config, $rest, $bWorkOffline = false, $bUseCache = true)




    /**
    *   Path to info.xml for the specific package
    */
    public static function getInfoUrl($strBaseUrl, $strPackageName)
    {
        return $strBaseUrl . 'p/' . strtolower($strPackageName) . '/info.xml';
    }//public static function getInfoUrl($strBaseUrl, $strPackageName)



    /**
    *   Path to latest.txt for the specific package
    */
    public static function getLatestVersionUrl($strBaseUrl, $strPackageName)
    {
        return $strBaseUrl . 'r/' . strtolower($strPackageName) . '/latest.txt';
    }//public static function getLatestVersionUrl($strBaseUrl, $strPackageName)



    /**
    *   Creates an array of categories (strings) of the current
    *   channel server
    *
    *   @param callback $callback       The callback to call when package information is loaded
    *   @param boolean  $bWorkOffline   If the user wants to work offline (don't try to connect to the internet)
    *
    *   @return array   Array of category strings
    */
    public function getCategories($callback = null, $bWorkOffline = false)
    {
        if (!isset($this->arCategories[$this->strActiveChannel])) {
            $arPackages = $this->getPackages(null, $callback, $bWorkOffline);

            $this->arCategories[$this->strActiveChannel] = array();
            if (count($arPackages) > 0) {
                foreach ($arPackages as $package) {
                    $strCategory = $package->getCategory();
                    $this->arCategories[$this->strActiveChannel][$strCategory] = $strCategory;
                }
                natcasesort($this->arCategories[$this->strActiveChannel]);
            }
        }

        return $this->arCategories[$this->strActiveChannel];
    }//public function getCategories($callback = null)



    /**
    *   Checks if the packages for the given channel have already been loaded
    *
    *   @param string $strChannel   The channel to check (active channel if null)
    *   @return boolean     true if the packages already have been loaded, false if not
    */
    public function packagesLoaded($strChannel = null)
    {
        if ($strChannel === null) {
            $strChannel = $this->strActiveChannel;
        }
        return (isset($this->arPackages[$strChannel]) && $this->arPackages[$strChannel] !== null);
    }//public function packagesLoaded($strChannel = null)



    /**
    *   Refreshs the package list of the given channel by re-reading
    *   the local package info
    *
    *   @param string   $strChannel     The channel to update. If NULL, the active one is used
    *   @return array   Package list for the given channel
    */
    public function refreshLocalPackages($strChannel = null)
    {
        if ($strChannel === null) {
            $strChannel = $this->strActiveChannel;
        }
        $this->arPackages[$strChannel] = self::getLocalPackages($strChannel, $this->arPackages[$strChannel]);
        return $this->arPackages[$strChannel];
    }//public function refreshLocalPackages($strChannel = null)



    /**
    *   Refreshs the package list of the given channel by re-reading the remote 
    *   package information.
    *
    *   @param string   $strChannel     The channel to update. If NULL, the active one is used
    *   @return array   Package list for the given channel
    */
    public function refreshRemotePackages($strChannel = null, $callback = null)
    {
        if ($strChannel === null) {
            $strChannel = $this->strActiveChannel;
        }
        $this->arPackages[$strChannel] = self::loadRemotePackageData($this->arPackages[$strChannel], $strChannel, $callback, false, false);
        return $this->arPackages[$strChannel];
    }//public function refreshRemotePackages($strChannel = null, $callback = null)

}//class PEAR_Frontend_Gtk2_Packages
?>