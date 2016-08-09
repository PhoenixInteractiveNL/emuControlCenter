<?php
/**
 * Enter description here...
 *
 */
class Item {

	protected $_checksum = false;
	protected $_errors = array();
	
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $function
	 * @param unknown_type $param
	 * @return unknown
	 */
	public function __call($function, $param) {
		
		assert(is_string($function));
		
		$type = substr($function, 0, 4);
		if (!in_array($type, array('set_', 'get_'))) return false;
		
		$variable = substr($function, 4);
		
		switch ($type) {
			case 'set_':
				if (isset($this->$variable)) $this->$variable = $param[0];
				break;
			case 'get_':
				return $this->$variable;
				break;
		}
	}
	
	/**
	 * Enter description here...
	 *
	 */
	protected function createItemChecksum($filterVariables = false) {
		
		$filter  = ($filterVariables) ? $filterVariables : array();
		$checksumData = array();
		
		foreach ($this as $key => $value) {
			if (!in_array($key, $filter) && substr($key, 0, 1) != '_') $checksumData[] = $key."=".$value.";";
		}
		
		$this->_checksum = sprintf('%08X', crc32(join(',', $checksumData)));
		
		return $this->_checksum;

	}
	
//	public function getChecksum() {
//		return ($this->_dcs) ? $this->_dcs : false;
//	}
	
//	/**
//	 * Enter description here...
//	 *
//	 * @return unknown
//	 */
//	public function buildSqlQuery() {
//		$queryData = array();
//		foreach ($this as $key => $value) {
//			if (substr($key, 0,1) != '_') $queryData[$key] = $value;
//		}
//		$q = "INSERT INTO `romdb_meta_in` (".DBMS::kw(array_keys($queryData)).") VALUES (".DBMS::qs($queryData).") ";
//		return $q;
//	}
	
	public function addError($field, $description) {
		$this->_errors[$field] = $description;
	}
	
	public function getErrors() {
		return $this->_errors;
	}
	
	public function getError($field) {
		return isset($this->_errors[$field]) ? $this->_errors[$field] : false;
	}
	
	public function hasErrors() {
		return (count($this->_errors)) ? true : false;
	}
	
	/**
	 * Enter description here...
	 *
	 */
	public function __to_string() {
		print "<pre>";
		print_r($this);
		print "</pre>";
	}
}
?>