<?php
/**
*   Some static class function to execute
*   PHP scripts in a new php instance
*
*   @author Christian Weiske <cweiske@php.net>
*/
require_once 'PEAR/Config.php';

class Gnope_AppRunner_Exec
{
    /**
    *   Runs the given .php file in a separate process.
    *   This function first checks if the file exists, then switches 
    *   to the Windows or Linux-specific functions.
    *
    *   @param string   $strPath    The .php file to execute
    *   @return boolean             True if all went ok, false if an error occured
    */
    public static function run($strPath)
    {
        if (!file_exists($strPath)) {
            $msg = new GtkMessageDialog(
                $this,
                0,
                Gtk::MESSAGE_ERROR,
                Gtk::BUTTONS_OK,
                'Can\'t find the program file:' . "\r\n" . $strPath
            );
            $msg->run();
            $msg->destroy();
            return false;
        }

        if (strstr(strtoupper(PHP_OS), 'WIN')) {
            return self::runWindows($strPath);
        } else {
            return self::runLinux($strPath);
        }
    }//public static function run($strPath)



    /**
    *   Runs the given file ($strPath) with the php executable
    *   It uses System_Command to start php detached.
    *
    *   It tries to find the php executable via the PEAR config
    *   variable "php_bin" and checks for existance of the passed
    *   php file $strPath
    *
    *   @param string   $strPath    The .php file to execute
    *   @return boolean             True if all went ok, false if an error occured
    */
    protected static function runLinux($strPath)
    {
        $strPhpPath = PEAR_Config::singleton()->get('php_bin');
        if (!file_exists($strPhpPath)) {
            $msg = new GtkMessageDialog(
                $this,
                0,
                Gtk::MESSAGE_ERROR,
                Gtk::BUTTONS_OK,
                'Can\'t find the PHP executable:' . "\r\n" . $strPhpPath
            );
            $msg->run();
            $msg->destroy();
            return false;
        }

        require_once 'System/Command.php';

        $cmd = new System_Command();
        $cmd->pushCommand($strPhpPath, $strPath);
        $cmd->setOption('BACKGROUND', true);
        $cmd->setOption('OUTPUT', false);
        $ret = $cmd->execute();
        if (!is_bool($ret) && is_object($ret)) {
            require_once 'Gtk2/VarDump.php';
            new Gtk2_VarDump($ret);
            return false;
        }

        return true;
    }//protected static function runLinux($strPath)



    /**
    *   Runs the given file ($strPath) with the php executable.
    *   This is the windows version.
    *   If possible, php-win.exe is used.
    *
    *   @param string   $strPath    The .php file to execute
    *   @return boolean             True if all went ok, false if an error occured
    */
    protected static function runWindows($strPath)
    {
        $strPhpPath = self::findPhpExe();

        if ($strPhpPath === null) {
            //No php.exe found
            return false;
        }

        //If you do 'start /b "c:\pa th\php.exe" "bla.phpw"', the editor is opened...
        //So to work around the spaces, we need to chdir first and run it then
        chdir(dirname($strPhpPath));
        $strCommand = 'start /b ' . basename($strPhpPath) . ' "' . $strPath . '"';
        //echo $strCommand . "\r\n";

        //I tried exec(), but that doesn't spawn it in the background
        $proc = popen($strCommand, 'r');
        if ($proc === false) {
            return false;
        }
        pclose($proc);
        return true;
    }//protected static function runWindows($strPath)



    /**
    *   Pass a path to an executable, and the php-win.exe in that
    *   directory is checked for existance. If it exists, it is returned,
    *   otherwise the orginal file is used.
    *
    *   @param string $strPhpPath   The path to a php executable
    *   @return string              The same path or the path to php-win.exe
    */
    protected static function getPhpWinIfPossible($strPhpPath)
    {
        if (basename($strPhpPath) != 'php-win.exe') {
            //Try to use php-win.exe instead of php.exe
            $strPhpPath2 = dirname($strPhpPath) . DIRECTORY_SEPARATOR . 'php-win.exe';
            if (file_exists($strPhpPath2)) {
                $strPhpPath = $strPhpPath2;
            }
        }
        return $strPhpPath;
    }//protected static function getPhpWinIfPossible($strPhpPath)



    /**
    *   Tries to find php.exe.
    *
    *   @return string  The path to php.exe (or php-win.exe) if found, NULL if not found
    */
    protected static function findPhpExe()
    {
        //Try to find the php executable
        $strPhpPath = PEAR_Config::singleton()->get('php_bin');
        if (file_exists($strPhpPath)) {
            return self::getPhpWinIfPossible($strPhpPath);
        }

        //PEAR Config setting is wrong
        $strPhpPath = PEAR_Config::singleton()->get('bin_dir') . DIRECTORY_SEPARATOR . 'php.exe';
        if (file_exists($strPhpPath)) {
            return self::getPhpWinIfPossible($strPhpPath);
        }

        //Another wrong setting
        $strPearPath = PEAR_Config::singleton()->get('php_bin') . '/';
        //try one to three directories up
        for ($nA = 1; $nA <= 3; $nA++) {
            $strPhpPath2 = $strPearPath . str_repeat('../', $nA) . 'php.exe';
            if (file_exists($strPhpPath2)) {
                return self::getPhpWinIfPossible($strPhpPath2);
            }
        }//for

        //TODO: check some common paths like C:\php\, C:\php5\ and so

        return null;
    }//protected static function findPhpExe()

}//class Gnope_AppRunner_Exec
?>