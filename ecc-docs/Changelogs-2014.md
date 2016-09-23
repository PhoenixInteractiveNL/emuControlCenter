## Changelogs 2014
***
![](https://raw.githubusercontent.com/wiki/PhoenixInteractiveNL/emuControlCenter/images/ecc_splashscreen_116.png)

Version 1.16 (2014.06.24) SUMMER 2014 RELEASE

- Added new platform: Nintendo Wii U
- Removed platform Sharp MZ-700, it was double, the platform
 has been merged with MZ-1500/MZ-700 a time ago!
- Adjusted names for platforms:
 - Nintendo Gamecube to: Nintendo GameCube
 - Nintendo GameBoy to: Nintendo Game Boy
 - Nintendo GameBoy Advance to: Nintendo Game Boy Advance
 - Nintendo GameBoy Color to: Nintendo Game Boy Color
 - Sega Megadrive to: Sega Mega Drive
 - Philips CDI to: Philips CD-i
- Script update for platform:
 - Texas Instruments Ti99 - Added script for classic99 3.x.x
 - Playstation 1 - ePXSe, Added 3 options trough ECC commandline (see script)
- Added filextensions:
 - TI99: C, G, D, and BIN
 - Commodrode 64: c64, nbz, nib
- Added support for compressed CD-Image formats: CSO, CDZ
- Added new feature to ECC to remove LAUNCH data like last played and time played.
- Updated the Spanish (ES) language for v1.15 (Jarlaxe)
- All links, urls an date's are updated due to new website adres .nl
- Fixed option 'none' in ECC Theme select
- Fixed ghosty "er" platform in navigation.
- Fixed missing translations in i18n_metaStorage.
- Fixed default theme colors on plain installation.
- Fixed ECC unpack GUI trigger to work also for ZIP files, therefore the internal
 PHP extractor has been replaced with 7zip.
- Fixed ECC extensions issue in the database when parsed with 7ZIP (7z/rar)
 - column title in table fdata
- Adjusted the new website and server URLs from .mine.nu to .nl
- Added 'mplayer' video player to play video files in ECC, this replaces the
 VLC ActiveX plugin etc, this did not work on every computer or windows installation.
 This is a thirdparty 'stand alone' program so no installation is needed.
 Website: http://mplayerwin.sourceforge.net
- ECC v1.152 build 06
 - Fixed a bug where the custom theme colors of the user dit not work anymore
   due to overrride of theme color settings.
   An config option has been made to enable or disable ecc-theme colors, so that
   user colors will be used.
- ECC v1.152 build 05
 - Added trigger to show a GUI (and progressbar) when unpacking big files.
   - This is configurable (trigger) in the options menu.
 - Fixed the 'big file' message pop-up even if the file is not big.
- ECC v1.152 Build 02
 - Extended multiplayer options to *, NO, 2P, 3P, 4P
- Notepad++ Improvements:
 - eccSCript extension now being recognized as autoit script
   this gives colors to functions etc.
 - Added autoit autocompletion and function helper
   source: http://www.autoitscript.com/forum/topic/104331-notepad-with-autoit-user-calltips/
 - Added plugins to improve creating scripts:
   - Overview: http://sourceforge.net/apps/mediawiki/notepad-plus/?title=Plugin_Central
   - Color picker v2.3
     - http://sourceforge.net/projects/npp-plugins/files/ColorPicker/Color%20Picker%20v.2.3/
   - Compare plugin v1.5.6.2
     - http://npp-compare.sourceforge.net/
     - http://sourceforge.net/projects/npp-compare/
   - Hasmaker v1.0
     - http://download.tuxfamily.org/nppplugins/NppHashMaker/

- ECC Startup
 - v2.4.1.2 (2014.05.12)
   - Fixed a crash if ECC is reloading on with 'empty unbpack folder' setting on.
   - Fixed website link in exe description.
   - Fixed fullscreen on startup, now true fullscreen (maximized)
   - Removed 'always on-top' setting of the splashscreen.
 - v2.4.1.1 (2014.04.11)
   - Fixed a bug where ecc could not detect the proper windows version to set
     compatibility for xpadder, now it will always write the REG key.
 - v2.4.1.0 (2014.04.09)
   - Added code to install a "SIDE" update with 7zip, for example to update Autoit wich
     eccUpdate cannot do, because of "file in use" while running the script.
   - Compiled with new Autoit v3.3.10.2, may return a false positive in some virusscanners!

- eccUpdate
 - v1.0.0.8 (2014.04.10)
   - Fixed a issue where executable files where not found due to wrong path.
 - v1.0.0.7 (2014.04.09)
   - Improves code for deleting files and/or folders by setting attributes to write.
   - Added new function to force eccUpdate to close

- ECC ScriptSystem
 - v1.3.0.0 (2014.05.18)
   - Fixed Daemon tools mounting (option "scsi" added)
   - New feature: ECC commandline parameters are now passed trough
   - Added CCD, TOC, MDS index files for Daemontoools
     referred in ECC CONFIG as "index" files.
 - v1.2.1.6 (2014.03.29)
   - Fixed a bug in the Daemon Tools algoritm
 - v1.2.1.5 (2013.12.29)
   - Added img & bin file extensions
     (possibly needs .cue or .m3u file to assist).
   - Changed traytips to tooltips.

- MobyGamesImporter
 - v1.1.0.2 (2014.05.25)
    - Fixed bug when searching for data where multiple files are packed within the same archive.
    - On manual search the filename will be put in the title field (when there is no META)
 - v1.1.0.1 (2014.04.23)
   - Fixed MG description fetching due to changed website structure
   - Moved MG tag on the end of the ROM description.
   - Added cancel button to platform auto import.
   - Changed the logo to the new 2014 version
   - Some GUI changes
 - v1.1.0.0 (2014.04.23)
   - Big update where you can select wich data should be updated.
   - Settings are stored in a INI file.
   - Rom Manual = Selections can be made what field to store
     (genre is not getting stored, but for info purposses only)
   - Rom Auto -> ROM has No META data = FULL auto, so everything gets added,
     including the 'fixed' name
   - Rom Auto -> ROM has META data = Asks wich field/value to overwrite,
     option to maintain the fixed name or use the ROM filename.
   - Platform Auto -> Asks wich field/value to overwrite, if no META is
     available all data will be added, there is a option to use the fixed
     name or use the ROM filename.

- ECC 3D Gallery
 - v1.2.0.0 (2014.04.23)
   - Fixed a bug in the scrollbox, the scrollbar did not appear.
   - Updated gallery's
     - simpleviewer
     - tiltviewer
   - Added gallery's from flashxml.net:
     - 3dphotorotator
     - 3dphotozoom
     - circulargallery
     - polaroidgalleryfx
 - v1.1.0.8 (2014.03.28)
   - Combined all global variables in the script eccToolVariables
   - Changed URL's to form MINE.NU to .NL (due to changing webadres)

- Create ECC startmenu shortcuts
 - v1.0.0.3 (2014.03.28)
   - Combined all global variables in the script eccToolVariables
   - Changed URL's to form MINE.NU to .NL (due to changing webadres)

- eccDatFileUpdater (DFU)
 - v1.2.5.9 (2014.03.29)
   - Fixed a bug where the 7Z backup was not created.
   - Fixed a bug calling eccToolsVariables script
 - v1.2.5.8 (2014.03.28)
   - Combined all global variables in the script eccToolVariables

- eccDiagnostics
 - v1.0.0.3 (2014.03.28)
   - Combined all global variables in the script eccToolVariables

- eccKameleonCode
 - v1.0.0.2 (2014.03.28)
   - Combined all global variables in the script eccToolVariables

- eccThirdPartyConfig
 - v1.0.0.2 (2014.03.28)
   - Combined all global variables in the script eccToolVariables

- eccToolVariables
 - v1.0.0.1 (2014.03.29)
   - Updated variables in eccToolVariables
 - v1.0.0.0 (2014.03.28)
   - Combined all global variables in the script eccToolVariables

- eccUpdate
 - v1.0.0.6 (2014.03.28)
   - Combined all global variables in the script eccToolVariables
   - Changed URL's to form MINE.NU to .NL (due to changing webadres)

- eccVideoPlayer
 - v2.0.0.1 (2014.05.10)
   - Fixed video window flashing when giving focus back to ECC
 - v2.0.0.0 (2014.05.04)
   - New initial release that uses 'Mplayer' instead of VLC activeX plugin.
 - v1.1.0.4 (2014.03.28)
   - Combined all global variables in the script eccToolVariables

- EmuMoviesDownloader (EMD)
 - v1.2.1.1 (2014.03.28)
   - Combined all global variables in the script eccToolVariables

- gtkThemeSelect
 - v1.0.0.3 (2014.03.28)
   - Combined all global variables in the script eccToolVariables

- iccImageInject
 - v1.1.0.8 (2014.03.28)
   - Combined all global variables in the script eccToolVariables
   - Changed URL's to form MINE.NU to .NL (due to changing webadres)

- MobyGamesImporter (MGI)
 - v1.0.0.2 (2014.03.28)
   - Combined all global variables in the script eccToolVariables

- ImagePackCenter (IPC)
 - v2.2.1.1 (2014.04.23)
   - Fixed a bug where IPC crashes if there was no imagefile extension.
 - v2.2.1.0 (2014.03.29)
   - Combined all global variables in the script eccToolVariables

- Updated thirdparty tools
 - Autoit v3.3.12.0
 - Notepad++ v6.5.5
 - 7-Zip v9.32
 - Stripper v1.5.7.70

- Updated DAT files for:
 - CPS-1  : v0.152 to v0.153 (mame)
 - CPS-2  : v0.152 to v0.153 (mame)
 - CPS-3  : v0.152 to v0.153 (mame)
 - MAME   : v0.152 to v0.153
 - MODEL1 : v0.152 to v0.153 (mame)
 - MODEL2 : v0.152 to v0.153 (mame)
 - NAOMI  : v0.152 to v0.153 (mame)
 - NEOGEO : v0.152 to v0.153 (mame)
 - PGM    : v0.152 to v0.153 (mame)
 - S11    : v0.152 to v0.153 (mame)
 - S16    : v0.152 to v0.153 (mame)
 - S18    : v0.152 to v0.153 (mame)
 - S22    : v0.152 to v0.153 (mame)
 - ZINC   : v0.152 to v0.153 (mame)