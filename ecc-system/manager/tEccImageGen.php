<?
error_reporting(E_ALL|E_STRICT);
define('LF', "\n");

$possibleArgs = array('source filepath (absolute)', 'color (#hex)', 'amount (percent)', 'destination filename (absolute)', 'greyscale first (optional) [0|1]');
if(count($argv) < 5) {
	echo LF;
	echo str_repeat('-', 80).LF;
	echo 'Commandline paramenter:'.LF;
	echo str_repeat('-', 80).LF;
	foreach($possibleArgs as $position => $name) echo 'Position: '.($position+1).' - '.$name.LF;
	echo str_repeat('-', 80).LF;
	echo 'Syntax: tEccImageGen.php fullpath\source.png #CC0000 25 fullpath\destintation.png'.LF;
	echo str_repeat('-', 80).LF;
	exit();
}

// parameter source filename
$source = (isset($argv[1]) && file_exists($argv[1])) ? $argv[1] : false;
if(!$source) exit('Error: '.$possibleArgs[0].' - file not exists "'.$argv[1].'"');
	
// parameter target color (hex color e.g. #AABBCC)
$targetColor = (isset($argv[2]) && $argv[2][0] == '#') ? $argv[2] : false;
if(!$targetColor) exit('Error: '.$possibleArgs[1].' - missing # "'.$argv[2].'"');
	
$amount = (isset($argv[3])) ? (int)$argv[3] : false;
if(!$amount) exit('Error: '.$possibleArgs[1].' - missing amount (percent) "'.$argv[2].'"');

// parameter destination filename
$destination = (isset($argv[4]) && is_dir(dirname($argv[4]))) ? $argv[4] : false;
if(!$destination) exit('Error: '.$possibleArgs[3].' - dir not exists "'.$argv[4].'"');

// use greyscale first or not?
$useGreyscale = (isset($argv[5])) ? (boolean)$argv[5] : false;

$gdimg = imagecreatefrompng($source);

// first create grayscale image
if($useGreyscale) ImageFilter($gdimg, IMG_FILTER_GRAYSCALE);

// create colorized image
$amountPct   = $amount / 100;
$targetColor = substr($targetColor, 1);
$TargetPixel['red']   = hexdec(substr($targetColor, 0, 2));
$TargetPixel['green'] = hexdec(substr($targetColor, 2, 2));
$TargetPixel['blue']  = hexdec(substr($targetColor, 4, 2));
for ($x = 0; $x < ImageSX($gdimg); $x++) {
	for ($y = 0; $y < ImageSY($gdimg); $y++) {
		$OriginalPixel = ImageColorsForIndex($gdimg, imageColorAt($gdimg, $x, $y));
		foreach ($TargetPixel as $key => $value) $NewPixel[$key] = round(max(0, min(255, ($OriginalPixel[$key] * ((100 - $amount) / 100)) + ($TargetPixel[$key] * $amountPct))));
		$newColor = ImageColorAllocateAlpha($gdimg, $NewPixel['red'], $NewPixel['green'], $NewPixel['blue'], $OriginalPixel['alpha']);
		ImageSetPixel($gdimg, $x, $y, $newColor);
	}
}

// store the image
imagesavealpha($gdimg, true);
imagepng($gdimg, $destination);
imagedestroy($gdimg);


?>