<?php
/**
* Config.php example with IniCommented container
* This container is for PHP .ini files, when you want
* to keep your comments. If you don't use comments, you'd rather
* use the IniFile.php container.
* @author 	Bertrand Mansion <bmansion@mamasam.com>
* @package	Config
*/
// $Id: IniCommented.php,v 1.3 2003/03/21 18:01:09 mansion Exp $

require_once('Config.php');

$datasrc = '/usr/local/php5/lib/php.ini';

$phpIni = new Config();
$root =& $phpIni->parseConfig($datasrc, 'inicommented');
if (PEAR::isError($root)) {
	die($root->getMessage());
}

// Convert your ini file to a php array config

echo '<pre>'.$root->toString('phparray', array('name' => 'php_ini')).'</pre>';
?>
