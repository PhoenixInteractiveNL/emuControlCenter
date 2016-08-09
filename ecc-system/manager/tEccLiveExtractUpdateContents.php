<?PHP

$update_file = getcwd() . '\ecc-core\tools\ecc_update.zip';
unzip($update_file);


function unzip($zipfile)
{
    $zip = zip_open($zipfile);
    while ($zip_entry = zip_read($zip))    {
        zip_entry_open($zip, $zip_entry);
        if (substr(zip_entry_name($zip_entry), -1) == '/') {
            $zdir = substr(zip_entry_name($zip_entry), 0, -1);
            mkdir($zdir);
        }
        else {
            $name = zip_entry_name($zip_entry);
            $fopen = fopen($name, "w");
            fwrite($fopen, zip_entry_read($zip_entry, zip_entry_filesize($zip_entry)), zip_entry_filesize($zip_entry));
        }
        zip_entry_close($zip_entry);
    }
    zip_close($zip);
    return true;
}

?>