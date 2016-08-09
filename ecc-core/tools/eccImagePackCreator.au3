; ------------------------------------------------------------------------------
; emuControlCenter ImagePackCreator (ECC-IPC)
;
; Script version         : v1.2.1.0
; Last changed           : 2010.12.24
;
;
; Author: Sebastiaan Ebeltjes (aka Phoenix)
; Code contributions: wAw (ecc forum member)
;
; NOTES: Nothing yet ;-)
;
; ------------------------------------------------------------------------------


Global $HostInfoIni = "..\..\ecc-system\system\info\ecc_local_host_info.ini"
Dim $infos, $PackStandardsNotice, $StandardsNotice

;==============================================================================
;BEGIN *** CHECK & VALIDATE
;==============================================================================

; First we need to know where ecc is installed, this is stored in 'ecc_local_host_info.ini'
If FileExists($HostInfoIni) <> 1 Then
	MsgBox(64,"ECC-IPC", "Please make sure you have run ECC at least once!, aborting...")
	Exit
EndIf

$eccPathTemp = IniRead($HostInfoIni, "ECC_HOST_INFO", "SCRIPT_NAME", "")
Global $eccPath = StringReplace($eccPathTemp, "\ecc-system\ecc.php", "")
Global $7zaexe = $eccPath & "\ecc-core\thirdparty\7zip\7za.exe"

If FileExists($eccPath & "\ecc.exe") <> 1 or FileExists($eccPath & "\ecc-system\ecc.php") <> 1 Then
	MsgBox(64,"ECC-IPC", "No ECC software found!, aborting...")
	Exit
EndIf

If FileExists(@ScriptDir & "\eccImagePackCreator_readme.txt") <> 1 Then
	MsgBox(64,"ECC-IPC", "IPC readme file could not be found!, aborting...")
	Exit
EndIf

If FileExists($7zaexe) <> 1 Then
	MsgBox(64,"ECC-IPC", "7zip could not be found!, aborting...")
	Exit
EndIf
;==============================================================================
;END *** CHECK & VALIDATE
;==============================================================================


;==============================================================================
;BEGIN *** BROWSE FOR IMAGEFOLDER
;==============================================================================
$ImagePackFolder = FileSelectFolder("ECC-IPC - Select a platform to create imagepacks.", $eccPath & "\ecc-user", "", $eccPath & "\ecc-user")

If @error Then
	MsgBox(48, "ECC-IPC", "User canceled!, aborting...")
	Exit
Else
	For $LineLocation = 1 to StringLen($ImagePackFolder)

		; Check that the user didn't select #_AUTO_UNPACKED OR #_GLOBAL
		If StringMid($ImagePackFolder, $LineLocation, 1) = "#" Then
			MsgBox(48, "ECC-IPC", "No platform selected!, aborting...")
			Exit
		EndIf

		If StringMid($ImagePackFolder, $LineLocation, 8) = "ecc-user" Then
			$SelectedFolder = StringMid($ImagePackFolder, $LineLocation + 9, StringLen($ImagePackFolder)-($LineLocation + 3))
		EndIf
	Next

	If $SelectedFolder = "" Then
		MsgBox(48, "ECC-IPC", "No choice made!, aborting...")
		Exit
	EndIf
EndIf

; If the user did select a folder inside a platform folder, for example 'snes\images' we can filter this out using 'StringSplit'.
$SelectedFolderPart = StringSplit($SelectedFolder, "\")

If Ubound($SelectedFolderPart) > 1 Then
	$ECCid = $SelectedFolderPart[1]
Else
	$ECCid = $SelectedFolder
EndIf

; If the '$ECCid' is empty then something so go seriously wrong!
If $ECCid = "" Then
	MsgBox(48, "ECC-IPC", "An error has occured, aborting...")
	Exit
EndIf
;==============================================================================
;END *** BROWSE FOR IMAGEFOLDER
;==============================================================================


;==============================================================================
;BEGIN *** INPUT IMAGEPACKVERSION
;==============================================================================
$VersionString = InputBox("ECC-IPC - Imagepack version string", "Enter the imagepack versionstring, suggested format: [YYYY]_v[N]_[N]", "2011_v1_0")

If $VersionString = "" Then
	MsgBox(48, "ECC-IPC", "No version string entered!, aborting...")
	Exit
EndIf
;==============================================================================
;END *** INPUT IMAGEPACKVERSION
;==============================================================================


;==============================================================================
;BEGIN *** SORT SLOT FILES INTO FOLDERS + CRC32 SORT
;==============================================================================
TrayTip("ECC-IPC", "Sorting image files for platform '" & $ECCid & "'.", 10, 1)

; Search for ALL PNG & JPG images and put this in an array: $RFSarray
RecursiveFileSearch($eccPath & "\ecc-user\" & $ECCid, "(?i)\.(png|jpg)", "", 1, true, 0)

; Create imagepack folder
DirCreate($eccPath & "\ecc-user-imagepacks")

; Define good images type
$png_temp = "_ingame_play|_ingame_title|_ingame_loading|_media_icon"
$png_good_array = StringSplit($png_temp, "|")

$png_7zname_temp = "-ingame-play-|-ingame-title-|-ingame-loading-|-media-icon-"
$png_good_7z_name = StringSplit($png_7zname_temp, "|")

$jpg_temp = "_cover_front|_cover_back|_cover_spine|_cover_inlay|_booklet_page|_media_flyer|_media_stor"
$jpg_good_array = StringSplit($jpg_temp, "|")

$jpg_7zname_temp = "-cover-front-|-cover-back-|-cover-spine-|-cover-inlay-|-booklet-page-|-media-flyer-|-media-stor-"
$jpg_good_7z_name = StringSplit($jpg_7zname_temp, "|")
; end imagetype define

For $ImageFile in $RFSarray

;Exit the progam is there are no images found for the specific platform
If $ImageFile = "" Then
	MsgBox(48, "ECC-IPC", "There are no images found for platform '" & $ECCid & "', aborting...")
	Exit
EndIf

	$FileName = StringSplit($ImageFile, "\")
	$location = Ubound($FileName)-1
	$ImageFileName = $FileName[$location]
	$ImageFileData = StringSplit($ImageFileName, "_")

	If Ubound($ImageFileData) > 5 Then

		$filenoext = StringSplit($ImageFileName, ".")
		$infos = StringSplit($filenoext[1], "_")
		$eccident = $infos[2]
		$crc32 = $infos[3]
		$crc32_short = StringMid($crc32, 1, 2)

		; is it a thumbfile or not?
		If StringInStr($ImageFile, "\thumb\" & $ImageFileName) Then
			; Do nothing!, we don't need thumbs!
		Else

			; ==== PNG ====
		        If StringInStr($ImageFileName, ".png") Then
				For $loop = 1 To $jpg_good_array[0]
					If StringInStr($ImageFileName, $jpg_good_array[$loop]) And $StandardsNotice = 0 Then $PackStandardsNotice = 1
				Next
				$ImageFileName = StringReplace($ImageFileName, ".png", ".png") ;convert file extension to lowercase
				For $loop = 1 To $png_good_array[0]
					If StringInStr($ImageFileName, $png_good_array[$loop]) Then FileCopy($ImageFile, $eccPath & "\ecc-user-imagepacks\temp_" & $ECCid & $png_good_array[$loop] & "\" & $ECCid & "\images\" & $crc32_short & "\" & $crc32 & "\" & $ImageFileName, 8)
				Next
			EndIf

			; ==== JPG ====
			If StringInStr($ImageFileName, ".jpg") Then
			        For $loop = 1 To $png_good_array[0]
					If StringInStr($ImageFileName, $png_good_array[$loop]) And $StandardsNotice = 0 Then $PackStandardsNotice = 1
				Next
				$ImageFileName = StringReplace($ImageFileName, ".jpg", ".jpg") ;convert file extension to lowercase
				For $loop = 1 To $jpg_good_array[0]
					If StringInStr($ImageFileName, $jpg_good_array[$loop]) Then FileCopy($ImageFile, $eccPath & "\ecc-user-imagepacks\temp_" & $ECCid & $jpg_good_array[$loop] & "\" & $ECCid & "\images\" & $crc32_short & "\" & $crc32 & "\" & $ImageFileName, 8)
				Next
			EndIf

			; Show one-time message if user has wrong filetype
			If $PackStandardsNotice = 1 Then
				MsgBox(64, "IPC Message", "Some wrong filetypes are found: " & $ImageFileName & "," & @CRLF & "please note that not every image will be taken in the imagepack!, suggested filetypes:" & @CRLF & "PNG: ingame_title/play/loading, media_icon" & @CRLF & "JPG: cover_front/back/spine/inlay, booklet_page, media_flyer/storage")
				$PackStandardsNotice = 0
				$StandardsNotice = 1
			EndIf
		EndIf
	EndIf
Next
;==============================================================================
;END *** SORT SLOT FILES INTO FOLDERS + CRC32 SORT
;==============================================================================


;==============================================================================
;BEGIN *** COMPRESS FILES OF FOLDER TO 7Z FORMAT
;==============================================================================
TrayTip("ECC-IPC", "Compressing image files for platform '" & $ECCid & "'...", 10, 1)

; ==== PNG ====
Global $progress = 0
For $loop = 1 To $png_good_array[0]
	If FileExists($eccPath & "\ecc-user-imagepacks\temp_" & $ECCid & $png_good_array[$loop] & "\" & $ECCid & "\images") Then
		ToolTip("Compressing PNG image files for platform '" & $ECCid & "'...", @DesktopWidth/2, @DesktopHeight/2, "ECC-IPC", 1, 6)
		FileCopy(@Scriptdir & "\eccImagePackCreator_readme.txt", $eccPath & "\ecc-user-imagepacks\temp_" & $ECCid & $jpg_good_array[$loop] & "\readme.txt", 1)
		$7zfile = Chr(34) & $eccPath & "\ecc-user-imagepacks\emucontrolcenter-" & $eccident & $png_good_7z_name[$loop] & $VersionString & ".7z" & Chr(34)
		ShellExecuteWait($7zaexe, "a -r " & $7zfile & " " & Chr(34) & $eccPath & "\ecc-user-imagepacks\temp_" & $ECCid & $png_good_array[$loop] & "\*.png" & Chr(34) & " " & Chr(34) & $eccPath & "\ecc-user-imagepacks\temp_" & $ECCid & $png_good_array[$loop] & "\readme.txt" & Chr(34), "", "", @SW_HIDE)
		DirRemove($eccPath & "\ecc-user-imagepacks\temp_" & $ECCid & $png_good_array[$loop], 1)
		ToolTip("")
		EndIf
Next

; ==== JPG ====
For $loop = 1 To $jpg_good_array[0]
	If FileExists($eccPath & "\ecc-user-imagepacks\temp_" & $ECCid & $jpg_good_array[$loop] & "\" & $ECCid & "\images") Then
		ToolTip("Compressing JPG image files for platform '" & $ECCid & "'...", @DesktopWidth/2, @DesktopHeight/2, "ECC-IPC", 1, 6)
		FileCopy(@Scriptdir & "\eccImagePackCreator_readme.txt", $eccPath & "\ecc-user-imagepacks\temp_" & $ECCid & $jpg_good_array[$loop] & "\readme.txt", 1)
		$7zfile = Chr(34) & $eccPath & "\ecc-user-imagepacks\emucontrolcenter-" & $eccident & $jpg_good_7z_name[$loop] & $VersionString & ".7z" & Chr(34)
		ShellExecuteWait($7zaexe, "a -r " & $7zfile & " " & Chr(34) & $eccPath & "\ecc-user-imagepacks\temp_" & $ECCid & $jpg_good_array[$loop] & "\*.jpg" & Chr(34) & " " & Chr(34) & $eccPath & "\ecc-user-imagepacks\temp_" & $ECCid & $jpg_good_array[$loop] & "\readme.txt" & Chr(34), "", "", @SW_HIDE)
		DirRemove($eccPath & "\ecc-user-imagepacks\temp_" & $ECCid & $jpg_good_array[$loop], 1)
		ToolTip("")
	EndIf
Next

;==============================================================================
;END *** COMPRESS FILES OF FOLDER TO 7Z FORMAT
;==============================================================================

MsgBox(64, "ECC-IPC", "The imagepacks for platform '" & $ECCid & "' are stored in: '" & $eccPath & "\ecc-user-imagepacks'.")
Exit

;==============================================================================
Func RecursiveFileSearch($RFSstartDir, $RFSFilepattern = ".", $RFSFolderpattern = ".", $RFSFlag = 0, $RFSrecurse = true, $RFSdepth = 0)
;==============================================================================
	If StringRight($RFSstartDir, 1) <> "\" Then $RFSstartDir &= "\"

	If $RFSdepth = 0 Then
		$RFSfilecount = DirGetSize($RFSstartDir, 1)
		Global $RFSarray[$RFSfilecount[1] + $RFSfilecount[2] + 1]
	EndIf

	$RFSsearch = FileFindFirstFile($RFSstartDir & "*.*")
	If @error Then Return

	While 1
		$RFSnext = FileFindNextFile($RFSsearch)
		If @error Then ExitLoop

		If StringInStr(FileGetAttrib($RFSstartDir & $RFSnext), "D") Then

			If $RFSrecurse AND StringRegExp($RFSnext, $RFSFolderpattern, 0) Then
				RecursiveFileSearch($RFSstartDir & $RFSnext, $RFSFilepattern, $RFSFolderpattern, $RFSFlag, $RFSrecurse, $RFSdepth + 1)
				If $RFSFlag <> 1 Then
					$RFSarray[$RFSarray[0] + 1] = $RFSstartDir & $RFSnext
				EndIf
			EndIf
		ElseIf StringRegExp($RFSnext, $RFSFilepattern, 0) AND $RFSFlag <> 2 Then
			$RFSarray[$RFSarray[0] + 1] = $RFSstartDir & $RFSnext
			$RFSarray[0] += 1
		EndIf
	WEnd
	FileClose($RFSsearch)

	If $RFSdepth = 0 Then
		Redim $RFSarray[$RFSarray[0] + 1]
		Return $RFSarray
	EndIf
EndFunc ;==>RecursiveFileSearch