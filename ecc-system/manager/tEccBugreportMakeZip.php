<?php

// Hi,
//
// This PHP file is hailed by eccBugreport.exe and makes a ZIP file of the HTM output,
// before ECC Bugreport sends it to the server.
// This is using the PHP extension php_zip.dll so the thirdparty tool RAR.EXE isn't
// needed for this anymore.
//
// p.s.
// This file is hailed from the ECC root. (like ecc-core\php.exe ecc-system\filename.php)

$bugreportFolder = '../../ecc-core/tools/eccBugreport';
$zipFileName = $bugreportFolder.'/'.'eccBugreport.zip';
$reportFileName = $bugreportFolder.'/'.'eccBugreport.htm';

$zip = new ZipArchive();

if ($zip->open($zipFileName, ZIPARCHIVE::CREATE)!==TRUE) exit("cannot open <$zipFileName>\n");

$zip->addFile($reportFileName, '/eccBugreport.htm'');

$zip->close();
?> 