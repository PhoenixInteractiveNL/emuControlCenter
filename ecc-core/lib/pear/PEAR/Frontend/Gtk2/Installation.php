<?php
require_once 'PEAR/Command.php';
require_once 'PEAR/Frontend.php';

/**
*   Installation dialog and user interaction interface
*   for the PEAR Gtk2 installer
*
*   @author Christian Weiske <cweiske@php.net>
*/
class PEAR_Frontend_Gtk2_Installation extends PEAR_Frontend
{
    protected $dlgParent     = null;
    protected $glade         = null;
    protected $dlgInstalling = null;
    protected $txtLog        = null;

    protected $strPackage    = null;
    protected $strChannel    = null;

    /**
    *   The command which shall be executed (upgrade or uninstall)
    *   @var string
    */
    protected $strCommand    = null;

    /**
    *   If the log detects an unrecoverable error (e.g. failed dependency)
    *   an error symbol is shown, no matter if the callback for "uninstall ok"
    *   is called.
    *
    *   @var boolean
    */
    protected $bSeriousError = false;

    /**
    *   Array with widgets from the glade file. They
    *   are loaded in the buildDialog() methods
    *   @var array
    */
    protected $arWidgets     = array();



    /**
    *   The widgets which shall be loaded from the glade
    *   file into the $arWidgets array
    *   @var array
    */
    protected static $arRequestedWidgets = array(
        'dlgInstalling', 'imgInstalling', 'progBarInstaller', 'btnCloseInstalling',
        'lblInstalling', 'txtInstalling', 'expLog'
    );



    public function __construct(GtkWidget $parent, GladeXML $glade)
    {
        $this->dlgParent = $parent;
        $this->glade = $glade;
        $this->buildDialog();
    }//public function __construct(GtkWidget $parent)



    /**
    *   load the glade file, load the widgets, connect the signals
    */
    protected function buildDialog()
    {
        foreach (self::$arRequestedWidgets as $strWidgetName) {
            $this->arWidgets[$strWidgetName] = $this->glade->get_widget($strWidgetName);
        }
        $this->txtLog        = $this->arWidgets['txtInstalling'];
        $this->dlgInstalling = $this->arWidgets['dlgInstalling'];
        $this->dlgInstalling->set_transient_for($this->dlgParent);

        $this->arWidgets['btnCloseInstalling']->connect_simple('clicked', array($this, 'hideMe'));
        $this->dlgInstalling->connect('delete-event', array($this, 'deleteWindow'));
    }//protected function buildDialog()



    /**
    *   Installs (or uninstalls) the given package.
    *
    *   @param string   $strPackage The package to install
    *   @param string   $strChannel The channel on which the package can be found
    *   @param boolean  $bInstall   If the package shall be installed (true) or uninstalled (false)
    */
    public function installPackage($strChannel, $strPackage, $strVersion, $bInstall = true, $strDepOptions = null)
    {
        //Bad code, but PEAR doesn't offer the possibility to do that a better way
        $GLOBALS['_PEAR_FRONTEND_SINGLETON'] = $this;

        $this->bSeriousError = false;//there is no serious error *yet*
        $this->strPackage    = $strPackage;
        $this->strChannel    = $strChannel;

        $strText = $bInstall ? 'I' : 'Uni';
        $strText .= 'nstalling ' . $strChannel . '/' . $strPackage;
        $this->dlgInstalling->set_title($strText);
        $this->setCurrentAction($strText);
        $this->setCurrentIcon(Gtk::STOCK_EXECUTE);
        $this->setPercentage(0);

        $buffer = $this->txtLog->get_buffer();
        $buffer->delete($buffer->get_start_iter(), $buffer->get_end_iter());
//        $this->arWidgets['expLog']->set_expanded(false);

        $this->appendToLog($strText . "\r\n");

        $this->showMe();

        $cmd              = PEAR_Command::factory('install', PEAR_Config::singleton());
        if ($bInstall) {
            $strPackagePath = 'channel://' . $strChannel . '/' . $strPackage . '-' . $strVersion;
            $strCommand       = 'upgrade';
        } else {
            $strPackagePath = 'channel://' . $strChannel . '/' . $strPackage;
            $strCommand       = 'uninstall';
        }
        $this->strCommand = $strCommand;

        while (Gtk::events_pending()) { Gtk::main_iteration();}

        if ($strCommand === 'upgrade') {
            $arOptions = array($strDepOptions => true);
        } else {
            $arOptions = array();
        }

        $cmd->run($strCommand, $arOptions, array($strPackagePath));

        //own main loop so that the next functions aren't executed until the window is closed
        Gtk::main();
    }//public function installPackage($strChannel, $strPackage, $strVersion, $bInstall = true, $strDepOptions = null)



    protected function showMe()
    {
        $this->dlgInstalling->set_modal(true);
        $this->dlgInstalling->set_position(Gtk::WIN_POS_CENTER_ON_PARENT);
        $this->dlgInstalling->show();
        while (Gtk::events_pending()) { Gtk::main_iteration();}
    }//protected function showMe()



    public function hideMe()
    {
        $this->dlgInstalling->set_modal(false);
        $this->dlgInstalling->hide();
        Gtk::main_quit();
    }//public function hideMe()



    /**
    *   The user may not close the window by hand
    */
    public function deleteWindow()
    {
        return true;
    }//public function deleteWindow()



    protected function appendToLog($strText)
    {
        $buffer = $this->txtLog->get_buffer();
        $end = $buffer->get_end_iter();
        $buffer->insert($end, $strText);

        $this->txtLog->scroll_to_iter($buffer->get_end_iter(), 0.49);
    }//protected function appendToLog($strText)



    protected function setCurrentAction($strText)
    {
        $this->arWidgets['lblInstalling']->set_text($strText);
    }//protected function setCurrentAction($strText)



    /**
    *   Set the icon for the dialog
    *
    *   @param int $nId     The stock item id
    */
    protected function setCurrentIcon($nId)
    {
        $this->arWidgets['imgInstalling']->set_from_stock($nId, Gtk::ICON_SIZE_DIALOG);
    }//protected function setCurrentIcon($nId)



    /**
    *   Set the progress bar progress in percent (0-100)
    */
    protected function setPercentage($nPercent)
    {
        if ($nPercent <= 0) {
            $this->arWidgets['progBarInstaller']->set_fraction(0);
        } else {
            $this->arWidgets['progBarInstaller']->set_fraction(100/$nPercent);
        }
    }//protected function setPercentage($nPercent)



    protected function setFinished()
    {
        if ($this->bSeriousError) {
            $this->setCurrentIcon(Gtk::STOCK_DIALOG_ERROR);
        } else {
            $this->setCurrentIcon(Gtk::STOCK_APPLY);
        }
        $this->setPercentage(100);
    }//protected function setFinished()



    public function __call($function, $arguments)
    {
        $this->appendToLog('__call: ' . $function . ' with ' . count($arguments) . ' arguments.' . "\r\n");
        foreach ($arguments as $strName => $strValue) {
            $this->appendToLog('   arg:' . $strName . ':' . gettype($strValue) . '::' . $strValue . "\r\n");
        }
        while (Gtk::events_pending()) { Gtk::main_iteration();}
    }//public function __call($function, $arguments)



    /**
    *   All functions which might be expected in a PEAR_Frontend go here
    */


    public function outputData($data, $command = null)
    {
        switch ($command) {
            case 'install':
            case 'upgrade':
            case 'upgrade-all':
                //FIXME: check for release warnings $data['release_warnings']
                $this->setCurrentAction('Installation of ' . $this->strPackage . ' done');
                $this->appendToLog($data['data'] . "\r\n");
                $this->setFinished();
                break;

            case 'uninstall':
                if ($this->bSeriousError) {
                    $this->setCurrentAction('Error uninstalling ' . $this->strPackage);
                    $this->arWidgets['expLog']->set_expanded(true);
                } else {
                    $this->setCurrentAction('Uninstallation of ' . $this->strPackage . ' ok');
                    $this->appendToLog('Uninstall ok' . "\r\n");
                }
                $this->setFinished();
                break;

            default:
if ($command !== null) {
    var_dump($command);
}
                if (isset($data['headline'])) {
                    $this->setCurrentAction($data['headline']);
                    $this->appendToLog('!!!' . $data['headline'] . '!!!' . "\r\n");

                    if (stripos($data['headline'], 'error') !== false) {
                        $this->setCurrentIcon(Gtk::STOCK_DIALOG_ERROR);
                        $this->arWidgets['expLog']->set_expanded(true);
                    }
                }

                if (is_array($data['data'])) {
                    //could somebody tell me if there is a fixed format?
                    foreach ($data['data'] as $nId => $arSubData) {
                        $this->appendToLog(implode(' / ', $arSubData) . "\r\n");
                    }
                } else if (is_string($data['data'])) {
                    $this->appendToLog($data['data'] . "\r\n");
                } else {
                    //What's this?
                    $this->appendToLog('PEAR_Frontend_Gtk2_Installation::outputData: Unhandled type "' . gettype($data['data']) . "\"\r\n");
                }
                while (Gtk::events_pending()) { Gtk::main_iteration();}
                break;
        }
    }//public function outputData($data, $command = null)



    function log($msg, $append_crlf = true)
    {
//require_once 'Gtk2/VarDump.php'; new Gtk2_VarDump(array($level, $msg, $append_crlf), 'msg');
        if (strpos($msg, 'is required by installed package') !== false) {
            $this->bSeriousError = true;
        }

        $this->appendToLog($msg . "\r\n");
        while (Gtk::events_pending()) { Gtk::main_iteration();}
    }//function log($msg, $append_crlf = true)


}//class PEAR_Frontend_Gtk2_Installation extends PEAR_Frontend
?>