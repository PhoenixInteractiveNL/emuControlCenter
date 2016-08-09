<?php
$os_type = PHP_OS;
$os_type = strtolower($os_type);

$file_dir=dirname(__FILE__);

chdir ($file_dir);
$file_envment_ini = fopen('..\infos\ecc_local_envment_info.ini', "a") or die("Can't open file");

fwrite($file_envment_ini, "os_type=" . $os_type . "\n"); 

fclose($file_envment_ini);
?>


