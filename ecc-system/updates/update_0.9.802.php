<?php

// create new system folder
if(!is_dir('../ecc-user-configs/config')){
	mkdir('../ecc-user-configs/config');
}
// move old structure user config files
if(file_exists('../ecc-user-configs/ecc_history.ini')){
	rename('../ecc-user-configs/ecc_history.ini', '../ecc-user-configs/config/ecc_history.ini');
}
// move old structure user config files
if(file_exists('../ecc-user-configs/ecc_general.ini')){
	rename('../ecc-user-configs/ecc_general.ini', '../ecc-user-configs/config/ecc_general.ini');
}

$updateConfig = array(
	'db' => array( /*NO DB UPDATE */),
)
?>