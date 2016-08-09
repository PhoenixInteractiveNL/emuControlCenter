<?php
/**
*   Lists all runnable applications from
*   the local PEAR installation.
*
*   The package.xml file have to have a an executable
*   "run.phpw" file, and optionally an "runicon.png" to
*   be shown in the list.
*
*   TODO
*   - start PEAR_Frontend_Gtk2 with channel name and selected
*       package name so that it can jump to there
*
*   @author Christian Weiske <cweiske@php.net>
*/
require_once 'PEAR/Config.php';
require_once 'System/Command.php';

require_once dirname(__FILE__) . '/AppRunnerExec.php';

class Gnope_AppRunner extends GtkWindow
{
    protected static $strTitle   = 'PHP-Gtk2 Applications';
    protected $strDefaultChannel = 'gnope.org';
    protected $strEmptyIcon      = null;
    protected $iconEmpty         = null;
    protected $config            = null;
    protected $cmbChannels       = null;
    protected $ivApps            = null;
    protected $nSelection        = null;


    public function __construct()
    {
        parent::__construct();
        $this->loadConfig();
        $this->buildGui();
        $this->showChannelApps();
    }//public function __construct()



    function loadConfig()
    {
        $this->strEmptyIcon = dirname(__FILE__) . '/defaulticon.png';
        $this->iconEmpty    = GdkPixbuf::new_from_file($this->strEmptyIcon);

        $this->config = PEAR_Config::singleton();
    }//function loadConfig()



    protected function buildGui()
    {
        $this->set_title(self::$strTitle);
        $this->set_default_size(260,300);
        $this->connect_simple('destroy', array('gtk', 'main_quit'));

        $vbox = new GtkVBox();

        //Channel list
            $channelbox = new GtkHBox();
            $lblChannel = new GtkLabel('_Channel:', true);
            $channelbox->pack_start($lblChannel, false);
            $channelbox->pack_start($this->createChannelCombo());
        $vbox->pack_start($channelbox, false);

        //Application list
        $vbox->pack_start($this->createApplicationList());
        $this->addInstallerButton($vbox);

        $this->add($vbox);
        $this->show_all();
    }//protected function buildGui()



    /**
    *   Creates a GtkCombo filled with available PEAR channels
    *
    *   @return GtkWidget
    */
    function createChannelCombo()
    {
        $this->cmbChannels = GtkComboBox::new_text();
        $arChannels = &$this->config->getRegistry()->getChannels();
        $arChannelNames = array();
        foreach ($arChannels as $nId => $channel) {
            if ($channel->getName() != '__uri') {
                $arChannelNames[] = $channel->getName();
            }
        }
        natcasesort($arChannelNames);
        $nActiveChannelId = 0;
        $nPos = 0;
        foreach ($arChannelNames as $strName) {
            $this->cmbChannels->append_text($strName);
            if ($strName == $this->strDefaultChannel) {
                $nActiveChannelId = $nPos;
            }
            $nPos++;
        }

        $this->cmbChannels->set_active($nActiveChannelId);
        $this->cmbChannels->connect('changed', array($this, 'showChannelApps'));

        return $this->cmbChannels;
    }//function createChannelCombo()



    /**
    *   Creates the list for the channel's applications
    *
    *   @return GtkWidget
    */
    function createApplicationList()
    {
        $this->ivApps = new GtkIconView();

        $model = new GtkListStore(Gtk::TYPE_STRING, Gtk::TYPE_STRING, Gtk::TYPE_STRING, GdkPixbuf::gtype);
        $this->ivApps->set_model($model);
        $this->ivApps->set_columns(0);

        $this->ivApps->set_pixbuf_column(3);
        $this->ivApps->set_text_column(0);
        //labels at the right side
        $this->ivApps->set_orientation(Gtk::ORIENTATION_HORIZONTAL);
        $this->ivApps->set_item_width(250);

        $this->ivApps->connect('button-press-event', array($this, 'buttonPressed'));

        $scrollwin = new GtkScrolledWindow();
        $scrollwin->set_shadow_type(Gtk::SHADOW_IN);
        $scrollwin->set_policy(Gtk::POLICY_AUTOMATIC, Gtk::POLICY_AUTOMATIC);
        $scrollwin->add($this->ivApps);

        return $scrollwin;
    }//function createApplicationList()



    /**
    *   Adds an "start installer" button at the end
    *   of the given box
    */
    function addInstallerButton(GtkBox $vbox)
    {
        $info = $this->config->getRegistry()->packageInfo('PEAR_Frontend_Gtk2', null, 'pear.php.net');
        if ($info === null) {
            //no installer found - no button
            return false;
        }

        $btn = new GtkButton();
        //This code has been built using a .glade file as template
        $alignment = new GtkAlignment(0.5, 0.5, 0, 0);
        $hbox      = new GtkHBox(false, 2);
        $img       = GtkImage::new_from_file(dirname(__FILE__) . '/install.png');
        $lbl       = new GtkLabel('_Install/Uninstall programs', true);

        $hbox->pack_start($img);
        $hbox->pack_start($lbl);
        $alignment->add($hbox);
        $btn->add($alignment);
        //This is hardcoded which is very, very bad - but as I am the maintainer of PEAR_Frontend_Gtk2,
        //this shouldn't change too often
        $btn->connect_simple('clicked', array($this, 'startApplication'), $this->config->get('php_dir') . '/PEAR/Frontend/Gtk2/run.phpw');

        $vbox->pack_end($btn, false);

        return true;
    }//function addInstallerButton(GtkBox $vbox)



    /**
    *   Display the runnable applications for the current channel
    */
    function showChannelApps()
    {
        $strChannelName = $this->cmbChannels->get_active_text();
        if ($strChannelName === null) {
            return;
        }
        $arApps = $this->getChannelApplications($strChannelName);

        $model = $this->ivApps->get_model();
        $model->clear();
        foreach ($arApps as $arApp) {
            if ($arApp[3] !== null) {
                $icon = GdkPixbuf::new_from_file($arApp[3]);
            } else {
                $icon = $this->iconEmpty;
            }
            $model->set(
                $model->append(),
                0, $arApp[0],
                1, $arApp[1],
                2, $arApp[2],
                3, $icon
            );
        }
    }//function showChannelApps()



    /**
    *   Returns an array with packages capable of being run.
    *
    *   @param string   $strChannelName     The channel to search programs in
    *   @return array   Array of package arrays: array(name, summary, runnable file, icon file)
    */
    function getChannelApplications($strChannelName)
    {
        $arInstalled    = $this->config->getRegistry()->packageInfo(null, null, $strChannelName);
        $arApps         = array();

        foreach ($arInstalled as $arPackage) {
            if (isset($arPackage['filelist'])) {
                $strRunnable = null;
                $strIcon     = null;
                if (isset($arPackage['filelist']['run.phpw'])) {
                    $strRunnable = $arPackage['filelist']['run.phpw']['installed_as'];
                    if (isset($arPackage['filelist']['runicon.png'])) {
                        $strIcon = $arPackage['filelist']['runicon.png']['installed_as'];
                    }
                } else {
                    //loop through the list to find a */run.phpw
                    foreach ($arPackage['filelist'] as $arFile) {
                        if (isset($arFile['name'])) {
                            if (substr($arFile['name'], -9) == '/run.phpw') {
                                $strRunnable = $arFile['installed_as'];
                            } else if (substr($arFile['name'], -12) == '/runicon.png') {
                                $strIcon = $arFile['installed_as'];
                            }
                        }
                    }//foreach
                }

                if ($strRunnable !== null) {
                    $arApps[] = array(
                        $arPackage['name'],
                        $arPackage['summary'],
                        $strRunnable,
                        $strIcon
                    );
                }
            }//filelist set
        }//foreach

        return $arApps;
    }//function getChannelApplications($strChannelName)



    /**
    *   The user has double-clicked on the application list
    */
    function buttonPressed($widget, $event)
    {
        if ($event->type == Gdk::_2BUTTON_PRESS) {
            $this->startSelectedApplication();
        }
    }//function buttonPressed($widget, $event)



    /**
    *   Starts the currently selected application
    *   in the background
    */
    function startSelectedApplication()
    {
        $strPath    = $this->getSelectedApplicationPath();
        $this->startApplication($strPath);
    }//function startSelectedApplication()



    /**
    *   Starts the given php script with the current php
    *   executable (detached)
    *
    *   @param string   $strPath    The file which shall be executed
    *   @return boolean             True if the program has been started, false if there has been an error
    */
    public function startApplication($strPath)
    {
        return Gnope_AppRunner_Exec::run($strPath);
    }//protected function startApplication($strPath)



    function getSelectedApplicationPath()
    {
        //This is really bad code. But only because the get_selected_items() is not implemented
        $model = $this->ivApps->get_model();
        $model->foreach(array($this, 'modelForeach'));
        if ($this->nSelection === null) {
            return;
        }
        $iter = $model->get_iter_first();
        $model->iter_nth_child($iter, null, $this->nSelection);
        return $model->get_value($iter, 2);
    }//function getSelectedApplicationPath()



    function modelForeach($one, $two)
    {
        if ($this->ivApps->path_is_selected($two[0])) {
            $this->nSelection = $two[0];
        }
    }//function modelForeach($one, $two)

}//class Gnope_AppRunner extends GtkWindow
?>