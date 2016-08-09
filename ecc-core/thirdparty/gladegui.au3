; ------------------------------------------------------------------------------
; emuControlCenter Start Glade GUI Editor
;
; Script version         : v1.0.0.0
; Last changed           : 2011.03.09
;
;
; Author: Sebastiaan Ebeltjes (aka Phoenix)
; Code contributions:
;
; NOTES: Nothing yet ;-)
;
; ------------------------------------------------------------------------------

Global $HostInfoIni = "..\..\ecc-system\system\info\ecc_local_host_info.ini"
;==============================================================================
;BEGIN *** CHECK & VALIDATE
;==============================================================================
; First we need to know where ecc is installed, this is stored in 'ecc_local_host_info.ini'
If FileExists($HostInfoIni) <> 1 Then
	MsgBox(64,"ECC", "Please make sure you have run ECC at least once!, aborting...")
	Exit
EndIf

$eccPathTemp = IniRead($HostInfoIni, "ECC_HOST_INFO", "SCRIPT_NAME", "")
Global $eccPath = StringReplace($eccPathTemp, "\ecc-system\ecc.php", "")
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