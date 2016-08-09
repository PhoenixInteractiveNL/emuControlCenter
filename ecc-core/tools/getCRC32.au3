; ------------------------------------------------------------------------------
; emuControlCenter getCRC32
;
; Script version         : v1.0.0.0
; Last changed           : 2012.11.29
;
; Author: Sebastiaan Ebeltjes (aka Phoenix)
; Code contributions:
;
; NOTES: Nothing yet ;-)
;
; ------------------------------------------------------------------------------
FileChangeDir(@ScriptDir)

#include "..\thirdparty\autoit\include\GetCRC32.au3"
Global $EccInstallFolder = StringReplace(@Scriptdir, "\ecc-core\tools", "")
Global $CRCfile = @ScriptDir & "\getCRC32.dat"

If $CmdLine[0] = 0 Then Exit
If FileExists($CmdLine[1]) = False Then Exit

$FileHandle = Fileopen($CRCfile, 10)
FileWriteLine($FileHandle, GetCRC($CmdLine[1]))
FileClose($FileHandle)