## Changelogs 2008
***
Version 0.9.8.R2 (2008.12.02)

- Added platforms:
 - Apple 2GS
 - Camputers Lynx
 - Coleco Adam
 - Híradástechnika Szövetkezet HT-1080Z
 - Interact Home Computer System
 - Jupiter Cantab - Jupiter Ace
 - Microsoft XBox 360

- Added Scripts:
 - PALE (Camputers Lynx)
 - VINTER (Interact Home Computer System)
 - DCMOTO v10.1 (Thomson MO5)

- Small updates
 - Fixed bug where Xpadder always ask where to save the setting, by adding a INI file.
   (if you already configured Xpadder, the original .ini will be renamed to .bak)
 - Fixed the 'error reporting' notices and warnings, they where set in php.ini
   but didn't help because it was re-configured in the PHP files.
 - Cleaned the system informations by removing most of the Wikipedia
   references in-page, like:
   - [1] t/m [50]
   - [citation needed]
   - [verification needed]
   - [dead link]
   - [...]
 - DAT file updates:
   - MAME:    v0.124b to v0.128
   - CPS-1:   v2007.09.22 to v2008.10.18
   - CPS-2:   v2007.11.09 to v2008.10.18
   - Neo-Geo: v2008.01.01 to v2008.10.18
   - Model2:  v2008.01.03 to v2008.06.05

- ECC Startup
v2.3.1.2
- Fixed a small bug where ECC startup did create the userfolders everytime when
 one or more platforms were disabled.
v2.3.1.1
- ECC Startup will now scan your userfolder and create userfolders if nessesary
 and if the platform is set active!, this way when new platforms are inserted,
 the userfolders for those added platforms will be automaticly created!
 (thanks to Andreas for the 'create userfolder' implementation!)
v2.3.1.0
- Made the splashscreen configurable & customizable
 - You can edit ecc-themes\[THEMENAME]\splashscreen.ini to fit your needs.
 - When 'splashscreen.ini' is not found in a ECC theme it uses the settings
   from the "ecc-themes\default\splashscreen.ini" file.
v2.3.0.5
- Let ECC start in fullscreen again.
- Found a better way to 'by-pass' the PHP-GTK window bug.
- Many small tweaks and improvements.

- 3rd party updates
 - Updated, Notepad++ v5.1.1
 - Updated, 7-Zip v4.62


Version 0.9.8.R1 (2008.09.28)

- Added platforms:
 - Pinball - Visual Pinball
 - Seiko Ruputer/Matsucom OnHand PC
 - Sega Titan Video game system
 - Memotech MTX 500/512

- ECC small updates:
 - Improved the multifile handler for the eccscriptrom DAT.
 - Improved the Amiga script to handle LONG filenames again.
   - Returned the amiga 8.3 setting to 0 again.
 - Updated the French language for 0.9.8
 - Renamed 'Nintendo Entertainment System' to 'Nintendo NES/Famicom'.
 - Renamed 'Nintendo Famicom/FDS' to 'Nintendo FDS'.
 - Renamed eccid 'famicom' to 'fds'.
 - Fixed a bug where FDS parser did not work properly (nes eccid).
 - Added support for thirdpartytool: Xpadder.
   - You can configure this in ECC config.
 - Removed the BEEP sound 'GTK error bell' when reaching end of list/page.
 - Added extensions
   - Amstrad CPC: BAS, BIN
   - Atari 8-bit: ATX
   - Oric Atmos: ORC
 - Removed extensions
   - DOSBox: ISO, CSO, NRG, MDF
 - Removed CCD extensions, becouse of duplicate ROM import, a CCD file is normal Clone CD behaviour.
 - Fixed a bug where ECC did crash when parsing large ZIPPED ROM files >1GB.
 - Fixed a bug where ECC selected the 'afterhours' theme at first restart.

- ECC script updates:
 - Added scripts:
   - Sharp X68000 (pacogf)
   - Atari 8-bit (phoenix).
 - Fixed a bug in the 'XM6' script. (Sharp X68000) (pacogf)
 - Added support for Windows Vista in the 'NullDC' script. (Sega Dreamcast) (wayne69x)
 - Added support for Windows Vista in the 'PT' script. (Atari Jaguar) (wayne69x)

- ECC GTK-theme updates:
 - Removed the 'Cold Blue' theme, due to problems with the 'libsmooth' engine.
 - Removed 'libsmooth' GTK engine, wich is not compatible anymore.
 - Added new GTK theme 'Live'.
 - Added theme: LiNsta3, this gives ECC an vista look! (select with eccTheme)

- ECC Startup
v2.3.0.0
- Added support for thirdpartytool: Xpadder.
v2.3.0.1
- Fixed a problem where ECC restart made a bug in error.log.
v2.3.0.2
- Added missing translations for the Xpadder addon
- Now registering OCX files (for the tools) at startup (once), so people who use
 RAR packages, have the OCX files registered at ECC startup.
v2.3.0.3
- ECC now closes Xpadder when ECC closes!
- Improved OCX registering by scanning the tools folder (instead of static commands).
- Improved the option 'minimize to tray' by directly activating it when ECC reloads!
- Improved the option 'start xpadder on startup' by directly activating it when ECC reloads!
v2.3.0.4
- Re-implemented the subroutine to ask for automatic bugreport,
 it was missing after ECC startup v2.9.0.9

- ECC Live!
v3.1.0.8
- Fixed a problem with some subroutines not working properly.
 - Also where ECC did not restart in kickstart mode #2.
- Fixed the double 'vv' version tag in the LOG file.
- Fixed a problem where ECC did shutdown where it should not do this.
v3.1.0.9
- Added driveletter detection.
- Added free space detection.
- Added new update function to replace strings in files.
- Added free space checking before installing update.
- Fixed a problem where the messagebox didn't show up in 'kickstart mode'.
v3.2.0.0
- Fixed temporally file removal when ECC Live! is forced to shutdown.
- Fixed a bug to replace strings on the first line of a file.
- Fixed a bug where replacing strings in a file would write a currupt file.
- Added a new function to add a line in a file to a specific linenumber.
- Added errorhandler if a temp file could not be renamed.
- Added errorhandler if a file to replace strings does not exist.
- Changed discription how to handle when an update has to be done manually.

- ECC Live! updater
v1.2.0.2
- Fixed a problem with some subroutines not working properly
 - Also where the ECC Live! update did not unpack properly.

- 3rd party updates
 - Added, Xpadder (for joystick control)
 - Updated, Notepad++ v5.0.3
 - Updated, 7-Zip v4.60 beta


Version 0.9.8b (2008.07.25)

- Added platforms
 - Elektor TV Games

- Small fixes
 - Added 'readme.txt' file in the ecc-root.
 - Updated 'Dutch' translations for 0.9.8.
 - Fixed a problem with the media_current (media_current of media_count)
   - Now the media_current is not stored with 0 to the database!

- ECC script updates
 - Commodore Amiga (winuae) v1.0.0.7
   - Adjusted the script to use new MULTI output with ZIP in eccScriptDat (TheCyberDruid)
     NOTE: It only works with the 8.3 setting enabled, so default config is
     overwritten and set with 8.3 enabled.

- ECC Live!
v3.1.0.7
 - Added new installer function 'execute_normal', without waiting function.
 - Added new installer function 'execute_hide', without waiting function.
 - Added new installer function 'ecclive_shutdown', wich forces ECC Live! to
   shutdown before installing next update.
 - Added new installer function to check the version of ECC Live! (to create stops)
 - Added auto-retry mode (3 times) if there is a problem writing to the TEMP folder.
 - Added DEBUG mode so the developers can test ECC Live! without using online data
 - Fixed a problem where ECC Live! updater would not start to update ECC Live!.
 - Fixed a problem with reading the local update info INI.
 - Fixed a problem where ECC Live! did not start ECC again in 'kickstart mode'.

- ECC Live! updater
v1.2.0.1
 - Fixed php INI to 'php.ini' (instead of emucontrolcenter-php.ini)
 - Fixed a problem with reading the local update info INI.

- 3rd party updates
 - Updated, Notepad++ v5.0.2

![](https://raw.githubusercontent.com/wiki/PhoenixInteractiveNL/emuControlCenter/images/img_misc_gears.jpg)

Version 0.9.8 (2008.07.20) ECC RESTRUCTURED!

- Platforms added
 - Enterprise Systems - Enterprise 64/128
 - Commodore Amiga CD32
 - Commodore CDTV
 - Commodore PET
 - COMX WOL Comx-35
 - CVT Mega Duck/Cougar Boy
 - Hartung Game Master
 - Sega Dreamcast VMU
 - Sega Naomi
 - Sinclair QL

- Small updates/fixes
 - Fixed all emulator presets for MESS emulator platforms by adding the %ROM% parameter.
 - Merged 'Sharp MZ-700' (mz700) and 'Sharp MZ-1500' (mz1500) into 'Sharp MZ-700/1500' (eccident=mz1500)
 - Merged 'Commodore 16' (c16) and 'Commodore Plus/4' (cplus4) into 'Commodore 16/plus4' (eccident=c16plus4)
 - Removed .WAV extension for 'Sinclair ZX Spectrum'
 - User path corrections for platforms (ECCid):
   - gba, gbc, gg, sms, ws, wsc, x68000
 - Fixed extensions for 'Texas instruments TI-99'.
 - Added extensions
   - All CD image platforms all have: ISO, CSO, IMG, BIN, NRG, MDF, MDS, CCD
   - Commodore Amiga: ADZ, HDZ
   - Micobee Systems - Microbee: COM, SUB, MWB, BAS, BIN, DAT, BEE, Z80, MAC, MCL, ML, Z
   - Microkey Primo: BIN
 - Added best emulator settings
   - Micobee Systems - Microbee
   - Updated, the ScummVM default config parameter.
   - Updated, the Microbee default config parameter.
 - Update DATFILES
   - MAME v0.123 to v0.124b

- Whole new scriptsystem (ecc-script folder)
 - Added scripts for:
   - Atari Jaguar (PT, Project Tempest)
   - Cellphone Java (JADmaker)
   - Commodore Amiga (winuae)
   - Commodore VIC-20 (xvic)
   - Laser Disc (daphne)
   - Gamepark GP32 (geepee32)
   - Nintendo Gamecube (Dolphin)
   - Nintendo Pokemon Mini (Minimon)
   - Panasonic 3DO (FreeDo)
   - Philips CD-I (wcdiemu)
   - Sega Dreamcast (NullDC)
   - Sega Dreamcast VMU (DirectVMS)
   - Sega Saturn (SSF)
   - Sony Zinc (ZiNc)
 - Including CD-Image mounting with Daemon tools LITE
   - Including ZIP support (autounpack option in ecc)

WIP 06 (2008.07.19)
- fixed crash while parsing optimizing roms
- added "rom_file_extension" for eccScriptRom.dat
- implemented new default meta setter mechanism for (see a2600+amiga)
- fixed eccScript preview output (removed path)
- added 8.3 filepath for eccScriptRom.dat
- added [p] pirate for dump type dropdown
- added support for default meta per file extension!
-- multiplayer
-- netplay
-- media_type
-- media_current
-- media_count
-- storage

WIP 05 (2008.07.14)
- Improved the MULTI INI lines in eccScriptRom.dat for packed and unpacked filenames.
- Fixed a bug where the CUE handler would write warnings in the log!
- added czech for language selection
- added dump type dropdown in meta edit
- catch exception, if shortcut for file selector is set already.
- not using the eccScript tool anymore, now using autoit3 directly.

WIP 04 (2008.07.04)
- fixed typo in multiplayer dropdown
- Added more fields to meta compare/merge function
-- Programmer, Graphics, Musican, Media Type, Region, Storage, USK
- fixed yes|no translations for meta options (rom detail area)
- New CELL_I and NAV_I images so you can also see, that a rom is not available if the rom have images
- Reorganized the MEDIA images, now also placed in the platform folder
- Added a tools for ECC Startup to use GD2 instead of Imagemagick
- Fixed unpack problem, if rom is in a zip-subfolder!
- added image download from romdb image-server (rom context menu)
- emulator config
-- implemented "use .cue file" for isos
-- if 8.3, escape path is autodisabled
- zip/7-zip unpack now only unpack, if emulator is available!
- added icon images for messageboxes
- reduced the size of system output in fileext_problem popup
- removed error output if no eccscript available in metaedit popup
- fixed region dropdown in german translation
- added local version script
- fixed metainfo area drowdowns and add translations for values
- replaced "change languages" by language string free image.
- organized rom context "meta edit" in submenu
- create idt folder, if not available
- added an new multi section to the eccScriptRom.dat
-- automatic detect multifile sequences from filename
-- detected syntax "filename (3/9).ext", "filename (Disc 3of9).ext", "filename-3.ext", "filename-A.ext"
-- automaticly create MULIT_ROM_FILE_N commandline paramenters, if file is available (case sensitive)
-- up to range (e.g. 15 if 4of15), if no range is set, up to 26 or aA-zZ (eg filename-a form -a to -z)
--- MULTI_ROM_FILE_1 = "monke1-1.adf"
--- MULTI_ROM_FILE_2 = "monke1-2.adf"
--- MULTI_ROM_FILE_1 = "test(!)(1/2)(Spanish).adf"
--- MULTI_ROM_FILE_2 = "test(!)(2/2)(Spanish).adf"
--- MULTI_ROM_FILE_1 = "test(Disk A)(German).adf"
--- MULTI_ROM_FILE_2 = "test(Disk B)(Spanish).adf"
- implemented new parameters for the commandline of emulators
-- you can add ALL these to the commandline in this syntax
--- %KEY% e.g. %META_NAME% %FILE_ROM_CRC32% %AUDIT_ROM_OF%

WIP 03 (2008.06.06)
- implemented 7zip packed file parsing
- implemented 7zip auto unpack
- fixed wrong zip/7zip filename in file info
- fixed wrong filename output in detail list for zip/7zip
- fixed little bug in ecc-script
- silence missing index in mame import
- Bugfix in 'cWebServices.php'
- 7-zip parsing fix
- RomDB fix

WIP 02 (2008.05.27)
- new advanced eccScript system...
-- eccscripts can now found in the ecc-script folder organized by systems
-- new eccScriptRom.dat contains all needed informations from
--- file (name, path, size, extension aso.)
--- meta (name, media type/current/count, player, info id and string)
--- audit (all available informations from CM dat like name, clone_of, rom_of, set type)
--- emulator (all settings you have made in ecc configuration)
--- ecc (platform name, category, supported extensions)
-- These informations can be used direct in the eccscripts!
- implemented new emulator template / rom overwrite for eccscript in metaEdit popup
-- Used to give eccScript emulator specific config values
-- Rom stores the diff into the ecc-script user folder.
- metaEdit - implemented new File information tab.
- fixed sorting bug in rom lists
- optimized quick filter for roms without meta data
- implemented search for rom meta in romdb and google
- Media count now also shown on rom details panel
- Removing roms from db now also show fileextension in filename
- now also left mouse click in empty romlist open "add rom context"
- refactoring of the rom file, meta and audit data.
- Updated some i18n strings
- added image slot "ingame_loading" e.g. for 8bit loading screens
- now info_string and id also are added to romdb
- moved emuControlCenter gui translations to translations/ folder
- replaced old inline help with default readme.txt
- added mechanism to show different images in confirm and info popups (see add bookmark)
- many optimizations, cleanup, refactoring!
- fixed tooltip warnings
- fixed wrong dropdown selections!

WIP 01 (2008.05.06)
- fixed checksums for atari lynx roms. header now removed from crc32 calculation

- ECC Startup
v2.2.0.0
- Removed all MD5 hash checks.
- Removed OS detection, now using the 'ecc-core' structure.
- Adjusted all paths to the new 'ecc-core' structure.
v2.2.0.2
- Increased free space needed for media images to 9 MB.
- Removed code residue from the ecc core detection.
- Removed splash time out calculation, now always 60 seconds!
- Removed the envment.ini creation, not needed anymore.
- Fixed the titlebar update bug on first restart. (thanks to Andreas)
- Fixed the ECC Bugreport path in the translations.
v2.2.0.5
- Adjusted the MEDIA image location.
- Now using GD2 instead of imagemagick.
- Added percentage counter for the creation of media images.
- Added creation of CELL_I & NAV_I images.
v2.2.0.6
- Added 1 decimal in the percentage counter.
- Increased space needed for media images and userfolders to 11 MB.
v2.2.0.7
- Fixed a bug where the proper translation from the INI did not work.
v2.2.0.8
- Fixed a bug where the one-time-message box appears twice.
- Fixed a bug when the language selector did not work properly.
v2.2.0.9
- Startup ECC again with 'php.ini' (instead of 'php-emucontrolcenter.ini')

- ECC Live!
v3.1.0.0
- Removed OS detection, now using the 'ecc-core' structure.
- Adjusted all paths to the new 'ecc-core' structure.
v3.1.0.5
- Fixed to get the OS info from the host INI (instead of the envment INI).
- Fixed crash by adding an error handler when 'ecc_local_update_info.ini' could not be found.
- Added messageboxes to inform the user of updates and error situations.
- Added a more 'detailed' information wich file is missing.
v3.1.0.6
- Use 'php.ini' again instead of 'php-emuControlCenter.ini' for unpacking files.
- Added better support to detect the installtype, for the 0.9.8 installer.
- Fixed a problem with the language detection, so the language should be working properly again.
- Fixed a problem where the 'info' box did not appear on some error messages.
- Maximum updates before asking to download new ECC version raised from 20 to 30.
- Changed layout & background.

- ECC Live! update
v1.2.0.0
- Adjusted all paths to the new 'ecc-core' structure.

- ECC Bugreport
v2.3.0.0
- Removed OS detection, now using the 'ecc-core' structure.
- Adjusted all paths to the new 'ecc-core' structure.
v2.3.0.1
- Now using system information from the HOST INI (instead from envment ini)
v2.3.0.2
- Now using FSUM to compare file hashes (instead of QuickSFV).
- Temporally hash results no longer in %user_temp% folder but
 are stored in the tools folder itself.
- Added GHS (get hash) file, it is actually a helping batchfile,
 it is renamed as a GHS file to prevent the user from starting it directly.
v2.3.0.3
- Fixed a bug where the user configs 'general' and 'history' INI's
 could not be found.
v2.3.0.4
- Read 'php.ini' again instead of 'php-emuControlCenter.ini'
- Removed the GHS file, bugreport now writes the file itself, instead of renaming it.

- Themes
 - Updated GTK Afterhours theme. (Cyrille)
 - Added GTK 'Milk 2.0' theme. (Cyrille)
 - Added ECC 'afterhours'' theme. (made by Cyrille)
 - Added ECC 'nature-full-green' theme. (made by Cyrille)
 - Fixed a typo in the INI of the ECC theme 'ecc-miss-bubblegum'.

- ECC Script
 - Removed, now using autoit directly.

- Core components
 - Updated, PHP-GTK v2 to v2.0.1
 - GTK Core update from v2.12.8.0 to v2.12.9.0

- 3rd party updates
 - Added, 7zip (for handling 7zip files)
 - Updated, Notepad++ v5.0
 - Updated, AutoIt v3.2.12.1
 - Removed, Imagemagick (not needed anymore, now using GD2)
 - Removed, QuickSFV (not needed anymore, now using fsum)


Version 0.9.7.R1 (2008.05.28)

- Added platforms
 - Commodore Amiga
 - Microkey Primo
 - Sinclair ZX81

- Small updates
 - Fixed the Atary Lynx importing, by removing the 64 bytes
   header when nessesary, so that the CRC32 will be good.
 - Fixed the white border around the ECC GUI buttons.
 - Replaced necessary CELL images with the eccident name for that platform.
 - Removed all CUE extensions, these are not ROM files.
 - Renamed 'Tandy/Radio Shack TRS-80' to 'Tandy/Radio Shack TRS-80 Color Computer'
 - Renamed 'Applied Technologies Microbee' to 'Microbee Systems - Microbee'
 - Replaced the 'Applied Technologies' to the 'Microbee Systems' logo in the
   Microbee system platform.
 - Fixed necessary emulator pathnames and added where needed.
 - Added the 'Casio PV-2000' informations/factsheet.
 - Added the watara logo in the 'Watara Supervision' platform.
 - Cleaned the 'Watara Supervision' teaser by removing lost pixels.
 - Cleaned the 'ScummVM' teaser image by removing lost pixels.

- Adds & fixes for best parameter/emulators:
 - Added the Xroar emulator for the TRS-80 Color Computer.
 - Added the ubee512 emulator for the Microbee System.
 - Added the 'X millennium T-tune' emulator for the Sharp X1 Computer.
 - Added the 'WinX1' emulator for the Sharp X1 Computer.
 - Selected Fusion to be the best emulator for the Sega.
 - Fixed the emulator homepage for the Pokemon Mini 'Minimon' emulator.
 - Added best emulator settings for
   - Amstrad CPC
   - Sharp X68000
   - Sony Playstation 1

- Added Extensions:
 - Acorn BBC: ADL
 - Applied Technologies Microbee: DSK, D40, DS40, S80, SS80,
   D80, DS80, D82, DS82, D84, DS84 (removed ROM, BIN)
 - Atari 8-bit: EXE, COM, A8S
 - Atari 5200: CAR
 - Bandai Wonderswan: BIN
 - Bandai Wonderswan Color: BIN
 - Casio PV-1000: BAS, PBF
 - Commodore 16/plus4: LNX
 - Commodore VIC-20: A0, B0, 20, 40, 60, BIN, CRT, 70, 80, P00
 - Dragon 32/64: PAK, DXT, DGN, S19
 - Matra/Hachete Alice: WAV
 - NEC PC-9801: FDD
 - Nintendo - Super Nintendo: BIN
 - RCA Studio II: BIN, ASM
 - Robotron KC series: BAS
 - ScummVM : GME, SM0, 000, LA0, 1C (removed DSK)
 - Sony PocketStation: GME
 - Sega SG-1000: BIN
 - Sharp MZ-700: MZF
 - Sharp MZ-1500: MZF
 - Sharp X1: D88, 2D (removed GBA)
 - Sinclair ZX Spectrum: SCL, BLK, CSW, WAV
 - Vtech Creativision: BIN
 - Vtech Laser 200: VZ, DSK
 - Tandy/Radio Shack TRS-80 Color Computer: CCC
 - Thomson MO5: BIN
 - Tomy Pyuta: BIN

- ECC Theme
v1.1.0.5
- Improved ECC Core detecion, so that ECC software can be found.

- ECC Bugreport
v2.2.0.5
- Improved ECC Core detecion, so that ECC software can be found.
- Updated the MD5 hash file to 0.9.7 WIP20

- 3rd party tool updates
 - AutoIt v3.2.12.0
 - Notepad++ v4.9.1
 - QuickSFV v2.36

- ECC Installer
v1.3.0.6 (ECC v0.9.7.R1):
- Compiled with NSIS v2.37.
- Added 2 more options available to install.
- Fixed 2 bugs in the installer that did not work in the 0.9.7 installer:
 - Creation of the desktop icon.
 - Restructure ECC Images.
- Default installfolder now point to the users profile.

![](https://raw.githubusercontent.com/wiki/PhoenixInteractiveNL/emuControlCenter/images/ecc_header_small.png)

Version 0.9.7 (2008.05.01) A NEW LOOK!

- Added platforms
 - Apple 1
 - Apple 2
 - Apple Lisa
 - Applied Technologies Microbee
 - NEC Supergrafx / PC Engine 2
 - Nintendo Famicom
 - Nintendo Wii
 - Nokia N-Gage
 - Scumm Virtual Machine
 - Sony Playstation 3
 - Tiger Electronics Game.com
 - Tiger Electronics Gizmondo
 - Tomy Pyuta

- Added extensions
 - Acorn Electron: SSD
 - Atari Jaguar: J64
 - Atari ST: STX, STC
 - Commodore 16: CRT, BIN, D64
 - Commodore 64: CRT, D81, G41, G64, LNX, P00, Z64
 - Commodore Plus/4: BIN, D64
 - EACA/TS Colour Genie: CGD, CGT, TD0
 - NEC PC-6001: BIN
 - NEC PC-9801: FDI
 - Nintendo Gameboy: SGB
 - Vectrex: GAM
- Changed file extension for:
 - NEC-PC6001: PC6 to CP6

- Small Fixes
 - Fixed an issue where thirdparty tool fsum.exe would not work with strange
   characters/space in the pathname
 - Fixed an window focus issue when first run of an emulator when not configured
   correctly.
 - Fixed the PC Engine CD factsheet to show correctly.
 - Fixed the system name of the NeoGeo CD factsheet.

- Update DATFILES
 - MAME v0.122 to v0.123

WIP 20 (2008.04.30)
- fixed bug, if you direct edit options in detail area (like running, bugs aso)
- Added new ecc-theme/miss-kit-I for girls and boys who dont like blue :-)
- fixed ecc dat file import bug, if usk is string!
- added missing translation for graphics
- added "region" (e.g. hispanic, world, japan, ...) meta dropdown, export, import, romdb

WIP 19 (2008.04.29)
- added "arcade board" to media type dropdown
- added imageslots cover_inlay_01-03 and ingame_play_boss
- fixed startup sound bug

WIP 18 (2008.04.28)
- added more fields to romdb export!
- Added new imageCenter slot Cover Spine.
- added updated translation (We are searching for more translations!)
-- dutch (by phoenix)
-- french (by cyrille)
-- german (by ecc, blackerking)
-- english (by ecc)

WIP 17 (2008.04.20)
- new imagepack tools in platform context menu
-- "Remove images for roms that i dont have in database",
--- Remove all images of roms, that are not available in your romlist.
If you want have a smaller imagepack without all the never used images!
-- "Create thumbnails for faster access",
--- Rebuild all thumbails for the imagpack. Use this, if you have downloaded an
imagepack from web without thumbs. If using thumbs, emuControlCenter detail
view is much faster than without thumbs!
-- "Remove thumbnails for imagepack exchange",
--- Use this option, if you want to exchange your imagepack with other people.
This will remove all thumbnails and make the imagepack much smaller
-- "Remove empty folder",
---- Cleanup function from imagepacks. Remove empty folders.
- Added 500 + 1000 as selection for detail view
-- use this, if you have created thumbs with low quality only! :-)
- added bookmarks and history entries to userdata xml-export!
- added updated translation
-- dutch (by phoenix)

WIP 16 (2008.04.13)
- added new dropdown for Medium x of n [1/x] button
-- select only first rom media
-- select first rom media and unknown
- fixed a problem in category dropdown (doubble entries)
- search dropdown
-- removed file extension
-- optimized filename now also can find file extension
- added new Flyer slot + ingame_play_04/05 for imageCenter

WIP 15 (2008.04.08)
- Medium x of n
-- Detail and listview now append "Rom name (1/3)", if this metadata is given.
-- New mode "Show only disk 1" button hiding all roms not disk 1 :-)
-- Listviews are now ordered by name + medium (x/n)
- fixed emulator commandline parameter bug (default spaces before/after %ROM%)
-- now it is also possible to add parameters like -a:%ROM% (e.g. to start WinX68kHighSpeed)

WIP 14 (2008.03.29)
- updated conf/mame/driver.ini
- max per page for list detail new 500 / list 100000 :-)
- fixed problem, if you direct edit the roms options in detail area
- hotfixed drag-n-drop of metadata in search mode problem (drop on last record not possible)
- limit stored window states to maximized

WIP 13 (2008.03.23)
- gui optimizations
-- shortcut "Strg F" now toggles fullscreen mode (Close in Fullscreen ALT+F4)
-- gui position is now restored at starup
-- gui size is now restored at startup
-- window state (maximized/fullscreen) is now restored on startup
-- show/hide navigation will restore the selected size on toggle
-- main window is now show if all gui settings are done! No flickering at startup!
- fixed problem with the import from online meta-database romDB
- New shortcut keys (see shortcuts.txt)
-- DEL - remove rom from database
-- STRG+DEL - remove rom metadata
-- ALT+DEL - remove rom images
-- SHIFT+DEL - remove rom from harddisk
- fixed little bug if removing images
- full settings are now stored, if you hit the save position button im imageCenter
- romdb datfile now also possible for "# all found"

WIP 12 (2008.03.20)
- implemented show last selected game on startup
- implemented select last selected page on startup
- gui settings
-- current state of the navigation/search/detail area is now saved by default.
-- "Save View-Mode settings" store the current selected mode like "roms with metadata"

WIP 11 (2008.03.15)
- Added option to backup your userdata (reviews, ratings, personal) to an xml file
-- see topmenu -> options -> backup userdata
-- xml file will be copied to your ecc-user/#_GLOBAL folder
-- import has to be done, export needs optimization :-)!
- implemented autohide for options not needed in emulator configuration
- fixed problem with cached romdb datfile, if #_GLOBAL folder dont exists
- now also emulators containing $ like no$gba are started without problems.
- fixed problem in fsum large file parsing
- fixed window focus bug
- fixed Blank holes when delete images and then refresh
- now OS_TYPE is lowercase

WIP 10 (2008.03.02)
- fixed an problem with the new ecc-core-os folder on larger file parsing
- implemented direct unpack of zip files for emus without zip support
- fixed problem for games containing invalid characters like % and &
- fixed problem with F12 - hide info area
- fixed Incorrect parsing string
- fixed bug if rom add for arcade platforms is canceled
- removed some php warnings
- add some fixes for new php-gtk2 2.0

WIP 09 (2008.02.23)
- added hidden scrolling for detail area (for resoultion 1024x768) (Jakuchu fix)

WIP 08 (2008.02.19)
- added helper for eccLive! 3.x

WIP 07 (2008.02.11)
- add label to search reset button
- make file, dir and zip paths elipsed (...) at start
- added theme background to media edit popup
- theme preview now also changes the background of the config-popup for preview
- changed host info ini

WIP 06 (2008.02.08)
- Implemented new meta-fields + eccDat export / romDb export
-- Programmer
-- Musican
-- Graphics
-- Media [CD|Diskette|...|Tape] [2] OF [5]
- optimizations for linux port
- fixed some export issues
- eccThemes
-- fixed "none" theme select
-- added themeColor.ini (in eccThemes folder)
-- added theme images for dialogs
- added ecc_host_info.ini for os detection and autoupdate!

WIP 05 (2007.01.29)
- ecc-theme images are now png
- removed os check for popup windows
- datfile-import
-- romcenter dat
--- fixed problem with no-intro datfiles
--- added some more regex to datfile stripper
-- CM dat
--- fixed some warnings if datfile is parsed
--- added flat logfile containing eccMergedCrc32 + infos from CM-Datfile (set activate loggin on!)
---- used for image-imports from various mame, cps1-3 aso image-repositories
- optimized logger to write logfiles of your actions (imports, exports aso) to the ecc-logs folder
- removed some php warnings
- added php-gtk2 repository fix (gtk renamed to GObject)

WIP 04 (2008.01.24)
- if eccScript is selected in emulator config, path is auto-escaped!
- Rescan all rom folders
-- fixed wrong output, if "all found" was selected
-- fixed wrong output, if stored path isnt valid!
-- implemented auto-optimize for stored paths.
- Added some new icons
- implemented ecc-theme selector in configuration
-- now you can add your own themes into ecc-themes folder

WIP 03 (2008.01.20)
- optimized reparsing
- escape path for fsum call
- added new hide option for search-panel
- added background images for better look n feel :-)
- optimized gui
- romDB datfile is now cached into ecc-user/#_GLOBAL folder

WIP 02 (2008.01.19)
- Added some missing languages strings
- Icons for context menus + reorganization

WIP 01 (2008.01.14)
- New tabs for meta-edit popup
-- Rating / Review
-- Personal for notes + hiscores
- Top menu "View"
-- Added "last played" (same as "history", ordered by launchtime!)
-- Added "most played"
-- Added "never played"
-- Added "bookmarked" (same as "bookmarks")
- Added new buttons to direct access these new view options including context menu
-- Added here also "personal with review"
- added new icons to metaInfo (right)
-- [N] Notes
-- [R] Review
-- [B] Bookmark
- Bookmarks and history are now handled line the normal list views!
- file dialog for "add roms" now starts in ecc user folder!
- Added new option "Silent ROM parsing (no popup requests)" in general config
- store path_selected_last in history ini for dialogs...
- disable double-click on platform-navigation for "all found"
- added new categories... dont find an good name now!
-- "-  Moving blocks (Tetris/Klax aso)"
-- "-  Finger speed (GuitarHero/DanceTrainer aso)"
- many more :-)

- ECC Startup
v2.1.5.0
- Added new images and a brand new splashscreen for the 0.9.7 release.
- Fixed an issue where the startup sound did not play or preview didn't work.

- 3rd party tool updates
 - Notepad++ v4.8.5

- Core components
 - GTK Core update from 2.12.1.0 to v2.12.8.0
 - PHP-GTK v2 beta to v2 full
 - PHP v5.2.5 to v5.2.5 NON THREAD SAFE

- ECC Installer
v1.3.0.3 (ECC v0.9.7):
- Compiled with NSIS v2.36.
- Removed DB overwrite warning (because 0.9.7 is compatible with 0.9.6)
- Updated the virtual box image.


Version 0.9.6.R3 (2008.03.10)

- Added platforms
 - Amstrad GX4000
 - Texas Instruments TI-99/4A

- Small updates
 - Improved ECC OS detect
   - Slightly improved the way to detect the OS system.
   - Removed the php_mbstring DLL, it's not used anymore, now
     using a internal PHP function to lowercase the OS string.
 - Fixed CRC32 calculation error on large files.
 - Fixed script creation with notepad++.

- Small Platform updates
 - Robotron KC
   - Renamed 'Robotron KC' to 'Robotron KC series'.
 - Atari 8-bit
   - Renamed 'Atari 8-bit' to 'Atari 8-bit series'.
 - Amstrad/Schneider PCW/Joyce
   - Renamed 'Amstrad/Schneider PCW/Joyce' to 'Amstrad PCW/Schneider Joyce'.
 - Capcom Playsystem 3
   - Replaced the logo by a darker one (like CPS1 & CPS2).
 - Bandai Wonderswan Color
   - Removed the 'color' logo from the screen.
 - Amstrad CPC
   - Replaced the logo and re-made the teaser.
 - Amstrad PCW/Schneider Joyce
   - Replaced the Amstrad logo and re-made the teaser.

- ECC Startup
v2.1.3.3
- Fixed an issue where ECC did not return to the original
 'maximized' size when ECC was minimized.

- ECC Live!
v3.0.0.4
- Fixed crash when updating ECC Live! to a more recent version.
v3.0.0.5
- Fixed an infinite loop when a update is not for the operating OS
 or installtype.
v3.0.0.6
- Adjusted to work with ZIP files instead of RAR files, this because
 there is no suitable RAR extension for PHP-GTK2 in feature update.

- ECC Live! updater
v1.1.0.6
- Adjusted to work with ZIP files instead of RAR files, this because
 there is no suitable RAR extension for PHP-GTK2 in feature update.

- ECC Bugreport
v2.2.0.3
- Adjusted the way the bugreports are stored on the server.
v2.2.0.4
- Fixed a bug that would not remove the temporally log file.

- 3rd party tool updates
 - Notepad++ v4.8.2

![](https://raw.githubusercontent.com/wiki/PhoenixInteractiveNL/emuControlCenter/images/img_misc_multios.jpg)

Version 0.9.6.R2 (2008.02.23) MULTI-OS READY

- Small updates
 - Fixed and issue where multiple menus popup, when clicked on
   the menu, this is caused if people have costum windows theming
   installed and active on their system
 - Fixed an issue loading in Win32 systems where ECC could not
   load a glade GUI file.

- ECC Startup
v2.1.2.7:
- ECC Startup did not run the desired PHP script for OS
 detection due to spaces in paths, this has been corrected
 by quoting the complete path.
- Added full paths to the OS & Bugreport PHP files.
v2.1.3.0
- Adjusted to work with multiple cores (Win32, WinNT)
v2.1.3.1
- Fixed update checking, because it did not function on a
 first start of ecc, so we can now also remove the 'update now'
 function from the installer.
v2.1.3.2
- Fixed an issue where ECC did not start when hailing from
 a offside-location (like from the installer) this is fixed by
 calling full paths.
- The file sOsDetect.php, has been improved to find the correct path.
- Removed the Kickstart line from the update INI, it is not used
 anymore.

- ECC Live!
v3.0.0.0
- Almost a Whole rewrite of the ECC Live! engine, not using table's anymore.
 It's now more advanced even more things implented, user interface has been
 simplified, no more buttons and stuff, also better messages implented.
v3.0.0.1
- Fixed an issue where ECC Live! did not start after an update
 with the update check.
- The file 'ecc-tools\msflxgrd.ocx' will be removed because it
 is not used anymore.
v3.0.0.2
- Fixed an running problem 'please run from the ecc-tools folder'
 on Win32 systems and maybe other operation systems.

- ECC Live! updater
v1.1.0.0
- Adjusted ECC Live! Updator to use the PHP RAR extension for extraction.
- Removing the thirdparty tool RAR.EXE, it is not used anymore.
v1.1.0.1
- Fixed an issue when no ECC software could be found.
- Included the language files for ECC Live! updater.
v1.1.0.2
- Fixed an issue where the update INI was not deleted.
- Removed an unused image in the file, now ECC Live! update
 is more than 50 KB smaller.
v1.1.0.3
- Fixed an issue where ECC Live! was in use so that it
 could not be updated.
v1.1.0.4
- Fixed an issue where ECC Live! was in use so that it
 could not be updated #2, this time using the 'timer'
 handling again.
v1.1.0.5
- Fixed an running problem 'please run from the ecc-tools folder'
 on Win32 systems and maybe other operation systems.

- ECC Bug report
v2.2.0.2
- Fixed an running problem 'please run from the ecc-tools folder'
 on Win32 systems and maybe other operation systems.

- ECC Theme
v1.1.0.4
- Adjusted to look in the right ECC core folder for the themes.

- ECC Script
v1.1.0.2
- Adjusted to look in the right ECC core folder for the
 thirdparty tool Autoit3.exe to run scripts.

- ECC GTK Core
 - Updated GTK from 2.10.11 to v2.12.8
 - Added a brand new theme engine called 'clearlooks' for a new
   default ecc theme.

- ECC Installer
v1.3.0.2 (ECC v0.9.6.R2):
- Compiled with NSIS v2.35.
- Added DB overwrite warning (because the 0.9.6 is not compatible with 0.9.5)


Version 0.9.6.R1 (2008.02.05)

- Added platforms
 - Amstrad/Schneider PCW/Joyce
 - Epoch/Yeno Super Cassette Vision
 - NEC PC-9801
 - Sharp MZ-1500
 - Sharp MZ-2500
 - Sharp X1

- Small updates
 - Updated the language files for the 0.9.6 release.
 - Added the right MD5 hash file for the 0.9.6 release.
 - Enabled the PHP extension 'php_mbsting' to be loaded.
 - Updated DAT files
   - CPS-1: v2006.07.24 to v2007.09.22
   - CPS-2: v2007.06.11 to v2007.11.19
   - MAME: v0.117 to v0.122
   - Neo-Geo: v2007.07.10 to v2008.01.01
   - Model2: v2007.05.31 to v2008.01.03

- ECC Startup
v2.1.2.6:
- Fixed error message 'could not find ecc-core\php-win.exe'
 Some users experienced that 'ecc-core\php-win.exe' could not
 be found, this can happen because of foreign language sets,
 now using full paths instead of relative paths.
v2.1.2.5:
- Adjusted to look in the ECC rootfolder for ECC error.log.
v2.1.2.4:
- ECC Startup is now hailing PHP to determine on what OS it
 runs, the output syntax from PHP is different then Autoit,
 so this is only to adjust things to the feature, because we
 want to let ECC run on linux/apple/mac systems later-on ;)

- ECC Bugreport
v2.2.0.1
- Adjusted to look in the ECC rootfolder for ECC error.log.
v2.2.0.0:
- Now uses the PHP extension 'php_zip.dll' to pack the htm file
 - Now the thirdparty tool RAR.EXE isn't needed for this anymore.
- Improved the way the bugreport is stored on our server, to give us a much
 better overview, so we can fix bugs faster!
- Not using the TEMP folder anymore, the temp file is created in the ecc-tools folder.
 - This because on a NON writable disc, the PHP error.log can't be created!

- Image updates
 - Bally Astrocade
   - Fixed the dropshadow (5 pix)
   - Move the 'Bally' logo a bit
 - Matra Alice
   - Added dropshadow
   - Removed lost pixels on top of the console
 - Mattel Aquarius
   - Added dropshadow
 - Capcom Play System 3
   - Added dropshadow
 - Tatung Einstein
   - Added dropshadow
 - Mattel Intellivision
   - Added dropshadow
 - Interton VC-4000
   - Added dropshadow
 - Atari Lynx
   - Updated the console picture
 - Panasonic 3DO
   - Made console picture smaller to fit better
 - Adobe Flash
   - Removed some lost pixels on top of the logo
 - Atari Jaguar
   - Removed the '64-bit' line
   - Centered the console picture
 - Nintendo DS
   - Moved the logo on left-top
   - Moved the console picture
 - NEC PC-FX
   - Fixed the dropshadow (5 pix)
 - Philips VG-5000
   - Fixed the dropshadow (5 pix)
 - Nintendo Super Nintendo
   - Added Nintendo logo
   - Moved console picture to middle
   - Moved 'Super Nintendo' logo to right-under
 - Bandai Wonderswan
   - Removed some lost pixels on top of the console
 - Sharp X68000
   - Updated the console picture
   - Updated the nav picture
 - Namco System 11
   - Updated the nav picture (red text)
 - Namco System 22
   - Updated the nav picture (red text)
 - Sega System 16
   - Updated the nav picture (purple text)
 - Sega System 18
   - Updated the nav picture (purple text)
 - Sony ZiNc
   - Updated the nav picture (blue text)
 - Emerson Arcadia
   - Added Emerson logo
   - Removed little Emerson logo from the console logo
   - Centered the console
 - Sharp MZ-700
   - Added dropshadow
 - Atari ST
   - Made the white screen black
   - Centered the console

- Core components
 - ECC Core degrade to v2.10.11a
   Using the old core again due to troubleshooting on some systems, we now have a
   'testchamber' in our development section to make feature (core)updates more
   stable before releasing, for now we are using the the old 2.10.11 engine
   from march 28 2007.

   Note:
   This also brings back the drivescanning problem of usb drives ect. (for now)

 - Due to feature multiplatform support, we have to alter some
   settings, one of these is the error.log output, this will
   be in the ECC root folder again.

- 3rd party tool updates
 - Notepad++ v4.7.2

- ECC Installer
v1.3.0.1 (ECC v0.9.6.R1):
- Compiled with NSIS v2.34.
- Fixed the checkboxes on the 'extra feature page' to handle ccorrect.
- Altered some of the text being displayed on the 'extra feature page'.
- Added the writing of the instal INI.