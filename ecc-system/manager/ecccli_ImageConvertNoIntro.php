<?
chdir(dirname(__FILE__).'../../');
require_once('manager/cFactory.php');
require_once('manager/cValid.php');

// TEST ONLY!	
// CONVERTER FOR IMAGES FROM NO-INTRO!!!	
$imgConvert = FACTORY::get('manager/ImageConvertNoIntro');
$imagePathIn = 'D:/Development_emuControlCenter/incomming/no-intro/gg/';
$imagePathOut = 'D:/Development_emuControlCenter/incomming/no-intro/gg/emuControlCenter_images/';
$imgConvert->setSourceFolder($imagePathIn);
$imgConvert->setDestinationFolder($imagePathOut);
$eccImageName = $imgConvert->covertImages();
print "<pre>";
print_r($eccImageName);
print "</pre>";
die;
// TEST ONLY!
?>
