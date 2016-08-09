; ------------------------------------------------------------------------------
; Script for             : Create ECC startmenu shortcuts
; Script version         : v1.0.0.2
; Last changed           : 2012-08-11
;
; Author: Sebastiaan Ebeltjes (AKA Phoenix)
;
; NOTES: Script to create ECC startmenu shortcuts!
;
; ------------------------------------------------------------------------------
FileChangeDir(@ScriptDir)

ToolTip("Creating Startmenu shortcuts, please wait...", @DesktopWidth/2, @DesktopHeight/2, "ECC", 1, 6)
$StartFolderName = "emuControlCenter"
$ECCInstallPath = StringReplace(@ScriptDir, "\ecc-core\tools", "")

DirRemove(@ProgramsDir & "\" & $StartFolderName, 1) ; Remove Old folder is exists
DirCreate(@ProgramsDir & "\" & $StartFolderName) ; Create startmenufolders

; Create shortcut to start ECC
$FileNameToLink = $ECCInstallPath & "\ecc.exe"
$WorkingDirectory = $ECCInstallPath
$LinkFileName = @ProgramsDir & "\" & $StartFolderName & "\" & "Start emuControlCenter"
$Description = "Start emuControlCenter"
$State = @SW_SHOWNORMAL ;Can be @SW_SHOWNORMAL or @SW_SHOWMINNOACTIVE
$Icon = $ECCInstallPath & "\ecc-system\images\icons\ecc.ico"
$IconNumber = 0
FileCreateShortcut($FileNametoLink, $LinkFileName, $WorkingDirectory, "", $Description, $Icon, "", $IconNumber,$State)

; Create shortcut to ECC documentation
$FileNameToLink = $ECCInstallPath & "\ecc-docs\index.html"
$WorkingDirectory = $ECCInstallPath & "\ecc-docs"
$LinkFileName = @ProgramsDir & "\" & $StartFolderName & "\" & "Documentation"
$Description = "emuControlCenter Documentation"
$State = @SW_SHOWNORMAL ;Can be @SW_SHOWNORMAL or @SW_SHOWMINNOACTIVE
$Icon = $ECCInstallPath & "\ecc-system\images\icons\Shell32_icon_71.ico"
$IconNumber = 0
FileCreateShortcut($FileNametoLink, $LinkFileName, $WorkingDirectory, "", $Description, $Icon, "", $IconNumber,$State)

; Create shortcut to ECC website
$FileNameToLink = "http://ecc.phoenixinteractive.mine.nu"
$WorkingDirectory = ""
$LinkFileName = @ProgramsDir & "\" & $StartFolderName & "\" & "ECC Website"
$Description = "Go to ECC Website"
$State = @SW_SHOWNORMAL ;Can be @SW_SHOWNORMAL or @SW_SHOWMINNOACTIVE
$Icon = $ECCInstallPath & "\ecc-system\images\icons\Shell32_icon_14.ico"
$IconNumber = 0
FileCreateShortcut($FileNametoLink, $LinkFileName, $WorkingDirectory, "", $Description, $Icon, "", $IconNumber,$State)

; Create shortcut to ECC official support forum
$FileNameToLink = "http://eccforum.phoenixinteractive.mine.nu"
$WorkingDirectory = ""
$LinkFileName = @ProgramsDir & "\" & $StartFolderName & "\" & "Official support forum"
$Description = "Go to ECC official support forum"
$State = @SW_SHOWNORMAL ;Can be @SW_SHOWNORMAL or @SW_SHOWMINNOACTIVE
$Icon = $ECCInstallPath & "\ecc-system\images\icons\Shell32_icon_171.ico"
$IconNumber = 0
FileCreateShortcut($FileNametoLink, $LinkFileName, $WorkingDirectory, "", $Description, $Icon, "", $IconNumber,$State)

DirCreate(@ProgramsDir & "\" & $StartFolderName & "\Tools")

; Create shortcut to start ECC Live!
$FileNameToLink = $ECCInstallPath & "\ecc-core\tools\eccUpdate.bat"
$WorkingDirectory = $ECCInstallPath & "\ecc-core\tools\"
$LinkFileName = @ProgramsDir & "\" & $StartFolderName & "\Tools\" & "Check for updates!"
$Description = "Check for updates with ECC Live!"
$State = @SW_SHOWNORMAL ;Can be @SW_SHOWNORMAL or @SW_SHOWMINNOACTIVE
$Icon = $ECCInstallPath & "\ecc-core\tools\eccUpdate.ico"
$IconNumber = 0
FileCreateShortcut($FileNametoLink, $LinkFileName, $WorkingDirectory, "", $Description, $Icon, "", $IconNumber,$State)

; Create shortcut to ECC Dignostics
$FileNameToLink = $ECCInstallPath & "\ecc-core\tools\eccDiagnostics.bat"
$WorkingDirectory = $ECCInstallPath & "\ecc-core\tools\"
$LinkFileName = @ProgramsDir & "\" & $StartFolderName & "\Tools\" & "ECC Diagnostics"
$Description = "ECC Diagnostics"
$State = @SW_SHOWMINNOACTIVE ;Can be @SW_SHOWNORMAL or @SW_SHOWMINNOACTIVE
$Icon = $ECCInstallPath & "\ecc-core\tools\eccDiagnostics.ico"
$IconNumber = 0
FileCreateShortcut($FileNametoLink, $LinkFileName, $WorkingDirectory, "", $Description, $Icon, "", $IconNumber,$State)

ToolTip("Startmenu shortcuts created!", @DesktopWidth/2, @DesktopHeight/2, "ECC", 1, 6)
Sleep(1500)
ToolTip("")
Exit
