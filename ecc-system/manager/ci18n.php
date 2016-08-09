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
	
	public static function set($langIdent=FALSE) {
		self::$langIdent = ($langIdent) ? $langIdent : self::$langIdentDefault;
		self::$langDir = 'translations/'.self::$langIdent.'/';
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
		
		#$destEncoding = 'UTF-8';
		$destEncoding = 'CP1250';
		
		# read encoding ini - this is not needed, if data is UTF-8
		$charsetIniFile = self::$langDir.'/charset.ini';
		$charsetIni = (file_exists($charsetIniFile)) ? parse_ini_file($charsetIniFile, true) : false;
		$sourceCharset = (isset($charsetIni['characterset']) && $charsetIni['characterset'] != $destEncoding) ? trim($charsetIni['characterset']) : false;
		
		$dirHdl = opendir(self::$langDir);
		while($file = readdir($dirHdl)) {
			
			if ($file=='.' || $file=='..' || self::$langDir.'/'.$file == $charsetIniFile || substr($file, -4) == '.ini' ) continue;
			include(self::$langDir.$file);
			if (isset($i18n) && is_array($i18n)) {
				foreach($i18n as $type => $i18nData){
					foreach($i18nData as $key => $value){
						if ($sourceCharset){
							$i18n[$type][$key] = iconv($sourceCharset, $destEncoding.'//TRANSLIT', $value);
						}
						else {
							$i18n[$type][$key] = $value;							
						}
						
						#$i18n[$type][$key] = htmlspecialchars($i18n[$type][$key]);
						

					}
				}

				self::$langData = array_merge(self::$langData, $i18n);
				$i18n = false;
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
	
	public static function getLanguageIdent(){
		return self::$langIdent;
	}
}
?>