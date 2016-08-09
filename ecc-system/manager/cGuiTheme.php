<?php
/*
 * Created on 03.10.2006
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

class GuiTheme {

	var $baseFolder;
	var $theme;
	var $colorIniFile = 'themeColors.ini';
	var $defaultTheme = 'default';
	
	public function __construct(){
		
		$this->baseFolder = ECC_DIR.'/ecc-themes/';
		
		$this->theme = FACTORY::get('manager/IniFile')->getKey('ECC_THEME', 'ecc-theme');
		
		if(!$this->theme) $this->theme = $this->defaultTheme;
	}
	
	public function getAvailableEccThemes() {
		
		$themes = array();
		$dirHdl = opendir($this->baseFolder);
		if (!$dirHdl) return $languages;
		while ($file = readdir($dirHdl)) {
			if ($file == '.' || $file == '..') continue;
			if(is_dir($this->baseFolder.$file)) $themes[] = $file;
		}
		$themes[] = 'none';
		return $themes;
	}
	
	public function getEccThemePreviewPath($theme){
		return $this->baseFolder.'/'.$theme.'/themePreview.png';
	}
	
	public function getEccThemeInfo($theme){
		$data = @parse_ini_file($this->baseFolder.'/'.$theme.'/themeInfo.txt');

		$ret = array();
		$ret['has_info'] = (count($data)) ? true : false; 
		$ret['name'] = (@$data['name']) ? trim($data['name']) : '';
		$ret['author'] = (@$data['author']) ? trim($data['author']) : '';
		$ret['date'] = (@$data['date']) ? trim($data['date']) : '';
		$ret['contact'] = (@$data['contact']) ? trim($data['contact']) : '';
		$ret['website'] = (@$data['website']) ? trim($data['website']) : '';
		
		return $ret;
	}
	
	public function getThemeFolder($fileName = '', $important = false, $themeOverwrite = false){
		
		$theme = ($themeOverwrite) ? $themeOverwrite : $this->theme;
		
		if($theme == 'none' && !$important){
			$fullPath = $this->baseFolder.'/eccThemeNone.png';
		}
		else{
			$fullPath = $this->baseFolder.$theme.'/'.$fileName;
			if($theme != $this->defaultTheme){
				if(!file_exists($fullPath)) $fullPath = $this->baseFolder.$theme.'/'.$fileName;;
			}
		}		
		
		return $fullPath;
	}
	
	public function getColorIniPath(){
		return realpath($this->baseFolder.$this->theme.'/'.$this->colorIniFile);
	}
}

?>
