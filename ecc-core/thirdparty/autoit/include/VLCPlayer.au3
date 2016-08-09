#include-once
#Region Header
#cs
    Title:          VLC UDF Library for AutoIt3
    Filename:       VLC.au3
    Description:    A collection of functions for creating and controlling a VLC control in AutoIT
    Author:         seangriffin
    Version:        V0.3b
    Last Update:    11/11/12
    Requirements:   AutoIt3 3.2 or higher,
                    A VLC version greater than 0.8.5 installed
    Changelog:
			--------11/11/12----------- v0.3b
			PATCHED 2012-11-11 by Phoenix versioninfo() can also result in a "" (zero) string!

                    --------17/05/10----------- v0.3
                    Added the function _GUICtrlVLC_GetVolume.
                    Added the function _GUICtrlVLC_SetVolume.
                    Added the function _GUICtrlVLC_GetMute.
                    Added the function _GUICtrlVLC_SetMute.

                    --------15/05/10----------- v0.2
                    Changed _GUICtrlVLC_GetState to return 0 if no playlist items.
                    Changed _GUICtrlVLC_Pause to return 0 if no playlist items.
                    Changed _GUICtrlVLC_Stop to return 0 if no playlist items.
                    Changed _GUICtrlVLC_SeekRelative to return 0 if no playlist items.
                    Changed _GUICtrlVLC_SeekAbsolute to return 0 if no playlist items.
                    Added error handling to all functions.
                    Added the function _VLCErrorHandlerRegister.
                    Added the function __VLCInternalErrorHandler.

                    ---------08/05/10---------- v0.1
                    Initial release.
                    Note that a clipping problem currently exists in the VLC control.

#ce
#EndRegion Header
#Region Global Variables and Constants
Global $oVLCErrorHandler, $sVLCUserErrorHandler
#EndRegion Global Variables and Constants
#Region Core functions
; #FUNCTION# ;===============================================================================
;
; Name...........:    _GUICtrlVLC_Create()
; Description ...:    Creates a VLC control.
; Syntax.........:    _GUICtrlVLC_Create($left, $top, $width, $height)
; Parameters ....:    $left            - The left side of the control.
;                    $top            - The top of the control.
;                    $width            - The width of the control
;                    $height            - The height of the control
; Return values .:     On Success        - Returns the identifier (controlID) of the new control.
;                     On Failure        - Returns False.
; Author ........:    seangriffin
; Modified.......:
; Remarks .......:    This function must be used before any other function in the UDF is used.
;                    There is currently a clipping problem with the control, where the video
;                    is overdrawn by any other window that overlaps it.  There is no known
;                    solution at this time.
;
; Related .......:
; Link ..........:
; Example .......:    Yes
;
; ;==========================================================================================
Func _GUICtrlVLC_Create($left, $top, $width, $height)

    Local Const $html = _
        "<style type=""text/css"">html, body, vlc {margin: 0px; padding: 0px; overflow: hidden;}</style>" & @CRLF & _
        "<object classid=""clsid:9BE31822-FDAD-461B-AD51-BE1D1C159921""" & @CRLF & _
            "codebase=""http://downloads.videolan.org/pub/videolan/vlc/latest/win32/axvlc.cab""" & @CRLF & _
            "width=""" & $width & """ height=""" & $height & """" & @CRLF & _
            "id=""vlc"" events=""True"">" & @CRLF & _
        "</object>"

    $oIE = _IECreateEmbedded ()
    $oIEActiveX = GUICtrlCreateObj($oIE, $left, $top, $width-4, $height-4)
    _IENavigate($oIE, "about:blank")
    _IEDocWriteHTML($oIE, $html)
    $vlc = _IEGetObjByName($oIE, "vlc")

    ; Clear any current VLC errors
    $oVLCErrorHandler.WinDescription = ""

    ; Check VLC version info. as a means to determine if the Active X control is installed
    $vlc.versionInfo()

    ; If an error (the ActiveX control is not installed), then return False
	; PATCHED 2012-11-11 by Phoenix versioninfo() can also result in a "" (zero) string!
    if StringInStr($oVLCErrorHandler.WinDescription, "Unknown name") > 0 Or $vlc.versionInfo() = "" Then

        GUICtrlDelete($oIEActiveX)
        _IEQuit($oIE)
        Return False
    EndIf

    Return $vlc
EndFunc

; #FUNCTION# ;===============================================================================
;
; Name...........:    _GUICtrlVLC_Add
; Description ...:    Adds a video to the playlist of a VLC control.
; Syntax.........:    _GUICtrlVLC_Add($vlc, $path)
; Parameters ....:    $vlc            - The control identifier (controlID) as returned by _GUICtrlVLC_Create.
;                    $path            - The full path, including filename, of the video to add.
; Return values .:     On Success        - Returns a number as an item identifier in playlist.
;                     On Failure        - Returns False.
; Author ........:    seangriffin
; Modified.......:
; Remarks .......:
; Related .......:
; Link ..........:
; Example .......:    Yes
;
; ;==========================================================================================
Func _GUICtrlVLC_Add($vlc, $path)

    if IsObj($vlc) = False Then Return False

    Return $vlc.playlist.add("file:///" & $path)
EndFunc

; #FUNCTION# ;===============================================================================
;
; Name...........:    _GUICtrlVLC_Play
; Description ...:    Plays a video in the playlist of a VLC control.
; Syntax.........:    _GUICtrlVLC_Play($vlc, $item)
; Parameters ....:    $vlc            - The control identifier (controlID) as returned by _GUICtrlVLC_Create.
;                    $item            - The item in playlist to play, as returned by _GUICtrlVLC_Add.
; Return values .:     On Success        - Returns True.
;                     On Failure        - Returns False.
; Author ........:    seangriffin
; Modified.......:
; Remarks .......:
; Related .......:
; Link ..........:
; Example .......:    Yes
;
; ;==========================================================================================
Func _GUICtrlVLC_Play($vlc, $item)

    if IsObj($vlc) = False Then Return False

    $vlc.playlist.playItem($item)
    Return True
EndFunc

; #FUNCTION# ;===============================================================================
;
; Name...........:    _GUICtrlVLC_Pause
; Description ...:    Pauses and unpauses the playing video.
; Syntax.........:    _GUICtrlVLC_Pause($vlc)
; Parameters ....:    $vlc            - The control identifier (controlID) as returned by _GUICtrlVLC_Create.
; Return values .:     On Success        - Returns True.
;                     On Failure        - Returns False.
; Author ........:    seangriffin
; Modified.......:
; Remarks .......:    Each time this function is called, the paused state of the video is toggled.
; Related .......:
; Link ..........:
; Example .......:    Yes
;
; ;==========================================================================================
Func _GUICtrlVLC_Pause($vlc)

    if IsObj($vlc) = False Then Return False

    if $vlc.playlist.items.count = 0 Then

        Return 0
    Else

        $vlc.playlist.togglePause()
    EndIf
EndFunc

; #FUNCTION# ;===============================================================================
;
; Name...........:    _GUICtrlVLC_Stop
; Description ...:    Stops the playing video.
; Syntax.........:    _GUICtrlVLC_Stop($vlc)
; Parameters ....:    $vlc            - The control identifier (controlID) as returned by _GUICtrlVLC_Create.
; Return values .:     On Success        - Returns True.
;                     On Failure        - Returns False.
; Author ........:    seangriffin
; Modified.......:
; Remarks .......:
; Related .......:
; Link ..........:
; Example .......:    Yes
;
; ;==========================================================================================
Func _GUICtrlVLC_Stop($vlc)

    if IsObj($vlc) = False Then Return False

    if $vlc.playlist.items.count = 0 Then

        Return 0
    Else

        $vlc.playlist.stop()
    EndIf
EndFunc

; #FUNCTION# ;===============================================================================
;
; Name...........:    _GUICtrlVLC_Clear
; Description ...:    Clears the playlist of a VLC control (removes all videos).
; Syntax.........:    _GUICtrlVLC_Clear($vlc)
; Parameters ....:    $vlc            - The control identifier (controlID) as returned by _GUICtrlVLC_Create.
; Return values .:     On Success        - Returns True.
;                     On Failure        - Returns False.
; Author ........:    seangriffin
; Modified.......:
; Remarks .......:
; Related .......:
; Link ..........:
; Example .......:    Yes
;
; ;==========================================================================================
Func _GUICtrlVLC_Clear($vlc)

    if IsObj($vlc) = False Then Return False

    $vlc.playlist.items.clear()

    while $vlc.playlist.items.count > 0
    WEnd

    Sleep(250)
    Return True
EndFunc

; #FUNCTION# ;===============================================================================
;
; Name...........:    _GUICtrlVLC_SeekRelative
; Description ...:    Seek a relative number of milliseconds through the playing video.
; Syntax.........:    _GUICtrlVLC_SeekRelative($vlc, $time)
; Parameters ....:    $vlc            - The control identifier (controlID) as returned by _GUICtrlVLC_Create.
;                    $time            - A number of milliseconds to seek (negative values seek backwards).
; Return values .:     On Success        - Returns True.
;                     On Failure        - Returns False.
; Author ........:    seangriffin
; Modified.......:
; Remarks .......:
; Related .......:
; Link ..........:
; Example .......:    Yes
;
; ;==========================================================================================
Func _GUICtrlVLC_SeekRelative($vlc, $time)

    if IsObj($vlc) = False Then Return False

    if $vlc.playlist.items.count = 0 Then

        Return 0
    Else

        $vlc.input.time = $vlc.input.time + $time
    EndIf
EndFunc

; #FUNCTION# ;===============================================================================
;
; Name...........:    _GUICtrlVLC_SeekAbsolute
; Description ...:    Seek to an absolute location in time in the playing video.
; Syntax.........:    _GUICtrlVLC_SeekAbsolute($vlc, $time)
; Parameters ....:    $vlc            - The control identifier (controlID) as returned by _GUICtrlVLC_Create.
;                    $time            - The number of milliseconds from the start of the video to seek to.
; Return values .:     On Success        - Returns True.
;                     On Failure        - Returns False.
; Author ........:    seangriffin
; Modified.......:
; Remarks .......:
; Related .......:
; Link ..........:
; Example .......:    Yes
;
; ;==========================================================================================
Func _GUICtrlVLC_SeekAbsolute($vlc, $time)

    if IsObj($vlc) = False Then Return False

    if $vlc.playlist.items.count = 0 Then

        Return 0
    Else

        $vlc.input.time = $time
    EndIf
EndFunc

; #FUNCTION# ;===============================================================================
;
; Name...........:    _GUICtrlVLC_GetState
; Description ...:    Get the current state of the playing video.
; Syntax.........:    _GUICtrlVLC_GetState($vlc)
; Parameters ....:    $vlc            - The control identifier (controlID) as returned by _GUICtrlVLC_Create.
; Return values .:     On Success        - Returns a number representing the state of the video:
;                                      (IDLE=0, OPENING=1, BUFFERING=2, PLAYING=3, PAUSED=4, STOPPING=5, ENDED=6, ERROR=7)
;                     On Failure        - Returns 0.
; Author ........:    seangriffin
; Modified.......:
; Remarks .......:
; Related .......:
; Link ..........:
; Example .......:    Yes
;
; ;==========================================================================================
Func _GUICtrlVLC_GetState($vlc)

    if IsObj($vlc) = False Then Return False

    if $vlc.playlist.items.count = 0 Then

        Return 0
    Else

        Return $vlc.input.state
    EndIf
EndFunc

; #FUNCTION# ;===============================================================================
;
; Name...........:    _GUICtrlVLC_GetLength
; Description ...:    Get the length of the playing video.
; Syntax.........:    _GUICtrlVLC_GetLength($vlc)
; Parameters ....:    $vlc            - The control identifier (controlID) as returned by _GUICtrlVLC_Create.
; Return values .:     On Success        - Returns the length of the video in milliseconds.
;                     On Failure        - Returns False.
; Author ........:    seangriffin
; Modified.......:
; Remarks .......:
; Related .......:
; Link ..........:
; Example .......:    Yes
;
; ;==========================================================================================
Func _GUICtrlVLC_GetLength($vlc)

    if IsObj($vlc) = False Then Return False

    Return $vlc.input.length
EndFunc

; #FUNCTION# ;===============================================================================
;
; Name...........:    _GUICtrlVLC_GetMute
; Description ...:    Gets the mute setting of the playing video.
; Syntax.........:    _GUICtrlVLC_GetMute($vlc)
; Parameters ....:    $vlc            - The control identifier (controlID) as returned by _GUICtrlVLC_Create.
; Return values .:     If muted        - Returns True.
;                     If not muted    - Returns False.
; Author ........:    seangriffin
; Modified.......:
; Remarks .......:
; Related .......:
; Link ..........:
; Example .......:    Yes
;
; ;==========================================================================================
Func _GUICtrlVLC_GetMute($vlc)

    if IsObj($vlc) = False Then Return False

    Return $vlc.audio.mute
EndFunc

; #FUNCTION# ;===============================================================================
;
; Name...........:    _GUICtrlVLC_GetTime
; Description ...:    Get the absolute position of the playing video.
; Syntax.........:    _GUICtrlVLC_GetTime($vlc)
; Parameters ....:    $vlc            - The control identifier (controlID) as returned by _GUICtrlVLC_Create.
; Return values .:     On Success        - Returns the position of the playing video in milliseconds.
;                     On Failure        - Returns False.
; Author ........:    seangriffin
; Modified.......:
; Remarks .......:
; Related .......:
; Link ..........:
; Example .......:    Yes
;
; ;==========================================================================================
Func _GUICtrlVLC_GetTime($vlc)

    if IsObj($vlc) = False Then Return False

    Return $vlc.input.time
EndFunc

; #FUNCTION# ;===============================================================================
;
; Name...........:    _GUICtrlVLC_GetVolume
; Description ...:    Gets the volume of the playing video.
; Syntax.........:    _GUICtrlVLC_GetVolume($vlc)
; Parameters ....:    $vlc            - The control identifier (controlID) as returned by _GUICtrlVLC_Create.
; Return values .:     On Success        - Returns the percentage of the volume [0-200].
;                     On Failure        - Returns False.
; Author ........:    seangriffin
; Modified.......:
; Remarks .......:
; Related .......:
; Link ..........:
; Example .......:    Yes
;
; ;==========================================================================================
Func _GUICtrlVLC_GetVolume($vlc)

    if IsObj($vlc) = False Then Return False

    Return $vlc.audio.volume
EndFunc

; #FUNCTION# ;===============================================================================
;
; Name...........:    _GUICtrlVLC_SetMute
; Description ...:    Mutes and unmutes the volume of the playing video.
; Syntax.........:    _GUICtrlVLC_SetMute($vlc, $toggle)
; Parameters ....:    $vlc            - The control identifier (controlID) as returned by _GUICtrlVLC_Create.
;                    $toggle            - boolean value to mute and ummute the audio.
; Return values .:     On Success        - Returns True.
;                     On Failure        - Returns False.
; Author ........:    seangriffin
; Modified.......:
; Remarks .......:
; Related .......:
; Link ..........:
; Example .......:    Yes
;
; ;==========================================================================================
Func _GUICtrlVLC_SetMute($vlc, $toggle)

    if IsObj($vlc) = False Then Return False

    $vlc.audio.mute = $toggle

    Return True
EndFunc

; #FUNCTION# ;===============================================================================
;
; Name...........:    _GUICtrlVLC_SetVolume
; Description ...:    Sets the volume of the playing video.
; Syntax.........:    _GUICtrlVLC_SetVolume($vlc, $level)
; Parameters ....:    $vlc            - The control identifier (controlID) as returned by _GUICtrlVLC_Create.
;                    $level            - a value between [0-200] which indicates a percentage of the volume.
; Return values .:     On Success        - Returns True.
;                     On Failure        - Returns False.
; Author ........:    seangriffin
; Modified.......:
; Remarks .......:
; Related .......:
; Link ..........:
; Example .......:    Yes
;
; ;==========================================================================================
Func _GUICtrlVLC_SetVolume($vlc, $level)

    if IsObj($vlc) = False Then Return False

    $vlc.audio.volume = $level

    Return True
EndFunc

; #FUNCTION# ;===============================================================================
;
; Name...........:    _VLCErrorHandlerRegister()
; Description ...:    Register and enable a VLC ActiveX error handler.
; Syntax.........:    _VLCErrorHandlerRegister($s_functionName = "__VLCInternalErrorHandler")
; Parameters ....:    $s_functionName    - The name of the AutoIT function to run if an error occurs.
; Return values .:    On Success    - Returns 1
;                     On Failure    - Returns 0
; Author ........:    seangriffin
; Modified.......:
; Remarks .......:
; Related .......:
; Link ..........:
; Example .......:    Yes
;
; ;==========================================================================================
Func _VLCErrorHandlerRegister($s_functionName = "__VLCInternalErrorHandler")

    _IEErrorNotify(false)
    $sVLCUserErrorHandler = $s_functionName
    $oVLCErrorHandler = ""
    $oVLCErrorHandler = ObjEvent("AutoIt.Error", $s_functionName)

    If IsObj($oVLCErrorHandler) Then

        SetError(0)
        Return 1
    Else

        SetError(0, 1)
        Return 0
    EndIf
EndFunc

; #FUNCTION# ;===============================================================================
;
; Name...........:    __VLCInternalErrorHandler()
; Description ...:    A VLC ActiveX error handler.
; Syntax.........:    __VLCInternalErrorHandler()
; Parameters ....:
; Return values .:    On Success    - Returns 1
;                     On Failure    - Returns 0
; Author ........:    seangriffin
; Modified.......:
; Remarks .......:  Doesnt do anything really, except catch and set the error values in
;                    $oVLCErrorHandler.  $oVLCErrorHandler is global, and can be utilised
;                    in the calling script instead.
; Related .......:
; Link ..........:
; Example .......:    Yes
;
; ;==========================================================================================
Func __VLCInternalErrorHandler()

;    if StringInStr($oVLCErrorHandler.WinDescription, "Unknown name") > 0 Then

;        ConsoleWrite("--> COM / ActiveX Error Encountered in " & @ScriptName & @CRLF & _
;                     "----> Unknown Active X object.  VLC is most likely not installed)." & @CRLF & _
;                     "      Please reinstall VLC Player and try again" & @CRLF)
;        SetError(1)
;    EndIf

    Return
EndFunc