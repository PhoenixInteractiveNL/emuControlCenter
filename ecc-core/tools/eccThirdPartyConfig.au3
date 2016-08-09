; ------------------------------------------------------------------------------
; Script for             : ECC Thirdparty config
; Script version         : v1.0.0.1
; Last changed           : 2012-07-04
;
; Author: Sebastiaan Ebeltjes (AKA Phoenix)
;
; NOTES: Script to configuring all tools that ECC uses in your chosen ECC language automaticly.
;
; ------------------------------------------------------------------------------
FileChangeDir(@ScriptDir)
#include "..\..\ecc-core\thirdparty\autoit\include\XMLDomWrapper.au3"

Global $ECCInstallPath = StringReplace(@ScriptDir, "\ecc-core\tools", "")
Global $ThirdPartyConfigIni = $ECCInstallPath & "\ecc-core\tools\eccThirdPartyConfig.ini"
Global $eccConfigFileGeneralUser = $ECCInstallPath & "\ecc-user-configs\config\ecc_general.ini"
Global $eccLanguageCurrent = IniRead($eccConfigFileGeneralUser, "USER_DATA", "language", "")
Global $eccLanguageSaved = IniRead($ThirdPartyConfigIni, "ECC", "language", "")

If $eccLanguageCurrent <> $eccLanguageSaved Then ; Language has been changed (compared ecc <> languagelist INI)

	; Configure proper language for NOTEPAD++
	; Copy the language to the root folder of Notepad++ as 'nativeLang.xml'
	; XML Files located in the 'ecc-core\thirdparty\notepad++\localization\' folder.
	If FileExists($ECCInstallPath & "\ecc-core\thirdparty\notepad++\notepad++.exe") = 1 Then
		$NotepadLanguageData = IniRead($ThirdPartyConfigIni, "NOTEPAD++", $eccLanguageCurrent, "")
		If $NotepadLanguageData <> "" Then ; a language setting has been found
			$NotepadFolder = $ECCInstallPath & "\ecc-core\thirdparty\notepad++"
			FileDelete($NotepadFolder & "\nativeLang.xml")
			FileCopy($NotepadFolder & "\localization\" & $NotepadLanguageData, $NotepadFolder & "\nativeLang.xml") ; Copy the language to 'NativeLanguage.xml' file.
		EndIf
	EndIf

	; Configure proper language for XPADDER
	; Copy the language to the root folder of Xpadder.
	; XML Files located in the 'ecc-core\thirdparty\xpadder\languages\' folder.
	If FileExists($ECCInstallPath & "\ecc-core\thirdparty\xpadder\xpadder.exe") = 1 Then
		$XpadderLanguageData = IniRead($ThirdPartyConfigIni, "XPADDER", $eccLanguageCurrent, "")
		If $XpadderLanguageData <> "" Then ; a language setting has been found
			$XpadderFolder = $ECCInstallPath & "\ecc-core\thirdparty\xpadder"
			FileDelete($XpadderFolder & "\*.xpadderlanguage")
			FileCopy($XpadderFolder & "\languages\" & $XpadderLanguageData, $XpadderFolder)
		EndIf
	EndIf

	; Configure proper language for KODA
	; Change the XML language file in the XML config file (fd.xml -> path=configuration/global/language)
	If FileExists($ECCInstallPath & "\ecc-core\thirdparty\koda\fd.exe") = 1 Then
		$KodaLanguageData = IniRead($ThirdPartyConfigIni, "KODA", $eccLanguageCurrent, "")
		If $KodaLanguageData <> "" Then ; a language setting has been found
			$KodaConfigFile = $ECCInstallPath & "\ecc-core\thirdparty\koda\fd.xml"
			_XMLFileOpen($KodaConfigFile)
			_XMLDeleteNode("configuration/global/language")
			_XMLCreateChildNode("configuration/global", "language", $KodaLanguageData)
		EndIf
	EndIf

	IniWrite($ThirdPartyConfigIni, "ECC", "language", $eccLanguageCurrent) ; Save selected language to INI
EndIf

Exit