<?
require_once('cFactory.php');

class EccMaintenance {
	
	public function __construct() {}
	
	public function cleaEccUserImageFolder($eccident = false){
		print __FUNCTION__.'<pre>';
		print_r($eccident);
		print '</pre>';
		
		$iniManager = FACTORY::get('manager/IniFile');
		print __FUNCTION__.'<pre>';
		print_r($iniManager->getUserFolder());
		print '</pre>';
	}
}
?>
