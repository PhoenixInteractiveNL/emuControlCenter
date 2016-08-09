; ------------------------------------------------------------------------------
; emuControlCenter Video Player
;
; Script version         : v1.1.0.4
; Last changed           : 2014.03.28
;
; Author: Sebastiaan Ebeltjes (aka Phoenix)
;
; NOTES:
;
; The ECC Video Player needs VLC VideoLAN player to work, wich you can download here:
; Download VLC Player: http://www.videolan.org/vlc/
;
; Pressing ESCAPE in ECC will close the Video Player
;
; Other things:
; - The ECC titlebar may blink a bit while loading the video.
; - When clicking to fast on ROMS the Video Player may start on center screen for a moment.
; - You cannot use the ROM context menu directly after clicking a ROM, please wait until the video has started!
;
; Ps. The ECC video player is still experimental, so please post bugs on the forum!
;
; ------------------------------------------------------------------------------
FileChangeDir(@ScriptDir)
#include "eccToolVariables.au3"

_VLCErrorHandlerRegister()
HotKeySet("{ESC}", "Terminate")

;Get ECC Video Player settings
Global $VideoPlayerEnableFlag = Iniread($eccConfigFileGeneralUser, "VIDEOPLAYER", "eccVideoPlayer_enable", "1")
Global $VideoPlayerSoundFlag = Iniread($eccConfigFileGeneralUser, "VIDEOPLAYER", "eccVideoPlayer_sound", "1")
Global $VideoPlayerSoundVolume = Iniread($eccConfigFileGeneralUser, "VIDEOPLAYER", "eccVideoPlayer_soundvolume", "70")
Global $VideoPlayerLoopFlag = Iniread($eccConfigFileGeneralUser, "VIDEOPLAYER", "eccVideoPlayer_loop", "1")
Global $VideoPlayerResX = Iniread($eccConfigFileGeneralUser, "VIDEOPLAYER", "eccVideoPlayer_resx", "300")
Global $VideoPlayerResY = Iniread($eccConfigFileGeneralUser, "VIDEOPLAYER", "eccVideoPlayer_resy", "300")
Global $VideoPlayerPadX = Iniread($eccConfigFileGeneralUser, "VIDEOPLAYER", "eccVideoPlayer_padx", "30")
Global $VideoPlayerPadY = Iniread($eccConfigFileGeneralUser, "VIDEOPLAYER", "eccVideoPlayer_pady", "20")

Global $RomVideoFolder = $eccUserPath & $RomEccId & "\videos\" & StringLeft($RomCrc32, 2) & "\" & $RomCrc32
Global $RomVideoFile = $RomVideoFolder & "\ecc_" & $RomEccId & "_" & $RomCrc32
Global $RomVideoFileExt
If FileExists($RomVideoFile & ".flv") Then $RomVideoFileExt = $RomVideoFile & ".flv" ;Secondary
If FileExists($RomVideoFile & ".mp4") Then $RomVideoFileExt = $RomVideoFile & ".mp4" ;Primary

If $CmdLine[0] > 0 Then
	Select

	Case $CmdLine[1] = "emulatorrun"
		If WinExists("ECC VIDEO GUI") Then WinKill("ECC VIDEO GUI") ;Stop Video/GUI

	Case $CmdLine[1] = "romselected"
		If FileExists($RomVideoFileExt) Then
			If WinExists("ECC VIDEO GUI") Then WinKill("ECC VIDEO GUI") ;Stop Video/GUI

			;Setup Main Video GUI
			Global $eccVideoGUI = GUICreate("ECC VIDEO GUI", $VideoPlayerResX, $VideoPlayerResY, -1, -1, $WS_POPUP)
			GUISetIcon(@ScriptDir & "\eccVideoPlayer.ico", -1) ;Set proper icon for the window.
			$VLCPlayer = _GUICtrlVLC_Create(0, 0, $VideoPlayerResX, $VideoPlayerResY)

			If $VLCPlayer = False Then ;Check if VLC player is installed
				MsgBox(64, "ECC VIDEO",	"VLC intialization failed!" & @CRLF & _
										"The most likely cause is that you don't have VLC installed." & @CRLF & _
										"Make sure VLC, and the ActiveX component is installed!" & @CRLF & _
										"The Video Player has been disabled!" & @CRLF & _
										"You can enable it again in the ECC config!")
				IniWrite($eccConfigFileGeneralUser, "VIDEOPLAYER", "eccVideoPlayer_enable", "0")
				Exit
			EndIf

			Global $eccMainVersion =	IniRead($eccVersionInfoIni, "GENERAL", "current_version", "x.x")
			Global $eccMainBuild =		IniRead($eccVersionInfoIni, "GENERAL", "current_build", "xxx")
			Global $eccMainBuildDate =	IniRead($eccVersionInfoIni, "GENERAL", "date_build", "xxxx.xx.xx")
			Global $eccLastUpdate =		IniRead($eccLocalUpdateIni, "UPDATE", "last_update", "xxxxx")
			Global $eccGeneratedTitle =	"emuControlCenter" & " v" & $eccMainVersion & " build:" & $eccMainBuild & " (" & $eccMainBuildDate & ")" & " upd:" & $eccLastUpdate

			;Failsave if ECC window is not found!
			If WinExists($eccGeneratedTitle) = False Then
 				ToolTip("The ECC window cannot be properly detected!", @DesktopWidth/2, @DesktopHeight/2, "ECC VIDEO ERROR", 1, 6)
 				Sleep(1500)
 				ToolTip("")
				Exit
			EndIf

			$eccMainWindowSize = WinGetPos($eccGeneratedTitle)
			WinMove("ECC VIDEO GUI", "", ($eccMainWindowSize[0] + $eccMainWindowSize[2]) - ($VideoPlayerResX + $VideoPlayerPadX), ($eccMainWindowSize[1] + $eccMainWindowSize[3]) - ($VideoPlayerResY + $VideoPlayerPadY))
			GUISetState(@SW_SHOW, $eccVideoGUI)
			WinSetOnTop("ECC VIDEO GUI", "", 1)

			WinActivate($eccGeneratedTitle) ;Give focus back to ECC window otherwise the shortcut commands won't work anymore!

			_GUICtrlVLC_Clear($VLCPlayer)
			_GUICtrlVLC_SetVolume($VLCPlayer, $VideoPlayerSoundVolume)
			If $VideoPlayerSoundFlag <> "1" Then _GUICtrlVLC_SetMute($VLCPlayer,True) ;Disable video sound
			_GUICtrlVLC_Add($VLCPlayer, $RomVideoFileExt)
			_GUICtrlVLC_Play($VLCPlayer, 0)


			While 1
				$msg = GUIGetMsg($eccVideoGUI)

				$VideoState = _GUICtrlVLC_GetState($VLCPlayer) ;Get the video state
				If $VideoState = 6 Then
					If $VideoPlayerLoopFlag = "1" Then
						_GUICtrlVLC_Play($VLCPlayer, _GUICtrlVLC_Add($VLCPlayer, $RomVideoFileExt)) ;Loop a finished video
					Else
						Exit ;Close video after playing
					EndIf
				EndIf

				If WinExists($eccGeneratedTitle) = True Then
					$eccMainWindowSize = WinGetPos($eccGeneratedTitle)
					WinMove("ECC VIDEO GUI", "", ($eccMainWindowSize[0] + $eccMainWindowSize[2]) - ($VideoPlayerResX + $VideoPlayerPadX), ($eccMainWindowSize[1] + $eccMainWindowSize[3]) - ($VideoPlayerResY + $VideoPlayerPadY))
				Else
					Exit
				EndIf

				If WinGetState($eccGeneratedTitle) = 15 Or WinGetState($eccGeneratedTitle) = 47 Then
					WinSetOnTop("ECC VIDEO GUI", "", 1)
				Else ;Do not set video GUI on TOP if focus to ECC window is lost!
					WinSetOnTop("ECC VIDEO GUI", "", 0)
				EndIf

				If $msg = $GUI_EVENT_CLOSE Then
					Exit
				EndIf
				Sleep(10)
			WEnd
		Else
			If WinExists("ECC VIDEO GUI") Then WinKill("ECC VIDEO GUI") ;Stop Video/GUI
		EndIf

	Case $CmdLine[1] = "romdelete"
		If WinExists("ECC VIDEO GUI") Then WinKill("ECC VIDEO GUI") ;Stop Video/GUI

	Case $CmdLine[1] = "videoadd"
		If FileExists($RomVideoFileExt) Then
			$FileAction = MsgBox(68, "ECC VIDEO", "A video file already exists...overwrite?")
			If $FileAction = 7 Then Exit ;NO button pressed
		EndIf

		If WinExists("ECC VIDEO GUI") Then WinKill("ECC VIDEO GUI") ;Stop Video/GUI
		Global $SelectedVideoFile = FileOpenDialog("ECC VIDEO - Select a video file:", "", "ECC Video File (*.mp4;*.flv)", 3)
		Global $SelectedVideoFileExtension = StringRight($SelectedVideoFile, 4)
		ToolTip("Copying video file, please wait...", @DesktopWidth/2, @DesktopHeight/2, "ECC VIDEO", 1, 6)
		FileCopy($SelectedVideoFile, $RomVideoFile & $SelectedVideoFileExtension, 9)
		ToolTip("")
		If $VideoPlayerEnableFlag = "1" Then Run($Autoit3Exe & " " & Chr(34) & @ScriptDir & "\eccVideoPlayer.au3" & Chr(34) & " romselected")

	Case $CmdLine[1] = "videodelete"
		If FileExists($RomVideoFileExt) Then
			$FileAction = MsgBox(68, "ECC VIDEO", "Delete all videos for this rom? (flv/mp4)?")
			If $FileAction = 7 Then Exit ;NO button pressed

			If WinExists("ECC VIDEO GUI") Then WinKill("ECC VIDEO GUI") ;Stop Video/GUI
			Sleep(500)
			FileDelete($RomVideoFile & ".mp4")
			FileDelete($RomVideoFile & ".flv")
			DirRemove($RomVideoFolder) ;no flag set to only remove the folder if no files exist in the folder
			ToolTip("Videofiles (flv/mp4) for this rom deleted!", @DesktopWidth/2, @DesktopHeight/2, "ECC VIDEO", 1, 6)
			Sleep(1500)
			ToolTip(""
		Else
			ToolTip("No videofiles found to delete!", @DesktopWidth/2, @DesktopHeight/2, "ECC VIDEO", 1, 6)
			Sleep(1500)
			ToolTip("")
		EndIf

	EndSelect
EndIf
Exit

Func Terminate()
	Exit
EndFunc ;Terminate