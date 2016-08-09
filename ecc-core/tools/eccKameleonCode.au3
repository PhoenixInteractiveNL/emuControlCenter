; ------------------------------------------------------------------------------
; Script for             : ECC Kameleon Code
; Script version         : v1.0.0.1
; Last changed           : 2012-09-27
;
; Author: Sebastiaan Ebeltjes (AKA Phoenix)
;
; NOTES: Kameleon Code to make usage of several ECC services on the internet.
;
; ------------------------------------------------------------------------------
;GUI INCLUDES
#include "..\thirdparty\autoit\include\ButtonConstants.au3"
#include "..\thirdparty\autoit\include\EditConstants.au3"
#include "..\thirdparty\autoit\include\GUIConstantsEx.au3"
#include "..\thirdparty\autoit\include\GUIListBox.au3"
#include "..\thirdparty\autoit\include\StaticConstants.au3"
#include "..\thirdparty\autoit\include\WindowsConstants.au3"

;==============================================================================
;BEGIN *** GUI
;==============================================================================
Global $KameleonCodeGui = GUICreate("ECC Kameleon Code", 372, 132, -1, -1)
GUISetBkColor(0xFFFFFF)
Global $Group1 = GUICtrlCreateGroup(" Code Input ", 0, 80, 369, 49)
GUICtrlSetFont(-1, 8, 800, 2, "Verdana")
Global $CodeInputBox = GUICtrlCreateInput("", 8, 96, 193, 21)
GUICtrlSetBkColor(-1, 0xA6CAF0)
Global $KnopOK = GUICtrlCreateButton("OK", 208, 96, 75, 25)
GUICtrlSetFont(-1, 10, 800, 2, "Verdana")
Global $KnopCancel = GUICtrlCreateButton("CANCEL", 288, 96, 75, 25)
GUICtrlSetFont(-1, 10, 800, 2, "Verdana")
GUICtrlCreateGroup("", -99, -99, 1, 1)
Global $Edit1 = GUICtrlCreateEdit("", 0, 0, 369, 73, BitOR($ES_READONLY,$ES_WANTRETURN))
GUICtrlSetData(-1, StringFormat("To make usage of serveral ECC services you need a code. You can find this \r\ncode on our forum (for link see top menu), and is only available for registered \r\nusers. People can register on our forum for free.\r\nThis code refreshes every hour, when a ECC service encounters a wrong \r\ncode, this pop-up box wil be shown to enter the code."))
;==============================================================================
;END *** GUI
;==============================================================================
GUISetState(@SW_SHOW, $KameleonCodeGui)
GUISetIcon(@ScriptDir & "\eccKameleonCode.ico", "", $KameleonCodeGui) ;Set proper icon for the window.

While 1
	$nMsg = GUIGetMsg($KameleonCodeGui)
	Switch $nMsg
		Case $GUI_EVENT_CLOSE
			If IniRead(@ScriptDir & "\eccKameleonCode.code", "kameleon", "code", "") = "" Then
				IniWrite(@ScriptDir & "\eccKameleonCode.code", "kameleon", "code", "x")
			EndIf
			Exit
		Case $KnopCancel
			If IniRead(@ScriptDir & "\eccKameleonCode.code", "kameleon", "code", "") = "" Then
				IniWrite(@ScriptDir & "\eccKameleonCode.code", "kameleon", "code", "x")
			EndIf
			Exit
		Case $KnopOK
			IniWrite(@ScriptDir & "\eccKameleonCode.code", "kameleon", "code", GUICtrlRead($CodeInputBox))
			Exit
	EndSwitch

Sleep(10)
WEnd

Exit