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

$zip = new ZipArchive();
$filename = "././ecc-tools/eccBugreport.zip";

if ($zip->open($filename, ZIPARCHIVE::CREATE)!==TRUE) {
    exit("cannot open <$filename>\n");
}

$zip->addFile("././ecc-tools/eccBugreport.htm","/eccBugreport.htm");

$zip->close();
?> 