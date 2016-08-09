; ------------------------------------------------------------------------------
; Script for             : ECC ImageInject!
; Script version         : v1.1.0.7
Global $ServerScriptVersion = "1100"
; Last changed           : 2012-11-09
;
; Author: Sebastiaan Ebeltjes (AKA Phoenix)
;
; NOTES: ImageInject for getting images for the ICC server.
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
#include "..\thirdparty\autoit\include\File.au3"

;Global Variables
Global $ICCServerUrl = "http://icc.phoenixinteractive.mine.nu/"
Global $EccInstallFolder = StringReplace(@Scriptdir, "\ecc-core\tools", "")
Global $EccDataBaseFile = $EccInstallFolder & "\ecc-system\database\eccdb"
Global $EccRomDataFile = $EccInstallFolder & "\ecc-system\selectedrom.ini"
Global $RomName = IniRead($EccRomDataFile, "ROMDATA", "rom_name", "")
Global $RomCrc32 = IniRead($EccRomDataFile, "ROMDATA", "rom_crc32", "")
Global $RomEccId = IniRead($EccRomDataFile, "ROMDATA", "rom_platformid", "")
Global $EccIdtFile = $EccInstallFolder & "\ecc-system\idt\cicheck.idt"
Global $EccKameleonCode = Iniread($EccInstallFolder & "\ecc-core\tools\eccKameleonCode.code", "kameleon", "code", "NOT FOUND")
Global $EccKameleon = $EccInstallFolder & "\ecc-core\tools\eccKameleonCode.au3"
Global $AutoitExe = $EccInstallFolder & "\ecc-core\thirdparty\autoit\AutoIt3.exe"
Global $SQliteExe = $EccInstallFolder & "\ecc-core\thirdparty\sqlite\sqlite.exe"
Global $SQLInstructionFile = @Scriptdir & "\sqlcommands.inst"
Global $SQLcommandFile = @Scriptdir & "\sqlcommands.cmd"
Global $ImagesINI = "images.ini"
Global $PlatFormImagesFile = "platformimages.txt"
Global $FullPlatformFlag, $CanceledFlag, $ImageCount

;Determine USER folder (set in ECC config)

Global $EccGeneralIni = $EccInstallFolder & "\ecc-user-configs\config\ecc_general.ini"
Global $EccUserPathTemp = StringReplace(Iniread($EccGeneralIni, "USER_DATA", "base_path", ""), "/", "\")
Global $EccUserPath = StringReplace($EccUserPathTemp, "..\", $EccInstallFolder & "\") ; Add full path to variable if it's an directory within the ECC structure


Global $TotalImageFileSize = 0
Global $AlreadyDownloaded = 0
Global $ImagesDownloaded = 0
Global $FullPlatformFlag = 0
Global $PlatformRomCount = 0
Global $PlatFormRomCountUser = 0
Global $TotalImageFileSize, $ErrorFlag, $PlatformRomCountUser

If FileExists($EccRomDataFile) <> 1 Then
	MsgBox(64,"ECC ImageInject", "ECC ROM datafile not found!, aborting...")
	Exit
EndIf

If $EccUserPath = "" Then
	MsgBox(64,"ECC ImageInject", "Please make sure you have ECC run once!, aborting...")
	Exit
EndIf

$IdtRead = FileOpen($EccIdtFile)
Global $EccIdt = FileRead($IdtRead)
FileClose($IdtRead)

Select
	Case $CmdLine[0] = 0
		$PlatFormRomCountUser = 1
		ImageDownload($RomEccId, $RomCrc32)
		ExitICC()

	Case $CmdLine[1] = "fullplatform"
		$FullPlatformFlag = 1

		; Exit if user wants to download from the ECC menu "ALL PLATFORMS", this is not possible, $RomEccId = ""
		If $RomEccId = "" Then
			ToolTip("You cannot download images for ALL platforms at once!", @DesktopWidth/2, @DesktopHeight/2, "ECC ImageInject", 1, 6)
			Sleep(1500)
			Exit
		EndIf

		ToolTip("Retrieving ROMlist from ECC database!", @DesktopWidth/2, @DesktopHeight/2, "ECC ImageInject", 1, 6)

		$INSTFile = Fileopen($SQLInstructionFile, 10)
		FileWriteLine($INSTFile, ".output " & $PlatFormImagesFile)
		FileWriteLine($INSTFile, "SELECT crc32 FROM fdata WHERE eccident='" & $RomEccId & "';")
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
		Sleep(1000)
		ToolTip("")

		; Exit if user has no images imported for the platform
		If FileGetSize(@ScriptDir & "\" & $PlatFormImagesFile) < 8 Then
			ToolTip("No imported ROMS found for this platform!", @DesktopWidth/2, @DesktopHeight/2, "ECC ImageInject", 1, 6)
			Sleep(1500)
			Exit
		Else
			;Count ROMS that the user has imported into ECC.
			$PlatFormRomCountUser = _FileCountLines(@ScriptDir & "\" & $PlatFormImagesFile)
		EndIf

		CreateGUI() ; Show GUI

		$IMAGEFile = Fileopen(@ScriptDir & "\" & $PlatFormImagesFile)
		While 1
			$ReadRomCRC = FileReadLine($IMAGEFile)
			If @error = -1 Then ExitLoop
			ImageDownload($RomEccId, $ReadRomCRC)
		WEnd
		FileClose($IMAGEFile)
		ExitICC()
EndSelect
ExitICC()


Func ImageDownload($iRomEccId, $iRomCrc32)
$ErrorFlag = 0
$PlatformRomCount = $PlatformRomCount + 1
FileDelete(@Scriptdir & "\" & $ImagesINI) ; Delete old file first, Inetget does not overwrite!
InetGet($ICCServerUrl & "download" & $ServerScriptVersion & ".php?idt=" & $EccIdt & "&eccid=" & $iRomEccId & "&crc32=" & $iRomCrc32 & "&file=" & $ImagesINI & "&code=" & $EccKameleonCode, @Scriptdir & "\" & $ImagesINI)
CheckForERROR()

If $FullPlatformFlag = 0 Then CreateGUI() ;Hotfix to not display the GUI (in single mode) when a ROM is not found!

_GUICtrlListView_DeleteAllItems($ImageList)
GUICtrlSetData($PlatformLabel, $iRomEccId)
GUICtrlSetData($CRCLabel, $iRomCrc32)

If $ErrorFlag = 0 Then
;Reset data
$ImageCount = 0
$AlreadyDownloaded = 0
$TotalImageFileSize = 0


; Put data in the Imagelist
$ImagesInIni = IniReadSectionNames(@Scriptdir & "\" & $ImagesINI)
$TotalImageCount = $ImagesInIni[0]
For $i = 1 To $ImagesInIni[0]
	$ImageFileSize = IniRead(@Scriptdir & "\" & $ImagesINI, $ImagesInIni[$i], "filesize", "-")
	$ImageFileType = IniRead(@Scriptdir & "\" & $ImagesINI, $ImagesInIni[$i], "filetype", "-")
	$ImageX = IniRead(@Scriptdir & "\" & $ImagesINI, $ImagesInIni[$i], "x", "-")
	$ImageY = IniRead(@Scriptdir & "\" & $ImagesINI, $ImagesInIni[$i], "y", "-")
	$TotalImageFileSize = $TotalImageFileSize + $ImageFileSize
	GUICtrlCreateListViewItem(StringReplace($ImagesInIni[$i], "_", " ") & "|" & $ImageFileType & "|" & $ImageX & "|" & $ImageY & "|" & $ImageFileSize, $ImageList)
Next


For $i = 1 To $ImagesInIni[0]
	$ImageFileSize = IniRead(@Scriptdir & "\" & $ImagesINI, $ImagesInIni[$i], "filesize", "0")
	$ImageFileType = IniRead(@Scriptdir & "\" & $ImagesINI, $ImagesInIni[$i], "filetype", "")
	$FileToDownload = "ecc_" & $iRomEccId & "_" & $iRomCrc32 & "_" & $ImagesInIni[$i] & "." & $ImageFileType ;Construct image filename
	$RomCrc32short = StringLeft($iRomCrc32, 2)
	$ImageFolderLocal = $EccUserPath & $RomEccId & "\images\" & $RomCrc32short & "\" & $iRomCrc32 & "\"
	$ImageCount = $ImageCount + 1

	GUICtrlSetData($DownloadBarTotalPlatform, (100 / $PlatFormRomCountUser) * $PlatformRomCount)

  	If FileExists($ImageFolderLocal & $FileToDownload) = 0 Then ;Do not overwrite existing files!

		GUICtrlSetData($DownloadingLabel, StringReplace($ImagesInIni[$i], "_", " "))
		GUICtrlSetData($RemainingLabelRom, $TotalImageCount - $ImageCount)
		GUICtrlSetData($RemainingLabelPlatform, $PlatFormRomCountUser - $PlatformRomCount)
		DirCreate($ImageFolderLocal)

		$FileDownloadHandle = InetGet($ICCServerUrl & "download" & $ServerScriptVersion & ".php?idt=" & $EccIdt & "&eccid=" & $iRomEccId & "&crc32=" & $iRomCrc32 & "&file=" & $FileToDownload & "&code=" & $EccKameleonCode, $ImageFolderLocal & $FileToDownload, 1, 1)

		Do
			$InetBytesRead = InetGetInfo($FileDownloadHandle, 0)

			$DownloadProcentImage = (($InetBytesRead/$ImageFileSize) * 100)
			GUICtrlSetData($DownloadBarImage, $DownloadProcentImage)

			$DownloadProcentTotal = ((($AlreadyDownloaded+$InetBytesRead)/$TotalImageFileSize) * 100)
			GUICtrlSetData($DownloadBarTotalRom, $DownloadProcentTotal)

			If GUIGetMsg($ECCIMAGEINJECTGUI) = $ButtonCancel Then
				InetClose($FileDownloadHandle) ;Close the handle to release resources.
				FileDelete($ImageFolderLocal & $FileToDownload) ;Remove the unfinished file
				ExitICC("1")
			EndIf

		Until InetGetInfo($FileDownloadHandle, 2) ;Check if the download is complete.
		$AlreadyDownloaded = $AlreadyDownloaded + $ImageFileSize
		$ImagesDownloaded = $ImagesDownloaded + 1
		Sleep(100)

	Else

		GUICtrlSetData($DownloadingLabel, "-")
		GUICtrlSetData($RemainingLabelRom, $TotalImageCount - $ImageCount)
		GUICtrlSetData($RemainingLabelPlatform, $PlatFormRomCountUser - $PlatformRomCount)
		$AlreadyDownloaded = $AlreadyDownloaded + $ImageFileSize
		$DownloadProcentTotal = ((($AlreadyDownloaded)/$TotalImageFileSize) * 100)
		GUICtrlSetData($DownloadBarTotalRom, $DownloadProcentTotal)
	EndIf

Next

EndIf
EndFunc ;ImageDownload()

Func CreateGUI()
;==============================================================================
;BEGIN *** GUI
;==============================================================================
Global $ECCIMAGEINJECTGUI = GUICreate("ECC ImageInject", 346, 349, -1, -1)
GUISetBkColor(0xFFFFFF)
Global $DownloadBarImage = GUICtrlCreateProgress(0, 192, 342, 17)
Global $DownloadBarTotalRom = GUICtrlCreateProgress(0, 240, 342, 17)
Global $ImageList = GUICtrlCreateListView("Image|Type|X|Y|Size", 0, 24, 345, 145)
Global $Label1 = GUICtrlCreateLabel("Downloading:", 0, 176, 92, 15)
GUICtrlSetFont(-1, 8, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x000000)
Global $DownloadingLabel = GUICtrlCreateLabel("-", 96, 176, 244, 15)
GUICtrlSetFont(-1, 8, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x000080)
Global $Label2 = GUICtrlCreateLabel("Total rom progress:", 0, 224, 132, 15)
GUICtrlSetFont(-1, 8, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x000000)
Global $ButtonCancel = GUICtrlCreateButton("CANCEL", 272, 312, 67, 33)
GUICtrlSetFont(-1, 9, 800, 2, "Verdana")
Global $Label3 = GUICtrlCreateLabel("Platform:", 0, 8, 60, 15)
GUICtrlSetFont(-1, 8, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x000000)
Global $Label4 = GUICtrlCreateLabel("CRC32:", 136, 8, 52, 15)
GUICtrlSetFont(-1, 8, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x000000)
Global $PlatformLabel = GUICtrlCreateLabel("-", 64, 8, 68, 15)
GUICtrlSetFont(-1, 8, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x000080)
Global $CRCLabel = GUICtrlCreateLabel("-", 184, 8, 76, 15)
GUICtrlSetFont(-1, 8, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x000080)
Global $Label5 = GUICtrlCreateLabel("Remaining:", 208, 224, 76, 15)
GUICtrlSetFont(-1, 8, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x000000)
Global $RemainingLabelRom = GUICtrlCreateLabel("-", 288, 224, 52, 15)
GUICtrlSetFont(-1, 8, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x000080)
Global $Label6 = GUICtrlCreateLabel("Total platform progress:", 0, 272, 164, 15)
GUICtrlSetFont(-1, 8, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x000000)
Global $DownloadBarTotalPlatform = GUICtrlCreateProgress(0, 288, 342, 17)
Global $Label7 = GUICtrlCreateLabel("Remaining:", 208, 272, 76, 15)
GUICtrlSetFont(-1, 8, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x000000)
Global $RemainingLabelPlatform = GUICtrlCreateLabel("-", 288, 272, 52, 15)
GUICtrlSetFont(-1, 8, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x000080)
; "GUICtrlCreateListView("Image|Type|X|Y|Size"
;==============================================================================
;END *** GUI
;==============================================================================
; Apply proper spacing!
GUICtrlSendMsg($ImageList, 0x101E, 0, 140)
GUICtrlSendMsg($ImageList, 0x101E, 1, 50)
GUICtrlSendMsg($ImageList, 0x101E, 2, 40)
GUICtrlSendMsg($ImageList, 0x101E, 3, 40)
GUICtrlSendMsg($ImageList, 0x101E, 4, 70)
;==============================================================================
GUISetState(@SW_SHOW, $ECCIMAGEINJECTGUI)
GUISetIcon(@ScriptDir & "\iccImageInject.ico", "", $ECCIMAGEINJECTGUI) ;Set proper icon for the window.
EndFunc ;CreateGUI

Func ExitICC($CanceledFlag = 0)
FileDelete(@Scriptdir & "\" & $ImagesINI)
FileDelete(@Scriptdir & "\" & $PlatFormImagesFile)
If $CanceledFlag = 0 Then
	GUICtrlSetData($RemainingLabelRom, "0")
	GUICtrlSetData($RemainingLabelPlatform, "0")
	GUICtrlSetData($DownloadBarImage, "100")
	GUICtrlSetData($DownloadBarTotalRom, "100")
	GUICtrlSetData($DownloadBarTotalPlatform, "100")
EndIf
Sleep(500)
ToolTip($ImagesDownloaded & " NEW images downloaded!", @DesktopWidth/2, @DesktopHeight/2, "ECC ImageInject", 1, 6)
Sleep(2000)
Exit
EndFunc ;ExitICC()

Func CheckForERROR()
If FileGetSize(@Scriptdir & "\" & $ImagesINI) = 0 Then ;Server is offline!
	ToolTip("ERROR5: The server is offline, please try at another time!", @DesktopWidth/2, @DesktopHeight/2, "ECC ImageInject", 1, 6)
	Sleep(1500)
	Exit
EndIf

$ImagesFileHandle = FileOpen(@Scriptdir & "\" & $ImagesINI)
$sData = FileReadLine($ImagesFileHandle)
FileClose($ImagesFileHandle)

If $sData = "ERROR1" Then
	ToolTip("ERROR1: Something went wrong!, please inform the ECC team!", @DesktopWidth/2, @DesktopHeight/2, "ECC ImageInject", 1, 6)
	Sleep(1500)
	Exit
EndIf
If $sData = "ERROR2" Then
	ToolTip("ERROR2: Something went wrong!, please inform the ECC team!", @DesktopWidth/2, @DesktopHeight/2, "ECC ImageInject", 1, 6)
	Sleep(1500)
	Exit
EndIf
If $sData = "ERROR3" Then ; Code is invalid
	Run(Chr(34) & $AutoitExe & Chr(34) & " " & Chr(34) & $EccKameleon & Chr(34))
	Exit
EndIf
If $sData = "ERROR4" Then ; No imagefiles found.
	$ErrorFlag = 1
	If $FullPlatformFlag = 1 Then
		; do not show anything
	Else
		ToolTip("No images found for this ROM!", @DesktopWidth/2, @DesktopHeight/2, "ECC ImageInject", 1, 6)
		Sleep(1200)
		Exit
	EndIf
EndIf
If $ErrorFlag = 0 Then
	$TestIni = IniReadSectionNames(@Scriptdir & "\" & $ImagesINI)
	If Ubound($TestIni) = 0 Then ;Corrupt file
		ToolTip("ERROR6: Something went wrong!, please inform the ECC team!", @DesktopWidth/2, @DesktopHeight/2, "ECC ImageInject", 1, 6)
		Sleep(1500)
		Exit
	EndIf
EndIf

EndFunc ;CheckForERROR()