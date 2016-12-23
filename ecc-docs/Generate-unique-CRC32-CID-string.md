*Depricated* but still handy to generate a unique CRC32 (ECC CID) per ECC installation on a computer, code example:

    $ciString = @$_SERVER['USERDOMAIN']."|".@$_SERVER['TEMP']."|".@$_SERVER['TMP']."|".@$_SERVER['APPDATA']."|".@$_SERVER['COMPUTERNAME']."|".@$_SERVER['HOMEPATH'];
    $ciCheck = sprintf('%08X', crc32($ciString));
