; ------------------------------------------------------------------------------
; emuControlCenter Start SQL Browser
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
Global $eccDBFile = Chr(34) & $eccPath & "\ecc-system\database\eccdb" & Chr(34)
Global $SQLBrowserExe = $eccPath & "\ecc-core\thirdparty\sqlitebrowser\sqlitebrowser.exe"

If FileExists($eccPath & "\ecc.exe") <> 1 or FileExists($eccPath & "\ecc-system\ecc.php") <> 1 Then
	MsgBox(64,"ECC", "No ECC software found!, aborting...")
	Exit
EndIf

If FileExists($SQLBrowserExe) <> 1 Then
	MsgBox(64,"ECC", "SQLite Browser has not been installed!")
	Exit
Else
	Run($SQLBrowserExe & " " & $eccDBFile)
EndIf

;==============================================================================
;END *** CHECK & VALIDATE
;==============================================================================