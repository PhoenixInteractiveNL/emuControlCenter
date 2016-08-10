<?

class Validator {

	private $eccCoreConfig = false;

	public function getEccCoreKey($key) {
		if (!$this->eccCoreConfig) $this->unpackEccCoreDat();
		return isset($this->eccCoreConfig[$key]) ? $this->eccCoreConfig[$key] : false;
	}

	public function unpackEccCoreDat() {
		// 2016.08.10 [Phoenix] RENDERED OBSOLETE, ECC IS ON GITHUB NOW AND IS AWAITING IMPROVEMENTS ;-)

		//$eccCoreData = str_rot13(@file_get_contents('ecccore.dat'));
		//$eccCoreDataMd5 = substr($eccCoreData, 0, 16).substr($eccCoreData, -16);
		//$eccPhpMd5 = md5_file('ecc.php'); /*if ($eccPhpMd5 != $eccCoreDataMd5) die('##');*/
		//$eccConfig = unserialize(str_rot13(base64_decode(str_rot13(substr($eccCoreData, 16, -16)))));
		/*if (!@$eccConfig || $eccPhpMd5 != $eccConfig['checksum']) die('##');*/
		//$this->eccCoreConfig = $eccConfig;

		// Load in 'PLAIN' PHP CORE settings.
		define('ECC_DIR', realpath(dirname(__FILE__).'/../')); # contains basepath of ecc
		define('ECC_DIR_SYSTEM', realpath(ECC_DIR.'/ecc-system/')); # contains ecc-system dir
		require_once(ECC_DIR_SYSTEM.'/ecccore.php');

		$this->eccCoreConfig = $eccConfig;
	}
}
?>