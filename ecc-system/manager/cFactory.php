<?php
class FACTORY {
	
	public static $_instances = array();
	private static $dbms;
	
	public function getModule($className, $parameter=false, $strictHash=""){
		return self::get('modules/'.'mod_'.$className, $parameter, $strictHash);
	}
	public function getManager($className, $parameter=false, $strictHash=""){
		return self::get('manager/'.$className, $parameter, $strictHash);
	}
	public function getItem($className, $parameter=false, $strictHash=""){
		return self::get('items/'.$className, $parameter, $strictHash);
	}
	
	public static function get($classNameIdent, $parameter=false, $strictHash="") {
		
		$className = $classNameIdent;
		$classPath = "";

		$splitBy = "/";
		if (FALSE !== $postition = strrpos($classNameIdent, $splitBy)) {
			$className = substr($classNameIdent, $postition+1);
			$classPath = substr($classNameIdent, 0, $postition+1);
		}
	
		if (isset(FACTORY::$_instances[$classPath.$className.$strictHash])) {
			return FACTORY::$_instances[$classPath.$className.$strictHash];
		}
		else {
			return FACTORY::register_instance($className, $classPath, $parameter, $strictHash);
		}
	}
	
	public function create($classNameIdent, $parameter=false) {
		
		$className = $classNameIdent;
		$classPath = "";

		$splitBy = "/";
		if (FALSE !== $postition = strrpos($classNameIdent, $splitBy)) {
			$className = substr($classNameIdent, $postition+1);
			$classPath = substr($classNameIdent, 0, $postition+1);
		}
		return FACTORY::register_instance($className, $classPath, $parameter, "");
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
		
		$cwdBackup = getcwd();
		chdir(ECC_DIR_SYSTEM);
		require_once($classFileName);
		chdir($cwdBackup);
		
		if ($parameter != false) {
			FACTORY::$_instances[$classPath.$className.$strictHash] = new $className($parameter);
		}
		else {
			FACTORY::$_instances[$classPath.$className.$strictHash] = new $className();
		}
		
		// if a manager has a class setDbms,
		// factory also sets the dbms!
		if (FACTORY::$dbms && method_exists(FACTORY::$_instances[$classPath.$className.$strictHash], 'setDbms')) {
			FACTORY::$_instances[$classPath.$className.$strictHash]->setDbms(FACTORY::$dbms);
		}
		
		return FACTORY::$_instances[$classPath.$className.$strictHash];
	}
	
	public function status() {
		foreach(FACTORY::$_instances as $objectName => $object) {
			print "$objectName ".get_class($object)."\n";
		}
		
	}
	
	public static function setDbms($dbmsObject) {
		FACTORY::$dbms = $dbmsObject;
	}
	public static function getDbms() {
		return FACTORY::$dbms;
	}
}
?>
