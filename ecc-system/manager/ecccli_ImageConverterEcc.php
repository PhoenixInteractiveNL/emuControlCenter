<?
define('LF', "\n");
$eccToolsDir = dirname(__FILE__);

chdir(dirname(__FILE__).'/../');
require_once('manager/cFactory.php');

/**
 * image convert!!!!
 */
$imageManager = FACTORY::get('manager/Image');
$imageManager->setSupportedExtensions(FACTORY::get('manager/Validator')->getEccCoreKey('supported_images'));
$imageManager->setEccImageTypes(FACTORY::get('manager/Validator')->getEccCoreKey('image_type'));
$imageManager->setThumbQuality(90);

$navigation = FACTORY::get('manager/IniFile')->getPlatformNavigation(false, false, true);

$hdl = fopen('../ecc-tools/convertUserImages.txt', 'a+');

foreach ($navigation as $eccident => $platformName) {
	
	if ($eccident == 'NULL') continue;
	
	$out  = str_repeat('.', 80).LF;
	$out .= "- '".$eccident."' $platformName - processing images".LF;
	$out .= str_repeat('^', 80).LF;
	
	$count = $imageManager->convertOldEccImages($eccident);
		
	$out .= LF;
	$out .= str_repeat('.', 80).LF;
	$out .= "- '".$eccident."' images processed: ".$count.LF;
	$out .= str_repeat('^', 80).LF;
	
	print $out;
	fwrite($hdl, $out);
}

$footer  = LF;
$footer .= str_repeat('#', 80).LF;
$footer .= "ALL JOBS DONE!!!! ".LF;
$footer .= str_repeat('#', 80).LF;

fwrite($hdl, $out.$footer);

fclose($hdl);
die;
?>
