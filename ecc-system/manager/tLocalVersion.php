<?php
error_reporting(E_ALL|E_STRICT);

define('LF', "\n");
define('DEBUG', true);

chdir(getcwd().'/ecc-system/'); // change to ecc-system dir
define('ECC_DIR_OFFSET', "..".DIRECTORY_SEPARATOR); # needed for relative paths
define('ECC_DIR', getcwd().'/../'); # contains basepath of ecc
define('ECC_DIR_SYSTEM', realpath(ECC_DIR.'/ecc-system/')); # contains ecc-system dir
require_once('manager/cFactory.php');

$mngrValidator = FACTORY::get('manager/Validator');
$release = $mngrValidator->getEccCoreKey('ecc_release');

$versionInfos = '
[GENERAL]
current_version="'.$release["local_release_version"].'"
date_build="'.$release['local_release_date'].'"
current_build="'.$release['release_build'].'"
';

file_put_contents('system/info/ecc_local_version_info.ini', trim($versionInfos));
?>
