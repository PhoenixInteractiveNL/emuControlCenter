; ------------------------------------------------------------------------------
; Script for             : MobyGamesImporter (MGI)
; Script version         : v1.0.0.1
; Last changed           : 2013-11-09
;
; Author: Sebastiaan Ebeltjes (AKA Phoenix)
;
; NOTES: Fetching description data isn't flawless, and may contain some unwanted strings!
;
; ------------------------------------------------------------------------------
FileChangeDir(@ScriptDir)

;GUI INCLUDES
#include "..\thirdparty\autoit\include\ButtonConstants.au3"
#include "..\thirdparty\autoit\include\EditConstants.au3"
#include "..\thirdparty\autoit\include\GUIConstantsEx.au3"
#include "..\thirdparty\autoit\include\GUIListBox.au3"
#include "..\thirdparty\autoit\include\StaticConstants.au3"
#include "..\thirdparty\autoit\include\WindowsConstants.au3"
#include "..\thirdparty\autoit\include\GuiListView.au3"
#include "..\thirdparty\autoit\include\GuiEdit.au3"
#include "..\thirdparty\autoit\include\File.au3"
#include "..\thirdparty\autoit\include\String.au3"
#include "..\thirdparty\autoit\include\URLStrings.au3"

Global $EccInstallFolder = StringReplace(@Scriptdir, "\ecc-core\tools", "")
Global $EccRomDataFile = $EccInstallFolder & "\ecc-system\selectedrom.ini"
Global $EccDataBaseFile = $EccInstallFolder & "\ecc-system\database\eccdb"
Global $eccUserCidFile = $EccInstallFolder & "\ecc-system\idt\cicheck.idt"
Global $RomEccId = IniRead($EccRomDataFile, "ROMDATA", "rom_platformid", "")
Global $RomName = IniRead($EccRomDataFile, "ROMDATA", "rom_name", "")
Global $RomPlatformName = IniRead($EccRomDataFile, "ROMDATA", "rom_platformname", "")
Global $RomCrc32 = IniRead($EccRomDataFile, "ROMDATA", "rom_crc32", "")
Global $RomMetaData = IniRead($EccRomDataFile, "ROMDATA", "rom_meta_data", "")
Global $RomUserData = IniRead($EccRomDataFile, "ROMDATA", "rom_user_data", "")
Global $SQliteExe = $EccInstallFolder & "\ecc-core\thirdparty\sqlite\sqlite.exe"
Global $SQLInstructionFile = @Scriptdir & "\sqlcommands.inst"
Global $SQLcommandFile = @Scriptdir & "\sqlcommands.cmd"
Global $PlatformDataFileRomList = "platformdata_romlist.txt"
Global $PlatformDataFileRomMeta = "platformdata_meta.txt"
Global $PlatformDataFileRomUser = "platformdata_user.txt"

Global $MobyGamesWebsite = "http://www.mobygames.com/"
Global $MobyGamesList = @ScriptDir & "\MobyGamesImporter.list"
Global $String, $Mode, $PlatFormRomCountUserList, $PlatFormRomCountUserMeta
Global $NameToSearchFor, $RomNameBack

;Determine USER folder (set in ECC config)
Global $EccGeneralIni = $EccInstallFolder & "\ecc-user-configs\config\ecc_general.ini"
Global $EccUserPathTemp = StringReplace(Iniread($EccGeneralIni, "USER_DATA", "base_path", ""), "/", "\")
Global $EccUserPath = StringReplace($EccUserPathTemp, "..\", $EccInstallFolder & "\") ; Add full path to variable if it's an directory within the ECC structure

Select
	Case $CmdLine[0] = 0
		Exit

	Case $CmdLine[1] = "platform_auto"
		$Mode = "platform_auto"

	Case $CmdLine[1] = "rom_auto"
		$Mode = "rom_auto"

	Case $CmdLine[1] = "rom_manual"
		$Mode = "rom_manual"

EndSelect

; Exit if user wants to download from the ECC menu "ALL PLATFORMS", this is not possible, $RomEccId = ""
If $RomEccId = "" Then
	ToolTip("You cannot download content for ALL platforms at once!", @DesktopWidth/2, @DesktopHeight/2, "MGI", 1, 6)
	Sleep(1500)
	Exit
Endif

; Check if this platform is available on MobyGames.
$MobyGamesListData = IniReadSection($MobyGamesList, "DATA")
For $i = 1 To $MobyGamesListData[0][0]
	If $MobyGamesListData[$i][0] = $RomEccId Then $MobyGamesId = $MobyGamesListData[$i][1]
Next
If $MobyGamesId = "" Then
	ToolTip("This platform is NOT available on Mobygames!", @DesktopWidth/2, @DesktopHeight/2, "MGI", 1, 6)
	Sleep(1500)
	Exit
EndIf

; Fix the Romname
FixRomName($RomName)


;PLATFORM AUTO MODE
If $Mode = "platform_auto" Then

$Choice = MsgBox(48+4, "MGI", "NOTE: This will overwrite any existing ECC database value if found from MobyGames.com (genre/developer/user notes/etc.)" & @CRLF & @CRLF & "Continue?")
If $Choice = 7 Then Exit

;==============================================================================
;BEGIN *** GUI
;==============================================================================
Global $MGIGUI = GUICreate("ECC MobyGamesImporter (MGI) - Platform", 497, 282, -1, -1)
GUISetBkColor(0xFFFFFF)
Global $Label3 = GUICtrlCreateLabel("ECC ID:", 272, 8, 52, 15, $SS_RIGHT)
GUICtrlSetFont(-1, 8, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x000000)
Global $eccidLabel = GUICtrlCreateLabel("-", 328, 8, 92, 15)
GUICtrlSetFont(-1, 8, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x000080)
Global $Label5 = GUICtrlCreateLabel("MG ID:", 280, 24, 44, 15, $SS_RIGHT)
GUICtrlSetFont(-1, 8, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x000000)
Global $mgidLabel = GUICtrlCreateLabel("-", 328, 24, 92, 15)
GUICtrlSetFont(-1, 8, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x000080)
Global $Picture = GUICtrlCreatePic("", 8, 8, 256, 34)
Global $ButtonCancel = GUICtrlCreateButton("CANCEL", 416, 248, 75, 25)
GUICtrlSetFont(-1, 9, 800, 2, "Verdana")
Global $ProcessingList = GUICtrlCreateEdit("", 8, 48, 481, 145, BitOR($ES_AUTOHSCROLL,$ES_READONLY,$ES_WANTRETURN,$WS_VSCROLL))
Global $BarTotalPlatform = GUICtrlCreateProgress(8, 256, 398, 17)
Global $Label6 = GUICtrlCreateLabel("Total platform progress:", 8, 240, 164, 15)
GUICtrlSetFont(-1, 8, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x000000)
Global $Label7 = GUICtrlCreateLabel("Remaining:", 280, 240, 76, 15, $SS_RIGHT)
GUICtrlSetFont(-1, 8, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x000000)
Global $RemainingPlatformLabel = GUICtrlCreateLabel("-", 360, 240, 52, 15)
GUICtrlSetFont(-1, 8, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x000080)
Global $CrcLabel = GUICtrlCreateLabel("-", 56, 216, 76, 15)
GUICtrlSetFont(-1, 8, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x000080)
Global $Label4 = GUICtrlCreateLabel("CRC32:", 8, 216, 44, 15, $SS_RIGHT)
GUICtrlSetFont(-1, 8, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x000000)
Global $Label2 = GUICtrlCreateLabel("Name:", 8, 200, 44, 15, $SS_RIGHT)
GUICtrlSetFont(-1, 8, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x000000)
Global $NameLabel = GUICtrlCreateLabel("-", 56, 200, 428, 15)
GUICtrlSetFont(-1, 8, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x000080)
;==============================================================================
;END *** GUI
;==============================================================================
GUICtrlSetImage($Picture, @ScriptDir & "\MobyGamesImporter_logo.gif")
GUISetIcon(@ScriptDir & "\MobyGamesImporter.ico", "", $MGIGUI) ;Set proper icon for the window.

; Retrieve FILE ROMlist from ECC
ToolTip("Retrieving ROMlist from ECC database!", @DesktopWidth/2, @DesktopHeight/2, "MGI", 1, 6)

$INSTFile = Fileopen($SQLInstructionFile, 10)
FileWriteLine($INSTFile, ".separator ;")
FileWriteLine($INSTFile, ".output " & $PlatformDataFileRomList)
FileWriteLine($INSTFile, "SELECT crc32, title FROM fdata WHERE eccident='" & $RomEccId & "';")
FileClose($INSTFile)

; It's not possible to execute the sqlite.exe with these command's, so we have to create a .BAT or .CMD file and then run that file.
; ShellExecuteWait($SQliteExe, Chr(34) & $EccDataBaseFile & Chr(34) & " <" & Chr(34) & $SQLcommandFile & Chr(34), @ScriptDir)
; RunWait(Chr(34) & $SQliteExe & Chr(34) & " " & Chr(34) & $EccDataBaseFile & Chr(34) & " <" & Chr(34) & $SQLcommandFile & Chr(34), @ScriptDir)

$CMDFile = Fileopen($SQLcommandFile, 10)
FileWrite($CMDFile, Chr(34) & $SQliteExe & Chr(34) & " " & Chr(34) & $EccDataBaseFile & Chr(34) & " <" & Chr(34) & $SQLInstructionFile & Chr(34))
FileClose($CMDFile)

RunWait(Chr(34) & $SQLcommandFile & Chr(34), @ScriptDir, @SW_HIDE) ; Execute the CMD file with the query

; Delete the temporally files
FileDelete($SQLInstructionFile)
FileDelete($SQLcommandFile)
Sleep(500)

; Retrieve META-data for ROMlist from ECC
ToolTip("Retrieving META-data for ROMlist from ECC database!", @DesktopWidth/2, @DesktopHeight/2, "MGI", 1, 6)

$INSTFile = Fileopen($SQLInstructionFile, 10)
FileWriteLine($INSTFile, ".separator ;")
FileWriteLine($INSTFile, ".output " & $PlatformDataFileRomMeta)
FileWriteLine($INSTFile, "SELECT crc32, name FROM mdata WHERE eccident='" & $RomEccId & "';")
FileClose($INSTFile)

; It's not possible to execute the sqlite.exe with these command's, so we have to create a .BAT or .CMD file and then run that file.
; ShellExecuteWait($SQliteExe, Chr(34) & $EccDataBaseFile & Chr(34) & " <" & Chr(34) & $SQLcommandFile & Chr(34), @ScriptDir)
; RunWait(Chr(34) & $SQliteExe & Chr(34) & " " & Chr(34) & $EccDataBaseFile & Chr(34) & " <" & Chr(34) & $SQLcommandFile & Chr(34), @ScriptDir)

$CMDFile = Fileopen($SQLcommandFile, 10)
FileWrite($CMDFile, Chr(34) & $SQliteExe & Chr(34) & " " & Chr(34) & $EccDataBaseFile & Chr(34) & " <" & Chr(34) & $SQLInstructionFile & Chr(34))
FileClose($CMDFile)

RunWait(Chr(34) & $SQLcommandFile & Chr(34), @ScriptDir, @SW_HIDE) ; Execute the CMD file with the query

; Delete the temporally files
FileDelete($SQLInstructionFile)
FileDelete($SQLcommandFile)
Sleep(500)


; Retrieve USER-data for ROMlist from ECC
ToolTip("Retrieving USER-data for ROMlist from ECC database!", @DesktopWidth/2, @DesktopHeight/2, "MGI", 1, 6)

$INSTFile = Fileopen($SQLInstructionFile, 10)
FileWriteLine($INSTFile, ".separator ;")
FileWriteLine($INSTFile, ".output " & $PlatformDataFileRomUser)
FileWriteLine($INSTFile, "SELECT crc32 FROM udata WHERE eccident='" & $RomEccId & "';")
FileClose($INSTFile)

; It's not possible to execute the sqlite.exe with these command's, so we have to create a .BAT or .CMD file and then run that file.
; ShellExecuteWait($SQliteExe, Chr(34) & $EccDataBaseFile & Chr(34) & " <" & Chr(34) & $SQLcommandFile & Chr(34), @ScriptDir)
; RunWait(Chr(34) & $SQliteExe & Chr(34) & " " & Chr(34) & $EccDataBaseFile & Chr(34) & " <" & Chr(34) & $SQLcommandFile & Chr(34), @ScriptDir)

$CMDFile = Fileopen($SQLcommandFile, 10)
FileWrite($CMDFile, Chr(34) & $SQliteExe & Chr(34) & " " & Chr(34) & $EccDataBaseFile & Chr(34) & " <" & Chr(34) & $SQLInstructionFile & Chr(34))
FileClose($CMDFile)

RunWait(Chr(34) & $SQLcommandFile & Chr(34), @ScriptDir, @SW_HIDE) ; Execute the CMD file with the query

; Delete the temporally files
FileDelete($SQLInstructionFile)
FileDelete($SQLcommandFile)
Sleep(500)
ToolTip("")


; Exit if user has no ROMS imported for the platform
If FileGetSize(@ScriptDir & "\" & $PlatformDataFileRomList) < 8 Then
	ToolTip("No imported ROMS found for this platform!", @DesktopWidth/2, @DesktopHeight/2, "EMD", 1, 6)
	Sleep(1500)
	Exit
Else
	;Count ROMS that the user has imported into ECC.
	$PlatFormRomCountList = _FileCountLines(@ScriptDir & "\" & $PlatformDataFileRomList)
	$PlatFormRomCountMeta = _FileCountLines(@ScriptDir & "\" & $PlatformDataFileRomMeta)
	$PlatFormRomCountUser = _FileCountLines(@ScriptDir & "\" & $PlatformDataFileRomUser)
EndIf

$PlatformDataFileRomList_handle = Fileopen(@ScriptDir & "\" & $PlatformDataFileRomList)
$PlatformDataFileRomMeta_handle = Fileopen(@ScriptDir & "\" & $PlatformDataFileRomMeta)
$PlatformDataFileRomUser_handle = Fileopen(@ScriptDir & "\" & $PlatformDataFileRomUser)

;Show GUI
GUISetState(@SW_SHOW, $MGIGUI)
GUICtrlSetData($eccidLabel, $RomEccId)
GUICtrlSetData($mgidLabel, $MobyGamesId)
GUICtrlSetData($RemainingPlatformLabel, $PlatFormRomCountList)

For $RomCount = 1 to $PlatFormRomCountList
	$RomMetaData = 0
	$RomUserData = 0

	$ReadRomData = StringSplit(FileReadLine($PlatformDataFileRomList_handle, $RomCount), ";") ;$ReadRomData[1] = CRC32, $ReadRomData[2] = ROM Name
	$NameToSearchFor = $ReadRomData[2]
	If @error = -1 Then ExitLoop

	;Check is there is meta-data inserted for the "name"
	For $MetaCount = 1 to $PlatFormRomCountMeta
		$ReadRomMeta = StringSplit(FileReadLine($PlatformDataFileRomMeta_handle, $MetaCount), ";") ;$ReadRomMeta[1] = CRC32, $ReadRomMeta[2] = ROM Name from META-data
		If $ReadRomMeta[1] = $ReadRomData[1] Then
			$NameToSearchFor = $ReadRomMeta[2]
			$RomMetaData = 1
			ExitLoop
		EndIf
	Next

	;Check is there is user-data inserted , needed to set the "flag" state to update or add data in the ecc database
	For $UserCount = 1 to $PlatFormRomCountUser
		$ReadRomUser = StringStripWS(FileReadLine($PlatformDataFileRomUser_handle, $UserCount), 8) ;$ReadRomUser = CRC32
		If $ReadRomUser = $ReadRomData[1] Then
			$RomUserData = 1
			ExitLoop
		EndIf
	Next

	GUICtrlSetData($NameLabel, $NameToSearchFor)
	GUICtrlSetData($CrcLabel, $ReadRomData[1])

	AddNote("- Searching for ROM/FILE name: " & $NameToSearchFor & "#")
	AddNote("  - FIXED name: " & FixRomName($NameToSearchFor) & "#")
	AddNote("  - CRC32:  " & $ReadRomData[1] & "#")

	MobyGamesGrabber(FixRomName($NameToSearchFor))
	;~ 	; How many data have we got? (game not found?)
	If $MissingData >= 4 Then
		AddNote("  - Game not found on MobyGames.com!#")
	Else
		AddNote("  - DB check: [MetaData=" & $RomMetaData & "], [UserData=" & $RomUserData & "]#")
		AddNote("  - Adding data to the database...")
		eccDatabaseWrite($RomEccId, $ReadRomData[1], FixRomName($NameToSearchFor), $Publisher, $Developer, $Released, $Genre, $Description)
		AddNote("OK!#")
	EndIf

	GUICtrlSetData($RemainingPlatformLabel, $PlatFormRomCountList - $RomCount)
	$ProcentTotal = ((($RomCount)/$PlatFormRomCountList) * 100)
	GUICtrlSetData($BarTotalPlatform, $ProcentTotal)

	;GUI Handle
	$nMsg = GUIGetMsg()
	Switch $nMsg
		Case $GUI_EVENT_CLOSE
			Exit

		Case $ButtonCancel
			Exit
	EndSwitch

Next

FileClose($PlatformDataFileRomList_handle)
FileClose($PlatformDataFileRomMeta_handle)
FileClose($PlatformDataFileRomUser_handle)
FileDelete(@ScriptDir & "\" & $PlatformDataFileRomList)
FileDelete(@ScriptDir & "\" & $PlatformDataFileRomMeta)
FileDelete(@ScriptDir & "\" & $PlatformDataFileRomUser)

EndIf

;ROM AUTO MODE
If $Mode = "rom_auto" Then
	ToolTip("Retrieving game information for '" & FixRomName($RomName)  & "', for platform '" & $RomPlatformName & "'...", @DesktopWidth/2, @DesktopHeight/2, "MGI", 1, 6)
	MobyGamesGrabber(FixRomName($RomName))
	ToolTip("")
	; How many data have we got? (game not found?)
	If $MissingData >= 4 Then
		ToolTip("There is no information available for '" & FixRomName($RomName)  & "', for platform '" & $RomPlatformName & "'...", @DesktopWidth/2, @DesktopHeight/2, "MGI", 1, 6)
		Sleep(2000)
		ToolTip("")
		Exit
	EndIf
	ToolTip("Writing data to the ECC database...", @DesktopWidth/2, @DesktopHeight/2, "MGI", 1, 6)
	eccDatabaseWrite($RomEccId, $RomCrc32, FixRomName($RomName), $Publisher, $Developer, $Released, $Genre, $Description)
	Sleep(1000)
	ToolTip("")
EndIf


;ROM MANUAL MODE
If $Mode = "rom_manual" Then
;==============================================================================
;BEGIN *** GUI
;==============================================================================
Global $MGIGUI = GUICreate("ECC MobyGamesImporter (MGI) - Rom", 411, 334, -1, -1)
GUISetBkColor(0xFFFFFF)
Global $Label3 = GUICtrlCreateLabel("ECC ID:", 272, 8, 52, 15, $SS_RIGHT)
GUICtrlSetFont(-1, 8, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x000000)
Global $eccidLabel = GUICtrlCreateLabel("-", 328, 8, 76, 15)
GUICtrlSetFont(-1, 8, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x000080)
Global $ButtonQuery = GUICtrlCreateButton("QUERY MG!", 288, 72, 115, 25)
GUICtrlSetFont(-1, 9, 800, 2, "Verdana")
Global $Label2 = GUICtrlCreateLabel("Name to search for platform:", 8, 56, 188, 15)
GUICtrlSetFont(-1, 8, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x000000)
Global $Label5 = GUICtrlCreateLabel("MG ID:", 280, 24, 44, 15, $SS_RIGHT)
GUICtrlSetFont(-1, 8, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x000000)
Global $mgidLabel = GUICtrlCreateLabel("-", 328, 24, 76, 15)
GUICtrlSetFont(-1, 8, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x000080)
Global $Picture = GUICtrlCreatePic("", 8, 8, 256, 34)
Global $ButtonCancel = GUICtrlCreateButton("CANCEL", 248, 304, 75, 25)
GUICtrlSetFont(-1, 9, 800, 2, "Verdana")
Global $InputName = GUICtrlCreateInput("", 8, 72, 273, 21)
Global $platformLabel = GUICtrlCreateLabel("-", 192, 56, 212, 15)
GUICtrlSetFont(-1, 8, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x000080)
Global $Label1 = GUICtrlCreateLabel("Publisher:", 16, 104, 76, 15, $SS_RIGHT)
GUICtrlSetFont(-1, 8, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x000000)
Global $Label4 = GUICtrlCreateLabel("Developer:", 8, 128, 84, 15, $SS_RIGHT)
GUICtrlSetFont(-1, 8, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x000000)
Global $Label6 = GUICtrlCreateLabel("Released:", 8, 152, 84, 15, $SS_RIGHT)
GUICtrlSetFont(-1, 8, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x000000)
Global $Label7 = GUICtrlCreateLabel("Genre:", 176, 152, 52, 15, $SS_RIGHT)
GUICtrlSetFont(-1, 8, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x000000)
Global $ButtonSave = GUICtrlCreateButton("SAVE", 328, 304, 75, 25)
GUICtrlSetFont(-1, 9, 800, 2, "Verdana")
Global $Label8 = GUICtrlCreateLabel("Description:", 8, 184, 84, 15, $SS_RIGHT)
GUICtrlSetFont(-1, 8, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x000000)
Global $InputPublisher = GUICtrlCreateInput("", 96, 104, 305, 21)
Global $InputDeveloper = GUICtrlCreateInput("", 96, 128, 305, 21)
Global $InputReleased = GUICtrlCreateInput("", 96, 152, 73, 21)
Global $InputGenre = GUICtrlCreateInput("", 240, 152, 161, 21)
Global $InputDescription = GUICtrlCreateEdit("", 96, 184, 305, 113, BitOR($ES_AUTOVSCROLL,$ES_WANTRETURN,$WS_VSCROLL))
;==============================================================================
;END *** GUI
;==============================================================================
GUICtrlSetImage($Picture, @ScriptDir & "\MobyGamesImporter_logo.gif")
GUISetIcon(@ScriptDir & "\MobyGamesImporter.ico", "", $MGIGUI) ;Set proper icon for the window.
GUICtrlSetData($eccidLabel, $RomEccId)
GUICtrlSetData($mgidLabel, $MobyGamesId)
GUICtrlSetData($platformLabel, $RomPlatformName)
GUICtrlSetData($InputName, FixRomName($RomName))

ToolTip("Retrieving game information for '" & FixRomName($RomName)  & "', for platform '" & $RomPlatformName & "'...", @DesktopWidth/2, @DesktopHeight/2, "MGI", 1, 6)
MobyGamesGrabber(FixRomName($RomName))
GUICtrlSetData($InputPublisher, $Publisher)
GUICtrlSetData($InputDeveloper, $Developer)
GUICtrlSetData($InputReleased, $Released)
GUICtrlSetData($InputGenre, $Genre)
GUICtrlSetData($InputDescription, $Description)
ToolTip("")

GUISetState(@SW_SHOW, $MGIGUI)

While 1
	$nMsg = GUIGetMsg()
	Switch $nMsg
		Case $GUI_EVENT_CLOSE
			Exit

		Case $ButtonCancel
			Exit

		Case $ButtonQuery
			ToolTip("Retrieving game information for '" & GUICtrlRead($InputName) & "', for platform '" & $RomPlatformName & "'...", @DesktopWidth/2, @DesktopHeight/2, "MGI", 1, 6)
			MobyGamesGrabber(GUICtrlRead($InputName))
			GUICtrlSetData($InputPublisher, $Publisher)
			GUICtrlSetData($InputDeveloper, $Developer)
			GUICtrlSetData($InputReleased, $Released)
			GUICtrlSetData($InputGenre, $Genre)
			GUICtrlSetData($InputDescription, $Description)
			ToolTip("")

		Case $ButtonSave
			eccDatabaseWrite($RomEccId, $RomCrc32, GUICtrlRead($InputName), GUICtrlRead($InputPublisher),  GUICtrlRead($InputDeveloper),  GUICtrlRead($InputReleased), GUICtrlRead($InputGenre), GUICtrlRead($InputDescription))
			Exit
	EndSwitch
Wend

EndIf


Func MobyGamesGrabber($NameToSearch)
; Example syntax:
; http://www.mobygames.com/game/[PLATFORM]/[GAMENAME]

Global $MissingData = 0
$MobyGamesFixedName = StringReplace($NameToSearch, "- ", "")
$MobyGamesFixedName = StringReplace($MobyGamesFixedName, "'", "")
$MobyGamesFixedName = StringReplace($MobyGamesFixedName, ", The", "")
$MobyGamesFixedName = StringReplace($MobyGamesFixedName, "Jr.", "junior")
$MobyGamesFixedName = StringReplace($MobyGamesFixedName, ".", "")
$MobyGamesFixedName = StringReplace($MobyGamesFixedName, "!", "")
$MobyGamesFixedName = StringReplace($MobyGamesFixedName, " ", "-")
$MobyGamesFixedName = StringStripWS($MobyGamesFixedName, 7)
Global $Cache = BinaryToString(InetRead("http://www.mobygames.com/game/" & $MobyGamesId & "/" & $MobyGamesFixedName, 1)) ;Get data from the website

;Publisher
Global $Publisher = "Unknown" ;Default value
Dim $Publisher_tmp_f2, $Publisher_tmp_f3
$Publisher_tmp_f1 = _StringBetween($Cache, "Published by</div>", "</div>") ;Get the "publisher" line
If UBound($Publisher_tmp_f1) > 0 Then $Publisher_tmp_f2 = _StringBetween($Publisher_tmp_f1[0], "<a href=", "</a>") ;Stripdown the line some more
If UBound($Publisher_tmp_f2) > 0 Then $Publisher_tmp_f3 = StringSplit($Publisher_tmp_f2[0], ">") ;Stripdown the line some more
If UBound($Publisher_tmp_f3) > 2 Then $Publisher = CleanString($Publisher_tmp_f3[2]) ;Get the rightmost part ([1] is the left part)
If $Publisher = "Unknown" Then $MissingData = $MissingData + 1

;Developer
Global $Developer = "Unknown" ;Default value
Dim $Developer_tmp_f2, $Developer_tmp_f3
$Developer_tmp_f1 = _StringBetween($Cache, "Developed by</div>", "</div>") ;Get the "developer" line
If UBound($Developer_tmp_f1) > 0 Then $Developer_tmp_f2 = _StringBetween($Developer_tmp_f1[0], "<a href=", "</a>") ;Stripdown the line some more
If UBound($Developer_tmp_f2) > 0 Then $Developer_tmp_f3 = StringSplit($Developer_tmp_f2[0], ">") ;Stripdown the line some more
If UBound($Developer_tmp_f3) > 2 Then $Developer = CleanString($Developer_tmp_f3[2]) ;Get the rightmost part ([1] is the left part)
If $Developer = "Unknown" Then $MissingData = $MissingData + 1

;Release year
Global $Released = "Unknown" ;Default value
Dim $Released_tmp_f2, $Released_tmp_f3
$Released_tmp_f1 = _StringBetween($Cache, "Released</div>", "</div>") ;Get the "released" line
If UBound($Released_tmp_f1) > 0 Then $Released_tmp_f2 = _StringBetween($Released_tmp_f1[0], "<a href=", "</a>") ;Stripdown the line some more
If UBound($Released_tmp_f2) > 0 Then $Released_tmp_f3 = StringSplit($Released_tmp_f2[0], ">") ;Stripdown the line some more
If UBound($Released_tmp_f3) > 2 Then $Released = CleanString($Released_tmp_f3[2]) ;Get the rightmost part ([1] is the left part)

; Fix year if it has a specific date to it, for example: Jan 29, 1996
If StringLen($Released) > 4 Then $Released = StringRight($Released, 4) ; Get the right most characters
If StringIsDigit($Released) = False Then $Released = "Unknown"

If $Released = "Unknown" Then $MissingData = $MissingData + 1

;Genre
Global $Genre = "Unknown" ;Default value
Dim $Genre_tmp_f2, $Genre_tmp_f3
$Genre_tmp_f1 = _StringBetween($Cache, "Genre</div>", "</div>") ;Get the "genre" line
If UBound($Genre_tmp_f1) > 0 Then $Genre_tmp_f2 = _StringBetween($Genre_tmp_f1[0], "<a href=", "</a>") ;Stripdown the line some more
If UBound($Genre_tmp_f2) > 0 Then $Genre_tmp_f3 = StringSplit($Genre_tmp_f2[0], ">") ;Stripdown the line some more
If UBound($Genre_tmp_f3) > 2 Then $Genre = CleanString($Genre_tmp_f3[2]) ;Get the rightmost part ([1] is the left part)
If $Genre = "Unknown" Then $MissingData = $MissingData + 1

;Description
Global $Description = "Unknown" ;Default value
$Description_tmp_f1 = _StringBetween($Cache, @TAB & @TAB & @TAB & @TAB & @TAB & @TAB & "</div>", "<a class=" & Chr(34) & "edit") ;Get the "description" line
If UBound($Description_tmp_f1) > 0 Then
	$Description = CleanString($Description_tmp_f1[0])
EndIf
If $Description = "Unknown" Then $MissingData = $MissingData + 1

EndFunc ;MobyGamesGrabber


Func eccDatabaseWrite($RomEccId, $RomCrc32, $RomName, $Publisher, $Developer, $Released, $Genre, $Description)
$RomName = StringReplace($RomName, "'", "''")

; ROM data (mdata table)
If $RomMetaData = "1" Then ; There is META-Data available, we need to UPDATE a database entry.

	;Example UPDATE syntax:
	;
	;UPDATE mdata
	;SET name = 'Adventureland', year = '1981', creator = 'Adventure International', publisher = 'Commodore'
	;WHERE eccident='vic20' AND crc32='FED52393';

	$INSTFile = Fileopen($SQLInstructionFile, 10)
	FileWriteLine($INSTFile, "UPDATE mdata")
	FileWriteLine($INSTFile, "SET name = '" & $RomName & "', year = '" & $Released & "', creator = '" & $Developer & "', publisher = '" & $Publisher & "'")
	FileWriteLine($INSTFile, "WHERE eccident='" & $RomEccId & "' AND crc32='" & $RomCrc32 & "';")
	FileClose($INSTFile)

Else ;There is no META-Data available, we need to INSERT a new database entry.

	;Example INSERT syntax:
	;
	;INSERT INTO mdata (eccident, crc32, name, year, creator, publisher)
	;VALUES ('vic20','EDDF4AD1', 'Adventureland', '1981', 'Adventure International', 'Commodore');

	$INSTFile = Fileopen($SQLInstructionFile, 10)
	FileWriteLine($INSTFile, "INSERT INTO mdata (eccident, crc32, name, year, creator, publisher)")
	FileWriteLine($INSTFile, "VALUES ('" & $RomEccId & "', '" & $RomCrc32 & "', '" & $RomName & "', '" & $Released & "', '" & $Developer & "', '" & $Publisher & "');")
	FileClose($INSTFile)

EndIf

; Review user-data (udata table)
If $RomUserData = "1" Then  ;There is USERMETA-Data "review" available, we need to UPDATE a new database entry.

	;Example UPDATE syntax:
	;
	;UPDATE udata
	;SET review_title = 'Adventureland', review_body ='This is my review'
	;WHERE eccident='vic20' AND crc32='FED52393';

	$INSTFile = Fileopen($SQLInstructionFile, 9)
	FileWriteLine($INSTFile, "UPDATE udata")
	FileWriteLine($INSTFile, "SET review_title = '" & $RomName & "', review_body = '[--- Info from MobyGames.com ---] " & $Description & "'")
	FileWriteLine($INSTFile, "WHERE eccident='" & $RomEccId & "' AND crc32='" & $RomCrc32 & "';")
	FileClose($INSTFile)

Else ;There is no USERMETA-Data "review" available, we need to INSERT a new database entry.

	;Example INSERT syntax:
	;
	;INSERT INTO udata (eccident, crc32, review_title, review_body)
	;VALUES ('vic20','EDDF4AD1', 'Adventureland', 'This is my review');

	$INSTFile = Fileopen($SQLInstructionFile, 9)
	FileWriteLine($INSTFile, "INSERT INTO udata (eccident, crc32, review_title, review_body)")
	FileWriteLine($INSTFile, "VALUES ('" & $RomEccId & "', '" & $RomCrc32 & "', '" & $RomName & "', '[--- Info from MobyGames.com ---] " & $Description & "');")
	FileClose($INSTFile)

EndIf

; Write data into database
$CMDFile = Fileopen($SQLcommandFile, 10)
FileWrite($CMDFile, Chr(34) & $SQliteExe & Chr(34) & " " & Chr(34) & $EccDataBaseFile & Chr(34) & " <" & Chr(34) & $SQLInstructionFile & Chr(34))
FileClose($CMDFile)
RunWait(Chr(34) & $SQLcommandFile & Chr(34), @ScriptDir, @SW_HIDE) ; Execute the CMD file with the query

; Delete the temporally files
FileDelete($SQLInstructionFile)
FileDelete($SQLcommandFile)

EndFunc ;eccDatabaseWrite


Func CleanString($String)
;Clean links & tags
$String = StringReplace($String, "<i>", "")

$String_tmp = _StringBetween($String, "<", ">")
If IsArray($String_tmp) Then
	For $rondje = 0 to UBound($String_tmp) - 1
		If StringLen($String_tmp[$rondje]) > 1 Then ; Do NOT delete single letter tags, like: <i> --> i
			$String = StringReplace($String, $String_tmp[$rondje], "") ;Delete everything between <>
		EndIf
	Next
Endif
$String = StringReplace($String, "<", "")
$String = StringReplace($String, ">", "")
$String = StringReplace($String, "'", "")
$String = StringReplace($String, "\", "")
$String = StringReplace($String, "/", "")

;Rebuild HTML code
$String = StringReplace($String, "&nbsp;", " ") ;Space
$String = StringReplace($String, "&quot;", Chr(34)) ; "
$String = StringReplace($String, "√∏", "o") ; ¯ in Br¯derbund -> Br√∏derbund
Return StringStripWS($String, 7)
EndFunc ;Cleanstring


Func FixRomName($RomNameToFix)
$RomNameBack = $RomNameToFix

;Fix the ROM title if nessesary
If StringInStr($RomNameBack, " (") Then
	$FixedRomName = StringSplit($RomNameBack, " (", 1)
	$RomNameBack = $FixedRomName[1]
EndIf

If StringInStr($RomNameBack, " [") Then
	$FixedRomName = StringSplit($RomNameBack, " [", 1)
	$RomNameBack = $FixedRomName[1]
EndIf

Return $RomNameBack
EndFunc ;FixRomName()


Func AddNote($string)
Global $totalstring
$string = StringReplace($string, "#", @CRLF)
$totalstring = $totalstring & $string
GUICtrlSetData($ProcessingList, $totalstring)
_GUICtrlEdit_LineScroll($ProcessingList, 0, _GUICtrlEdit_GetLineCount($ProcessingList))
EndFunc ;Addnote