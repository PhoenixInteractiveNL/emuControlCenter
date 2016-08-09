; ------------------------------------------------------------------------------
; Script for             : Allround ECC variables for ECC tool scripts
; Script version         : v1.0.0.1
; Last changed           : 2014.03.29
;
; Author: Sebastiaan Ebeltjes (AKA Phoenix)
;
; NOTES:
;
; None yet! ;-)
;
; ------------------------------------------------------------------------------
FileChangeDir(@ScriptDir)

; AU3 INCLUDES
#include "..\thirdparty\autoit\include\GuiEdit.au3"
#include "..\thirdparty\autoit\include\GuiRichEdit.au3"
#include "..\thirdparty\autoit\include\ScrollBarConstants.au3"
#include "..\thirdparty\autoit\include\ButtonConstants.au3"
#include "..\thirdparty\autoit\include\EditConstants.au3"
#include "..\thirdparty\autoit\include\GUIConstantsEx.au3"
#include "..\thirdparty\autoit\include\GUIListBox.au3"
#include "..\thirdparty\autoit\include\GuiListView.au3"
#include "..\thirdparty\autoit\include\StaticConstants.au3"
#include "..\thirdparty\autoit\include\WindowsConstants.au3"
#include "..\thirdparty\autoit\include\ProgressConstants.au3"
#include "..\thirdparty\autoit\include\ComboConstants.au3"
#include "..\thirdparty\autoit\include\File.au3"
#include "..\thirdparty\autoit\include\String.au3"
#include "..\thirdparty\autoit\include\URLStrings.au3"
#include "..\thirdparty\autoit\include\XMLDomWrapper.au3"
#include "..\thirdparty\autoit\include\VLCPlayer.au3"
#include "..\thirdparty\autoit\include\IE.au3"
#include "..\thirdparty\autoit\include\GetCRC32.au3"

; Global FILE variables
Global $eccInstallPath 		= StringReplace(@Scriptdir, "\ecc-core\tools", "")
Global $7zExe 				= $eccInstallPath & "\ecc-core\thirdparty\7zip\7z.exe"
Global $AutoIt3Exe 			= $eccInstallPath & "\ecc-core\thirdparty\autoit\autoit3.exe"
Global $eccExe 				= $eccInstallPath & "\ecc.exe"
Global $NotepadExe 			= $eccInstallPath & "\ecc-core\thirdparty\notepad++\notepad++.exe"
Global $DATUtilExe 			= $eccInstallPath & "\ecc-core\thirdparty\datutil\datutil.exe"
Global $XpadderExe			= $eccInstallPath & "\ecc-core\thirdparty\xpadder\xpadder.exe"
Global $KodaExe				= $eccInstallPath & "\ecc-core\thirdparty\koda\fd.exe"
Global $StripperExe 		= $eccInstallPath & "\ecc-core\thirdparty\stripper\stripper.exe"
Global $SQliteExe 			= $eccInstallPath & "\ecc-core\thirdparty\sqlite\sqlite.exe"
Global $eccDataBaseFile 	= $eccInstallPath & "\ecc-system\database\eccdb"
Global $SQLInstructionFile 	= @Scriptdir & "\sqlcommands.inst"
Global $SQLcommandFile 		= @Scriptdir & "\sqlcommands.cmd"
Global $PlatformDataFile 	= "platformdata.txt"

; Global INFO variables
; version info
Global $eccLocalVersionIni 	= $eccInstallPath & "\ecc-system\system\info\ecc_local_version_info.ini"
Global $eccCurrentVersion 	= Iniread($eccLocalVersionIni, "GENERAL", "current_version", "")
Global $eccCurrentDateBuild = Iniread($eccLocalVersionIni, "GENERAL", "date_build", "")
Global $eccCurrentBuild 	= Iniread($eccLocalVersionIni, "GENERAL", "current_build", "")
; update info
Global $eccLocalUpdateIni 	= $eccInstallPath & "\ecc-system\system\info\ecc_local_update_info.ini"
Global $eccLocalLastUpdate 	= Iniread($eccLocalUpdateIni, "UPDATE", "last_update", "")
Global $eccDatfileInfoIni 	= $eccInstallPath & "\ecc-system\system\info\ecc_local_datfile_info.ini"
Global $eccVersionInfoIni 	= $eccInstallPath & "\ecc-system\system\info\ecc_local_version_info.ini"
Global $eccHostInfoIni 		= $eccInstallPath & "\ecc-system\system\info\ecc_local_host_info.ini"
; language info
Global $eccConfigFileGeneralUser = $eccInstallPath & "\ecc-user-configs\config\ecc_general.ini"
Global $eccLanguageCurrent 	= IniRead($eccConfigFileGeneralUser, "USER_DATA", "language", "")
Global $ThirdPartyConfigIni = @Scriptdir & "\eccThirdPartyConfig.ini"
Global $eccLanguageSaved 	= IniRead($ThirdPartyConfigIni, "ECC", "language", "")
;Determine USER folder (set in ECC config)
Global $EccUserPathTemp 	= StringReplace(Iniread($eccConfigFileGeneralUser, "USER_DATA", "base_path", ""), "/", "\")
Global $EccUserPath 		= StringReplace($EccUserPathTemp, "..\", $eccInstallPath & "\") ;Add full path to variable if it's an directory within the ECC structure
; userdata
Global $eccUserCidFile 		= $eccInstallPath & "\ecc-system\idt\cicheck.idt"
Global $eccUserPathTemp 	= StringReplace(IniRead($eccConfigFileGeneralUser, "USER_DATA", "base_path", ""), "/", "\")
Global $eccUserPath 		= StringReplace($eccUserPathTemp, "..\", $eccInstallPath & "\") ; Add full path to variable if it's an directory within the ECC structure


; ECC SELECTED ROM variables
Global $eccRomDataFile 		= $eccInstallPath & "\ecc-system\selectedrom.ini"
Global $RomName 			= IniRead($eccRomDataFile, "ROMDATA", "rom_name", "")
Global $RomCrc32 			= IniRead($eccRomDataFile, "ROMDATA", "rom_crc32", "")
Global $RomCrc32short 		= StringLeft($RomCrc32, 2)
Global $RomEccId 			= IniRead($eccRomDataFile, "ROMDATA", "rom_platformid", "")
Global $RomPlatformName 	= IniRead($EccRomDataFile, "ROMDATA", "rom_platformname", "")
Global $RomMetaData 		= IniRead($EccRomDataFile, "ROMDATA", "rom_meta_data", "")
Global $RomUserData 		= IniRead($EccRomDataFile, "ROMDATA", "rom_user_data", "")
Global $eccImageFolder		= $eccUserPath & $RomEccId & "\images"

; ECC CREATE SHORTCUT variables
Global $StartFolderName 	= "emuControlCenter"

; ECC update variables
Global $eccUpdateLogFile 	= @Scriptdir & "\eccUpdate.log"
Global $UpdateServer 		= "http://eccupdate.phoenixinteractive.nl/" ; don't forget the / on the end!

; ECC GALLERY variables
Global $GalleryImageUrl 	= "http://ecc.phoenixinteractive.nl"
Global $FullPathToImageFolder = $eccInstallPath & "\ecc-user\" & $RomEccId & "\images\" & $RomCrc32short & "\" & $RomCrc32 & "\"

; ECC EMD variables
Global $eccSig 				= "3AC741E3A76D7BD5B31256A1B67A7D6A238D" ;Please do NOT alter!
Global $EmuMoviesServer 	= "http://api.gamesdbase.com/"
Global $EmuMoviesWebsite 	= "http://emumovies.com/"
Global $EmuMoviesList 		= @ScriptDir & "\emuMoviesDownloader.list"

; ECC GET CRC32 variables
Global $CRCfile 			= @ScriptDir & "\getCRC32.dat"

; ECC THEME SELECT variables
Global $eccThemeFolder 		= $eccInstallPath  & "\ecc-core\php-gtk2\share\themes\"
Global $NoPreviewImage 		= $eccInstallPath  & "\ecc-core\tools\gtkThemeSelect_nopreview.jpg"
Global $GtkThemeFile 		= $eccInstallPath  & "\ecc-core\php-gtk2\etc\gtk-2.0\theme"
Global $GtkEngineFolder 	= $eccInstallPath  & "\ecc-core\php-gtk2\lib\gtk-2.0\2.10.0\engines\"

; ECC IMAGEINJECT variables
Global $ICCServerUrl 		= "http://icc.phoenixinteractive.nl/"
Global $ImagesINI 			= "images.ini"
Global $PlatFormImagesFile 	= "platformimages.txt"

; ECC KAMELEON CODE variables
Global $EccKameleonCode 	= Iniread($eccInstallPath  & "\ecc-core\tools\eccKameleonCode.code", "kameleon", "code", "NOT FOUND")
Global $EccKameleon 		= $eccInstallPath  & "\ecc-core\tools\eccKameleonCode.au3"

;;MOBY GAMES IMPORTER variables
Global $MobyGamesWebsite 	= "http://www.mobygames.com/"
Global $MobyGamesList 		= @ScriptDir & "\MobyGamesImporter.list"
Global $PlatformDataFileRomList = "platformdata_romlist.txt"
Global $PlatformDataFileRomMeta = "platformdata_meta.txt"
Global $PlatformDataFileRomUser = "platformdata_user.txt"

; IMAGEPACK CREATOR variables

Global $IpcPresetFolder 	= @ScriptDir & "\eccImagePackCenter_presets"
Global $AutoSavedIpcFile 	= @ScriptDir & "\eccImagePackCenter.ipc"