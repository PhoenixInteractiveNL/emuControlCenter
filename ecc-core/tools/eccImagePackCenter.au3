; ------------------------------------------------------------------------------
; emuControlCenter ImagePackCenter (IPC)
;
; Script version         : v2.2.0.8
; Last changed           : 2012.11.19
;
; Author: Sebastiaan Ebeltjes (aka Phoenix)
; Code contributions:
;
; NOTES: Nothing yet ;-)
;
; ------------------------------------------------------------------------------
FileChangeDir(@ScriptDir)

#include "..\thirdparty\autoit\include\ButtonConstants.au3"
#include "..\thirdparty\autoit\include\EditConstants.au3"
#include "..\thirdparty\autoit\include\GUIConstantsEx.au3"
#include "..\thirdparty\autoit\include\ProgressConstants.au3"
#include "..\thirdparty\autoit\include\StaticConstants.au3"
#include "..\thirdparty\autoit\include\WindowsConstants.au3"
#include "..\thirdparty\autoit\include\ComboConstants.au3"

; Define variables
Global $EccInstallFolder = StringReplace(@Scriptdir, "\ecc-core\tools", "")
Global $7zexe = $EccInstallFolder & "\ecc-core\thirdparty\7zip\7z.exe"
Global $IpcPresetFolder = @ScriptDir & "\eccImagePackCenter_presets"
Global $AutoSavedIpcFile = @ScriptDir & "\" & "eccImagePackCenter.ipc"

; Define variables for ECC database access
Global $StripperExe = $EccInstallFolder & "\ecc-core\thirdparty\stripper\stripper.exe"
Global $EccDataBaseFile = $EccInstallFolder & "\ecc-system\database\eccdb"
Global $SQliteExe = $EccInstallFolder & "\ecc-core\thirdparty\sqlite\sqlite.exe"
Global $SQLInstructionFile = @Scriptdir & "\sqlcommands.inst"
Global $SQLCommandFile = @Scriptdir & "\sqlcommands.cmd"
Global $PlatFormImagesFile = "platformdata.txt"

;Determine USER folder (set in ECC config)
Global $EccGeneralIni = $EccInstallFolder & "\ecc-user-configs\config\ecc_general.ini"
Global $EccUserPathTemp = StringReplace(IniRead($EccGeneralIni, "USER_DATA", "base_path", ""), "/", "\")
Global $EccUserPath = StringReplace($EccUserPathTemp, "..\", $EccInstallFolder & "\") ; Add full path to variable if it's an directory within the ECC structure

; Read in ECC dumped values of the platform
Global $EccPlatformIni	= $EccInstallFolder & "\ecc-system\selectedrom.ini"
Global $PlatformId		= IniRead($EccPlatformIni, "ROMDATA", "rom_platformid", "") ; %PLATFORMID% variable
Global $PlatformName	= IniRead($EccPlatformIni, "ROMDATA", "rom_platformname", "") ; %PLATFORMNAME% variable
Global $EccImageFolder	= $EccUserPath & $PlatformId & "\images"
Global $ProcessResult, $IpcSettingsFile, $AutoSave, $AutoLoad, $RomListRetrieved, $TrueFileName, $AbortProcess, $ImagesProcessed = 0
Global $SelectedFolder, $SelectedRCFile

; Exit if user wants to create an imagepack from the ECC menu "ALL PLATFORMS", this is not possible, $PlatformId = ""
If $PlatformId = "" Then
	ToolTip("You cannot use this function for ALL platforms, please select a single platform!", @DesktopWidth/2, @DesktopHeight/2, "eccImagePackCenter", 1, 6)
	Sleep(1500)
	Exit
EndIf

Select

	Case $CmdLine[0] = 0
		Exit

	Case $CmdLine[1] = "export"
		StartExport()
		Exit

	Case $CmdLine[1] = "import"
		StartImport()
		Exit

EndSelect
Exit

Func StartExport()
;==============================================================================
;BEGIN *** GUI
;==============================================================================
Global $ECCIPCEXPORT = GUICreate("ECC ImagePackCenter [EXPORT]", 1070, 808, -1, -1)
GUISetBkColor(0xFFFFFF)
Global $Label1 = GUICtrlCreateLabel("Processing:", 8, 488, 84, 15)
GUICtrlSetFont(-1, 8, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x000000)
Global $ProcessingLabel = GUICtrlCreateLabel("-", 96, 488, 332, 15)
GUICtrlSetFont(-1, 8, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x000080)
Global $ButtonExport = GUICtrlCreateButton("CREATE IMAGEPACK!", 320, 440, 107, 41, $BS_MULTILINE)
GUICtrlSetFont(-1, 9, 800, 2, "Verdana")
GUICtrlSetTip(-1, "Start creating imagepacks.")
Global $Label3 = GUICtrlCreateLabel("Platform:", 8, 8, 60, 15)
GUICtrlSetFont(-1, 8, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x000000)
Global $Label4 = GUICtrlCreateLabel("ECCid:", 344, 8, 44, 15)
GUICtrlSetFont(-1, 8, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x000000)
Global $PlatformLabel = GUICtrlCreateLabel("-", 72, 8, 268, 15)
GUICtrlSetFont(-1, 8, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x000080)
Global $eccidLabel = GUICtrlCreateLabel("-", 384, 8, 44, 15, $SS_RIGHT)
GUICtrlSetFont(-1, 8, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x000080)
Global $Group1 = GUICtrlCreateGroup(" LOAD A PRESET DIRECTLY FROM FILE ", 8, 24, 425, 289)
GUICtrlSetFont(-1, 8, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x000080)
Global $ExportStyleECC = GUICtrlCreateRadio("emuControlCenter (FULL, processing all images)", 16, 48, 329, 17)
GUICtrlSetFont(-1, 8, 800, 2, "Verdana")
GUICtrlSetColor(-1, 0x800000)
Global $Label2 = GUICtrlCreateLabel("Export your platform images as several 7z imagepacks with ECC structure:", 32, 64, 393, 33)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $ExportStyleNointro = GUICtrlCreateRadio("No-Intro (only ingame titles + play01)", 16, 128, 273, 17)
GUICtrlSetFont(-1, 8, 800, 2, "Verdana")
GUICtrlSetColor(-1, 0x800000)
Global $Label5 = GUICtrlCreateLabel("Export your images in the No-Intro style, creating 2 folders with 'title' and 'ingame' snapshots:", 32, 144, 393, 33)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $Label6 = GUICtrlCreateLabel("CRC_Snaps\[CRC32].[EXT]", 32, 192, 393, 17)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $Label7 = GUICtrlCreateLabel("CRC_Titles\[CRC32].[EXT]", 32, 176, 393, 17)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $Label8 = GUICtrlCreateLabel("[PLATFORMID]\images\[CRC32SHORT]\[CRC32]\[ORIGINALFILE]", 32, 96, 393, 17)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $ExportStyleEmumovies = GUICtrlCreateRadio("EmuMovies (DB Only, only processing images in ECC DB)", 16, 224, 385, 17)
GUICtrlSetFont(-1, 8, 800, 2, "Verdana")
GUICtrlSetColor(-1, 0x800000)
Global $Label10 = GUICtrlCreateLabel("Export your images in the EmuMovies style, creating several folders with snapshots:", 32, 240, 393, 33)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $Label11 = GUICtrlCreateLabel("[PLATFORMNAME][Snaps]\[ROMFILENAME].[EXT]", 32, 272, 393, 17)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $Label12 = GUICtrlCreateLabel("[PLATFORMNAME][Titles]\[ROMFILENAME].[EXT]", 32, 288, 393, 17)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $ExportStyleNointroPlus = GUICtrlCreateRadio("No-Intro+", 304, 128, 89, 17)
GUICtrlSetFont(-1, 8, 800, 2, "Verdana")
GUICtrlSetColor(-1, 0x800000)
GUICtrlCreateGroup("", -99, -99, 1, 1)
Global $Group2 = GUICtrlCreateGroup(" Set variables for export", 440, 0, 625, 801)
GUICtrlSetFont(-1, 8, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x000080)
Global $Label13 = GUICtrlCreateLabel("ingame_play_03", 448, 120, 98, 17)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $ingame_title = GUICtrlCreateInput("", 560, 48, 121, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $Label14 = GUICtrlCreateLabel("ingame_play_01", 448, 72, 98, 17)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $ingame_play_01 = GUICtrlCreateInput("", 560, 72, 121, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $Label15 = GUICtrlCreateLabel("ingame_play_02", 448, 96, 98, 17)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $ingame_play_02 = GUICtrlCreateInput("", 560, 96, 121, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $Label16 = GUICtrlCreateLabel("ingame_title", 448, 48, 74, 17)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $ingame_play_03 = GUICtrlCreateInput("", 560, 120, 121, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $cover_back = GUICtrlCreateInput("", 560, 216, 121, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $Label17 = GUICtrlCreateLabel("cover_back", 448, 216, 70, 17)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $Label18 = GUICtrlCreateLabel("cover_front", 448, 192, 70, 17)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $cover_front = GUICtrlCreateInput("", 560, 192, 121, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $ingame_loading = GUICtrlCreateInput("", 560, 168, 121, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $Label19 = GUICtrlCreateLabel("ingame_play_boss", 448, 144, 110, 17)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $ingame_play_boss = GUICtrlCreateInput("", 560, 144, 121, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $Label20 = GUICtrlCreateLabel("ingame_loading", 448, 168, 94, 17)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $media_stor_03 = GUICtrlCreateInput("", 560, 384, 121, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $media_stor_04 = GUICtrlCreateInput("", 560, 408, 121, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $Label21 = GUICtrlCreateLabel("media_stor_04", 448, 408, 89, 17)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $Label22 = GUICtrlCreateLabel("media_stor_03", 448, 384, 89, 17)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $media_stor_02 = GUICtrlCreateInput("", 560, 360, 121, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $Label23 = GUICtrlCreateLabel("media_stor_02", 448, 360, 89, 17)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $Label24 = GUICtrlCreateLabel("media_storage", 448, 336, 89, 17)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $media_storage = GUICtrlCreateInput("", 560, 336, 121, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $cover_3d = GUICtrlCreateInput("", 560, 312, 121, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $Label25 = GUICtrlCreateLabel("cover_3d", 448, 312, 57, 17)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $Label26 = GUICtrlCreateLabel("cover_inlay_02", 448, 288, 91, 17)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $cover_inlay_02 = GUICtrlCreateInput("", 560, 288, 121, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $cover_inlay_01 = GUICtrlCreateInput("", 560, 264, 121, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $Label27 = GUICtrlCreateLabel("cover_inlay_01", 448, 264, 91, 17)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $Label28 = GUICtrlCreateLabel("cover_spine", 448, 240, 73, 17)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $cover_spine = GUICtrlCreateInput("", 560, 240, 121, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $booklet_page_10 = GUICtrlCreateInput("", 560, 768, 121, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $Label29 = GUICtrlCreateLabel("booklet_page_10", 448, 768, 102, 17)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $Label30 = GUICtrlCreateLabel("booklet_page_09", 448, 744, 102, 17)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $booklet_page_09 = GUICtrlCreateInput("", 560, 744, 121, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $booklet_page_08 = GUICtrlCreateInput("", 560, 720, 121, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $Label31 = GUICtrlCreateLabel("booklet_page_08", 448, 720, 102, 17)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $Label32 = GUICtrlCreateLabel("booklet_page_07", 448, 696, 102, 17)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $booklet_page_07 = GUICtrlCreateInput("", 560, 696, 121, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $booklet_page_06 = GUICtrlCreateInput("", 560, 672, 121, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $Label33 = GUICtrlCreateLabel("booklet_page_06", 448, 672, 102, 17)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $Label34 = GUICtrlCreateLabel("booklet_page_05", 448, 648, 102, 17)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $booklet_page_05 = GUICtrlCreateInput("", 560, 648, 121, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $booklet_page_04 = GUICtrlCreateInput("", 560, 624, 121, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $Label35 = GUICtrlCreateLabel("booklet_page_04", 448, 624, 102, 17)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $Label36 = GUICtrlCreateLabel("booklet_page_03", 448, 600, 102, 17)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $booklet_page_03 = GUICtrlCreateInput("", 560, 600, 121, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $booklet_page_02 = GUICtrlCreateInput("", 560, 576, 121, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $Label37 = GUICtrlCreateLabel("booklet_page_02", 448, 576, 102, 17)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $Label38 = GUICtrlCreateLabel("booklet_page_01", 448, 552, 102, 17)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $booklet_page_01 = GUICtrlCreateInput("", 560, 552, 121, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $media_icon = GUICtrlCreateInput("", 560, 528, 121, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $Label39 = GUICtrlCreateLabel("media_icon", 448, 528, 69, 17)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $Label40 = GUICtrlCreateLabel("media_flyer_04", 448, 504, 93, 17)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $media_flyer_04 = GUICtrlCreateInput("", 560, 504, 121, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $media_flyer_03 = GUICtrlCreateInput("", 560, 480, 121, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $Label41 = GUICtrlCreateLabel("media_flyer_03", 448, 480, 93, 17)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $media_flyer_02 = GUICtrlCreateInput("", 560, 456, 121, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $Label42 = GUICtrlCreateLabel("media_flyer_02", 448, 456, 93, 17)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $Label43 = GUICtrlCreateLabel("media_flyer", 448, 432, 72, 17)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $media_flyer = GUICtrlCreateInput("", 560, 432, 121, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $Label63 = GUICtrlCreateLabel("Set variable for %IMAGETYPE%", 448, 24, 193, 17)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
GUICtrlSetColor(-1, 0x800000)
Global $ingame_title_location = GUICtrlCreateInput("", 688, 48, 369, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $Label64 = GUICtrlCreateLabel("Set location for imagetype:", 688, 24, 161, 17)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
GUICtrlSetColor(-1, 0x800000)
Global $ingame_play_01_location = GUICtrlCreateInput("", 688, 72, 369, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $ingame_play_02_location = GUICtrlCreateInput("", 688, 96, 369, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $ingame_play_03_location = GUICtrlCreateInput("", 688, 120, 369, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $ingame_play_boss_location = GUICtrlCreateInput("", 688, 144, 369, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $ingame_loading_location = GUICtrlCreateInput("", 688, 168, 369, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $cover_front_location = GUICtrlCreateInput("", 688, 192, 369, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $cover_back_location = GUICtrlCreateInput("", 688, 216, 369, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $cover_spine_location = GUICtrlCreateInput("", 688, 240, 369, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $cover_inlay_01_location = GUICtrlCreateInput("", 688, 264, 369, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $cover_inlay_02_location = GUICtrlCreateInput("", 688, 288, 369, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $cover_3d_location = GUICtrlCreateInput("", 688, 312, 369, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $media_flyer_04_location = GUICtrlCreateInput("", 688, 504, 369, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $media_flyer_03_location = GUICtrlCreateInput("", 688, 480, 369, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $media_flyer_02_location = GUICtrlCreateInput("", 688, 456, 369, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $media_flyer_location = GUICtrlCreateInput("", 688, 432, 369, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $media_stor_04_location = GUICtrlCreateInput("", 688, 408, 369, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $media_stor_03_location = GUICtrlCreateInput("", 688, 384, 369, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $media_stor_02_location = GUICtrlCreateInput("", 688, 360, 369, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $media_storage_location = GUICtrlCreateInput("", 688, 336, 369, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $booklet_page_07_location = GUICtrlCreateInput("", 688, 696, 369, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $booklet_page_06_location = GUICtrlCreateInput("", 688, 672, 369, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $booklet_page_05_location = GUICtrlCreateInput("", 688, 648, 369, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $booklet_page_04_location = GUICtrlCreateInput("", 688, 624, 369, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $booklet_page_03_location = GUICtrlCreateInput("", 688, 600, 369, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $booklet_page_02_location = GUICtrlCreateInput("", 688, 576, 369, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $booklet_page_01_location = GUICtrlCreateInput("", 688, 552, 369, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $media_icon_location = GUICtrlCreateInput("", 688, 528, 369, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $booklet_page_09_location = GUICtrlCreateInput("", 688, 744, 369, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $booklet_page_10_location = GUICtrlCreateInput("", 688, 768, 369, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $booklet_page_08_location = GUICtrlCreateInput("", 688, 720, 369, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
GUICtrlCreateGroup("", -99, -99, 1, 1)
Global $VariableInfo = GUICtrlCreateEdit("", 8, 504, 425, 297, BitOR($GUI_SS_DEFAULT_EDIT,$ES_READONLY))
GUICtrlSetData(-1, StringFormat("ECC-IPC Information:\r\n\r\nVariables to use:\r\n%CRC32% = CRC32 of the ROM file.\r\n%CRC32SHORT% = CRC32 of the ROM file (1st 2 digits).\r\n%PLATFORMID% = ECC platform ID.\r\n%PLATFORMNAME% = Full platform name.\r\n%ROMFILENAME% = Filename of rom (will initiate a eccdb dump to get data)\r\n%IMAGETYPE% = Type of image (ingame play, title).\r\n%IMAGEEXT% = Extension of the imagefile.\r\n%YEAR% = Current year in 4 digits.\r\n%DAYOFYEAR% = Current day in the year (1-365).\r\n\r\nOther info:\r\n1) Processed images will be copied from original files.\r\n2) Variables will be autosaved when exported.\r\n3) All spaces will be converted to underscore (except %ROMFILENAME%).\r\n4) Thumbnail files will NOT be processed.\r\n5) When using %ROMFILENAME%, only images of\r\n   roms that are parsed will be processed.\r\n\r\nExtension usage:\r\n1) Use a pipe "&Chr(39)&" | "&Chr(39)&" as a extension delimter.\r\n2) You cannot use a wildcard "&Chr(39)&" * "&Chr(39)&".\r\n\r\nRemoving unnecessary metadata (junk):\r\n1) Stripper will remove uncessary metadata (junk) like EXIF, IPTC\r\nand comments out of the imagefile.\r\n2) This method could save loads of unessesary MB"&Chr(39)&"s for the imagepack\r\nand slink it nicely without loss of imagequality!\r\n3) This does NOT affect your original imagefiles!"))
GUICtrlSetFont(-1, 7, 400, 0, "Verdana")
Global $ButtonLoad = GUICtrlCreateButton("LOAD PRESETS", 8, 440, 91, 41, $BS_MULTILINE)
GUICtrlSetFont(-1, 9, 800, 2, "Verdana")
GUICtrlSetTip(-1, "Load presets from a .ipc file.")
Global $ButtonSave = GUICtrlCreateButton("SAVE PRESETS", 104, 440, 91, 41, $BS_MULTILINE)
GUICtrlSetFont(-1, 9, 800, 2, "Verdana")
GUICtrlSetTip(-1, "Save presets to a .ipc file.")
Global $Group3 = GUICtrlCreateGroup(" SETTINGS ", 8, 320, 425, 113)
GUICtrlSetFont(-1, 8, 800, 0, "Verdana")
Global $ExtensionsToProcess = GUICtrlCreateInput("", 176, 368, 249, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $ExportName = GUICtrlCreateInput("", 96, 344, 329, 21)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $Label46 = GUICtrlCreateLabel("Export name:", 16, 344, 81, 17)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
GUICtrlSetColor(-1, 0x800000)
Global $Label47 = GUICtrlCreateLabel("File extensions to process:", 16, 368, 153, 17)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
GUICtrlSetColor(-1, 0x800000)
Global $CheckBoxStripper = GUICtrlCreateCheckbox("Remove unnecessary metadata (junk) from images (recommended)", 16, 392, 409, 17)
GUICtrlSetState(-1, $GUI_CHECKED)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $CheckBoxZip = GUICtrlCreateCheckbox("Compress exported images with 7ZIP", 16, 408, 241, 17)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $InputBoxVolumeSize = GUICtrlCreateInput("", 384, 409, 41, 21, BitOR($GUI_SS_DEFAULT_INPUT,$ES_NUMBER))
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $Label9 = GUICtrlCreateLabel("Volume size in MB:", 256, 410, 119, 17, $SS_RIGHT)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
GUICtrlCreateGroup("", -99, -99, 1, 1)
Global $ButtonClear = GUICtrlCreateButton("CLEAR ALL", 200, 440, 91, 41, $BS_MULTILINE)
GUICtrlSetFont(-1, 9, 800, 2, "Verdana")
GUICtrlSetTip(-1, "Clear all GUI settings.")
;==============================================================================
;END *** GUI
;==============================================================================
GUISetIcon(@ScriptDir & "\eccImagePackCenter_export.ico", "", $ECCIPCEXPORT) ;Set proper icon for the window.
GUISetState(@SW_SHOW, $ECCIPCEXPORT)
GUICtrlSetData($PlatformLabel, $PlatformName)
GUICtrlSetData($eccidLabel, $PlatformId)
If FileExists($AutoSavedIpcFile) Then
	$AutoLoad = 1
	ReadFromFile($AutoSavedIpcFile)
EndIf

While 1
	$nMsg = GUIGetMsg()
	Switch $nMsg
		Case $GUI_EVENT_CLOSE
			Exit

		Case $ButtonExport
			$AutoSave = 1
			FileDelete($AutoSavedIpcFile)
			WriteToFile($AutoSavedIpcFile)
			Global $ImageExportFolder = $EccInstallFolder & "\ecc-user-imagepacks\" & @YEAR & @MON & @MDAY & "_" & @HOUR & @MIN & @SEC & "_" & GUICtrlRead($ExportName) & "_export_of_" & $PlatformId
			$ImagesProcessed = 0
			$AbortProcess = 0
			StartFileExport($EccImageFolder) ; Search for images

			If GUICtrlRead($CheckBoxZip) = $GUI_CHECKED Then
				ToolTip("Compressing images with 7ZIP, please wait...", @DesktopWidth/2, @DesktopHeight/2, "ECC-IPC", 1, 6)
				Global $CompressCommand = "a " & Chr(34) & $ImageExportFolder & ".7z" & Chr(34) & " " & Chr(34) & $ImageExportFolder & "\*.*" & Chr(34) & " -r -y"
				If GUICtrlRead($InputBoxVolumeSize) <> "" Then $CompressCommand = $CompressCommand & " -v" & GUICtrlRead($InputBoxVolumeSize) & "m"
				ShellExecuteWait($7zexe, $CompressCommand, "", "", @SW_HIDE)
				ToolTip("Removing temporally files, please wait...", @DesktopWidth/2, @DesktopHeight/2, "ECC-IPC", 1, 6)
				DirRemove($ImageExportFolder & "\", 1)
				ToolTip("7ZIP Archive(s) stored in: " & $ImageExportFolder, @DesktopWidth/2, @DesktopHeight/2, $ImagesProcessed & " images processed!", 1, 6)
				Sleep(2500)
				ToolTip("")
			Else
				ToolTip("Images are stored in: " & $ImageExportFolder, @DesktopWidth/2, @DesktopHeight/2, $ImagesProcessed & " images processed!", 1, 6)
				Sleep(2500)
				ToolTip("")
			EndIf

		Case $ButtonClear
			ReadFromFile("CLEARGUI") ; Dummy INI file to clear the GUI.

		Case $ButtonLoad
			$IpcFiletoLoad = FileOpenDialog("Open IPC settings file:", $IpcPresetFolder, "IPC Settings file (*.ipc)", 3)
			If @error Then
				;User pressed cancel (or an error occured), do nothing!
			Else
				ReadFromFile($IpcFiletoLoad)
			EndIf

		Case $ButtonSave
			$IpcFiletoSave = FileSaveDialog("Save settings to IPC file:", $IpcPresetFolder, "IPC Settings file (*.ipc)", 18)
			If @error Then
				;User pressed cancel (or an error occured), do nothing!
			Else
				If StringRight($IpcFiletoSave, 4) <> ".ipc" Then $IpcFiletoSave &= ".ipc"
				WriteToFile($IpcFiletoSave)
			EndIf

		Case $ExportStyleECC
			$IpcSettingsFile = "emuControlCenter.ipc"
			ReadFromFile($IpcPresetFolder & "\" & $IpcSettingsFile)

		Case $ExportStyleNoIntro
			$IpcSettingsFile = "No-Intro.ipc"
			ReadFromFile($IpcPresetFolder & "\" & $IpcSettingsFile)

		Case $ExportStyleNoIntroPlus
			$IpcSettingsFile = "No-Intro+.ipc"
			ReadFromFile($IpcPresetFolder & "\" & $IpcSettingsFile)

		Case $ExportStyleEmuMovies
			$IpcSettingsFile = "EmuMovies.ipc"
			ReadFromFile($IpcPresetFolder & "\" & $IpcSettingsFile)

	EndSwitch
WEnd
EndFunc ;=>StartExport


Func StartImport()
;==============================================================================
;BEGIN *** GUI
;==============================================================================
Global $ECCIPCIMPORT = GUICreate("ECC ImagePackCenter [IMPORT]", 361, 511, -1, -1)
GUISetBkColor(0xFFFFFF)
Global $Label1 = GUICtrlCreateLabel("Processing:", 8, 288, 84, 15)
GUICtrlSetFont(-1, 8, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x000000)
Global $ImportProcessingLabel = GUICtrlCreateLabel("-", 8, 312, 236, 15)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
GUICtrlSetColor(-1, 0x000080)
Global $ButtonImport = GUICtrlCreateButton("IMPORT TO ECC!", 248, 288, 107, 41, $BS_MULTILINE)
GUICtrlSetFont(-1, 9, 800, 2, "Verdana")
GUICtrlSetTip(-1, "Start importing images.")
Global $Label3 = GUICtrlCreateLabel("Platform:", 8, 8, 60, 15)
GUICtrlSetFont(-1, 8, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x000000)
Global $Label4 = GUICtrlCreateLabel("ECCid:", 264, 8, 44, 15)
GUICtrlSetFont(-1, 8, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x000000)
Global $PlatformLabel = GUICtrlCreateLabel("-", 72, 8, 188, 15)
GUICtrlSetFont(-1, 8, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x000080)
Global $eccidLabel = GUICtrlCreateLabel("-", 304, 8, 44, 15, $SS_RIGHT)
GUICtrlSetFont(-1, 8, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x000080)
Global $Group1 = GUICtrlCreateGroup(" SETTINGS ", 8, 24, 345, 257)
GUICtrlSetFont(-1, 8, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x800000)
Global $ImportImageType = GUICtrlCreateCombo("", 125, 123, 215, 25, BitOR($CBS_DROPDOWN,$CBS_AUTOHSCROLL))
GUICtrlSetData(-1, "ingame_title|ingame_play_01|ingame_play_02|ingame_play_03|ingame_play_boss|ingame_loading|cover_front|cover_back|cover_spine|cover_inlay_01|cover_inlay_02|cover_3d|media_storage|media_stor_02|media_stor_03|media_stor_04|media_flyer|media_flyer_02|media_flyer_03|media_flyer_04|media_icon|booklet_page_01|booklet_page_02|booklet_page_03|booklet_page_04|booklet_page_05|booklet_page_06|booklet_page_07|booklet_page_08|booklet_page_09|booklet_page_10")
Global $ImportDumpType = GUICtrlCreateCombo("", 125, 147, 215, 25, BitOR($CBS_DROPDOWN,$CBS_AUTOHSCROLL))
GUICtrlSetData(-1, "CRC32 Based (like No-Intro)|NAME Based (like EmuMovies)")
Global $Label5 = GUICtrlCreateLabel("Define dumptype:", 13, 147, 105, 17)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $Label6 = GUICtrlCreateLabel("Define imagetype:", 13, 123, 113, 17)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $ButtonFolderSelect = GUICtrlCreateButton("Select folder to import", 13, 43, 331, 33, $BS_MULTILINE)
GUICtrlSetTip(-1, "Select the folder where images are located to import.")
Global $Label2 = GUICtrlCreateLabel("Selected folder to import images:", 13, 83, 201, 17)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $SelectedFolderLabel = GUICtrlCreateLabel("", 13, 99, 329, 17)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
GUICtrlSetColor(-1, 0x000080)
Global $ButtonRCFileSelect = GUICtrlCreateButton("Select RomCenter DAT file to use (only needed when importing NAME BASED images!", 13, 171, 331, 41, $BS_MULTILINE)
GUICtrlSetTip(-1, "Select a RomCenter DaTfile where the CRC32 can be retrived for namebased images.")
Global $Label7 = GUICtrlCreateLabel("Selected RomCenter DATfile to retrieve CRC32:", 13, 219, 329, 17)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $SelectedRCFileLabel = GUICtrlCreateLabel("", 13, 235, 329, 17)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
GUICtrlSetColor(-1, 0x000080)
Global $CheckBoxStripper = GUICtrlCreateCheckbox("Remove unnecessary metadata (junk) from images", 16, 256, 329, 17)
GUICtrlSetState(-1, $GUI_CHECKED)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
GUICtrlCreateGroup("", -99, -99, 1, 1)
Global $VariableInfo = GUICtrlCreateEdit("", 8, 336, 345, 169, BitOR($GUI_SS_DEFAULT_EDIT,$ES_READONLY))
GUICtrlSetData(-1, StringFormat("ECC-IPC import information:\r\n\r\n1) Original files wil l be copied, not moved!\r\n2) All files in the folder will be processed\r\n    (except those with no extension).\r\n3) Subfolders are not processed.\r\n4) When importing namebased imagefiles, a CRC32 code\r\n    has to be retrieved from a RomCenter DAT file.\r\n\r\nExpected file structures:\r\nCRC32 BASED: [CRC32].[EXTENSION]\r\nNAME BASED: [ROMNAME].[EXTENSION]\r\n\r\nRemoving unnecessary metadata (junk):\r\n1) Stripper will remove uncessary metadata (junk) like EXIF, IPTC\r\nand comments out of the imagefile.\r\n2) This method could save loads of unessesary MB"&Chr(39)&"s for the imagepack\r\nand slink it nicely without loss of imagequality!\r\n3) This does NOT affect your original imagefiles!"))
GUICtrlSetFont(-1, 7, 400, 0, "Verdana")
;==============================================================================
;END *** GUI
;==============================================================================
GUISetIcon(@ScriptDir & "\eccImagePackCenter_import.ico", "", $ECCIPCIMPORT) ;Set proper icon for the window.
GUISetState(@SW_SHOW, $ECCIPCIMPORT)
GUICtrlSetData($PlatformLabel, $PlatformName)
GUICtrlSetData($eccidLabel, $PlatformId)
$SelectedFolder = ""

While 1
	$nMsg = GUIGetMsg()
	Switch $nMsg
		Case $GUI_EVENT_CLOSE
			Exit

		Case $ButtonFolderSelect
			$SelectedFolder = FileSelectFolder("ECC-IPC - Select folder to import:", "", "", "") ; Leave empty to show all drives
			If StringLen($SelectedFolder) > 55 Then
				$SelectedFolderForLabel = StringLeft($SelectedFolder, 25) & "....." & StringRight($SelectedFolder, 25)
			Else
				$SelectedFolderForLabel = $SelectedFolder
			EndIf
			GUICtrlSetData($SelectedFolderLabel, $SelectedFolderForLabel)

		Case $ButtonRCFileSelect
			$SelectedRCFile = FileOpenDialog("ECC-IPC - Select RomCenter DAT file:", "", "RomCenter DATfile (*.dat)", 3) ; Leave empty to show all drives
			If StringLen($SelectedRCFile) > 55 Then
				$SelectedRCFileForLabel = StringLeft($SelectedRCFile, 25) & "....." & StringRight($SelectedRCFile, 25)
			Else
				$SelectedRCFileForLabel = $SelectedRCFile
			EndIf
			GUICtrlSetData($SelectedRCFileLabel, $SelectedRCFileForLabel)

		Case $ButtonImport
			If $SelectedFolder = "" Then
				ToolTip("Select a folder where to import images from!", @DesktopWidth/2, @DesktopHeight/2, "ECC-IPC", 1, 6)
				Sleep(1500)
				ToolTip("")
			Else
				If GUICtrlRead($ImportImageType) = "" Then
					ToolTip("Please select a imagetype!", @DesktopWidth/2, @DesktopHeight/2, "ECC-IPC", 1, 6)
					Sleep(1500)
					ToolTip("")
				Else
					StartFileImport($SelectedFolder)
				EndIf
			EndIf

	EndSwitch
WEnd
EndFunc ;=>StartImport



Func StartFileImport($RFSstartDir)
$ImagesProcessed = 0
$DumpTypeOK = 0
$RCFileOK = 0
If StringRight($RFSstartDir, 1) <> "\" Then $RFSstartDir &= "\"

$RFSsearch = FileFindFirstFile($RFSstartDir & "*.*")
If @error Then Return

While 1
	$RFSnext = FileFindNextFile($RFSsearch)
	If @error Then ExitLoop
	If $AbortProcess = 1 Then ExitLoop

	Global $PathVariables = StringSplit($RFSstartDir & $RFSnext, "\")
	;;;If @error Then ExitLoop ;Exit loop, asuming here there are no files found!
	Global $ImageFileName = $PathVariables[Ubound($PathVariables)-1]
	Global $ImageFolder = StringReplace($RFSstartDir & $RFSnext, $ImageFileName, "")
	Global $ImageFileVariables = StringSplit($ImageFileName, ".")

	If Ubound($ImageFileVariables) < 1 Then ExitLoop ; Do not process file without any extension

	Global $ImageFileNameOnly = $ImageFileVariables[1]
	Global $ImageFileExtension = $ImageFileVariables[2]

	If GUICtrlRead($ImportDumpType) = "CRC32 Based (like No-Intro)" Then
		$DumpTypeOK = 1 ;Set a flag is this is a true good value from the imagetype dropbox
		$RCFileOK = 1 ;Set this flag for RC datfile ok, in this case it's always OK because it's not needed ;-)
		Global $ImageFileCrc32 = $ImageFileVariables[1]
		Global $ImageFileCrc32Short = StringMid($ImageFileCrc32, 1, 2)

		GUICtrlSetData($ImportProcessingLabel, $ImageFileName)
		FileCopy_IPC($RFSstartDir & $RFSnext, $EccUserPath & $PlatformId & "\images\" & $ImageFileCrc32Short & "\" & $ImageFileCrc32 & "\ecc_" & $PlatformId & "_" & $ImageFileCrc32 & "_" & GUICtrlRead($ImportImageType) & "." & $ImageFileExtension)
	EndIf

	If GUICtrlRead($ImportDumpType) = "NAME Based (like EmuMovies)" Then
		$DumpTypeOK = 1 ;Set a flag is this is a true good value from the imagetype dropbox
		If $SelectedRCFile = "" Then
			ToolTip("Please select a RomCenter DATfile!", @DesktopWidth/2, @DesktopHeight/2, "ECC-IPC", 1, 6)
			Sleep(1500)
			ToolTip("")
			ExitLoop
		Else
			$RCDATVersion = IniRead($SelectedRCFile, "DAT", "version", "")
			If $RCDATVersion = "2.00" Or $RCDATVersion = "2.50" Then
				Global $ImageFileCrc32 = RetrieveCRCFromFilename($ImageFileNameOnly)
				If $ImageFileCrc32 <> "" Then ;Is the ROMNAME found in the RomCenter DATfile?
					Global $ImageFileCrc32Short = StringMid($ImageFileCrc32, 1, 2)
					GUICtrlSetData($ImportProcessingLabel, $ImageFileName)
					FileCopy_IPC($RFSstartDir & $RFSnext, $EccUserPath & $PlatformId & "\images\" & $ImageFileCrc32Short & "\" & $ImageFileCrc32 & "\ecc_" & $PlatformId & "_" & $ImageFileCrc32 & "_" & GUICtrlRead($ImportImageType) & "." & $ImageFileExtension)
				EndIf
			Else
				ToolTip("This RomCenter DAT version is not supported!", @DesktopWidth/2, @DesktopHeight/2, "ECC-IPC", 1, 6)
				Sleep(1500)
				ToolTip("")
				ExitLoop
			EndIf
		EndIf
	EndIf

	If $DumpTypeOK = 0 Then
		ToolTip("Please select a valid dumptype!", @DesktopWidth/2, @DesktopHeight/2, "ECC-IPC", 1, 6)
		Sleep(1500)
		ToolTip("")
		ExitLoop
	EndIf
WEnd

GUICtrlSetData($ImportProcessingLabel, "-")
ToolTip($ImagesProcessed & " images imported!", @DesktopWidth/2, @DesktopHeight/2, "ECC-IPC", 1, 6)
Sleep(2500)
ToolTip("")
FileClose($RFSsearch)
EndFunc ;==>StartFileImport


Func RetrieveCRCFromFilename($nametofind)
$TrueCRC = ""
$PlatFormImagesFileHandle = Fileopen($SelectedRCFile)
While 1
	$PlatFormImagesData = FileReadLine($PlatFormImagesFileHandle)
	If @error = -1 Then ExitLoop
	$DBFilenameData = StringSplit($PlatFormImagesData, Chr(172))
		If Ubound($DBFilenameData) > 10 Then ;We have found a DATA line (this way header lines are not processed)
			; ROMCENTER DAT FORMAT: ¬parent name¬parent description¬game name¬game description¬rom name¬rom crc¬rom size¬romof name¬merge name¬
			If $nametofind = $DBFilenameData[5] Then
			$TrueCRC = StringUpper($DBFilenameData[7])
			ExitLoop
		EndIf
	EndIf
WEnd
FileClose($PlatFormImagesFileHandle)
Return($TrueCRC)
EndFunc ;==>RetrieveFilenameFromCRC


Func StartFileExport($RFSstartDir)
If StringRight($RFSstartDir, 1) <> "\" Then $RFSstartDir &= "\"

$RFSsearch = FileFindFirstFile($RFSstartDir & "*.*")
If @error Then Return

While 1
	$RFSnext = FileFindNextFile($RFSsearch)
	If @error Then ExitLoop
	If $AbortProcess = 1 Then ExitLoop

	If StringInStr(FileGetAttrib($RFSstartDir & $RFSnext), "D") Then ; We found a directory
		StartFileExport($RFSstartDir & $RFSnext)
	Else ; We found a file
		If StringInStr($RFSstartDir & $RFSnext, "\thumb\") Then ; is it a thumbfile or not?
			; Do nothing!, we don't need thumbs!
		Else
			$FileExtensionPatterns = StringSplit(GUICtrlRead($ExtensionsToProcess), "|")
			For $loop = 1 to UBound($FileExtensionPatterns)-1
				If StringRight($RFSnext, StringLen($FileExtensionPatterns[$loop])) = $FileExtensionPatterns[$loop] Then ; is this an extension we search for?
					Global $PathVariables = StringSplit($RFSstartDir & $RFSnext, "\")
					Global $ImageFileName = $PathVariables[Ubound($PathVariables)-1]
					Global $ImageFolder = StringReplace($RFSstartDir & $RFSnext, $ImageFileName, "")
					Global $ImageFileData = StringSplit($ImageFileName, "_")

					If Ubound($ImageFileData) > 5 Then ; is it a valid structure of the ECC image file?
						Global $ImageFileVariables = StringSplit($ImageFileName, ".")
						Global $ImageFileNameOnly = $ImageFileVariables[1]
						Global $ImageFileExtension = $ImageFileVariables[2] ; %IMAGEEXT% variable
						Global $ImageFileNameData = StringSplit($ImageFileNameOnly, "_")
						Global $ImageFileCrc32 = $ImageFileNameData[3] ; %CRC32% variable
						Global $ImageFileCrc32Short = StringMid($ImageFileCrc32, 1, 2) ; %CRC32SHORT% variable
						Global $ImageFileType = $ImageFileNameData[4] & "_" & $ImageFileNameData[5] ; %IMAGETYPE%  variable
						If Ubound($ImageFileNameData) > 6 Then $ImageFileType = $ImageFileType & "_" & $ImageFileNameData[6]

						If $ImageFileType = "ingame_title" And GUICtrlRead($ingame_title) <> "" And GUICtrlRead($ingame_title_location) <> "" Then
								$ImageFileType = StringReplace($ImageFileType, "ingame_title", GUICtrlRead($ingame_title))
								$ProcessResult = ProcessVariables(GUICtrlRead($ingame_title_location))
								FileCopy_IPC($RFSstartDir & $RFSnext, $ImageExportFolder & "\" & $ProcessResult)
						EndIf

						If $ImageFileType = "ingame_play_01" And GUICtrlRead($ingame_play_01) <> "" And GUICtrlRead($ingame_play_01_location) <> "" Then
								$ImageFileType = StringReplace($ImageFileType, "ingame_play_01", GUICtrlRead($ingame_play_01))
								$ProcessResult = ProcessVariables(GUICtrlRead($ingame_play_01_location))
								FileCopy_IPC($RFSstartDir & $RFSnext, $ImageExportFolder & "\" & $ProcessResult)
						EndIf

						If $ImageFileType = "ingame_play_02" And GUICtrlRead($ingame_play_02) <> "" And GUICtrlRead($ingame_play_02_location) <> "" Then
								$ImageFileType = StringReplace($ImageFileType, "ingame_play_02", GUICtrlRead($ingame_play_02))
								$ProcessResult = ProcessVariables(GUICtrlRead($ingame_play_02_location))
								FileCopy_IPC($RFSstartDir & $RFSnext, $ImageExportFolder & "\" & $ProcessResult)
						EndIf

						If $ImageFileType = "ingame_play_03" And GUICtrlRead($ingame_play_03) <> "" And GUICtrlRead($ingame_play_03_location) <> "" Then
								$ImageFileType = StringReplace($ImageFileType, "ingame_play_03", GUICtrlRead($ingame_play_03))
								$ProcessResult = ProcessVariables(GUICtrlRead($ingame_play_03_location))
								FileCopy_IPC($RFSstartDir & $RFSnext, $ImageExportFolder & "\" & $ProcessResult)
						EndIf

						If $ImageFileType = "ingame_play_boss" And GUICtrlRead($ingame_play_boss) <> "" And GUICtrlRead($ingame_play_boss_location) <> "" Then
								$ImageFileType = StringReplace($ImageFileType, "ingame_play_boss", GUICtrlRead($ingame_play_boss))
								$ProcessResult = ProcessVariables(GUICtrlRead($ingame_play_boss_location))
								FileCopy_IPC($RFSstartDir & $RFSnext, $ImageExportFolder & "\" & $ProcessResult)
							EndIf

						If $ImageFileType = "ingame_loading" And GUICtrlRead($ingame_loading) <> "" And GUICtrlRead($ingame_loading_location) <> "" Then
								$ImageFileType = StringReplace($ImageFileType, "ingame_loading", GUICtrlRead($ingame_loading))
								$ProcessResult = ProcessVariables(GUICtrlRead($ingame_loading_location))
								FileCopy_IPC($RFSstartDir & $RFSnext, $ImageExportFolder & "\" & $ProcessResult)
						EndIf

						If $ImageFileType = "cover_front" And GUICtrlRead($cover_front) <> "" And GUICtrlRead($cover_front_location) <> "" Then
								$ImageFileType = StringReplace($ImageFileType, "cover_front", GUICtrlRead($cover_front))
								$ProcessResult = ProcessVariables(GUICtrlRead($cover_front_location))
								FileCopy_IPC($RFSstartDir & $RFSnext, $ImageExportFolder & "\" & $ProcessResult)
						EndIf

						If $ImageFileType = "cover_back" And GUICtrlRead($cover_back) <> "" And GUICtrlRead($cover_back_location) <> "" Then
								$ImageFileType = StringReplace($ImageFileType, "cover_back", GUICtrlRead($cover_back))
								$ProcessResult = ProcessVariables(GUICtrlRead($cover_back_location))
								FileCopy_IPC($RFSstartDir & $RFSnext, $ImageExportFolder & "\" & $ProcessResult)
						EndIf

						If $ImageFileType = "cover_spine" And GUICtrlRead($cover_spine) <> "" And GUICtrlRead($cover_spine_location) <> "" Then
								$ImageFileType = StringReplace($ImageFileType, "cover_spine", GUICtrlRead($cover_spine))
								$ProcessResult = ProcessVariables(GUICtrlRead($cover_spine_location))
								FileCopy_IPC($RFSstartDir & $RFSnext, $ImageExportFolder & "\" & $ProcessResult)
						EndIf

						If $ImageFileType = "cover_inlay_01" And GUICtrlRead($cover_inlay_01) <> "" And GUICtrlRead($cover_inlay_01_location) <> "" Then
								$ImageFileType = StringReplace($ImageFileType, "cover_inlay_01", GUICtrlRead($cover_inlay_01))
								$ProcessResult = ProcessVariables(GUICtrlRead($cover_inlay_01_location))
								FileCopy_IPC($RFSstartDir & $RFSnext, $ImageExportFolder & "\" & $ProcessResult)
						EndIf

						If $ImageFileType = "cover_inlay_02" And GUICtrlRead($cover_inlay_02) <> "" And GUICtrlRead($cover_inlay_02_location) <> "" Then
								$ImageFileType = StringReplace($ImageFileType, "cover_inlay_02", GUICtrlRead($cover_inlay_02))
								$ProcessResult = ProcessVariables(GUICtrlRead($cover_inlay_02_location))
								FileCopy_IPC($RFSstartDir & $RFSnext, $ImageExportFolder & "\" & $ProcessResult)
						EndIf

						If $ImageFileType = "cover_3d" And GUICtrlRead($cover_3d) <> "" And GUICtrlRead($cover_3d_location) <> "" Then
								$ImageFileType = StringReplace($ImageFileType, "cover_3d", GUICtrlRead($cover_3d))
								$ProcessResult = ProcessVariables(GUICtrlRead($cover_3d_location))
								FileCopy_IPC($RFSstartDir & $RFSnext, $ImageExportFolder & "\" & $ProcessResult)
						EndIf

						If $ImageFileType = "media_storage" And GUICtrlRead($media_storage) <> "" And GUICtrlRead($media_storage_location) <> "" Then
								$ImageFileType = StringReplace($ImageFileType, "media_storage", GUICtrlRead($media_storage))
								$ProcessResult = ProcessVariables(GUICtrlRead($media_storage_location))
								FileCopy_IPC($RFSstartDir & $RFSnext, $ImageExportFolder & "\" & $ProcessResult)
						EndIf

						If $ImageFileType = "media_stor_02" And GUICtrlRead($media_stor_02) <> "" And GUICtrlRead($media_stor_02_location) <> "" Then
								$ImageFileType = StringReplace($ImageFileType, "media_stor_02", GUICtrlRead($media_stor_02))
								$ProcessResult = ProcessVariables(GUICtrlRead($media_stor_02_location))
								FileCopy_IPC($RFSstartDir & $RFSnext, $ImageExportFolder & "\" & $ProcessResult)
						EndIf

						If $ImageFileType = "media_stor_03" And GUICtrlRead($media_stor_03) <> "" And GUICtrlRead($media_stor_03_location) <> "" Then
								$ImageFileType = StringReplace($ImageFileType, "media_stor_03", GUICtrlRead($media_stor_03))
								$ProcessResult = ProcessVariables(GUICtrlRead($media_stor_03_location))
								FileCopy_IPC($RFSstartDir & $RFSnext, $ImageExportFolder & "\" & $ProcessResult)
						EndIf

						If $ImageFileType = "media_stor_04" And GUICtrlRead($media_stor_04) <> "" And GUICtrlRead($media_stor_04_location) <> "" Then
								$ImageFileType = StringReplace($ImageFileType, "media_stor_04", GUICtrlRead($media_stor_04))
								$ProcessResult = ProcessVariables(GUICtrlRead($media_stor_04_location))
								FileCopy_IPC($RFSstartDir & $RFSnext, $ImageExportFolder & "\" & $ProcessResult)
						EndIf

						If $ImageFileType = "media_flyer" And GUICtrlRead($media_flyer) <> "" And GUICtrlRead($media_flyer_location) <> "" Then
								$ImageFileType = StringReplace($ImageFileType, "media_flyer", GUICtrlRead($media_flyer))
								$ProcessResult = ProcessVariables(GUICtrlRead($media_flyer_location))
								FileCopy_IPC($RFSstartDir & $RFSnext, $ImageExportFolder & "\" & $ProcessResult)
						EndIf

						If $ImageFileType = "media_flyer_02" And GUICtrlRead($media_flyer_02) <> "" And GUICtrlRead($media_flyer_02_location) <> "" Then
								$ImageFileType = StringReplace($ImageFileType, "media_flyer_02", GUICtrlRead($media_flyer_02))
								$ProcessResult = ProcessVariables(GUICtrlRead($media_flyer_02_location))
								FileCopy_IPC($RFSstartDir & $RFSnext, $ImageExportFolder & "\" & $ProcessResult)
						EndIf

						If $ImageFileType = "media_flyer_03" And GUICtrlRead($media_flyer_03) <> "" And GUICtrlRead($media_flyer_03_location) <> "" Then
								$ImageFileType = StringReplace($ImageFileType, "media_flyer_03", GUICtrlRead($media_flyer_03))
								$ProcessResult = ProcessVariables(GUICtrlRead($media_flyer_03_location))
								FileCopy_IPC($RFSstartDir & $RFSnext, $ImageExportFolder & "\" & $ProcessResult)
						EndIf

						If $ImageFileType = "media_flyer_04" And GUICtrlRead($media_flyer_04) <> "" And GUICtrlRead($media_flyer_04_location) <> "" Then
								$ImageFileType = StringReplace($ImageFileType, "media_flyer_04", GUICtrlRead($media_flyer_04))
								$ProcessResult = ProcessVariables(GUICtrlRead($media_flyer_04_location))
								FileCopy_IPC($RFSstartDir & $RFSnext, $ImageExportFolder & "\" & $ProcessResult)
						EndIf

						If $ImageFileType = "media_icon" And GUICtrlRead($media_icon) <> "" And GUICtrlRead($media_icon_location) <> "" Then
								$ImageFileType = StringReplace($ImageFileType, "media_icon", GUICtrlRead($media_icon))
								$ProcessResult = ProcessVariables(GUICtrlRead($media_icon_location))
								FileCopy_IPC($RFSstartDir & $RFSnext, $ImageExportFolder & "\" & $ProcessResult)
						EndIf

						If $ImageFileType = "booklet_page_01" And GUICtrlRead($booklet_page_01) <> "" And GUICtrlRead($booklet_page_01_location) <> "" Then
								$ImageFileType = StringReplace($ImageFileType, "booklet_page_01", GUICtrlRead($booklet_page_01))
								$ProcessResult = ProcessVariables(GUICtrlRead($booklet_page_01_location))
								FileCopy_IPC($RFSstartDir & $RFSnext, $ImageExportFolder & "\" & $ProcessResult)
						EndIf

						If $ImageFileType = "booklet_page_02" And GUICtrlRead($booklet_page_02) <> "" And GUICtrlRead($booklet_page_02_location) <> "" Then
								$ImageFileType = StringReplace($ImageFileType, "booklet_page_02", GUICtrlRead($booklet_page_02))
								$ProcessResult = ProcessVariables(GUICtrlRead($booklet_page_02_location))
								FileCopy_IPC($RFSstartDir & $RFSnext, $ImageExportFolder & "\" & $ProcessResult)
						EndIf

						If $ImageFileType = "booklet_page_03" And GUICtrlRead($booklet_page_03) <> "" And GUICtrlRead($booklet_page_03_location) <> "" Then
								$ImageFileType = StringReplace($ImageFileType, "booklet_page_03", GUICtrlRead($booklet_page_03))
								$ProcessResult = ProcessVariables(GUICtrlRead($booklet_page_03_location))
								FileCopy_IPC($RFSstartDir & $RFSnext, $ImageExportFolder & "\" & $ProcessResult)
						EndIf

						If $ImageFileType = "booklet_page_04" And GUICtrlRead($booklet_page_04) <> "" And GUICtrlRead($booklet_page_04_location) <> "" Then
								$ImageFileType = StringReplace($ImageFileType, "booklet_page_04", GUICtrlRead($booklet_page_04))
								$ProcessResult = ProcessVariables(GUICtrlRead($booklet_page_04_location))
								FileCopy_IPC($RFSstartDir & $RFSnext, $ImageExportFolder & "\" & $ProcessResult)
						EndIf

						If $ImageFileType = "booklet_page_05" And GUICtrlRead($booklet_page_05) <> "" And GUICtrlRead($booklet_page_05_location) <> "" Then
								$ImageFileType = StringReplace($ImageFileType, "booklet_page_05", GUICtrlRead($booklet_page_05))
								$ProcessResult = ProcessVariables(GUICtrlRead($booklet_page_05_location))
								FileCopy_IPC($RFSstartDir & $RFSnext, $ImageExportFolder & "\" & $ProcessResult)
						EndIf

						If $ImageFileType = "booklet_page_06" And GUICtrlRead($booklet_page_06) <> "" And GUICtrlRead($booklet_page_06_location) <> "" Then
								$ImageFileType = StringReplace($ImageFileType, "booklet_page_06", GUICtrlRead($booklet_page_06))
								$ProcessResult = ProcessVariables(GUICtrlRead($booklet_page_06_location))
								FileCopy_IPC($RFSstartDir & $RFSnext, $ImageExportFolder & "\" & $ProcessResult)
						EndIf

						If $ImageFileType = "booklet_page_07" And GUICtrlRead($booklet_page_07) <> "" And GUICtrlRead($booklet_page_07_location) <> "" Then
								$ImageFileType = StringReplace($ImageFileType, "booklet_page_07", GUICtrlRead($booklet_page_07))
								$ProcessResult = ProcessVariables(GUICtrlRead($booklet_page_07_location))
								FileCopy_IPC($RFSstartDir & $RFSnext, $ImageExportFolder & "\" & $ProcessResult)
						EndIf

						If $ImageFileType = "booklet_page_08" And GUICtrlRead($booklet_page_08) <> "" And GUICtrlRead($booklet_page_08_location) <> "" Then
								$ImageFileType = StringReplace($ImageFileType, "booklet_page_08", GUICtrlRead($booklet_page_08))
								$ProcessResult = ProcessVariables(GUICtrlRead($booklet_page_08_location))
								FileCopy_IPC($RFSstartDir & $RFSnext, $ImageExportFolder & "\" & $ProcessResult)
						EndIf

						If $ImageFileType = "booklet_page_09" And GUICtrlRead($booklet_page_09) <> "" And GUICtrlRead($booklet_page_09_location) <> "" Then
								$ImageFileType = StringReplace($ImageFileType, "booklet_page_09", GUICtrlRead($booklet_page_09))
								$ProcessResult = ProcessVariables(GUICtrlRead($booklet_page_09_location))
								FileCopy_IPC($RFSstartDir & $RFSnext, $ImageExportFolder & "\" & $ProcessResult)
						EndIf

						If $ImageFileType = "booklet_page_10" And GUICtrlRead($booklet_page_10) <> "" And GUICtrlRead($booklet_page_10_location) <> "" Then
								$ImageFileType = StringReplace($ImageFileType, "booklet_page_10", GUICtrlRead($booklet_page_10))
								$ProcessResult = ProcessVariables(GUICtrlRead($booklet_page_10_location))
								FileCopy_IPC($RFSstartDir & $RFSnext, $ImageExportFolder & "\" & $ProcessResult)
						EndIf

					EndIf
				EndIf
			Next
		EndIf
	EndIf
WEnd
GUICtrlSetData($ProcessingLabel, "-")
FileClose($RFSsearch)
EndFunc ;==>StartFileExport


Func FileCopy_IPC($fromlocation, $tolocation)
FileCopy($fromlocation, $tolocation, 9)
If GUICtrlRead($CheckBoxStripper) = $GUI_CHECKED Then ShellExecuteWait(Chr(34) & $StripperExe & Chr(34), "/gui=0 " & Chr(34) & $tolocation & Chr(34), @ScriptDir, "", @SW_HIDE) ;Remove unnecessary metadata (junk) from image when active
If FileExists($tolocation) <> 1 Then
	MsgBox(64, "ECC-IPC", "ERROR: FILECOPY FAILED! ABORTING PROCESS!, DETAILS:" & @CRLF & @CRLF & _
	"FROM: '" & $fromlocation & "'"  & @CRLF & @CRLF & _
	"TO: '" & $tolocation & "'" )
	$AbortProcess = 1
EndIf
$ImagesProcessed = $ImagesProcessed + 1
EndFunc ;==>FileCopy_IPC

Func ProcessVariables($Input)
$Input = StringReplace($Input, "%PLATFORMID%", $PlatformId)
$Input = StringReplace($Input, "%PLATFORMNAME%", $PlatformName)
$Input = StringReplace($Input, "%CRC32%", $ImageFileCrc32)
$Input = StringReplace($Input, "%CRC32SHORT%", $ImageFileCrc32Short)
$Input = StringReplace($Input, "%IMAGEEXT%", $ImageFileExtension)
$Input = StringReplace($Input, "%IMAGETYPE%", $ImageFileType)
$Input = StringReplace($Input, "%YEAR%", @YEAR)
$Input = StringReplace($Input, "%DAYOFYEAR%", @YDAY)
$Input = StringReplace($Input, " ", "_") ; Convert all spaces to underscore
If StringInStr($Input, "%ROMFILENAME%") Then ;Do we need to dump the ECC database?
	GetDataFromEccDB()
	$Input = StringReplace($Input, "%ROMFILENAME%", RetrieveFilenameFromCRC($ImageFileCrc32))
EndIf
GUICtrlSetData($ProcessingLabel, $ImageFileNameOnly)
Return $Input
EndFunc ;==>ProcessVariables


Func GetDataFromEccDB()
If $RomListRetrieved <> 1 Then
	ToolTip("Found %ROMFILENAME% variable...retrieving ROMlist from ECC database!", @DesktopWidth/2, @DesktopHeight/2, "ECC-IPC", 1, 6)

	$INSTFile = Fileopen($SQLInstructionFile, 10)
	FileWriteLine($INSTFile, ".separator ;")
	FileWriteLine($INSTFile, ".output " & $PlatFormImagesFile)
	FileWriteLine($INSTFile, "SELECT crc32, path FROM fdata WHERE eccident='" & $PlatformId & "';")
	FileClose($INSTFile)

	; It's not possible to execute the sqlite.exe with these command's, so we have to create a .BAT or .CMD file and then run that file.
	; ShellExecuteWait($SQliteExe, Chr(34) & $EccDataBaseFile & Chr(34) & " <" & Chr(34) & $SQLcommandFile & Chr(34), @ScriptDir)
	; RunWait(Chr(34) & $SQliteExe & Chr(34) & " " & Chr(34) & $EccDataBaseFile & Chr(34) & " <" & Chr(34) & $SQLcommandFile & Chr(34), @ScriptDir)
	$CMDFile = Fileopen($SQLCommandFile, 10)
	FileWrite($CMDFile, Chr(34) & $SQliteExe & Chr(34) & " " & Chr(34) & $EccDataBaseFile & Chr(34) & " <" & Chr(34) & $SQLInstructionFile & Chr(34))
	FileClose($CMDFile)

	RunWait(Chr(34) & $SQLcommandFile & Chr(34), @ScriptDir, @SW_HIDE) ; Execute the CMD file with the query

	; Delete the temporally files
	FileDelete($SQLInstructionFile)
	FileDelete($SQLcommandFile)
	Sleep(1000)
	ToolTip("")

	; Warning if user has no roms imported for the platform
	If FileGetSize(@ScriptDir & "\" & $PlatFormImagesFile) < 8 Then
		ToolTip("WARNING: Could not retrive any imported ROMS for this platform!", @DesktopWidth/2, @DesktopHeight/2, "ECC-IPC", 1, 6)
		Sleep(1500)
	EndIf
EndIf
$RomListRetrieved = 1
EndFunc ;==>GetDataFromEccDB


Func RetrieveFilenameFromCRC($crc32tofind)
$PlatFormImagesFileHandle = Fileopen(@ScriptDir & "\" & $PlatFormImagesFile)
While 1
	$PlatFormImagesData = FileReadLine($PlatFormImagesFileHandle)
	If @error = -1 Then ExitLoop
	$DBFilenameData = StringSplit($PlatFormImagesData, ";")
	If $crc32tofind = $DBFilenameData[1] Then
		$PlatFormImagesFileName = $DBFilenameData[2]
		$PlatFormImagesFileName = StringReplace($PlatFormImagesFileName, "/", "\") ; make sure all slashes are \
		$PlatFormImagesFileNameData = StringSplit($PlatFormImagesFileName, "\")
		$PlatFormImagesFileNameData = $PlatFormImagesFileNameData[Ubound($PlatFormImagesFileNameData)-1]
		$TrueFileNameTemp = StringSplit($PlatFormImagesFileNameData, ".")
		$TrueFileName = $TrueFileNameTemp[1]
		ExitLoop
	EndIf
WEnd
FileClose($PlatFormImagesFileHandle)
Return($TrueFileName)
EndFunc ;==>RetrieveFilenameFromCRC


Func ReadFromFile($ipcfilename) ; Read in IPC settings from INI
GUICtrlSetData($ExportName, IniRead($ipcfilename, "SETTINGS", "ExportName", ""))
GUICtrlSetData($ExtensionsToProcess, IniRead($ipcfilename, "SETTINGS", "Extensions", ""))

GUICtrlSetData($ingame_title, IniRead($ipcfilename, "IMAGETYPES", "ingame_title", ""))
GUICtrlSetData($ingame_play_01, IniRead($ipcfilename, "IMAGETYPES", "ingame_play_01", ""))
GUICtrlSetData($ingame_play_02, IniRead($ipcfilename, "IMAGETYPES", "ingame_play_02", ""))
GUICtrlSetData($ingame_play_03, IniRead($ipcfilename, "IMAGETYPES", "ingame_play_03", ""))
GUICtrlSetData($ingame_play_boss, IniRead($ipcfilename, "IMAGETYPES", "ingame_play_boss", ""))
GUICtrlSetData($ingame_loading, IniRead($ipcfilename, "IMAGETYPES", "ingame_loading", ""))
GUICtrlSetData($cover_front, IniRead($ipcfilename, "IMAGETYPES", "cover_front", ""))
GUICtrlSetData($cover_back, IniRead($ipcfilename, "IMAGETYPES", "cover_back", ""))
GUICtrlSetData($cover_spine, IniRead($ipcfilename, "IMAGETYPES", "cover_spine", ""))
GUICtrlSetData($cover_inlay_01, IniRead($ipcfilename, "IMAGETYPES", "cover_inlay_01", ""))
GUICtrlSetData($cover_inlay_02, IniRead($ipcfilename, "IMAGETYPES", "cover_inlay_02", ""))
GUICtrlSetData($cover_3d, IniRead($ipcfilename, "IMAGETYPES", "cover_3d", ""))
GUICtrlSetData($media_storage, IniRead($ipcfilename, "IMAGETYPES", "media_storage", ""))
GUICtrlSetData($media_stor_02, IniRead($ipcfilename, "IMAGETYPES", "media_stor_02", ""))
GUICtrlSetData($media_stor_03, IniRead($ipcfilename, "IMAGETYPES", "media_stor_03", ""))
GUICtrlSetData($media_stor_04, IniRead($ipcfilename, "IMAGETYPES", "media_stor_04", ""))
GUICtrlSetData($media_flyer, IniRead($ipcfilename, "IMAGETYPES", "media_flyer", ""))
GUICtrlSetData($media_flyer_02, IniRead($ipcfilename, "IMAGETYPES", "media_flyer_02", ""))
GUICtrlSetData($media_flyer_03, IniRead($ipcfilename, "IMAGETYPES", "media_flyer_03", ""))
GUICtrlSetData($media_flyer_04, IniRead($ipcfilename, "IMAGETYPES", "media_flyer_04", ""))
GUICtrlSetData($media_icon, IniRead($ipcfilename, "IMAGETYPES", "media_icon", ""))
GUICtrlSetData($booklet_page_01, IniRead($ipcfilename, "IMAGETYPES", "booklet_page_01", ""))
GUICtrlSetData($booklet_page_02, IniRead($ipcfilename, "IMAGETYPES", "booklet_page_02", ""))
GUICtrlSetData($booklet_page_03, IniRead($ipcfilename, "IMAGETYPES", "booklet_page_03", ""))
GUICtrlSetData($booklet_page_04, IniRead($ipcfilename, "IMAGETYPES", "booklet_page_04", ""))
GUICtrlSetData($booklet_page_05, IniRead($ipcfilename, "IMAGETYPES", "booklet_page_05", ""))
GUICtrlSetData($booklet_page_06, IniRead($ipcfilename, "IMAGETYPES", "booklet_page_06", ""))
GUICtrlSetData($booklet_page_07, IniRead($ipcfilename, "IMAGETYPES", "booklet_page_07", ""))
GUICtrlSetData($booklet_page_08, IniRead($ipcfilename, "IMAGETYPES", "booklet_page_08", ""))
GUICtrlSetData($booklet_page_09, IniRead($ipcfilename, "IMAGETYPES", "booklet_page_09", ""))
GUICtrlSetData($booklet_page_10, IniRead($ipcfilename, "IMAGETYPES", "booklet_page_10", ""))

GUICtrlSetData($ingame_title_location, IniRead($ipcfilename, "IMAGETYPES", "ingame_title_location", ""))
GUICtrlSetData($ingame_play_01_location, IniRead($ipcfilename, "IMAGETYPES", "ingame_play_01_location", ""))
GUICtrlSetData($ingame_play_02_location, IniRead($ipcfilename, "IMAGETYPES", "ingame_play_02_location", ""))
GUICtrlSetData($ingame_play_03_location, IniRead($ipcfilename, "IMAGETYPES", "ingame_play_03_location", ""))
GUICtrlSetData($ingame_play_boss_location, IniRead($ipcfilename, "IMAGETYPES", "ingame_play_boss_location", ""))
GUICtrlSetData($ingame_loading_location, IniRead($ipcfilename, "IMAGETYPES", "ingame_loading_location", ""))
GUICtrlSetData($cover_front_location, IniRead($ipcfilename, "IMAGETYPES", "cover_front_location", ""))
GUICtrlSetData($cover_back_location, IniRead($ipcfilename, "IMAGETYPES", "cover_back_location", ""))
GUICtrlSetData($cover_spine_location, IniRead($ipcfilename, "IMAGETYPES", "cover_spine_location", ""))
GUICtrlSetData($cover_inlay_01_location, IniRead($ipcfilename, "IMAGETYPES", "cover_inlay_01_location", ""))
GUICtrlSetData($cover_inlay_02_location, IniRead($ipcfilename, "IMAGETYPES", "cover_inlay_02_location", ""))
GUICtrlSetData($cover_3d_location, IniRead($ipcfilename, "IMAGETYPES", "cover_3d_location", ""))
GUICtrlSetData($media_storage_location, IniRead($ipcfilename, "IMAGETYPES", "media_storage_location", ""))
GUICtrlSetData($media_stor_02_location, IniRead($ipcfilename, "IMAGETYPES", "media_stor_02_location", ""))
GUICtrlSetData($media_stor_03_location, IniRead($ipcfilename, "IMAGETYPES", "media_stor_03_location", ""))
GUICtrlSetData($media_stor_04_location, IniRead($ipcfilename, "IMAGETYPES", "media_stor_04_location", ""))
GUICtrlSetData($media_flyer_location, IniRead($ipcfilename, "IMAGETYPES", "media_flyer_location", ""))
GUICtrlSetData($media_flyer_02_location, IniRead($ipcfilename, "IMAGETYPES", "media_flyer_02_location", ""))
GUICtrlSetData($media_flyer_03_location, IniRead($ipcfilename, "IMAGETYPES", "media_flyer_03_location", ""))
GUICtrlSetData($media_flyer_04_location, IniRead($ipcfilename, "IMAGETYPES", "media_flyer_04_location", ""))
GUICtrlSetData($media_icon_location, IniRead($ipcfilename, "IMAGETYPES", "media_icon_location", ""))
GUICtrlSetData($booklet_page_01_location, IniRead($ipcfilename, "IMAGETYPES", "booklet_page_01_location", ""))
GUICtrlSetData($booklet_page_02_location, IniRead($ipcfilename, "IMAGETYPES", "booklet_page_02_location", ""))
GUICtrlSetData($booklet_page_03_location, IniRead($ipcfilename, "IMAGETYPES", "booklet_page_03_location", ""))
GUICtrlSetData($booklet_page_04_location, IniRead($ipcfilename, "IMAGETYPES", "booklet_page_04_location", ""))
GUICtrlSetData($booklet_page_05_location, IniRead($ipcfilename, "IMAGETYPES", "booklet_page_05_location", ""))
GUICtrlSetData($booklet_page_06_location, IniRead($ipcfilename, "IMAGETYPES", "booklet_page_06_location", ""))
GUICtrlSetData($booklet_page_07_location, IniRead($ipcfilename, "IMAGETYPES", "booklet_page_07_location", ""))
GUICtrlSetData($booklet_page_08_location, IniRead($ipcfilename, "IMAGETYPES", "booklet_page_08_location", ""))
GUICtrlSetData($booklet_page_09_location, IniRead($ipcfilename, "IMAGETYPES", "booklet_page_09_location", ""))
GUICtrlSetData($booklet_page_10_location, IniRead($ipcfilename, "IMAGETYPES", "booklet_page_10_location", ""))

If $AutoLoad <> 1 Then ;Do not diplay tooltip if this is a autosave (on export).
	If FileExists($ipcfilename) Then
		ToolTip("Preset '" & $ipcfilename & "' loaded!", @DesktopWidth/2, @DesktopHeight/2, "ECC-IPC", 1, 6)
		Sleep(2000)
		ToolTip("")
	Else
		If $ipcfilename = "CLEARGUI" Then
			ToolTip("All settings cleared!", @DesktopWidth/2, @DesktopHeight/2, "ECC-IPC", 1, 6)
			Sleep(2000)
			ToolTip("")
		Else
			ToolTip("Preset file not found: " & $ipcfilename, @DesktopWidth/2, @DesktopHeight/2, "ECC-IPC", 1, 6)
			Sleep(3000)
			ToolTip("")
		EndIf
	EndIf
EndIf
$AutoLoad = 0
EndFunc ;==>ReadFromFile


Func WriteToFile($ipcfilename) ; Write IPC settings to INI
IniWrite($ipcfilename, "SETTINGS", "ExportName", GUICtrlRead($ExportName))
IniWrite($ipcfilename, "SETTINGS", "Extensions", GUICtrlRead($ExtensionsToProcess))

IniWrite($ipcfilename, "IMAGETYPES", "ingame_title", GUICtrlRead($ingame_title))
IniWrite($ipcfilename, "IMAGETYPES", "ingame_play_01", GUICtrlRead($ingame_play_01))
IniWrite($ipcfilename, "IMAGETYPES", "ingame_play_02", GUICtrlRead($ingame_play_02))
IniWrite($ipcfilename, "IMAGETYPES", "ingame_play_03", GUICtrlRead($ingame_play_03))
IniWrite($ipcfilename, "IMAGETYPES", "ingame_play_boss", GUICtrlRead($ingame_play_boss))
IniWrite($ipcfilename, "IMAGETYPES", "ingame_loading", GUICtrlRead($ingame_loading))
IniWrite($ipcfilename, "IMAGETYPES", "cover_front", GUICtrlRead($cover_front))
IniWrite($ipcfilename, "IMAGETYPES", "cover_back", GUICtrlRead($cover_back))
IniWrite($ipcfilename, "IMAGETYPES", "cover_spine", GUICtrlRead($cover_spine))
IniWrite($ipcfilename, "IMAGETYPES", "cover_inlay_01", GUICtrlRead($cover_inlay_01))
IniWrite($ipcfilename, "IMAGETYPES", "cover_inlay_02", GUICtrlRead($cover_inlay_02))
IniWrite($ipcfilename, "IMAGETYPES", "cover_3d", GUICtrlRead($cover_3d))
IniWrite($ipcfilename, "IMAGETYPES", "media_storage", GUICtrlRead($media_storage))
IniWrite($ipcfilename, "IMAGETYPES", "media_stor_02", GUICtrlRead($media_stor_02))
IniWrite($ipcfilename, "IMAGETYPES", "media_stor_03", GUICtrlRead($media_stor_03))
IniWrite($ipcfilename, "IMAGETYPES", "media_stor_04", GUICtrlRead($media_stor_04))
IniWrite($ipcfilename, "IMAGETYPES", "media_flyer", GUICtrlRead($media_flyer))
IniWrite($ipcfilename, "IMAGETYPES", "media_flyer_02", GUICtrlRead($media_flyer_02))
IniWrite($ipcfilename, "IMAGETYPES", "media_flyer_03", GUICtrlRead($media_flyer_03))
IniWrite($ipcfilename, "IMAGETYPES", "media_flyer_04", GUICtrlRead($media_flyer_04))
IniWrite($ipcfilename, "IMAGETYPES", "media_icon", GUICtrlRead($media_icon))
IniWrite($ipcfilename, "IMAGETYPES", "booklet_page_01", GUICtrlRead($booklet_page_01))
IniWrite($ipcfilename, "IMAGETYPES", "booklet_page_02", GUICtrlRead($booklet_page_02))
IniWrite($ipcfilename, "IMAGETYPES", "booklet_page_03", GUICtrlRead($booklet_page_03))
IniWrite($ipcfilename, "IMAGETYPES", "booklet_page_04", GUICtrlRead($booklet_page_04))
IniWrite($ipcfilename, "IMAGETYPES", "booklet_page_05", GUICtrlRead($booklet_page_05))
IniWrite($ipcfilename, "IMAGETYPES", "booklet_page_06", GUICtrlRead($booklet_page_06))
IniWrite($ipcfilename, "IMAGETYPES", "booklet_page_07", GUICtrlRead($booklet_page_07))
IniWrite($ipcfilename, "IMAGETYPES", "booklet_page_08", GUICtrlRead($booklet_page_08))
IniWrite($ipcfilename, "IMAGETYPES", "booklet_page_09", GUICtrlRead($booklet_page_09))
IniWrite($ipcfilename, "IMAGETYPES", "booklet_page_10", GUICtrlRead($booklet_page_10))

IniWrite($ipcfilename, "IMAGETYPES", "ingame_title_location", GUICtrlRead($ingame_title_location))
IniWrite($ipcfilename, "IMAGETYPES", "ingame_play_01_location", GUICtrlRead($ingame_play_01_location))
IniWrite($ipcfilename, "IMAGETYPES", "ingame_play_02_location", GUICtrlRead($ingame_play_02_location))
IniWrite($ipcfilename, "IMAGETYPES", "ingame_play_03_location", GUICtrlRead($ingame_play_03_location))
IniWrite($ipcfilename, "IMAGETYPES", "ingame_play_boss_location", GUICtrlRead($ingame_play_boss_location))
IniWrite($ipcfilename, "IMAGETYPES", "ingame_loading_location", GUICtrlRead($ingame_loading_location))
IniWrite($ipcfilename, "IMAGETYPES", "cover_front_location", GUICtrlRead($cover_front_location))
IniWrite($ipcfilename, "IMAGETYPES", "cover_back_location", GUICtrlRead($cover_back_location))
IniWrite($ipcfilename, "IMAGETYPES", "cover_spine_location", GUICtrlRead($cover_spine_location))
IniWrite($ipcfilename, "IMAGETYPES", "cover_inlay_01_location", GUICtrlRead($cover_inlay_01_location))
IniWrite($ipcfilename, "IMAGETYPES", "cover_inlay_02_location", GUICtrlRead($cover_inlay_02_location))
IniWrite($ipcfilename, "IMAGETYPES", "cover_3d_location", GUICtrlRead($cover_3d_location))
IniWrite($ipcfilename, "IMAGETYPES", "media_storage_location", GUICtrlRead($media_storage_location))
IniWrite($ipcfilename, "IMAGETYPES", "media_stor_02_location", GUICtrlRead($media_stor_02_location))
IniWrite($ipcfilename, "IMAGETYPES", "media_stor_03_location", GUICtrlRead($media_stor_03_location))
IniWrite($ipcfilename, "IMAGETYPES", "media_stor_04_location", GUICtrlRead($media_stor_04_location))
IniWrite($ipcfilename, "IMAGETYPES", "media_flyer_location", GUICtrlRead($media_flyer_location))
IniWrite($ipcfilename, "IMAGETYPES", "media_flyer_02_location", GUICtrlRead($media_flyer_02_location))
IniWrite($ipcfilename, "IMAGETYPES", "media_flyer_03_location", GUICtrlRead($media_flyer_03_location))
IniWrite($ipcfilename, "IMAGETYPES", "media_flyer_04_location", GUICtrlRead($media_flyer_04_location))
IniWrite($ipcfilename, "IMAGETYPES", "media_icon_location", GUICtrlRead($media_icon_location))
IniWrite($ipcfilename, "IMAGETYPES", "booklet_page_01_location", GUICtrlRead($booklet_page_01_location))
IniWrite($ipcfilename, "IMAGETYPES", "booklet_page_02_location", GUICtrlRead($booklet_page_02_location))
IniWrite($ipcfilename, "IMAGETYPES", "booklet_page_03_location", GUICtrlRead($booklet_page_03_location))
IniWrite($ipcfilename, "IMAGETYPES", "booklet_page_04_location", GUICtrlRead($booklet_page_04_location))
IniWrite($ipcfilename, "IMAGETYPES", "booklet_page_05_location", GUICtrlRead($booklet_page_05_location))
IniWrite($ipcfilename, "IMAGETYPES", "booklet_page_06_location", GUICtrlRead($booklet_page_06_location))
IniWrite($ipcfilename, "IMAGETYPES", "booklet_page_07_location", GUICtrlRead($booklet_page_07_location))
IniWrite($ipcfilename, "IMAGETYPES", "booklet_page_08_location", GUICtrlRead($booklet_page_08_location))
IniWrite($ipcfilename, "IMAGETYPES", "booklet_page_09_location", GUICtrlRead($booklet_page_09_location))
IniWrite($ipcfilename, "IMAGETYPES", "booklet_page_10_location", GUICtrlRead($booklet_page_10_location))

If $AutoSave <> 1 Then ;Do not diplay tooltip if this is a autosave (on export).
	ToolTip("Preset '" & $ipcfilename & "' saved!", @DesktopWidth/2, @DesktopHeight/2, "ECC-IPC", 1, 6)
	Sleep(2000)
	ToolTip("")
	$Autosave = 0
EndIf
EndFunc ;==>WriteToFile