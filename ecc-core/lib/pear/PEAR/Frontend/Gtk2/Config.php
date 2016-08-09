<?php
/**
*   This file contains the configuration options
*   for PEAR_Frontend_Gtk2, beginning with channel
*   colors/images, default channel and more.
*   It can load and write the config from an .ini file,
*   and from and to the GUI.
*
*   @author Christian Weiske <cweiske@php.net>
*/
class PEAR_Frontend_Gtk2_Config
{
    /**
    *   Color settings for the different channels.
    *   When a channel is selected from the dropdown,
    *   this array is read and the background color
    *   as well as the text color  of the bar on the right 
    *   top is is set according to this settings here.
    *
    *   If there is no entry for a specific channel, the 
    *   settings in "default" are used.
    *
    *   @var array
    */
    public static $arChannels = array(
        'pear.php.net'  => array(
            'background-color'  => '#339900',
            'color'             => '#FFF'
        ),
        'pecl.php.net'  => array(
            'background-color'  => '#2C1D83',
            'color'             => '#FFF'
        ),
        'pear.chiaraquartet.net'  => array(
            'background-color'  => '#333333',
            'color'             => '#FFF'
        ),
        'tradebit.bogo' => array(
            'background-color'  => '#FFF',
            'color'             => '#000'
        ),
        'gnope.org' => array(
            'background-color'  => '#FFF',
            'color'             => '#000'
        ),
        'gnope.bogo' => array(
            'background-color'  => '#FFF',
            'color'             => '#000'
        ),
        'default' => array(
            'background-color'  => '#FFF',
            'color'             => '#000'
        )
    );

    /**
    *   The channel which is shown first.
    *   @var string
    */
    public static $strDefaultChannel = 'gnope.org';

    /**
    *   Work offline? If yes, then no internet connection is established.
    *   @var boolean
    */
    public static $bWorkOffline = false;

    /**
    *   What dependency option should be used when installing
    *   a package.
    *   One of: onlyreqdeps, alldeps, nodeps or "".
    *   @var string
    */
    public static $strDepOptions = 'onlyreqdeps';

    /**
    *   The dependency options for installation, and the 
    *   corresponding widget names from the GUI.
    *   @var array
    */
    protected static $arDepWidgets = array(
        'onlyreqdeps' => 'mnuOptDepsReq',
        'alldeps'     => 'mnuOptDepsAll',
        'nodeps'      => 'mnuOptDepsNo',
        ''            => 'mnuOptDepNothing'
    );



    /**
    *   Load the config file into the variables here.
    *
    *   @return boolean  True if all is ok, false if not
    */
    public static function loadConfig()
    {
        require_once 'Config.php';
        $config = new Config();
        $root   = $config->parseConfig(self::getConfigFilePath(), 'inifile');

        if (PEAR::isError($root)) {
            //we have default values if there is no config file yet
            return false;
        }
        $arRoot = $root->toArray();
        if (!isset($arRoot['root']['installer']) || !is_array($arRoot['root']['installer'])) {
            return false;
        }
        $arConf = array_merge(self::getConfigArray(), $arRoot['root']['installer']);

        self::$bWorkOffline     = (boolean)$arConf['offline'];
        self::$strDepOptions    = (string) $arConf['depOption'];

        return true;
    }//public static function loadConfig()



    /**
    *   Save the config in the config file
    */
    public static function saveConfig()
    {
        require_once 'Config.php';
        $conf   = new Config_Container('section', 'installer');
        $arConf = self::getConfigArray();
        foreach ($arConf as $key => $value) {
            $conf->createDirective($key, $value);
        }

        $config = new Config();
        $config->setRoot($conf);
        $config->writeConfig(self::getConfigFilePath(), 'inifile');
    }//public static function saveConfig()



    /**
    *   The config array with all current values.
    *   Used for loading and storing
    *
    *   @return array  Arra with all the config options: option name => option value
    */
    public static function getConfigArray()
    {
        return array(
            'offline' => self::$bWorkOffline,
            'depOption' => self::$strDepOptions
        );
    }//public static function getConfigArray()



    /**
    *   The config file path. (Where the config file is/shall be stored)
    *
    *   @return string  The config file path
    */
    protected static function getConfigFilePath()
    {
        return PEAR_Config::singleton()->get('data_dir') . DIRECTORY_SEPARATOR . get_class() . '.ini';
    }//protected static function getConfigFilePath()



    /**
    *   Loads the current configuration into the GUI.
    *   Sets all the widgets which reflect the config settings
    *   in a way (e.g. the radio menu group for dep options)
    *
    *   @param PEAR_Frontend_Gtk2   $fe     The current frontend to where the config shall be transferred
    */
    public static function loadCurrentConfigIntoGui(PEAR_Frontend_Gtk2 $fe)
    {
        $fe->getWidget('mnuOffline')   ->set_active(self::$bWorkOffline);
        foreach (self::$arDepWidgets as $strValue => $strWidget) {
            $fe->getWidget($strWidget)->set_active(self::$strDepOptions == $strValue);
        }
    }//public static function loadConfigIntoGui(PEAR_Frontend_Gtk2 $fe)



    /**
    *   Loads the configuration from the GUI to this config.
    *   This needs to be done before saving the config file.
    *   It checks the widgets responsible for the config options and reads their
    *   settings, saving them intho this config class.
    *
    *   @param PEAR_Frontend_Gtk2   $fe     The current frontend to where the config shall be transferred
    */
    public static function loadConfigurationFromGui(PEAR_Frontend_Gtk2 $fe)
    {
        self::$bWorkOffline = $fe->getWidget('mnuOffline')->get_active();
        foreach (self::$arDepWidgets as $strValue => $strWidget) {
            if ($fe->getWidget($strWidget)->get_active()) {
                self::$strDepOptions = $strValue;
            }
        }
    }//public static function loadConfigurationFromGui(PEAR_Frontend_Gtk2 $fe)

}//class PEAR_Frontend_Gtk2_Config
?>