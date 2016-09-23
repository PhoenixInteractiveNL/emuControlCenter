## Changelogs 2007
***
![](https://raw.githubusercontent.com/wiki/PhoenixInteractiveNL/emuControlCenter/images/img_misc_100plus_platform.png)

Version 0.9.6 (2007.12.14) 100+ PLATFORM SUPPORT

- Added platforms
 - Casio PV-1000
 - Casio PV-2000
 - Cybiko Classic
 - DOSbox - DOS emulator
 - Dragon Systems - Dragon 32/64
 - Interton VC-4000
 - NEC PC-FX

- Added Arcade platforms
 - Capcom Play system 1
 - Capcom Play system 2
 - Capcom Play system 3
 - MAME Arcade
 - MCA Laserdic Arcade
 - Namco System 11
 - Namco System 22
 - SNK NeoGeo
 - SNK NeoGeo CD
 - Sega Model 1
 - Sega Model 2
 - Sega System 16
 - Sega System 18
 - Sony ZiNc (ZN-1/ZN-2)

- Small updates
 - Added MSX1, & MSX2 EMU INI's.
 - replaced zlib1.dll with an uncompressed version.
   - as from ECC 0.9.5.R2 ECC is not compressed with UPX anymore.
     so hopefully after this update the false virus positives should be gone!
 - Theme fix - Valiance
   - This update fixes the ECC crash on 'Valiance' theme.
   - Fix by Cyrille, our Theme Manager, many thanks Cyrille!.

WIP 24 (2007.12.12)
- size of navigation is saved to ecc_history.ini, if "Save settings" is activated!
- fixed white menu problems under windows vista (new gtk version)
- remove romid menu entry
- removed old ecc-datfile suffix ".ecc" - now only ".eccDat" is supported (rename old .ecc files to .eccDat)
- removed backup creation for user configs (.bak files)

WIP 23 (2007.12.10)
- fixed array output for "# All found" popups
- fixed problem with eccUserFolder eccident-platform.html files (e.g. ZINC)
- fixed some little glitches in relative path convertion for emulators and roms.
- "Reparse all ROM folders" added the platforms eccUser Folder as default path for reparsing
-- Start ecc -> platform menu "Open eccUser folder" -> copy roms to roms folder -> platform menu, choose "Reparse" ... done!
- "Optimize roms" now also optimize folders not available for "Reparse all Roms" feature
- added new table fdata_drivemap for future CD/USB drive mapping.
- renamed some tables in database to fit the naming convention.

WIP 22 (2007.12.07)
- fixed "ECC 0.9.6 WIP21 list comparing bug"

WIP 21 (2007.12.06)
- added new entry to platform-navigation "Open eccUser folder (pce)"
- added new html file e.g. "c64_-_Commodore_64.html" to eccUser folders. (use rebuild eccUser folder)
- moved language config to own tab
- added new option "Send bugreport at startup" in Config/Startup
- click on emuControlCenter logo now opens ecc website (before opens about-popup).

WIP 20 (2007.12.05)
- fixed size of large "extension *.??? problem" popup.
- fixed "Emulator conf. disabled in menu when standing on 'all found'"
- fixed array output in "remove roms" dialog (for "All found")
- fixed eccdb datfile import problem (wrong linebreak sequence)

WIP 19 (2007.11.26)
- fixed crc32 "Double trouble" on large files by ecc "fsum" wrapper for these!
- emulator config
-- added platfom name in emuConfig headline for better overview
-- moved known emulators to new tab "Links & Infos"

WIP 18 (2007.11.19)
- Config - option to Save View-Mode settings (only experts)
-- save current list selection (have, donthave, meta aso)
-- save the state of the panels navigation (key F11) and info (key F12)
- changed datfile suffix to .eccDat
- default selection of ecc-system/datfile (arcade), if you choose CtrlMAME-Import
- catched png decompression exception (better/more error handling in ECC)
- fixed ECC crashes when 'illegal' characters are in the searchbox
- eccDAT file with line-breakline-feed (TODO: ecc romdb)
- fixed ECC Script BUG (URGENT)
- Double trouble with PSX isos
-- found bug but and fixed it.... but....
-- DANGER: ecc will now possible freeze on larger files!! Hopefully we find a way for this!

WIP 17 (2007.09.10)
- added new colors to config for imageCenter slots
- fixed imageCenter slot selection bug
- fixed imageCenter hilighting of empty slots (next, prev buttons)
- optimized audit speed for multiplatform roms :-)

WIP 16 (2007.08.15)
- added storage of listview mode (have, donthave aso) in history.ini (+shortcuts)
- added translation for mame driver-dropdown (ecc-system/conf/mame/driver.ini)
- fixed rom-audit bug for arcade platforms... please re-audit!
- fixed stupid bug in imageCenter - now add images works again.
- fixed an escape problem (&) in romInfo/DATA-Tab
- fixed little glitch in metaEdit
- optimized gui
- optimized the default colors
- updated shortcuts

WIP 15 (2007.08.13)
- added preview of "roms you dont have" images
- added size and position save for imageCenter
- added mame driver dropdown
- added mame driver to rom details (MAME (driver.c))
- added parser state for multifile roms (current file parsed)
- added soundpreview (ecc.exe /sndprev "full path to audio file")
- added "naviagation images in list view!" again
- added more i18n translations
- added new shortcuts
- fixed imageCenter "type is not supported" bug
- fixed list switch / results perpage bug
- fixed emulator command preview
- optimized top navigation
- optimized audit state icons
- optimized position of save/close buttons in configuration (from ?x600 resolution)
- optimized mediaEdit labels (now elipsed ...)

WIP 14 (2007.08.05)
- added "relative paths" for assigned emulator, if in ecc-user folder
- added "relative paths" for added rom folders
- added new "shortcut" for folderchooser pointing to the selected platform ecc-user folder
- added "ecc-user/#_GLOBAL/emus/" folder for emulators used by more than one platform
- fixed an bug with mame datfile import
- fixed little glitch in emuConfig

WIP 13 (2007.08.04)
- added Rom Audit (multirom)
-- icons for listview/details to show the state of the roms
-- Popup for current multirom status like valid states, missing files aso.
--- Repair of wrong filenames implemented (for winkawaks... needs the right filename!)
- added "reparse all found" option. Select platform or "all found" and hit ALT+F5
- added "start emulator" button in emulator configuration to make settings in the emu.
- added error popup to file operations, if rename could not done! (file is allready in place)
- added new Shortcuts:
-- ALT+R Rescan rom folder for new roms
-- ALT+I Show rom audit informations (multiroms)
-- ALT+F5 Reparse selected Platform / All found (or STRG+F5)
- added new parameter to ecc_ARCADEPLATORM_system.ini for automatic mame sourcefile matching!
-- ecc will automaticly transfer these games to the right platforms later (planed! :-))
-- @phoenix: please take a look at the datfile/mame.dat for the right sourcefiles
-- [OPTIONS] mame_sourcefile = cps2.c
- added more translation strings...
- fixed an big bug in the auditing multifile roms. !!!
-- if you have added roms for more than one multirom platform, ecc havent changed the datfile
-- How to correct you checksums? (USE WIP 12!!!!!)
--- To correct your checksums, please use the "Import CtrlMAME datfile" function in WIP 12.
--- this works correct and will change your checksums to the right ones.
- fixed "Notepad++ cannot create scripts with relative paths"
- fixed "Glitch in compare layout"
- fixed "File/games ZIP notice"
- fixed missing "view" translation
- fixed both bugs in "ECC config more translated"
- fixed "Choice of Fonts and Colors" in main romlist
- removed context menu "add new roms" (romlist) if no platform is selected
- removed UTF-8 support SWITCHED BACK TEMPORARILY TO CP1250 (!)

WIP 12 (2007.07.23)
- Reparse rom directory
-- Now the folder is optimized before new parsed. So removed roms are deleted from db
-- Menu entry isnt disabled anymore.
- changed: Image center "add image" confirm-popup dont hide the imagecenter complete.

WIP 11 (2007.07.19)
- Romlist  
-- Added grey out for missing roms
-- Now the name is bold in detail-view
- CtrlMame-Dat
-- Moved to import submenu
-- Added i18n entries
-- removed debug output

WIP 10 (2007.07.15)
- CtrlMame-Dat
-- Implemented auto audit for new parsed roms (Neo-Geo / CPS2)
--- CM-Dat parser automaticlly assign the correct mergedEccCrc32 to the romset
--- if you want to add the metadata from the datfile, choose "import CtrlMame Dat"
--- added new folder containing multirom platform CtrlMame (CM) dat files
-- Optimized CM ControlMame-Dat import
-- Added state of merged clone sets (only diff to original) (F: Full set / M = Merge set)
-- crc32 will now also changed for existing meta- and userdata
- Gamelist view
-- Added indicator for Have/Donthave
-- made name resizable
- Other changes
-- fixed wrong folder bug for "parse new roms" in history.ini
-- Disabled "adding roms all found in context menu"
-- Fixed "Create all ecc user-folders - Missing folder in popup"
-- Fixed "Bug in platform list sorting"

WIP 9 (2007.07.14)
- Implemented CM ControlMame-Dat import
-- first simple Audit for multirom-files like cps2, neo-geo!
--- This will automaticly change the crc32 to the right combined eccCrc32 from dat
--- The images are automaticly transfered to the new folder
--- Infos are shown in the info-field
---- ok [unique] == only one matching rom found
---- ok [multi] == many matching roms found, best used!
---- error [unique] == only one matching rom found, but single files are missing
---- error [multi] == many matching roms found, best used! but single files are missing
---- Output of an logfile (array) dump at the end of auditing!

WIP 8 (2007.07.05)
- ListContext
-- Added "Add new platform roms"
-- Added "Remove all platform roms"
-- Added "Remove ROM metadata"
- File-/Folder-Chooser popup
-- Implement multi folder selection for "Add new roms"
-- Implemented folder-shortcuts for all choosers (bottom left folder selection)
--- ecc-user folder
--- all assigned emulator-folders for selected platform
- imageCenter
-- fixed some glitches, if you cancel the file-selector
-- fixed that ecc forgot the path for moved images in file-selector
-- Tried to set a sticky image preview (not working very good now :-) )
- Implemented topmenu entry for "Import online datfiles"
- Added updated frensch translation done by cyrille
- Fixed "Find duplicate ROMS - Strange popups"
- Fixed "Optimize database ROM combined"

WIP 7 (2007.07.01)
- Restructuring Detaillist-Output
- Reordening downsection "info box"
- Fixed "Wrong messagebox" bug

WIP 6 (2007.06.30)
- fixed a little problem with "Start in Emufolder"
- added i18n for eSearch reset button
- Implemented 2 Zip-Handeling Modes
- YOU CANNOT MIX ZIP-PARSER AND OTHER PARSER NOW! DONT TRY IT! :-)
- If no file left after include/exclude, the file isnt parsed in!

WIP 5 (2007.06.30)
- emulator configuration
-- Implemented "Start in Emufolder" for e.g WinKawaks
-- Optimized the option-selection (Hide unvalid settings)
-- Add little hint, if there is no emulator assigned :-)

WIP 4 (2007.06.29)
- i18n translation support
--  complete top-menu
--  complete infoPane area
- Fixed confirm destroy problem

WIP 3 (2007.06.28)
- emuControlCenter is now UTF-8 ready
-- set "php-gtk.codepage = UTF-8" in php.ini
-- Added japanese languages support provided by yoshi
-- Added charset.ini to i18n folder containing the charset of the tranlation
--- used to convert given charset to UTF-8
-- "en", "fr" and "de" tranlations are now UTF-8!!!!
--- Use Notepad++ (ecc-core/thirdparty ) for translations (UTF-8 without BOM!!!!)
- Fixed "No extension" path bug
- Fixed problem, if ecc-user folder is missing!
- Fixed "Some wierd errors" bug

WIP 2 (2007.06.24)
- Implemented "first try" version of (multirom) zip parsing
-- How it works..
--- ecc parses each file in the zip file and creates checksums for each file!
--- All singlefile crc32 are ordered acending, concated by "," and used for an global crc32 checksum.
--- All singlefile crc32 checksums + real size are viewable in the header-tab!
--- build function to "excludeExtensions = txt, inf, diz, url, tag, www, nfo"
- Implemented "hide dialog checkbox" for parsing large-file dialog!
- fileOperations && Configuration changed background-color
- Meged updated french translation by cyrille

WIP 1 (2007.06.22)
- refactured gameLists
-- Now switch between list and detail view without restart (USE F1/F2 or top menu!)
-- Very fast list-mode.... :-) (because iï¿½ve removed fields!) Only for DEVEL! :-)
- Added Datfile-Download from internet
- mediaEdit
-- added tearoffs/detach for category-dropdown! (perfect, if you add many meta-informations)
-- added Fileinfo header
-- changed background color
- imageCenter
-- changed background color
- Fixed "extension problem windows" size problem
- Fixed "Ball & Paddle" bug
- Fixed Glade-Warning on startup
- Fixed crash on files bigger than 64MB ( arghhhhhhh :-) ) - wrong ini settings
-- fixed php.ini - wrong memory_limit = 64M, changed to wrong memory_limit = -1
-- We have to take care of this ini-settings!
-- @phoenix: please change error_log path to error_log= ..\ecc-core-errors.log

- 3rd party tool updates
 - Notepad++ v4.6
 - Autoit v3.2.10.0

- Core components
 - ECC GTK Core update from 2.10.11.0 to v2.12.1.0 (GIMP build)


Version 0.9.5.R3 (2007.11.14)

- Added platforms
 - Acorn Electron
 - NEC PC-Engine CD
 - Robotron KC
 - Vtech Laser 200
 - Tatung Einstein

- Small updates
 - Completed the emulator INI files (information/download)
 - There where 2 'error_reporting' settings found in PHP.INI,
   now there is one left ;)
 - Improved ECC error handling, to let ECC not report WARNING & NOTICE errors.
 - Added 'bugsending' translations (FR/GER) for ECC startup v2.1.2.2

- Platform updates
 - Removed all '(c)' & 'tm' crap in the logo's.
 - Removed some text underneath some logo's.
 - Made some images much nicer by splitting-up the logo.
 - Replaced & polished some images to look much nicer.
 - Updated the PC Engine image (moved logo's)
 - Removed the Sega Dreamcast MDF extension, and added CDI, MDS, NRG, GDI, BIN, ELF

- ECC Startup
v2.1.2.3:
- Removed PHP hash checks (now also feature 'core selection' is possible)
v2.1.2.2:
- Fixed a critical mistake by sending the environment
 data in the bugreport already overwritten by the
 new start of ECC, this means it was not the actual
 environment data when the crash happened, now the
 checking routine has been improved, also ECC Startup
 waits for ECC Bugreport to finish on startup.
- Compiled with ANSI support, so ECC Startup can work
 on WIN 98/ME systems.
v2.1.2.1:
- Tweaked the ECC 'startup time out' a litte bit, because
 ECC has trouble with some (old) USB Flash disks and
 would not start up the first time.
v2.1.2.0:
- Fixed GTK load on a server machine.
 - This is done by setting the ECC php.ini path in the commandline.
- Added desktop data to environment INI.
- Removed the 'Bugreport using' text in ECC Startup.
v2.1.0.4:
- Fixed the 1st time language selecting, this didn't work, when the user folder
 didn't exist. (it worked after the second time when starting ECC)
- Fixed GTK load on a server machine.
 - This is done by setting the ECC php.ini path in the commandline.
- Added desktop data to environment INI.
- Removed the 'Bugreport using' text in ECC Startup.
v2.1.0.3:
- Implented messagebox & option to enable automatic bugreport sending.

- ECC Bugreport
v2.1.0.1:
- Fix for memory 'MB' to 'KB' mistake in the template & envment INI.
v2.1.0.0:
- IP bugsender not noted when manually started the report.
- Only exe/dll/ocx files are scanned in the 'version detail' lists.
- Detect linebreaks in ECC general/history INI's.
- Added 'back to top' in template.
- Added report of ECC startup version.
- Fixed displaying some incorrect fileversion informations.
- The OS now displays a 'long' name.
- QuickSFV intergration for MD5 hash checkup.
- Added transations GER / FRA.
v2.0.0.0:
- Almost totally rebuild from scratch!
 - Whole new HTML output (using template file :D)
- The user can run ECC Bugreport/Diagnostics whenever he/she wants,
 just to view/checkup ECC version/core/environment data.
 (for example placing the bugreport on the forum)
v1.2.0.5:
- Extended the bugreport details/diagnostics with the users environment data.
- Coming soon: ECC Bugreport diagnostics build-in
v1.2.0.0:
- Removed manual interface.
- Automatic bug sending when a ECC error occurs.
- Fully translatable!
- Fixed an running issue on Win98 systems (and maybe other OS'es).

- Core components
 - PHP update from 5.2.4 (30-Aug-2007) to v5.2.5 (08-Nov-2007)

- 3rd party tool updates
 - RAR v3.71
 - Notepad++ v4.5
 - AutoIt v3 v3.2.8.1 help files


Version 0.9.5.R2  (2007.09.30)

- Added platforms
 - Philips VG-5000
 - Sharp MZ-700
 - Acorn BBC
 - Acorn Archimedes
 - Matra/Hachette Alice

- ECC Startup
v2.1.0.2:
- Fixed a bug on creating images, where slow systems (700 Mhz/128 MB) would hang
 with the message 'out of memory / low on system resources'
v2.1.0.1:
- Fixed a bug where ECC would not create the media images when not running from the ECC root folder.

- ECC Live!
v2.2.0.4:
- Fixed an running issue on Win98 systems (and maybe other OS'es).
v2.2.0.3:
- Set unpacker from 'ecc-core\thirdparty\unrar\unrar.exe' to 'ecc-core\thirdparty\rar\rar.exe'

- ECC Live! upd
v1.0.0.5:
- Fixed an running issue on Win98 systems (and maybe other OS'es).
v1.0.0.4:
- Set unpacker from 'ecc-core\thirdparty\unrar\unrar.exe' to 'ecc-core\thirdparty\rar\rar.exe'

- ECC Theme select
v1.1.0.3:
- Fixed an running issue on Win98 systems (and maybe other OS'es).

- 3rd party tool updates
 - RAR v3.70
 - AutoIt v3 v3.2.8.1
 - Notepad++ v4.3


Version 0.9.5.R1 (2007.09.02)

- Added platforms  
 - Commodore plus/4
 - Commodore 16
 - Mattel Aquarius

- Platform updates  
 - GCE Vectrex
   - Added the MB (Milton Bradly) logo in the teaser, and adjusted the name to 'GCE/MB Vectrex'
 - Coleco Vision
   - Updated the teaser with a new fresh platform image.
   - Added the Coleco logo in the teaser.
   - Changed the name to 'Coleco ColecoVision'
 - Colour Genie
   - Updated the teaser with a new fresh platform image.
   - Added the Colour Genie logo in the teaser.
 - Added the 'Mattel' logo in the Intellivision teaser

- Platform name updates
 - Renamed 'Colour Genie' to 'EACA/TS Colour Genie'
 - Renamed 'Fairchild Channel F' to 'Fairchild Channel-F'
 - Renamed 'Game Park 32' to 'GamePark GP32'
 - Renamed 'MSX' to 'MSX Home Computer'
 - Renamed 'MSX 2' to 'MSX 2 Home Computer'
 - Renamed 'Nintendo Gameboy' to 'Nintendo GameBoy'
 - Renamed 'Nintendo Gameboy Advance' to 'Nintendo GameBoy Advance'
 - Renamed 'Nintendo Gameboy Color' to 'Nintendo GameBoy Color'
 - Renamed 'Sega Gamegear' to 'Sega Game Gear'
 - Renamed 'Sega Mega Drive/Genesis' to 'Sega MegaDrive/Genesis'
 - Renamed 'Sega Megadrive 32X' to 'Sega MegaDrive 32X'
 - Renamed 'Sony Playstation 1' to 'Sony PlayStation 1'
 - Renamed 'Sony Playstation 2' to 'Sony PlayStation 2'
 - Renamed 'Sony Playstation Portable' to 'Sony PlayStation Portable'
 - Renamed 'Sony Pocketstation' to 'Sony PocketStation'
 - SNK NeoGeo CD is now in arcade section

- Small updates
 - Updated the ECC banner to look much more professional
 - Fixes the horizontal rating images, now going from left ro right!
   - Also added transparency to the images!

- ECC Startup
v2.1.0.0:
- ECC Startup will generate 'media' images when necessary!
 - This saves some MB's on installation, and also 80 KB for each platform update!
 - The images look much nicer to!
 - Included: thirparty tool 'convert' from imagemagick!
v2.0.2.0:
- ECC Startup can now be translated, files can be found in 'ecc-system\translations'
v2.0.1.3:
- Fixed 'already started' bug for Windows Vista
 - Now gives the user a one-time message when this happens
- Removing unused files and folders
 - Folder "ecc-core\share\icons\default\16x16"
 - Folder "ecc-core\share\icons\default"
 - Folder "ecc-core\share\icons"
   - This fixes the temporally icon for GTK and uses the default again!
 - Folder "ecc-system\item"
 - Folder "ecc-system\items"
 - File "ecc-system\gui2\ecc_media_placeholder_camya.png
 - File "ecc-system\images\ecc_ph_media_camya.png
 - File "ecc-system\images\eccsys\internal\ecc_header.png
 - File "ecc-system\images\eccsys\rating\ecc_rating_6_v.png
 - File "ecc-system\manager\_OLD_cGui.php"
v2.0.1.2:
- Added a 'LOADING ECC...' label on startup
- Added a loadingbar in upper-right corner, sweet!
v2.0.1.1:
- Fixed the splashscreen unload before mainwindow pop-up!
- Added a splashscreen when ECC is restarting!
v2.0.1.0:
- Added a language selector on first startup!
- Added command '/sndprev' to preview the selected startup sound
 - eventually there will be a 'sound preview' button in ECC config!
v2.0.0.9:
- Has a 'smart splashscreen timeout calculator' built in ;)
 - On some systems ECC could not load within 20 seconds!
 - Now validates the system and sets a timeout value!
- Added BIN extension to Sega Megadrive 32X
v2.0.0.8:
- Increases the splashscreen timeout to 20 seconds
 - On some systems ECC could not load within 15 seconds

- ECC Live!
v2.2.0.2:
- ECC Live! is now also capable to display a message to get the users attention.
v2.2.0.1:
- ECC Live! is now also capable to remove (unused) folders in the ECC structure.

- Core components
 - PHP update from 5.2.3 (31-May-2007) to v5.2.4 (30-Aug-2007)

- 3rd party tool updates
 - AutoIt v3 v3.2.6.0
 - Notepad++ v4.2.2


Version 0.9.5b (2007.06.24)

FYEO 19 (2007.06.17)
- Dialogs
-- Added focus to OK-Button
-- added an checkbox to hide the confirmpopups, if needed.
-- added shortcuts RETURN & Escape for Dialogs.

- Added platforms  
 - Adobe Flash
 - Atari-ST
 - Fujitsu FM Towns
 - Microsoft X-Box
 - Sony Playstation 2
 - Sony Playstation Portable

- Added extensions to platforms (also for TOSEC renames)
 - PC Engine: HES, BIN, ISO
 - NeoGeo Pocket: BIN
 - NeoGeo Pocket Color: BIN
 - GCE Vectrex: BIN
 - Watara supervision: BIN
 - Sega SG-3000: BIN
 - Coleco Vision: BIN
 - Gamepark 32: SMC

- Platform images updates
 - Atari 2600
   - The teaser is replaced by a much better looking image
   - Also added correct (new) Atari 2600 MEDIA images
 - Atari 8-bit
   -Adding some text in the teaser showing the user what Atari 8-bit systems are!
 - Cellphone Java
   - Improved the images for the 'Cellphone Java' platform, to look much nicer!
 - Adding platform shadows in consoles
   -Fujitsu FM-7
   - Gamepark GP32

- Small updates
 - Bringing the Fujitsu FM-7 back in the navigation list.
    - It wasn't included in the navigation for a while, now it's back!

- ECC Startup
v2.0.0.7:
- No more hash check on PHP.INI
- Changed PHP.INI settings:
 - Changed error log location to 'ecc-core\error.log'
 - Changed 'upload_max_filesize' from 2M to 2048M
 - Changed 'post_max_size' from 8M to 2048M
 - Changed 'memory_limit' from 64M to -1 (unlimited)
 - Changed 'log_errors_max_len' from 1024 to 4096
 - Changed 'output_buffering' from 4096 to 16384
 - Changed 'max_execution_time' from 30 to -1 (unlimited)


Version 0.9.5 (2007.06.16)

FYEO 18 (2007.06.16)
- changed the mechanism to get the icon
- create a valid JAD File for transfering a game to a cellphone
- fixed an encoding problem in the infopane (causing ecc crash)

FYEO 17 (2007.06.16)
- fixed a little bug in datfile import
- fixed some glitches in infoPane
- added new i18n strings
- added new popups for confirmation and infos. No more focus problem!

FYEO 16 (2007.06.14)
- added game icon output to right pane
- restructured the DATA tab
- added new HEADER tab for parsed header informations

FYEO 15 (2007.06.11)
- implements manifest parser for java cellphone games (eccident: celljar)
-- Automaticlly gather metadata and icon images form jar game (+ copy to ecc-user folder)
-- validate the file. Corrupt files arent inserted!
- implemented eccScript comment preview, if an eccScript is available!
-- Shows max first 15 lines of comment. First Textline is hilighted!

FYEO 14 (2007.06.07)
- implemented autodetection of eccScripts, if an emulator is assigned.
- disabled creating of eccScript, if no valid emulator is assigned!
- fixed problem to migrate to php 5.2.x

FYEO 13 (2007.06.03)
- tried again to fix the paring bug with big files...
-- added new process output
-- now files greater than 96 MB are slower processed!

FYEO 12 (2007.06.03)
- fixed "ECC Crash on 'Empty datfile database backup'"
- fixed "Incorrect platform count!"
- added big file handeling ('SLOW_CRC32_PARSING_FROM', 134217728) (128MB)
-- ecc asks for confirmation, if an biger file than SLOW_CRC32_PARSING_FROM is found and warns!
-- added mp3 filter for sound selection

FYEO 11 (2007.06.02)
- disable commandline-parameters, if eccScript is selected.
- fixed a problem with saving the startup sound to the ini.
- fixed the problem with parsing files bigger than 500MB (ps1) ecc crashes
-- php runs in am memory allocation problem with file_get_contents
-- ecc freezes very long if parsing these big files No way now to prevent this!

FYEO 10 (2007.06.01)
- Implemented new VIEW-MODES "HAVE" + "DONT HAVE"
- Optimized queries for building and updating navigation.
- Cache navigation images
- Implemented image caching for listview images
- eccScript
- added indicator, if there is an eccscript available
- alert error, if there is no valid emulator assigned
- fixed some little glitches
- added real language-strings to lang-dropdown in configuration

FYEO 09 (2007.05.28)
- added a autoupdating preview of the emulator command! COOOL
- added placeholder %ROM% to "commandline-parameter" in emu-config
-- "--fullscreen=1 %ROM% --sync=1 == (emu.exe --fullscreen=1 romfile.rom --sync=1)"
- fixed problem with param 8.3 causing a ecc-crash

FYEO 08 (2007.05.27)
- added startup configuration

FYEO 07 (2007.05.27)
- added autoupdate for categories dropdown!
- optimize speed of import datfiles / parse roms to database

FYEO 06 (2007.04.22)
- eccScript
-- added delete-button
-- escape path for script creation (button edit/create)
-- changed extension to .eccscript
-- added active/inactive state for eccscript
- added the 05+ features

FYEO 05 (2007.05.20)
- fixed eccScript problem with finding eccScript.exe
- implemented button to edit eccScript to configuration
-- for ecc-core/tools-thirdParty/notepad++/notepad++.exe
- added /fastload for config-changes
- changed order in emu-config GLOBAL|ALT1|ALT2|extension1|....|extensionN

FYEO 04 (2007.05.20)
- implemented possibility for platforms, to parse only roms with available metadata entry
- !!! THIS IS ONLY DEVEL !!! - This could be added to ecc_eccident_user.ini later
-- used for multifile platforms like neogeo
-- Create a datfile with the first (start) file of a game as entry in the datfile
-- Import this datfile to ecc metadatabase
--- ecc searches, if this entry is in db and parse the rom, otherwise not!
--- you have to set the option "connectedMetaOnly = 1" in the ecc_eccident_system.ini
< BR > FYEO 03 (2007.05.18)
- Added possibility to start eccScript direct, if available
- updated frensh translation by cyrille

FYEO 02 (2007.05.13)
- imageCenter: implemented storage for imagePopup options and imageslot expander (bottom)
- fixed a glitch in the compare-popup
- Full restructured right detail-pane for better overview
- added more translation files for mainGui and Metainformations pane

FYEO 01 (2007.05.11)
- Added and fixed some i18n placeholders in configPopup
- added /fastload to ecc.exe calls

- Added platforms
-- Entex Adventure Vision
-- Sony Pocketstation
-- Nintendo Pokémon mini
-- Sharp MZ-2000
-- Bally Astrocade
-- RCA Studio II
-- Cellphone Java games

- Some small fixes
-- Nintendo Virtual Boy now in handheld category!
-- Emu name fix for 'Nintendo Pokemon mini'
-- Emu name fix for 'Sony Pocketstation'
-- Removed all 'romrunner' strings in platform user INI
-- Updated images
--- TEASER & NAV --> Atari 2600 (the JR console)
--- NAV --> Atari 5200 (made transparant)
--- NAV --> Nec Pc Engine (fixed oversize)

- ECC Documentation
2007.06.17:
- Much better overview
- New section: Scriptfunctions
- Added complete off-line mirror of Autoit3
- Added known scripts for ECC

- ECC Startup
v2.0.0.6:
- Adjusted splashscreen time-out to 15 seconds
v2.0.0.5:
- Now works with new ECC Live! v2.2.0.0
- Fixed bug when 'startup check' notice shows message everytime!, until the user pressed 'yes'
v2.0.0.4:
- Fixed bug when ECC did not startup when having spaces in the pathname!
- Added timeout on ECC window catching! when ECC could/did not startup!
- No more splashscreen hanging anymore!!, timeout is set on 5 sec!
- Added extra window on 'error' to notice user to use Bugreport or forum.
v2.0.0.3:
- Put 'first startup' message to AFTER the checkup message.
- Added warning if user is no admin or not in admin mode
 - Because a user in admin mode has 'read-only' priveleges.
- Now works with php 5.2.3.3
v2.0.0.2:
- Fixed some issues with the sound playing
- Fixed that sound plays again on ECC 'fastload'
- Fixed soundplay from being cut-off (because of unload ecc.exe)
- Sound now plays after splashcreen!
- Fixed issue if splashscreen hangs
- When title screen hangs it should be closed when starting ECC again
 - This should prevent unnessesary multiple instances of ecc.exe!
- ECC teaser image has been locked! (no change allowed)
v2.0.0.1:
- New splash screen & teaser image!
- All ECC 'yellow' icons have been updated and look much better!
- Added new startup sound
- Added ECC teaser image validation
- Reduced the splashscreen fade-in from 900 ms to 600 ms
v2.0.0.0:
- Total rewrite of the code (from 60 KB to 11 KB!!)
- I did some 'smart 'n' better' coding ;)
- Removed over 40 messageboxes and other stuff.
- No more usage of 'ecc-system\conf\ecc_startup.ini' (all variables are 'baked-in')
- Removed unnessesary checking of files and values.
 - This will also speedup the startup a little bit!
- Implented better ECC detection (not checking for 'php.exe' process anymore)
 - This will let ECC function on a server machine!
- When ECC crashed, it will startup normally again!
 - This is without using the (now removed) '/unload' option first.
- Changed the icon to the 'yellow' icon.
- New MD5 hash for PHP ini (removed some lines not needed by ECC)
- I have to implent this feature in ECC Live! itself first
- Tweaked the ECC title a little bit now: version...build...date....update
- Updated the 'default' general INI with the new strings
- When general.ini not exist in 'ecc-user-configs' it will be copied from 'ecc-system\conf'
- Removed commandline parameters
 - Command '/config' removed (can now configured in ECC itself)
 - Command '/about' removed (now in ecc-docs file)
 - Command '/regreset' removed (now using INI file)
 - Command '/regdelete' removed (now using INI file)
 - Command '/deskicon' removed (done by installer)
 - Command '/verify' removed (done by external program later)
 - Command '/phpinfo' removed (not needed anymore)
 - Command '/unload' removed (not needed anymore, ecc should startup again when crashed)
v1.4.2.2:
- Fixed a 'ECC can not start twice' bug when changing from detail/list view.

- ECC Live!
v2.2.0.0
- Own update check-up (instead of ecc.exe v1.4.2.x)
- Using a INI file instead of 'zero byte' files to read-out 'status'
- Updating unrar from v3.20 (2003) to v3.70 (2007)
 - Placing unrar in 'thirdparty' and added license.txt
- Removed version check-ups of tools
- Adjusting some text in messageboxes
- Changed color & lettertype to verdana in info boxes
- Cleaned-up some internal code

- ECC Live! UPD
v2.0.0.3
- Changed Unrar path to 'ecc-core\thirdparty\unrar'

- ECC Script
v1.1.0.0
- Turbo mode!, by using another function script validation speed has been increased by 50 times!!
v1.0.0.3
- Removed Autoit3 check
- This will let ECC Script function on computers that have autoit3 running for other purposes
- Multiple scripts can executed at the same time (if needed)
v1.0.0.2
- Added extra commandline support (pushed to script)
- Commandline 2 in script is extra emulator options BEFORE ROM
- Commandline 3 in script is extra emulator options AFTER ROM
- Internal checks
- Added folder check (ecc-tools)
- Added name check (eccScript.exe)
v1.0.0.1
- Added 'please wait' text string to validation
- Removed the 'close' string from the validation 'blacklist'

- Tools GLOBAL
- Added Thirdparty tools for scriptfunctions
 - Autoit v3.2.4.9
 - Notepad++ v4.1.2
- Added NEW tool: eccScript
 - This is to validate & run ECC scripts.

- Core components
-- PHP update from 5.1.4.4 (04-May-2006) to v5.2.3.3 (31-May-2007)


Version 0.9.1 (2007.05.06)

FYEO 06 (2007.05.05)
- platformNav contextmenu "configuration" now opens the emuconfig + selected platform
- fixed "show only roms w/o meta"
- Compare/Merge now also for roms you dont have possible (metadata only!)

FYEO 05 (2007.05.04)
- removed option "navigation autoupdate" Now activated by default!
- removed the tooltips from navigation and mainlist...
- optimized search
-- decrease timeout for freeform search
-- fixed the strange behavior of "match beginning of searchword"
- ConfigPopup - added i18n entries for all tab-names
- updated ecc-datfile format (v0.97) filesize added!
- fixed open compare popup only, if different roms are selected.
- Topmenu
-- now, "Add new ROMS" is deactivated, if no platform is selected!
--- Also, if a platform is selected, this is shown as "Add new ROMS for Platform"
--- Better, because the most new user dont know how "All found works" :-(
--- new i18n file for topmenu translation

FYEO 4 (2007.04.28)
- Doubleclick on Platform-Navigation opens "Add ROM"
- optimized contextmenu - 'Start ROM with' - Now begins with emulator-name
- romCombiner (Compare/Merge)
-- added FULL merge functionallity to merge eg. title, developer to an other rom!
--- Use the arrows in popup to merge data!
-- added mainlist contextmenu 'Compare - Select left side' -> 'Compare to "romname"'
--- First select a rom with 'Compare - Select left side'
--- Then a second with 'Compare to "romname"'
--- OR use drag´n´drop!!!!

FYEO 3 (2007.04.28)
- Added option "show all roms with personal notes!"
- romCombiner
-- as first step added rom-compare - use drag-n-drop in maint listview!
- ConfigPopup
-- images: Added Thumbnail quality / min original size selector
-- emucontrolcenter: reactivate startup-config
- imageCenter
-- Added 3 additional slots for storage images (like cds, cartdridges aso)
- hopefully fixed problem with parsing PS1 Files.
- mainlist context-menu
-- added Remove ROM images (also automaticlly called after Remove ROM from database)

FYEO 2 (2007.04.21)
- emuConfig
-- added new info area for emulator infos/config-tips/weblink
- Top-Menu
-- added "feel lucky", showing a single random game (F4)
-- added "reload", reloading roms in view (F5)
-- added new main-menu item for update! (eccLive!)
- imageCenter
-- Add/Remove popup dont put imageCenter in background
- Personal-Tab
-- added save/delete-confirmation!
-- Empty note will remove entry in database
- center all confirm/info/browser popups
- added some info-popups on add/remove

FYEO 1 (2007.04.20)
- added nav-autoupdate for "show all available metadata"
- imageCenter
-- added contextmenu to add/remove images (rightclick on a imageSlot)
-- added hilighting on selection
- optimized listviews performance
- removed logging from allfound romparsing. To many results freezes ecc
- better integration of ecc imageConverter + statistcs in status-area

Documentation
2007.05.06:
- Updated Changelists
- New section: Troubleshooting
 - Added "OCX Outdated"
 - Added "File is hacked"
2007.04.29:
- Updated Changelist
- Updated ECC Tools Changelists
- Updated ECC Tools Screenshots (white editions)
- Updated supported filetypes
- Fixed empty lines between links
- New section: Getting started
 - Added "Adding ROMS to ECC"
 - Added "Emulator assignment for platform"
 - Added "Add/Edit ROM media info"
 - Added "Adding images to a ROM"

- Added platforms
-- NEC PC-6001
-- NEC PC-8801
-- Fujitsu FM-7

- ECC Startup
v1.4.2.0:
- Added 'auto update check' on startup!
- This will notify the user if there is an update available!
- Speed up the splashscreen a little bit.
- Sound now start playing when splashscreen is shown.
v1.4.2.1:
- Auto update check on startup in total silent mode!
-- Not even an error message will be shown, in some cases there is a error
-- caused by ECC when writing new ini at fresh installation.
-- And you will not get bothered of error messages showing up when something goes wrong

- ECC Live!
v2.1.0.5
- Added better connection messages.
- Added a row for 'lastupdate'.
- Added 'ecc start' after updating, this is only when updating from 'autoupdatecheck'.
- Resized the updatelist.

- Tools GLOBAL
-- White edition
-- Added XP look
-- Added tooltips on the buttons
-- Updated the OCX components!
-- Fixed 'HALT' on Japanese / Chinese systems.
--- The 'self validation' check on the tools that where incompatible/conflicting
--- with japanese / chinese charsets, hopefully this is fixed now!

- Core components
- ECC Core update from 2.10.7.0 to v2.10.11.0
- Pixbufloaders from 2005.08.02 to 2007.03.26
- Immodules from 2005.08.02 to 2007.03.26
- Pango 1.5.0 to 1.6.0
- Updated some graphical engines


Version 0.9 (2007.04.14)

FYEO 9 (2007.04.14)
- imageCenter - fixed problem with + sign in image filepath
- fixed Romlist refreshing fix bug :-)
- fixed help documentation link
- fixed ECC 0.5.887 Beta - ECC window goes to background (For config and about!) :-)
- deactivated button for startup config because of focus problems
- LOGGER
-- implemented logger checkbox/openFolder in the ecc-config
-- loging implemented for (logged to ecc-logs/logfiletype.txt)
--- fileparsing
--- datfile import ecc / rc
--- find/remove duplicate roms from db
--- files remove/rename/copy
--- Images add/remove
--- Webservice romdb transfer

FYEO 8 (2007.04.11)
- implemented new database update mechanism for a easier database update
-- added new database/eccdb.empty containing no data
-- added backup folder for backups on version change!
- Metaedit-Popup
-- now 3 buttons available... save, save+close, save!
-- removed year autocompletion :-)
- fixed encoding problem if using ° signs in detail/list-view
- fixed Romlist refreshing. Now the detail-list will not be resetet on platform (right)click.
- reordering ecc-tools menu - adding ecc ROMid

FYEO 7 (2007.04.07)
- Metaedit-Popup
- Added error output, if there is no title set!
-- Added autocompletion for Developer, Publisher, Year
--- all rom meta-informations are used for autocompletion
-- removed some buttons and move them to top
-- Added rating tab - This feature isnt implemented now!
- Added autocompletion for search-field-selector ([DE] DEVELOPER, [PU] PUBLISHER, [YE] YEAR)
-- only rom meta-informations of available files are used for autocompletion
- ImageCenter
-- fixed problem, if you add images to an game, you dont have! (metadata only)
- Search filter
-- reset now also reset the search field/operator
-- optimized reset mechanism
- InfoPane
-- Removed image-tab -> moved direct under the preview-image
-- changed order... now personal is second tab, third is e(xtended)Search
- Tools Menu
-- Added ecc image coverter from V1 to V2 structure!
- Configuration
-- Added new color and themes tab
-- Added image background-color
- Fixed ordering in listview

FYEO 6 (2007.03.18)
- Search filter (first menu item in rom context-menu (right mouse click))
-- lets direct filter for a given value of the selected rom (good feature for listview with hidden panes)
-- possiblility to reset the serach
- imageCenter
-- added new slot media_storage for and image of the media- cartridge, cd, disk aso.
- added file-operations option to delete also the userimages for a deleted rom
- info-pane
-- added new ecc-star images for rating
-- fileinfo, removed space, chaged Crc32 to CRC32 ;-)
- media-edit popup
- fixed taborder (but dont know how! :-))
- optimized dropdown sizes

FYEO 5 (2007.03.17)
- fixed Alphabetic listing bug in list and detail view
- fixed import of ecc datfile version 0.7
- added new eccTools menu (eccLive, eccBugreport, eccTheme)
- fixed urlencode problem in imageCenter dragndrop
- fixed Romlist does not refresh when search is empty
- restructured fileinfo area

FYEO 4 (2007.03.11)
- added fast list refresh to image config
-- this is experimental... (paging problem) but works fine and is ULTRA FAST!!!!! :-)
- fixed aspect ratio bug on next image in image-preview!
- imageCenter
-- added button to open the current image-folder
-- fixed problem, if colors for dropzones are missing!

FYEO 3 (2007.03.10)
- implemented image-converter for ecc-user-images (ecc-tools/convertUserImages.bat)
-- moves the old images to the new folder structure and create thumbnails
-- please backup your ecc-user folder before using this batch script!
- imageCenter
-- fixed problem with not removed thumbnails. (eg. if gif inserted = no thumbnail)
-- changed colors of dropzones to option_select_* editable from ecc-configuration
-- match option now only reset platform cache
-- optimized caching
- configuration - new image tab added
-- moved imagesize from ecc-tab to imgage tab
-- removed imagesize 480x320 from dropdown
-- added aspect ratio ooption
- system / performance
-- remove duplicate call of update_treeview_nav

FYEO 2 (2007.04.21)
- imageCenter
-- added hilight of the current selected imagetype
-- implemented autoselect of the choosen imgatype from info-pane image-tab

FYEO 1 (2007.04.20)
- Imagehandeling
-- completely refactured image handeling
-- optimized imagefolder structure for better performance
-- removed old type of unsaved image-search. (to be replaced)
-- added new images match-button to show only matching images
--- used to search for wrong image-assignments
--- used to see, which images are missing!
-- implemented new imageCenter with drag-n-drop tagets
--- possibility to direct assign an image to an image-type using drag-n-drop
--- automatical thumnail creation
---- thumbs are only created if needed (gifs, small images <= 30 KB)
---- thumbs are created as jpg quality 75% in 240x* (no aspect ratio)
--- copy and move images from source implemented
--- feature to remove images (including removing thumbnails)
--- feature to copy/move/delete without confirmation popup
--- optimized toggle image best fit
- Topmenu
-- added emu-menu for direct configuration
-- added view-menu with view-data, view-type and show/hide areas

- Added platforms:
-- Fairchild Channel F
-- Game Park 32
-- MGT Sam Coupé
-- MSX 2
-- Nintendo Virtual Boy
-- Oric Atmos/Telestrat

- New tool: ECC ROMid
- All platform info's are updated and present
- ECC Documentation
- Split-up NeoGeo pocket BW/color.
- Removed the 'double' sega logo on the 'Sega Mega CD' platform.
- Renamed the 'Sega Genesis / Megadrive' to 'Sega Megadrive / Genesis' to fit better in the platformlist.
- Renamed the 'Bandai Wonderswan (B/W)' to 'Bandai Wonderswan' to fit better in the platformlist.
- Polished the NDS platform lost pixels and missing dropshadow.
- Sorted the 'Navigation' list alphabeticly.

- ECC Startup
v1.4.1.2:
- 'ECC Startup Config' can now only start once (fix)
- Tweaked the 'fixed' title a little bit
v1.4.1.1:
- The 'minimize-2-tray' function can now be configured!
 - Run 'ecc.exe /config' to configure ECC startup!
v1.4.1.0:
- ECC gets a fixed title after capturing the window!
- Added a nice 'minimize-2-tray' function!
- Added an egg... ;)
v1.4.0.3:
- ECC not longer standing on TOPMOST window! (fix)
v1.4.0.2:
- Fixed the 'freeze' bug on startup, by improving the focusing of the ECC window

- ECC Installer
v1.1.0.5 (ECC v0.9):
- Compiled with new NSIS v2.25
- Fixed the ECC Live! run on cancel/abort installation.
- Enhanced the discription text visibility.
- Disabled the components page (cannot unselect components).
- Disabled the ECC exist checkup due to an idea of creating an empty/new eccdb when needed.
- Added option to re-structure ECC imagecenter images.
- Added option to add a ECC icon on the desktop.
- Added ROMid startmenu icon.
- Added extra startmenu subfolder 'links'
- Added shortcut to ECC documentation in startmenu.
- Added shortcut to ECC Theme download page in 'links'.
- Added shortcut to ECC support forum in 'links'.
- Added shortcut to imagecenter images v1 to v2 convertor in 'tools'.
- Moved the Camya website link to startmenu folder 'links'


Version 0.8 (2007.03.05)

* config-popup
** Full i18 translation support
** EMU-SECTION
*** implemented new emulator parameter
**** "filename only"
***** removes the path eg. "c:/emu/sf2.rom" to "sf2.rom"
***** also auto-change the basepath to the rom-directory (standard emu-directory!)
**** "no extension"
***** removes the fileextesion from filename eg. "sf2.rom" to "sf2"
** ECC-SECTION
*** added color-selector for treeview hilight color (bg/fg)
*** Added Fontselector for global Gui-Fonts
*** added button to access startup config
* added true option state (yes, no, value) output to the detail view
* added background-colors to all colums (also image columns)
* fixed order in detail view - some meta-fields are flipped
* renamed ego-shooter to first-person shooter
* optimized pixbuf and textrenderer in treeview
* Polished platform images
** Removed some artifacts, lost pixels and redone some unsharp images!
* Image transparity and alpha channel
** All images have been REDONE with image transparity and alpha channel!
** This is a neat feature.....also for the themes , so NO MORE WHITE BORDERS!!
* Improved rating image
** Slightly improved the rating image, by darkening the border and faded it some more!
* Improved cell image
** Slightly improved the cell image, by giving it a border, and used some gradient overlay for extra eye candy!
* Added platforms
** Sinclair ZX Spectrum
** Sega Mega-CD
** Sega Saturn
** Emerson Arcadia 2001
** Magnavox Odyssey 2
** Philips CDI
** Sega Dreamcast
** Nintendo Gamecube
** Vtech Creativision

- ECC Core
- Update to GTK v2.10.7 (2007.01.09)
- Libgdk-0 & Libgtk-0
 - A small core update that eliminates the error message:
 - 'This application cannot run because libgdk-0.dll cannot be found, reinstalling this application can solve this problem'
- SVG Support for themes
- Pango (language)
 - Pango is a 'font renderer' for GTK, used to display the correct fonts to a specific 'non latin' language.

- ECC Startup
v1.4.0.0:
- Fixed the registry writing, when not in 'admin' mode (HKLM --> HKCU) (fix)
- Removed 'first startup' pop-up if user wants startmenu icons (ECC installation takes care of this)
- Removed some commands:
 - Removed /website (ECC installation takes care of this)
 - Removed /starticon (ECC installation takes care of this)
 - Removed /disclaimer (Tools and startup are distributed onder the same license)
v1.4.0.1:
- Removed the disclaimer line from the 'about' box.
- Deleted Some old code.
- Splash image
 - Changed the footer line.
 - Syncronized the ECC icon with the background.
 - Now locked with MD5 Hash (unchangeable).

- ECC Theme select
v1.0.0.7
- Updated for the 'new GTK v2.10.7 engine', corrected the path to '2.10.0'
v1.0.0.8
- Updated ECC Theme for the 'Global GUI font' config for ECC v0.7.5+
v1.0.0.9
- Auto highlight theme in list on startup has been fixed.

- ECC Live!
v2.0.0.1:
- Handling of the separate INI files!
 - The file 'ecc_local_update_info.ini' has been added, ECC Live! keep it's track in this file.
- Fixed a small bug that sould say 'No update available...' and not 'Waiting...' when no updates found.
v2.0.0.2:
- Fixed a bug where ECC Live! crashed when clicked on the update list when updating/downloading.
- Fixed a bug where old file(s) should be deleted on the next update that uses a delete list.
- Made some fields a bit longer, also made the list a bit bigger.
- Added a 'kickstart', when updating ECC Live!, the new version wil directly continue downloading.
- Some text strings have been adjusted.

- ECC Bugreport
v1.0.0.8:
- Increased the 'local' INI size to be sent, otherwise Bugreport won't send the report (fix)
- Adjusted some text messages.


Version 0.71 (2007.02.10)

* added search field publisher
* added remove duplicate roms to context-menu
* added error output for found invalid zip files
* config-popup
** (ecc) added color selectors for event-select boxes
* fixed romcenter datfile import bug
* fixed missing indices to database for better performance


Version 0.7 (2007.02.04)

* added config popup
** platform / emulators
*** implemented activation/deactivation of platforms from emuconfig popup
*** removed old platform treeview from emuControlCenter config tab
*** start rom with (alternate emulator)
*** global, extension and alternate emus available
*** emus can use fallbacks and can overwrite parameters!!!
*** added error output
*** added indicator for escape and 8.3
** emuControlCenter
*** moved from old tab to new config popup
*** added color chooser and font selector
** Datfile
*** Added romcenter clean name option to frontend
* extend rom context menu
** added description in emu-selector context menu for better overview
** doubleclick on rom without emu opens config
* added platform-icons to list view
* added ecc.exe startup-config to mainmenu
* added new folder "ecc-tools"
* added hotkey F5 and ALT-R for imagereload (fixed F5)
* added meta-fields (metaedit/export/import/romdb)
** publisher
** storage type (savegame)
* added extension gen to genesis
* added fileextesion jma to snes
* added missing flags for language dropdown
* added new versions of ecc-tools
** eccTheme - a theme selector
** eccLive - for direct updates from internet
** eccBugreport - for direct bugreports to the ecc developer
* optimized image-error handling
** for unknown imagetypes and for missing files
* complete refactored startRom
* complete refactored the ini-manager
* fixed bug with not starting on first time!
** I´ve missed the default ecc-user path in the ecc_general.ini
* fixed extension to parser assignment for wonderswan bw/color
* fix null byte problem in data-tab
* fixed predifined best emu for 32X
* fixed open zip files bug


Version 0.6  (2007.01.13)

* Update romdb - the internet rom/emu-metadatabase!
**www.camya.com/eccdb/
** Added search functions
** optimized overview
** added web2.0 clouds  
* Added french languages files created by Cyrille
* fixed EDIT-Window always up focus problem.
* added default emulator
** optimize the ini-files for all platforms
* added commandline parameter support for emulators
* added shortcuts
* ALT-A or ALT-Ins = Add new roms
* ALT-X or ALT-Del = Remove rom from database
* ALT-E = Edit
* ALT-B = Bookmark
* ALT-F = search focus!
* added simple listview (default 100 results)
** fixed graphical bug on 1024x768 (FYEO V2)
** reorderable
** cols resizeable!
** added toggle between detail and list view
** added some more fiels for this view!
*** because of an php-gtk2 bug, it is not possible to do this on the fly yet!
* Fixed a little history ini bug!
** Autoupdate was not saved if never used!
* disable buttons in imagepreview when reached end
* try to catch import bug (playstation 1)
* Platforms
** added Atari 8-bit Computers a8bit
*** added ecc_platform_a8bit_info.ini
*** Removed A400/A800 (allready in a8bit)
** extensions added for MSX, NES, Sharp X68000
** changed image of PC Engine
* Added categories:
** "Action Adventure"
** "Trading Simulation",
** "3D Shooter",
** "Ego Shooter",
** "Sports/other",
** "Jump n Run 3D".


Version 0.5.96 (2007.01.11)

* Update romdb - the internet rom/emu-metadatabase!
** www.camya.com/eccdb/
** Added search functions
** optimized overview
** added web2.0 clouds :-)
* Added french languages files created by Cyrille
* fixed EDIT-Window always up focus problem.
* added default emulator
** optimize the ini-files for all platforms
* added commandline parameter support for emulators
* added shortcuts
* ALT-A or ALT-Ins = Add new roms
* ALT-X or ALT-Del = Remove rom from database
* ALT-E = Edit
* ALT-B = Bookmark
* ALT-F = search focus!
* added simple listview (default 100 results)
** fixed graphical bug on 1024x768 (FYEO V2)
** reorderable
** cols resizeable!
** added toggle between detail and list view
** added some more fiels for this view!
*** because of an php-gtk2 bug, it is not possible to do this on the fly yet!
* Fixed a little history ini bug!
** Autoupdate was not saved if never used!
* disable buttons in imagepreview when reached end
* try to catch import bug (playstation 1)
* Platforms
** added Atari 8-bit Computers a8bit
*** added ecc_platform_a8bit_info.ini
*** Removed A400/A800 (allready in a8bit)
** extensions added for MSX, NES, Sharp X68000
** changed image of PC Engine
* Added categories:
** "Action Adventure"
** "Trading Simulation",
** "3D Shooter",
** "Ego Shooter",
** "Sports/other",
** "Jump n Run 3D"


Version 0.5.95 (2007.01.06)

* Implemented "user table"
** adding/updating rom notes implemented
* Implemented "best fit" in imageCenter
** option will be written to history.ini
* Implemented "image selection" saving
** The last imagetype-selection is saved into the history.ini
* Shortcuts implemented
** F11 Hide navigation area
** F12 Hide mediainfo area
** F6 Show media
** F7 Show bookmarks
** F8 Show history
* implemented internal database updator for eccLive updates!
* fixed "Number of pages error"
* deactivated support-banner (support banner bug)
* adding platforms
** Amstrad CPC
** Panasonic 3DO
** Sharp X68000


Version 0.5.9 (2007.01.01)

* Include new Tab PERSONAL
** Rom played count
** Rom played last time
** Rom rating
** Rom bookmark status
* Improve config updates. Please resave you genesis/megadrive config!
* Improvements in ROMDB
** Now all platforms supported by ecc will be accepted
** Limit length of filename for non breaking divs.
** Update statistics (platfom, categories)