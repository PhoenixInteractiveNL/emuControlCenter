#NoTrayIcon
; emuControlCenter Startup Made by Sebastiaan Ebeltjes (Phoenix Interactive)
; Greetings from Deventer, The Netherlands! ;)

; AU3 INCLUDES
#include "ecc-core\thirdparty\autoit\include\GuiEdit.au3"
#include "ecc-core\thirdparty\autoit\include\GDIPlus.au3"
#include "ecc-core\thirdparty\autoit\include\Constants.au3"
#include "ecc-core\thirdparty\autoit\include\WindowsConstants.au3"
#include "ecc-core\thirdparty\autoit\include\GuiConstants.au3"
#include "ecc-core\thirdparty\autoit\include\GuiConstantsEx.au3"
#include "ecc-core\thirdparty\autoit\include\Date.au3"
#include "ecc-core\thirdparty\autoit\include\EditConstants.au3"
#include "ecc-core\thirdparty\autoit\include\ButtonConstants.au3"
#include "ecc-core\thirdparty\autoit\include\TreeViewConstants.au3"
#include "ecc-core\thirdparty\autoit\include\FTPEx.au3"
#include "ecc-core\thirdparty\autoit\include\Array.au3"
#include "ecc-core\thirdparty\autoit\include\File.au3"
#include "ecc-core\thirdparty\autoit\include\GIFAnimation.au3"

Global Const $AC_SRC_ALPHA = 1

; -------------------------------------
; SET Auto-it options
; -------------------------------------
Break(0)
AutoItSetOption("TrayIconHide",1)
AutoItSetOption("TrayAutoPause",0)
AutoItSetOption("TrayMenuMode",1)
AutoItSetOption("TrayOnEventMode",1)
AutoItSetOption("WinTitleMatchMode",3)

; -------------------------------------
; Define baked-in variables
; -------------------------------------
Global $ecc_file_version =				"3.0.0.4"
Global $ecc_messagebox_title =			"ECC"
Global $ecc_php_file = 					@Scriptdir & "\ecc-system\ecc.php"
Global $ecc_php_file_q = 				Chr(34) & $ecc_php_file & Chr(34)
Global $ecc_window_title_catchname = 	".oO(emuControlCenter)Oo."
Global $ecc_teaser_image = 				@ScriptDir & "\ecc-system\images\platform\ecc_ecc_teaser.png"
Global $ecc_local_version_info = 		@ScriptDir & "\ecc-system\system\info\ecc_local_version_info.ini"
Global $ecc_local_update_info =			@ScriptDir & "\ecc-system\system\info\ecc_local_update_info.ini"
Global $ecc_drive_get_info =			@ScriptDir & "\ecc-system\system\info\ecc_drive_get_info.ini"
Global $ecc_easteregg_month =			"12"
Global $ecc_easteregg_day =				"29"
Global $ecc_config_file_user_folder =	@ScriptDir & "\ecc-user-configs\config"
Global $ecc_config_file_general_ecc =	@ScriptDir & "\ecc-system\system\config\ecc_general.ini"
Global $ecc_config_file_general_user =	@ScriptDir & "\ecc-user-configs\config\ecc_general.ini"
Global $ecc_default_language = 			"en"
Global $ecc_default_language_q = 		Chr(34) & $ecc_default_language & Chr(34)
Global $ecc_translation_folder = 		@Scriptdir & "\ecc-system\translations\"
Global $ecc_translation_filename = 		"ecc_startup.ini"
Global $ecc_phperror_log =				@ScriptDir & "\error.log"
Global $ecc_php_exe =					@ScriptDir & "\ecc-core\php-gtk2\php.exe"
Global $ecc_php_exe_q = 				Chr(34) & $ecc_php_exe & Chr(34)
Global $ecc_php_exe_win =				@ScriptDir & "\ecc-core\php-gtk2\php-win.exe"
Global $ecc_php_exe_win_q = 			Chr(34) & $ecc_php_exe_win & Chr(34)
Global $ecc_php_ini_file =				" -c ecc-core/php-gtk2/php.ini "
Global $ecc_localversion_update =		@Scriptdir & "\ecc-system\manager\tLocalVersion.php"
Global $ecc_localversion_update_q=		Chr(34) & $ecc_localversion_update & Chr(34)
Global $ecc_easteregg_icon =			@ScriptDir & "\ecc-core\php-gtk2\ext\php_icn.dll"
Global $ecc_image_converter =			@Scriptdir & "\ecc-system\manager\tEccImageGen.php"
Global $ecc_image_converter_q =			Chr(34) & $ecc_image_converter & Chr(34)
Global $ecc_navigation_ini =			@ScriptDir & "\ecc-system\system\config\ecc_navigation.ini"
Global $3rdParty_notepad_folder =		@Scriptdir & "\ecc-core\thirdparty\notepad++"
Global $ecc_datfile_folder =			@Scriptdir & "\ecc-system\datfile"
Global $ecc_datfile_tempfile =			@Scriptdir & $ecc_datfile_folder & "\temp\mamelist.dat"
Global $ecc_3rdParty_autoit =			@Scriptdir & "\ecc-core\thirdparty\autoit\AutoIt3.exe"
Global $ecc_3rdParty_7zip =				@Scriptdir & "\ecc-core\thirdparty\7zip\7z.exe"
Global $ecc_tools_path =				@Scriptdir & "\ecc-core\tools"
Global $ecc_tool_DFU =					$ecc_tools_path & "\eccDatFileUpdater.au3"
Global $ecc_tool_CreateMenuIcons =		$ecc_tools_path & "\eccCreateStartmenuShotcuts.au3"
Global $ecc_tool_ThirdPartyConfig =		$ecc_tools_path & "\eccThirdPartyConfig.au3"
Global $ecc_tool_diagnostics = 			$ecc_tools_path & "\eccDiagnostics.au3"
Global $ecc_tool_diagnostics_report = 	$ecc_tools_path & "\eccDiagnostics.txt"
Global $ecc_tool_eccupdate = 			$ecc_tools_path & "\eccUpdate.au3"
Global $gui_regel, $ecc_translation, $ecc_splash_window, $ecc_splash_window_border
Global $ChoosenLanguage =				$ecc_default_language
; This line is to set the current work folder back to the ECC root, because when there is a restart of ECC
; from within ECC, the work folder is diffrent and errors occur!
FileChangeDir(@Scriptdir)

; -------------------------------------
; Read-in title variables
; -------------------------------------
If FileExists($ecc_php_exe_win) = 1 Then ShellExecuteWait($ecc_php_exe_win_q, $ecc_php_ini_file & " " & $ecc_localversion_update_q, @SW_HIDE)
Global $ecc_main_version =		IniRead($ecc_local_version_info, "GENERAL", "current_version", "x.x")
Global $ecc_main_build =		IniRead($ecc_local_version_info, "GENERAL", "current_build", "xxx")
Global $ecc_main_build_date =	IniRead($ecc_local_version_info, "GENERAL", "date_build", "xxxx.xx.xx")
Global $ecc_last_update =		IniRead($ecc_local_update_info, "UPDATE", "last_update", "xxxxx")
Global $ecc_generated_title =	"emuControlCenter" & " v" & $ecc_main_version & " build:" & $ecc_main_build & " (" & $ecc_main_build_date & ")" & " upd:" & $ecc_last_update

; -------------------------------------
; Read-in ecc config variables
; -------------------------------------
If FileExists($ecc_config_file_general_user) = 0 Then FileCopy($ecc_config_file_general_ecc, $ecc_config_file_general_user, 9)
Global $ecc_startup_sound =				IniRead($ecc_config_file_general_user, "ECC_STARTUP", "startup_sound", "")
Global $ecc_startup_soundplay =			"1"
Global $ecc_startup_bugreport_check =	IniRead($ecc_config_file_general_user, "ECC_STARTUP", "startup_bugreport_check", "")
Global $ecc_startup_minimize_to_tray =	IniRead($ecc_config_file_general_user, "ECC_STARTUP", "minimize_to_tray", "")
Global $ecc_external_JoyEmulator =		IniRead($ecc_config_file_general_user, "USER_DATA", "joyemulator_exe", "")
Global $ecc_external_JoyEmulator_param =IniRead($ecc_config_file_general_user, "USER_DATA", "joyemulator_param", "")

; Set recursive path to full (once)
If $ecc_external_JoyEmulator = "\ecc-core\thirdparty\wojemulator\WoJEmulatorStandard.exe" Then
	$ecc_external_JoyEmulator = @Scriptdir & "\ecc-core\thirdparty\wojemulator\WoJEmulatorStandard.exe"
	IniWrite($ecc_config_file_general_user, "USER_DATA", "joyemulator_exe", Chr(34) & $ecc_external_JoyEmulator & Chr(34))
EndIf

Local $sDrive = "", $sDir = "", $sFileName = "", $sExtension = ""
Global $ecc_external_JoyEmulator_temp =	_PathSplit($ecc_external_JoyEmulator, $sDrive, $sDir, $sFileName, $sExtension)
Global $ecc_external_JoyEmulator_exe =	$ecc_external_JoyEmulator_temp[3] & $ecc_external_JoyEmulator_temp[4]
Global $ecc_startup_JoyEmulator =		IniRead($ecc_config_file_general_user, "ECC_STARTUP", "startup_joyemulator", "")
Global $ecc_base_path =					IniRead($ecc_config_file_general_user, "USER_DATA", "base_path", @ScriptDir & "\ecc-user\") ; ecc user folder
Global $ecc_base_path_full =			StringReplace($ecc_base_path, "..", @ScriptDir) ; If userpath is default '..\ecc-user', replace '..' into ecc root folder, so we have a complete and full pathname
Global $ecc_base_path_full_fix =		StringReplace($ecc_base_path_full, "/", "\") ; Fix path with 'windows' slashes (seems the path settings in 'cIniFile.php' don't like the '\' string (makes ecc crash)

; -------------------------------------
; Read-in ecc splashscreen variables
; -------------------------------------
Global $ecc_theme = IniRead($ecc_config_file_general_user, "ECC_THEME", "ecc-theme", "default")

Global $ecc_theme_file = @ScriptDir & "\ecc-themes\default\SplashScreen.ini"
Global $ecc_theme_to_use = "default"

If FileExists(@ScriptDir & "\ecc-themes\" & $ecc_theme & "\SplashScreen.ini") Then
	$ecc_theme_file = @ScriptDir & "\ecc-themes\" & $ecc_theme & "\SplashScreen.ini"
	Global $ecc_theme_to_use = $ecc_theme
EndIf

Global $ecc_splash_icon = 				IniRead($ecc_theme_file, "GENERAL", "SplashIconFileName", "")
Global $ecc_splash_image_file_name =	IniRead($ecc_theme_file, "GENERAL", "SplashImageFileName", "")
Global $ecc_splash_image_file =			@ScriptDir & "\ecc-themes\" & $ecc_theme_to_use & "\splashscreen\" & $ecc_splash_image_file_name
Global $ecc_splash_image_border_file_name =	IniRead($ecc_theme_file, "GENERAL", "SplashImageBorderFileName", "")
Global $ecc_splash_image_border_file =	@ScriptDir & "\ecc-themes\" & $ecc_theme_to_use & "\splashscreen\" & $ecc_splash_image_border_file_name
Global $ecc_splash_image_fade_limit =	IniRead($ecc_theme_file, "GENERAL", "SplashImageFadeLimit", "0")
Global $ecc_splash_image_fade_step =	IniRead($ecc_theme_file, "GENERAL", "SplashImageFadeStep", "1")
Global $ecc_splash_image_fade_sleep =	IniRead($ecc_theme_file, "GENERAL", "SplashImageFadeSleep", "5")
Global $ecc_splash_image_width = 		IniRead($ecc_theme_file, "GENERAL", "SplashImageWidth", "0")
Global $ecc_splash_image_height = 		IniRead($ecc_theme_file, "GENERAL", "SplashImageHeight", "0")
Global $ecc_splash_movie_file_name =	IniRead($ecc_theme_file, "GENERAL", "SplashMovieFileName", "")
Global $ecc_splash_movie_file =			@ScriptDir & "\ecc-themes\" & $ecc_theme_to_use & "\splashscreen\" & $ecc_splash_movie_file_name
Global $ecc_splash_movielocation_left =	IniRead($ecc_theme_file, "GENERAL", "SplashMovieLocationLeft", "")
Global $ecc_splash_movielocation_top =	IniRead($ecc_theme_file, "GENERAL", "SplashMovieLocationTop", "")
Global $ecc_splash_textlocation_left =	IniRead($ecc_theme_file, "GENERAL", "SplashTextLocationLeft", "")
Global $ecc_splash_textlocation_top =	IniRead($ecc_theme_file, "GENERAL", "SplashTextLocationTop", "")
Global $ecc_splash_text_color =			IniRead($ecc_theme_file, "GENERAL", "SplashTextColor", "")
Global $ecc_splash_image_fade =			"0x00080000" ; (Fade-in=0x00080000) (Fade-out=0x00090000)
Global $ecc_splash_timeout =			"20"

; -------------------------------------
; First startup config
; -------------------------------------
If IniRead($ecc_config_file_general_user, "USER_DATA", "language", "") = "" Then

$ECCSTARTUPFIRSTCONFIG = GUICreate("ECC Startup - First time configuration!", 571, 311, -1, -1)
GUISetBkColor(0xFFFFFF)
$ButtonOk = GUICtrlCreateButton("OK", 472, 264, 91, 41)
$guistring3 = GUICtrlCreateGroup(" Options ", 8, 256, 345, 49)
$CheckBoxUpdate = GUICtrlCreateCheckbox("Check for updates on startup.", 16, 272, 329, 17)
GUICtrlCreateGroup("", -99, -99, 1, 1)
$guistring1 = GUICtrlCreateGroup(" Select Language ", 8, 0, 161, 257)
$LanguageTree = GUICtrlCreateTreeView(16, 16, 145, 233, BitOR($TVS_HASBUTTONS,$TVS_LINESATROOT,$TVS_DISABLEDRAGDROP,$TVS_SHOWSELALWAYS,$TVS_FULLROWSELECT,$WS_BORDER))
GUICtrlCreateGroup("", -99, -99, 1, 1)
$guistring2 = GUICtrlCreateGroup(" Please read carefully ", 176, 0, 385, 257)
$Textbox = GUICtrlCreateEdit("", 184, 16, 369, 233, BitOR($GUI_SS_DEFAULT_EDIT,$ES_READONLY))
GUICtrlCreateGroup("", -99, -99, 1, 1)
$ButtonShortcuts = GUICtrlCreateButton("Create startmenu shortcuts", 360, 264, 107, 41, $BS_MULTILINE)

; -------------------------------------
; Fill Language Tree
; -------------------------------------
$FileSearch = FileFindFirstFile($ecc_translation_folder & "*.*")
While 1
	If $FileSearch = -1 Then ExitLoop
	$FoundFile = FileFindNextFile($FileSearch)
	If @error Then ExitLoop

	$FullFilePath = $ecc_translation_folder & "\" & $FoundFile
	$FileAttributes = FileGetAttrib($FullFilePath)
	If StringInStr($FileAttributes,"D") Then
		$ShortLanguage = StringReplace($FullFilePath, $ecc_translation_folder & "\", "")
		If StringLen($ShortLanguage) = 2 Then
			$FullLanguage = Iniread($ecc_translation_folder & "\" & $ShortLanguage & "\" & "ecc_startup.ini", "ECC_TRANSLATION", "ecc_language", "")
			If $FullLanguage = "" Then $FullLanguage = $ShortLanguage
			GUICtrlCreateTreeViewItem($FullLanguage & " (" & $ShortLanguage & ")", $LanguageTree)
			GUICtrlSetImage(-1, $ecc_translation_folder & "\" & $ShortLanguage & "\flag.ico")
		EndIf
	EndIf
WEnd
FileClose($FileSearch)

; Default values for EN language
ControlTreeView($ECCSTARTUPFIRSTCONFIG, "", $LanguageTree, "Select", "English (en)")
GUICtrlSetState($CheckBoxUpdate, $GUI_CHECKED)
GUICtrlSetData($Textbox, FileRead($ecc_translation_folder & "\en\ecc_startup.txt"))
GUICtrlSetData($guistring1, Iniread($ecc_translation_folder & "\en\" & $ecc_translation_filename, "ECC_TRANSLATION", "ecc_gui_string1", "Select Language"))
GUICtrlSetData($guistring2, Iniread($ecc_translation_folder & "\en\" & $ecc_translation_filename, "ECC_TRANSLATION", "ecc_gui_string2", "Please read carefully"))
GUICtrlSetData($guistring3, Iniread($ecc_translation_folder & "\en\" & $ecc_translation_filename, "ECC_TRANSLATION", "ecc_gui_string3", "Options"))
GUICtrlSetData($CheckBoxUpdate, Iniread($ecc_translation_folder & "\en\" & $ecc_translation_filename, "ECC_TRANSLATION", "ecc_gui_string4", "Check fo updates on startup."))
GUICtrlSetData($ButtonShortcuts, Iniread($ecc_translation_folder & "\en\" & $ecc_translation_filename, "ECC_TRANSLATION", "ecc_gui_string6", "Create startmenu shortcuts"))
GUICtrlSetData($ButtonOk, Iniread($ecc_translation_folder & "\en\" & $ecc_translation_filename, "ECC_TRANSLATION", "ecc_gui_string7", "OK"))

; Show first config GUI
GUISetState(@SW_SHOW, $ECCSTARTUPFIRSTCONFIG)

While 1
	$nMsg = GUIGetMsg($ECCSTARTUPFIRSTCONFIG)
	Switch $nMsg

		Case $GUI_EVENT_CLOSE
			Exit

		Case $ButtonOk
			Global $ChoosenLanguage = StringReplace(StringRight(GUICtrlRead($LanguageTree, 1), 3), ")" , "")
			IniWrite($ecc_config_file_general_user, "USER_DATA", "language", Chr(34) & $ChoosenLanguage & Chr(34))

			If GUICtrlRead($CheckBoxUpdate) = $GUI_CHECKED Then
				IniWrite($ecc_config_file_general_user, "ECC_STARTUP", "startup_update_check", Chr(34) & "1" & Chr(34))
			Else
				IniWrite($ecc_config_file_general_user, "ECC_STARTUP", "startup_update_check", Chr(34) & "0" Chr(34))
			EndIf

			$ecc_translation = IniRead($ecc_config_file_general_user, "USER_DATA", "language", "")
			GUIDelete($ECCSTARTUPFIRSTCONFIG)
			ExitLoop

		Case $ButtonShortcuts
			ShellExecuteWait($ecc_3rdParty_autoit, Chr(34) & $ecc_tool_CreateMenuIcons & Chr(34), $ecc_tools_path, "", @SW_HIDE)

		Case $GUI_EVENT_PRIMARYDOWN ;Left mouse button pressed
			Global $ChoosenLanguage = StringReplace(StringRight(GUICtrlRead($LanguageTree, 1), 3), ")" , "")
			GUICtrlSetData($guistring1, Iniread($ecc_translation_folder & "\" & $ChoosenLanguage & "\" & $ecc_translation_filename, "ECC_TRANSLATION", "ecc_gui_string1", "Select Language"))
			GUICtrlSetData($guistring2, Iniread($ecc_translation_folder & "\" & $ChoosenLanguage & "\" & $ecc_translation_filename, "ECC_TRANSLATION", "ecc_gui_string2", "Please read carefully"))
			GUICtrlSetData($guistring3, Iniread($ecc_translation_folder & "\" & $ChoosenLanguage & "\" & $ecc_translation_filename, "ECC_TRANSLATION", "ecc_gui_string3", "Options"))
			GUICtrlSetData($CheckBoxUpdate, Iniread($ecc_translation_folder & "\" & $ChoosenLanguage & "\" & $ecc_translation_filename, "ECC_TRANSLATION", "ecc_gui_string4", "Check fo updates on startup."))
			GUICtrlSetData($ButtonShortcuts, Iniread($ecc_translation_folder & "\" & $ChoosenLanguage & "\" & $ecc_translation_filename, "ECC_TRANSLATION", "ecc_gui_string6", "Create startmenu shortcuts"))
			GUICtrlSetData($ButtonOk, Iniread($ecc_translation_folder & "\" & $ChoosenLanguage & "\" & $ecc_translation_filename, "ECC_TRANSLATION", "ecc_gui_string7", "OK"))
			GUICtrlSetData($Textbox, FileRead($ecc_translation_folder & "\" & $ChoosenLanguage & "\ecc_startup.txt"))

	EndSwitch
WEnd

Else
	$ecc_translation = IniRead($ecc_config_file_general_user, "USER_DATA", "language", "en")
EndIf

; -------------------------------------
; Read-in language strings
; -------------------------------------
$ecc_translation_file = StringReplace($ecc_translation_folder & "\" & $ecc_translation & "\" & $ecc_translation_filename, "\\", "\") ; StringReplace in case there are \\ in the path!

;Does the file exist?, of not fall back to english
If FileExists($ecc_translation_file) = 0 Then
	If FileExists($ecc_translation_folder & "en\" & $ecc_translation_filename) = 1 Then
		$ecc_translation_file = $ecc_translation_folder & "en\" & $ecc_translation_filename
	Else
		MsgBox(16, $ecc_messagebox_title, "CRITICAL ERROR! NO TRANSLATIONS FOUND :(, exiting...")
		Exit
	EndIf
EndIf

Global $ecc_splash_title =		IniRead($ecc_translation_file, "ECC_TRANSLATION", "ecc_splash_title", " ")
Global $ecc_splash_message =		IniRead($ecc_translation_file, "ECC_TRANSLATION", "ecc_splash_message", "")
Global $ecc_restart_title =		IniRead($ecc_translation_file, "ECC_TRANSLATION", "ecc_restart_title", " ")
Global $ecc_restart_message =		IniRead($ecc_translation_file, "ECC_TRANSLATION", "ecc_restart_message", "")
Global $ecc_traytooltip_message =	IniRead($ecc_translation_file, "ECC_TRANSLATION", "ecc_traytooltip_message", "")
Global $ecc_generateimages_busy = 	IniRead($ecc_translation_file, "ECC_TRANSLATION", "ecc_generateimages_busy", "")
Global $ecc_generateimages_process = 	IniRead($ecc_translation_file, "ECC_TRANSLATION", "ecc_generateimages_process", "")
Global $ecc_error_1001 =	IniRead($ecc_translation_file, "ECC_TRANSLATION", "ecc_error_1000", "")
Global $ecc_error_1004 =	IniRead($ecc_translation_file, "ECC_TRANSLATION", "ecc_error_1004", "")
Global $ecc_error_2002a =	IniRead($ecc_translation_file, "ECC_TRANSLATION", "ecc_error_2002a", "")
Global $ecc_error_2002b =	IniRead($ecc_translation_file, "ECC_TRANSLATION", "ecc_error_2002b", "")
Global $ecc_error_2005 =	IniRead($ecc_translation_file, "ECC_TRANSLATION", "ecc_error_2005", "")
Global $ecc_error_type1_a =	IniRead($ecc_translation_file, "ECC_TRANSLATION", "ecc_error_type1_a", "")
Global $ecc_error_type1_b =	IniRead($ecc_translation_file, "ECC_TRANSLATION", "ecc_error_type1_b", "")
Global $ecc_error_type1_c =	IniRead($ecc_translation_file, "ECC_TRANSLATION", "ecc_error_type1_c", "")
Global $ecc_error_report_title =	IniRead($ecc_translation_file, "ECC_TRANSLATION", "ecc_error_report_title", "")
Global $ecc_error_report =		IniRead($ecc_translation_file, "ECC_TRANSLATION", "ecc_error_report", "")
Global $ecc_startup_createuserfolder =	IniRead($ecc_translation_file, "ECC_TRANSLATION", "ecc_startup_createuserfolder", "")
Global $ecc_startup_configthirdparty =	IniRead($ecc_translation_file, "ECC_TRANSLATION", "ecc_startup_configthirdparty", "")
Global $ecc_startup_updateemulatorlist = IniRead($ecc_translation_file, "ECC_TRANSLATION", "ecc_startup_updateemulatorlist", "")
Global $ecc_startup_updatedatfiles = IniRead($ecc_translation_file, "ECC_TRANSLATION", "ecc_startup_updatedatfiles", "")
Global $ecc_3rdparty_JoyEmulator_start = IniRead($ecc_translation_file, "ECC_TRANSLATION", "ecc_startup_joyemulator", "")

;======================================
; *** CHECK INIT ***
;======================================
If StringRight(@Scriptname, 3) = "au3" Then ecc_error(1003) 				; Check if the filename is 'au3'
If StringRight(@Scriptname, 3) <> "exe" Then ecc_error(1001) 				; Check if the filename is 'exe'
If FileExists($ecc_php_file) <> 1 then ecc_error(2005) 						; Check if ECC PHP is found
If WinExists($ecc_splash_title) Then WinKill($ecc_splash_title) 			; Check if ECC Splashscreen is still there (it got stuck)
If FileExists($ecc_php_exe) <> 1 then ecc_error(2002) 						; Check files (check if php is there)

;======================================
; *** MAIN INIT ***
;======================================
Select

	Case $CmdLine[0] = 0
		ecc_splashscreen()
		ecc_joyemulatorstart()
		ecc_load()
		ecc_update_check()
		ecc_background()

	Case $CmdLine[1] = "/fastload"
		ecc_splashscreen_reload()
		ecc_joyemulatorstart()
		If WinExists($ecc_generated_title) Then
			WinClose($ecc_generated_title, "")
			WinWaitClose($ecc_generated_title, "", 5)
		EndIf
		$ecc_startup_soundplay = "0"
		ecc_load()
		GUIDelete()
		ecc_background()

	Case $CmdLine[1] = "/sndprev"
		SoundPlay($CmdLine[2], 1)

	Case $CmdLine[1] = "/getdriveinfo"
		ecc_getdriveinfo()

EndSelect

;======================================
; *** FUNCTION: SPLASHCREEN LOADER ***
;======================================
Func ecc_splashscreen()

;--------------------------------------
; Splashscreen GUIDATA (MAINGUI-JPG)
;--------------------------------------
$ecc_splash_window = GUICreate(chr(32) & $ecc_splash_title, $ecc_splash_image_width, $ecc_splash_image_height, -1, -1, $WS_POPUP, $WS_EX_LAYERED)
GuiSetIcon($ecc_splash_icon)
$ecc_splash_image_show = GUICtrlCreatePic($ecc_splash_image_file, 0, 0, $ecc_splash_image_width, $ecc_splash_image_height)
_GUICtrlCreateGIF($ecc_splash_movie_file, "", $ecc_splash_movielocation_left, $ecc_splash_movielocation_top)
$gui_regel = GuiCtrlCreateLabel("", $ecc_splash_textlocation_left, $ecc_splash_textlocation_top, 300, 20)
GUICtrlSetBkColor($gui_regel, $GUI_BKCOLOR_TRANSPARENT)
GUICtrlSetFont($gui_regel, 8, 400, "", "Verdana")
GUICtrlSetColor($gui_regel,$ecc_splash_text_color)
GuiCtrlSetData($gui_regel, $ecc_splash_message)

;--------------------------------------
; Splashscreen GUIDATA2 (MAINGUIBORDER-PNG)
;--------------------------------------
; Load PNG file as GDI bitmap
_GDIPlus_Startup()
$hImage = _GDIPlus_ImageLoadFromFile($ecc_splash_image_border_file)
; Extract image width and height from PNG
$width = _GDIPlus_ImageGetWidth($hImage)
$height = _GDIPlus_ImageGetHeight($hImage)
; Create layered window
$ecc_splash_window_border = GUICreate("splashborder", $width, $height, -1, -1, $WS_POPUP, $WS_EX_LAYERED, $ecc_splash_window)

; Set the main GUI and the PNG GUI transparancy to 0
SetBitmap($ecc_splash_window_border, $hImage, 0)
WinSetTrans($ecc_splash_window, "", 0)

;--------------------------------------
; Display splashscreen
;--------------------------------------
GUISetState(@SW_SHOW, $ecc_splash_window_border)
GUISetState(@SW_SHOW, $ecc_splash_window)

For $fade = 0 To $ecc_splash_image_fade_limit Step $ecc_splash_image_fade_step
Sleep($ecc_splash_image_fade_sleep)
	SetBitmap($ecc_splash_window_border, $hImage, $fade)
	WinSetTrans($ecc_splash_window, "", $fade)
Next

;--------------------------------------
; Install SIDE update? (for example a autoit3 update, wich cannot be done by eccUpdate)
;--------------------------------------

;eccUpdate instructions:

; [UPDATE_ACTION]
; ExtractFiles=1
; ExecuteFile=ecc.exe
; ForceExit=1

If FileExists(@ScriptDir & "\side_update.7z") Then
	GuiCtrlSetData($gui_regel, "Installing side update...")
	Sleep(2000) ;Give eccUpdate some time to exit
	ShellExecuteWait($ecc_3rdParty_7zip, " x side_update.7z -y", @ScriptDir, "", @SW_HIDE)
	FileDelete(@ScriptDir & "\side_update.7z")
EndIf

;--------------------------------------
; Check media images...generate if not exist
;--------------------------------------
$imagesearch = FileFindFirstFile(@ScriptDir & "\ecc-system\images\platform\*_teaser.*")
Dim $imagecount
Dim $totalimagecount
Dim $procent

; Count the images first
While 1
	$imagefile = FileFindNextFile($imagesearch)
	If @error Then ExitLoop

	$eccident=StringMid($imagefile, 5, Stringlen($imagefile)-15)
	If FileExists(@ScriptDir & "\ecc-system\images\platform\ecc_" & $eccident & "_media_a.png") = 0 Then $totalimagecount = $totalimagecount + 1
	If FileExists(@ScriptDir & "\ecc-system\images\platform\ecc_" & $eccident & "_media_i.png") = 0 Then $totalimagecount = $totalimagecount + 1
	If FileExists(@ScriptDir & "\ecc-system\images\platform\ecc_" & $eccident & "_cell_i.png") = 0 Then $totalimagecount = $totalimagecount + 1
	If FileExists(@ScriptDir & "\ecc-system\images\platform\ecc_" & $eccident & "_nav_i.png") = 0 Then $totalimagecount = $totalimagecount + 1
Wend


; Convert the images
$imagesearch = FileFindFirstFile(@ScriptDir & "\ecc-system\images\platform\*_teaser.*")

While 1

	$imagefile = FileFindNextFile($imagesearch)
	If @error Then ExitLoop

	$eccident=StringMid($imagefile, 5, Stringlen($imagefile)-15)

	If FileExists(@ScriptDir & "\ecc-system\images\platform\ecc_" & $eccident & "_media_a.png") = 0 Then
		$imagecount = $imagecount + 1
		$procent = Round((100 / $totalimagecount) * $imagecount, 1)
		$procent_2d = StringFormat("%.1f", $procent)
		GuiCtrlSetData($gui_regel, $ecc_generateimages_process & " " & $procent_2d & "%, eccid '" & $eccident & "'")
		ShellExecuteWait($ecc_php_exe_q, $ecc_php_ini_file & $ecc_image_converter_q & " ecc-system\images\platform\ecc_" & $eccident & "_teaser.png #008800 30 ecc-system\images\platform\ecc_" & $eccident & "_media_a.png 1", @ScriptDir, "", @SW_HIDE)
	EndIf

	If FileExists(@ScriptDir & "\ecc-system\images\platform\ecc_" & $eccident & "_media_i.png") = 0 Then
		$imagecount = $imagecount + 1
		$procent = Round((100 / $totalimagecount) * $imagecount, 1)
		$procent_2d = StringFormat("%.1f", $procent)
		GuiCtrlSetData($gui_regel, $ecc_generateimages_process & " " & $procent_2d & "%, eccid '" & $eccident & "'")
		ShellExecuteWait($ecc_php_exe_q, $ecc_php_ini_file & $ecc_image_converter_q & " ecc-system\images\platform\ecc_" & $eccident & "_teaser.png #880000 30 ecc-system\images\platform\ecc_" & $eccident & "_media_i.png 1", @ScriptDir, "", @SW_HIDE)
	EndIf

	If FileExists(@ScriptDir & "\ecc-system\images\platform\ecc_" & $eccident & "_cell_i.png") = 0 Then
		$imagecount = $imagecount + 1
		$procent = Round((100 / $totalimagecount) * $imagecount, 1)
		$procent_2d = StringFormat("%.1f", $procent)
		GuiCtrlSetData($gui_regel, $ecc_generateimages_process & " " & $procent_2d & "%, eccid '" & $eccident & "'")
		ShellExecuteWait($ecc_php_exe_q, $ecc_php_ini_file & $ecc_image_converter_q & " ecc-system\images\platform\ecc_" & $eccident & "_cell.png #880000 30 ecc-system\images\platform\ecc_" & $eccident & "_cell_i.png 1", @ScriptDir, "", @SW_HIDE)
	EndIf

	If FileExists(@ScriptDir & "\ecc-system\images\platform\ecc_" & $eccident & "_nav_i.png") = 0 Then
		$imagecount = $imagecount + 1
		$procent = Round((100 / $totalimagecount) * $imagecount, 1)
		$procent_2d = StringFormat("%.1f", $procent)
		GuiCtrlSetData($gui_regel, $ecc_generateimages_process & " " & $procent_2d & "%, eccid '" & $eccident & "'")
		ShellExecuteWait($ecc_php_exe_q, $ecc_php_ini_file & $ecc_image_converter_q & " ecc-system\images\platform\ecc_" & $eccident & "_nav.png #880000 30 ecc-system\images\platform\ecc_" & $eccident & "_nav_i.png 1", @ScriptDir, "", @SW_HIDE)
	EndIf

Wend

FileClose($imagesearch)

;--------------------------------------
; Check emulator ini...generate into navigation.ini if not exist (plugin-like)
;--------------------------------------
$EmuIniSearch = FileFindFirstFile(@ScriptDir & "\ecc-system\system\*_user.ini") ; Lookup emulator INI files

While 1

	$Inifile = FileFindNextFile($EmuIniSearch)
	If @error Then ExitLoop

	$eccidentraw=StringSplit($Inifile, "_")
	$eccident=$eccidentraw[2]

	If FileExists(@ScriptDir & "\ecc-system\system\ecc_" & $eccident & "_system.ini") = 1 Then ; Now we got the 2 most important 'platform' ini's
		If IniRead($ecc_navigation_ini, "NAVIGATION", $eccident, "0") = "0" Then
			GuiCtrlSetData($gui_regel, $ecc_startup_updateemulatorlist)
			IniWrite($ecc_navigation_ini, "NAVIGATION", $eccident, Chr(34) & "1" & Chr(34))
		EndIf
	Else
		GuiCtrlSetData($gui_regel, $ecc_startup_updateemulatorlist)
		IniWrite($ecc_navigation_ini, "NAVIGATION", $eccident, Chr(34) & "0" & Chr(34))
	EndIf

Wend

;--------------------------------------
; Check user folders...generate if not exist
;--------------------------------------
Global $eccnavini = IniReadSection($ecc_navigation_ini, "NAVIGATION")
DirCreate($ecc_base_path_full_fix) ; Create the userfolder (if it does not exists, ecc will fall back to '../ecc-user'

For $navcount = 1 To $eccnavini[0][0]
	If FileExists($ecc_base_path_full_fix & $eccnavini[$navcount][0] & "\emuControlCenter.txt") <> 1 Then ; Does the userfolder already exists??
		; The userfolder does not exists...is the platform set active??
		$user_config_ini_file = @ScriptDir & "\ecc-user-configs\ecc_" & $eccnavini[$navcount][0] & "_user.ini"
		If FileExists($user_config_ini_file) = 1 Then
			If IniRead($user_config_ini_file, "PLATFORM", "active", "") = 1 Then
				; Create userfolders if nessesary
				GuiCtrlSetData($gui_regel, $ecc_startup_createuserfolder)
				ShellExecuteWait($ecc_php_exe_q, $ecc_php_ini_file & $ecc_php_file_q & " create_userfolder", @ScriptDir, "", @SW_HIDE)
			EndIf
		Else
			; Create userfolders if nessesary
			GuiCtrlSetData($gui_regel, $ecc_startup_createuserfolder)
			ShellExecuteWait($ecc_php_exe_q, $ecc_php_ini_file & $ecc_php_file_q & " create_userfolder", @ScriptDir, "", @SW_HIDE)
		EndIf
	EndIf
Next

; $eccnavini[0][0] = INI key-range
; $eccnavini[$navcount][0] = INI key (eccident)
; $eccnavini[$navcount][1] = INI value (is eccident active? --> Not working, seems to have another function)
; NOTE: Values are read as RAW text, so "1" will be read as "1".

;--------------------------------------
; Configure 3rd party tools
;--------------------------------------
GuiCtrlSetData($gui_regel, $ecc_startup_configthirdparty)
ShellExecuteWait($ecc_3rdParty_autoit, Chr(34) & $ecc_tool_ThirdPartyConfig & Chr(34), $ecc_tools_path, "", @SW_HIDE)
GuiCtrlSetData($gui_regel, $ecc_splash_message)

;--------------------------------------
; Check if we need to unpack DATfiles
;--------------------------------------
$DATFileFound = 0
$FileList = _FileListToArray($ecc_datfile_folder, "*.7z")
$DATFileFound = Ubound($FileList)

If $DATFileFound > 0 Then
	GuiCtrlSetData($gui_regel, $ecc_startup_updatedatfiles)

	$Search = FileFindFirstFile($ecc_datfile_folder & "\*.7z")
	While 1
		If $Search = -1 Then
			ExitLoop
		EndIf

		$File = FileFindNextFile($Search)
		If @error Then ExitLoop

		;Unpack 7Z DAT archives
		ShellExecuteWait($ecc_3rdParty_7zip, " x " & Chr(34) & $ecc_datfile_folder & "\" & $File & Chr(34) & " -o" & Chr(34) & $ecc_datfile_folder & Chr(34) & " -y", @ScriptDir, "", @SW_HIDE)

		FileDelete($ecc_datfile_folder & "\" & $File)

	WEnd
	FileClose($Search)
EndIf
GuiCtrlSetData($gui_regel, $ecc_splash_message)


;--------------------------------------
; Check if we need to update DATfiles
;--------------------------------------
If FileExists($ecc_datfile_tempfile) Then
	GuiCtrlSetData($gui_regel, $ecc_startup_updatedatfiles)
	ShellExecuteWait($ecc_3rdParty_autoit, Chr(34) & $ecc_tool_DFU & Chr(34), $ecc_tools_path, "", @SW_HIDE)
EndIf
GuiCtrlSetData($gui_regel, $ecc_splash_message)

EndFunc ;ecc_splashscreen()


;======================================
; *** FUNCTION: ECC LOADER ***
;======================================
Func ecc_load()
Dim $foutteller=0
ShellExecute($ecc_php_exe_q, $ecc_php_ini_file & $ecc_php_file_q, @ScriptDir, "", @SW_HIDE)

While WinExists($ecc_window_title_catchname) <> 1
	; This is a counter to wait for the ECC window to come up, if it don't show's up in 60 seconds then an error has occured
	$foutteller = $foutteller + 1
	If $foutteller > 60 Then ecc_error(1004)
	If FileExists($ecc_phperror_log) = 1 Then ecc_error(1005) ; Check if there is an PHP errorlog, if yes...then quit immidiatly!
	Sleep(1000)
Wend

WinSetTitle($ecc_window_title_catchname, "", $ecc_generated_title)
GUIDelete($ecc_splash_window)
GUIDelete($ecc_splash_window_border)
WinGetHandle($ecc_generated_title, "")
WinSetState($ecc_generated_title, "", @SW_MAXIMIZE)
EndFunc

;============================================
; *** FUNCTION: SPLASHCREEN ECC RELOADER ***
;============================================
Func ecc_splashscreen_reload()

;--------------------------------------
; Splashscreen INIT
;--------------------------------------
$ecc_splash_window_reload = GUICreate(chr(32) & $ecc_restart_title, $ecc_splash_image_width, $ecc_splash_image_height, -1, -1, $WS_POPUP)
GuiSetIcon($ecc_splash_icon)
$ecc_splash_image_show = GUICtrlCreatePic($ecc_splash_image_file, 0, 0, $ecc_splash_image_width, $ecc_splash_image_height)
_GUICtrlCreateGIF($ecc_splash_movie_file, "", $ecc_splash_movielocation_left, $ecc_splash_movielocation_top)
$gui_regel = GuiCtrlCreateLabel("", $ecc_splash_textlocation_left, $ecc_splash_textlocation_top, 300, 20)
GUICtrlSetBkColor($gui_regel, $GUI_BKCOLOR_TRANSPARENT)
GUICtrlSetFont($gui_regel, 8, 400, "", "Verdana")
GUICtrlSetColor($gui_regel,$ecc_splash_text_color)
GuiCtrlSetData($gui_regel, $ecc_restart_message)

;--------------------------------------
; Splashscreen GUIDATA2 (MAINGUIBORDER-PNG)
;--------------------------------------
; Load PNG file as GDI bitmap
_GDIPlus_Startup()
$hImage = _GDIPlus_ImageLoadFromFile($ecc_splash_image_border_file)
; Extract image width and height from PNG
$width = _GDIPlus_ImageGetWidth($hImage)
$height = _GDIPlus_ImageGetHeight($hImage)
; Create layered window
$ecc_splash_window_border = GUICreate("splashborder", $width, $height, -1, -1, $WS_POPUP, $WS_EX_LAYERED, $ecc_splash_window_reload)

; Set the main GUI and the PNG GUI transparancy to 0
SetBitmap($ecc_splash_window_border, $hImage, 0)
WinSetTrans($ecc_splash_window_reload, "", 0)

;--------------------------------------
; Display splashscreen (reload)
;--------------------------------------
GUISetState(@SW_SHOW, $ecc_splash_window_border)
GUISetState(@SW_SHOW, $ecc_splash_window_reload)

For $fade = 0 To $ecc_splash_image_fade_limit Step $ecc_splash_image_fade_step
Sleep($ecc_splash_image_fade_sleep)
	SetBitmap($ecc_splash_window_border, $hImage, $fade)
	WinSetTrans($ecc_splash_window_reload, "", $fade)
Next

;--------------------------------------
; Configure 3rd party tools
;--------------------------------------
GuiCtrlSetData($gui_regel, $ecc_startup_configthirdparty)
ShellExecuteWait($ecc_3rdParty_autoit, Chr(34) & $ecc_tool_ThirdPartyConfig & Chr(34), $ecc_tools_path, "", @SW_HIDE)
GuiCtrlSetData($gui_regel, $ecc_splash_message)
EndFunc


;======================================
; *** FUNCTION: UPDATE CHECK ***
;======================================
Func ecc_update_check()
Global $ecc_startup_update_check = IniRead($ecc_config_file_general_user, "ECC_STARTUP", "startup_update_check", "")
If FileExists($ecc_tool_eccupdate) = 1 then
	If $ecc_startup_update_check = "1" Then ShellExecute($ecc_3rdParty_autoit, Chr(34) & $ecc_tool_eccupdate & Chr(34) & " /check", @ScriptDir & "\ecc-core\tools", "", @SW_HIDE)
EndIf
EndFunc


;======================================
; *** FUNCTION: ECC BACKGROUND ***
;======================================
Func ecc_background()
If $ecc_startup_soundplay = "1" Then SoundPlay($ecc_startup_sound, 0) ; Play intro sound

;--------------------------------------
; Set traytip settings
;--------------------------------------
If $ecc_startup_minimize_to_tray = 1 Then
	TraySetIcon($ecc_splash_icon)

	; Easter egg time? (tray icon)
	If $ecc_easteregg_month = @MON then
		If $ecc_easteregg_day = @MDAY then
			TraySetIcon($ecc_easteregg_icon)
		EndIf
	EndIf

	TraySetToolTip($ecc_traytooltip_message)
	TraySetOnEvent($TRAY_EVENT_PRIMARYDOUBLE,"restoremaineccwindow") ;doubleclick on the tray icon will run a function
	TraySetClick(8) ;activate traymenu with right click only
EndIf


; Loop until the ECC window does not exist anymore
Dim $eccWindowStatus
Dim $tijd

While WinExists($ecc_generated_title)
	$msg = TrayGetMsg()

	Select
       	Case $msg = 0

		If $ecc_startup_minimize_to_tray = 1 Then
			; Check if the ECC window is minimized
			$state = WinGetState($ecc_generated_title, "")
			; Is the "minimized" value set?
			If BitAND($state, 16) Then
				WinSetState($ecc_generated_title, "", @SW_HIDE)
				TraySetState(1)
			Else
				TraySetState(2)
			EndIf
		Else
			; This section is a better work-around around the GTK window bug
			$state = WinGetState($ecc_generated_title, "")

			If BitAND($state, 16) Then
				WinWaitActive($ecc_generated_title, "")
				If $eccWindowStatus = "maximized" Then WinSetState($ecc_generated_title, "", @SW_MAXIMIZE)
			EndIf

			If BitAND($state, 32) Then
				$eccWindowStatus = "maximized"
			Else
				$eccWindowStatus = ""
			EndIf

		EndIf

	Sleep(100)
	EndSelect
Wend

; Things todo when ECC closes..
If IniRead($ecc_config_file_general_user, "ECC_STARTUP", "delete_unpacked", "") = 1 Then DirRemove($ecc_base_path_full_fix & "#_AUTO_UNPACKED", 1) ; Purge the ECC unpacked cache folder on Exit
If $ecc_startup_JoyEmulator = "1" And ProcessExists($ecc_external_JoyEmulator_exe) Then ProcessClose($ecc_external_JoyEmulator_exe) ; Unload Joystick emulator.
Exit
EndFunc

;--------------------------------------
; Tray engine Sub function
;--------------------------------------
Func restoremaineccwindow()

$staterestore = WinGetState($ecc_generated_title, "")
If BitAnd($staterestore, 16) Then ;is the window minimized?
	WinSetState($ecc_generated_title, "", @SW_RESTORE)
	WinSetOnTop($ecc_generated_title, "", 1)
	WinSetOnTop($ecc_generated_title, "", 0)
EndIf

EndFunc

;--------------------------------------
; Get drive info function
;--------------------------------------
Func ecc_getdriveinfo()
Dim $DriveToGet, $GetDrives, $DriveOnly

If $CmdLine[0] < 2 Then
	; Get info of all drives
	;
	FileDelete($ecc_drive_get_info)
	$GetDrives = DriveGetDrive("ALL")
	If NOT @error Then
		For $i = 1 to $GetDrives[0]
			$DriveOnly = StringMid($GetDrives[$i], 1, 1)
			$DriveToGet = $DriveOnly & ":\"

			ConsoleWrite("[DRIVEINFO:" & $DriveOnly &"]")
			ConsoleWrite("DriveGetStatus=" & DriveStatus($DriveToGet))
			ConsoleWrite("DriveGetFileSystem=" & DriveGetFileSystem($DriveToGet))
			ConsoleWrite("DriveGetType=" & DriveGetType($DriveToGet))
			ConsoleWrite("DriveGetLabel=" & DriveGetLabel($DriveToGet))
			ConsoleWrite("DriveGetSerial=" & DriveGetSerial($DriveToGet))

			IniWrite($ecc_drive_get_info, "DRIVEINFO:" & $DriveOnly, "DriveGetStatus", DriveStatus($DriveToGet))
			IniWrite($ecc_drive_get_info, "DRIVEINFO:" & $DriveOnly, "DriveGetFileSystem", DriveGetFileSystem($DriveToGet))
			IniWrite($ecc_drive_get_info, "DRIVEINFO:" & $DriveOnly, "DriveGetType", DriveGetType($DriveToGet))
			IniWrite($ecc_drive_get_info, "DRIVEINFO:" & $DriveOnly, "DriveGetLabel", DriveGetLabel($DriveToGet))
			IniWrite($ecc_drive_get_info, "DRIVEINFO:" & $DriveOnly, "DriveGetSerial", DriveGetSerial($DriveToGet))
		Next
	EndIf
Else
	; Get info on one drive
	;
	FileDelete($ecc_drive_get_info)
	$DriveOnly = StringMid($CmdLine[2], 1, 1)
	$DriveToGet = $DriveOnly & ":\"

	ConsoleWrite("[DRIVEINFO:" & $DriveOnly &"]")
	ConsoleWrite("DriveGetStatus=" & DriveStatus($DriveToGet))
	ConsoleWrite("DriveGetFileSystem=" & DriveGetFileSystem($DriveToGet))
	ConsoleWrite("DriveGetType=" & DriveGetType($DriveToGet))
	ConsoleWrite("DriveGetLabel=" & DriveGetLabel($DriveToGet))
	ConsoleWrite("DriveGetSerial=" & DriveGetSerial($DriveToGet))

	IniWrite($ecc_drive_get_info, "DRIVEINFO:" & $DriveOnly, "DriveGetStatus", DriveStatus($DriveToGet))
	IniWrite($ecc_drive_get_info, "DRIVEINFO:" & $DriveOnly, "DriveGetFileSystem", DriveGetFileSystem($DriveToGet))
	IniWrite($ecc_drive_get_info, "DRIVEINFO:" & $DriveOnly, "DriveGetType", DriveGetType($DriveToGet))
	IniWrite($ecc_drive_get_info, "DRIVEINFO:" & $DriveOnly, "DriveGetLabel", DriveGetLabel($DriveToGet))
	IniWrite($ecc_drive_get_info, "DRIVEINFO:" & $DriveOnly, "DriveGetSerial", DriveGetSerial($DriveToGet))
EndIf

; Write some informations in the file
FileWriteLine ($ecc_drive_get_info, ";")
FileWriteLine ($ecc_drive_get_info, "; Informations:")
FileWriteLine ($ecc_drive_get_info, ";")
FileWriteLine ($ecc_drive_get_info, "; * DriveGetType can be: Unknown, Removable, Fixed, Network, CDROM, RAMDisk")
FileWriteLine ($ecc_drive_get_info, ";")
FileWriteLine ($ecc_drive_get_info, "; * DriveGetStatus can be:")
FileWriteLine ($ecc_drive_get_info, ";   UNKNOWN  > Drive may be unformatted (RAW).")
FileWriteLine ($ecc_drive_get_info, ";   READY    > Typical of hard drives and drives that contain removable media.")
FileWriteLine ($ecc_drive_get_info, ";   NOTREADY > Typical of floppy and CD drives that do not contain media. ")
FileWriteLine ($ecc_drive_get_info, ";   INVALID  > May indicate the drive letter does not exist or that a mapped network drive is inaccessible.")
FileWriteLine ($ecc_drive_get_info, ";")
FileWriteLine ($ecc_drive_get_info, "; * DriveGetFileSystem can be:")
FileWriteLine ($ecc_drive_get_info, ";   (number) > Drive does NOT contain media (CD, Floppy, Zip) or media is unformatted (RAW).")
FileWriteLine ($ecc_drive_get_info, ";   FAT      > Typical file system for drives under ~500 MB such as Floppy, RAM disks, USB 'pen' drives, etc.")
FileWriteLine ($ecc_drive_get_info, ";   FAT32    > Typical file system for Windows 9x/Me hard drives.")
FileWriteLine ($ecc_drive_get_info, ";   NTFS     > Typical file system for Windows NT/2000/XP hard drives.")
FileWriteLine ($ecc_drive_get_info, ";   NWFS     > Typical file system for Novell Netware file servers.")
FileWriteLine ($ecc_drive_get_info, ";   CDFS     > Typically indicates a CD (or an ISO image mounted as a virtual CD drive).")
FileWriteLine ($ecc_drive_get_info, ";   UDF      > Typically indicates a DVD.")
EndFunc

;======================================
; *** FUNCTION: ERROR HANDLER ***
;======================================
Func ecc_error($error_code)

; Remove the splashscreen GUI
GUIDelete($ecc_splash_window)
GUIDelete($ecc_splash_window_border)
$ecc_messagebox_title = $ecc_messagebox_title & " (" & $error_code & ")" ; Add errorcode to the title

Select

	Case 	$error_code = "1001"
		MsgBox(64, $ecc_messagebox_title, $ecc_error_1001)
	Case 	$error_code = "1002"
		MsgBox(16, $ecc_messagebox_title, $ecc_error_type1_a & @ScriptDir & "\" & @ScriptName & $ecc_error_type1_b)
	Case 	$error_code = "1003"
		MsgBox(16, $ecc_messagebox_title, "Please compile the source first and run emuControlCenter with ecc.exe")
		Exit
	Case 	$error_code = "1004"
		MsgBox(48, $ecc_messagebox_title, $ecc_error_1004)
	Case 	$error_code = "1005"

	MsgBox(48, $ecc_messagebox_title, $ecc_error_1004)
	#Region ### START Koda GUI section ###
	$eccErrorGUI = GUICreate($ecc_messagebox_title & " ERROR.LOG Contents:", 515, 180, -1, -1)
	GUISetCursor (0)
	$PHPErrorText = GUICtrlCreateEdit("", 0, 0, 513, 177, BitOR($ES_AUTOVSCROLL,$ES_READONLY,$ES_WANTRETURN,$WS_VSCROLL))
	GUICtrlSetFont(-1, 7, 400, 0, "Verdana")
	#EndRegion ### END Koda GUI section ###

	; Read PHP error.log
	$PHPErrorLogContents=""
	$PHPErrorLog = FileOpen($ecc_phperror_log, 0)
	$PHPErrorLogContents = $PHPErrorLogContents & FileRead($PHPErrorLog)
	FileClose($PHPErrorLog)
	; Change slashes to back-slashes otherwise the text in the textbox is not shown properly.
	$PHPErrorLogContents = StringReplace($PHPErrorLogContents, "\", "/")
	GUICtrlSetData($PHPErrorText, StringFormat($PHPErrorLogContents))
	GUISetState(@SW_SHOW, $eccErrorGUI)

	While 1
		$nMsg = GUIGetMsg($eccErrorGUI)
		Switch $nMsg
			Case $GUI_EVENT_CLOSE
				; Delete error.log files
				FileDelete($ecc_phperror_log)
				FileDelete($ecc_tool_diagnostics_report)
				Exit
		EndSwitch
	Wend

	Case 	$error_code = "2004"
		MsgBox(16, $ecc_messagebox_title, $ecc_error_type1_a & $ecc_splash_image_file & $ecc_error_type1_c)
	Case 	$error_code = "2005"
		MsgBox(16, $ecc_messagebox_title, $ecc_error_2005)
	Case 	$error_code = "2006"
		MsgBox(16, $ecc_messagebox_title, $ecc_error_type1_a & $ecc_teaser_image & $ecc_error_type1_c)
	Case 	$error_code = "2007"
		MsgBox(16, $ecc_messagebox_title, $ecc_error_type1_a & $ecc_splash_movie_file & $ecc_error_type1_c)
EndSelect

MsgBox(48, $ecc_error_report_title, $ecc_error_report)
Exit
Endfunc


; -------------------------------------
; Joystick Emulator start
; -------------------------------------
Func ecc_joyemulatorstart()

If $ecc_startup_JoyEmulator = "1" Then
	If FileExists($ecc_external_JoyEmulator) = 1 then
		GuiCtrlSetData($gui_regel, $ecc_3rdparty_JoyEmulator_start)
		ShellExecute($ecc_external_JoyEmulator, $ecc_external_JoyEmulator_param, "", "", @SW_MINIMIZE)
		ProcessWait($ecc_external_JoyEmulator_exe, 5)
	EndIf
EndIf

GuiCtrlSetData($gui_regel, $ecc_splash_message)

EndFunc


;======================================
; *** FUNCTION: SET BITMAP ***
;======================================
Func SetBitmap($hGUI, $hImage, $iOpacity)
    Local $hScrDC, $hMemDC, $hBitmap, $hOld, $pSize, $tSize, $pSource, $tSource, $pBlend, $tBlend

    $hScrDC = _WinAPI_GetDC(0)
    $hMemDC = _WinAPI_CreateCompatibleDC($hScrDC)
    $hBitmap = _GDIPlus_BitmapCreateHBITMAPFromBitmap($hImage)
    $hOld = _WinAPI_SelectObject($hMemDC, $hBitmap)
    $tSize = DllStructCreate($tagSIZE)
    $pSize = DllStructGetPtr($tSize)
    DllStructSetData($tSize, "X", _GDIPlus_ImageGetWidth($hImage))
    DllStructSetData($tSize, "Y", _GDIPlus_ImageGetHeight($hImage))
    $tSource = DllStructCreate($tagPOINT)
    $pSource = DllStructGetPtr($tSource)
    $tBlend = DllStructCreate($tagBLENDFUNCTION)
    $pBlend = DllStructGetPtr($tBlend)
    DllStructSetData($tBlend, "Alpha", $iOpacity)
    DllStructSetData($tBlend, "Format", $AC_SRC_ALPHA)
    _WinAPI_UpdateLayeredWindow($hGUI, $hScrDC, 0, $pSize, $hMemDC, $pSource, 0, $pBlend, $ULW_ALPHA)
    _WinAPI_ReleaseDC(0, $hScrDC)
    _WinAPI_SelectObject($hMemDC, $hOld)
    _WinAPI_DeleteObject($hBitmap)
    _WinAPI_DeleteDC($hMemDC)
EndFunc   ;==>SetBitmap