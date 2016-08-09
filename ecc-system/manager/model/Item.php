<?php
class Item {
	
	/**
	 * Array of all member variables used to create an crc32 checksum
	 * to compare rom metadata state changes getChecksum()
	 *
	 * @var Array of included member variables
	 */
	protected $checksumInclude = array();
	
	/**
	 * Creates an crc32 checksum of defined member fieds
	 *
	 * @return string crc32 checksum
	 */
	public function getChecksum(){
		if(!$this->getChecksumInclude()) $this->setError('ChecksumInclude missing');
		$string = '';
		foreach ($this->checksumInclude as $memberVariable){
			$memberGetter = 'get'.ucfirst($memberVariable);
			$data = $this->$memberGetter();
			if(is_array($data)){
				foreach ($data as $arrayKey => $arrayValue){
					$string .= $arrayValue;
				}
			}
			else{
				$data = (!$data || $data == 'NULL') ? ';' : $data;
				$string .= $data;
			}
		}
		return str_pad(strtoupper(dechex(crc32($string))), 8, '0', STR_PAD_LEFT);
	}
	
	/**
	 * @return Array of member variables to include
	 */
	public function getChecksumInclude() {
		return $this->checksumInclude;
	}
	
	/**
	 * Magic function to use getter and setter also,
	 * if these are not implmented!
	 *
	 * @param string $name
	 * @param Array $arguments
	 * @return mixed
	 */
	public function __call($name, $arguments){
		
		$type = substr($name, 0, 3);
		$memberVariable = substr($name, 3);
		$memberVariable[0] = strtolower($memberVariable[0]);

		if(!in_array($type, array('get', 'set'))) throw new Exception();
		switch ($type){
			case 'get':
				return $this->$memberVariable;
			case 'set':
				$this->$memberVariable = @$arguments[0];
			default:
				// impossible
		}
	}
	
	protected function isValidString($string, $minLength = 0, $maxLength = false){
		$stringLength = strlen($string);
		if($stringLength < $minLength || ($maxLength && $stringLength > $maxLength)){
			$this->setError('isValidString - Stringlength dont match');
		}
		return true;
	}
	
	/**
	 * Array of errors
	 *
	 * @var Array
	 */
	private $error = array();
	
	/**
	 * Add error to the error array
	 *
	 * @param string $string
	 */
	public function setError($string){
		$this->error[] = $string;
	}
	
	/**
	 * Return all errors found in error array
	 * 
	 * @return Array
	 */
	public function getError() {
		return $this->error;
	}
	
	/**
	 * Simplify the prit of this object
	 *
	 * @return string of the current object
	 */
	public function __toString(){
		return print_r($this, true);
	}
}
?>