<?PHP

// Open the RAR file
$rar_file = rar_open(getcwd() . '\ecc-tools\ecc_update.rar') or die("Failed to open RAR archive");


// Read entries and extract entries (files)
$entries = rar_list($rar_file);

foreach ($entries as $entry) {
#    echo 'Filename: ' . $entry->getName() . "\n";
#    echo 'Packed size: ' . $entry->getPackedSize() . "\n";
#    echo 'Unpacked size: ' . $entry->getUnpackedSize() . "\n";

    $entry->extract(getcwd());
}

?>