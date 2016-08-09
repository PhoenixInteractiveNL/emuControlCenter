<?php

class UserData {
	
	private $dbms = false;

	public function __construct() {}
	
	public function setDbms($dbmsObject) {
		$this->dbms =  $dbmsObject;
	}
	
	public function getUserdata($eccident, $crc32) {
		if (!$eccident || !$crc32) return false;
		$q="SELECT * FROM udata WHERE eccident = '".sqlite_escape_string($eccident)."' AND crc32 = '".sqlite_escape_string($crc32)."' LIMIT 1";
		$hdl = $this->dbms->query($q);
		return $hdl->fetch(SQLITE_ASSOC);
	}
	
	public function getAllUserdata() {
		$q="SELECT * FROM udata WHERE eccident != '' AND crc32 != '' ORDER BY eccident, crc32";
		$hdl = $this->dbms->query($q);
		$out = array();
		while($res = $hdl->fetch(SQLITE_ASSOC)) $out[] = $res;
		return $out;
	}
	
	public function getRomBookmarks(){
		$q="
			SELECT eccident, crc32
			FROM fdata fd
			INNER JOIN fdata_bookmarks fdb ON (fd.id = fdb.file_id)
			ORDER BY eccident, crc32
		";
		$hdl = $this->dbms->query($q);
		$out = array();
		while($res = $hdl->fetch(SQLITE_ASSOC)) $out[] = $res;
		return $out;
	}
	
	public function getRomHistory(){
		$q="
			SELECT eccident, crc32, launchtime, launchcnt
			FROM fdata WHERE launchtime IS NOT NULL
			ORDER BY eccident, launchcnt DESC
		";
		$hdl = $this->dbms->query($q);
		$out = array();
		while($res = $hdl->fetch(SQLITE_ASSOC)) $out[] = $res;
		return $out;
	}
	
	public function updateNotesById($userDataId, $notes) {
		if (!$userDataId) return false;
		$q = "UPDATE udata SET notes = '".sqlite_escape_string($notes)."' WHERE id = ".(int)$userDataId."";
		$hdl = $this->dbms->query($q);
	}
	
	public function insertNotesByRomident($eccident, $crc32, $notes) {
		if (!$eccident || !$crc32) return false;
		$notes = trim($notes);
		$q = "INSERT INTO udata (eccident, crc32, notes) VALUES ('".sqlite_escape_string($eccident)."', '".sqlite_escape_string($crc32)."', '".sqlite_escape_string($notes)."')";
		$this->dbms->query($q);
		return $this->dbms->lastInsertRowid();
	}
	
	public function deleteNotesByRomident($eccident, $crc32) {
		if (!$eccident || !$crc32) return false;
		$q = "DELETE FROM udata WHERE eccident = '".sqlite_escape_string($eccident)."' AND '".sqlite_escape_string($crc32)."'";
		$this->dbms->query($q);
	}
	
	public function deleteById($id) {
		if (!$id) return false;
		$q = "DELETE FROM udata WHERE id = ".(int)$id."";
		$this->dbms->query($q);
	}
	
	public function updateFullUserData($data){
		$q = "
			REPLACE INTO udata (
			eccident,
			crc32,
			notes,
			rating,
			rating_fun,
			rating_gameplay,
			rating_graphics,
			rating_music,
			review_title,
			review_body,
			review_export_allowed,
			hiscore,
			difficulty,
			cdate
			) VALUES (
			'".sqlite_escape_string($data['eccident'])."',
			'".sqlite_escape_string($data['crc32'])."',
			'".sqlite_escape_string($data['notes'])."',
			".(int)$data['rating'].",
			".(int)$data['rating_fun'].",
			".(int)$data['rating_gameplay'].",
			".(int)$data['rating_graphics'].",
			".(int)$data['rating_music'].",
			'".sqlite_escape_string($data['review_title'])."',
			'".sqlite_escape_string($data['review_body'])."',
			".(int)$data['review_export_allowed'].",
			'".sqlite_escape_string($data['hiscore'])."',
			".(int)$data['difficulty'].",
			".time()."
			)
		";
		$hdl = $this->dbms->query($q);
	}
	
	function exportXml(){
		
		$mngrValidator = FACTORY::get('manager/Validator');
		$release = $mngrValidator->getEccCoreKey('ecc_release');
		
		$xml = "<?xml version='1.0' standalone='yes'?><userdata>";
		
		$generator = trim('
		  <generator name="'.htmlspecialchars($release['title']).'" version="'.htmlspecialchars($release['local_release_version']).'-'.htmlspecialchars($release['release_build']).'">
		     <exportdate value="'.date('Ymd').'" />
		     <website><![CDATA['.$this->cleanXmlCdata($release['website']).']]></website>
		  </generator>
		');
		
		$xml .= $generator;
		
		$xml .= '<games>';
		
		$userData = $this->getAllUserdata();
		foreach($userData as $userData){
			
			$review = '<review />';
			$visibleReview = false;
			if(
				trim($userData['review_title']) ||
				trim($userData['review_body'])
			){
				
				$visibleReview = true;
				
				$title = (trim($userData['review_title'])) ? '<title><![CDATA['.$this->cleanXmlCdata($userData['review_title']).']]></title>' : '<title />';
				$body = (trim($userData['review_body'])) ? '<body><![CDATA['.$this->cleanXmlCdata($userData['review_body']).']]></body>' : '<body />';
				
				$review = trim('
				<review>
					'.$title.'
					'.$body.'
					<export value="'.(int)$userData['review_export_allowed'].'"/>
				</review>
				');
			}
			
			
			$personal = '<personal />';
			$visiblePersonal = false;
			if(
				trim($userData['notes']) ||
				$userData['difficulty'] ||
				$userData['hiscore'] ||
				$userData['launchcnt']
			){

				$visiblePersonal = true;
				
				$notes = (trim($userData['notes'])) ? '<notes><![CDATA['.$this->cleanXmlCdata($userData['notes']).']]></notes>' : '<notes />';
				
				$personal = trim('
				<personal>
					'.$notes.'
					<difficulty value="'.(int)$userData['difficulty'].'" />
					<hiscore value="'.(int)$userData['hiscore'].'" />
					<launchcount value="'.(int)$userData['launchcnt'].'" />
				</personal>
				');
			}
			
			$rating = '<rating />';
			$visibleRating = false;
			if($userData['rating']){
				
				$visibleRating = true;
				
				$rating = trim('
				<rating value="'.(int)$userData['rating'].'">
					<fun value="'.(int)$userData['rating_fun'].'" />
					<gameplay value="'.(int)$userData['rating_gameplay'].'" />
					<graphics value="'.(int)$userData['rating_graphics'].'" />
					<music value="'.(int)$userData['rating_music'].'" />
				</rating>
				');
			}
			
			if($visibleReview || $visiblePersonal || $visibleRating){
				$xml .= trim('
					<game system="'.$userData['eccident'].'" crc32="'.$userData['crc32'].'">
					'.$review.'
					'.$personal.'
					'.$rating.'
					</game>
				')."\n";
			}
			
		}
		
		$xml .= '</games>';

		$userBookmarks = $this->getRomBookmarks();
		if($userBookmarks){
			$xml .= '<bookmarks>';
			foreach ($userBookmarks as $bookmark){
				$xml .= '<game system="'.$bookmark['eccident'].'" crc32="'.$bookmark['crc32'].'" />';	
			}
			$xml .= '</bookmarks>';	
		}
		else{
			$xml .= '<bookmarks />';
		}
		
		$userBookmarks = $this->getRomHistory();
		if($userBookmarks){
			$xml .= '<history>';
			foreach ($userBookmarks as $bookmark){
				$xml .= '<game system="'.$bookmark['eccident'].'" crc32="'.$bookmark['crc32'].'" date="'.$bookmark['launchtime'].'" count="'.$bookmark['launchcnt'].'" />';	
			}
			$xml .= '</history>';	
		}
		else{
			$xml .= '<history />';
		}
		
		$xml .= '</userdata>';
		
		# output data
		$userFolder = FACTORY::get('manager/IniFile')->getUserFolder(false, '#_GLOBAL', true);
		$filename = $userFolder.'/emuControlCenter-userdata_'.date('Ymd-his', time()).'.xml';
		if(file_put_contents($filename, trim($xml))){
			return realpath($filename);
		}
		return false;
		
	}
	
	private function cleanXmlCdata($string){
		return trim(str_replace(']]>', '', $string));
	}
	
}

?>
