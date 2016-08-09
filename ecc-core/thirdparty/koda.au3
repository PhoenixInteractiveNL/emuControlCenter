; ------------------------------------------------------------------------------
; emuControlCenter Start KODA (Autoit3 GUI editor)
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
Global $KodaExe = $eccPath & "\ecc-core\thirdparty\koda\FD.exe"

If FileExists($eccPath & "\ecc.exe") <> 1 or FileExists($eccPath & "\ecc-system\ecc.php") <> 1 Then
	MsgBox(64,"ECC", "No ECC software found!, aborting...")
	Exit
EndIf

If FileExists($KodaExe) <> 1 Then
	MsgBox(64,"ECC", "KODA (Autoit3 GUI editor) has not been installed!")
	Exit
Else
	Run($KodaExe)
EndIf

;==============================================================================
;END *** CHECK & VALIDATE
;==============================================================================