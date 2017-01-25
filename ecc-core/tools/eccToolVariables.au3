; ------------------------------------------------------------------------------
; Script for             : Allround ECC variables for ECC tool scripts
; Script version         : v1.0.2.0
; Last changed           : 2016.12.19
;
; Author: Sebastiaan Ebeltjes (AKA Phoenix)
;
; NOTES:
;
; None yet! ;-)
;
; ------------------------------------------------------------------------------
FileChangeDir(@ScriptDir)

; Autoit includes
#include "..\thirdparty\autoit\include\GuiEdit.au3"
#include "..\thirdparty\autoit\include\GuiRichEdit.au3"
#include "..\thirdparty\autoit\include\ScrollBarConstants.au3"
#include "..\thirdparty\autoit\include\ButtonConstants.au3"
#include "..\thirdparty\autoit\include\Constants.au3"
#include "..\thirdparty\autoit\include\ColorConstants.au3"
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
#include "..\thirdparty\autoit\include\IE.au3"
#include "..\thirdparty\autoit\include\GetCRC32.au3"
#include "..\thirdparty\autoit\include\Crypt.au3"
#include "..\thirdparty\autoit\include\Misc.au3"

; WEB variables
Global $eccWebsite 					= "https://github.com/PhoenixInteractiveNL/emuControlCenter/wiki"
Global $eccForum 					= "http://eccforum.phoenixinteractive.nl"
Global $eccDownloadLatest			= "https://phoenixinteractivenl.github.io/emuControlCenter/"
Global $eccDownloadTaggedReleases 	= "https://github.com/PhoenixInteractiveNL/emuControlCenter/releases"

; Global FILE locations
Global $eccInstallPath 		= StringReplace(@Scriptdir, "\ecc-core\tools", "")
Global $eccExe 				= $eccInstallPath & "\ecc.exe"
Global $7zExe 				= $eccInstallPath & "\ecc-core\thirdparty\7zip\7z.exe"
Global $AutoIt3Exe 			= $eccInstallPath & "\ecc-core\thirdparty\autoit\autoit3.exe"
Global $NotepadExe 			= $eccInstallPath & "\ecc-core\thirdparty\notepad++\notepad++.exe"
Global $DATUtilExe 			= $eccInstallPath & "\ecc-core\thirdparty\datutil\datutil.exe"
Global $KodaExe				= $eccInstallPath & "\ecc-core\thirdparty\koda\fd.exe"
Global $StripperExe 		= $eccInstallPath & "\ecc-core\thirdparty\stripper\stripper.exe"
Global $SQliteExe 			= $eccInstallPath & "\ecc-core\thirdparty\sqlite\sqlite.exe"
Global $VideoPlayerExe		= $eccInstallPath & "\ecc-core\thirdparty\mplayer\mplayer.exe"
Global $SQLInstructionFile 	= @Scriptdir & "\sqlcommands.inst"
Global $SQLcommandFile 		= @Scriptdir & "\sqlcommands.cmd"
Global $PlatformDataFile 	= "platformdata.txt"

; ECC INI FILE locations
Global $eccConfigFileGeneralUser = $eccInstallPath & "\ecc-user-configs\config\ecc_general.ini"
Global $eccLocalVersionIni 	= $eccInstallPath & "\ecc-system\system\info\ecc_local_version_info.ini"
Global $eccLocalUpdateIni 	= $eccInstallPath & "\ecc-system\system\info\ecc_local_update_info.ini"
Global $eccDatfileInfoIni 	= $eccInstallPath & "\ecc-system\system\info\ecc_local_datfile_info.ini"
Global $eccVersionInfoIni 	= $eccInstallPath & "\ecc-system\system\info\ecc_local_version_info.ini"
Global $eccHostInfoIni 		= $eccInstallPath & "\ecc-system\system\info\ecc_local_host_info.ini"

; ECC settings
Global $eccDataBasePath 	= Iniread($eccConfigFileGeneralUser, "USER_DATA", "database_path", $eccInstallPath & "\ecc-system\database\")
If $eccDataBasePath = "database/" Then $eccDataBasePath = $eccInstallPath & "\ecc-system\database\" ; Tools need the full path!
Global $eccDataBaseFile 	= $eccDataBasePath & "eccdb"
Global $DaemonToolsExe		= Iniread($eccConfigFileGeneralUser, "DAEMONTOOLS", "daemontools_exe", "")
Global $JoyEmulatorExe		= Iniread($eccConfigFileGeneralUser, "USER_DATA", "joyemulator_exe", "")

; Global INFO variables
; version info
Global $eccCurrentVersion 	= Iniread($eccLocalVersionIni, "GENERAL", "current_version", "x.x")
Global $eccCurrentDateBuild = Iniread($eccLocalVersionIni, "GENERAL", "date_build", "xxxx.xx.xx")
Global $eccCurrentBuild 	= Iniread($eccLocalVersionIni, "GENERAL", "current_build", "xxx")
; update info
Global $eccLocalLastUpdate 	= Iniread($eccLocalUpdateIni, "UPDATE", "last_update", "xxxxx")
; language info
Global $eccLanguageCurrent 	= IniRead($eccConfigFileGeneralUser, "USER_DATA", "language", "")
Global $ThirdPartyConfigIni = @Scriptdir & "\eccThirdPartyConfig.ini"
Global $eccLanguageSaved 	= IniRead($ThirdPartyConfigIni, "ECC", "language", "")
;Determine USER folder (set in ECC config)
Global $EccUserPathTemp 	= StringReplace(Iniread($eccConfigFileGeneralUser, "USER_DATA", "base_path", ""), "/", "\")
Global $EccUserPath 		= StringReplace($EccUserPathTemp, "..\", $eccInstallPath & "\") ;Add full path to variable if it's an directory within the ECC structure
; Userdata
$UIDdata					= @ScriptDir & @ComputerName & @UserName & @CPUArch & @OSArch & @OSType & @OSVersion & @OSBuild
$UIDuser					= GetAES($UIDdata)
Global $eccUserPathTemp 	= StringReplace(IniRead($eccConfigFileGeneralUser, "USER_DATA", "base_path", ""), "/", "\")
Global $eccUserPath 		= StringReplace($eccUserPathTemp, "..\", $eccInstallPath & "\") ; Add full path to variable if it's an directory within the ECC structure
; ECC window title
Global $eccGeneratedTitle 	=	"emuControlCenter" & " v" & $eccCurrentVersion & " build:" & $eccCurrentBuild & " (" & $eccCurrentDateBuild & ")" & " upd:" & $eccLocalLastUpdate

; ECC SELECTED ROM variables
Global $eccRomDataFile 		= $eccInstallPath & "\ecc-system\selectedrom.ini"
Global $RomName 			= IniRead($eccRomDataFile, "ROMDATA", "rom_name", "")
Global $RomFileNamePlain	= IniRead($eccRomDataFile, "ROMDATA", "rom_filename_plain", "")
Global $RomCrc32 			= IniRead($eccRomDataFile, "ROMDATA", "rom_crc32", "")
Global $RomCrc32short 		= StringLeft($RomCrc32, 2)
Global $RomEccId 			= IniRead($eccRomDataFile, "ROMDATA", "rom_platformid", "")
Global $RomPlatformName 	= IniRead($EccRomDataFile, "ROMDATA", "rom_platformname", "")
Global $RomMetaData 		= IniRead($EccRomDataFile, "ROMDATA", "rom_meta_data", "")
Global $RomUserData 		= IniRead($EccRomDataFile, "ROMDATA", "rom_user_data", "")
Global $eccImageFolder		= $eccUserPath & $RomEccId & "\images"

; emuDownloadCenter
Global $EDCServer 			= "https://raw.githubusercontent.com/PhoenixInteractiveNL/edc-masterhook/master/"
Global $EDCServerEmulatorList= $EDCServer & "ecc_platform_emulators.ini"
Global $EDCServerStatistics	= $EDCServer & "edc_statistics.ini"
Global $EDCWindowTitle		= "ECC - emuDownloadCenter"
Global $EDCWebsitelink		= "https://github.com/PhoenixInteractiveNL/emuDownloadCenter/wiki"
Global $EDCFolder 			= @Scriptdir & "\emuDownloadCenter\"
Global $EDCSettingsINI		= $EDCFolder & "settings.ini"
Global $EDCFavoriteINI		= $EDCFolder & "favorites.ini"
Global $EDCFolderCache		= $EDCFolder & "cache\"
Global $EDCEmulatorListINI	= $EDCFolderCache & "ecc_platform_emulators.ini"
Global $EDCEmulatorDownloadsINI = $EDCFolderCache & "emulator_downloads.ini"
Global $EDCEmulatorConfigINI = $EDCFolderCache & "configs_frontend_ecc.ini"
Global $EDCStatisticsINI	= $EDCFolderCache & "edc_statistics.ini"

; ECC CREATE SHORTCUT variables
Global $StartFolderName 	= "emuControlCenter"

; ECC update variables
Global $eccUpdateLogFile 	= @Scriptdir & "\eccUpdate.log"
Global $UpdateServer 		= "https://raw.githubusercontent.com/PhoenixInteractiveNL/ecc-updates/master/" ; don't forget the / on the end!

; ECC GALLERY variables
Global $FullPathToImageFolder = $eccInstallPath & "\ecc-user\" & $RomEccId & "\images\" & $RomCrc32short & "\" & $RomCrc32 & "\"

; ECC EMD variables
Global $eccSig 				= "3AC741E3A76D7BD5B31256A1B67A7D6A238D" ;Please do NOT alter!
Global $EmuMoviesServer 	= "https://api.gamesdbase.com/"
Global $EmuMoviesWebsite 	= "http://emumovies.com/"
Global $EmuMoviesList 		= @ScriptDir & "\emuMoviesDownloader.list"
Global $EmuMovieshKey		= "emuControlCenter"

; ECC MobyGames Importer (MGI)
Global $MGIConfigFile		= @Scriptdir & "\MobyGamesImporter.ini"
Global $FileNameFlag 		= IniRead($MGIConfigFile, "SETTINGS", "FileNameFlag", "1")
Global $NameFlag 			= IniRead($MGIConfigFile, "SETTINGS", "NameFlag", "1")
Global $YearFlag 			= IniRead($MGIConfigFile, "SETTINGS", "YearFlag", "1")
Global $DeveloperFlag 		= IniRead($MGIConfigFile, "SETTINGS", "DeveloperFlag", "1")
Global $PublisherFlag 		= IniRead($MGIConfigFile, "SETTINGS", "PublisherFlag", "1")
Global $ReviewFlag 			= IniRead($MGIConfigFile, "SETTINGS", "ReviewFlag", "1")
Global $MGFooterTag			= "[--- Info from MobyGames.com ---]"

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

; VIDEOPLAYER (MPLAYER) variables
Global $VideoWindowTitle	= "The Movie Player"

Func GetAES($StringtoAES)
$bHash = _Crypt_EncryptData($StringtoAES, "", $CALG_AES_128)
Return StringUpper(StringTrimLeft($bHash, 2)) ;removes the 0x
EndFunc ;GetMD5