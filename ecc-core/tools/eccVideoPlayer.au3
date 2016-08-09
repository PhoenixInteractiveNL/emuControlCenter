; ------------------------------------------------------------------------------
; emuControlCenter Video Player
;
; Script version         : v2.0.0.1
; Last changed           : 2014.05.10
;
; Author: Sebastiaan Ebeltjes (aka Phoenix)
;
; NOTES:
;
; The ECC Video Player uses mplayer:
; http://mplayerwin.sourceforge.net/downloads.html
;
; ------------------------------------------------------------------------------
FileChangeDir(@ScriptDir)
#include "eccToolVariables.au3"

HotKeySet("{ESC}", "Terminate")
Opt("WinTitleMatchMode", 2)

;Get ECC Video Player settings
Global $ECCVideoPlayerEnableFlag = Iniread($eccConfigFileGeneralUser, "VIDEOPLAYER", "eccVideoPlayer_enable", "1")
Global $ECCVideoPlayerSoundFlag = Iniread($eccConfigFileGeneralUser, "VIDEOPLAYER", "eccVideoPlayer_sound", "1")
Global $ECCVideoPlayerSoundVolume = Iniread($eccConfigFileGeneralUser, "VIDEOPLAYER", "eccVideoPlayer_soundvolume", "70")
Global $ECCVideoPlayerLoopFlag = Iniread($eccConfigFileGeneralUser, "VIDEOPLAYER", "eccVideoPlayer_loop", "1")
Global $ECCVideoPlayerResX = Iniread($eccConfigFileGeneralUser, "VIDEOPLAYER", "eccVideoPlayer_resx", "300")
Global $ECCVideoPlayerResY = Iniread($eccConfigFileGeneralUser, "VIDEOPLAYER", "eccVideoPlayer_resy", "300")
Global $ECCVideoPlayerPadX = Iniread($eccConfigFileGeneralUser, "VIDEOPLAYER", "eccVideoPlayer_padx", "30")
Global $ECCVideoPlayerPadY = Iniread($eccConfigFileGeneralUser, "VIDEOPLAYER", "eccVideoPlayer_pady", "20")

Global $RomVideoFolder = $eccUserPath & $RomEccId & "\videos\" & StringLeft($RomCrc32, 2) & "\" & $RomCrc32
Global $RomVideoFile = $RomVideoFolder & "\ecc_" & $RomEccId & "_" & $RomCrc32
Global $RomVideoFileExt
If FileExists($RomVideoFile & ".flv") Then $RomVideoFileExt = $RomVideoFile & ".flv" ;Secondary
If FileExists($RomVideoFile & ".mp4") Then $RomVideoFileExt = $RomVideoFile & ".mp4" ;Primary

If $CmdLine[0] > 0 Then
	Select
	Case $CmdLine[1] = "emulatorrun"
		If WinExists($VideoWindowTitle) Then WinKill($VideoWindowTitle) ;Stop Video

	Case $CmdLine[1] = "romselected"
		If WinExists($VideoWindowTitle) Then WinKill($VideoWindowTitle) ;Stop Video

		If FileExists($RomVideoFileExt) Then

			;Failsave if ECC window is not found!
			If WinExists($eccGeneratedTitle) = False Then
 				ToolTip("The ECC window cannot be properly detected!", @DesktopWidth/2, @DesktopHeight/2, "ECC VIDEO ERROR", 1, 6)
				Sleep(1500)
 				ToolTip("")
				Exit
			EndIf

			$eccMainWindowSize = WinGetPos($eccGeneratedTitle)

			;$eccMainWindowSize[0] = X position
			;$eccMainWindowSize[1] = Y position
			;$eccMainWindowSize[2] = Width
			;$eccMainWindowSize[3] = Height

			$ECCVideoPlayerCoordX = ($eccMainWindowSize[0] + $eccMainWindowSize[2]) - ($ECCVideoPlayerResX + $ECCVideoPlayerPadX)
			$ECCVideoPlayerCoordY = ($eccMainWindowSize[1] + $eccMainWindowSize[3]) - ($ECCVideoPlayerResY + $ECCVideoPlayerPadY)

			;option: -slave -quiet > runs mplayer as slave mode.
			;option: -nofontconfig > disable font cache build in \Users\[USER]\AppData\Local\fontconfig\cache\[filename].cache-4
			;option: -vo direct3d >needed to prevent windo flashing in Windows (default is directx, the video then switches to direct3d, causing a screen blink
			;option: -nokeepaspect > disables aspect ratio
			;option: -noborder > removes border around the videowindow
			;option: -nosound > do not play/encode any video sound
			;option: -volume 0 > set volume for playback
			;option: -geometry [width]x[height]+[offsetX]+[offsetY]

			;Settings trough commandline
			;Play sound?
			$VideoSound = ""
			If $ECCVideoPlayerSoundFlag <> "1" Then
				$VideoSound = "-nosound "
			Else
				$VideoSound = " -softvol -volume " & $ECCVideoPlayerSoundVolume & " "
			EndIf

			$VideoPlayerCommandline = Chr(34) & $VideoPlayerExe & Chr(34) & _
			" -slave -quiet " & _
			"-nofontconfig " & _
			"-vo direct3d " & _
			"-nokeepaspect " & _
			"-noborder " & _
			$VideoSound & _
			"-geometry " & $ECCVideoPlayerResX & "x" & $ECCVideoPlayerResY & "+" & $ECCVideoPlayerCoordX & "+" & $ECCVideoPlayerCoordY & " " & _
			Chr(34) & $RomVideoFileExt & Chr(34)

			Tooltip("Starting video, please wait...", $ECCVideoPlayerCoordX + $ECCVideoPlayerResX / 2, $ECCVideoPlayerCoordY + $ECCVideoPlayerResY / 2, "ECC Video Player", 1, 6)
			$VideoPlayerPID = Run($VideoPlayerCommandline, "", @SW_HIDE, $STDERR_CHILD + $STDOUT_CHILD + $STDIN_CHILD)
			WinWait($VideoWindowTitle, "", 3) ;first wait when the videoplayer has started!
			Tooltip("")

			;Set video on top at first start, this reduce flickering while switching back to ECC
			WinSetOnTop($VideoWindowTitle, "", 1)

			;Give focus back to ECC window otherwise the shortcut commands won't work anymore
			WinActivate($eccGeneratedTitle)

			;Settings through slave mode
			;Better set loop parameter in slave mode, this won't close the mplayer and script when looping
			If $ECCVideoPlayerLoopFlag = "1" Then StdinWrite($VideoPlayerPID, "loop 1" & @LF) ; Loop video? 1 = infinite

			While WinExists($eccGeneratedTitle) = True
				If WinExists($VideoWindowTitle) = False Then Exit ;Exit the script if the videoplayer has been closed

				$eccMainWindowSize = WinGetPos($eccGeneratedTitle)
				$ECCVideoPlayerCoordX = ($eccMainWindowSize[0] + $eccMainWindowSize[2]) - ($ECCVideoPlayerResX + $ECCVideoPlayerPadX)
				$ECCVideoPlayerCoordY = ($eccMainWindowSize[1] + $eccMainWindowSize[3]) - ($ECCVideoPlayerResY + $ECCVideoPlayerPadY)
				WinMove($VideoWindowTitle, "", $ECCVideoPlayerCoordX, $ECCVideoPlayerCoordY)

				If WinGetState($eccGeneratedTitle) = 15 Or WinGetState($eccGeneratedTitle) = 47 Then
					WinSetOnTop($VideoWindowTitle, "", 1)
				Else ;Do not set video GUI on TOP if focus to ECC window is lost!
					WinSetOnTop($VideoWindowTitle, "", 0)
				EndIf

				Sleep(10)
			WEnd
			If WinExists($VideoWindowTitle) Then WinKill($VideoWindowTitle) ;Stop Video
		EndIf

	Case $CmdLine[1] = "romdelete"
		If WinExists($VideoWindowTitle) Then WinKill($VideoWindowTitle) ;Stop Video

	Case $CmdLine[1] = "videoadd"
		If FileExists($RomVideoFileExt) Then
			$FileAction = MsgBox(68, "ECC VIDEO", "A video file already exists...overwrite?")
			If $FileAction = 7 Then Exit ;NO button pressed
		EndIf

		If WinExists($VideoWindowTitle) Then WinKill($VideoWindowTitle) ;Stop Video
		Global $SelectedVideoFile = FileOpenDialog("ECC VIDEO - Select a video file:", "", "ECC Video File (*.mp4;*.flv)", 3)
		Global $SelectedVideoFileExtension = StringRight($SelectedVideoFile, 4)
		ToolTip("Copying video file, please wait...", @DesktopWidth/2, @DesktopHeight/2, "ECC VIDEO", 1, 6)
		FileCopy($SelectedVideoFile, $RomVideoFile & $SelectedVideoFileExtension, 9)
		ToolTip("")
		If $ECCVideoPlayerEnableFlag = "1" Then Run($Autoit3Exe & " " & Chr(34) & @ScriptDir & "\eccVideoPlayer.au3" & Chr(34) & " romselected")

	Case $CmdLine[1] = "videodelete"
		If FileExists($RomVideoFileExt) Then
			$FileAction = MsgBox(68, "ECC VIDEO", "Delete all videos for this rom? (flv/mp4)?")
			If $FileAction = 7 Then Exit ;NO button pressed

			If WinExists($VideoWindowTitle) Then WinKill($VideoWindowTitle) ;Stop Video
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