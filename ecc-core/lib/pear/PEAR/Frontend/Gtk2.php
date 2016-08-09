<?php
require_once 'PEAR/Config.php';
require_once 'PEAR/Frontend.php';

require_once 'PEAR/Frontend/Gtk2/About.php';
require_once 'PEAR/Frontend/Gtk2/Channels.php';
require_once 'PEAR/Frontend/Gtk2/Config.php';
require_once 'PEAR/Frontend/Gtk2/Packages.php';
require_once 'PEAR/Frontend/Gtk2/Installation.php';

/**
*   Graphical frontend for PEAR, based on PHP-Gtk2
*
*   TODO:
*   - Package categories aren't updated/extendet if there is an error at startup loading/packages are refreshed and a new category is required
*   - channel discovery
*   - Drop package file onto install button -> install it
*   - Warn if the package list is older than 5 days
*   - upgrade-all menu option
*   - Filter by upgradeable/installed/installable/all packages
*   - Install pecl packages - error messages aren't shown
*
*   Don't know how to do:
*   - installation dialog showing has to be updated correctly
*   - Installation: shrink window if expander is collapsed
*
*   Done:
*   - better installation ok icon
*   - Window icon - shows the php icon on windows currently
*   - When no internet connection on startup, no categories are loaded and no local packages are shown
*   - When no pear cache directory exists, the check warning is shown - pear cache directory is created now
*   - uninstall: pear/liveuser is required by installed package "pear/Event_Dispatcher"
*   - use menu options (deps, nodeps)
*   - refresh online info (clear cache or so)
*   - offline mode
*   - save and load settings
*   - scroll text in installation
*
*   @author Christian Weiske <cweiske@php.net>
*/
class PEAR_Frontend_Gtk2 extends PEAR_Frontend
{
    /**
    *   The widgets which shall be loaded from the glade
    *   file into the $arWidgets array
    *   @var array
    */
    protected static $arRequestedWidgets = array(
        'dlgInstaller', 'lstCategories', 'lstPackages', 'txtPackageInfo',
        'cmbChannel', 'imgChannelLogo', 'lblSelectedCategory', 'hboxCategoryInfo',
        'btnInstall','btnUninstall','lblBtnInstall','evboxSelectedCategory','expPackageInfo',

        'mnuOptDepsNo','mnuOptDepsReq','mnuOptDepsAll','mnuOptDepNothing',
        'mnuOffline','mnuQuit','mnuAbout','mnuUpdateOnline','mnuUpdateLocal',

        'dlgProgress', 'lblDescription', 'imgProgress', 'lblProgress', 'progBar'
    );

    protected $nProgressImage = 1;

    /**
    *   Array with images used for the progress animation
    */
    protected $arAnimationImages = array();

    /**
    *   Requested widgets are loaded from glade into this array.
    *   So this is an associative array with all required widgets 
    *   from the glade file: name => widget object
    *   @var array
    */
    protected $arWidgets;

    /**
    *   The PEAR_Frontend_Gtk2_Installation object to use
    *   @var PEAR_Frontend_Gtk2_Installation
    */
    protected $installer = null;

    protected $selectedPackage          = null;
    protected $strSelectedCategoryName  = null;
    protected $strSelectedCategoryKey   = null;

    /**
    *   The package information class instance
    *   @var PEAR_Frontend_Gtk2_Packages
    */
    protected $packages = null;

    const CATEGORY_ALL      = 12345678;
    const CATEGORY_SELECTED = 12345679;



    /**
    *   Special categories which are added to the 
    *   channel categories
    *   @var array
    */
    protected static $arSpecialCategories = array(
        PEAR_Frontend_Gtk2::CATEGORY_ALL      => '*All packages',
//        PEAR_Frontend_Gtk2::CATEGORY_SELECTED => '*Selected for install'
    );


    public function __construct()
    {
        $this->loadConfig();
        $this->buildDialog();
        PEAR_Frontend_Gtk2_Config::loadConfig();
        PEAR_Frontend_Gtk2_Config::loadCurrentConfigIntoGui($this);

        //After all has been initiated, load the data
        PEAR_Frontend_Gtk2_Channels::loadChannels(
            $this->arWidgets['cmbChannel'],
            $this->config,
            PEAR_Frontend_Gtk2_Config::$strDefaultChannel
        );
    }//public function __construct()



    function loadConfig()
    {
        $this->config   = PEAR_Config::singleton();
        $this->packages = new PEAR_Frontend_Gtk2_Packages($this->config);
    }//function loadConfig()



    public function quitApp()
    {
        PEAR_Frontend_Gtk2_Config::loadConfigurationFromGui($this);
        PEAR_Frontend_Gtk2_Config::saveConfig();
        Gtk::main_quit();
    }//public function quitApp()



    /**
    *   load the glade file, load the widgets, connect the signals
    */
    protected function buildDialog()
    {
        $this->glade = new GladeXML(dirname(__FILE__) . '/Gtk2/installer.glade');
        foreach (self::$arRequestedWidgets as $strWidgetName) {
            $this->arWidgets[$strWidgetName] = $this->glade->get_widget($strWidgetName);
        }

        $this->arWidgets['dlgInstaller']->connect_simple('destroy', array($this, 'quitApp'));
        $strIcon = dirname(__FILE__) . '/Gtk2/runicon.png';
        if (file_exists($strIcon)) {
            $this->arWidgets['dlgInstaller']->set_icon_from_file($strIcon);
        }

        $this->arWidgets['lblSelectedCategory']->set_use_markup(true);

        $this->arWidgets['cmbChannel']->connect('changed', array($this, 'selectChannel'));

        $this->arWidgets['lstCategories']->set_model(new GtkListStore(Gtk::TYPE_STRING, Gtk::TYPE_STRING));
        $cell_renderer = new GtkCellRendererText();
        $column = new GtkTreeViewColumn('test', $cell_renderer, "text", 0);
        $this->arWidgets['lstCategories']->append_column($column);

        $this->arWidgets['btnInstall']  ->connect_simple('clicked', array($this, 'installPackage'), true);
        $this->arWidgets['btnUninstall']->connect_simple('clicked', array($this, 'installPackage'), false);

        $this->arWidgets['dlgProgress']->connect('delete-event', array($this, 'deleteProgressWindow'));

        //Menu entries
        $this->arWidgets['mnuQuit']         ->connect_simple('activate', array($this, 'quitApp'));
        $this->arWidgets['mnuAbout']        ->connect_simple('activate', array('PEAR_Frontend_Gtk2_About', 'showMe'));
        $this->arWidgets['mnuUpdateLocal']  ->connect_simple('activate', array($this, 'refreshLocalPackages'));
        $this->arWidgets['mnuUpdateOnline'] ->connect_simple('activate', array($this, 'refreshOnlinePackages'));


        //that's channel name and array key of the packages
        $this->arWidgets['lstPackages']->set_model(new GtkListStore(Gtk::TYPE_STRING, Gtk::TYPE_STRING, Gtk::TYPE_STRING, Gtk::TYPE_STRING, Gtk::TYPE_PHP_VALUE));
        $cell_renderer = new GtkCellRendererText();

        $colName = new GtkTreeViewColumn('Package', $cell_renderer, "text", 0);
        $colName->set_resizable(true);
        $colName->set_sort_column_id(0);
        $this->arWidgets['lstPackages']->append_column($colName);

        $colInstalled = new GtkTreeViewColumn('Installed', $cell_renderer, "text", 1);
        $colInstalled->set_resizable(true);
        $colInstalled->set_sort_column_id(1);
        $this->arWidgets['lstPackages']->append_column($colInstalled);

        $colNew = new GtkTreeViewColumn('New version', $cell_renderer, "text", 2);
        $colNew->set_resizable(true);
        $colNew->set_sort_column_id(2);
        $this->arWidgets['lstPackages']->append_column($colNew);

        $colSummary = new GtkTreeViewColumn('Summary', $cell_renderer, "text", 3);
        $colSummary->set_resizable(true);
        $colSummary->set_sort_column_id(3);
        $this->arWidgets['lstPackages']->append_column($colSummary);

        $selCategories = $this->arWidgets['lstCategories']->get_selection();
        $selCategories->set_mode(Gtk::SELECTION_SINGLE);
        $selCategories->connect('changed', array($this, 'selectCategory'));

        $selPackages = $this->arWidgets['lstPackages']->get_selection();
        $selPackages->set_mode(Gtk::SELECTION_SINGLE);
        $selPackages->connect('changed', array($this, 'selectPackage'));

        for ($nA = 1; $nA <= 3; $nA++) {
            $this->arAnimationImages[] = GdkPixbuf::new_from_file(dirname(__FILE__) . '/Gtk2/pixmaps/progress/load-anim-' . $nA . '.png');
        }
        $this->nProgressImage = 0;

        $this->loadInstaller();
    }//protected function buildDialog()



    protected function loadInstaller()
    {
        $this->installer = new PEAR_Frontend_Gtk2_Installation($this->arWidgets['dlgInstaller'], $this->glade);
    }//protected function loadInstaller()



    /**
    *   A channel has been selected from the channel combo box
    *
    *   Has to be public as it is a callback function
    *
    *   @param GtkComboBox  $cmbChannel     The channel selection combo box
    *   @param boolean      $bSecondTime    If the function is being run a second time (because the first run had a problem) - used to detect infinite loops.
    */
    public function selectChannel($cmbChannel, $bSecondTime = false)
    {
        $strChannel = $cmbChannel->get_active_text();

        $this->setChannelStyles($strChannel);

        $model = $this->arWidgets['lstCategories']->get_model();
        $model->clear();
        $this->arWidgets['lstPackages']->get_model()->clear();

        //Channel categories
        $this->packages->setActiveChannel($strChannel);

        if (!$this->packages->packagesLoaded()) {
            //show the progress dialog before it gets loaded with the first callback
            $this->showProgressDialog(true);
        }

        try {
            $arCategories = $this->packages->getCategories(array($this, 'packagesCallback'), $this->getWorkOffline());
        } catch (Exception $e) {
            $this->hideProgressDialog();
            $dialog = new GtkMessageDialog(
                $this->arWidgets['dlgInstaller'],
                0,
                Gtk::MESSAGE_ERROR,
                Gtk::BUTTONS_OK,
                'Can\'t list the categories:' . "\r\n"
                . $e->getCode() . ': ' . $e->getMessage()
                . "\r\n\r\nMake sure you have an internet connection."
            );
            $dialog->set_transient_for($this->arWidgets['dlgInstaller']);
            $dialog->set_position(Gtk::WIN_POS_CENTER_ON_PARENT);
            $dialog->run();
            $dialog->destroy();

            if (!$bSecondTime) {
                //The exception code seem to vary on every run - so I can't check for a certain code.
                //But as we still can load the local packages, we can work offline.
                $this->setWorkOffline(true);
                $this->selectChannel($cmbChannel, true);
                return;
            } else {
                //no chance to do a fix - do nothing
                return;
            }
        }

        $arCategories = $this->appendSpecialCategories(
            $arCategories
        );

        foreach ($arCategories as $key => $strCategory) {
            $model->set($model->append(), 0, $strCategory, 1, $key);
        }

        //Clear packages
        $this->arWidgets['lstPackages']->get_model()->clear();
        $this->arWidgets['lstCategories']->get_selection()->select_path('0');

        $this->hideProgressDialog();
    }//public function selectChannel($cmbChannel, $bSecondTime = false)



    protected function setChannelStyles($strChannel)
    {
        //Logo
        $strIcon = dirname(__FILE__) . '/Gtk2/pixmaps/' . $strChannel . '.png';
        if (!file_exists($strIcon)) {
            $strIcon = dirname(__FILE__) . '/Gtk2/pixmaps/default.png';
        }
        if (file_exists($strIcon)) {
            $this->arWidgets['imgChannelLogo']->set_from_file($strIcon);
        }

        //Colors
        $strColorChannel = $strChannel;
        if (!isset(PEAR_Frontend_Gtk2_Config::$arChannels[$strColorChannel])) {
            $strColorChannel = 'default';
        }
        $colBg = GdkColor::parse(PEAR_Frontend_Gtk2_Config::$arChannels[$strColorChannel]['background-color']);
        $colFg = GdkColor::parse(PEAR_Frontend_Gtk2_Config::$arChannels[$strColorChannel]['color']);

        $this->arWidgets['evboxSelectedCategory']->modify_bg(Gtk::STATE_NORMAL, $colBg);
        $this->arWidgets['lblSelectedCategory']->modify_fg(Gtk::STATE_NORMAL, $colFg);
    }//protected function setChannelStyles($strChannel)



    /**
    *   Appends defined special categories to the given
    *   list
    *
    *   @param  array   Array of categories
    *   @return array   Array of categories with special ones
    */
    function appendSpecialCategories($arCategories)
    {
        foreach (PEAR_Frontend_Gtk2::$arSpecialCategories as $key => $value) {
            $arCategories[$key] = $value;
        }
        return $arCategories;
    }//function appendSpecialCategories($arCategories)



    /**
    *   A category has been selected
    */
    function selectCategory($selection)
    {
        list($model, $iter) = $selection->get_selected();
        if ($iter === null) {
            $this->arWidgets['lblSelectedCategory']->set_text('No category selected');
            return;
        }

        $this->strSelectedCategoryName  = $model->get_value($iter, 0);
        $this->strSelectedCategoryKey   = $model->get_value($iter, 1);
        $this->arWidgets['lblSelectedCategory']->set_markup('<b>' . $this->strSelectedCategoryName . '</b>');

        if ($this->strSelectedCategoryKey == self::CATEGORY_ALL) {
            $this->strSelectedCategoryName = null;
        }
        $this->showPackageList($this->strSelectedCategoryName);
    }//function selectCategory($selection)



    /**
    *   Fill the package list for the given category
    */
    protected function showPackageList($strCategory)
    {
        //Show packages
        $model = $this->arWidgets['lstPackages']->get_model();
        $model->clear();
        $arPackages = $this->packages->getPackages($strCategory);
        if (count($arPackages) > 0) {
            foreach ($arPackages as $key => $package) {
                $model->set(
                    $model->append(),
                    0, $package->getName(),
                    1, $package->getInstalledVersion(),
                    2, $package->getLatestVersion(),
                    3, $package->getSummary(),
                    4, $package
                );
            }
        }
    }//protected function showPackageList($strCategory)



    /**
    *   Update the package list model entries for the given packages.
    *   Useful after installing/uninstalling a package
    *
    *   @param array    $arPackages     Array of packages which model entry shall be updated
    */
    protected function updatePackageList($arPackages)
    {
        $model = $this->arWidgets['lstPackages']->get_model();
        $iter  = $model->get_iter_first();
        while ($iter !== null) {
            $package = $model->get_value($iter, 4);
            if (in_array($package, $arPackages)) {
                $model->set(
                    $iter,
                    0, $package->getName(),
                    1, $package->getInstalledVersion(),
                    2, $package->getLatestVersion(),
                    3, $package->getSummary(),
                    4, $package
                );
            }
            $iter = $model->iter_next($iter);
        }
    }//protected function updatePackageList($arPackages)



    /**
    *   Callback for the package loading functions
    */
    function packagesCallback($nPackageCount, $nCurrentPackage)
    {
        $dlgProgress = $this->arWidgets['dlgProgress'];
        if ($nPackageCount === true) {
            //we're done
            $this->hideProgressDialog();
            return;
        }
        $this->showProgressDialog();
        $this->arWidgets['lblProgress']->set_text($nCurrentPackage . ' / ' . $nPackageCount);
        $this->arWidgets['progBar']->set_fraction(1/$nPackageCount * $nCurrentPackage);

        $this->nProgressImage++;
        if (!isset($this->arAnimationImages[$this->nProgressImage])) {
            $this->nProgressImage = 0;
        }
        //TODO: hold the images in memory
        $this->arWidgets['imgProgress']->set_from_pixbuf($this->arAnimationImages[$this->nProgressImage]);
        while (Gtk::events_pending()) { Gtk::main_iteration();}
    }//function packagesCallback($nPackageCount, $nCurrentPackage)



    public function selectPackage($selection)
    {
        list($model, $iter) = $selection->get_selected();
        if ($iter === null) {
            $strDescription = '';
            $this->setSelectedPackage(null);
            $this->arWidgets['btnUninstall']->hide();
            $this->arWidgets['btnInstall']  ->set_sensitive(false);
        } else {
            $package = $model->get_value($iter, 4);
            $this->setSelectedPackage($package);
            $strDescription = $package->getDescription();
            $this->ableInstallButtons($package);
            if ($package->getInstalledVersion() === null || $package->getInstalledVersion() == '') {
                $this->arWidgets['lblBtnInstall']->set_label('Inst_all package');
            } else {
                $this->arWidgets['lblBtnInstall']->set_label('Upgr_ade package');
            }
        }

        $this->arWidgets['txtPackageInfo']->get_buffer()->set_text($strDescription);
    }//public function selectPackage($selection)



    /**
    *   Enables or disables the install buttons depending
    *   on the installation status of $package
    *
    *   @param PEAR_Frontend_Gtk2_Package $package  The package to check
    */
    protected function ableInstallButtons($package)
    {
        if ($package === null) {
            return;
        }

        $this->arWidgets['btnInstall']->set_sensitive(
            $package->getLatestVersion() !== null
            && $package->getLatestVersion() !== ''
        );

        if ($package->getInstalledVersion() == '') {
            $this->arWidgets['btnUninstall']->hide();
            $this->arWidgets['btnUninstall']->set_sensitive(false);
        } else {
            $this->arWidgets['btnUninstall']->show();
            $this->arWidgets['btnUninstall']->set_sensitive(true);
        }
    }//protected function ableInstallButtons($package)



    protected function updateInstallButtons()
    {
        $selection = $this->arWidgets['lstPackages']->get_selection();
        list($model, $iter) = $selection->get_selected();
        $package = $model->get_value($iter, 4);
        $this->ableInstallButtons($package);
    }//protected function updateInstallButtons()



    protected function getSelectedPackage()
    {
        return $this->selectedPackage;
    }//protected function getSelectedPackage()



    protected function setSelectedPackage($package)
    {
        $this->selectedPackage = $package;
    }//protected function setSelectedPackage($package)



    /**
    *   Installs (or uninstalls) the selected package
    *
    *   @param boolean $bInstall    True if the package shall be installed, false if it shall be uninstalled
    */
    function installPackage($bInstall = true)
    {
        $strChannel = $this->packages->getActiveChannel();
        $package = $this->getSelectedPackage();
        if ($package === null) {
            $dialog = new GtkMessageDialog(
                $this->arWidgets['dlgInstaller'], 0, Gtk::MESSAGE_ERROR, Gtk::BUTTONS_OK,
                'You have to select a package before you can install it.'
            );
            $dialog->run();
            $dialog->destroy();
            return;
        }

        //get changes done in gui into config
        PEAR_Frontend_Gtk2_Config::loadConfigurationFromGui($this);

        $this->installer->installPackage(
            $strChannel,
            $package->getName(),
            $package->getLatestVersion(),
            $bInstall,
            PEAR_Frontend_Gtk2_Config::$strDepOptions
        );

        $package->refreshLocalInfo();
        $this->updatePackageList(array($package));
        $this->updateInstallButtons();
    }//function installPackage($bInstall = true)



    /**
    *   Shows the progress dialog if that hasn't already been done
    *
    *   @param boolean  $bInitWithZero  If the dialog's labels should be initialized (if the dialog has been hidden)
    */
    protected function showProgressDialog($bInitWithZero = false)
    {
        $dlgProgress = $this->arWidgets['dlgProgress'];
        if ($dlgProgress->window === null || !$dlgProgress->window->is_visible()) {
            //show it
            $dlgProgress->modify_bg(Gtk::STATE_NORMAL, GdkColor::parse('#FFFFFF'));
            $dlgProgress->set_transient_for($this->arWidgets['dlgInstaller']);
            $dlgProgress->set_position(Gtk::WIN_POS_CENTER_ON_PARENT);

            if ($bInitWithZero) {
                $this->arWidgets['lblProgress']->set_text('');
                $this->arWidgets['progBar']->set_fraction(0);
            }

            $dlgProgress->show_now();
            while (Gtk::events_pending()) { Gtk::main_iteration();}
        }
    }//protected function showProgressDialog($bInitWithZero = false)



    /**
    *   Hides the progress dialog
    */
    protected function hideProgressDialog()
    {
        $this->arWidgets['dlgProgress']->hide();
        while (Gtk::events_pending()) { Gtk::main_iteration();}
    }//protected function hideProgressDialog()



    /**
    *   The user may not close the progress window by hand
    */
    public function deleteProgressWindow()
    {
        return true;
    }//public function deleteProgressWindow()



    /**
    *   Check if the user wants to work offline
    *   @return boolean     True, if the offline setting is active (work offline), false if working online
    */
    protected function getWorkOffline()
    {
        return $this->arWidgets['mnuOffline']->get_active();
    }//protected function getWorkOffline()



    /**
    *   Set the offline setting
    *   @param boolean $bOffline    If the user wants to work offline (true) or not (false)
    */
    protected function setWorkOffline($bOffline)
    {
        return $this->arWidgets['mnuOffline']->set_active($bOffline);
    }//protected function setWorkOffline($bOffline)



    public function getWidget($strWidget)
    {
        return $this->arWidgets[$strWidget];
    }//public function getWidget($strWidget)



    /**
    *   Reload local package information and update the list.
    */
    public function refreshLocalPackages()
    {
        $this->packages->refreshLocalPackages();
        $this->showPackageList($this->strSelectedCategoryName);
    }//public function refreshLocalPackages()



    /**
    *   Reload the online package list and package information,
    *   and update the list here.
    */
    public function refreshOnlinePackages()
    {
        $this->packages->refreshRemotePackages(null, array($this, 'packagesCallback'));
        $this->showPackageList($this->strSelectedCategoryName);
    }//public function refreshOnlinePackages()

}//class PEAR_Frontend_Gtk2
?>