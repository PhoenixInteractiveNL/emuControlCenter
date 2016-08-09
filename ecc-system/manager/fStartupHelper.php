<?php
/**
 * Helpers for os external ecc tools
 * like eccStartup, eccLive aso!
 *
 */
class EccExtHelper {
	
	/**
	 * Writes the local host infos to the filesystem
	 *
	 * @param string $filename
	 * @return boolean
	 */
	public static function writeLocalHostInfo($filename) {
		if(!is_dir(dirname($filename))) return false;
		# put host info for autoupdate!
		$hostInfo = '[ECC_HOST_INFO]'."\r\n";
		$_SERVER['OS_TYPE'] = strtolower(PHP_OS);
		foreach($_SERVER as $key => $value){
			if(is_array($value)) $hostInfo .= $key.' = "'.implode('|', $value).'"'."\r\n";
			else $hostInfo .= $key.' = "'.$value.'"'."\r\n";
		}
		return file_put_contents($filename, $hostInfo);
	}
}
?>