; ------------------------------------------------------------------------------
; emuControlCenter getCRC32
;
; Script version         : v1.0.0.1
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

If $CmdLine[0] = 0 Then Exit
If FileExists($CmdLine[1]) = False Then Exit

$FileHandle = Fileopen($CRCfile, 10)
FileWriteLine($FileHandle, GetCRC($CmdLine[1]))
FileClose($FileHandle)