<?php
class EccScript {
	
	private $cache = array();
	
	public function getAvailableEccScripts($systemIdent){

		$eccScript = array();
		
		$eccScriptDir = '../ecc-script/'.$systemIdent.'/';
		if(!file_exists($eccScriptDir)) return array();
		$dirHdl = opendir($eccScriptDir);
		while($file = readdir($dirHdl)){
			if(in_array($file, array('.', '..')) || is_dir($file)) continue;
			$pos = strripos($file, '.');
			if(substr($file, $pos) == '.eccscript'){
				$eccScript[] = substr($file, 0, $pos);	
			}
		}
		closedir($dirHdl);
		
		return $eccScript;
	}
	
	public function getRomIni($systemIdent, $crc32, $emulatorPlainBasename){
		
		$templateIniFileName = '../ecc-script/'.$systemIdent.'/'.$emulatorPlainBasename.'-template.ini';
		$romIniFileName = '../ecc-script-user/'.$systemIdent.'/'.$emulatorPlainBasename.'/eccscript_'.$crc32.'.ini';
		
		// only show, if there is an template available
		if(!file_exists($templateIniFileName)) return false;
		
		// load from filesystem or from cache
		
		if(isset($this->cache[$romIniFileName])){
			$romConfig = $this->cache[$romIniFileName];
		}
		else {
			$romConfigData = (file_exists($romIniFileName)) ? file($romIniFileName) : array();
			$romConfig = $this->parseEccScriptEmuConfig($romConfigData);
			$this->cache[$romIniFileName] = $romConfig;
		}
		
		if(isset($this->cache[$templateIniFileName])){
			$romConfigTemplate = $this->cache[$templateIniFileName];
		}
		else{
			$romConfigTemplate = $this->parseEccScriptEmuConfig(file($templateIniFileName));
			$this->cache[$templateIniFileName] = $romConfigTemplate;
		}
		
		return $this->getEmuConfigDiff($romConfigTemplate, $romConfig);
	}
	
	public function storeRomIni($systemIdent, $crc32, $emulatorPlainBasename, $newRomIniData){
		
		$templateIniFileName = '../ecc-script/'.$systemIdent.'/'.$emulatorPlainBasename.'-template.ini';
		$romIniFileName = '../ecc-script-user/'.$systemIdent.'/'.$emulatorPlainBasename.'/eccscript_'.$crc32.'.ini';
		
		$cachedIni = (isset($this->cache[$templateIniFileName])) ? $this->cache[$templateIniFileName] : array(); 
		$data = $this->getEmuConfigDiff($cachedIni, $this->parseEccScriptEmuConfig(explode("\n", $newRomIniData)));

		$romIni = '';
		foreach($data as $key => $value){
			if($value[0] != $value[1]){
				$romIni .= $value[1]."\r\n";
			}
		}
		
		if(!trim($romIni)){
			// dont store empty inis - cleanup
			@unlink($romIniFileName);
		}
		else{
			if(!is_dir(dirname($romIniFileName))) mkdir(dirname($romIniFileName), null, true);
			file_put_contents($romIniFileName, $romIni);
		}
		unset($this->cache[$romIniFileName]);
	}
	
	/**
	 * 
	 * [0] Data from default template
	 * [1] Data from rom ini
	 * 
	 * Changed:
	 * [collision_level] => Array
     * (
     *   [0] => collision_level=playfields
     *   [1] => collision_level=anotherfield
     * )
     * 
     * Inherited
     * [collision_level] => Array
     * (
     *   [0] => collision_level=playfields
     *   [1] => 
     * )
     * 
     * Error - not available in template
     * [collision_level] => Array
     * (
     *   [0] => 
     *   [1] => collision_level=playfields
     * )
	 *
	 * @param unknown_type $romConfigTemplateFile
	 * @param unknown_type $romConfigFile
	 * @return unknown
	 */
	public function getEmuConfigDiff($romConfigTemplate, $romConfig){
		
		$romConfigMerged = array_merge($romConfigTemplate, $romConfig);
		
		$out = array();
		foreach ($romConfigMerged as $key => $value){
			if(isset($romConfigTemplate[$key])){
				$out[$key][0] = $key.'='.$romConfigTemplate[$key];	
			}
			else{
				$out[$key][0] = false;
			}
			
			if(isset($romConfig[$key])){
				$out[$key][1] = $key.'='.$romConfig[$key];	
			}
			else{
				$out[$key][1] = false;
			}
			
		}
		return $out;
	}
	
	public  function parseEccScriptEmuConfig($templateData){
		
		$out = array();
		foreach ($templateData as $row) {
			
			$row = trim($row);
			if(!$row || substr($row, 0, 1) == ';') continue;
			
			// if '=' found, use key value
			if($strPos = strpos($row, '=')){
				$key = substr($row, 0, $strPos);
				$value = substr($row, $strPos+1);
			}
			else{
				// if no '=' was found, use complete row as key!
				$key = $row;
				$value = '';		
			}
			
			$out[trim($key)] = trim($value);
		}
	
		return $out;
	}
}
?>