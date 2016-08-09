<?php
/**
*   About dialog for the PEAR Gtk2 frontend
*
*   @author Christian Weiske <cweiske@php.net>
*/
class PEAR_Frontend_Gtk2_About extends GtkAboutDialog
{
    /**
    *   Creates the About dialog and fills it with
    *   the right values (name, version, programmer, ...)
    */
    public function __construct()
    {
        parent::__construct();
        $this->set_title('About PEAR package installer');
        $this->set_version('0.0.6');
        $this->set_name('PEAR package installer');

        $this->set_license("This program is licensed under the LGPL v2.1");

        $this->set_comments('Programmed by Christian Weiske <cweiske@php.net>'
            . "\r\n" . 'Sponsored by Ralf Schwoebel, www.tradebit.com');

        $this->set_website('http://gnope.org');

//        $this->set_authors(array('Christian Weiske <cweiske@cweiske.de>', 'Ralf Schwöbel'));
    }//public function __construct()



    /**
    *   Static function that just creates an instance of the
    *   About dialog, and run()s it afterwards.
    */
    public static function showMe()
    {
        $dlg = new PEAR_Frontend_Gtk2_About();
        $dlg->run();
    }//public static function showMe()

}//class PEAR_Frontend_Gtk2_About extends GtkAboutDialog
?>