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
	 * @return unknown
	 */
	public function storeItem() {
		$queryData = array();
		foreach ($this as $key => $value) if (substr($key, 0,1) != '_') $queryData[$key] = $value;
		$q = "INSERT INTO `romdb_meta_in` (".$this->kw(array_keys($queryData)).") VALUES (".$this->qs($queryData).") ";
		return $q;
	}

	public function getItemById($id) {
		$q = "SELECT * FROM `romdb_meta_in` WHERE `id` = ".$this->qs($id);
		
		print $q;
		
		$test = array(
			'name' => 'aa',
			'crc32' => 'bb',
		);
		
		return $this->createItemByRow($test);
	}
	
	public function  createItemByRow($row) {
		foreach ($row as $field => $value) $this->$field = $value;
		return $this;
	}
	
	
	/**
	 * ADD TO DBMS
	 */
	public function qs($data) {
		$sqlData = array();
		if (!is_array($data)) $sqlData[] = $data;
		else $sqlData = $data;
		
		$retData = array();
		foreach ($sqlData as $value) {
			switch(true) {
				case ($value === false || $value === 0):
					$retData[] = 0;
				break;
				case ($value === true):
					$retData[] = 1;
				break;
				case ($value == 'NULL'):
					$retData[] = 'NULL';
				break;
				case (!is_string($value) && @($value/$value) === 1):
					$retData[] = (int)$value;
				break;
				default:
					//$retData[] = "'".self::sql_escape($value)."'";
					$retData[] = "'".($value)."'";
				break;
			}
		}
		
		return implode(', ', $retData);
	}
	
	/**
	 * ADD TO DBMS
	 */
	public function kw($data) {
		$sqlData = array();
		if (!is_array($data)) $sqlData[] = $data;
		else $sqlData = $data;
		
		$retData = array();
		foreach ($sqlData as $value) {
			$retData[] = '`'.$value.'`';
		}
		return implode(', ', $retData);
	}
	
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
//		print "<pre>";
//		print_r($this);
//		print "</pre>";
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
		
		print "$function, $param".LF;
		
		$type = substr($function, 0, 3);
		if (!in_array($type, array('set', 'get'))) return false;
		$variable = strtolower(substr($function, 3));
		
		switch ($type) {
			case 'set':
				if (isset($this->$variable)) $this->$variable = $param[0];
				break;
			case 'get':
				return $this->$variable;
				break;
		}
	}
}
?>