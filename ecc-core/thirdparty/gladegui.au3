; ------------------------------------------------------------------------------
; emuControlCenter Start Glade GUI Editor
;
; Script version         : v1.0.0.1
; Last changed           : 2012.05.27
;
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
Global $eccPath = StringReplace(@Scriptdir, "\ecc-core\thirdparty", "")
Global $GladeGUIExe = $eccPath & "\ecc-core\php-gtk2\glade-3.exe"

If FileExists($eccPath & "\ecc.exe") <> 1 or FileExists($eccPath & "\ecc-system\ecc.php") <> 1 Then
	MsgBox(64,"ECC", "No ECC software found!, aborting...")
	Exit
EndIf

If FileExists($GladeGUIExe) <> 1 Then
	MsgBox(64,"ECC", "Glade GUI Editor has not been installed!")
	Exit
Else
	Run($GladeGUIExe)
EndIf
;==============================================================================
;END *** CHECK & VALIDATE
;==============================================================================