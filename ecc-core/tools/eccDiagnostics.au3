; ------------------------------------------------------------------------------
; emuControlCenter eccDiagnostics (ECC-DIAG)
;
$ScriptVersion = "1.0.0.1"
; Last changed           : 2012.05.06
;
;
; Author: Sebastiaan Ebeltjes (aka Phoenix)
; Code contributions:
;
; NOTES: Nothing yet ;-)
;
; ------------------------------------------------------------------------------
FileChangeDir(@ScriptDir)

#include "..\thirdparty\autoit\include\ButtonConstants.au3"
#include "..\thirdparty\autoit\include\GUIConstantsEx.au3"
#include "..\thirdparty\autoit\include\GUIListBox.au3"
#include "..\thirdparty\autoit\include\StaticConstants.au3"
#include "..\thirdparty\autoit\include\WindowsConstants.au3"
#include "..\thirdparty\autoit\include\GuiRichEdit.au3"

;==============================================================================
;BEGIN *** CHECK & VALIDATE
;==============================================================================
$eccPath = StringReplace(@Scriptdir, "\ecc-core\tools", "")
Global $eccphpErrorLog = $eccPath & "\error.log"
Global $eccStartupIni = $eccPath & "\ecc-core\php-gtk2\php.ini"
Global $eccGeneralIni = $eccPath & "\ecc-user-configs\config\ecc_general.ini"
Global $eccHistoryIni = $eccPath & "\ecc-user-configs\config\ecc_history.ini"
Global $eccDATfileIni = $eccPath & "\ecc-system\system\info\ecc_local_datfile_info.ini"
Global $eccVersionInfoIni = $eccPath & "\ecc-system\system\info\ecc_local_version_info.ini"
Global $eccUpdateInfoIni = $eccPath & "\ecc-system\system\info\ecc_local_update_info.ini"
Global $eccHostInfoIni = $eccPath & "\ecc-system\system\info\ecc_local_host_info.ini"
Global $eccUserCidFile = $eccPath & "\ecc-system\idt\cicheck.idt"
Global $NotepadExe = $eccPath & "\ecc-core\thirdparty\notepad++\notepad++.exe"
Global $BackgroundRun = 0

If FileExists($eccPath & "\ecc.exe") <> 1 or FileExists($eccPath & "\ecc-system\ecc.php") <> 1 Then
	MsgBox(64,"ECC Diagnostics", "No ECC software found!, aborting...")
	Exit
EndIf
;==============================================================================
;END *** CHECK & VALIDATE
;==============================================================================

;==============================================================================
;BEGIN *** BACKGROUND RUN SETTING?
;==============================================================================
If $CmdLine[0] > 0 Then
	If $CmdLine[1] = "/background" Then $BackgroundRun = 1
EndIf
;==============================================================================
;END *** BACKGROUND RUN SETTING?
;==============================================================================

;==============================================================================
;BEGIN *** GUI
;==============================================================================
$eccDiagnostics = GUICreate("eccDiagnostics", 565, 472, -1, -1, BitOR($WS_MINIMIZEBOX,$WS_SYSMENU,$WS_GROUP))
GUISetBkColor(0xFFFFFF)
$TabPages = GUICtrlCreateTab(0, 0, 561, 401, 0)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
$MainInfo = GUICtrlCreateTabItem("Main info")
$MainInfoText = GUICtrlCreateEdit("", 8, 31, 545, 361, BitOR($GUI_SS_DEFAULT_EDIT,$ES_READONLY))
GUICtrlSetFont(-1, 9, 400, 0, "Courier New")
$PhpErrorLog = GUICtrlCreateTabItem("PHP error log")
$PhpErrorLogText = GUICtrlCreateEdit("", 8, 31, 545, 361, BitOR($GUI_SS_DEFAULT_EDIT,$ES_READONLY))
GUICtrlSetFont(-1, 9, 400, 0, "Courier New")
$StartupIni = GUICtrlCreateTabItem("Startup INI")
$StartupIniText = GUICtrlCreateEdit("", 8, 31, 545, 361, BitOR($GUI_SS_DEFAULT_EDIT,$ES_READONLY))
GUICtrlSetFont(-1, 9, 400, 0, "Courier New")
$GeneralIni = GUICtrlCreateTabItem("General INI")
$GeneralIniText = GUICtrlCreateEdit("", 8, 31, 545, 361, BitOR($GUI_SS_DEFAULT_EDIT,$ES_READONLY))
GUICtrlSetFont(-1, 9, 400, 0, "Courier New")
$HistoryIni = GUICtrlCreateTabItem("History INI")
$HistoryIniText = GUICtrlCreateEdit("", 8, 31, 545, 361, BitOR($GUI_SS_DEFAULT_EDIT,$ES_READONLY))
GUICtrlSetFont(-1, 9, 400, 0, "Courier New")
$DatInfo = GUICtrlCreateTabItem("DAT info")
$DatInfoText = GUICtrlCreateEdit("", 8, 31, 545, 361, BitOR($GUI_SS_DEFAULT_EDIT,$ES_READONLY))
GUICtrlSetFont(-1, 9, 400, 0, "Courier New")
$VersionInfo = GUICtrlCreateTabItem("Version Info")
$VersionInfoText = GUICtrlCreateEdit("", 8, 31, 545, 361, BitOR($GUI_SS_DEFAULT_EDIT,$ES_READONLY))
GUICtrlSetFont(-1, 9, 400, 0, "Courier New")
GUICtrlCreateTabItem("")
$ButtonDumpFile = GUICtrlCreateButton("DUMP TO FILE", 408, 408, 147, 33, $BS_NOTIFY)
GUICtrlSetState(-1, $GUI_DISABLE)
;==============================================================================
;END *** GUI
;==============================================================================
GUISetIcon (@ScriptDir & "\eccDiagnostics.ico", "", $eccDiagnostics) ;Set proper icon for the window.
If $BackgroundRun = 0 Then GUISetState(@SW_SHOW)

;User CID info ================================================================
Global $iEccUserCidFile
$oEccUserCidFile = FileOpen($eccUserCidFile, 0) ; Open file.
If $oEccUserCidFile = 1 Then $iEccUserCidFile = FileRead($oEccUserCidFile) ; Read-in data from file.
FileClose($oEccUserCidFile) ; Close the file.
;User CID info ================================================================

;Main Info ================================================================
If $BackgroundRun = 0 Then ToolTip("Gathering Main Info...", @DesktopWidth/2, @DesktopHeight/2, "eccDiagnostics", 1, 6)
Global $MainInfoContents
$MainInfoContents = $MainInfoContents & "eccDiagnostics v" & $ScriptVersion & Chr(13) & Chr(10)
$MainInfoContents = $MainInfoContents & Chr(13) & Chr(10)
$MainInfoContents = $MainInfoContents & "This report is created on: " & @MDAY & "-" & @MON & "-" & @YEAR & " / " & @HOUR & ":" & @MIN & " (dd-mm-yyyy / hh:mm)" & Chr(13) & Chr(10)
$MainInfoContents = $MainInfoContents & Chr(13) & Chr(10)
$MainInfoContents = $MainInfoContents & "[EMUCONTROLCENTER INFO]" & Chr(13) & Chr(10)
$MainInfoContents = $MainInfoContents & "Version  : " & IniRead($eccVersionInfoIni, "GENERAL", "current_version", "x.x.x") & " "
$MainInfoContents = $MainInfoContents & "build: " & IniRead($eccVersionInfoIni, "GENERAL", "current_build", "x.x") & " "
$MainInfoContents = $MainInfoContents & "(" & IniRead($eccVersionInfoIni, "GENERAL", "date_build", "xxxx.xx.xx") & ") "
$MainInfoContents = $MainInfoContents & "upd: " & IniRead($eccUpdateInfoIni, "UPDATE", "last_update", "xxxxx") & Chr(13) & Chr(10)
$MainInfoContents = $MainInfoContents & "Startup  : v" & FileGetVersion($eccPath & "\ecc.exe") & Chr(13) & Chr(10)
$MainInfoContents = $MainInfoContents & "Core     : ECC is using PHP v" & FileGetVersion($eccPath & "\ecc-core\php-gtk2\php5.dll") & " and "
$MainInfoContents = $MainInfoContents & "GTK v" & FileGetVersion($eccPath & "\ecc-core\php-gtk2\libgtk-win32-2.0-0.dll") & Chr(13) & Chr(10)
$MainInfoContents = $MainInfoContents & "User CID : " & $iEccUserCidFile  & Chr(13) & Chr(10)
$MainInfoContents = $MainInfoContents & Chr(13) & Chr(10)
$MainInfoContents = $MainInfoContents & "[LOCAL ENVIRONMENT]" & Chr(13) & Chr(10)
$MainInfoContents = $MainInfoContents & "Processor(s) : " & IniRead($eccHostInfoIni, "ECC_HOST_INFO", "NUMBER_OF_PROCESSORS", "----") & " processor(s) "
$MainInfoContents = $MainInfoContents & "(" & IniRead($eccHostInfoIni, "ECC_HOST_INFO", "PROCESSOR_ARCHITECTURE", "unknown") & ")" & Chr(13) & Chr(10)
$MainInfoContents = $MainInfoContents & "        type : " & IniRead($eccHostInfoIni, "ECC_HOST_INFO", "PROCESSOR_IDENTIFIER", "unknown") & Chr(13) & Chr(10)
$MainInfoContents = $MainInfoContents & "OperatingSys : ECC is running on " & IniRead($eccHostInfoIni, "ECC_HOST_INFO", "OS", "?") & " "
$MainInfoContents = $MainInfoContents & "(" & IniRead($eccHostInfoIni, "ECC_HOST_INFO", "OS_TYPE", "?") & ")" & Chr(13) & Chr(10)
GUICtrlSetData($MainInfoText, $MainInfoContents)
;Main Info ================================================================

;PHP error log ================================================================
If $BackgroundRun = 0 Then ToolTip("Gathering PHP error log Info...", @DesktopWidth/2, @DesktopHeight/2, "eccDiagnostics", 1, 6)
$oEccPhpErrorLog = FileOpen($eccphpErrorLog, 0) ; Open file.
If $oEccPhpErrorLog = -1 Then ; Check if file opened for reading OK.
	$iEccPhpErrorLog = "No PHP error has occured!" ; File is not found
	GUICtrlSetData($PhpErrorLogText, $iEccPhpErrorLog) ; Put data into the editbox.
Else
	$iEccPhpErrorLog = FileRead($oEccPhpErrorLog) ; Read-in data from file.
	GUICtrlSetData($PhpErrorLogText, $iEccPhpErrorLog) ; Put data into the editbox.
	FileClose($oEccPhpErrorLog) ; Close the file.
EndIf
;PHP error log ================================================================

;Startup INI ==================================================================
If $BackgroundRun = 0 Then ToolTip("Gathering Startup INI Info...", @DesktopWidth/2, @DesktopHeight/2, "eccDiagnostics", 1, 6)
$oEccStartupIni = FileOpen($eccStartupIni, 0) ; Open file.
If $oEccStartupIni = -1 Then ; Check if file opened for reading OK.
	GUICtrlSetData($StartupIniText, "The file '" & $eccStartupIni & " could not be found!") ; File is not found
Else
	$iEccStartupIni = FileRead($oEccStartupIni) ; Read-in data from file.
	GUICtrlSetData($StartupIniText, $iEccStartupIni) ; Put data into the editbox.
	FileClose($oEccStartupIni) ; Close the file.
EndIf
;Startup INI ==================================================================

;General INI ==================================================================
If $BackgroundRun = 0 Then ToolTip("Gathering General INI Info...", @DesktopWidth/2, @DesktopHeight/2, "eccDiagnostics", 1, 6)
$oEccGeneralIni = FileOpen($eccGeneralIni, 0) ; Open file.
If $oEccGeneralIni = -1 Then ; Check if file opened for reading OK.
	GUICtrlSetData($GeneralIniText, "The file '" & $eccGeneralIni & " could not be found!") ; File is not found
Else
	$iEccGeneralIni = FileRead($oEccGeneralIni) ; Read-in data from file.
	; Add a "Carriage Return" DEC(13) before the linefeed characters (linux: HEX 0A) to get a "Windows" format with proper linebreaks!
    $iEccGeneralIni = StringReplace($iEccGeneralIni, Chr(10), Chr(13) & Chr(10))
	GUICtrlSetData($GeneralIniText, $iEccGeneralIni) ; Put data into the editbox.
	FileClose($oEccGeneralIni) ; Close the file.
EndIf
;General INI ==================================================================

;History INI ==================================================================
If $BackgroundRun = 0 Then ToolTip("Gathering History INI Info...", @DesktopWidth/2, @DesktopHeight/2, "eccDiagnostics", 1, 6)
$oEccHistoryIni = FileOpen($eccHistoryIni, 0) ; Open file.
If $oEccHistoryIni = -1 Then ; Check if file opened for reading OK.
	GUICtrlSetData($GeneralIniText, "The file '" & $eccHistoryIni & " could not be found!") ; File is not found
Else
	$iEccHistoryIni = FileRead($oEccHistoryIni) ; Read-in data from file.
	; Add a "Carriage Return" DEC(13) before the linefeed characters (linux: HEX 0A) to get a "Windows" format with proper linebreaks!
    $iEccHistoryIni = StringReplace($iEccHistoryIni, Chr(10), Chr(13) & Chr(10))
	GUICtrlSetData($HistoryIniText, $iEccHistoryIni) ; Put data into the editbox.
	FileClose($oEccHistoryIni) ; Close the file.
EndIf
;History INI ==================================================================

;DAT Info ==================================================================
If $BackgroundRun = 0 Then ToolTip("Gathering DATfile INI Info...", @DesktopWidth/2, @DesktopHeight/2, "eccDiagnostics", 1, 6)
$oEccDATfileIni = FileOpen($eccDATfileIni, 0) ; Open file.
If $oEccDATfileIni = -1 Then ; Check if file opened for reading OK.
	GUICtrlSetData($GeneralIniText, "The file '" & $eccDATfileIni & " could not be found!") ; File is not found
Else
	$iEccDATfileIni = FileRead($oEccDATfileIni) ; Read-in data from file.
	GUICtrlSetData($DatInfoText, $iEccDATfileIni) ; Put data into the editbox.
	FileClose($oEccDATfileIni) ; Close the file.
EndIf
;DAT Info ==================================================================

;Version Info ==================================================================
If $BackgroundRun = 0 Then ToolTip("Gathering Version Info...", @DesktopWidth/2, @DesktopHeight/2, "eccDiagnostics", 1, 6)
Global $FoundFileVersions, $String
GUICtrlSetData($VersionInfoText, "Scanning...") ; Put data into the editbox.
$GetVersions = ScanFolder($eccPath)
GUICtrlSetData($VersionInfoText, $GetVersions) ; Put data into the editbox.
;Version Info ==================================================================

If $BackgroundRun = 0 Then ToolTip("") ; Remove Tooltip
GuiCtrlSetstate($ButtonDumpFile, $GUI_ENABLE) ; Enable the DUMP to file button

If $BackgroundRun = 1 Then ;When running in background dump file and exit!
	DumpFile()
	Exit
EndIf

While 1
	$nMsg = GUIGetMsg()
	Switch $nMsg
		Case $GUI_EVENT_CLOSE ; When the cross in the right-top corner is pressed
			Exit
		Case $ButtonDumpFile
			DumpFile()
			GuiCtrlSetstate($ButtonDumpFile, $GUI_DISABLE) ; Disable the DUMP to file button.

			Switch MsgBox(52, "ECC Diagnostics", "File '" & @ScriptDir & "\eccDiagnostics.txt' created, " & @CRLF & "would you like to view it now?")
			Case 6 ; YES
				If FileExists($NotepadExe) Then ShellExecute($NotepadExe, @ScriptDir & "\eccDiagnostics.txt")
				Exit
			Case 7 ; NO
				Exit
			EndSwitch
	EndSwitch
WEnd
Exit

Func DumpFile()
Global $TotalDump
$TotalDump = $TotalDump & "Main Info ================================================================" & Chr(13) & Chr(10)
$TotalDump = $TotalDump & $MainInfoContents & Chr(13) & Chr(10)
$TotalDump = $TotalDump & Chr(13) & Chr(10)

$TotalDump = $TotalDump & "PHP error log ============================================================" & Chr(13) & Chr(10)
$TotalDump = $TotalDump & $iEccPhpErrorLog & Chr(13) & Chr(10)
$TotalDump = $TotalDump & Chr(13) & Chr(10)

$TotalDump = $TotalDump & "Startup INI ==============================================================" & Chr(13) & Chr(10)
$TotalDump = $TotalDump & $iEccStartupIni & Chr(13) & Chr(10)
$TotalDump = $TotalDump & Chr(13) & Chr(10)

$TotalDump = $TotalDump & "General INI ==============================================================" & Chr(13) & Chr(10)
$TotalDump = $TotalDump & $iEccGeneralIni & Chr(13) & Chr(10)
$TotalDump = $TotalDump & Chr(13) & Chr(10)

$TotalDump = $TotalDump & "History INI ==============================================================" & Chr(13) & Chr(10)
$TotalDump = $TotalDump & $iEccHistoryIni & Chr(13) & Chr(10)
$TotalDump = $TotalDump & Chr(13) & Chr(10)

$TotalDump = $TotalDump & "DAT Info =================================================================" & Chr(13) & Chr(10)
$TotalDump = $TotalDump & $iEccDATfileIni & Chr(13) & Chr(10)
$TotalDump = $TotalDump & Chr(13) & Chr(10)

$TotalDump = $TotalDump & "Version Info =============================================================" & Chr(13) & Chr(10)
$TotalDump = $TotalDump & $GetVersions & Chr(13) & Chr(10)

$DumpFile = FileOpen(@ScriptDir & "\eccDiagnostics.txt", 10)
FileWrite($DumpFile, $TotalDump)
FileClose($DumpFile)
EndFunc ;DumpFile

Func ScanFolder($SourceFolder)
$Search = FileFindFirstFile($SourceFolder & "\*.*")

While 1
	If $Search = -1 Then
		ExitLoop
	EndIf

	$File = FileFindNextFile($Search)
	If @error Then ExitLoop
	$FullFilePath = $SourceFolder & "\" & $File
	$FileAttributes = FileGetAttrib($FullFilePath)

	If StringInStr($FileAttributes, "D") Then ; Is this a folder?
		ScanFolder($FullFilePath)
	Else
		If FileGetVersion($FullFilePath) <> "0.0.0.0" Then ; Legit file wich contains a fileversion
			If StringRight($File, 3) = "exe" Or StringRight($File, 3) = "ocx" Or StringRight($File, 3) = "dll" Then ; Only EXE / OCX / DLL files
				$FoundFileVersions = $FoundFileVersions & StringSpace(StringReplace($FullFilePath, $eccPath & "\", "")) & " v" & FileGetVersion($FullFilePath) & Chr(13) & Chr(10)
			EndIf
		EndIf
	EndIf

WEnd
FileClose($Search)
Return $FoundFileVersions
EndFunc ;ScanFolder

Func StringSpace($String)
Do
	$String = $String & " "
Until StringLen($String) >= 60
Return $String
EndFunc ; StringSpace