; ------------------------------------------------------------------------------
; emuControlCenter gtkThemeSelect (ECC-GTKTS)
;
; Script version         : v1.0.0.2
; Last changed           : 2014.03.28
;
; Author: Sebastiaan Ebeltjes (aka Phoenix)
; Code contributions:
;
; NOTES: Nothing yet ;-)
;
; ------------------------------------------------------------------------------
FileChangeDir(@ScriptDir)
#include "eccToolVariables.au3"

;==============================================================================
;BEGIN *** CHECK & VALIDATE
;==============================================================================
If FileExists($eccInstallPath  & "\ecc.exe") <> 1 or FileExists($eccInstallPath  & "\ecc-system\ecc.php") <> 1 Then
	MsgBox(64,"ECC gtkThemeSelect", "No ECC software found!, aborting...")
	Exit
EndIf

Global $ColorGreen = "0x008000"
Global $ColorRed = "0x800000"
;==============================================================================
;END *** CHECK & VALIDATE
;==============================================================================

;==============================================================================
;BEGIN *** GUI
;==============================================================================
$ECCGTKTS = GUICreate("gtkThemeSelect", 345, 199, -1, -1, BitOR($WS_SYSMENU,$WS_CAPTION,$WS_POPUP,$WS_POPUPWINDOW,$WS_BORDER,$WS_CLIPSIBLINGS))
GUISetBkColor(0xFFFFFF)
$Group1 = GUICtrlCreateGroup(" Select a GTK theme ", 8, 0, 329, 137)
GUICtrlSetFont(-1, 8, 400, 2, "Verdana")
$ThemeList = GUICtrlCreateList("", 16, 16, 153, 110)
GUICtrlSetBkColor(-1, 0xA6CAF0)
$Label1 = GUICtrlCreateLabel("preview image:", 208, 8, 93, 16)
GUICtrlSetFont(-1, 8, 400, 0, "Verdana")
$ThemeImage = GUICtrlCreatePic("", 176, 24, 156, 68, $WS_GROUP)
$ThemeEngineLabel = GUICtrlCreateLabel("-", 256, 96, 73, 17)
GUICtrlSetFont(-1, 7, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x800000)
$Label6 = GUICtrlCreateLabel("theme load:", 184, 112, 63, 16, $SS_RIGHT)
GUICtrlSetFont(-1, 7, 400, 0, "Verdana")
$Label2 = GUICtrlCreateLabel("theme engine:", 176, 96, 71, 16, $SS_RIGHT)
GUICtrlSetFont(-1, 7, 400, 0, "Verdana")
$ThemeLoadLabel = GUICtrlCreateLabel("-", 256, 112, 73, 17)
GUICtrlSetFont(-1, 7, 800, 0, "Verdana")
GUICtrlSetColor(-1, 0x800000)
GUICtrlCreateGroup("", -99, -99, 1, 1)
$ButtonSelect = GUICtrlCreateButton("Select", 288, 144, 51, 49, $WS_GROUP)
$Group2 = GUICtrlCreateGroup(" Information ", 8, 136, 273, 57)
GUICtrlSetFont(-1, 8, 400, 2, "Verdana")
$Information = GUICtrlCreateGroup(" Information ", 16, 264, 185, 57)
GUICtrlCreateGroup("", -99, -99, 1, 1)
$Label3 = GUICtrlCreateLabel("gtk 2.0 theme:", 40, 152, 74, 16, $SS_RIGHT)
GUICtrlSetFont(-1, 7, 400, 0, "Verdana")
$Label4 = GUICtrlCreateLabel("theme file present:", 16, 168, 98, 16, $SS_RIGHT)
GUICtrlSetFont(-1, 7, 400, 0, "Verdana")
$Label5 = GUICtrlCreateLabel("ecc verified:", 176, 152, 66, 16, $SS_RIGHT)
GUICtrlSetFont(-1, 7, 400, 0, "Verdana")
$Label7 = GUICtrlCreateLabel("engine available:", 152, 168, 90, 16, $SS_RIGHT)
GUICtrlSetFont(-1, 7, 400, 0, "Verdana")
$SkinLabel = GUICtrlCreateLabel("-", 120, 152, 25, 17)
GUICtrlSetFont(-1, 7, 800, 0, "Verdana")
$ThemeFileLabel = GUICtrlCreateLabel("-", 120, 168, 25, 17)
GUICtrlSetFont(-1, 7, 800, 0, "Verdana")
$eccLabel = GUICtrlCreateLabel("-", 248, 152, 25, 17)
GUICtrlSetFont(-1, 7, 800, 0, "Verdana")
$EngineLabel = GUICtrlCreateLabel("-", 248, 168, 25, 17)
GUICtrlSetFont(-1, 7, 800, 0, "Verdana")
GUICtrlCreateGroup("", -99, -99, 1, 1)
GUISetState(@SW_SHOW)
;==============================================================================
;END *** GUI
;==============================================================================
GUISetIcon (@ScriptDir & "\gtkThemeSelect.ico", "", $ECCGTKTS) ;Set proper icon for the window.

$eccThemeFolders = FileFindFirstFile($eccThemeFolder & "\*.*")

If $eccThemeFolders = -1 Then ; Check if the search was successful.
    MsgBox(0, "ECC gtkThemeSelect", "An error has occured!, aborting...")
    Exit
EndIf

While 1 ;Fill the listbox with available themes.
    $eccThemeFolderName = FileFindNextFile($eccThemeFolders)
    If @error Then ExitLoop
    GUICtrlSetData($ThemeList, $eccThemeFolderName)
WEnd

; Get active theme from 'ecc-core\php-gtk2\etc\gtk-2.0\theme'
$oGtkThemeFile = FileOpen($GtkThemeFile, 0)
If $oGtkThemeFile = -1 Then ; Check if file opened for reading OK.
    MsgBox(0, "ECC gtkThemeSelect", "An error has occured!, aborting...")
    Exit
EndIf
$CurrentGtkTheme = FileReadLine($oGtkThemeFile)
$CurrentGtkTheme = StringMid($CurrentGtkTheme, 19, Stringlen($CurrentGtkTheme)-19)
FileClose($oGtkThemeFile)

; Set selection in the listbox to the current theme.
If $CurrentGtkTheme = "" Then ;Highly unlikely, but ok, we have to catch it anyway....
	_GUICtrlListBox_SetCurSel($ThemeList, 0) ;Select first value in the listbox
Else
	_GUICtrlListBox_SelectString($ThemeList, $CurrentGtkTheme) ;Highlight the current theme in the listbox.
EndIf
UpdateThemeData()

While 1
	$nMsg = GUIGetMsg()
	Switch $nMsg
		Case $GUI_EVENT_CLOSE ;When the cross in the right-top corner is pressed ;-)
			Exit
		Case $ThemeList ;Something in the themelist is clicked.
			UpdateThemeData()
		Case $ButtonSelect ;Button 'select' is pressed
			$oGtkThemeFile = FileOpen($GtkThemeFile, 2)
			If $oGtkThemeFile = -1 Then ; Check if file opened for reading OK.
				MsgBox(0, "ECC gtkThemeSelect", "An error has occured!, aborting...")
				Exit
			Else
				FileWriteLine($oGtkThemeFile, "gtk-theme-name = " & Chr(34) & $SelectedTheme & Chr(34))
				FileClose($oGtkThemeFile)
				MsgBox(0, "ECC gtkThemeSelect", "You have selected '" & $SelectedTheme & "' as your current theme!" & @CRLF & "To enable the theme you need to restart ECC!")
				Exit
			EndIf
	EndSwitch
WEnd

;==============================================================================
;END *** GUI
;==============================================================================

Func UpdateThemeData()
Global $SelectedTheme = GUICtrlRead($ThemeList)
Global $ThemeLoad = IniRead($eccThemeFolder & $SelectedTheme & "\ecc_theme_info.ini", "THEME", "themeload", "unknown")
Global $ThemeEccVerified = IniRead($eccThemeFolder & $SelectedTheme & "\ecc_theme_info.ini", "THEME", "eccverified", "unknown")
Global $ThemeEngine = IniRead($eccThemeFolder & $SelectedTheme & "\ecc_theme_info.ini", "THEME", "themeengine", "unknown")
GUICtrlSetData($ThemeEngineLabel, $ThemeEngine)

; Set 'Themeload' to textform
If $ThemeLoad = "1" Then $ThemeLoad = "light"
If $ThemeLoad = "2" Then $ThemeLoad = "normal"
If $ThemeLoad = "3" Then $ThemeLoad = "heavy"
If $ThemeLoad = "4" Then $ThemeLoad = "very heavy"
GUICtrlSetData($ThemeLoadLabel, $ThemeLoad)

; Determine proper DLL file (engine) for the theme
Global $ThemeEngineFile = "unknown"
If $ThemeEngine = "clearlooks" Then $ThemeEngineFile = "libclearlooks.dll"
If $ThemeEngine = "wimp" Then $ThemeEngineFile = "libwimp.dll"
If $ThemeEngine = "pixmap" Then $ThemeEngineFile = "libpixmap.dll"
If $ThemeEngine = "smooth" Then $ThemeEngineFile = "libsmooth.dll"

; Check if the DLL file for the theme exist.
If FileExists($GtkEngineFolder & $ThemeEngineFile) Then
	GUICtrlSetData($EngineLabel, "OK")
	GUICtrlSetColor($EngineLabel, $ColorGreen)
Else
	GUICtrlSetData($EngineLabel, "NO")
	GUICtrlSetColor($EngineLabel, $ColorRed)
EndIf

; Load in the preview image if exist.
If FileExists($eccThemeFolder & $SelectedTheme & "\ecc_theme_preview.jpg") Then
	GUICtrlSetImage($ThemeImage, $eccThemeFolder & $SelectedTheme & "\ecc_theme_preview.jpg")
Else
	GUICtrlSetImage($ThemeImage, $NoPreviewImage)
EndIf

; Check if the 'gtk-2.0' folder exist.
If FileExists($eccThemeFolder & $SelectedTheme & "\gtk-2.0") Then
	GUICtrlSetData($SkinLabel, "OK")
	GUICtrlSetColor($SkinLabel, $ColorGreen)
Else
	GUICtrlSetData($SkinLabel, "NO")
	GUICtrlSetColor($SkinLabel, $ColorRed)
EndIf

; Check if the 'gtkrc' file exist.
If FileExists($eccThemeFolder & $SelectedTheme & "\gtk-2.0\gtkrc") Then
	GUICtrlSetData($ThemeFileLabel, "OK")
	GUICtrlSetColor($ThemeFileLabel, $ColorGreen)
Else
	GUICtrlSetData($ThemeFileLabel, "NO")
	GUICtrlSetColor($ThemeFileLabel, $ColorRed)
EndIf

; Check if the theme is verified (tested by ecc)
If $ThemeEccVerified = 1 Then
	GUICtrlSetData($eccLabel, "OK")
	GUICtrlSetColor($eccLabel, $ColorGreen)
Else
	GUICtrlSetData($eccLabel, "NO")
	GUICtrlSetColor($eccLabel, $ColorRed)
EndIf

EndFunc