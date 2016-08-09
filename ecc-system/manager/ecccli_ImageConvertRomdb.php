<?
chdir(dirname(__FILE__).'../../');
require_once('manager/cFactory.php');
require_once('manager/cValid.php');

// TEST ONLY!	
// CONVERTER FOR IMAGES FROM NO-INTRO!!!	
$imgConvert = FACTORY::get('manager/ImageConvertRomdb');
$imagePathIn = 'D:/Development_emuControlCenter/emuControlCenterImages/ecc_nes_imgpack_png/test';
$imagePathOut = 'D:/Development_emuControlCenter/emuControlCenterImages/romdb/';
$imgConvert->setSourceFolder($imagePathIn);
$imgConvert->setDestinationFolder($imagePathOut);
$eccImageName = $imgConvert->covertImages();
print "<pre>";
print_r($eccImageName);
print "</pre>";
die;
// TEST ONLY!
?>
