; ------------------------------------------------------------------------------
; Script for             : emuDownloadCenter (EDC)
; Script version         : 1.0.0.1
Global $EDCScriptVersion = "1.0.0.1"
; Last changed           : 2016.12.27
;
; Author: Sebastiaan Ebeltjes (AKA Phoenix)
;
; NOTES:
;
; ------------------------------------------------------------------------------
FileChangeDir(@ScriptDir)
#include "eccToolVariables.au3"

Global $SelectedEmulatorShortName, $UserClickedAnother, $OldVersion, $OldEmulator

Global Const $STD_INPUT_HANDLE = -10
Global Const $STD_OUTPUT_HANDLE = -11
Global Const $STD_ERROR_HANDLE = -12
Global Const $_CONSOLE_SCREEN_BUFFER_INFO = "short dwSizeX; short dwSizeY;short dwCursorPositionX; short dwCursorPositionY; short wAttributes;short Left; short Top; short Right; short Bottom; short dwMaximumWindowSizeX; short dwMaximumWindowSizeY"
Global Const $_COORD = "short X; short Y"
Global Const $_CHAR_INFO = "wchar UnicodeChar; short Attributes"
Global Const $_SMALL_RECT = "short Left; short Top; short Right; short Bottom"
Global Const $K32 = "Kernel32.dll", $INT = "int", $HWN = "hwnd", $PTR = "ptr", $DWO = "dword"

Opt("WinTitleMatchMode", 3) ;1=start, 2=subStr, 3=exact, 4=advanced, -1 to -4=Nocase.

;======================================
; *** CHECK INIT ***
;======================================
;Check if media is writable.
$CreateCacheFolder = DirCreate($EDCFolderCache)
If $CreateCacheFolder = 0 Then
	ToolTip("You can only use EDC on writable media!", @DesktopWidth/2, @DesktopHeight/2, "EDC", 1, 6)
	Sleep(2000)
	Exit
EndIf

;Check if there is already an instance of EDC running
If WinExists("ECC - emuDownloadCenter") Then
	WinActivate("ECC - emuDownloadCenter", "")
	Exit
EndIf

;Exit if user wants to download from the ECC menu "ALL PLATFORMS", this is not possible, $RomEccId = "".
If $RomEccId = "" Then
	ToolTip("You cannot download emulators for ALL platforms at once!", @DesktopWidth/2, @DesktopHeight/2, "EDC", 1, 6)
	Sleep(2000)
	Exit
EndIf

;Exit if no active (internet)connection is found!
$connect = _GetNetworkConnect()
If Not $connect Then
	ToolTip("You need an active (internet)connection to use EDC!", @DesktopWidth/2, @DesktopHeight/2, "EDC", 1, 6)
	Sleep(2000)
	Exit
EndIf

ToolTip("Downloading platform info...", @DesktopWidth/2, @DesktopHeight/2, "EDC", 1, 6)
InetGet($EDCServerEmulatorList, $EDCEmulatorListINI) ;Download the emulator list to cache.
InetGet($EDCServerStatistics, $EDCStatisticsINI) ;Download the statistics ini to cache.
Global $PlatformEmulators = IniReadSection($EDCEmulatorListINI, $RomEccId) ;Read ECCID INI section (emulators for platform).
Sleep(500)
ToolTip("")

; Exit if there are no emulators avaialble for this platform.
If UBound($PlatformEmulators) = 0 Then
	ToolTip("There are no emulators avaialable for this platform (yet)!", @DesktopWidth/2, @DesktopHeight/2, "EDC", 1, 6)
	Sleep(2000)
	Exit
EndIf
;======================================
; *** CHECK INIT ***
;======================================

EmuSelect()

Func EmuSelect()
; -----------------------------------------------------------------------------------------
; EMULATOR SELECT GUI
; -----------------------------------------------------------------------------------------
Global $EDCEMULATOR = GUICreate("ECC - emuDownloadCenter", 763, 734, -1, -1)
GUISetBkColor(0xFFFFFF)
Global $SelectEmulatorGroup = GUICtrlCreateGroup(" Select emulator ", 8, 0, 745, 505, BitOR($GUI_SS_DEFAULT_GROUP,$BS_CENTER))
GUICtrlSetFont(-1, 9, 800, 2, "Verdana")
Global $EmulatorList = GUICtrlCreateList("", 16, 16, 201, 454, $GUI_SS_DEFAULT_LIST)
GUICtrlSetBkColor(-1, 0xA6CAF0)
Global $Label1 = GUICtrlCreateLabel("Preview image:", 224, 15, 93, 17, 0)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $EmulatorImage = GUICtrlCreatePic("", 224, 32, 524, 444, 0)
Global $EmulatorWebsite = GUICtrlCreateLabel("", 280, 480, 466, 17, 0)
GUICtrlSetFont(-1, 8, 800, 6, "Verdana")
GUICtrlSetColor(-1, 0x0000FF)
GUICtrlSetCursor (-1, 0)
Global $Label3 = GUICtrlCreateLabel("Website:", 218, 480, 55, 17, $SS_RIGHT)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $FavoriteButton = GUICtrlCreateButton("SET", 176, 472, 41, 25)
Global $Label9 = GUICtrlCreateLabel("FAV:", 10, 472, 30, 17, $SS_RIGHT)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $FavoriteEmulator = GUICtrlCreateLabel("-", 48, 472, 122, 25, 0)
GUICtrlSetFont(-1, 8, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x0000FF)
GUICtrlCreateGroup("", -99, -99, 1, 1)
Global $ButtonSelect = GUICtrlCreateButton("SELECT", 632, 696, 121, 33)
GUICtrlSetFont(-1, 10, 800, 0, "MS Sans Serif")
Global $EmulatorInfoGroup = GUICtrlCreateGroup(" Emulator info ", 8, 512, 745, 177, BitOR($GUI_SS_DEFAULT_GROUP,$BS_CENTER))
GUICtrlSetFont(-1, 9, 800, 2, "Verdana")
Global $Label4 = GUICtrlCreateLabel("Author(s):", 10, 528, 78, 17, $SS_RIGHT)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $Label5 = GUICtrlCreateLabel("License:", 10, 544, 78, 17, $SS_RIGHT)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $Label6 = GUICtrlCreateLabel("Need BIOS?:", 10, 560, 78, 17, $SS_RIGHT)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $EmulatorAuthor = GUICtrlCreateLabel("-", 96, 528, 258, 17, 0)
GUICtrlSetFont(-1, 8, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x0000FF)
Global $EmulatorLicense = GUICtrlCreateLabel("-", 96, 544, 258, 17, 0)
GUICtrlSetFont(-1, 8, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x0000FF)
Global $EmulatorBios = GUICtrlCreateLabel("-", 96, 560, 98, 17, 0)
GUICtrlSetFont(-1, 8, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x0000FF)
Global $Label8 = GUICtrlCreateLabel("Notes:", 368, 528, 47, 17, 0)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $EmulatorNotes = GUICtrlCreateLabel("-", 368, 544, 378, 129, 0)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
GUICtrlSetColor(-1, 0x0000FF)
Global $Label2 = GUICtrlCreateLabel("Last Check:", 10, 584, 78, 17, $SS_RIGHT)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $EmulatorLastCheck = GUICtrlCreateLabel("-", 96, 584, 98, 17, 0)
GUICtrlSetFont(-1, 8, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x0000FF)
Global $Label7 = GUICtrlCreateLabel("Complete?:", 10, 600, 78, 17, $SS_RIGHT)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $EmulatorComplete = GUICtrlCreateLabel("-", 96, 600, 98, 17, 0)
GUICtrlSetFont(-1, 8, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x0000FF)
Global $EmulatorLogo = GUICtrlCreatePic("", 96, 624, 161, 57, BitOR($GUI_SS_DEFAULT_PIC,$SS_CENTERIMAGE))
GUICtrlSetResizing(-1, $GUI_DOCKHEIGHT)
GUICtrlCreateGroup("", -99, -99, 1, 1)
Global $ButtonExit = GUICtrlCreateButton("EXIT", 8, 696, 121, 33)
GUICtrlSetFont(-1, 10, 800, 0, "MS Sans Serif")
Global $EDCInfoLabel = GUICtrlCreateLabel("", 160, 696, 448, 17, $SS_CENTER)
GUICtrlSetFont(-1, 8, 800, 2, "Verdana")
GUICtrlSetColor(-1, 0x0000FF)
Global $EDCLink = GUICtrlCreateLabel("Open EDC Project website", 374, 716, 234, 17, $SS_CENTER)
GUICtrlSetFont(-1, 8, 800, 6, "Verdana")
GUICtrlSetColor(-1, 0x0000FF)
GUICtrlSetCursor (-1, 0)
Global $EDCUpdate = GUICtrlCreateLabel("Last EDC update: Unknown", 160, 716, 212, 17, $SS_CENTER)
GUICtrlSetFont(-1, 8, 800, 2, "Verdana")
GUICtrlSetColor(-1, 0x800000)
; -----------------------------------------------------------------------------------------
; EMULATOR SELECT GUI
; -----------------------------------------------------------------------------------------

GUISetIcon(@ScriptDir & "\emuDownloadCenter.ico", "", $EDCEMULATOR) ;Set proper icon for the window.
GUICtrlSetData($SelectEmulatorGroup, " Select emulator (" & $RomPlatformName & ") ") ;Set the platform in the group.
GUICtrlSetData($EDCInfoLabel, "emuDownloadCenter v" & $EDCScriptVersion) ;Set version in the infolabel.
GUICtrlSetData($EDCUpdate, "Last EDC update: " & IniRead($EDCFolderCache & "edc_statistics.ini", "INFO", "LastUpdate", "Unknown")) ;Set the last update in the infolabel.

GUISetState(@SW_SHOW, $EDCEMULATOR) ;Show the emulator GUI.

; Fill the emulator list.
GUICtrlSetData($EmulatorList, "") ;Clear the list before adding new items to it.
For $i = 1 To $PlatformEmulators[0][0]
	GUICtrlSetData($EmulatorList, $PlatformEmulators[$i][1])
Next

;Set favorite selection.
Global $EmuFav = IniRead($EDCFavoriteINI, "FAVORITE", $RomEccId, "")
Global $FavEmulatorName = "Not set!"
If $EmuFav = "" Then
	;Always select the first emulator, this way at least something is selected.
	_GUICtrlListBox_SetCurSel($EmulatorList, 0)
Else
	For $i = 1 To $PlatformEmulators[0][0]
		If $EmuFav = $PlatformEmulators[$i][0] Then
			$FavEmulatorName = $PlatformEmulators[$i][1]
			ExitLoop
		EndIf
	Next
	_GUICtrlListBox_SelectString($EmulatorList, $FavEmulatorName)
EndIf
GUICtrlSetData($FavoriteEmulator, $FavEmulatorName)

UpdateEmulatorData()

While 1
	$nMsg = GUIGetMsg()
	Switch $nMsg
		Case $GUI_EVENT_CLOSE
			Exit

		Case $ButtonExit
			Exit

		Case $FavoriteButton
			Global $SelectedEmulator = GUICtrlRead($EmulatorList, 1)
			For $i = 1 To $PlatformEmulators[0][0]
				If $SelectedEmulator = $PlatformEmulators[$i][1] Then
					$SelectedEmulatorShortName = $PlatformEmulators[$i][0]
					ExitLoop
				EndIf
			Next
			IniWrite($EDCFavoriteINI, "FAVORITE", $RomEccId, $SelectedEmulatorShortName)
			GUICtrlSetData($FavoriteEmulator, $SelectedEmulator)

		Case $EmulatorList
			UpdateEmulatorData()

		Case $EmulatorWebsite
			If $Websitelink <> "" Then Run(@comspec & " /c start " & $Websitelink, "", @SW_HIDE)

		Case $EDCLink
			Run(@comspec & " /c start " & $EDCWebsitelink, "", @SW_HIDE)

		Case $ButtonSelect
			GUIDelete($EDCEMULATOR) ;Close the SelectEmulator GUI.
			$OldEmulator = "" ;Reset Oldemulator to force reload of emulator data.
			VersionSelect()
	EndSwitch

If WinActive($EDCEMULATOR) Then ;Catch ESCAPE in EDC but only when the EDC window is active, this way ESC won't be blocked for other applications.
	HotKeySet("{ESC}", "CatchEscape")
Else
	HotKeySet("{ESC}")
EndIf
Sleep(20)
WEnd

EndFunc ;EmuSelect

Func UpdateEmulatorData()
Global $SelectedEmulator = GUICtrlRead($EmulatorList, 1)
If $SelectedEmulator <> $OldEmulator And $SelectedEmulator <> "" Then ; User clicked on another one.

	$SelectedEmulatorShortName = ""
	For $i = 1 To $PlatformEmulators[0][0]
		If $SelectedEmulator = $PlatformEmulators[$i][1] Then
			$SelectedEmulatorShortName = $PlatformEmulators[$i][0]
			ExitLoop
		EndIf
	Next

	ToolTip("Downloading emulator data...", @DesktopWidth/2, @DesktopHeight/2, "EDC", 1, 6)
	;Download emulator information.
	InetGet($EDCServer & "hooks/" & $SelectedEmulatorShortName & "/emulator_info.ini", $EDCFolderCache & "emulator_info.ini", 1)
	;Download emulator images.
	FileDelete($EDCFolderCache & "emulator_logo.png") ;Delete image because logo can be jpg or png.
	FileDelete($EDCFolderCache & "emulator_logo.jpg") ;Delete image because logo can be jpg or png.
	InetGet($EDCServer & "hooks/" & $SelectedEmulatorShortName & "/emulator_screen_01.jpg", $EDCFolderCache & "emulator_screen_01.jpg", 1)
	InetGet($EDCServer & "hooks/" & $SelectedEmulatorShortName & "/emulator_logo.jpg", $EDCFolderCache & "emulator_logo.jpg", 1)
	InetGet($EDCServer & "hooks/" & $SelectedEmulatorShortName & "/emulator_logo.png", $EDCFolderCache & "emulator_logo.png", 1)

	GUICtrlSetData($EmulatorInfoGroup, " Emulator info (" & $SelectedEmulator & ") ")
	GUICtrlSetData($EmulatorAuthor, IniRead($EDCFolderCache & "emulator_info.ini", "EMULATOR", "Author", "-"))
	GUICtrlSetData($EmulatorLicense, IniRead($EDCFolderCache & "emulator_info.ini", "EMULATOR", "License", "-"))
	$EmulatorBiosNeeded = IniRead($EDCFolderCache & "emulator_info.ini", "EMULATOR", "BiosNeeded", "-")
	If $EmulatorBiosNeeded = "1" Then $EmulatorBiosNeeded = "Yes"
	If $EmulatorBiosNeeded = "0" Then $EmulatorBiosNeeded = "No"
	If $EmulatorBiosNeeded = "" Then $EmulatorBiosNeeded = "?"
	GUICtrlSetData($EmulatorBios, $EmulatorBiosNeeded)

	$EmulatorLastCheckData = IniRead($EDCFolderCache & "emulator_info.ini", "INFO", "LastCheck", "Unknown")
	$EmulatorCompleteData = IniRead($EDCFolderCache & "emulator_info.ini", "INFO", "CompleteFlag", "Unknown")
	If $EmulatorLastCheckData = "" Then $EmulatorLastCheckData = "Unknown"
	If $EmulatorCompleteData = "" Then $EmulatorCompleteData = "Unknown"

	GUICtrlSetData($EmulatorLastCheck, $EmulatorLastCheckData)
	GUICtrlSetData($EmulatorComplete, $EmulatorCompleteData)
	$Websitelink = ""
	$Websitelink = IniRead($EDCFolderCache & "emulator_info.ini", "EMULATOR", "Website", "")
	GUICtrlSetData($EmulatorWebsite, $Websitelink)
	GUICtrlSetData($EmulatorNotes, IniRead($EDCFolderCache & "emulator_info.ini", "EMULATOR", "Notes", "-"))
	GUICtrlSetImage($EmulatorImage, $EDCFolderCache & "emulator_screen_01.jpg")
	GUICtrlSetImage($EmulatorLogo, "")
	If FileExists($EDCFolderCache & "emulator_logo.png") Then GUICtrlSetImage($EmulatorLogo, $EDCFolderCache & "emulator_logo.png")
	If FileExists($EDCFolderCache & "emulator_logo.jpg") Then GUICtrlSetImage($EmulatorLogo, $EDCFolderCache & "emulator_logo.jpg")
	ToolTip("", @DesktopWidth/2, @DesktopHeight/2, "EDC", 1, 6)

	$OldEmulator = $SelectedEmulator ; Save the emulatorname the user has clicked before.
EndIf
EndFunc ;UpdateEmulatorData()


Func VersionSelect()
; -----------------------------------------------------------------------------------------
; VERSION SELECT GUI
; -----------------------------------------------------------------------------------------
Global $EDCVERSION = GUICreate("ECC - emuDownloadCenter", 1128, 775, -1, -1)
GUISetCursor (0)
GUISetBkColor(0xFFFFFF)
Global $VersionInfoGroup = GUICtrlCreateGroup(" version information ", 8, 352, 1113, 297, BitOR($GUI_SS_DEFAULT_GROUP,$BS_CENTER))
GUICtrlSetFont(-1, 9, 800, 2, "Verdana")
Global $Label6 = GUICtrlCreateLabel("License:", 101, 269, 60, 18, 0)
Global $ChangeLogText = GUICtrlCreateEdit("", 16, 384, 545, 257, BitOR($GUI_SS_DEFAULT_EDIT,$ES_READONLY,$WS_BORDER))
GUICtrlSetFont(-1, 9, 400, 0, "Courier New")
GUICtrlSetBkColor(-1, 0xFFFBF0)
Global $Label5 = GUICtrlCreateLabel("Changelog:", 16, 367, 70, 17, 0)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $ArchiveContentsText = GUICtrlCreateEdit("", 568, 384, 545, 257, BitOR($GUI_SS_DEFAULT_EDIT,$ES_READONLY,$WS_BORDER))
GUICtrlSetFont(-1, 9, 400, 0, "Courier New")
GUICtrlSetBkColor(-1, 0xFFFBF0)
Global $Label8 = GUICtrlCreateLabel("Archive contents:", 568, 367, 104, 17, 0)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
GUICtrlCreateGroup("", -99, -99, 1, 1)
Global $InstallationGroup = GUICtrlCreateGroup(" installation ", 8, 656, 1113, 113, BitOR($GUI_SS_DEFAULT_GROUP,$BS_CENTER))
GUICtrlSetFont(-1, 9, 800, 2, "Verdana")
Global $InstallButton = GUICtrlCreateButton("INSTALL", 1008, 688, 105, 73, BitOR($BS_MULTILINE,$BS_NOTIFY))
GUICtrlSetFont(-1, 9, 800, 0, "Verdana")
GUICtrlSetState(-1, $GUI_DISABLE)
GUICtrlSetTip(-1, "Download and install the selected emulator.")
Global $Label1 = GUICtrlCreateLabel("Emulator destination path:", 16, 672, 155, 17, 0)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $EmuInstallPathLabel = GUICtrlCreateLabel("-", 168, 672, 270, 17, 0)
GUICtrlSetFont(-1, 7, 400, 0, "Verdana")
GUICtrlSetColor(-1, 0x000080)
Global $UnpackBar = GUICtrlCreateProgress(312, 712, 246, 17, BitOR($PBS_SMOOTH,$WS_BORDER))
Global $DownloadBar = GUICtrlCreateProgress(64, 712, 246, 17, BitOR($PBS_SMOOTH,$WS_BORDER))
GUICtrlSetColor(-1, 0x3399FF)
Global $ExitButton = GUICtrlCreateButton("EXIT", 816, 728, 81, 33, BitOR($BS_MULTILINE,$BS_NOTIFY))
GUICtrlSetFont(-1, 10, 800, 0, "Verdana")
GUICtrlSetTip(-1, "Exit EDC.")
Global $Label2 = GUICtrlCreateLabel("Download:", 64, 695, 65, 17, 0)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $Label3 = GUICtrlCreateLabel("Configure:", 560, 695, 65, 17, 0)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $Label4 = GUICtrlCreateLabel("Status:", 16, 736, 45, 17, 0)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $DownloadStatusLabel = GUICtrlCreateLabel("-", 64, 736, 190, 17, 0)
GUICtrlSetFont(-1, 7, 400, 0, "Verdana")
GUICtrlSetColor(-1, 0x000080)
Global $UnpackStatusLabel = GUICtrlCreateLabel("-", 312, 736, 190, 17, 0)
GUICtrlSetFont(-1, 7, 400, 0, "Verdana")
GUICtrlSetColor(-1, 0x000080)
Global $ConfigureStatusLabel = GUICtrlCreateLabel("-", 560, 736, 190, 17, 0)
GUICtrlSetFont(-1, 7, 400, 0, "Verdana")
GUICtrlSetColor(-1, 0x000080)
Global $ConfigureBar = GUICtrlCreateProgress(560, 712, 246, 17, BitOR($PBS_SMOOTH,$WS_BORDER))
Global $Label9 = GUICtrlCreateLabel("Unpack:", 312, 695, 51, 17, 0)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $EDCInfoLabel2 = GUICtrlCreateLabel("", 818, 671, 296, 17, $SS_CENTER)
GUICtrlSetFont(-1, 8, 800, 2, "Verdana")
GUICtrlSetColor(-1, 0x0000FF)
Global $BackButton = GUICtrlCreateButton("BACK", 816, 688, 81, 33, BitOR($BS_MULTILINE,$BS_NOTIFY))
GUICtrlSetFont(-1, 10, 800, 0, "Verdana")
GUICtrlSetTip(-1, "Back to Emulator selection.")
Global $ConfigButton = GUICtrlCreateButton("CONFIGURE", 904, 688, 97, 73, BitOR($BS_MULTILINE,$BS_NOTIFY))
GUICtrlSetFont(-1, 9, 800, 0, "Verdana")
GUICtrlSetState(-1, $GUI_DISABLE)
GUICtrlSetTip(-1, "Re-Configure Emulator with ECC.")
GUICtrlCreateGroup("", -99, -99, 1, 1)
Global $SelectVersionGroup = GUICtrlCreateGroup(" select version ", 8, 0, 1113, 345, BitOR($GUI_SS_DEFAULT_GROUP,$BS_CENTER))
GUICtrlSetFont(-1, 9, 800, 2, "Verdana")
Global $VersionList = GUICtrlCreateListView("Version|Release|EMU Arch|Operating system|Parameter|>Script?|Executable|DL (KB)|INST (KB)|Emulator Notes", 12, 16, 1105, 321, $GUI_SS_DEFAULT_LISTVIEW, BitOR($WS_EX_CLIENTEDGE,$LVS_EX_GRIDLINES,$LVS_EX_SUBITEMIMAGES,$LVS_EX_FULLROWSELECT))
GUICtrlSendMsg(-1, $LVM_SETCOLUMNWIDTH, 0, 100)
GUICtrlSendMsg(-1, $LVM_SETCOLUMNWIDTH, 1, 80)
GUICtrlSendMsg(-1, $LVM_SETCOLUMNWIDTH, 2, 70)
GUICtrlSendMsg(-1, $LVM_SETCOLUMNWIDTH, 3, 150)
GUICtrlSendMsg(-1, $LVM_SETCOLUMNWIDTH, 4, 130)
GUICtrlSendMsg(-1, $LVM_SETCOLUMNWIDTH, 5, 55)
GUICtrlSendMsg(-1, $LVM_SETCOLUMNWIDTH, 6, 130)
GUICtrlSendMsg(-1, $LVM_SETCOLUMNWIDTH, 7, 70)
GUICtrlSendMsg(-1, $LVM_SETCOLUMNWIDTH, 8, 70)
GUICtrlSendMsg(-1, $LVM_SETCOLUMNWIDTH, 9, 320)
GUICtrlSetFont(-1, 7, 400, 0, "Verdana")
GUICtrlSetBkColor(-1, 0xA6CAF0)
GUICtrlSetCursor (-1, 0)
GUICtrlCreateGroup("", -99, -99, 1, 1)
; -----------------------------------------------------------------------------------------
; VERSION SELECT GUI
; -----------------------------------------------------------------------------------------

GUISetIcon(@ScriptDir & "\emuDownloadCenter.ico", "", $EDCVERSION) ;Set proper icon for the window.
GUICtrlSetData($SelectVersionGroup, " select version (" & $SelectedEmulator & ")")
GUICtrlSetData($EDCInfoLabel2, "emuDownloadCenter v" & $EDCScriptVersion) ;Set version in the infolabel.
GUICtrlSetData($InstallButton, "INSTALL")

GUISetState(@SW_SHOW, $EDCVERSION)

;Download Emulator information and configuration.
Message("show", "Downloading version and configuration information...")
InetGet($EDCServer & "hooks/" & $SelectedEmulatorShortName & "/emulator_downloads.ini", $EDCEmulatorDownloadsINI, 1)
InetGet($EDCServer & "hooks/" & $SelectedEmulatorShortName & "/configs_frontend_ecc.ini", $EDCEmulatorConfigINI, 1)
$PlatformVersions = IniReadSectionNames($EDCEmulatorDownloadsINI)

Global $ListLine = 0
For $i = 1 To $PlatformVersions[0] ;$PlatformVersions[0] = the amount of emulator versions
	If $PlatformVersions[$i] <> "INFO" Then

		Global $ContentType = IniRead($EDCEmulatorDownloadsINI, $PlatformVersions[$i], "FILE_ContentType", "-")
		Global $ContentCategory = IniRead($EDCEmulatorDownloadsINI, $PlatformVersions[$i], "FILE_ContentCategory", "")
		Global $FileNotes = IniRead($EDCEmulatorDownloadsINI, $PlatformVersions[$i], "FILE_Notes", "")
		Global $Version = $PlatformVersions[$i]
		Global $ReleaseDate = IniRead($EDCEmulatorDownloadsINI, $PlatformVersions[$i], "EMU_ReleaseDate", "-")
		Global $EMUnotes = IniRead($EDCEmulatorDownloadsINI, $PlatformVersions[$i], "EMU_Notes", "")
		Global $OSEmulator = IniRead($EDCEmulatorDownloadsINI, $PlatformVersions[$i], "EMU_OS", "")
		Global $OSEmulatorVersion = IniRead($EDCEmulatorDownloadsINI, $PlatformVersions[$i], "EMU_OSVersion", "")
		Global $OSEmulatorArch = IniRead($EDCEmulatorDownloadsINI, $PlatformVersions[$i], "EMU_OSArchitecture", "")
		Global $Executable = IniRead($EDCEmulatorDownloadsINI, $PlatformVersions[$i], "EMU_ExecutableFile", "")
		Global $CRC32Executable = IniRead($EDCEmulatorDownloadsINI, $PlatformVersions[$i], "INFO_CRC32Executable", "-")
		Global $PackedSize = IniRead($EDCEmulatorDownloadsINI, $PlatformVersions[$i], "INFO_PackedSize", "-")
		Global $UnpackedSize = IniRead($EDCEmulatorDownloadsINI, $PlatformVersions[$i], "INFO_UnpackedSize", "-")

		If $ContentType = "Program" And $ContentCategory = "Emulator" And $OSEmulator = "Windows" Then ;Only add "Windows emulator programs" to the list.

			$OSSupported = 0 ;Check if the user OS supports this emulator.
			If StringLower(@OSArch) = $OSEmulatorArch Then $OSSupported = 1
			If StringLower(@OSArch) = "x64" And $OSEmulatorArch = "x86" Then $OSSupported = 1

			If $OSSupported = 1 Then

				Global $ScriptNeededNum = IniRead($EDCEmulatorConfigINI, $PlatformVersions[$i], "CFG_enableEccScript", "X") ;Read-out VERSION specific information.
				If $ScriptNeededNum = "X" Then $ScriptNeededNum = IniRead($EDCEmulatorConfigINI, "GLOBAL", "CFG_enableEccScript", "0") ;Read-out GLOBAL confguration for this emulator.
				$ScriptNeeded = "No"
				If $ScriptNeededNum = "1" Then $ScriptNeeded = "Yes"

				Global $CFG_ECCParameter = IniRead($EDCEmulatorConfigINI, $PlatformVersions[$i], "CFG_ECCParameter", "X") ;Read-out VERSION specific information.
				If $CFG_ECCParameter = "X" Then $CFG_ECCParameter = IniRead($EDCEmulatorConfigINI, "GLOBAL", "CFG_ECCParameter", "") ;Read-out GLOBAL confguration for this emulator.

				;Fill the row, start with 0
				_GUICtrlListView_AddItem($VersionList, "", $ListLine)
				_GUICtrlListView_AddSubItem($VersionList, $ListLine, $Version, 0)
				_GUICtrlListView_AddSubItem($VersionList, $ListLine, $ReleaseDate, 1)
				_GUICtrlListView_AddSubItem($VersionList, $ListLine, $OSEmulatorArch, 2)
				_GUICtrlListView_AddSubItem($VersionList, $ListLine, $OSEmulator & " " & $OSEmulatorVersion, 3)
				_GUICtrlListView_AddSubItem($VersionList, $ListLine, $CFG_ECCParameter, 4)
				_GUICtrlListView_AddSubItem($VersionList, $ListLine, $ScriptNeeded, 5)
				_GUICtrlListView_AddSubItem($VersionList, $ListLine, $Executable, 6)
				_GUICtrlListView_AddSubItem($VersionList, $ListLine, $PackedSize, 7)
				_GUICtrlListView_AddSubItem($VersionList, $ListLine, $UnpackedSize, 8)
				_GUICtrlListView_AddSubItem($VersionList, $ListLine, $EMUnotes, 9)
				$ListLine = $ListLine + 1

			EndIf
		EndIf
	EndIf
Next

_GUICtrlListView_SetItemSelected($VersionList, 0) ;Always select the first version, this way at least something is selected.
UpdateEmuVariables()

GUICtrlSetState($InstallButton, $GUI_ENABLE) ;Enable the install button.
Message("delete")

While 1
	$nMsg = GUIGetMsg()
	Switch $nMsg
		Case $GUI_EVENT_CLOSE
			Exit

		Case $Exitbutton
			Exit

		Case $InstallButton
			EmulatorInstall()

		Case $ConfigButton
			GUICtrlSetState($InstallButton, $GUI_DISABLE) ;Disable the install button.
			GUICtrlSetState($ConfigButton, $GUI_DISABLE) ;Enable the config button.
			EmulatorConfig()

		Case $BackButton
			GUIDelete($EDCVERSION) ;Close the SelectVersion GUI.
			EmuSelect()

		Case _IsPressed("26") ;UP arrow key pressed.
			UpdateEmuVariables()

		Case _IsPressed("28") ;DOWN arrow key pressed.
			UpdateEmuVariables()

		Case $GUI_EVENT_PRIMARYDOWN ;Left mouse button prerssed.
			UpdateEmuVariables()

		Case $GUI_EVENT_SECONDARYDOWN ;Right mouse button pressed.
			UpdateEmuVariables()
	EndSwitch

	If WinActive($EDCVERSION) Then ;Catch ESCAPE in EDC but only when the EDC window is active, this way ESC won't be blocked for other applications.
		HotKeySet("{ESC}", "CatchEscape")
	Else
		HotKeySet("{ESC}")
	EndIf

Sleep(20)
WEnd

EndFunc ;VersionSelect()

Func UpdateEmuVariables()
$SelectedRow = _GUICtrlListView_GetItemTextString($VersionList) ;Get current row selection.
$RowData = StringSplit($SelectedRow, "|")
Global $SelectedVersion = $Rowdata[1] ;Get emulator version from the list.
Global $SelectedOSEmulatorArch = $Rowdata[3] ;Get emulator architecture from the list.
Global $EmuInstallPathShort = "ecc-user\" & $RomEccId & "\emus\" & $SelectedEmulatorShortName & "_" & $SelectedVersion
Global $EmuInstallPathFull = $eccInstallPath & "\" & $EmuInstallPathShort
Global $UnpackSize = IniRead($EDCEmulatorDownloadsINI, $SelectedVersion, "INFO_UnpackedSize", "")
Global $ExecutableFile = IniRead($EDCEmulatorDownloadsINI, $SelectedVersion, "EMU_ExecutableFile", "")
Global $EmuInstallPathFull = $eccInstallPath & "\ecc-user\" & $RomEccId & "\emus\" & $SelectedEmulatorShortName & "_" & $SelectedVersion
Global $EmuConfigFile = $eccInstallPath & "\ecc-system\system\ecc_" & $RomEccId & "_user.ini" ;Determine emulator config INI file.

If $SelectedVersion <> $OldVersion And $SelectedVersion <> "" Then ; User clicked on another one.
	;Reset BARS/GUI/BUTTONS
	GUICtrlSetState($InstallButton, $GUI_DISABLE) ;Disable the install button.
	GUICtrlSetState($ConfigButton, $GUI_DISABLE) ;Disable the config button.
	GUICtrlSetData($InstallButton, "INSTALL")
	GUICtrlSetData($DownloadBar, "0")
	GUICtrlSetData($DownloadStatusLabel, "-")
	GUICtrlSetData($UnpackBar, "0")
	GUICtrlSetData($UnpackStatusLabel, "-")
	GUICtrlSetData($ConfigureBar, "0")
	GUICtrlSetData($ConfigureStatusLabel, "-")

	Message("show", "Downloading informations...")
	FileDelete($EDCFolderCache & "changelog.txt")
	FileDelete($EDCFolderCache & "contents.txt")

	;CHANGELOG INFORMATION
	$VersionDownloadChangelog = IniRead($EDCEmulatorDownloadsINI, $SelectedVersion, "EMU_DownloadUrl", "") & $SelectedVersion & "_changelog.txt"
	InetGet($VersionDownloadChangelog, $EDCFolderCache & "changelog.txt", 1)
	;ARCHIVE CONTENTS INFORMATION
	$VersionDownloadChangelog = IniRead($EDCEmulatorDownloadsINI, $SelectedVersion, "EMU_DownloadUrl", "") & $SelectedVersion & "_contents.txt"
	InetGet($VersionDownloadChangelog, $EDCFolderCache & "contents.txt", 1)

	GUICtrlSetData($VersionInfoGroup, " version information (" & $SelectedVersion & ") ")

	;Set data in the fields.
	If FileExists($EDCFolderCache & "changelog.txt") Then
		$EmulatorChangeLog = StringReplace(FileRead($EDCFolderCache & "changelog.txt"), @LF, @CRLF) ;Convert Linux LineFeed to Windows CarriageReturn/LineFeed
		GUICtrlSetData($ChangeLogText, $EmulatorChangeLog)
	Else
		GUICtrlSetData($ChangeLogText, "Not present/available")
	EndIf

	If FileExists($EDCFolderCache & "contents.txt") Then
		$EmulatorContents = StringReplace(FileRead($EDCFolderCache & "contents.txt"), @LF, @CRLF) ;Convert Linux LineFeed to Windows CarriageReturn/LineFeed
		GUICtrlSetData($ArchiveContentsText, $EmulatorContents)
	Else
		GUICtrlSetData($ArchiveContentsText, "Not present/available")
	EndIf

	GUICtrlSetData($EmuInstallPathLabel, $EmuInstallPathShort)

	;Check if emulator has already been installed.
	If FileExists($eccInstallPath & "\" & $EmuInstallPathShort & "\" & $ExecutableFile) Then
		GUICtrlSetData($InstallButton, "RE-INSTALL?")
		GUICtrlSetState($ConfigButton, $GUI_ENABLE) ;Enable the config button.
	EndIf

	GUICtrlSetState($InstallButton, $GUI_ENABLE) ;Enable the installbutton.
	$Oldversion = $SelectedVersion ;Save the version the user has clicked before.
	Message("delete")
EndIf

EndFunc ;UpdateEmuVariables


Func EmulatorInstall()
; DOWNLOAD -----------------------------------------------
$DownloadSize = IniRead($EDCEmulatorDownloadsINI, $SelectedVersion, "INFO_PackedSize", "")

; Check free space for download.
If FreeSpaceOnDrive() < $DownloadSize Then ;Check for Free Space before unpacking the archive.
	GUICtrlSetData($DownloadStatusLabel, "Failed, not enough space!")
	GUICtrlSetFont($DownloadStatusLabel, 8, 800)
	GUICtrlSetColor($DownloadStatusLabel, $COLOR_RED)
	Message("show", "Not enough free space on drive to download the emulator archive!")
	Sleep(3000) ;Some delay to let user read the message
	Message("delete", "")
	Return
EndIf

GUICtrlSetState($InstallButton, $GUI_DISABLE)

; Download the emulator archive
$InetGetEmulatorHandle = InetGet(IniRead($EDCEmulatorDownloadsINI, $SelectedVersion, "EMU_DownloadUrl", "") & $SelectedVersion & ".7z", $EDCFolderCache & "archive.7z", 1, 1) ; Get the file from the internet
If @Error Then ;User has no internet connection
	GUICtrlSetData($DownloadStatusLabel, "Emulator download failed!")
	GUICtrlSetFont($DownloadStatusLabel, 8, 800)
	GUICtrlSetColor($DownloadStatusLabel, $COLOR_RED)
	Message("show", "Emulator download failed!, you need an active internet connection to download, or your firewall may block EDC!")
	Sleep(3000) ;Some delay to let user read the message
	Message("delete", "")
	Return
Else
	Do
		$InetBytesRead = InetGetInfo($InetGetEmulatorHandle, 0)
		GUICtrlSetData($DownloadStatusLabel, "Downloading " & Round(($InetBytesRead / 1024), 0) & " / " & $DownloadSize & " KB")
		$DownloadProcent = Round((($InetBytesRead / 1024) / $DownloadSize) * 100, 0)
		GUICtrlSetData($DownloadBar, $DownloadProcent)
		Sleep(20)
	Until InetGetInfo($InetGetEmulatorHandle, 2) ;Check if the download is complete.

	GUICtrlSetData($DownloadBar, "100") ;Bar always 100% on finish.
EndIf

; Check if download is successfull with CRC32
If IniRead($EDCEmulatorDownloadsINI, $SelectedVersion, "INFO_CRC32Archive", "") <> GetCRC($EDCFolderCache & "archive.7z") Then
	GUICtrlSetData($DownloadStatusLabel, "Emulator download failed! (CRC)")
	GUICtrlSetFont($DownloadStatusLabel, 8, 800)
	GUICtrlSetColor($DownloadStatusLabel, $COLOR_RED)
	Message("show", "Emulator download failed!, CRC32 does not match!")
	Sleep(3000) ;Some delay to let user read the message
	Message("delete", "")
	Return
EndIf

GUICtrlSetData($DownloadStatusLabel, "Succesfull!")
GUICtrlSetFont($DownloadStatusLabel, 8, 800)
GUICtrlSetColor($DownloadStatusLabel, $COLOR_GREEN)

; UNPACK -----------------------------------------------
;
;
;Check free space for unpacking.
GUICtrlSetData($UnpackStatusLabel, "Check free space...")
If FreeSpaceOnDrive() < $UnpackSize Then ;Check for Free Space before unpacking the archive
	GUICtrlSetData($UnpackStatusLabel, "Failed, not enough space!")
	GUICtrlSetFont($UnpackStatusLabel, 8, 800)
	GUICtrlSetColor($UnpackStatusLabel, $COLOR_RED)
	Message("show", "Not enough free space on drive to install the emulator!")
	Sleep(3000) ;Some delay to let user read the message
	Message("delete", "")
	Return
EndIf

;Create emulator folder
GUICtrlSetData($UnpackStatusLabel, "Create emulator folder...")
DirCreate($EmuInstallPathFull)

;Extract file(s) to emulator folder
GUICtrlSetData($UnpackStatusLabel, "Extracting emulator...")
;ShellExecuteWait(Chr(34) & $7zExe & Chr(34), "X " & Chr(34) & $EDCFolderCache & "archive.7z" & Chr(34) & " -y -o" & Chr(34) & $EmuInstallPathFull & Chr(34), @ScriptDir, "", @SW_HIDE)
_7zRead(Chr(34) & $7zExe & Chr(34) & " X -bb3 " & Chr(34) & $EDCFolderCache & "archive.7z" & Chr(34) & " -y -o" & Chr(34) & $EmuInstallPathFull & Chr(34), $UnpackBar, 1, 1, "", "", @SW_HIDE)
GUICtrlSetData($UnpackBar, "100")

;Check if EMU executable file exists.
If FileExists($EmuInstallPathFull & "\" & $ExecutableFile) = 0 Then
	GUICtrlSetData($UnpackStatusLabel, "Emulator unpacking failed!")
	GUICtrlSetFont($UnpackStatusLabel, 8, 800)
	GUICtrlSetColor($UnpackStatusLabel, $COLOR_RED)
	Message("show", "Emulator unpacking failed!, target file '" & $EmuInstallPathFull & "\" & $ExecutableFile & "' does not exists!")
	Sleep(4000) ;Some delay to let user read the message
	Message("delete", "")
	Return
EndIf

GUICtrlSetData($UnpackStatusLabel, "Succesfull!")
GUICtrlSetFont($UnpackStatusLabel, 8, 800)
GUICtrlSetColor($UnpackStatusLabel, $COLOR_GREEN)

; CONFIG -----------------------------------------------
;
;
GUICtrlSetData($ConfigureStatusLabel, "Check installation...")
If IniRead($EDCEmulatorDownloadsINI, $SelectedVersion, "INFO_CRC32Executable", "") <> GetCRC($EmuInstallPathFull & "\" & $ExecutableFile) Then
	GUICtrlSetData($ConfigureStatusLabel, "Emulator installation failed! (CRC)")
	Message("show", "Emulator installation failed!, CRC32 does not match!")
	Sleep(3000) ;Some delay to let user read the message
	Message("delete", "")
	Return
EndIf
GUICtrlSetData($ConfigureBar, "33")

;Delete archive.7z
GUICtrlSetData($ConfigureStatusLabel, "Delete archive...")
FileDelete($EDCFolderCache & "archive.7z")
GUICtrlSetData($ConfigureBar, "66")

EmulatorConfig()

Message("show", "Emulator downloaded, unpacked and configured sucessfully!, enjoy!")
Sleep(3000) ;Some delay to let user read the message
Message("delete", "")

EndFunc ;DownloadConfigEmulator


Func EmulatorConfig()
; Configure emulator with emuControlCenter
GUICtrlSetData($ConfigureStatusLabel, "Configure emulator...")
Global $EmuConfigFileUser = $eccInstallPath & "\ecc-user-configs\ecc_" & $RomEccId & "_user.ini"
If FileExists($EmuConfigFileUser) = 0 Then FileCopy($EmuConfigFile, $EmuConfigFileUser, 8)
IniWrite($EmuConfigFileUser, "EMU.GLOBAL", "active", Chr(34) & "1" & Chr(34))
IniWrite($EmuConfigFileUser, "EMU.GLOBAL", "path", Chr(34) & $EmuInstallPathFull & "\" & $ExecutableFile & Chr(34))

Global $CFG_param = IniRead($EDCEmulatorConfigINI, $SelectedVersion, "CFG_param", "X") ;Read-out VERSION specific information.
If $CFG_param = "X" Then $CFG_param = IniRead($EDCEmulatorConfigINI, "GLOBAL", "CFG_param", "") ;Read-out GLOBAL confguration for this emulator.
IniWrite($EmuConfigFileUser, "EMU.GLOBAL", "param", Chr(34) & $CFG_param & Chr(34))

Global $CFG_escape = IniRead($EDCEmulatorConfigINI, $SelectedVersion, "CFG_escape", "X") ;Read-out VERSION specific information.
If $CFG_escape = "X" Then $CFG_escape = IniRead($EDCEmulatorConfigINI, "GLOBAL", "CFG_escape", "") ;Read-out GLOBAL confguration for this emulator.
IniWrite($EmuConfigFileUser, "EMU.GLOBAL", "escape", Chr(34) & $CFG_escape & Chr(34))

Global $CFG_win8char = IniRead($EDCEmulatorConfigINI, $SelectedVersion, "CFG_win8char", "X") ;Read-out VERSION specific information.
If $CFG_win8char = "X" Then $CFG_win8char = IniRead($EDCEmulatorConfigINI, "GLOBAL", "CFG_win8char", "") ;Read-out GLOBAL confguration for this emulator.
IniWrite($EmuConfigFileUser, "EMU.GLOBAL", "win8char", Chr(34) & $CFG_win8char & Chr(34))

Global $CFG_useCueFile = IniRead($EDCEmulatorConfigINI, $SelectedVersion, "CFG_useCueFile", "X") ;Read-out VERSION specific information.
If $CFG_useCueFile = "X" Then $CFG_useCueFile = IniRead($EDCEmulatorConfigINI, "GLOBAL", "CFG_useCueFile", "") ;Read-out GLOBAL confguration for this emulator.
IniWrite($EmuConfigFileUser, "EMU.GLOBAL", "useCueFile", Chr(34) & $CFG_useCueFile & Chr(34))

Global $CFG_filenameOnly = IniRead($EDCEmulatorConfigINI, $SelectedVersion, "CFG_filenameOnly", "X") ;Read-out VERSION specific information.
If $CFG_filenameOnly = "X" Then $CFG_filenameOnly = IniRead($EDCEmulatorConfigINI, "GLOBAL", "CFG_filenameOnly", "") ;Read-out GLOBAL confguration for this emulator.
IniWrite($EmuConfigFileUser, "EMU.GLOBAL", "filenameOnly", Chr(34) & $CFG_filenameOnly & Chr(34))

Global $CFG_noExtension = IniRead($EDCEmulatorConfigINI, $SelectedVersion, "CFG_noExtension", "X") ;Read-out VERSION specific information.
If $CFG_noExtension = "X" Then $CFG_noExtension = IniRead($EDCEmulatorConfigINI, "GLOBAL", "CFG_noExtension", "") ;Read-out GLOBAL confguration for this emulator.
IniWrite($EmuConfigFileUser, "EMU.GLOBAL", "noExtension", Chr(34) & $CFG_noExtension & Chr(34))

Global $CFG_executeInEmuFolder = IniRead($EDCEmulatorConfigINI, $SelectedVersion, "CFG_executeInEmuFolder", "X") ;Read-out VERSION specific information.
If $CFG_executeInEmuFolder = "X" Then $CFG_executeInEmuFolder = IniRead($EDCEmulatorConfigINI, "GLOBAL", "CFG_executeInEmuFolder", "") ;Read-out GLOBAL confguration for this emulator.
IniWrite($EmuConfigFileUser, "EMU.GLOBAL", "executeInEmuFolder", Chr(34) & $CFG_executeInEmuFolder & Chr(34))

Global $CFG_enableEccScript = IniRead($EDCEmulatorConfigINI, $SelectedVersion, "CFG_enableEccScript", "X") ;Read-out VERSION specific information.
If $CFG_enableEccScript = "X" Then $CFG_enableEccScript = IniRead($EDCEmulatorConfigINI, "GLOBAL", "CFG_enableEccScript", "") ;Read-out GLOBAL confguration for this emulator.
IniWrite($EmuConfigFileUser, "EMU.GLOBAL", "enableEccScript", Chr(34) & $CFG_enableEccScript & Chr(34))

Global $CFG_enableZipUnpackActive = IniRead($EDCEmulatorConfigINI, $SelectedVersion, "CFG_enableZipUnpackActive", "X") ;Read-out VERSION specific information.
If $CFG_enableZipUnpackActive = "X" Then $CFG_enableZipUnpackActive = IniRead($EDCEmulatorConfigINI, "GLOBAL", "CFG_enableZipUnpackActive", "") ;Read-out GLOBAL confguration for this emulator.
IniWrite($EmuConfigFileUser, "EMU.GLOBAL", "enableZipUnpackActive", Chr(34) & $CFG_enableZipUnpackActive & Chr(34))

Global $CFG_enableZipUnpackAll = IniRead($EDCEmulatorConfigINI, $SelectedVersion, "enableZipUnpackAll", "X") ;Read-out VERSION specific information.
If $CFG_enableZipUnpackAll = "X" Then $CFG_enableZipUnpackAll = IniRead($EDCEmulatorConfigINI, "GLOBAL", "enableZipUnpackAll", "") ;Read-out GLOBAL confguration for this emulator.
IniWrite($EmuConfigFileUser, "EMU.GLOBAL", "enableZipUnpackAll", Chr(34) & $CFG_enableZipUnpackAll & Chr(34))

Global $CFG_enableZipUnpackSkip = IniRead($EDCEmulatorConfigINI, $SelectedVersion, "CFG_enableZipUnpackSkip", "X") ;Read-out VERSION specific information.
If $CFG_enableZipUnpackSkip = "X" Then $CFG_enableZipUnpackSkip = IniRead($EDCEmulatorConfigINI, "GLOBAL", "CFG_enableZipUnpackSkip", "") ;Read-out GLOBAL confguration for this emulator.
IniWrite($EmuConfigFileUser, "EMU.GLOBAL", "enableZipUnpackSkip", Chr(34) & $CFG_enableZipUnpackSkip & Chr(34))


GUICtrlSetData($ConfigureBar, "100")
GUICtrlSetData($ConfigureStatusLabel, "Succesfull!")
GUICtrlSetFont($ConfigureStatusLabel, 8, 800)
GUICtrlSetColor($ConfigureStatusLabel, $COLOR_GREEN)
EndFunc ;ConfigEmu


Func CatchEscape()
	; Catch the escape key so preventing to EDC close itself when pressed
	; Just catch the escape button and do noting....
EndFunc ;CatchEscape

Func Message($action, $notetext = "")
If $action = "show" Then
	; Disable the MAIN GUI
	GUISetState(@SW_DISABLE, $EDCVERSION)
	$EDCcoord = WinGetPos($EDCWindowTitle)
	ToolTip($notetext, $EDCcoord[0]+($EDCcoord[2]/2), $EDCcoord[1]+($EDCcoord[3]/2), "EDC", 1, 2)
EndIf
If $action = "delete" Then
	; Enable the MAIN GUI
	GUISetState(@SW_ENABLE, $EDCVERSION)
	ToolTip("")
EndIf
EndFunc ;Loading

Func FreeSpaceOnDrive()
Return Round(DriveSpaceFree(StringMid(@ScriptDir, 1, 3)), 0) * 1024 ; Round the value & convert from MB to KB
EndFunc ;FreeSpaceOnDrive()

Func _GetNetworkConnect()
    Local Const $NETWORK_ALIVE_LAN = 0x1  ;net card connection
    Local Const $NETWORK_ALIVE_WAN = 0x2  ;RAS (internet) connection
    Local Const $NETWORK_ALIVE_AOL = 0x4  ;AOL

    Local $aRet, $iResult

    $aRet = DllCall("sensapi.dll", "int", "IsNetworkAlive", "int*", 0)

    If BitAND($aRet[1], $NETWORK_ALIVE_LAN) Then $iResult &= "LAN connected" & @LF
    If BitAND($aRet[1], $NETWORK_ALIVE_WAN) Then $iResult &= "WAN connected" & @LF
    If BitAND($aRet[1], $NETWORK_ALIVE_AOL) Then $iResult &= "AOL connected" & @LF

    Return $iResult
EndFunc


; Internal 7ZIP helper functions ----------------------------------------------------
;
;   Function:       _7zRead
;   Description:    Reads progress from 7zip window and sets control data
;   Parameters:     $sCmd = 7zip Commandline to be executed
;                   $iProgress = [optional] ControlID of Progress Control to be updated (if none, use "")
;                   $sProgress = [optional] 1 if Progress Bar Window has to be updated, "" if not
;                   $sProgressSubText = [optional] 1 if Progress Bar Subtext to be written, "" if not
;                   $iStatic = [optional] ControlID of a Static Control (label, button etc pp) to be updated (if none, use "")
;                   $iStaticText = [optional] Text for $iStatic, the percentage will be added
;                   $nShow = [optional] Flag for 7zip window, default is @SW_HIDE
;   Author:         jennico, basic script by valik

Func _7zRead($sCmd, $iProgress="", $sProgress="", $sProgressSubText="", $iStatic="", $iStaticText="", $nShow = @SW_HIDE)
    $iPID = Run($sCmd, "", $nShow)
    ProcessWait($iPID)
    If $iStatic Then GUICtrlSetData($iStatic, $iStaticText & "0%")
    Local $hPercent = Open7ZipPercent($iPID), $opercent = -1
    While ProcessExists($iPID)
        Local $nPercent = Read7ZipPercent($hPercent)
        If $nPercent >= 0 And $opercent <> $nPercent Then
            If $sProgressSubText Then $sProgressSubText = $nPercent
            If $sProgress Then ProgressSet($nPercent, $nPercent & "%")
            If $iProgress Then GUICtrlSetData($iProgress, $nPercent)
            If $iStatic Then GUICtrlSetData($iStatic, $iStaticText & $nPercent & "%")
            $opercent = $nPercent
        Else
            Sleep(20)
        EndIf
    WEnd
    Close7ZipPercent($hPercent)
EndFunc   ;==>_7zRead

Func Open7ZipPercent($pid)
    If _AttachConsole($pid) = 0 Then Return
    Local $vHandle[4]
    $vHandle[0] = _GetStdHandle($STD_OUTPUT_HANDLE)
    $vHandle[1] = DllStructCreate($_CONSOLE_SCREEN_BUFFER_INFO)
    $vHandle[2] = DllStructCreate("dword[4]")
    $vHandle[3] = DllStructCreate($_SMALL_RECT)
    Return $vHandle
EndFunc   ;==>Open7ZipPercent

Func Close7ZipPercent(ByRef $vHandle)
    If UBound($vHandle) <> 4 Then Return False
    DllCall($K32, $INT, "FreeConsole")
    $vHandle = 0
    Return True
EndFunc   ;==>Close7ZipPercent

Func Read7ZipPercent(ByRef $vHandle)
    If UBound($vHandle) = 4 Then
        Local Const $hStdOut = $vHandle[0]
        Local Const $pConsoleScreenBufferInfo = $vHandle[1]
        Local Const $pBuffer = $vHandle[2]
        Local Const $pSmallRect = $vHandle[3]
        If _GetConsoleScreenBufferInfo($hStdOut, $pConsoleScreenBufferInfo) Then
            $afstand = DllStructSetData($pSmallRect, "Left", DllStructGetData($pConsoleScreenBufferInfo, "dwCursorPositionX"))
			DllStructSetData($pSmallRect, "Left", DllStructGetData($pConsoleScreenBufferInfo, "dwCursorPositionX") - $afstand) ; Go to max left corner!
            DllStructSetData($pSmallRect, "Top", DllStructGetData($pConsoleScreenBufferInfo, "dwCursorPositionY"))
            DllStructSetData($pSmallRect, "Right", DllStructGetData($pConsoleScreenBufferInfo, "dwCursorPositionX"))
            DllStructSetData($pSmallRect, "Bottom", DllStructGetData($pConsoleScreenBufferInfo, "dwCursorPositionY"))
            If _ReadConsoleOutput($hStdOut, $pBuffer, $pSmallRect) Then
                Local $sPercent = ""
                For $i = 0 To 3
                    Local $pCharInfo = DllStructCreate($_CHAR_INFO, DllStructGetPtr($pBuffer) + ($i * 4))
                    $sPercent &= DllStructGetData($pCharInfo, "UnicodeChar")
                Next
                If StringRight($sPercent, 1) = "%"  Then Return Number($sPercent)
            EndIf
        EndIf
    EndIf
    Return -1
EndFunc   ;==>Read7ZipPercent

Func _GetStdHandle($nHandle)
    Local $aRet = DllCall($K32, $HWN, "GetStdHandle", $DWO, $nHandle)
    If @error Then Return SetError(@error, @extended, $INVALID_HANDLE_VALUE)
    Return $aRet[0]
EndFunc   ;==>_GetStdHandle

Func _AttachConsole($nPid)
    Local $aRet = DllCall($K32, $INT, "AttachConsole", $DWO, $nPid)
    If @error Then Return SetError(@error, @extended, False)
    Return $aRet[0]
EndFunc   ;==>_AttachConsole

Func _GetConsoleScreenBufferInfo($hConsoleOutput, $pConsoleScreenBufferInfo)
    Local $aRet = DllCall($K32, $INT, "GetConsoleScreenBufferInfo", $HWN, $hConsoleOutput, $PTR, _SafeGetPtr($pConsoleScreenBufferInfo))
    If @error Then Return SetError(@error, @extended, False)
    Return $aRet[0]
EndFunc   ;==>_GetConsoleScreenBufferInfo

Func _ReadConsoleOutput($hConsoleOutput, $pBuffer, $pSmallRect);, 65540, 0,
    Local $aRet = DllCall($K32, $INT, "ReadConsoleOutputW", $PTR, $hConsoleOutput, $INT, _SafeGetPtr($pBuffer), $INT, 65540, $INT, 0, $PTR, _SafeGetPtr($pSmallRect))
    If @error Then SetError(@error, @extended, False)
    Return $aRet[0]
EndFunc   ;==>_ReadConsoleOutput

Func _SafeGetPtr(Const ByRef $PTR)
    Local $_ptr = DllStructGetPtr($PTR)
    If @error Then $_ptr = $PTR
    Return $_ptr
EndFunc   ;==>_SafeGetPtr