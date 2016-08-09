<?php
/*
 * Created on 08.09.2006
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class i18n {
	
	private static $langIdentDefault = 'en';
	private static $langIdent;
	private static $langDir;
	private static $langData = array();
	
	public function set($langIdent=FALSE) {
		self::$langIdent = ($langIdent) ? $langIdent : self::$langIdentDefault;
		self::$langDir = 'i18n/'.self::$langIdent.'/';
		self::readLangDir();
	}
	
	public static function get($category, $string) {
		if (!isset(self::$langData[$category][$string])) {
			$string_missing = "i18n['".$category."']['".$string."']";
			self::$langData[$category][$string] = str_replace("_","__",$string_missing);
			print "lang:".self::$langIdent."|".$string_missing."\n";
		}
		return self::$langData[$category][$string];
	}
	
	public static function readLangDir() {
		if (!is_dir(self::$langDir)) return false;
		$dirHdl = opendir(self::$langDir);
		while($file = readdir($dirHdl)) {
			if ($file=='.' || $file=='..') continue;
			include(self::$langDir.$file);
			if (isset($i18n) && is_array($i18n)) {
				self::$langData = array_merge(self::$langData, $i18n);
			}
		}
	}
	
	public static function translateArray($category, $languageArray, $createPlaceholder=false, $valueAsIndex=false) {
		$ret = array();
		foreach($languageArray as $key => $placeholder) {
			$needle = ($createPlaceholder) ? "[[".$placeholder."]]" : $placeholder;
			if ($valueAsIndex) $key = $placeholder;
			$ret[$key] = htmlspecialchars(I18n::get($category, $needle));
		}
		return $ret;
	}
}
?>