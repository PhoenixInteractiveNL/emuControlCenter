<?php
/*
*
*/
class FileIOCrc32 {

	public $crc32Table = array();
	
	public function __construct() {
		$this->initCrc32Table();
	}
	
     /** gets a crc32 checksum from a given text file
	 * 
	 * @param filename $$name
	 */
	public function getCrc32FromFile($name) {

       // Start out with all bits set high.
       $crc=0xffffffff;

       if(($fp=fopen($name,'rb'))===false) return false;

       // Perform the algorithm on each character in file
       for(;;) {
           $i=@fread($fp,1);
           if(strlen($i)==0) break;
           $crc=(($crc >> 8) & 0x00ffffff) ^ $this->crc32Table[($crc & 0xFF) ^ ord($i)];
       }
      
       @fclose($fp);
      
       // Exclusive OR the result with the beginning value.
       return dechex($crc ^ 0xffffffff);
	}
	
     /** gets a crc32 checksum from a given text file
	 * 
	 * @param string $text
	 */
	public function getCrc32FromString($text) {
		// Once the lookup table has been filled in by the two functions above,
		// this function creates all CRCs using only the lookup table.
		
		// You need unsigned variables because negative values
		// introduce high bits where zero bits are required.
		// PHP doesn't have unsigned integers:
		// I've solved this problem by doing a '&' after a '>>'.
		
		// Start out with all bits set high.
		$crc=0xffffffff;
		$len=strlen($text);
		
		// Perform the algorithm on each character in the string,
		// using the lookup table values.
		for($i=0;$i < $len;++$i) {
		    $crc=(($crc >> 8) & 0x00ffffff) ^ $this->crc32Table[($crc & 0xFF) ^ ord($text{$i})];
		}
		
		// Exclusive OR the result with the beginning value.
		return dechex($crc ^ 0xffffffff);
	}
	
	/** Builds lookup table array
	 * 
	 */
	private function initCrc32Table() {
		// This is the official polynomial used by
		// CRC-32 in PKZip, WinZip and Ethernet.
		$polynomial = 0x04c11db7;
		
		// 256 values representing ASCII character codes.
		for($i=0;$i <= 0xFF;++$i) {
			$this->crc32Table[$i]=($this->crc32Reflect($i,8) << 24);
			for($j=0;$j < 8;++$j) {
				$this->crc32Table[$i] = (($this->crc32Table[$i] << 1) ^ (($this->crc32Table[$i] & (1 << 31))?$polynomial:0));
			}
			$this->crc32Table[$i] = $this->crc32Reflect($this->crc32Table[$i], 32);
		}
	}
	
	/**
	 * Creates a CRC from a text string
	 *
	 * @param unknown_type $ref
	 * @param unknown_type $ch
	 * @return unknown
	 */
	private function crc32Reflect($ref, $ch) {
		$value=0;
		
		// Swap bit 0 for bit 7, bit 1 for bit 6, etc.
		for($i=1;$i<($ch+1);++$i) {
			if($ref & 1) $value |= (1 << ($ch-$i));
			$ref = (($ref >> 1) & 0x7fffffff);
		}
		return $value;
	}
}

$fileIoCrc32 = new FileIOCrc32();
chdir(dirname(__FILE__));

$filename = 'Pokemon Stadium 2 (U) [!].v64';

$time_start = microtime(true);
print str_pad (strtoupper(dechex(crc32(file_get_contents($filename)))), 8, '0', STR_PAD_LEFT)."\n";
$time_end = microtime(true);
$time = $time_end - $time_start;
print "file_get_contents ".$time."\n";

$time_start = microtime(true);
print $fileIoCrc32->getCrc32FromFile($filename)."\n";
$time_end = microtime(true);
$time = $time_end - $time_start;
print "getCrc32FromFile ".$time."\n";


?>

//<?php
//   $GLOBALS['__crc32_table']=array();        // Lookup table array
//   __crc32_init_table();
//
//   function __crc32_init_table() {            // Builds lookup table array
//       // This is the official polynomial used by
//       // CRC-32 in PKZip, WinZip and Ethernet.
//       $polynomial = 0x04c11db7;
//
//       // 256 values representing ASCII character codes.
//       for($i=0;$i <= 0xFF;++$i) {
//           $GLOBALS['__crc32_table'][$i]=(__crc32_reflect($i,8) << 24);
//           for($j=0;$j < 8;++$j) {
//               $GLOBALS['__crc32_table'][$i]=(($GLOBALS['__crc32_table'][$i] << 1) ^
//                   (($GLOBALS['__crc32_table'][$i] & (1 << 31))?$polynomial:0));
//           }
//           $GLOBALS['__crc32_table'][$i] = __crc32_reflect($GLOBALS['__crc32_table'][$i], 32);
//       }
//   }
//
//   function __crc32_reflect($ref, $ch) {        // Reflects CRC bits in the lookup table
//       $value=0;
//      
//       // Swap bit 0 for bit 7, bit 1 for bit 6, etc.
//       for($i=1;$i<($ch+1);++$i) {
//           if($ref & 1) $value |= (1 << ($ch-$i));
//           $ref = (($ref >> 1) & 0x7fffffff);
//       }
//       return $value;
//   }
//
//   function __crc32_string($text) {        // Creates a CRC from a text string
//       // Once the lookup table has been filled in by the two functions above,
//       // this function creates all CRCs using only the lookup table.
//
//       // You need unsigned variables because negative values
//       // introduce high bits where zero bits are required.
//       // PHP doesn't have unsigned integers:
//       // I've solved this problem by doing a '&' after a '>>'.
//
//       // Start out with all bits set high.
//       $crc=0xffffffff;
//       $len=strlen($text);
//
//       // Perform the algorithm on each character in the string,
//       // using the lookup table values.
//       for($i=0;$i < $len;++$i) {
//           $crc=(($crc >> 8) & 0x00ffffff) ^ $GLOBALS['__crc32_table'][($crc & 0xFF) ^ ord($text{$i})];
//       }
//      
//       // Exclusive OR the result with the beginning value.
//       return $crc ^ 0xffffffff;
//   }
//  
//   function __crc32_file($name) {            // Creates a CRC from a file
//       // Info: look at __crc32_string
//
//       // Start out with all bits set high.
//       $crc=0xffffffff;
//
//       if(($fp=fopen($name,'rb'))===false) return false;
//
//       // Perform the algorithm on each character in file
//       for(;;) {
//           $i=@fread($fp,1);
//           if(strlen($i)==0) break;
//           $crc=(($crc >> 8) & 0x00ffffff) ^ $GLOBALS['__crc32_table'][($crc & 0xFF) ^ ord($i)];
//       }
//      
//       @fclose($fp);
//      
//       // Exclusive OR the result with the beginning value.
//       return dechex($crc ^ 0xffffffff);
//   }
//
//chdir(dirname(__FILE__));
//print __crc32_file('11 Sprite Demo (Piero Cavina) (PD).a26')."\n";
//print str_pad (strtoupper(dechex(crc32(file_get_contents('11 Sprite Demo (Piero Cavina) (PD).a26')))), 8, '0', STR_PAD_LEFT);
//  
//?>


