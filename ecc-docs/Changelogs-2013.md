## Changelogs 2013
***
Version 1.15 (2013.12.08)

- Added new platforms:
 - Microsoft X-Box One
 - Nintendo 3DS
 - Sony Playstation 4
 - Sony Playstation Vita
- MobyGames Importer (MGI)
 - Grab developer/publisher/year and description of your roms from MobyGames.com
 - You can do this Platform based (batch-auto) or Rom based (auto or manual).
 - NOTE: Fetching description data isn't flawless, and may contain some
   unwanted strings!
- Added thirdparty software: HEX Editor (HxD v1.7.7.0)
- Added Top-menu option to start notepad++ (tools)
- Added Top-menu option to start a HEX editor (tools)
- New startup sound (old one is still preserved)
- Replaced some files to do a bit of code cleanup!
- Fixed EmuMovies content download for Playstation 1
- Fixed possible startup problems on systems with other language charset
 who has strange characters in the ECC path.
- Fixed a bug in the "use cue system" if there are more "." (dots) in the filename.
- Fixed the Link to the online documentation due to emuControlCenter CMS website.
- Updated ES language and FR INI language files.
- Updated PT language to v1.14.
- Script updates
 - Platform DosBox v1.0.0.4
   - Added new hotkey: SHIFT+C to configure your DOS game (setup.exe, etc.)
   - Fixed problems when there are spaces in the path/folder names
 - Platform: Amiga
   - Winaue v1.0.0.9b
     - Added GetGemusConfig v1.4 RELOADED permanently! (Phoenix)
      - GAMEBASE/GEMUS ADD-ON v1.0.0.4
        - Changed tray messages to "center" Tooltips.
   - Winaue v1.0.0.9
     - Fixed crash if there are too many config lines available.
- ECC url updates
 - Website
   - www.camya.com/ecc is now ecc.phoenixinteractive.mine.nu
 - Forum
   - ecc.phoenixinteractive.mine.nu is now eccforum.phoenixinteractive.mine.nu

- MobyGames Importer (MGI)
 - v1.0.0.1
   - Added better support to fetch the 4-digit release year if there is a month added.
   - Fixed "Fixed RomName" where there is () and [] in the filename.
 - v1.0.0.0
   - Initial release!
- ECC Startup
 - v2.4.0.2
   - Fixed a bug where language settings for ecc startup aren't loaded properly.
 - v2.4.0.1
   - Fixed a problem where the ECC version isn't generated at first startup.
   - Adjusted file info's/details in ECC executable properties.
- eccScriptSystem
 - v1.2.1.1 (2013.02.06)
   - Fixed a bug where amiga settings are stored in the wrong folder.
- ImagePackCenter
 - v2.2.0.9 (2013.02.19)
   - Fixed a imagepack export bug in "name" style filenames (like emumovies)
     with games that have a "." (dot) in their file name, the exported filenames
     should be ok now.
- emuMoviesDownloader
 - v1.2.1.0
   - Fixed "MAME Artwork" downloads, these download seems to be ZIP files now, so by
     adding a unpack feature for ZIP files these images are now extracted from the
     achives and given the proper name!
   - Added check if 7Zip is working (due to "MAME Artwork" ZIP downloads)
   - Added check if media is writable.
 - Fixed a bug for the "Game Boy" platform when downloading resources from emuMovies
   The platform was not good detected due to a wrong double string in the EMD list.
 - v1.2.0.6
   - Improved search query's by removing "trash" in the search result
 - v1.2.0.5
   - Redesigned the EMD GUI, textbox has been made bigger!
   - Fixed bug where EMD did not close after server error message.
   - Fixed url in the EMD banner on the login menu to go their website.
- eccScriptSystem
 - v1.2.1.2
   - Fixed bugs where Amiga script variables are not declared, these where returning an error.
- eccUpdate
 - v1.0.0.5
   - Made some change in the GUI notification screen, now the update info is displayed
     first before the download starts, also made some other textual improvementents
   - Added new feature to skip (big) update if there is a better update available
     down the line, like MAME datfile updates, this save bandwith for both party's!
- Updated thirdparty tools
 - Notepad++ v6.5
 - 7-Zip v9.30

- Updated DAT files for:
 - CPS-1  : v0.150 to v0.151 (mame)
 - CPS-2  : v0.150 to v0.151 (mame)
 - CPS-3  : v0.150 to v0.151 (mame)
 - MAME   : v0.150 to v0.151
 - MODEL1 : v0.150 to v0.151 (mame)
 - MODEL2 : v0.150 to v0.151 (mame)
 - NAOMI  : v0.150 to v0.151 (mame)
 - NEOGEO : v0.150 to v0.151 (mame)
 - PGM    : v0.150 to v0.151 (mame)
 - S11    : v0.150 to v0.151 (mame)
 - S16    : v0.150 to v0.151 (mame)
 - S18    : v0.150 to v0.151 (mame)
 - S22    : v0.150 to v0.151 (mame)
 - ZINC   : v0.150 to v0.151 (mame)
 - SVM    : v0.99.1.8 (2013-10-03)