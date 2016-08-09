<?
/**
 * emuControlCenter Tosec CM importer
 * 
 * @author Andreas Scheibel <ecc@camya.com>
 * @copyright Andreas Scheibel, 2007 emuControlCenter
 */

require_once('cImportDat.php');
class ImportDatControlMame extends ImportDat {
	
	private $datContentSets = false;
	
	/**
	 * Starts the import
	 */
	public function prepareData(){
		# process the found data
		$this->parseDat();
	}
	
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $eccident
	 * @return unknown
	 */
	public function importAllData($eccident){
		
		$this->dbms->query('BEGIN TRANSACTION;');
		
		if ($this->statusObj) $this->statusObj->update_message('Update database...');
		
		$statusCurrent = 0;
		$statusCount = count($this->datContentSets);
		
		# for flat output of the multirom datfile!
		$multiFileDatFields = array(
			'mergedEccCrc32',
			'name',
			'sourcefile',
			'cloneof',
			'romof',
			'description',
			'manufacturer',
			'year',
			'filesize',
			'type',
		);
		
		
		$multiFileDatOut = "Platform: ".$eccident.LF;
		$multiFileDatOut .= "CM-Datfile: ".$this->datFileName.LF.LF;
		$multiFileDatOut .= implode(';', $multiFileDatFields).LF;
		
		foreach($this->datContentSets as $index => $game){
			
			if(!$game['info']['mergedEccCrc32']) continue;
			
			# for flat output of the multirom datfile!
			foreach($multiFileDatFields as $key){
				$multiFileDatOut .= @$game['info'][$key].';';
			}
			$multiFileDatOut .= LF;
			
			$data['eccident'] = $eccident;

			$data['crc32'] = $game['info']['mergedEccCrc32']; # $combinedCrc32 = 'XXXXXXXX';
			$data['name'] = @$game['info']['description'];
			$data['extension'] = '';
			$data['info'] = '';
			$data['info_id'] = '';
			$data['running'] = "NULL";
			$data['bugs'] = "NULL";
			$data['trainer'] = "NULL";
			$data['intro'] = "NULL";
			$data['usermod'] = "NULL";
			$data['freeware'] = "NULL";
			$data['multiplayer'] = "NULL";
			$data['netplay'] = "NULL";
			$data['usk'] = "NULL";
			$data['year'] = @$game['info']['year'];
			$data['category'] = "NULL";
			$data['creator'] = '';
			$data['publisher'] = @$game['info']['manufacturer'];
			$data['storage'] = "NULL";
			$data['filesize'] = @$game['info']['size'];
			
			
			$q = "SELECT id FROM mdata WHERE eccident = '".sqlite_escape_string($eccident)."' AND crc32 = '".sqlite_escape_string($game['info']['mergedEccCrc32'])."' LIMIT 0,1";
			
			$hdl1 = $this->dbms->query($q);
			if ($res1 = $hdl1->fetch(SQLITE_ASSOC)) {
				#$id = $res1['id'];
				#print "UPDATE: ".$data['name']."\n";
				#$this->updateMeta($id, $data);
			}
			else {
				#print "- INSERT: ".$data['name']."\n";
				$id = $this->insertMeta($data);
			}
			
			# update status and handle cancelation
			if ('FAIL' === $this->updateStatusProgress('Import', $statusCount, $statusCurrent++, true, 10)) return false;
		}
		$this->dbms->query('COMMIT TRANSACTION;');
		
		LOGGER::add('datimportcm', trim($multiFileDatOut), 1, $eccident, 'w');
		LOGGER::close('datimportcm');
	}
	
	public function getBestMatch($hits, $fileId = false){

		
//		if ($fileId == 309) {
//			print "\n<pre>";
//			print_r($hits);
//			print "<pre>\n";
//			
//			# TODO REMOVE! :-)
//			# ONLY DEBUG
//			$logDir = '../ecc-logs/';
//			if (!is_dir($logDir)) mkdir($logDir);
//			$debug = array('ID' => $fileId, 'FOUND' => $hits);
//			file_put_contents($logDir.'/DEBUG_CM_AUDIT.txt', print_r($debug, true), FILE_APPEND);
//			
//		}
		
		$hitsCount = (isset($hits['complete'])) ? count($hits['complete']) : false;

		$match = false;
		
		$mergedSet = false;
		
		if (!$hitsCount) {
		}
		elseif ($hitsCount == 1) {
			$match = reset($hits['complete']);
		}
		elseif ($hitsCount > 1) {
			# give output - not unique

			# now try to get the best match percentage
			if (!$match && isset($value['crc']['hit_unmerged_clone'])) {
				$percent = array();
				foreach($hits['complete'] as $id => $value){
					$percent[$id] = count($value['crc']['hit_unmerged_clone']) * 100 / $value['info']['files_unique'];
				}
				arsort($percent);
			}
			
			$match = reset($hits['complete']);
			$mergedSet = $hitsCount-1;
		}
		
		# no match untill now
		if (!$match){
			$incompleteHitsCount = count(@$hits['incomplete']);
			if ($incompleteHitsCount == 1){
				$match = reset($hits['incomplete']);
			}
			elseif ($incompleteHitsCount > 1) {
				
				foreach ($hits['incomplete'] as $key => $iData) {
					if ($iData['valid']['splitSet']) {
						$match = $hits['incomplete'][$key];
					}
				}
				
				# now try to get the best match percentage
				if (!$match) {
					$percent = array();
					foreach($hits['incomplete'] as $id => $value){
						$percent[$id] = $value['crc']['hit_count'] * 100 / $value['crc']['total_count'];
					}
					arsort($percent);
					$match = $hits['incomplete'][$key];
				}
			}
		}
		
		if ($match) {
			$match['valid']['mergedSet'] = $mergedSet;
			if ($fileId) $this->updateFileAudit($fileId, $match, $hits);
		}
		
		return $match;
		
	}
	
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $eccident
	 * @return unknown
	 */
	public function importCompleteRoms($eccident){
		
		if ($this->statusObj) $this->statusObj->update_message('Update rom audit...');
		
//		# TODO REMOVE! :-)
//		# ONLY DEBUG
//		$logDir = '../ecc-logs/';
//		if (!is_dir($logDir)) mkdir($logDir);
//		$logFile = $logDir.'/DEBUG_CM_AUDIT.txt';
//		file_put_contents($logFile, '');
		
		# now audit
		$this->dbms->query('BEGIN TRANSACTION;');
		
		# total count
		$statusCurrent = 0;
		$hdl = $this->dbms->query('select count(*) as cnt from fdata where eccident="'.$eccident.'"');
		$statusCount = ($res = $hdl->fetch(SQLITE_ASSOC)) ? $res['cnt'] : 0;
		
		$allAvailableCrc32 = array();		
		$imageRename = array();
		$hdl = $this->dbms->query('select * from fdata where eccident="'.$eccident.'"');
		while($res = $hdl->fetch(SQLITE_ASSOC)) {
			
			if (!$res['mdata']) continue;
			
			$search = unserialize(base64_decode($res['mdata']));
			
			if (!$search) $search = array(basename($res['path']) => $res['crc32']);
			
			$hits = $this->searchForRom(array_flip($search), $res['path']);
			
			$bestMatch = $this->getBestMatch($hits, $res['id']);
			
//			if ($res['path'] == 'D:/# Emulation and Roms/# SNK/# NeoGeo/roms/svc.zip'){
//				print "\n<pre>";
//				print_r($bestMatch);
//				print "<pre>\n";
//			}
			
			if ($bestMatch){
				
				# state for clone unmerged sets
				#$cloneState = (isset($bestMatch['state_unmerged_clone'])) ? 'M: '.$bestMatch['state_unmerged_clone'].', ' : '';
				
				if (isset($bestMatch['info']['mergedEccCrc32']) && $res['crc32'] != $bestMatch['info']['mergedEccCrc32']){

					$crc32 = $bestMatch['info']['mergedEccCrc32'];
					$imageRename[$res['crc32']] = $bestMatch['info']['mergedEccCrc32'];
					
					# update file checksum
					$q = 'UPDATE fdata SET crc32 = "'.$crc32.'" WHERE eccident = "'.$eccident.'" AND crc32 = "'.$res['crc32'].'" ';
					$this->dbms->query($q);
					
					# update meta checksum
					$q = 'UPDATE mdata SET crc32 = "'.$crc32.'" WHERE eccident = "'.$eccident.'" AND crc32 = "'.$res['crc32'].'" ';
					$hdl1 = $this->dbms->query($q);
					
					# update user notes checksum
					$q = 'UPDATE udata SET crc32 = "'.$crc32.'" WHERE eccident = "'.$eccident.'" AND crc32 = "'.$res['crc32'].'" ';
					$hdl1 = $this->dbms->query($q);
				}
				else {
					$crc32 = $res['crc32'];
				}
				
				$data['eccident'] = $eccident;
				$data['crc32'] = $crc32;
				
				$data['name'] = @$bestMatch['info']['description'];
				$data['extension'] = '';
				$data['info'] = '';
				$data['info_id'] = '';
				$data['running'] = true;
				$data['bugs'] = 0;
				$data['trainer'] = "NULL";
				$data['intro'] = "NULL";
				$data['usermod'] = "NULL";
				$data['freeware'] = "NULL";
				$data['multiplayer'] = "NULL";
				$data['netplay'] = "NULL";
				$data['usk'] = "NULL";
				$data['year'] = @$bestMatch['info']['year'];
				$data['category'] = "NULL";
				$data['creator'] = '';
				$data['publisher'] = @$bestMatch['info']['manufacturer'];
				$data['storage'] = "NULL";
				
				$q = "SELECT id FROM mdata WHERE eccident = '".sqlite_escape_string($eccident)."' AND crc32 = '".sqlite_escape_string($crc32)."' LIMIT 0,1";
				$hdl1 = $this->dbms->query($q);
				if ($res1 = $hdl1->fetch(SQLITE_ASSOC)) {
					#print "UPDATE\n";
					#$id = $res1['id'];
					#$this->updateMeta($res['id'], $data);
				}
				else {
					#print "INSERT\n";
					$id = $this->insertMeta($data);
				}
			}

			# update status and handle cancelation
			if ('FAIL' === $this->updateStatusProgress('Update rom audit', $statusCount, $statusCurrent++, true, 10)) return false;
			
		}
		
		$this->dbms->query('COMMIT TRANSACTION;');
		
		# after this, import all other data
		$this->importAllData($eccident);
		
		# now correct also the image crc32
		# do this after the transaction is done!!!!
		if (count($imageRename)) FACTORY::get('manager/Image')->correctImageCrc32($eccident, $imageRename);
	}
	
	public function updateFileAudit($fileId, $match, $allData = false){
		
		$isMatch = (isset($match['info'])) ? true : false;
		
		$info = (isset($match['info'])) ? $match['info'] : false;
		$valid = (isset($match['valid'])) ? $match['valid'] : false;
		
		if (!$info || !$valid) return false;
		
		$allData = ($allData) ? serialize($allData) : '';
		
		$q = "
			REPLACE INTO fdata_audit (
				fdataId,
				isMatch,
				fileName,
				isValidFileName,
				isValidNonMergedSet,
				isValidSplitSet,
				isValidMergedSet,
				hasTrashfiles,
				cloneOf,
				romOf,
				mameDriver,
				foundFiles,
				cDate
			)
			VALUES (
				".(int)$fileId.",
				".(int)$isMatch.",
				'".@sqlite_escape_string($info['name'])."',
				".@(int)$valid['name'].",				
				".@(int)$valid['nonMergedSet'].",
				".@(int)$valid['splitSet'].",
				".@(int)$valid['mergedSet'].",
				".@(int)$valid['trashfiles'].",
				'".@sqlite_escape_string(@$info['cloneof'])."',
				'".@sqlite_escape_string(@$info['romof'])."',
				'".@sqlite_escape_string(@$info['sourcefile'])."',
				'".@sqlite_escape_string($allData)."',
				'".time()."'
			)
		";
		#print $q."\n";
		$hdl = $this->dbms->query($q);
	}
	
	public function updateCloneState($eccident){
		
		$q = 'SELECT * FROM fdata fd INNER JOIN fdata_audit fa on (fd.id = fa.fDataId AND fa.cloneOf != "") WHERE fd.eccident = "'.sqlite_escape_string($eccident).'"';
		$hdl = $this->dbms->query($q);
		$clonesOf = array();
		while($res = $hdl->fetch(SQLITE_ASSOC)) {
			$clonesOf[$res['fa.fDataId']] = $res['fa.cloneOf'];
		}
		
		foreach($clonesOf as $cloneId => $void) {
			$q = 'UPDATE fdata_audit SET originalExists = 0 WHERE fDataId = '.$cloneId.'';
			$this->dbms->query($q);
		}
		
		if (!count($clonesOf)) return false;
		
		foreach($clonesOf as $id => $originalName) $clonesOf[$id] = sqlite_escape_string($originalName);
		$fileNames = '"'.join('", "', $clonesOf).'"';
		
		$q = 'SELECT fDataId, fileName FROM fdata_audit WHERE cloneOf = "" AND fileName IN ('.$fileNames.')';
		$hdl = $this->dbms->query($q);
		$originals = array();
		while($res = $hdl->fetch(SQLITE_ASSOC)) {
			$originals[$res['fDataId']] = $res['fileName'];
		}
	
		if (!count($originals)) return false;
		
		foreach($clonesOf as $cloneId => $void) {
			$q = 'UPDATE fdata_audit SET originalExists = 1 WHERE fDataId = '.$cloneId.'';
			$this->dbms->query($q);
		}
	}
	
	/**
	 * Searches for given crc32 checksums in an Tosec CM file
	 * 
	 * This method tries to get all matches from the given
	 * datfile body from $this->datContentSets by testing $search
	 * against this data. Matches are returned containing all needed
	 * data like rom-informations (name, year) and crc arrays containg
	 * the hits, misses and wrong crc32 checksums plus filenames
	 * 
	 * Search has to be an array in this format
	 * array('aof2_c1.rom' => '17B9CBD2', 'aof2_c6.rom' => '31DE68D3');
	 * 
	 * @author Andreas Scheibel <ecc@camya.com>
	 * @copyright Andreas Scheibel, 2007 emuControlCenter
	 * @see parseDat()
	 * 
	 * @param array $search containing 'filename' => crc32
	 * @return array containing all match-data
	 */
	public function searchForRom($search, $searchFilePath = false){
		
		if (!$this->datContentSets) $this->parseDat();
		
		
		# first search for the crc in the file
		$maybes = array();
		foreach($search as $searchCrc32 => $searchFileName) {
			#print "$searchFileName => $searchCrc32\n";
			if (isset($this->datContentIndex['crcTank'][$searchCrc32])) {
				#print "#";
				foreach($this->datContentIndex['crcTank'][$searchCrc32] as $gameId => $uniqueRom) {
					$maybes[$gameId] = false;
				}
			}
		}

//		print "$searchFilePath\n<pre>";
//		print_r($maybes);
//		print "<pre>\n";
		
		#if ($this->statusObj) $this->statusObj->update_message('searching....');
		
		$match = false; # contains the match data
		$sort = array(); # used to sort desc, if more than one match found

		# loop though all games from datfile to find matches
		#foreach($this->datContentSets as $index => $game){
		foreach($maybes as $index => $void){
			
			$game = $this->datContentSets[$index];
			
//			print "$searchFilePath\n<pre>";
//			print_r($game);
//			print "<pre>\n";
			
			if (!isset($game['rom_unique'])) continue;
			
			# calculate the count of unique roms in set
			$uniqueRomsCount = count($game['rom_unique']);
			
			$hits = 0;
			$searchTemp = $search; # used to get an miss array containing not matched roms
			foreach($game['rom_unique'] as $crc32 => $romData){
				
				# continue, if no hit!
				if (!isset($search[$crc32])) continue;
				
				#if (!isset($romData[$index]['romdata']['crc'][$crc32])){

					# if not set, set the gameinformation
					if (!isset($match[$index]['info'])){
						$match[$index]['info'] = $game['info'];
						
						# setup filename
						$match[$index]['valid']['name'] = (FACTORY::get('manager/FileIO')->get_plain_filename($searchFilePath) == $match[$index]['info']['name']) ? true : false;
						
					}
					
					# set total unique file count
					if (!isset($match[$index]['crc']['total_count'])) $match[$index]['crc']['total_count'] = $uniqueRomsCount;

					# update hit counter for the current rom
					$hits++;
					
					#$sort[$index] = $hits;
					
					$match[$index]['crc']['hit_count'] = $hits;

					# set all found crc32 to hit array
					$match[$index]['crc']['hit'][$crc32] = $romData['name'];
					
					# for clones
					# maybe the user has only a diff of the original rom
					# this is possible in many emulators
					if (isset($this->datContentSets[$index]['rom_unique_unmerged_simple'][$crc32])){
						$match[$index]['crc']['hit_unmerged_clone'][$crc32] = $this->datContentSets[$index]['rom_unique_unmerged_simple'][$crc32];
					}
					
					# unset the temp search result for $match[$index]['crc']['miss'] array
					unset($searchTemp[$crc32]);
				#}
			}
			
			# only process, if matches found in file
			if (isset($match[$index]['crc']['hit'])){
				
				# test, if the set is allready complete
				if ($match[$index]['crc']['hit_count'] == $match[$index]['crc']['total_count']){
					$match[$index]['state'] = 'complete';
					
					$match[$index]['valid']['nonMergedSet'] = true;
				}
				else {

					# set state to incomplete
					$match[$index]['state'] = 'incomplete';
					
					$match[$index]['valid']['nonMergedSet'] = false;
					
					# create an array with the missing roms
					$match[$index]['crc']['miss'] = array_diff_assoc(($game['rom_unique_simple']), ($match[$index]['crc']['hit']));
				}
				
				
				if (count(@$this->datContentSets[$index]['rom_unique_unmerged_simple']) == count(@$match[$index]['crc']['hit_unmerged_clone'])){
					#$match[$index]['state_unmerged_clone'] = 'ok';
					$match[$index]['valid']['splitSet'] = true;
				}
				else {
					#$match[$index]['state_unmerged_clone'] = 'miss';
					$match[$index]['valid']['splitSet'] = false;
				}
				
				$sort[$index] = count(@$this->datContentSets[$index]['rom_unique_unmerged_simple']);
				
			}
			
			# wrong crc found in file / could be removed
			$match[$index]['crc']['wrong'] = $searchTemp;
			
			# files in the zip like help, readme, docs aso
			$match[$index]['valid']['trashfiles'] = (count($searchTemp)) ? true : false;			
			
			while (gtk::events_pending()) gtk::main_iteration();
		}
		
		# if there is more than one result-set matched, sort hitcounts desc
		$result = array();
		if (count($match) > 1) {
			arsort($sort);
			foreach($sort as $index => $count){
				$result[$match[$index]['state']][$index] = $match[$index];
			}
		}
		else {
			# only one possible set found!
			$result[$match[$index]['state']] = $match;
		}
		
//		# TODO REMOVE! :-)
//		# ONLY DEBUG
//		$logDir = '../ecc-logs/';
//		if (!is_dir($logDir)) mkdir($logDir);
//		$debug = array('PATH' => $searchFilePath, 'FOUND' => $result);
//		file_put_contents($logDir.'/DEBUG_CM_AUDIT.txt', print_r($debug, true), FILE_APPEND);
		
		return $result;
	}
	
	/**
	 * extract dat header and body from datFileString
	 *
	 * @return void
	 */
	private function parseDat($combineMergedSets = true){
		
		if ($this->statusObj) $this->statusObj->update_message('parsing datfile...');
		
		# read in the file
		$this->readDatfileContent();
		
		if (!$this->datFileString) return false;
		
		$out = array();
		$clones = array();
		$gameIndex = array();
		$headerDone = false;
		$gamePos = 0;
		$lineCount = 0;
		$crcTank = array();
		foreach($this->datFileString as $currentPos => $line){
			
			if ($this->statusObj) if ($this->statusObj->is_canceled()) return false;
			
			if (!$line = trim($line)) continue;
			
			# first extract header
			if (!$headerDone){
				if ($line ==  ')'){
					$headerDone = true;
				}
				else{
					if ($name = $this->extractField($line, 'name')) $this->datContentHeader['name'] = $name;
					if ($description = $this->extractField($line, 'description')) $this->datContentHeader['description'] = $description;
					if ($category = $this->extractField($line, 'category')) $this->datContentHeader['category'] = $category;
					if ($version = $this->extractField($line, 'version')) $this->datContentHeader['version'] = $version;
					if ($author = $this->extractField($line, 'author')) $this->datContentHeader['author'] = $author;
				}
			}
			else {
				# if headerDone, try to get games / resources
				if (0 === strpos($line, 'game (')){
					$entryType = 'game';
					$gamePos++; 
				}
				elseif(0 === strpos($line, 'resource (')){
					$entryType = 'resource';
					$gamePos++;
				}
				elseif ($line ==  ')'); // do nothing - only skip
				else{
					
					# extract rom informations
					$out[$gamePos]['info']['type'] = $entryType;
					if ($name = $this->extractField($line, 'name')) $out[$gamePos]['info']['name'] = $name;
					
					$gameIndex[$out[$gamePos]['info']['name']] = $gamePos;
					
					if ($description = $this->extractField($line, 'description')) $out[$gamePos]['info']['description'] = $description;
					if ($year = $this->extractField($line, 'year')) $out[$gamePos]['info']['year'] = $year;
					if ($manufacturer = $this->extractField($line, 'manufacturer')) $out[$gamePos]['info']['manufacturer'] = $manufacturer;
					if ($author = $this->extractField($line, 'author')) $out[$gamePos]['info']['author'] = $author;

					if ($romof = $this->extractField($line, 'romof')) $out[$gamePos]['info']['romof'] = $romof;
					if ($cloneof = $this->extractField($line, 'cloneof')) $out[$gamePos]['info']['cloneof'] = $cloneof;
					
					if ($cloneof) $clones[$gamePos] = $cloneof;
					
					if ($sourcefile = $this->extractField($line, 'sourcefile')) $out[$gamePos]['info']['sourcefile'] = $sourcefile;

					# handle romdata
					if (0 === $dataPos = strpos($line, 'rom (')) {
		
						# create an array of rom data
						$data = explode(' ', $line);
						
						#if (false === $crc = $this->getRomFieldValue($data, 'crc', 'strtoupper')) continue;
						
						# extract fields
						$romData = array();

						# crc
						preg_match('/ crc (.*?) /i', $line, $matches);
						if (isset($matches[0])){
							$crc = strtoupper($matches[1]);
						}
						else continue;
												
						# name
						if (false === strpos($line, 'name "')){
							preg_match('/ name (.*?) /i', $line, $matches);
							if (isset($matches[0])) $romData['name'] = $name = $matches[1];
						}
						else {
							preg_match('/ name "(.*?)" /i', $line, $matches);
							if (isset($matches[0])) $romData['name'] = $name = $matches[1];
						}
						
						# merge
						if (false === strpos($line, 'merge "')){
							preg_match('/ merge (.*?) /i', $line, $matches);
							if (isset($matches[0])) $romData['merge'] = $merge = $matches[1];
						}
						else {
							preg_match('/ merge "(.*?)" /i', $line, $matches);
							if (isset($matches[0])) $romData['merge'] = $merge = $matches[1];
						}
						
						# crc
						preg_match('/ size (.*?) /i', $line, $matches);
						if (isset($matches[0])){
							$romData['size'] = $size = $matches[1];
						}
						
						if (isset($romData['merge'])){
							$romData['merge'] = $merge;
							$type = 'rom_merge';
							
						}
						else{
							$type = 'rom_unique';
							
						}
						
						$crcTank[$crc][$gamePos] = 1;
						
						# add romdate to the array
						$out[$gamePos][$type][$crc] = $romData;
						$out[$gamePos][$type.'_simple'][$crc] = $name;
						
						$out[$gamePos][$type.'_unmerged_simple'][$crc] = $name;
						
						
						
						
					}
				}
			}
			$lineCount++;
			
//			# TODO REMOVE! :-)
//			# ONLY DEBUG
//			$logDir = '../ecc-logs/';
//			if (!is_dir($logDir)) mkdir($logDir);
//			file_put_contents($logDir.'/DEBUG_CM_AUDIT_TANK_PARSE.txt', print_r($crcTank, true), FILE_APPEND);

			if ($lineCount >= 1000) $lineCount = 0;
			if ($lineCount%250==0) {
				# update status and handle cancelation
				if ('FAIL' === $this->updateStatusProgressInfinity('Analyze data...')) return false;
			}
		}
		
		if ($combineMergedSets) {
			# TODO How to use clone merge?
			# handle clones
			# not really needed in the first step!
			# but working
			$cloneCount = count($clones);
			$clonePos = 0;
			foreach($clones as $cloneId => $original){
	
				# get the original id
				$originalId = $gameIndex[$original];
				
				# create union for unique roms
				if (isset($out[$cloneId]['rom_unique']) && is_array($out[$cloneId]['rom_unique'])) {
					$out[$cloneId]['rom_unique'] = $out[$cloneId]['rom_unique'] + $out[$originalId]['rom_unique'];
					$out[$cloneId]['rom_unique_simple'] = $out[$cloneId]['rom_unique_simple'] + $out[$originalId]['rom_unique_simple'];					
				}
				else {
					$out[$cloneId]['rom_unique'] = array();
					$out[$cloneId]['rom_unique_simple'] = array();
				}

				# create diff for the merged roms
				if (isset($out[$cloneId]['rom_merge']) && is_array($out[$cloneId]['rom_merge'])) {
					if(!is_array($out[$originalId]['rom_unique'])) $out[$originalId]['rom_unique'] = array();
					if(!is_array($out[$originalId]['rom_unique_simple'])) $out[$originalId]['rom_unique_simple'] = array();
					$out[$cloneId]['rom_merge'] = array_diff_assoc($out[$cloneId]['rom_merge'], $out[$originalId]['rom_unique']);
					$out[$cloneId]['rom_merge_simple'] = array_diff_assoc($out[$cloneId]['rom_merge_simple'], $out[$originalId]['rom_unique_simple']);	
				}
				else {
					$out[$cloneId]['rom_merge'] = array();
					$out[$cloneId]['rom_merge_simple'] = array();
				}
				
				# update status and handle cancelation
				if ('FAIL' === $this->updateStatusProgress('Analyze data...', $cloneCount, $clonePos++, false, 25)) return false;
				
			}
		}
		
		$outCount = count($out);
		$outPos = 0;
		foreach($out as $pos => $game){
			
			$out[$pos]['info']['mergedEccCrc32'] = (isset($out[$pos]['rom_unique_simple'])) ?  FileIO::createMergedEccCrc32(array_flip($out[$pos]['rom_unique_simple'])) : false;
			
			$out[$pos]['info']['size'] = 0;
			if (isset($out[$pos]['rom_unique']) && is_array($out[$pos]['rom_unique'])) foreach($out[$pos]['rom_unique'] as $data) $out[$pos]['info']['size'] += $data['size'];
			
			$out[$pos]['info']['files_unique'] = (isset($out[$pos]['rom_unique_simple'])) ?  count($out[$pos]['rom_unique_simple']) : 0;
			$out[$pos]['info']['files_merge'] = (isset($out[$pos]['rom_merge_simple'])) ?  count($out[$pos]['rom_merge_simple']) : 0;
			
			# update status and handle cancelation
			if ('FAIL' === $this->updateStatusProgress('Analyze data...', $outCount, $outPos++, false, 25)) return false;
		}
		
		$this->datContentIndex['crcTank'] = $crcTank;
		$this->datContentIndex['clones'] = $clones;
		$this->datContentIndex['games'] = $gameIndex;
		
//		# TODO REMOVE! :-)
//		# ONLY DEBUG
		#$logDir = '../ecc-logs/';
		#if (!is_dir($logDir)) mkdir($logDir);
		#file_put_contents($logDir.'/DEBUG_CM_AUDIT_PARSE.txt', print_r($out, true));
		
		# assing the sets
		$this->datContentSets = $out;
	}
	
	public function resetDatfileContent(){
		$this->datContentSets = false;
	}
	
	/**
	 * Extract data from and given string
	 *
	 * @param string $haystack
	 * @param string $needle
	 * @param string $functionName php-function name to change the data
	 * @return mixed string on success, false on fail
	 */
	private function extractField($haystack, $needle, $functionName = false){
		if (0 === $dataPos = strpos($haystack, $needle)) {
			$fieldData = trim(substr($haystack, strlen($needle)));
			$fieldData = str_replace('"', '', $fieldData);
			if ($functionName) $fieldData = $functionName($fieldData);
			return $fieldData;
		}
		return false;
	}
	
	/**
	 * Extract data from and given array with an given offset
	 *
	 * @param array $haystack
	 * @param string $needle
	 * @param string $functionName php-function name to change the data
	 * @param int $offset for fields after found needle 
	 * @return mixed string on success, false on fail
	 */
	private function getRomFieldValue($haystack, $needle, $functionName = false, $offset = 1){
		if (false !== $romDataPos = array_search($needle, $haystack)){
			$hit = $haystack[$romDataPos+$offset];
			if ($functionName) $hit = $functionName($hit);
			return $hit;
		}
		return false;
	}
	
	/**
	 * Validates the given datfile headers
	 */
	protected function validateHeader(){
		
	}
	
	/**
	 * Validates the given datfile internal format
	 */	
	protected function validateFormat() {
		
	}
	
	/**
	 * Enter description here...
	 *
	 */
	protected function readDatfileContent(){
		$this->datFileString = file($this->datFileName);
	}
	
	public function auditBySystemDat($eccident){
		$this->datFileName = 'datfile/'.$eccident.'.dat';
		if (!file_exists($this->datFileName)) return false;
		$this->datFileString = file($this->datFileName);
		return true;
	}
}
?>