<?php
class Item {
	
	private $errors = false;
	
	public function setDbms($dbmsObject) {
		$this->dbms = $dbmsObject;
	}
	
	/**
	 * magic function - used to support the syntax
	 * $item->setName('test') and $item->getName()
	 *
	 * @param string $function
	 * @param mixed $param
	 * @return unknown
	 */
	public function __call($function, $param) {
		assert(is_string($function));
		$type = substr($function, 0, 3);
		if (!in_array($type, array('set', 'get'))) return false;
		$variable = strtolower($function[3]).substr($function, 4);
		switch ($type) {
			case 'set':
				if (isset($this->$variable)) {
					$this->$variable = $param[0];
				}
				else throw new Exception('SETTER called for undefined variable "'.$variable.'"');
				break;
			case 'get':
				return $this->$variable;
				break;
		}
	}
	
	public function addError($field, $description) {
		$this->errors[$field] = $description;
	}
	
	public function getErrors() {
		return $this->errors;
	}
	
	public function getError($field) {
		return isset($this->errors[$field]) ? $this->errors[$field] : false;
	}
	
	public function hasErrors() {
		return ($this->errors) ? true : false;
	}
}
?>