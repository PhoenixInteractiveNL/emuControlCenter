; ------------------------------------------------------------------------------
; emuControlCenter DatFileUpdater (ECC-DFU)
;
; Script version         : v1.2.5.7
; Last changed           : 2012.07.04
;
; Author: Sebastiaan Ebeltjes (aka Phoenix)
; Code contributions:
;
; NOTES: Nothing yet ;-)
;
; ------------------------------------------------------------------------------
FileChangeDir(@ScriptDir)

;==============================================================================
;BEGIN *** CHECK & VALIDATE
;==============================================================================
$eccPath = StringReplace(@Scriptdir, "\ecc-core\tools", "")
Global $7zexe = $eccPath & "\ecc-core\thirdparty\7zip\7z.exe"
Global $DATfileInfoINI = $eccPath & "\ecc-system\system\info\ecc_local_datfile_info.ini"
Global $DATUtilExe = $eccPath & "\ecc-core\thirdparty\datutil\datutil.exe"

If FileExists($eccPath & "\ecc.exe") <> 1 or FileExists($eccPath & "\ecc-system\ecc.php") <> 1 Then
	MsgBox(64,"ECC DatFileUpdater", "No ECC software found!, aborting...")
	Exit
EndIf

If FileExists($7zexe) <> 1 Then
	MsgBox(64,"ECC DatFileUpdater", "7zip could not be found!, aborting...")
	Exit
EndIf

If FileExists($DATfileInfoINI) <> 1 or FileExists($DATUtilExe) <> 1 Then
	MsgBox(64,"ECC DatFileUpdater", "Critical file(s) missing!, aborting...")
	Exit
EndIf

If DriveSpaceFree(@Scriptdir) < 100 Then
	MsgBox(64,"ECC DatFileUpdater", "There is not enough drivespace to perform DATfile updates!" & @CRLF & "You need at least 100 MB for the temporally files." & @CRLF & "You have " & Round(DriveSpaceFree(@Scriptdir), -1) & " MB free, aborting...")
	Exit
EndIf
;==============================================================================
;END *** CHECK & VALIDATE
;==============================================================================


;==============================================================================
;BEGIN *** SET VARIABLES & PREPERATION
;==============================================================================
Global $MameUpdateFileVersion
Global $DATfilePath = $eccPath & "\ecc-system\datfile\"
Global $DATfileTempPath = $eccPath & "\ecc-system\datfile\temp\"
Global $DATfileBackupPath = $eccPath & "\ecc-system\datfile\backup\"
Global $DATfileInfoFile = $DATfileTempPath & "mameinfo.dat"
Global $DATfileMameFile = $DATfileTempPath & "mamelist.dat"
Global $DATfileMameFetch = $DATfileTempPath & "mamefetch.cmd"
Global $InstalledMameVersion = IniRead($DATfileInfoINI, "GENERAL", "datfile_mame_version", "")
Global $InstalledMameDateStr = IniRead($DATfileInfoINI, "GENERAL", "datfile_mame_date_str", "")
Global $InstalledMameDateYmd = IniRead($DATfileInfoINI, "GENERAL", "datfile_mame_date_ymd", "")
DirCreate($DATfileTempPath)
DirCreate($DATfileBackupPath)
;==============================================================================
;END *** SET VARIABLES & PREPERATION
;==============================================================================

If FileExists($DATfileMameFile) <> 1 Then
	$MameFile = FileOpenDialog("ECC DatFileUpdater - Select MAME.EXE where to update the DATfiles from:", $eccPath & "\ecc-user\mame\emus", "Program (mame.exe;mame64.exe;mamepp.exe)", 3)
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
		Global $MameVersion = StringMid($RawData, 11, 5)
		Global $MameDate = StringReplace($RawData, " - Multiple Arcade Machine Emulator", "")
		$MameDate = StringReplace($MameDate, $MameVersion, "")
		$MameDate = StringReplace($MameDate, "M.A.M.E. v", "")
		$MameDate = StringStripWS($MameDate, 3)
		MameCpuDates($MameDate)
		ToolTip("")

		If $MameVersion = $InstalledMameVersion Then
			$UpdateBox = Msgbox(324, "ECC DatFileUpdater", "DATfile info:" & @CRLF & @CRLF & "Currently installed: " & @TAB & $InstalledMameVersion & " " & $InstalledMameDateStr & @CRLF & "Selected version: " & @TAB & $MameVersion & " " & $MameDate & @CRLF & @CRLF & "Suggestion: your DATfile versions are already UP-TO-DATE, an update is not nessesary!" & @CRLF & @CRLF & "Do you want to continue with the process?")
		EndIf
		If $MameVersion < $InstalledMameVersion Then
			$UpdateBox = Msgbox(324, "ECC DatFileUpdater", "DATfile info:" & @CRLF & @CRLF & "Currently installed: " & @TAB & $InstalledMameVersion & " " & $InstalledMameDateStr & @CRLF & "Selected version: " & @TAB & $MameVersion & " " & $MameDate & @CRLF & @CRLF & "Suggestion: your selected MAME update is OLDER then the DATfiles already installed, it will be unwise to update!" & @CRLF & @CRLF & "Do you want to continue with the process?")
		EndIf
		If $MameVersion > $InstalledMameVersion Then
			$UpdateBox = Msgbox(68, "ECC DatFileUpdater", "DATfile info:" & @CRLF & @CRLF & "Currently installed: " & @TAB & $InstalledMameVersion & " " & $InstalledMameDateStr & @CRLF & "Selected version: " & @TAB & $MameVersion & " " & $MameDate & @CRLF & @CRLF & "Suggestion: your selected MAME update is NEWER then the DATfiles already installed, an update would be advisable!" & @CRLF & @CRLF & "Do you want to continue with the process?")
		EndIf

		If $UpdateBox = 6 Then ;YES selected
			$BackupBox = Msgbox(68, "ECC DatFileUpdater", "Do you want to create a backup of your existing DAT files before the update process? (recommended)")
			If $BackupBox = 6 Then ;YES selected
				ToolTip("Creating a backup of your existing DATfiles, please wait...", @DesktopWidth/2, @DesktopHeight/2, "ECC DatFileUpdater", 1, 6)
				$7zfile = $DATfileBackupPath & "eccDATbackup_" & @YEAR & @MON & @MDAY & "_" & @HOUR & @MIN & @SEC & ".7z"
				ShellExecuteWait($7zexe, "a " & $7zfile & " " & Chr(34) & Chr(34) & $DATfilePath &  "\*.dat" & Chr(34), "", "", @SW_HIDE)
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
	;Check if the MAME version in the ECC update is newer then the user has installed.
	$CheckMameUpdateFile = FileOpen($DATfileMameFile,0)
	For $checkline = 50 to 200
		$LineToCheck = FileReadLine($CheckMameUpdateFile, $checkline)
		If StringInStr($LineToCheck, "mame build=") Then
			Global $MameVersion = StringMid($LineToCheck, 14, 5)
			$MameUpdateFileDateBegin = StringInStr($LineToCheck, "(")
			$MameUpdateFileDateEnd = StringInStr($LineToCheck, ")")
			Global $MameDate = StringMid($LineToCheck, $MameUpdateFileDateBegin, StringLen($LineToCheck)-($MameUpdateFileDateBegin + $MameUpdateFileDateEnd) + 4)
			MameCpuDates($MameDate)
			ExitLoop
		EndIf
	Next
	FileClose($CheckMameUpdateFile)
	If $MameVersion > $InstalledMameVersion Then
		UpdatePlatforms()
		WriteIniData()
	Else
		FileDelete($DATfileMameFile)
	EndIf
EndIf

;Remove TEMP files before exit
FileDelete($DATfileInfoFile)


Exit

Func WriteIniData()
	IniWrite($DATfileInfoINI, "GENERAL", "datfile_mame_version", $MameVersion)
	IniWrite($DATfileInfoINI, "GENERAL", "datfile_mame_date_str", $MameDate)
	IniWrite($DATfileInfoINI, "GENERAL", "datfile_mame_date_ymd", $MameCPUDateYear & $MameCPUDateMonth & $MameCPUDateDay)
EndFunc


Func MameCpuDates($Date)
	; This function translates the 'humanoid' readable date into a YYYY.MM.DD version
	; Example: (Apr  3 2011) will result in 2011.04.03
	$MameCPUDate = $Date
	$MameCPUDate = StringReplace($MameCPUDate, "(", "")
	$MameCPUDate = StringReplace($MameCPUDate, ")", "")
	$MameCPUDate = StringReplace($MameCPUDate, "Jan", "01")
	$MameCPUDate = StringReplace($MameCPUDate, "Feb", "02")
	$MameCPUDate = StringReplace($MameCPUDate, "Mar", "03")
	$MameCPUDate = StringReplace($MameCPUDate, "Apr", "04")
	$MameCPUDate = StringReplace($MameCPUDate, "May", "05")
	$MameCPUDate = StringReplace($MameCPUDate, "Jun", "06")
	$MameCPUDate = StringReplace($MameCPUDate, "Jul", "07")
	$MameCPUDate = StringReplace($MameCPUDate, "Aug", "08")
	$MameCPUDate = StringReplace($MameCPUDate, "Sep", "09")
	$MameCPUDate = StringReplace($MameCPUDate, "Sept", "09")
	$MameCPUDate = StringReplace($MameCPUDate, "Oct", "10")
	$MameCPUDate = StringReplace($MameCPUDate, "Nov", "11")
	$MameCPUDate = StringReplace($MameCPUDate, "Dec", "12")
	$MameCPUDate = StringStripWS($MameCPUDate, 8)
	Global $MameCPUDateMonth = StringLeft($MameCPUDate, 2)
	Global $MameCPUDateYear = StringRight($MameCPUDate, 4)
	Global $MameCPUDateDay = $MameCPUDate
	$MameCPUDateDay = StringReplace($MameCPUDateDay, $MameCPUDateMonth, "", 1)
	$MameCPUDateDay = StringReplace($MameCPUDateDay, $MameCPUDateYear, "", 1)
	If $MameCPUDateDay < 10 Then $MameCPUDateDay = "0" & $MameCPUDateDay ; Add a 0 before the variable so you always have 2 digits
EndFunc

Func UpdatePlatforms()
	WriteDatFileWithHeader("mame.dat", "MAME", "MAME")
	ExecuteCMD(Chr(34) & $DATUtilExe & Chr(34) & " -a " & Chr(34) & $DATfilePath & "mame.dat" & Chr(34) & " -f cmp " & Chr(34) & $DATfileMameFile & Chr(34))
	ToolTip("")

	WriteDatFileWithHeader("cps1.dat", "CPS-1", "Capcom Play System 1")
	ExecuteCMD(Chr(34) & $DATUtilExe & Chr(34) & " -G cps1.c -a " & Chr(34) & $DATfilePath & "cps1.dat" & Chr(34) & " -f cmp " & Chr(34) & $DATfileMameFile & Chr(34))
	ToolTip("")

	WriteDatFileWithHeader("cps2.dat", "CPS-2", "Capcom Play System 2")
	ExecuteCMD(Chr(34) & $DATUtilExe & Chr(34) & " -G cps2.c -a " & Chr(34) & $DATfilePath & "cps2.dat" & Chr(34) & " -f cmp " & Chr(34) & $DATfileMameFile & Chr(34))
	ToolTip("")

	WriteDatFileWithHeader("cps3.dat", "CPS-3", "Capcom Play System 3")
	ExecuteCMD(Chr(34) & $DATUtilExe & Chr(34) & " -G cps3.c -a " & Chr(34) & $DATfilePath & "cps3.dat" & Chr(34) & " -f cmp " & Chr(34) & $DATfileMameFile & Chr(34))
	ToolTip("")

	WriteDatFileWithHeader("zinc.dat", "ZINC", "ZiNc")
	ExecuteCMD(Chr(34) & $DATUtilExe & Chr(34) & " -G zn.c -a " & Chr(34) & $DATfilePath & "zinc.dat" & Chr(34) & " -f cmp " & Chr(34) & $DATfileMameFile & Chr(34))
	ToolTip("")

	WriteDatFileWithHeader("model1.dat", "MODEL-1", "Sega Model 1")
	ExecuteCMD(Chr(34) & $DATUtilExe & Chr(34) & " -G model1.c -a " & Chr(34) & $DATfilePath & "model1.dat" & Chr(34) & " -f cmp " & Chr(34) & $DATfileMameFile & Chr(34))
	ToolTip("")

	WriteDatFileWithHeader("model2.dat", "MODEL-2", "Sega Model 2")
	ExecuteCMD(Chr(34) & $DATUtilExe & Chr(34) & " -G model2.c -a " & Chr(34) & $DATfilePath & "model2.dat" & Chr(34) & " -f cmp " & Chr(34) & $DATfileMameFile & Chr(34))
	ToolTip("")

	WriteDatFileWithHeader("pgm.dat", "PGM", "PGM")
	ExecuteCMD(Chr(34) & $DATUtilExe & Chr(34) & " -G pgm.c -a " & Chr(34) & $DATfilePath & "pgm.dat" & Chr(34) & " -f cmp " & Chr(34) & $DATfileMameFile & Chr(34))
	ToolTip("")

	WriteDatFileWithHeader("naomi.dat", "NAOMI", "Sega Naomi")
	ExecuteCMD(Chr(34) & $DATUtilExe & Chr(34) & " -G naomi.c -a " & Chr(34) & $DATfilePath & "naomi.dat" & Chr(34) & " -f cmp " & Chr(34) & $DATfileMameFile & Chr(34))
	ToolTip("")

	WriteDatFileWithHeader("ng.dat", "NEOGEO", "SNK NeoGeo")
	ExecuteCMD(Chr(34) & $DATUtilExe & Chr(34) & " -G neodrvr.c -a " & Chr(34) & $DATfilePath & "ng.dat" & Chr(34) & " -f cmp " & Chr(34) & $DATfileMameFile & Chr(34))
	ToolTip("")

	WriteDatFileWithHeader("s11.dat", "SYSTEM-11", "Namco System 11")
	ExecuteCMD(Chr(34) & $DATUtilExe & Chr(34) & " -G namcos11.c -a " & Chr(34) & $DATfilePath & "s11.dat" & Chr(34) & " -f cmp " & Chr(34) & $DATfileMameFile & Chr(34))
	ExecuteCMD(Chr(34) & $DATUtilExe & Chr(34) & " -G namcos12.c -a " & Chr(34) & $DATfilePath & "s11.dat" & Chr(34) & " -f cmp " & Chr(34) & $DATfileMameFile & Chr(34))
	ToolTip("")

	WriteDatFileWithHeader("s16.dat", "SYSTEM-16", "Sega System 16")
	ExecuteCMD(Chr(34) & $DATUtilExe & Chr(34) & " -G system16.c -a " & Chr(34) & $DATfilePath & "s16.dat" & Chr(34) & " -f cmp " & Chr(34) & $DATfileMameFile & Chr(34))
	ExecuteCMD(Chr(34) & $DATUtilExe & Chr(34) & " -G segas16a.c -a " & Chr(34) & $DATfilePath & "s16.dat" & Chr(34) & " -f cmp " & Chr(34) & $DATfileMameFile & Chr(34))
	ExecuteCMD(Chr(34) & $DATUtilExe & Chr(34) & " -G segas16b.c -a " & Chr(34) & $DATfilePath & "s16.dat" & Chr(34) & " -f cmp " & Chr(34) & $DATfileMameFile & Chr(34))
	ToolTip("")

	WriteDatFileWithHeader("s18.dat", "SYSTEM-18", "Sega System 18")
	ExecuteCMD(Chr(34) & $DATUtilExe & Chr(34) & " -G system18.c -a " & Chr(34) & $DATfilePath & "s18.dat" & Chr(34) & " -f cmp " & Chr(34) & $DATfileMameFile & Chr(34))
	ExecuteCMD(Chr(34) & $DATUtilExe & Chr(34) & " -G segas18.c -a " & Chr(34) & $DATfilePath & "s18.dat" & Chr(34) & " -f cmp " & Chr(34) & $DATfileMameFile & Chr(34))
	ToolTip("")

	WriteDatFileWithHeader("s22.dat", "SYSTEM-22", "Namco System 22")
	ExecuteCMD(Chr(34) & $DATUtilExe & Chr(34) & " -G namcos21.c -a " & Chr(34) & $DATfilePath & "s22.dat" & Chr(34) & " -f cmp " & Chr(34) & $DATfileMameFile & Chr(34))
	ExecuteCMD(Chr(34) & $DATUtilExe & Chr(34) & " -G namcos22.c -a " & Chr(34) & $DATfilePath & "s22.dat" & Chr(34) & " -f cmp " & Chr(34) & $DATfileMameFile & Chr(34))
	ToolTip("")

	FileDelete($DATfileInfoFile)
	FileDelete($DATfileMameFile)
EndFunc ;UpdatePlatforms

Func WriteDatFileWithHeader($Filename, $Name, $Category)
	ToolTip("Creating '" & $Name & "' DATfile v" & $MameVersion & ", please wait...", @DesktopWidth/2, @DesktopHeight/2, "ECC DatFileUpdater", 1, 6)
	$datfile = FileOpen($DATfilePath & $Filename, 2)
	FileWriteLine($datfile, "clrmamepro (")
	FileWriteLine($datfile, @TAB & "name " & Chr(34) & $Name & Chr(34))
	FileWriteLine($datfile, @TAB & "description " & Chr(34) & $Name & " " & $MameCPUDateYear & $MameCPUDateMonth & $MameCPUDateDay & Chr(34))
	FileWriteLine($datfile, @TAB & "category " & Chr(34) & $Category & Chr(34))
	FileWriteLine($datfile, @TAB & "version " & Chr(34) & $MameVersion & " (MAME)" & Chr(34))
	FileWriteLine($datfile, @TAB & "date " & Chr(34) & $MameCPUDateYear & "-" & $MameCPUDateMonth & "-" & $MameCPUDateDay & Chr(34))
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