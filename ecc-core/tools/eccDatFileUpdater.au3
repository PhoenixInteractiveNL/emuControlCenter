; ------------------------------------------------------------------------------
; emuControlCenter DatFileUpdater (ECC-DFU)
;
; Script version         : v1.3.0.1
; Last changed           : 2016.10.09
;
; Author: Sebastiaan Ebeltjes (aka Phoenix)
;
; NOTES: Nothing yet ;-)
;
; ------------------------------------------------------------------------------
FileChangeDir(@ScriptDir)
#include "eccToolVariables.au3"

;==============================================================================
;BEGIN *** CHECK & VALIDATE
;==============================================================================
If FileExists($7zExe) <> 1 Then
	MsgBox(64,"ECC DatFileUpdater", "7zip could not be found!, aborting...")
	Exit
EndIf

If FileExists($eccDatfileInfoIni) <> 1 or FileExists($DATUtilExe) <> 1 Then
	MsgBox(64,"ECC DatFileUpdater", "Critical file(s) missing!, aborting...")
	Exit
EndIf

If DriveSpaceFree(@Scriptdir) < 150 Then
	MsgBox(64,"ECC DatFileUpdater", "There is not enough drivespace to perform DATfile updates!" & @CRLF & "You need at least 150 MB for the temporally files." & @CRLF & "You have " & Round(DriveSpaceFree(@Scriptdir), -1) & " MB free, aborting...")
	Exit
EndIf
;==============================================================================
;END *** CHECK & VALIDATE
;==============================================================================


;==============================================================================
;BEGIN *** SET VARIABLES & PREPERATION
;==============================================================================
Global $MameUpdateFileVersion
Global $DATfilePath = $eccInstallPath & "\ecc-system\datfile\"
Global $DATfileTempPath = $eccInstallPath & "\ecc-system\datfile\temp\"
Global $DATfileBackupPath = $eccInstallPath & "\ecc-system\datfile\backup\"
Global $DATfileInfoFile = $DATfileTempPath & "mameinfo.dat"
Global $DATfileMameFile = $DATfileTempPath & "mamelist.dat"
Global $DATfileMameFetch = $DATfileTempPath & "mamefetch.cmd"
Global $InstalledMameVersion = IniRead($eccDatfileInfoIni, "GENERAL", "datfile_mame_version", "")
Global $InstalledMameDateStr = IniRead($eccDatfileInfoIni, "GENERAL", "datfile_mame_date_str", "")
Global $InstalledMameDateYmd = IniRead($eccDatfileInfoIni, "GENERAL", "datfile_mame_date_ymd", "")
DirCreate($DATfileTempPath)
DirCreate($DATfileBackupPath)
;==============================================================================
;END *** SET VARIABLES & PREPERATION
;==============================================================================

If FileExists($DATfileMameFile) <> 1 Then
	$MameFile = FileOpenDialog("ECC DatFileUpdater - Select MAME.EXE where to update the DATfiles from:", $eccInstallPath & "\ecc-user\mame\emus", "Program (mame.exe;mame64.exe;mamepp.exe)", 3)
	If @error Then
		Exit
	Else
		ToolTip("Retrieving information from '" & $MameFile & "', please wait...", @DesktopWidth/2, @DesktopHeight/2, "ECC DatFileUpdater", 1, 6)
		ExecuteCMD(Chr(34) & $MameFile & Chr(34) & " -? > " & Chr(34) & $DATfileInfoFile & Chr(34))
		If FileGetSize($DATfileInfoFile) < 500 Then ;Less then 500 bytes
			ToolTip("")
			Msgbox(64, "ECC DatFileUpdater", "The file '" & $MameFile & "' is corrupt or not a real MAME executable!")
			Exit
		EndIf
		$MameTempFile = FileOpen($DATfileInfoFile, 0)
		$RawData = FileReadLine($MameTempFile)
		FileClose($MameTempFile)
		Global $MameVersion = StringMid($RawData, 7, 5)
		ToolTip("")

		If $MameVersion = $InstalledMameVersion Then
			$UpdateBox = Msgbox(324, "ECC DatFileUpdater", "DATfile info:" & @CRLF & @CRLF & "Currently installed: " & @TAB & $InstalledMameVersion & @CRLF & "Selected version: " & @TAB & $MameVersion & @CRLF & @CRLF & "Suggestion: your DATfile versions are already UP-TO-DATE, an update is not nessesary!" & @CRLF & @CRLF & "Do you want to continue with the process?")
		EndIf
		If $MameVersion < $InstalledMameVersion Then
			$UpdateBox = Msgbox(324, "ECC DatFileUpdater", "DATfile info:" & @CRLF & @CRLF & "Currently installed: " & @TAB & $InstalledMameVersion & @CRLF & "Selected version: " & @TAB & $MameVersion & @CRLF & @CRLF & "Suggestion: your selected MAME update is OLDER then the DATfiles already installed, it will be unwise to update!" & @CRLF & @CRLF & "Do you want to continue with the process?")
		EndIf
		If $MameVersion > $InstalledMameVersion Then
			$UpdateBox = Msgbox(68, "ECC DatFileUpdater", "DATfile info:" & @CRLF & @CRLF & "Currently installed: " & @TAB & $InstalledMameVersion& @CRLF & "Selected version: " & @TAB & $MameVersion & @CRLF & @CRLF & "Suggestion: your selected MAME update is NEWER then the DATfiles already installed, an update would be advisable!" & @CRLF & @CRLF & "Do you want to continue with the process?")
		EndIf

		If $UpdateBox = 6 Then ;YES selected
			$BackupBox = Msgbox(68, "ECC DatFileUpdater", "Do you want to create a backup of your existing DAT files before the update process? (recommended)")
			If $BackupBox = 6 Then ;YES selected
				ToolTip("Creating a backup of your existing DATfiles, please wait...", @DesktopWidth/2, @DesktopHeight/2, "ECC DatFileUpdater", 1, 6)
				$7zfile = $DATfileBackupPath & "eccDATbackup_" & @YEAR & @MON & @MDAY & "_" & @HOUR & @MIN & @SEC & ".7z"
				ShellExecuteWait($7zexe, "a " & Chr(34) & $7zfile & Chr(34) & " " & Chr(34) & $DATfilePath & "\*.dat" & Chr(34), "", "", @SW_HIDE)
				ToolTip("")
				Msgbox(64, "ECC DatFileUpdater", "DATfile backup '" & $7zfile & "' created!")
			EndIf

			ToolTip("Extracting main XML DATfile from " & $MameFile & "', please wait...", @DesktopWidth/2, @DesktopHeight/2, "ECC DatFileUpdater", 1, 6)
			ExecuteCMD(Chr(34) & $MameFile & Chr(34) & " -listxml >" & Chr(34) & $DATfileMameFile & Chr(34))
			ToolTip("")
			UpdatePlatforms()
			WriteIniData()
			MsgBox(64, "ECC DatFileUpdater", "DATfiles updated to v" & $MameVersion & "!")
		EndIf
	EndIf
Else
	; OBSOLETE, BUT STILL HANDY TO CREATE THE DAT FILES FOR GITHUB!!!
	$MameVersion = "0.178" ;  Manual!
	UpdatePlatforms()
	WriteIniData()
EndIf

;Remove TEMP files before exit
FileDelete($DATfileInfoFile)
Exit

Func WriteIniData()
	IniWrite($eccDatfileInfoIni, "GENERAL", "datfile_mame_version", $MameVersion)
EndFunc


Func UpdatePlatforms()
	WriteDatFileWithHeader("mame.dat", "MAME", "MAME")
	ExecuteCMD(Chr(34) & $DATUtilExe & Chr(34) & " -a " & Chr(34) & $DATfilePath & "mame.dat" & Chr(34) & " -f cmp " & Chr(34) & $DATfileMameFile & Chr(34))
	ToolTip("")

	WriteDatFileWithHeader("cps1.dat", "CPS-1", "Capcom Play System 1")
	ExecuteCMD(Chr(34) & $DATUtilExe & Chr(34) & " -G cps1.cpp -a " & Chr(34) & $DATfilePath & "cps1.dat" & Chr(34) & " -f cmp " & Chr(34) & $DATfileMameFile & Chr(34))
	ToolTip("")

	WriteDatFileWithHeader("cps2.dat", "CPS-2", "Capcom Play System 2")
	ExecuteCMD(Chr(34) & $DATUtilExe & Chr(34) & " -G cps2.cpp -a " & Chr(34) & $DATfilePath & "cps2.dat" & Chr(34) & " -f cmp " & Chr(34) & $DATfileMameFile & Chr(34))
	ToolTip("")

	WriteDatFileWithHeader("cps3.dat", "CPS-3", "Capcom Play System 3")
	ExecuteCMD(Chr(34) & $DATUtilExe & Chr(34) & " -G cps3.cpp -a " & Chr(34) & $DATfilePath & "cps3.dat" & Chr(34) & " -f cmp " & Chr(34) & $DATfileMameFile & Chr(34))
	ToolTip("")

	WriteDatFileWithHeader("zinc.dat", "ZINC", "ZiNc")
	ExecuteCMD(Chr(34) & $DATUtilExe & Chr(34) & " -G zn.cpp -a " & Chr(34) & $DATfilePath & "zinc.dat" & Chr(34) & " -f cmp " & Chr(34) & $DATfileMameFile & Chr(34))
	ToolTip("")

	WriteDatFileWithHeader("model1.dat", "MODEL-1", "Sega Model 1")
	ExecuteCMD(Chr(34) & $DATUtilExe & Chr(34) & " -G model1.cpp -a " & Chr(34) & $DATfilePath & "model1.dat" & Chr(34) & " -f cmp " & Chr(34) & $DATfileMameFile & Chr(34))
	ToolTip("")

	WriteDatFileWithHeader("model2.dat", "MODEL-2", "Sega Model 2")
	ExecuteCMD(Chr(34) & $DATUtilExe & Chr(34) & " -G model2.cpp -a " & Chr(34) & $DATfilePath & "model2.dat" & Chr(34) & " -f cmp " & Chr(34) & $DATfileMameFile & Chr(34))
	ToolTip("")

	WriteDatFileWithHeader("pgm.dat", "PGM", "PGM")
	ExecuteCMD(Chr(34) & $DATUtilExe & Chr(34) & " -G pgm.cpp -a " & Chr(34) & $DATfilePath & "pgm.dat" & Chr(34) & " -f cmp " & Chr(34) & $DATfileMameFile & Chr(34))
	ToolTip("")

	WriteDatFileWithHeader("naomi.dat", "NAOMI", "Sega Naomi")
	ExecuteCMD(Chr(34) & $DATUtilExe & Chr(34) & " -G naomi.cpp -a " & Chr(34) & $DATfilePath & "naomi.dat" & Chr(34) & " -f cmp " & Chr(34) & $DATfileMameFile & Chr(34))
	ToolTip("")

	WriteDatFileWithHeader("ng.dat", "NEOGEO", "SNK NeoGeo")
	ExecuteCMD(Chr(34) & $DATUtilExe & Chr(34) & " -G neodriv.hxx -a " & Chr(34) & $DATfilePath & "ng.dat" & Chr(34) & " -f cmp " & Chr(34) & $DATfileMameFile & Chr(34))
	ToolTip("")

	WriteDatFileWithHeader("s11.dat", "SYSTEM-11", "Namco System 11")
	ExecuteCMD(Chr(34) & $DATUtilExe & Chr(34) & " -G namcos11.cpp -a " & Chr(34) & $DATfilePath & "s11.dat" & Chr(34) & " -f cmp " & Chr(34) & $DATfileMameFile & Chr(34))
	ExecuteCMD(Chr(34) & $DATUtilExe & Chr(34) & " -G namcos12.cpp -a " & Chr(34) & $DATfilePath & "s11.dat" & Chr(34) & " -f cmp " & Chr(34) & $DATfileMameFile & Chr(34))
	ToolTip("")

	WriteDatFileWithHeader("s16.dat", "SYSTEM-16", "Sega System 16")
	ExecuteCMD(Chr(34) & $DATUtilExe & Chr(34) & " -G system16.cpp -a " & Chr(34) & $DATfilePath & "s16.dat" & Chr(34) & " -f cmp " & Chr(34) & $DATfileMameFile & Chr(34))
	ExecuteCMD(Chr(34) & $DATUtilExe & Chr(34) & " -G segas16a.cpp -a " & Chr(34) & $DATfilePath & "s16.dat" & Chr(34) & " -f cmp " & Chr(34) & $DATfileMameFile & Chr(34))
	ExecuteCMD(Chr(34) & $DATUtilExe & Chr(34) & " -G segas16b.cpp -a " & Chr(34) & $DATfilePath & "s16.dat" & Chr(34) & " -f cmp " & Chr(34) & $DATfileMameFile & Chr(34))
	ToolTip("")

	WriteDatFileWithHeader("s18.dat", "SYSTEM-18", "Sega System 18")
	ExecuteCMD(Chr(34) & $DATUtilExe & Chr(34) & " -G system18.cpp -a " & Chr(34) & $DATfilePath & "s18.dat" & Chr(34) & " -f cmp " & Chr(34) & $DATfileMameFile & Chr(34))
	ExecuteCMD(Chr(34) & $DATUtilExe & Chr(34) & " -G segas18.cpp -a " & Chr(34) & $DATfilePath & "s18.dat" & Chr(34) & " -f cmp " & Chr(34) & $DATfileMameFile & Chr(34))
	ToolTip("")

	WriteDatFileWithHeader("s22.dat", "SYSTEM-22", "Namco System 22")
	ExecuteCMD(Chr(34) & $DATUtilExe & Chr(34) & " -G namcos21.cpp -a " & Chr(34) & $DATfilePath & "s22.dat" & Chr(34) & " -f cmp " & Chr(34) & $DATfileMameFile & Chr(34))
	ExecuteCMD(Chr(34) & $DATUtilExe & Chr(34) & " -G namcos22.cpp -a " & Chr(34) & $DATfilePath & "s22.dat" & Chr(34) & " -f cmp " & Chr(34) & $DATfileMameFile & Chr(34))
	ToolTip("")

	FileDelete($DATfileInfoFile)
	FileDelete($DATfileMameFile)
EndFunc ;UpdatePlatforms

Func WriteDatFileWithHeader($Filename, $Name, $Category)
	ToolTip("Creating '" & $Name & "' DATfile v" & $MameVersion & ", please wait...", @DesktopWidth/2, @DesktopHeight/2, "ECC DatFileUpdater", 1, 6)
	$datfile = FileOpen($DATfilePath & $Filename, 2)
	FileWriteLine($datfile, "clrmamepro (")
	FileWriteLine($datfile, @TAB & "name " & Chr(34) & $Name & Chr(34))
	FileWriteLine($datfile, @TAB & "description " & Chr(34) & $Name & Chr(34))
	FileWriteLine($datfile, @TAB & "category " & Chr(34) & $Category & Chr(34))
	FileWriteLine($datfile, @TAB & "version " & Chr(34) & $MameVersion & " (MAME)" & Chr(34))
	FileWriteLine($datfile, @TAB & "author " & Chr(34) & "MAME Developement team" & Chr(34))
	FileWriteLine($datfile, @TAB & "homepage " & Chr(34) & "Multiple Arcade Machine Emulator" & Chr(34))
	FileWriteLine($datfile, @TAB & "url " & Chr(34) & "http://mamedev.org" & Chr(34))
	FileWriteLine($datfile, @TAB & "comment " & Chr(34) & "visit our forum for more info: http://mamedev.org/forum.html" & Chr(34))
	FileWriteLine($datfile, @TAB & "forcenodump ignore")
	FileWriteLine($datfile, ")" & @CRLF & @CRLF)
	FileClose($datfile)
EndFunc ;WriteDatFileWithHeader

Func ExecuteCMD($commandtoexe)
	;Write CMD file down and execute them (these commands aren't working with RunWait of ShellexecuteWait in Autoit :S)
	;example: C:\Windows\system32\cmd.exe /c "D:\MA ME\mame.exe" -? > "C:\emu ControlCenter\ecc-system\datfile\temp\mameinfo.dat"
	$CMDFile = Fileopen($DATfileMameFetch, 10)
	FileWrite($CMDFile, $commandtoexe)
	FileClose($CMDFile)
	RunWait(Chr(34) & $DATfileMameFetch & Chr(34), "",  @SW_HIDE)
	FileDelete($DATfileMameFetch)
EndFunc ;ExecuteCMD