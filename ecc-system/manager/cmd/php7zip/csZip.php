<?php

require_once 'item/sZipListItem.php';
require_once 'item/sZipInfoItem.php';


/**
 * Wrapper for an configurable 7zip executable
 * Supports extracting from and listing of 7z archives.
 * 
 * Documentation: http://www.camya.com/php/7zip/
 *
 * @author Andreas Scheibel <ecc@camya.com>
 * @version 0.1.0 
 * 
 * @copyright 2008 by Andreas Scheibel
 * 
 */
class sZip {
	
	private $executableFile;
	private $executableCommand;
	private $executableSwitches;
	
	private $file;

	public function __construct() {/* NOT USED */}
	
	/**
	 * Extracts a file from an archive to a given directory
	 *  
	 * Command
	 * 7z.exe e -y -bd -otemp/ 7z457_extra.7z Installer\cr.bat
	 * 
	 * @param string $sZipFile
	 * @param string $fileName
	 * @param string $outputFolder relative path
	 */
	public function extract($sZipFile, $fileName, $outputFolder = false) {

		// if no output folder is given, extract direct to 7zip file folder 
		if(!$outputFolder) $outputFolder = dirname($sZipFile);
		if(!file_exists($outputFolder)) mkdir($outputFolder);
					
		$this->setExecutableCommand('e');
		$this->setFile(escapeshellarg(realpath($sZipFile)).' '.escapeshellarg($fileName));
		$switchOutput = ($outputFolder) ? '-o"'.$outputFolder.'"' : '' ;
		$this->setExecutableSwitches('-y '.$switchOutput);

		$command = $this->getCommand(true);
		system($command);
		
		return true;
	}
	
	/**
	 * Extracts ALL files from an archive to a given directory including subdirs!
	 *  
	 * Command
	 * 7z.exe x -y -bd -otemp/ 7z457_extra.7z
	 * 
	 * @param string $sZipFile
	 * @param string $fileName
	 * @param string $outputFolder relative path
	 */
	public function extractAll($sZipFile, $fileName, $outputFolder = false) {

		// if no output folder is given, extract direct to 7zip file folder 
		if(!$outputFolder) $outputFolder = dirname($sZipFile);
		if(!file_exists($outputFolder)) mkdir($outputFolder);
					
		$this->setExecutableCommand('x');
		$this->setFile(escapeshellarg(realpath($sZipFile)));
		$switchOutput = ($outputFolder) ? '-o"'.$outputFolder.'"' : '' ;
		$this->setExecutableSwitches('-y '.$switchOutput);
		
		$command = $this->getCommand(true);
		system($command);
		
		return true;
	}

	/**
	 * List contents of archive
	 * 
	 * Setup for 7zip execution
	 * Command 'l'
	 * Switches '-scsWIN'
	 * 
	 * Output of commandline tool:
	 * Path = 1\FMT_DIC.7z
	 * Size = 323054
	 * Packed Size = 658888
 	 * Modified = 2008-02-27 23:30:57
	 * Attributes = ....A
	 * CRC = C9B152A6
	 * Method = LZMA:20
	 * Block = 0
	 * 
	 * @param string $zipfile
	 * @param string $executableSwitches
	 * @return array of sZipListItem items
	 */
	public function getList($zipfile){

		$this->setExecutableCommand('l');
		$this->setFile(escapeshellarg(realpath($zipfile)));
		$this->setExecutableSwitches('-scsWIN');

		# get data from command
		$stdout = false; # init var
		$command = $this->getCommand();
		exec($command, $stdout);
		
		# start extracting informations
		$out = array();
		$startpointFound = false;
		foreach($stdout as $row){
			# if line is empty, skip. If path = found, start new array!
			if(!trim($row)) continue;
			elseif(0 === strpos($row, '-------------------')){
				
				# if allready found, this is the end :-)
				if($startpointFound) break;
				else $startpointFound = true;

				# get field length
				$fieldLenght = split(' ', $row);
				foreach($fieldLenght as $idx => $value) $fieldLenght[$idx] = strlen($value);
			}
			else{
				if(!$startpointFound) continue;

				$listItem = new sZipListItem();
				$listItem->setName(trim(substr($row, $fieldLenght[0]+1+$fieldLenght[1]+1+$fieldLenght[2]+1+$fieldLenght[3]+1+1)));
				$listItem->setDate(trim(substr($row, 0, $fieldLenght[0])));
				$listItem->setAttr(trim(substr($row, $fieldLenght[0]+1, $fieldLenght[1])));
				$listItem->setSize(trim(substr($row, $fieldLenght[0]+1+$fieldLenght[1]+1, $fieldLenght[2])));
				$listItem->setCompressed(trim(substr($row, $fieldLenght[0]+1+$fieldLenght[1]+1+$fieldLenght[2]+1, $fieldLenght[3])));
				$listItem->finalize();
				$out[$listItem->getName()] = $listItem;
			}
		}
		#asort($out);
		
		return $out;
	}
	
	/**
	 * Show technical information list of files in archive
	 * 
	 * Setup for 7zip execution
	 * Command 'l'
	 * Switch '-slt -scsWIN' 
	 * 
	 * Output of commandline tool:
	 * Path = 1\FMT_DIC.7z
	 * Size = 323054
	 * Packed Size = 658888
	 * Modified = 2008-02-27 23:30:57
	 * Attributes = ....A
	 * CRC = C9B152A6
	 * Method = LZMA:20
	 * Block = 0
	 *
	 * @param string $zipfile filename of the 7zip file
	 * @return array of sZipInfoItem items
	 */
	public function getInfo($zipfile){
		
		$this->setExecutableCommand('l');
		$this->setFile(escapeshellarg(realpath($zipfile)));
		$this->setExecutableSwitches('-slt -scsWIN');
		
		# get data from command
		$stdout = false; # init var
		$command = $this->getCommand();
		$code = exec($command, $stdout);
		if($code > 0) return false; # here, errorcode false is "all fine" ;-)
		
		# start extracting informations
		$out = array();

		foreach($stdout as $row){
			
			$row = trim($row);
			
			# if line is empty, skip. If path = found, start new array!
			if(!$row) continue;
			elseif(0 === strpos($row, 'Path =')){
				
				# store the last item
				if(isset($infoItem)){
					$infoItem->finalize();
					$out[$infoItem->getPath()] = $infoItem;
				}
				
				# now get the new data;
				list(, $path) = split('=', $row);
				$infoItem = new sZipInfoItem();
				$infoItem->setPath($path);
			}
			
			if(!isset($infoItem)) continue;
			
			# get the key and value and set object
			
			$split = explode('= ', $row);
			$key = trim(array_shift($split));
			$value = join('= ', $split);
			
			switch ($key) {
				case 'Path':
					$infoItem->setPath($value);
					break;
				case 'Size':
					$infoItem->setSize($value);
					break;
				case 'Packed Size':
					$infoItem->setPackedSize($value);
					break;
				case 'Modified':
					$infoItem->setModified($value);
					break;
				case 'Attributes':
					$infoItem->setAttributes($value);
					break;
				case 'CRC':
					$infoItem->setCrc($value);
					break;
				case 'Method':
					$infoItem->setMethod($value);
					break;
				case 'Block':
					$infoItem->setBlock($value);
					break;
			}
		}
		# store the last item
		if(isset($infoItem)){
			$infoItem->finalize();
			$out[$infoItem->getPath()] = $infoItem;
		}
		asort($out);
		
		return $out;
	}
	
	/**
	 * create the current shell command as string
	 *
	 * @return string command to execute
	 */
	public function getCommand($silent = false){
		$executable = FACTORY::get('manager/Os')->getEightDotThreePath($this->getExecutableFile());
		$command = $this->getExecutableCommand();
		$switches = $this->getExecutableSwitches();
		$file = $this->getFile();
		$silent = ($silent) ? '> nul' : ''; 
		$command = $executable.' '.$command.' '.$switches.' '.$file.' '.$silent;
		
		print $command."\n";
		
		return $command;
	}
	
	public function setExecutable($executableFile){
		$this->setExecutableFile(realpath($executableFile));
	}
	
	public function setExecutableFile($executableFile){
		if(!realpath($executableFile)) throw new Exception('Error');
		$this->executableFile = $executableFile;
	}
	
	public function getExecutableFile(){
		return $this->executableFile;
	}
	
	public function setExecutableCommand($executableCommand){
		$this->executableCommand = $executableCommand;
	}
	
	public function getExecutableCommand(){
		return trim($this->executableCommand);
	}
	
	public function setExecutableSwitches($executableSwitches){
		$this->executableSwitches = $executableSwitches;
	}
	
	public function getExecutableSwitches(){
		return trim($this->executableSwitches);
	}
	
	public function setFile($file){
		$this->file = $file;
	}
	
	public function getFile(){
		return $this->file;
	}
}
?>