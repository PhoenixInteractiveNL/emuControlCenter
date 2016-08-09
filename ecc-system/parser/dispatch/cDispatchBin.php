<?php
require_once('cDispatch.php');
class DispatchBin extends Dispatch {
	
	/**
	 * Validate order and action
	 * Key defines the used validator class
	 * e.g. parser/bin/cValidatorKey
	 * Value defines the parser on sucess!
	 */
	protected $validators = array(
		'gen' => 'FileParserGeneric#gen',
		'a26' => 'FileParserGeneric#a26',
	);

	/**
	 * call constructor in class Dispatch
	 *
	 * @param unknown_type $fHdl
	 */
	public function __construct($fHdl) {
		parent::__construct($fHdl);
	}
	
	public function getValidParser() {
		return $this->validate($this->getExtensionFromClassname(get_class($this)));
	}
}

?>