#include <ButtonConstants.au3>
#include <EditConstants.au3>
#include <GUIConstantsEx.au3>
#include <StaticConstants.au3>
#include <WindowsConstants.au3>

#Region ### START Koda GUI section ### Form=add_icon.kxf
$Form1 = GUICreate("Add icon", 511, 122, -1, -1)
$Input1 = GUICtrlCreateInput("", 64, 8, 313, 21)
$Button1 = GUICtrlCreateButton("...", 376, 8, 27, 21)
$Input2 = GUICtrlCreateInput("", 64, 40, 313, 21)
$Button2 = GUICtrlCreateButton("...", 376, 40, 27, 21)
$Input3 = GUICtrlCreateInput("", 64, 72, 313, 21)
$Button3 = GUICtrlCreateButton("...", 376, 72, 27, 21)
GUICtrlCreateLabel("Template", 8, 12, 48, 17)
GUICtrlCreateLabel("Icon", 8, 44, 25, 17)
$Button4 = GUICtrlCreateButton("Save", 424, 24, 75, 25)
$Icon1 = GUICtrlCreateIcon("", -1, 8, 80, 36, 24, BitOR($SS_NOTIFY,$WS_GROUP))
GUISetState(@SW_SHOW)
#EndRegion ### END Koda GUI section ###


While 1
	$msg = GuiGetMsg()
	Select
	Case $msg = $GUI_EVENT_CLOSE
		ExitLoop
	Case $msg = $Button1
        GUICtrlSetData($Input1, FileOpenDialog("Open template", @ScriptDir, "Templates (template_*.xml)"))
	Case $msg = $Button2
        GUICtrlSetData($Input2, FileOpenDialog("Open icon", @ScriptDir & "\Country_icons", "Icons (*.ico)"))
        GUICtrlSetImage($Icon1, GUICtrlRead($Input2))
	Case $msg = $Button3
        GUICtrlSetData($Input3, FileSaveDialog("New language name", @ScriptDir, "Language files (lang_*.xml)", 16, "lang_"))
        If StringRight(GUICtrlRead($Input3), 4) <> ".xml" Then GUICtrlSetData($Input3, GUICtrlRead($Input3) & ".xml")
    Case $msg = $Button4
        $sInputFile  = GUICtrlRead($Input1)  
        $sIconFile   = GUICtrlRead($Input2)
        $sOutputFile = GUICtrlRead($Input3)
        
        If ($sInputFile = "") Or Not FileExists($sInputFile) Then
            MsgBox(0, "Error", "Please select input (template) file.")
            ContinueLoop
        EndIf

        If ($sIconFile = "") Or Not FileExists($sIconFile) Then
            MsgBox(0, "Error", "Please select icon file.")
            ContinueLoop
        EndIf

        If $sOutputFile = "" Then
            MsgBox(0, "Error", "Please select output file.")
            ContinueLoop
        EndIf
        
        $sTemplate = FileRead($sInputFile)

        $hIconFile = FileOpen($sIconFile, 16)
        $sData = StringTrimLeft(String(FileRead($hIconFile)), 2)
        FileClose($hIconFile)

        $sIconData = "<icon>" & @CRLF
        For $i = 1 To FileGetSize($sIconFile) * 2 Step 64
            $sIconData &= @TAB & @TAB & "<bin>"
            $sIconData &= StringMid($sData, $i, 64)
            $sIconData &= "</bin>" & @CRLF
        Next
        $sIconData &= @TAB & "</icon>"

        $sTemplate = StringRegExpReplace($sTemplate, "(?s)<icon>.*</icon>", $sIconData)
        FileDelete($sOutputFile)
        FileWrite($sOutputFile, $sTemplate)
        MsgBox(0, "Done", "Icon successfuly added.")
	Case Else
		;;;;;;;
	EndSelect
WEnd
