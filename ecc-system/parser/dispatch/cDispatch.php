<?php
// new singleton factory


require_once(dirname(__FILE__).'/../../manager/cFactory.php');

abstract class Dispatch {

	/**
	 * Validate order and action
	 * Key defines the used validator class
	 * e.g. bin/cValidatorKey
	 * Value defines, if true escapes the test with sucess
	 * So win => false escapes, if this is validated as
	 * windows binary file
	 */
	protected $validators = array();
	
	private $fHdl  = false;

	public function __construct($fHdl) {
		$this->fHdl = $fHdl;
	}
	
	public function validate($extension) {
		if (!$extension) return false;
		$this->extension = $extension;
		
		return $this->runValidators();
	}
	
	private function runValidators() {
		
		$path = dirname(__FILE__).DIRECTORY_SEPARATOR.$this->extension;
		if (!realpath($path)) return false;
		
		foreach($this->validators as $eccident => $exitType) {
			$classFile = $path.'/Validator'.ucfirst($eccident);
			$oValidator = FACTORY::get($classFile);
			$oValidator->setFileHandle($this->fHdl);
			if ($oValidator->validate()) {
				FileIO::ecc_reset($this->fHdl);
				return $exitType;
			}
		}
		FileIO::ecc_reset($this->fHdl);
		return false;
	}
	
	protected function getExtensionFromClassname($classname) {
		return strtolower(str_replace("Dispatch", "", $classname));
	}
}
?>