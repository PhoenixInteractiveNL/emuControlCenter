; ------------------------------------------------------------------------------
; emuControlCenter eccUpdate
;
; Script version         : v1.0.0.4
Global $ScriptVersion = "v1.0.0.4"
; Last changed           : 2012.08.31
;
; Author: Sebastiaan Ebeltjes (aka Phoenix)
;
; NOTES: Please do not alter this update script file!
; Altering this file could damage your ECC installation!
;
; ------------------------------------------------------------------------------
FileChangeDir(@ScriptDir)

If WinExists("eccUpdate ") Then Exit ;Exit if eccUpdate is already active, running more then 2 instances simultaneously could cause errors and may damage a ECC installation.

#include "..\thirdparty\autoit\include\ButtonConstants.au3"
#include "..\thirdparty\autoit\include\GUIConstantsEx.au3"
#include "..\thirdparty\autoit\include\GUIListBox.au3"
#include "..\thirdparty\autoit\include\StaticConstants.au3"
#include "..\thirdparty\autoit\include\WindowsConstants.au3"
#include "..\thirdparty\autoit\include\EditConstants.au3"
#include "..\thirdparty\autoit\include\GuiEdit.au3"
#include "..\thirdparty\autoit\include\ScrollBarConstants.au3"

;==============================================================================
;BEGIN *** CHECK & VALIDATE
;==============================================================================
Global $EccPath = StringReplace(@Scriptdir, "\ecc-core\tools", "")
Global $7zExe = $EccPath & "\ecc-core\thirdparty\7zip\7z.exe"
Global $AutoIt3Exe = $EccPath & "\ecc-core\thirdparty\autoit\autoit3.exe"
Global $EccExe = $EccPath & "\ecc.exe"
Global $NotepadExe = $EccPath & "\ecc-core\thirdparty\notepad++\notepad++.exe"
Global $EccUpdateLogFile = @Scriptdir & "\eccUpdate.log"

;$UpdateServerScript = "http://www.camya.com/ecc/updates/download.php"
Global $UpdateServer = "http://eccupdate.phoenixinteractive.mine.nu/" ; don't forget the / on the end!
Global $EccLocalVersionIni = $EccPath & "\ecc-system\system\info\ecc_local_version_info.ini"
Global $EccCurrentVersion = Iniread($EccLocalVersionIni, "GENERAL", "current_version", "")
Global $EccCurrentDateBuild = Iniread($EccLocalVersionIni, "GENERAL", "date_build", "")
Global $EccCurrentBuild = Iniread($EccLocalVersionIni, "GENERAL", "current_build", "")
Global $EccLocalUpdateIni = $EccPath & "\ecc-system\system\info\ecc_local_update_info.ini"
Global $EccLocalLastUpdate = Iniread($EccLocalUpdateIni, "UPDATE", "last_update", "")
Global $EccIdtFile = $EccPath & "\ecc-system\idt\cicheck.idt"
$IdtRead = FileOpen($EccIdtFile)
Global $EccIdt = FileRead($IdtRead)
FileClose($IdtRead)


Global $StartEccAfterUpdate = 0

If $CmdLine[0] > 0 Then
	If $CmdLine[1] = "/check" Then
		CheckForUpdates()
		Exit
	EndIf

	If $CmdLine[1] = "/StartEccAfterUpdate" Then $StartEccAfterUpdate = 1
EndIf
;==============================================================================
;END *** CHECK & VALIDATE
;==============================================================================

;==============================================================================
;BEGIN *** GUI
;==============================================================================
Global $ECCUPDATE = GUICreate("eccUpdate ", 506, 266, -1, -1)
GUISetBkColor(0xFFFFFF)
Global $UpdateNotes = GUICtrlCreateEdit("", 0, 0, 505, 217, BitOR($ES_AUTOVSCROLL,$ES_READONLY,$ES_WANTRETURN,$WS_VSCROLL))
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $Label1 = GUICtrlCreateLabel("eccUpdate by Sebastiaan Ebeltjes (phoenixinteractive@hotmail.com)", 104, 248, 395, 17, $SS_RIGHT)
GUICtrlSetFont(-1, 8, 400, 2, "Verdana")
Global $UpdateProgress = GUICtrlCreateProgress(8, 224, 490, 17)
;==============================================================================
;END *** GUI
;==============================================================================
GUISetIcon (@ScriptDir & "\eccUpdate.ico", "", $ECCUPDATE) ;Set proper icon for the window.
GUISetState(@SW_SHOW, $ECCUPDATE)

AddNote("**************************************************************#")
AddNote(@YEAR & "-" & @MON & "-" & @MDAY & "  / " & @HOUR & ":" & @MIN & ":" & @SEC & " ECC UPDATE (" & $ScriptVersion & ")#")
AddNote("**************************************************************#")
AddNote("Welcome to eccUpdate!, initializing...##")

AddNote("checking: validations...")
If FileExists($7zExe) <> 1 Or FileExists($EccExe) <> 1 Or FileExists($AutoIt3Exe) <> 1 Then
	AddNote("FAILED!#")
	ExitOnError()
Else
	AddNote("passed!#")
EndIf

AddNote("checking: free space...")
If DriveSpaceFree(@Scriptdir) < 100 Then
	AddNote("FAILED!, you need at least 100 MB free space to perform updates#")
	AddNote("you have only " & Round(DriveSpaceFree(@Scriptdir), -1) & " MB free!#")
	ExitOnError()
Else
	AddNote("ok!, you have " & Round(DriveSpaceFree(@Scriptdir) -1) & " MB free!#")
EndIf

AddNote("checking: writable media...")
Global $EccDummyFile = @ScriptDir & "\eccUpdate.dummy"
$FileToWrite = FileOpen($EccDummyFile, 10)
FileWrite($FileToWrite, "Dummy")
FileClose($FileToWrite)
If FileExists($EccDummyFile) Then
	If FileGetSize($EccDummyFile) > 4 Then
		AddNote("OK!, media is writable!#")
	Else
		AddNote("FAILED!, media is not writable!#")
		ExitOnError()
	EndIf
Else
	AddNote("FAILED!, media is not writable!#")
	ExitOnError()
EndIf

AddNote("checking: is 7zip working...")
ShellExecuteWait($7zexe, "a eccUpdate.7z " & Chr(34) & $EccDummyFile & Chr(34) & " -o" & Chr(34) & @ScriptDir & Chr(34) & " -y", "", "", @SW_HIDE)
If FileExists(@ScriptDir & "\eccUpdate.7z") Then
	If FileGetSize(@ScriptDir & "\eccUpdate.7z") > 100 Then
		AddNote("YES!#")
	Else
	AddNote("FAILED!, aborting update!...#")
	ExitOnError()
	EndIf
Else
	AddNote("FAILED!, aborting update!...#")
	ExitOnError()
EndIf
FileDelete($EccDummyFile)
FileDelete(@ScriptDir & "\eccUpdate.7z")

AddNote("retrieving: current ECC version...")
If $EccCurrentVersion <> "" And $EccCurrentBuild <> "" And $EccCurrentDateBuild <> "" Then
	AddNote("succes!, ECC v" & $EccCurrentVersion & " build " & $EccCurrentBuild & " (" & $EccCurrentDateBuild & ")#")
Else
	AddNote("FAILED!, could not retrieve current ECC version!#")
	ExitOnError()
EndIf

AddNote("retrieving: last installed update...")
If $EccLocalLastUpdate <> "" Then
	AddNote("succes!, last update is " & $EccLocalLastUpdate & "#")
Else
	AddNote("FAILED!, could not retrieve last installed update!#")
	ExitOnError()
EndIf

AddNote("info: update server is: " & $UpdateServer & "#")

AddNote("querying: update server...")
$ServerMessage = InetRead($UpdateServer & "update.php?idt=" & $EccIdt & "&eccversion=" & $EccCurrentVersion & "&eccbuild=" & $EccCurrentBuild & "&eccupdate=" & $EccLocalLastUpdate & "&command=message", 1)
If StringLen(BinaryToString($ServerMessage)) > 10 Then
   AddNote("succes!#")
   AddNote("*********************** Server message **********************#")
   Addnote(BinaryToString($ServerMessage) & "#")
   AddNote("*************************************************************#")
Else
	AddNote("FAILED!, updateserver offline or no internet connection available!#")
	ExitOnError()
EndIf

AddNote("checking: updates available?...")
$EccLastUpdate = BinaryToString(InetRead($UpdateServer & "update.php?idt=" & $EccIdt & "&eccversion=" & $EccCurrentVersion & "&eccbuild=" & $EccCurrentBuild & "&eccupdate=" & $EccLocalLastUpdate & "&command=lastupdate", 1))
If $EccLastUpdate > $EccLocalLastUpdate Then
	If $EccLastUpdate - $EccLocalLastUpdate > 30 Then
	    Addnote("yes,#")
		Addnote("there are more then 30 updates available!#")
		Addnote("please download a more recent version of ECC!#")
		Addnote("visit 'http://www.camya.com/ecc/' for more information.#")
		ExitOnError()
	Else
		Addnote("yes, found " & $EccLastUpdate - $EccLocalLastUpdate & " update(s).#")
	EndIf
Else
	Addnote("no, there are no updates available at this time!#")
	ExitNoUpdate()
EndIf

AddNote("checking: ECC still active?...") ;ECC needs to be closed of some file could be locked when attempt to overwrite!
If WinExists("emuControlCenter" & " v" & $EccCurrentVersion & " build:" & $EccCurrentBuild) Then
   AddNote("yes, closing ECC...#")
   WinKill("emuControlCenter" & " v" & $EccCurrentVersion & " build:" & $EccCurrentBuild)
   Sleep(1000)
   If WinExists("emuControlCenter" & " v" & $EccCurrentVersion & " build:" & $EccCurrentBuild) Then
	  AddNote("checking: ECC did not close!, updating aborted...")
	  ExitOnError()
   Else
	  Global $StartEccAfterUpdate = 1 ;ECC was running so set flag to start ecc again after installing updates!
   EndIf
Else
	AddNote("no.#")
EndIf


For $Download = $EccLocalLastUpdate + 1 To $EccLastUpdate

   Global $UpdateToDownload = Stringformat("%05s", $Download) ;Convert number to 5 digit including 0's
   Global $FileDate = BinaryToString(InetRead($UpdateServer & "update.php?idt=" & $EccIdt & "&eccversion=" & $EccCurrentVersion & "&eccbuild=" & $EccCurrentBuild & "&eccupdate=" & $UpdateToDownload & "&command=date", 1))
   Global $FileDownloadSize = InetGetSize($UpdateServer & "update.php?idt=" & $EccIdt & "&eccversion=" & $EccCurrentVersion & "&eccbuild=" & $EccCurrentBuild & "&eccupdate=" & $UpdateToDownload & "&command=download")

   AddNote("------------------------ UPDATE " & $UpdateToDownload & " ------------------------#")

   ;Download update files, 7Z
   AddNote("action: downloading update " &  $UpdateToDownload & " (" & Round($FileDownloadSize/1000, 0) & " kb)...") ;do NOT use 1024 otherwise the values of ROUND do not match!
   $FileDownloadHandle = InetGet($UpdateServer & "update.php?idt=" & $EccIdt & "&eccversion=" & $EccCurrentVersion & "&eccbuild=" & $EccCurrentBuild & "&eccupdate=" & $UpdateToDownload & "&command=download", @ScriptDir & "\ecc_update_" & $UpdateToDownload & ".7z", 1, 1)
   Do
	  $InetBytesRead = InetGetInfo($FileDownloadHandle, 0)
	  $DownloadProcent = (($InetBytesRead/$FileDownloadSize) * 100)
	  GUICtrlSetData($UpdateProgress, $DownloadProcent)

   Until InetGetInfo($FileDownloadHandle, 2) ;Check if the download is complete.
   Sleep(100)
   AddNote("succes!#")

   ;Download update instructions, INI
   AddNote("action: downloading update " &  $UpdateToDownload & " instructions...") ;do NOT use 1024 otherwise the values of ROUND do not match!
   $FileDownloadHandle = InetGet($UpdateServer & "update.php?idt=" & $EccIdt & "&eccversion=" & $EccCurrentVersion & "&eccbuild=" & $EccCurrentBuild & "&eccupdate=" & $UpdateToDownload & "&command=instructions", @ScriptDir & "\ecc_update_" & $UpdateToDownload & ".ini", 1, 1)
   Do
	  If GUIGetMsg($ECCUPDATE) = $GUI_EVENT_CLOSE Then
		 ; Catch close button.
	  EndIf
   Until InetGetInfo($FileDownloadHandle, 2) ;Check if the download is complete.
   Sleep(100)
   AddNote("succes!#")

   Global $ShortInfo = IniRead(@ScriptDir & "\ecc_update_" & $UpdateToDownload & ".ini", "UPDATE_INFO", "ShortInfo", "-")
   Global $Credits = IniRead(@ScriptDir & "\ecc_update_" & $UpdateToDownload & ".ini", "UPDATE_INFO", "Credits", "-")
   Global $Message = IniRead(@ScriptDir & "\ecc_update_" & $UpdateToDownload & ".ini", "UPDATE_INFO", "Message", "")

   If $Message <> "" Then
	  AddNote("message: " & $Message & "#")
	  MsgBox(64, "eccUpdate Message", $Message)
   EndIf

   AddNote("info: " & $ShortInfo & "#")
   AddNote("date: " & $FileDate & "#")
   AddNote("credits: " & $Credits & "#")

   $UpdateInstructions = IniReadSection(@ScriptDir & "\ecc_update_" & $UpdateToDownload & ".ini", "UPDATE_ACTION") ;$var[$i][0] = key, $var[$i][1] = value

   ;Execute update instructions
   For $i = 1 To $UpdateInstructions[0][0]

	  If $UpdateInstructions[$i][0] = "ExtractFiles" And $UpdateInstructions[$i][1] = 1 Then
		 AddNote("action: extracting update files...")
		 ShellExecuteWait($7zexe, "x " & Chr(34) & @ScriptDir & "\ecc_update_" & $UpdateToDownload & ".7z" & Chr(34) & " -o" & Chr(34) & $EccPath & Chr(34) & " -r -y", "", "", @SW_HIDE)
		 Sleep(500) ; Just to be sure 7z is closed!
		 AddNote("done!#")
	  EndIf

	  If $UpdateInstructions[$i][0] = "FileDelete" And $UpdateInstructions[$i][1] <> "" Then
		 AddNote("action: deleting file [ecc-path]\" & $UpdateInstructions[$i][1] & "...")
		 FileDelete($EccPath & "\" & $UpdateInstructions[$i][1])
		 If FileExists($EccPath & "\" & $UpdateInstructions[$i][1]) = 1 Then
			AddNote("failed!#")
		 Else
			AddNote("succes!#")
		 EndIf
	  EndIf

	  If $UpdateInstructions[$i][0] = "FolderDelete" And $UpdateInstructions[$i][1] <> "" Then
		 AddNote("action: deleting folder (incl. files) [ecc-path]\" & $UpdateInstructions[$i][1] & "...")
		 DirRemove($EccPath & "\" & $UpdateInstructions[$i][1], 1)
		 If FileExists($EccPath & "\" & $UpdateInstructions[$i][1]) = 1 Then
			AddNote("failed!#")
		 Else
			AddNote("succes!#")
		 EndIf
	  EndIf

	  If $UpdateInstructions[$i][0] = "ExecuteFile" And $UpdateInstructions[$i][1] <> "" Then
		 AddNote("action: executing file [ecc-path]\" & $UpdateInstructions[$i][1] & "...")
		 ShellExecute($EccPath & "\" & $UpdateInstructions[$i][1])
		 AddNote("done!#")
	  EndIf

	  If $UpdateInstructions[$i][0] = "ExecuteFileWait" And $UpdateInstructions[$i][1] <> "" Then
		 AddNote("action: executing file [ecc-path]\" & $UpdateInstructions[$i][1] & "...")
		 ShellExecuteWait($EccPath & "\" & $UpdateInstructions[$i][1])
		 AddNote("done!#")
	  EndIf

	  If $UpdateInstructions[$i][0] = "ForceReload" And $UpdateInstructions[$i][1] <> "" Then
		 AddNote("action: force reloading eccUpdate...#")
		 If $StartEccAfterUpdate = 1 Then
			FileDelete(@ScriptDir & "\ecc_update_" & $UpdateToDownload & ".7z")
			FileDelete(@ScriptDir & "\ecc_update_" & $UpdateToDownload & ".ini")
			IniWrite($EccLocalUpdateIni, "UPDATE", "last_update", $UpdateToDownload) ;Write lastupdate value in local INI
			Run($Autoit3Exe & " " & Chr(34) & @ScriptDir & "\eccUpdate.au3 /StartEccAfterUpdate" & Chr(34))
			Exit
		Else
			FileDelete(@ScriptDir & "\ecc_update_" & $UpdateToDownload & ".7z")
			FileDelete(@ScriptDir & "\ecc_update_" & $UpdateToDownload & ".ini")
			IniWrite($EccLocalUpdateIni, "UPDATE", "last_update", $UpdateToDownload) ;Write lastupdate value in local INI
			Run($Autoit3Exe & " " & Chr(34) & @ScriptDir & "\eccUpdate.au3" & Chr(34))
			Exit
		EndIf
	  EndIf

   Next

   AddNote("action: removing temporally update files...")
   FileDelete(@ScriptDir & "\ecc_update_" & $UpdateToDownload & ".7z")
   FileDelete(@ScriptDir & "\ecc_update_" & $UpdateToDownload & ".ini")
   AddNote("done!#")

   IniWrite($EccLocalUpdateIni, "UPDATE", "last_update", $UpdateToDownload) ;Write lastupdate value in local INI

Next
AddNote("info: all available updates downloaded!#")
InetClose($FileDownloadHandle) ;Close the handle to release resources.
GUICtrlSetData($UpdateProgress, 0)
UpdateComplete()
Exit


Func AddNote($string)
Global $totalstring
$string = StringReplace($string, "#", @CRLF)
$totalstring = $totalstring & $string
GUICtrlSetData($UpdateNotes, $totalstring)
_GUICtrlEdit_LineScroll($UpdateNotes, 0, _GUICtrlEdit_GetLineCount($UpdateNotes))
$LogToWrite = FileOpen($EccUpdateLogFile, 1)
FileWrite($LogToWrite, $string)
FileClose($LogToWrite)
EndFunc ;Addnote

Func ExitOnError()
$Choice = MsgBox(16+4, "eccUpdate", "An error occured!, would you like to view the LOG?")
If $Choice = 6 Then ;Yes
    Run($NotepadExe & " " & Chr(34) & $EccUpdateLogFile & Chr(34))
EndIf
Exit
EndFunc ;ExitOnError

Func ExitNoUpdate()
MsgBox(64, "eccUpdate", "There are currently no updates available!")
Exit
EndFunc ;ExitNoUpdate

Func UpdateComplete()
$Choice =  MsgBox(64+4, "eccUpdate", "All updates have been installed successfully!" & @CRLF & "Would you like to view the LOG?")
If $Choice = 6 Then ;Yes
    Run($NotepadExe & " " & Chr(34) & $EccUpdateLogFile & Chr(34))
EndIf
If $StartEccAfterUpdate = 1 Then Run($EccExe)
Exit
EndFunc ;UpdateComplete

Func CheckForUpdates()
$EccLastUpdate = BinaryToString(InetRead($UpdateServer & "update.php?idt=" & $EccIdt & "&eccversion=" & $EccCurrentVersion & "&eccbuild=" & $EccCurrentBuild & "&eccupdate=" & $EccLocalLastUpdate & "&command=check", 1))
If $EccLastUpdate > $EccLocalLastUpdate Then
   $Choice = MsgBox(64+4, "eccUpdate", "Found " & $EccLastUpdate - $EccLocalLastUpdate & " update(s) available for ECC, would you like to update now?")
   If $Choice = 6 Then ;Yes
	  Run($Autoit3Exe & " " & Chr(34) & @ScriptDir & "\eccUpdate.au3" & Chr(34))
   EndIf
EndIf
EndFunc ;CheckForUpdates()