<?php
class Singleton {
	
	// hält alle objects unique in einem
	// statischen array
	public static $_instances = array();
	
	/*
	*
	*/
	public function get_instance($class_name_ident, $path=false) {
		
		$constr_param = false;
		if (strpos($class_name_ident, '#')) {
			$split = explode('#', $class_name_ident);
			$class_name = array_shift($split);
			
			if (count($split)) {
				foreach ($split as $param) {
					$constr_param[] = $param;
				}
			}
		}
		else {
			$class_name = $class_name_ident;
		}
		
		if (isset(Singleton::$_instances[$class_name_ident])) {
			return Singleton::$_instances[$class_name_ident];
		}
		else {
			return Singleton::register_instance($class_name, $path, $class_name_ident, $constr_param);
		}
	}
	
	/*
	*
	*/
	private function register_instance($class_name, $path, $class_name_ident, $constr_param=false) {
		$name = $path."class".$class_name.".php";
		require_once($name);
		if ($constr_param != false) {
			Singleton::$_instances[$class_name_ident] = new $class_name($constr_param);
		}
		else {
			Singleton::$_instances[$class_name_ident] = new $class_name();
		}
		return Singleton::$_instances[$class_name_ident];
	}
}
?>
