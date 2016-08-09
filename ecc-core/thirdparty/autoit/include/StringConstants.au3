#include-once

; #INDEX# =======================================================================================================================
; Title .........: String_Constants
; AutoIt Version : 3.3.12.0
; Language ......: English
; Description ...: Constants to be included in an AutoIt v3 script when using String functions.
; Author(s) .....: guinness, jpm
; ===============================================================================================================================

; #CONSTANTS# ===================================================================================================================
; StringCompare, StringInStr, StringReplace Constants
; Indicates if string operations should be case sensitive
Global Const $STR_NOCASESENSE = 0 ; Not case sensitive (default)
Global Const $STR_CASESENSE = 1 ; Case sensitive
Global Const $STR_NOCASESENSEBASIC = 2 ; Not case sensitive, using a basic comparison

; StringStripWS Constants
; Indicates the type of stripping that should be performed
Global Const $STR_STRIPLEADING = 1 ; Strip leading whitespace
Global Const $STR_STRIPTRAILING = 2 ; Strip trailing whitespace
Global Const $STR_STRIPSPACES = 4 ; Strip double (or more) spaces between words
Global Const $STR_STRIPALL = 8 ; Strip all spaces (over-rides all other flags)

; StringSplit Constants
Global Const $STR_CHRSPLIT = 0 ; Each character in the delimiter string will mark the split
Global Const $STR_ENTIRESPLIT = 1 ; Entire delimiter marks the split
Global Const $STR_NOCOUNT = 2 ; Disable the return count

; StringRegExp Constants
Global Const $STR_REGEXPMATCH = 0 ; Return 1 if match.
Global Const $STR_REGEXPARRAYMATCH = 1 ; Return array of matches.
Global Const $STR_REGEXPARRAYFULLMATCH = 2 ; Return array of matches including the full match (Perl / PHP style).
Global Const $STR_REGEXPARRAYGLOBALMATCH = 3 ; Return array of global matches.
Global Const $STR_REGEXPARRAYGLOBALFULLMATCH = 4 ; Return an array of arrays containing global matches including the full match (Perl / PHP style).Global Const $STR_REGEXPMATCH = 0 ; Each character in the delimiter string will mark the split

; StringBetween Constants
Global Const $STR_ENDISSTART = 0  ; End acts as next start when end = start
Global Const $STR_ENDNOTSTART = 1 ; End does not act as new start when end = start
; ===============================================================================================================================
