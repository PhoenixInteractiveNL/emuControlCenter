; ------------------------------------------------------------------------------
; Script for             : 3D Gallery for viewing images!
; Script version         : v1.1.0.4
; Last changed           : 2012-07-06
;
; Author: Sebastiaan Ebeltjes (AKA Phoenix)
;
; NOTES: 3D Gallery script for viewing ROM images!
;
; Supported gallery's:
;
; tiltviewer			http://www.simpleviewer.net/products
; postcardviewer		http://www.simpleviewer.net/products
; simpleviewer			http://www.simpleviewer.net/products
; 3dtouchring			http://www.flashmo.com
; 3dcurvegallery		http://www.flashmo.com
; polaroidgallery		http://www.no3dfx.com/polaroid
; flshowcarouselblack	http://www.flshow.net
;
; ------------------------------------------------------------------------------
FileChangeDir(@ScriptDir)
;XML Wrapper
#include "..\..\ecc-core\thirdparty\autoit\include\XMLDomWrapper.au3"

;GUI INCLUDES
#include "..\..\ecc-core\thirdparty\autoit\include\ButtonConstants.au3"
#include "..\..\ecc-core\thirdparty\autoit\include\EditConstants.au3"
#include "..\..\ecc-core\thirdparty\autoit\include\GUIConstantsEx.au3"
#include "..\..\ecc-core\thirdparty\autoit\include\GUIListBox.au3"
#include "..\..\ecc-core\thirdparty\autoit\include\StaticConstants.au3"
#include "..\..\ecc-core\thirdparty\autoit\include\WindowsConstants.au3"

;Global Variables
Global $EccInstallFolder = StringReplace(@Scriptdir, "\ecc-system\3dgallery", "")
Global $EccRomDataFile = $EccInstallFolder & "\ecc-system\eccromdata.ini"
Global $GalleryImageUrl = "http://www.camya.com/ecc"
Global $RomName = IniRead($EccRomDataFile, "ROMDATA", "rom_name", "")
Global $RomCrc32 = IniRead($EccRomDataFile, "ROMDATA", "rom_crc32", "")
Global $RomCrc32short = StringLeft($RomCrc32, 2)
Global $RomEccId = IniRead($EccRomDataFile, "ROMDATA", "rom_eccid", "")
Global $FullPathToImageFolder = $EccInstallFolder & "\ecc-user\" & $RomEccId & "\images\" & $RomCrc32short & "\" & $RomCrc32 & "\"
Global $Teller = 0
Global $RomTypeContents

If FileExists($EccRomDataFile) <> 1 Then
	MsgBox(64,"ECC 3D Gallery", "ECC ROM datafile not found!, aborting...")
	Exit
EndIf

;Gallery variables
Global $GalleryConfigFile = @Scriptdir & "\3dgallery.ini"
Global $GalleryToUse = IniRead($GalleryConfigFile , "gallery", "start", "")
Global $GalleryResolution = StringSplit(IniRead($GalleryConfigFile , "gallery", "resolution", "1024x768"), "x")
If $GalleryResolution[0] < 2 Then ;When on error (resolution field in INI not filled properly)
	$GalleryResolutionX = @DesktopWidth
	$GalleryResolutionY = @DesktopHeight - 60
Else
	If IniRead($GalleryConfigFile , "gallery", "fullscreen", "") = 1 Then
		$GalleryResolutionX = @DesktopWidth
		$GalleryResolutionY = @DesktopHeight - 60 ;Added -60 to prevent windows controls are offscreen
	Else
		$GalleryResolutionX = $GalleryResolution[1]
		$GalleryResolutionY = $GalleryResolution[2]
	EndIf
EndIf
Global $GalleryDataFile = IniRead($GalleryConfigFile, $GalleryToUse, "datafile", "")
Global $GalleryDataFileFull = @ScriptDir & "\" & $GalleryToUse & "\" & $GalleryDataFile
Global $GalleryStartFile = IniRead($GalleryConfigFile, $GalleryToUse, "startfile", "")
Global $GalleryStartFileFull = @ScriptDir & "\" & $GalleryToUse & "\" & $GalleryStartFile
Global $GalleryEngine = IniRead($GalleryConfigFile, $GalleryToUse, "engine", "")
Global $GallerySource = IniRead($GalleryConfigFile, $GalleryToUse, "source", "")
Global $GalleryTitle = "ECC 3D Gallery [" & $GalleryToUse & "]"

Select

	Case $CmdLine[0] = 0
		BuildDataFile($GalleryToUse)
		ShowGallery($GalleryTitle, $GalleryResolutionX, $GalleryResolutionY)
		Exit

	Case $CmdLine[1] = "config"
		GalleryConfig()
		Exit

EndSelect
Exit


Func GetRomTypeContents($RomName) ;Determine "type of contents" for file
	$RomTypeContents = "Unknown"
	If StringInStr($RomName, "ingame_title") Then $RomTypeContents = "Ingame Title"
	If StringInStr($RomName, "ingame_play") Then $RomTypeContents = "Ingame Play"
	If StringInStr($RomName, "ingame_play_boss") Then $RomTypeContents = "Ingame Play: Final boss"
	If StringInStr($RomName, "ingame_loading") Then $RomTypeContents = "Ingame Loading"
	If StringInStr($RomName, "cover_front") Then $RomTypeContents = "Cover Front"
	If StringInStr($RomName, "cover_back") Then $RomTypeContents = "Cover Back"
	If StringInStr($RomName, "cover_spine") Then $RomTypeContents = "Cover Spine"
	If StringInStr($RomName, "cover_inlay") Then $RomTypeContents = "Cover Inlay"
	If StringInStr($RomName, "media_stor") Then $RomTypeContents = "Storage"
	If StringInStr($RomName, "media_flyer") Then $RomTypeContents = "Flyer"
	If StringInStr($RomName, "media_icon") Then $RomTypeContents = "Icon"
	If StringInStr($RomName, "booklet_page") Then $RomTypeContents = "Booklet Page"
	Return $RomTypeContents
EndFunc ;GetRomTypeContents

Func IsFileOk($filename)
$FileOK = 0
If StringinStr($filename, ".jpg") Then $FileOK = 1
If StringinStr($filename, ".png") Then $FileOK = 1
If StringinStr($filename, ".gif") Then $FileOK = 1
If StringinStr($filename, ".bmp") Then $FileOK = 1
Return $FileOK
EndFunc ;IsFileOk

Func ShowGallery($GalleryTitle, $GalleryResolutionX, $GalleryResolutionY)
$objExplorer = ObjCreate("shell.Explorer.2")

$ECCGallery = GUICreate($GalleryTitle, $GalleryResolutionX - 20, $GalleryResolutionY - 10, -1, -1) ;added -values to remove the scrollbars
GUICtrlCreateObj($objExplorer, 0, 0, $GalleryResolutionX, $GalleryResolutionY)
$objExplorer.navigate($GalleryStartFileFull)
GUISetIcon(@ScriptDir & "\3dgallery.ico", "", $ECCGallery) ;Set proper icon for the window.
GUISetState(@SW_SHOW, $ECCGallery)


While 1
     $nMsg = GUIGetMsg($ECCGallery)
     Switch $nMsg
         Case $GUI_EVENT_CLOSE
             Exit
     EndSwitch
Sleep(10)
WEnd
EndFunc ;ShowGallery()


Func GalleryConfig()
;==============================================================================
;BEGIN *** GUI
;==============================================================================
Global $ECC3DGALCONF = GUICreate("ECC 3D Gallery configuration", 342, 246, -1, -1)
GUISetBkColor(0xFFFFFF)
Global $Group1 = GUICtrlCreateGroup(" Select a 3D gallery ", 8, 0, 329, 169)
GUICtrlSetFont(-1, 8, 400, 2, "Verdana")
Global $GalleryList = GUICtrlCreateList("", 16, 16, 153, 123, BitOR($LBS_NOTIFY,$LBS_SORT,$WS_BORDER))
GUICtrlSetBkColor(-1, 0xA6CAF0)
Global $Label1 = GUICtrlCreateLabel("preview image:", 208, 8, 93, 17, 0)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $GalleryImage = GUICtrlCreatePic("", 176, 24, 156, 100, 0)
Global $GalleryEngineLabel = GUICtrlCreateLabel("-", 216, 128, 113, 17, 0)
GUICtrlSetFont(-1, 7, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x800000)
Global $Label6 = GUICtrlCreateLabel("website:", 16, 144, 47, 16, 0)
GUICtrlSetFont(-1, 7, 400, 0, "Verdana")
Global $Label2 = GUICtrlCreateLabel("engine:", 176, 128, 39, 16, 0)
GUICtrlSetFont(-1, 7, 400, 0, "Verdana")
Global $GalleryWebsiteLabel = GUICtrlCreateLabel("-", 64, 144, 265, 17, 0)
GUICtrlSetFont(-1, 7, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x800000)
GUICtrlSetCursor (-1, 0)
GUICtrlCreateGroup("", -99, -99, 1, 1)
Global $Group3 = GUICtrlCreateGroup(" Settings ", 8, 168, 201, 73)
GUICtrlSetFont(-1, 8, 400, 2, "Verdana")
Global $OptionFullScreen = GUICtrlCreateCheckbox("Fullscreen", 16, 216, 81, 17)
Global $Label3 = GUICtrlCreateLabel("Resolution:", 16, 192, 71, 16, 0)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
Global $InputX = GUICtrlCreateInput("", 88, 192, 41, 21, BitOR($GUI_SS_DEFAULT_INPUT,$ES_NUMBER))
Global $InputY = GUICtrlCreateInput("", 160, 192, 41, 21, BitOR($GUI_SS_DEFAULT_INPUT,$ES_NUMBER))
Global $Label4 = GUICtrlCreateLabel("X", 136, 192, 15, 24, $SS_RIGHT)
GUICtrlSetFont(-1, 12, 800, 0, "Verdana")
Global $Label5 = GUICtrlCreateLabel("width", 88, 176, 39, 16, $SS_CENTER)
GUICtrlSetFont(-1, 7, 400, 0, "Verdana")
Global $Label7 = GUICtrlCreateLabel("height", 160, 176, 39, 16, $SS_CENTER)
GUICtrlSetFont(-1, 7, 400, 0, "Verdana")
GUICtrlCreateGroup("", -99, -99, 1, 1)
Global $ButtonSelect = GUICtrlCreateButton("Select / Save", 216, 176, 121, 65)
;==============================================================================
;END *** GUI
;==============================================================================
GUISetState(@SW_SHOW, $ECC3DGALCONF)
GUISetIcon(@ScriptDir & "\3dgallery.ico", "", $ECC3DGALCONF) ;Set proper icon for the window.

; Fill the gallerylist
$FoundGallerys = IniReadSectionNames($GalleryConfigFile)
If @error Then
    MsgBox(4096, "", "Error occurred!, no INI file!")
Else
    For $i = 1 To $FoundGallerys[0]
		If $FoundGallerys[$i] <> "gallery" then GUICtrlSetData($GalleryList, $FoundGallerys[$i])
    Next
EndIf

; Set selection in the listbox to the current gallery.
If $GalleryToUse = "" Then ;Highly unlikely, but ok, we have to catch it anyway....
	_GUICtrlListBox_SetCurSel($GalleryList, 0) ;Select first value in the listbox
Else
	_GUICtrlListBox_SelectString($GalleryList, $GalleryToUse) ;Highlight the current gallery in the listbox.
EndIf

UpdateGalleryData()
GUICtrlSetData($InputX, $GalleryResolutionX)
GUICtrlSetData($InputY, $GalleryResolutionY)
If IniRead($GalleryConfigFile , "gallery", "fullscreen", "") = 1 Then GUICtrlSetState($OptionFullScreen, $GUI_CHECKED)

While 1
	$nMsg = GUIGetMsg()
	Switch $nMsg
		Case $GUI_EVENT_CLOSE ;When the cross in the right-top corner is pressed ;-)
			Exit
		Case $GalleryWebsiteLabel
			$WebsiteToShow = GUICtrlRead($GalleryWebsiteLabel)
			Run(@Comspec & " /c start " & $WebsiteToShow, "", @SW_HIDE)
		Case $GalleryList ;Something in the gallerylist is clicked.
			UpdateGalleryData()
		Case $OptionFullScreen
			If GUICtrlRead($OptionFullScreen) = $GUI_CHECKED Then
				GUICtrlSetData($InputX, @DesktopWidth)
				GUICtrlSetData($InputY, @DesktopHeight - 60) ;Added -60 to prevent windows controls are offscreen
			Else
				GUICtrlSetData($InputX, $GalleryResolutionX)
				GUICtrlSetData($InputY, $GalleryResolutionY)
			EndIf
		Case $ButtonSelect ;Button 'select/saved' is pressed
			IniWrite($GalleryConfigFile, "gallery", "start", $SelectedGallery)
			IniWrite($GalleryConfigFile, "gallery", "resolution", GUICtrlRead($InputX) & "x" & GUICtrlRead($InputY))
			If GUICtrlRead($OptionFullScreen) = $GUI_CHECKED Then IniWrite($GalleryConfigFile, "gallery", "fullscreen", "1")
			If GUICtrlRead($OptionFullScreen) = $GUI_UNCHECKED Then IniWrite($GalleryConfigFile, "gallery", "fullscreen", "0")
			Exit
	EndSwitch
WEnd
EndFunc ;GalleryConfig()


Func UpdateGalleryData()
Global $SelectedGallery = GUICtrlRead($GalleryList)
$GalleryEngine = IniRead($GalleryConfigFile, $SelectedGallery, "engine", "unknown")
$GalleryWebsite = IniRead($GalleryConfigFile, $SelectedGallery, "website", "unknown")
GUICtrlSetData($GalleryEngineLabel, $GalleryEngine)
GUICtrlSetData($GalleryWebsiteLabel, $GalleryWebsite)

; Load in the preview image if exist.
If FileExists(@ScriptDir & "\" & $SelectedGallery & "\preview.jpg") Then
	GUICtrlSetImage($GalleryImage, @ScriptDir & "\" & $SelectedGallery & "\preview.jpg")
Else
	GUICtrlSetImage($GalleryImage, @ScriptDir & "\3dgallery_nopreview.jpg")
EndIf

EndFunc ;UpdateGalleryData


Func BuildDataFile($Gallery)
;=========================================================================================
;=========================================================================================
;
;[tiltviewer]
;datafile="gallery.xml"
;startfile="index.html"
;engine="Java & Flash"
;source="http://www.simpleviewer.net/products/"
;
;Default datafile structure:
;
;<?xml version="1.0"?>
;<tiltviewergallery>
;	<photos>
;		<photo imageurl="image.jpg" linkurl="http://www.site.com">
;			<title>[TITLE]</title>
;			<description>[DESCRIPTION]</description>
;		</photo>
;		<photo imageurl="image.jpg" linkurl="http://www.site.com">
;			<title>[TITLE]</title>
;			<description>[DESCRIPTION]</description>
;		</photo>
;	</photos>
;</tiltviewergallery>

If $Gallery = "tiltviewer" Then
	FileDelete($GalleryDataFileFull)
	_XMLCreateFile($GalleryDataFileFull, "tiltviewergallery")
	_XMLFileOpen($GalleryDataFileFull)
	_XMLCreateChildNode("tiltviewergallery", "photos")

	$search = FileFindFirstFile($FullPathToImageFolder & "\*.*")
	If $search = -1 Then
		MsgBox(0, "Error", "No images found for this Rom!")
		Exit
		FileClose($search)
	EndIf

	While 1
		$file = FileFindNextFile($search)
		If @error Then ExitLoop

		If IsFileOk($file) = 1 Then
			$Teller = $Teller + 1
			_XMLCreateChildNode("tiltviewergallery/photos", "photo")
			_XMLSetAttrib("tiltviewergallery/photos/photo[" & $Teller & "]", "imageurl", $FullPathToImageFolder & $file)
			_XMLSetAttrib("tiltviewergallery/photos/photo[" & $Teller & "]", "linkurl", $GalleryImageUrl)
			_XMLCreateChildNode("tiltviewergallery/photos/photo[" & $Teller & "]", "title", GetRomTypeContents($file))
			_XMLCreateChildNode("tiltviewergallery/photos/photo[" & $Teller & "]", "description", $RomName & " [" & $RomEccId & "]")
		EndIf
	WEnd
FileClose($search)
EndIf
;=========================================================================================
;=========================================================================================
;
;[postcardviewer]
;datafile="gallery.xml"
;startfile="index.html"
;engine="Java & Flash"
;source="http://www.simpleviewer.net/products/"
;
;Default datafile structure:
;
;<?xml version="1.0"?>
;<gallery
;	cellDimension="800"
;	columns="4"
;	zoomOutPerc="15"
;	zoomInPerc="100"
;	frameWidth="20"
;	frameColor="0xFFFFFF"
;	captionColor="0xFFFFFF"
;	enableRightClickOpen="true"
;>
;	<image>
;		<url>image.jpg</url>
;		<caption>[CAPTION]</caption>
;	</image>
;	<image>
;		<url>image.jpg</url>
;		<caption>[CAPTION]</caption>
;	</image>
;</gallery>

If $Gallery = "postcardviewer" Then
	FileDelete($GalleryDataFileFull)
	_XMLCreateFile($GalleryDataFileFull, "gallery")
	_XMLFileOpen($GalleryDataFileFull)
	_XMLSetAttrib("gallery", "cellDimension", "1000")
	_XMLSetAttrib("gallery", "columns", "4")
	_XMLSetAttrib("gallery", "zoomOutPerc", "15")
	_XMLSetAttrib("gallery", "zoomInPerc", "100")
	_XMLSetAttrib("gallery", "frameWidth", "20")
	_XMLSetAttrib("gallery", "frameColor", "0xFFFFFF")
	_XMLSetAttrib("gallery", "captionColor", "0xFFFFFF")
	_XMLSetAttrib("gallery", "enableRightClickOpen", "true")
	$search = FileFindFirstFile($FullPathToImageFolder & "\*.*")
	If $search = -1 Then
		MsgBox(0, "Error", "No images found for this Rom!")
		Exit
		FileClose($search)
	EndIf

	While 1
		$file = FileFindNextFile($search)
		If @error Then ExitLoop

		If IsFileOk($file) = 1 Then
			$Teller = $Teller + 1
			_XMLCreateChildNode("gallery", "image")
			_XMLCreateChildNode("gallery/image[" & $Teller & "]", "url", $FullPathToImageFolder & $file)
			_XMLCreateChildNode("gallery/image[" & $Teller & "]", "caption", GetRomTypeContents($file))
		EndIf
	WEnd
FileClose($search)
EndIf
;=========================================================================================
;=========================================================================================
;
;[simpleviewer]
;datafile="gallery.xml"
;startfile="index.html"
;engine="Java & Flash"
;source="http://www.simpleviewer.net/products/"
;
;Default datafile structure:
;
;<?xml version="1.0"?>
;
;
;<simpleviewergallery
;	galleryStyle="MODERN"
;	title="SimpleViewer Gallery"
;	textColor="FFFFFF"
;	frameColor="FFFFFF"
;	frameWidth="20"
;	thumbPosition="LEFT"
;	thumbColumns="3"
;	thumbRows="3"
;	showOpenButton="TRUE"
;	showFullscreenButton="TRUE"
;	maxImageWidth="640"
;	maxImageHeight="640"
;	useFlickr="false"
;	flickrUserName=""
;	flickrTags=""
;	languageCode="AUTO"
;	languageList=""
;	imagePath="images/"
;	thumbPath="thumbs/"
;>
;	<image imageURL="image.jpg" thumbURL="image.jpg" linkURL="http://www.site.com" linkTarget="_blank" >
;		<caption>[CAPTION]</caption>
;	</image>
;	<image imageURL="image.jpg" thumbURL="image.jpg" linkURL="http://www.site.com" linkTarget="_blank" >
;		<caption>[CAPTION]</caption>
;	</image>
;</simpleviewergallery>

If $Gallery = "simpleviewer" Then
	FileDelete($GalleryDataFileFull)
	_XMLCreateFile($GalleryDataFileFull, "simpleviewergallery")
	_XMLFileOpen($GalleryDataFileFull)
	_XMLSetAttrib("simpleviewergallery", "galleryStyle", "MODERN")
	_XMLSetAttrib("simpleviewergallery", "title", "SimpleViewer Gallery")
	_XMLSetAttrib("simpleviewergallery", "textColor", "FFFFFF")
	_XMLSetAttrib("simpleviewergallery", "frameColor", "FFFFFF")
	_XMLSetAttrib("simpleviewergallery", "frameWidth", "20")
	_XMLSetAttrib("simpleviewergallery", "thumbPosition", "LEFT")
	_XMLSetAttrib("simpleviewergallery", "thumbColumns", "4")
	_XMLSetAttrib("simpleviewergallery", "thumbRows", "4")
	_XMLSetAttrib("simpleviewergallery", "showOpenButton", "TRUE")
	_XMLSetAttrib("simpleviewergallery", "showFullscreenButton", "TRUE")
	_XMLSetAttrib("simpleviewergallery", "maxImageWidth", "950")
	_XMLSetAttrib("simpleviewergallery", "maxImageHeight", "950")
	_XMLSetAttrib("simpleviewergallery", "useFlickr", "false")
	_XMLSetAttrib("simpleviewergallery", "flickrUserName", "")
	_XMLSetAttrib("simpleviewergallery", "flickrTags", "")
	_XMLSetAttrib("simpleviewergallery", "languageCode", "AUTO")
	_XMLSetAttrib("simpleviewergallery", "languageList", "")
	_XMLSetAttrib("simpleviewergallery", "imagePath", "")
	_XMLSetAttrib("simpleviewergallery", "thumbPath", "")

	$search = FileFindFirstFile($FullPathToImageFolder & "\*.*")
	If $search = -1 Then
		MsgBox(0, "Error", "No images found for this Rom!")
		Exit
		FileClose($search)
	EndIf

	While 1
		$file = FileFindNextFile($search)
		If @error Then ExitLoop

		If IsFileOk($file) = 1 Then
			$Teller = $Teller + 1
			_XMLCreateChildNode("simpleviewergallery", "image")
			_XMLSetAttrib("simpleviewergallery/image[" & $Teller & "]", "imageURL", $FullPathToImageFolder & $file)
			_XMLSetAttrib("simpleviewergallery/image[" & $Teller & "]", "thumbURL", $FullPathToImageFolder & $file)
			_XMLSetAttrib("simpleviewergallery/image[" & $Teller & "]", "linkURL", $GalleryImageUrl)
			_XMLSetAttrib("simpleviewergallery/image[" & $Teller & "]", "linkTarget", "_blank")
			_XMLCreateChildNode("simpleviewergallery/image[" & $Teller & "]", "caption", GetRomTypeContents($file) & " of " & $RomName & " [" & $RomEccId & "]")
		EndIf
	WEnd
FileClose($search)
EndIf
;=========================================================================================
;=========================================================================================
;
;[3dtouchring]
;datafile="flashmo_247_photo_list.xml"
;startfile="index.html"
;engine="Java & Flash"
;source="http://www.flashmo.com/"
;
;Default datafile structure:
;
;<?xml version="1.0"?>
;
;<photos>
;	<config
;		folder="photos/"
;		enable_fullscreen="true"
;		galaxy_background="true"
;		show_tooltip="true"
;		radius="440"
;		default_zoom="5"
;		thumbnail_width="100"
;		thumbnail_height="100"
;		thumbnail_back_alpha="0.25"
;		photo_border_size="10"
;		photo_border_color="#FFFFFF"
;		close_button="true"
;		previous_button="true"
;		next_button="true"
;		description="true"
;		description_bg_color="#000000"
;		description_bg_alpha="0.6"
;		css_file="flashmo_210_style.css"
;		tween_duration="0.5"
;	</config>
;	<photo>
;		<thumbnail>image.jpg</thumbnail>
;		<filename>image.jpg</filename>
;		<tooltip>{TOOLTIP]</tooltip>
;		<description>[DISCRIPTION]</description>
;	</photo>
;	<photo>
;		<thumbnail>image.jpg</thumbnail>
;		<filename>image.jpg</filename>
;		<tooltip>{TOOLTIP]</tooltip>
;		<description>[DISCRIPTION]</description>
;	</photo>
;</photos>

If $Gallery = "3dtouchring" Then
	FileDelete($GalleryDataFileFull)
	_XMLCreateFile($GalleryDataFileFull, "photos")
	_XMLFileOpen($GalleryDataFileFull)
	_XMLCreateChildNode("photos", "config")
	_XMLSetAttrib("photos/config", "folder", $FullPathToImageFolder)
	_XMLSetAttrib("photos/config", "enable_fullscreen", "true")
	_XMLSetAttrib("photos/config", "galaxy_background", "true")
	_XMLSetAttrib("photos/config", "show_tooltip", "true")
	_XMLSetAttrib("photos/config", "radius", "440")
	_XMLSetAttrib("photos/config", "default_zoom", "5")
	_XMLSetAttrib("photos/config", "thumbnail_width", "150")
	_XMLSetAttrib("photos/config", "thumbnail_height", "150")
	_XMLSetAttrib("photos/config", "thumbnail_back_alpha", "0.25")
	_XMLSetAttrib("photos/config", "photo_border_size", "10")
	_XMLSetAttrib("photos/config", "photo_border_color", "#FFFFFF")
	_XMLSetAttrib("photos/config", "close_button", "true")
	_XMLSetAttrib("photos/config", "previous_button", "true")
	_XMLSetAttrib("photos/config", "next_button", "true")
	_XMLSetAttrib("photos/config", "description", "true")
	_XMLSetAttrib("photos/config", "description_bg_color", "#000000")
	_XMLSetAttrib("photos/config", "description_bg_alpha", "0.6")
	_XMLSetAttrib("photos/config", "css_file", "flashmo_210_style.css")
	_XMLSetAttrib("photos/config", "tween_duration", "0.5")

	$search = FileFindFirstFile($FullPathToImageFolder & "\*.*")
	If $search = -1 Then
		MsgBox(0, "Error", "No images found for this Rom!")
		Exit
		FileClose($search)
	EndIf

	While 1
		$file = FileFindNextFile($search)
		If @error Then ExitLoop

		If IsFileOk($file) = 1 Then
			$Teller = $Teller + 1
			_XMLCreateChildNode("photos", "photo")
			_XMLCreateChildNode("photos/photo[" & $Teller & "]", "thumbnail", $file)
			_XMLCreateChildNode("photos/photo[" & $Teller & "]", "filename", $file)
			_XMLCreateChildNode("photos/photo[" & $Teller & "]", "tooltip", GetRomTypeContents($file))
			_XMLCreateChildNode("photos/photo[" & $Teller & "]", "description", GetRomTypeContents($file) & " of " & $RomName & " [" & $RomEccId & "]")
		EndIf
	WEnd
FileClose($search)
EndIf
;=========================================================================================
;=========================================================================================
;
;[3dcurvegallery]
;datafile="flashmo_236_photo_list.xml"
;startfile="index.html"
;engine="Java & Flash"
;source="http://www.flashmo.com/"
;
;Default datafile structure:
;
;<?xml version="1.0"?>
;
;<photos>
;	<config
;		folder="photos/"
;		enable_fullscreen="true"
;		galaxy_background="true"
;		no_of_rings="3"
;		radius="228"
;		vertical_spacing="5"
;		default_zoom="6"
;		show_tooltip="true"
;		thumbnail_width="100"
;		thumbnail_height="100"
;		thumbnail_border_size="0"
;		thumbnail_border_color="#FFFFFF"
;		thumbnail_border_alpha="1"
;		photo_border_size="10"
;		photo_border_color="#FFFFFF"
;		close_button="true"
;		previous_button="true"
;		next_button="true"
;		description="true"
;		description_bg_color="#000000"
;		description_bg_alpha="0.6"
;		css_file="flashmo_210_style.css"
;		tween_duration="0.6"
;	</config>
;	<photo>
;		<thumbnail>image.jpg</thumbnail>
;		<filename>image.jpg</filename>
;		<tooltip>{TOOLTIP]</tooltip>
;		<description>[DISCRIPTION]</description>
;	</photo>
;	<photo>
;		<thumbnail>image.jpg</thumbnail>
;		<filename>image.jpg</filename>
;		<tooltip>{TOOLTIP]</tooltip>
;		<description>[DISCRIPTION]</description>
;	</photo>
;</photos>

If $Gallery = "3dcurvegallery" Then
	FileDelete($GalleryDataFileFull)
	_XMLCreateFile($GalleryDataFileFull, "photos")
	_XMLFileOpen($GalleryDataFileFull)
	_XMLCreateChildNode("photos", "config")
	_XMLSetAttrib("photos/config", "folder", $FullPathToImageFolder)
	_XMLSetAttrib("photos/config", "enable_fullscreen", "true")
	_XMLSetAttrib("photos/config", "galaxy_background", "true")
	_XMLSetAttrib("photos/config", "no_of_rings", "1")
	_XMLSetAttrib("photos/config", "radius", "228")
	_XMLSetAttrib("photos/config", "vertical_spacing", "5")
	_XMLSetAttrib("photos/config", "default_zoom", "6")
	_XMLSetAttrib("photos/config", "show_tooltip", "true")
	_XMLSetAttrib("photos/config", "thumbnail_width", "150")
	_XMLSetAttrib("photos/config", "thumbnail_height", "150")
	_XMLSetAttrib("photos/config", "thumbnail_border_size", "0")
	_XMLSetAttrib("photos/config", "thumbnail_border_color", "#FFFFFF")
	_XMLSetAttrib("photos/config", "thumbnail_border_alpha", "1")
	_XMLSetAttrib("photos/config", "photo_border_size", "10")
	_XMLSetAttrib("photos/config", "photo_border_color", "#FFFFFF")
	_XMLSetAttrib("photos/config", "close_button", "true")
	_XMLSetAttrib("photos/config", "previous_button", "true")
	_XMLSetAttrib("photos/config", "next_button", "true")
	_XMLSetAttrib("photos/config", "description", "true")
	_XMLSetAttrib("photos/config", "description_bg_color", "#000000")
	_XMLSetAttrib("photos/config", "description_bg_alpha", "0.6")
	_XMLSetAttrib("photos/config", "css_file", "flashmo_210_style.css")
	_XMLSetAttrib("photos/config", "tween_duration", "0.6")

	$search = FileFindFirstFile($FullPathToImageFolder & "\*.*")
	If $search = -1 Then
		MsgBox(0, "Error", "No images found for this Rom!")
		Exit
		FileClose($search)
	EndIf

	While 1
		$file = FileFindNextFile($search)
		If @error Then ExitLoop

		If IsFileOk($file) = 1 Then
			$Teller = $Teller + 1
			_XMLCreateChildNode("photos", "photo")
			_XMLCreateChildNode("photos/photo[" & $Teller & "]", "thumbnail", $file)
			_XMLCreateChildNode("photos/photo[" & $Teller & "]", "filename", $file)
			_XMLCreateChildNode("photos/photo[" & $Teller & "]", "tooltip", GetRomTypeContents($file))
			_XMLCreateChildNode("photos/photo[" & $Teller & "]", "description", GetRomTypeContents($file) & " of " & $RomName & " [" & $RomEccId & "]")
		EndIf
	WEnd
FileClose($search)
EndIf
;=========================================================================================
;=========================================================================================
;
;[polaroidgallery]
;datafile="photos.xml"
;startfile="fullscreen.html"
;engine="Java & Flash"
;source="http://www.no3dfx.com/polaroid"
;
;Default datafile structure:
;
;<?xml version="1.0"?>
;<photos>
;	<photo desc="DISCRIPTION" url="image.jpg" />
;	<photo desc="DISCRIPTION" url="image.jpg" />
;</photos>

If $Gallery = "polaroidgallery" Then
	FileDelete($GalleryDataFileFull)
	_XMLCreateFile($GalleryDataFileFull, "photos")
	_XMLFileOpen($GalleryDataFileFull)

	$search = FileFindFirstFile($FullPathToImageFolder & "\*.*")
	If $search = -1 Then
		MsgBox(0, "Error", "No images found for this Rom!")
		Exit
		FileClose($search)
	EndIf

	While 1
		$file = FileFindNextFile($search)
		If @error Then ExitLoop

		If IsFileOk($file) = 1 Then
			$Teller = $Teller + 1
			_XMLCreateChildNode("photos", "photo")
			_XMLSetAttrib("photos/photo[" & $Teller & "]", "desc", GetRomTypeContents($file) & " of " & $RomName & " [" & $RomEccId & "]")
			_XMLSetAttrib("photos/photo[" & $Teller & "]", "url", $FullPathToImageFolder & $file)
		EndIf
	WEnd
FileClose($search)
EndIf
;=========================================================================================
;=========================================================================================
;
;[flshowcarouselblack]
;datafile="default.xml"
;startfile="fullpage.html"
;engine="Java / Flash"
;website="http://www.flshow.net"
;
;Default datafile structure:
;
;<?xml version="1.0"?>
;
;<slide_show>
;	<photo href="http://website.com" target="_self">image.jpg</photo>
;	<photo href="http://website.com" target="_self">image.jpg</photo>
;	<photo href="http://website.com" target="_self">image.jpg</photo>
;</slide_show>

If $Gallery = "flshowcarouselblack" Then
	FileDelete($GalleryDataFileFull)
	_XMLCreateFile($GalleryDataFileFull, "slide_show")
	_XMLFileOpen($GalleryDataFileFull)

	$search = FileFindFirstFile($FullPathToImageFolder & "\*.*")
	If $search = -1 Then
		MsgBox(0, "Error", "No images found for this Rom!")
		Exit
		FileClose($search)
	EndIf

	While 1
		$file = FileFindNextFile($search)
		If @error Then ExitLoop

		If IsFileOk($file) = 1 Then
			$Teller = $Teller + 1
			_XMLCreateChildNode("slide_show", "photo", $FullPathToImageFolder & $file)
			_XMLSetAttrib("slide_show/photo[" & $Teller & "]", "href", "")
			_XMLSetAttrib("slide_show/photo[" & $Teller & "]", "target", "_self")
		EndIf
	WEnd
FileClose($search)
EndIf

EndFunc ;BuildDataFile
;=========================================================================================
;=========================================================================================