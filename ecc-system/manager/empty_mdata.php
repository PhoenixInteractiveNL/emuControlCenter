<?
$sqlitDb = new SQLiteDatabase('ecc-system/database/eccdb_sqlite2', 0666, $sqliteerror);
$q = "delete from mdata";
print $q."\n";
$hdl = $sqlitDb->query($q);
$q = "delete from mdata_language";
print $q."\n";
$hdl = $sqlitDb->query($q);
$q = "vacuum";
print $q."\n";
$hdl = $sqlitDb->query($q);
print "DONE!\n";
?>
