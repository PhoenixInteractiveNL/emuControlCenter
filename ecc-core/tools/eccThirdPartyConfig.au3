; ------------------------------------------------------------------------------
; Script for             : ECC Thirdparty config
; Script version         : v1.0.0.3
; Last changed           : 2016.09.25
;
; Author: Sebastiaan Ebeltjes (AKA Phoenix)
;
; NOTES: Script to configuring all tools that ECC uses in your chosen ECC language automaticly.
;
; ------------------------------------------------------------------------------
FileChangeDir(@ScriptDir)
#include "eccToolVariables.au3"

If $eccLanguageCurrent <> $eccLanguageSaved Then ; Language has been changed (compared ecc <> languagelist INI)

	; Configure proper language for NOTEPAD++
	; Copy the language to the root folder of Notepad++ as 'nativeLang.xml'
	; XML Files located in the 'ecc-core\thirdparty\notepad++\localization\' folder.
	If FileExists($NotepadExe) = 1 Then
		$NotepadLanguageData = IniRead($ThirdPartyConfigIni, "NOTEPAD++", $eccLanguageCurrent, "")
		If $NotepadLanguageData <> "" Then ; a language setting has been found
			$NotepadFolder = $eccInstallPath & "\ecc-core\thirdparty\notepad++"
			FileDelete($NotepadFolder & "\nativeLang.xml")
			FileCopy($NotepadFolder & "\localization\" & $NotepadLanguageData, $NotepadFolder & "\nativeLang.xml") ; Copy the language to 'NativeLanguage.xml' file.
		EndIf
	EndIf

	; Configure proper language for KODA
	; Change the XML language file in the XML config file (fd.xml -> path=configuration/global/language)
	If FileExists($KodaExe) = 1 Then
		$KodaLanguageData = IniRead($ThirdPartyConfigIni, "KODA", $eccLanguageCurrent, "")
		If $KodaLanguageData <> "" Then ; a language setting has been found
			$KodaConfigFile = $eccInstallPath & "\ecc-core\thirdparty\koda\fd.xml"
			_XMLFileOpen($KodaConfigFile)
			_XMLDeleteNode("configuration/global/language")
			_XMLCreateChildNode("configuration/global", "language", $KodaLanguageData)
		EndIf
	EndIf

	IniWrite($ThirdPartyConfigIni, "ECC", "language", $eccLanguageCurrent) ; Save selected language to INI
EndIf

Exit