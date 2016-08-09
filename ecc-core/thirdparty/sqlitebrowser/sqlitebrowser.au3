; ------------------------------------------------------------------------------
; emuControlCenter Start SQL Browser with ECC database
;
; Script version         : v1.0.0.2
; Last changed           : 2013.11.03
;
;
; Author: Sebastiaan Ebeltjes (aka Phoenix)
; Code contributions:
;
; NOTES: Nothing yet ;-)
;
; ------------------------------------------------------------------------------
FileChangeDir(@ScriptDir)
Run(@Scriptdir & "\sqlitebrowser.exe " & "..\..\..\ecc-system\database\eccdb")
