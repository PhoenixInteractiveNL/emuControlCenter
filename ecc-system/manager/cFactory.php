<?php
class FACTORY {
	
	public static $_instances = array();
	private static $dbms;
	
	public static function get($classNameIdent, $parameter=false, $strictHash="") {
		
		$className = $classNameIdent;
		$classPath = "";

		$splitBy = "/";
		if (FALSE !== $postition = strrpos($classNameIdent, $splitBy)) {
			$className = substr($classNameIdent, $postition+1);
			$classPath = substr($classNameIdent, 0, $postition+1);
		}
	
		if (isset(FACTORY::$_instances[$className.$strictHash])) {
			return FACTORY::$_instances[$className.$strictHash];
		}
		else {
			return FACTORY::register_instance($className, $classPath, $parameter, $strictHash);
		}
	}
	
	/*
	 * This function ads a checksum from parameters to the
	 * keys in $_instances ($strictHash) to get a unique object for a
	 * parameter combination
	 * needed in cEccParserDataProcessor to get the right generic parser!
	 */
	public function getStrict($classNameIdent, $parameter=false) {
		$strictHash = md5(serialize($parameter));
		return FACTORY::get($classNameIdent, $parameter, $strictHash);
	}
	
	/*
	*
	*/
	private static function register_instance($className, $classPath, $parameter=false, $strictHash="") {
		$classFileName = $classPath."c".$className.".php";
		require_once($classFileName);

		if ($parameter != false) {
			FACTORY::$_instances[$className.$strictHash] = new $className($parameter);
		}
		else {
			FACTORY::$_instances[$className.$strictHash] = new $className();
		}

		// if a manager has a class setDbms,
		// factory also sets the dbms!
		if (FACTORY::$dbms && method_exists(FACTORY::$_instances[$className.$strictHash], 'setDbms')) {
			FACTORY::$_instances[$className.$strictHash]->setDbms(FACTORY::$dbms);
		}

		return FACTORY::$_instances[$className.$strictHash];
	}
	
	public function status() {
		foreach(FACTORY::$_instances as $objectName => $object) {
			print "$objectName ".get_class($object)."\n";
		}
		
	}
	
	public function setDbms($dbmsObject) {
		FACTORY::$dbms = $dbmsObject;
	}
}
?>
