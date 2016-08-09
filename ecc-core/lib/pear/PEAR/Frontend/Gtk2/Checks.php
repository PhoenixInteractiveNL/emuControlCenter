<?php
/**
*   Some checks before the application is started.
*   That should ensure optimal user experience.
*
*   @author Christian Weiske <cweiske@php.net>
*/

//Check PHP version
if (version_compare('5.1.0dev', phpversion(), '>')) {
    echo "You need at least PHP 5.1.0 to run that program\r\n";
    exit(1);
}

//Check PHP-Gtk version
try {
    $ext = new ReflectionExtension("php-gtk");
} catch (ReflectionException $e) {
    echo "You need to install PHP-Gtk2\r\n";
    exit(2);
}
if (version_compare('2.0.0', $ext->getVersion()) < 0) {
    echo "You need at least PHP-Gtk version 2.0\r\n";
    exit(2);
}

//Do we have Glade? (was a problem on some php-gtk1 windows installations)
if (!class_exists('gladexml')) {
    echo "The GladeXML class is not available, but required.\r\n";
    $dialog = new GtkMessageDialog(
        null,//parent
        0,
        Gtk::MESSAGE_ERROR,
        Gtk::BUTTONS_OK,
        'The GladeXML class is not available, but required.'
    );
    $dialog->run();
    $dialog->destroy();
    exit(3);
}

//PEAR cache directory
require_once 'PEAR/Config.php';
$config     = PEAR_Config::singleton();
$cachedir   = $config->get('cache_dir');
if (!file_exists($cachedir)) {
    //Try to create the directory - if that fails, no problem:
    //the error message is thrown in the next if-block
    @mkdir($cachedir, 0777, true);
}
if (!is_writable($cachedir)) {
    $message = 'The PEAR cache directory "' . $cachedir . '" is NOT writable!' . "\r\n"
        . 'It is highly recommended that you make it writable before using the'
        . ' graphical installer.';
    echo $message . "\r\n";
    $dialog = new GtkMessageDialog(
        null,//parent
        0,
        Gtk::MESSAGE_WARNING,
        Gtk::BUTTONS_NONE,
        $message
    );
    $dialog->add_button('Close and fix it', 0);
    $dialog->add_button('Continue the program', 1);
    $answer = $dialog->run();
    $dialog->destroy();
    if ($answer == 0) {
        exit(4);
    }
}

?>