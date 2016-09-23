## SAVE INI DATA FROM ECC.PHP (RAW EXAMPLE)
***
    $ini = new INI('..\ecc-user-configs\config\ecc_general.ini');
    $ini->data['USER_DATA']['database_path'] = 'database/';
    $ini->write();
