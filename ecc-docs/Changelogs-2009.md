## Changelogs 2009
***
Version 1.0.R1 (2009.11.20)

- Added platforms:
 - Atari Jaguar CD
 - Cosmac VIP
 - Mikrosha Radio-86RK
 - MSX Turbo R (Panasonic FS-A1GT/ST)
 - Panasonic JR-200
 - Spectravideo SV318/328
 - Taito GNET
 - Texas Instruments TI-82
 - Texas Instruments TI-83
 - Texas Instruments TI-85
 - Texas Instruments TI-86
 - Texas Instruments TI-92
 - IGS PolygameMaster (resources by robertobernardo & te_lanus)

- Small changes:
 - Placed Philips G7400+ in 'console' category.
 - Added Extensions for:
   - Exidy Sorcerer (.snp)
   - Texas Instruments TI-99/4A (.TIcart, .TIDisk, .TITape)
 - Removed .CSO extensions for ECCid: 3doe, Amigacd32, cdi, cdtv
 - Fixed ROM import for: Commodore CDTV (eccid: cdtv)
 - Renamed plaform 'Tomy Pyuta/Tutor' to 'Tomy Tutor/Pyuta'
   - NOTE: You have to re-import your roms again!, because the eccid has,
     been changed from 'pyuta' to 'tutor'.
 - Updated platform images:
   - Tomy Tutor teaser image (words turned).
   - Nintendo DS teaser image (added nintendo logo in it).
 - Fixed ecc-user pathnames for ECCid: l100, atomisw
 - Fixed RU (russian) translation selection string for all languages (was missing)
 - Script updates:
   - Updated: 'psxfin' script, adding code to mount a cd (if needed) (by Bigby)
   - Added: 'ePSXe' script, to mount a cd (by Bigby)
   - Improved the Virtual Pinball startup, the script isn't needed anymore. (by thetrout)
 - Fixed ECC version string to 'v1.0 WIP 06 (2009.08.08)'
 - Updated 'Atari Jaguar' teaser with correct console image.
 - Fixed name 'Texas Instuments TI-99/4A' to 'Texas Instruments TI-99/4A'

- ECC Startup
v2.3.3.8
- Fixed a bug where unpacked zip files where not deleted due to update 00411 wich
 has a newer beta compile of autoit 3.3.1.5 the function 'Opt("OnExitFunc")'
 doens't work anymore, so i have found another solution, that does work!
- Fixed a bug where unpacked files should be deleted if the user-folder is not
 the default "ecc-user" location.
v2.3.3.7
- Added a function that configures Xpadder for Windows 7, so that no error occurs
 when Xpadder is started, by writing a XPSP3 compatability mode into the windows
 registery.
v2.3.3.6
- Added a function to delete the 'ecc-user\#_AUTO_UNPACKED' folder on ECC exit.
 - This feature is only active in ECC v1.1 WIP or above!

- DATfile updates:
 - ATOMISW: *NEW*
 - PGM    : *NEW*
 - MAME   : from v0.134 to v0.135
 - MODEL2 : from MAME v0.135
 - PGM    : from MAME v0.135
 - S16    : from MAME v0.135

![](https://raw.githubusercontent.com/wiki/PhoenixInteractiveNL/emuControlCenter/images/img_misc_great_step.jpg)

Version 1.0 (2009.10.11)  A GREAT STEP FORWARD!

- Added platforms:
 - Apple 3 (resources by: te_lanus)
 - Commodore 128 (resources by: te_lanus)
 - Philips Videopac+ G7400 (resources by: te_lanus)
 - Sega Sammy Atomiswave (resources by: Jarlaxe)
 - Sord M5 (resources by: te_lanus)
 - Vtech Laser 100/110 (resources by: te_lanus)
 - Vtech Laser 350/500/700 (resources by: te_lanus)

WIP 06 (2009.08.08)
- Moved the small ECC teaser image to the 'internal' image folder.
- Fixed broken links of the 'ecc_icon_small.ico' file in certain files.
- The header/banner website bug is fixed.
- now Show/Hide status of search-panel is saved.
- fixed "List view options not saved" bug.
- added new ps1 parser.
-- You have to update your ecc_ps1_system.ini, if you want use this parser!

WIP 05 (2009.03.31)
- implemented new gameid parser for PS1 (resources by zerosan).
- fixed the doubleclick bug in mainlist.
- optimized the refresh of imagelist and metadata panel.
- fixed the imagelist bug (duplicate entries if you navigate fast in mainlist :-)).
- fixed a bug that avoid, that roms in zip subfolders are removed from temp folder.

WIP 04 (2009.03.21)
- added new IconView mode. (experimental - not finished).
- fixed romOf/cloneOf bug.

WIP 03 (2009.02.13)
- added "IMAGES" panel for direct view of all images.
- reorganized meta info panel.
- optimized emuControlCenter for resolution 1024x768.
- Fixed the French language by changing the charset (finally!).
- Added back the tool languages for PT & GR (because they are not yet included and supported in the 0.9.9 series).
- Changed the original author/date/info inside the language files to the rightfull translater.

WIP 02 (2009.02.04)
- added "info ID" to freeform search.
- added autocompletion to "info string" and "info id" field in meta edit popup.
- fixed navigation auto update for "own edited/transfered meta".
- added "copy/move by searchresult".

WIP 01 (2009.01.26)
- added new filter modes
 - own metadata (edited or transfered)
   - Show your own modified metadata. This mode shows edited but not transfered (to gamedb) and also transfered meta!
 - own metadata (edited only)
   - This show only the edited meta, that isn´t transfered yet!
 - own metadata (transfered only)
   - This show only the transfered meta. Use this to update already uploaded metadata....
- Changed translation system. Now a source and destination encoding is setable via charset.ini

- Small updates:
 - Added translations:
   - Greek (by Alkis30)
   - Hungarian (by Gruby & Delirious)
   - Portoguese (by Namnam)
   - Russian (by ALLiGaToR144)
   - Spanish (by Jarlaxe)
 - Fixed messagebox icons when selecting another theme then 'default'.
 - Added theme color splashscreens for the themes:
   - afterhours
   - miss-kit-bubblegum
   - miss-kit-purple
 - Renamed the Platform 'Vtech Laser 200' to 'Vtech Laser 200/210/310'
 - Updated the 'Vtech Laser 200/210/310' teaser.
 - Updated the 'Dosbox' teaser
 - Renamed plaform 'Magnavox Oddysey2' to 'Philips Videopac G7000'
   - NOTE: You have to re-import your roms again!, because the eccid has,
     been changed from 'ody2' to 'vg7000'.

- Script updates:
 - Added script for 'JVZ200' emulator, for 'Laser 200' (by te_lanus)
 - Added script for 'WinAPE' emulator, for 'Amstrad GX4000' (by te_lanus)
 - Added script for 'DCVG5K' emulator, for 'Philips VG-5000' (by Vicman)
 - Updated ECC ScriptROM SYSTEM file to v1.0.0.7
   - Made some gramatical changes in the 'title bar not found' sentance.

- ECC Startup
v2.3.3.5
- Fixed a possible problem to register the OCX files, now using the full path
 and using quotes to register an OCX with 'regsvr32'.
v2.3.3.4
- Fixed a problem with creating userfolders at the right location, now the userfolder
 location is read from the ecc ini file.
- Fixed a problem with an infinite loop if the userfolder doesn't exist.
v2.3.3.3
- Xpadder now also appears in the language wich is chosen in ECC.
v2.3.3.2
- Possible fix for ECC splashscreen starting/staying in minimized mode #2.
- Improved ECC Bugreporting, now the bugreports are being send right away,
 instead on next startup.
v2.3.3.1
- Possible fix for ECC splashscreen starting/staying in minimized mode.
- Added ECC 1005 error, wich shows the PHP error.log in a GUI when an
 error has occured.
v2.3.3.0
- Improved they way if an (PHP) error occured with ECC.
 - The GUI splashscreen will be unloaded fist before the messagebox will be shown.
   (the messagebox was sometimes behind the GUI, so it could hardly be clicked)
 - When an PHP error has occured (wich writes an error.log file), ECC startup will
   immediatly quit. (instead of waiting a minute and nagging you with the
   waiting splascreen)

- 3rd party updates
 - Notepad++ v5.5

- Updated DATfiles:
 - MAME   : v0.132 to v0.134
 - CPS1   : v2009.04.26 to v2009.09.15
 - CPS2   : v2009.06.08 to v2009.09.15
 - CPS3   : v2009.09.15 (from MAME v0.134 DAT)
 - MODEL1 : v2009.09.15 (from MAME v0.134 DAT)
 - MODEL1 : v2009.09.15 (from MAME v0.134 DAT)
 - NEOGEO : v2009.04.26 to v2009.09.15
 - S11    : v2009.09.15 (from MAME v0.134 DAT)
 - S16    : v2009.09.15 (from MAME v0.134 DAT)
 - S18    : v2009.09.15 (from MAME v0.134 DAT)
 - S22    : v2009.09.15 (from MAME v0.134 DAT)
 - SVM    : v0.99.1.3 to v0.99.1.5


Version 0.9.9.R2 (2009.06.28)

- Added platforms:
 - Acorn Atom (resources by: te_lanus)
 - Bondwell Model 2 (resources by: te_lanus)
 - Casio Loopy (resources by: te_lanus)
 - DEC PDP-1 (resources by: te_lanus)
 - DEC PDP-7 (resources by: te_lanus)
 - Sega Pico (resources by: te_lanus)
 - Signetics Pipbug (resources by: te_lanus)

- Small updates:
 - Fixed the banner (top left) website bug (hyperlink is removed)
 - Fixed some EN language strings/typos
 - Changed Homelab4 emulator and settings INI's (by Gruby)
 - Placed "Magnavox Odyssey 2" in the console catagory.
 - Renamed "Tomy Pyuta" to "Tomy Pyuta/Tutor" (incl. new teaser)
 - Improved 'option' images, by adding transparancy & removing ugly pixels
 - Improved the small ECC teaser image by removing the border at the bottom.
 - Moved the small ECC teaser image to the 'internal' image folder.
 - Fixed broken links of the 'ecc_icon_small.ico' file in certain files.
 - Added spanish translation instructions for the thirpartytools.

- Script updates:
 - Added Microkey Primo script (by Gruby)
 - Added script for the 'pdp7' emulator (by te_lanus)
 - Updated the "Ultimo" script for "Microkey Primo", v1.0.1.0 to 1.0.1.2, changes:
   - New Function - EmulatorControl($EmulatorWindowTitle, $CommandLine) (Thanks to Phoenix)
   - Some Primo C (Colour Primo) files is working now.
   - Needed Reset handle
   - Some fixes...

- ECC Startup
v2.3.2.9
- Added a PNG border to the splashscreen, for some shade (so it doens't look flat)
- When reloading ECC the (reloading)splashscreen now also fades.
- Fixed a bug where the 'reloading' string wasn't shown properly when ecc was reloading.

- ECC Live!
v3.2.0.3
- Fixed issues where filenames to add/replace strings could not be found.
v3.2.0.2 -fix2
- Fixed the word 'Avaialable' to 'Available'

- 3rd party updates
 - Updated, Notepad++ v5.4

- DAT file updates:
 - CPS-1: v2009.03.10 to v2009.04.26
 - CPS-2: v2009.01.06 to v2009.06.08
 - SVM: v0.99.0.8 to v0.99.1.3
 - MAME: v0.130 to v0.132
 - NEOGEO: v2009.03.10 to v2009.04.26
 - PRIMO: *NEW* v0.99.1.1 (by Gruby)


Version 0.9.9.R1 (2009.04.04)

- Added platforms:
 - APF Electronics Imagination Machine
 - Exidy Sorcerer
 - HomeLAB series - HomeLAB4

- Small updates:
 - Added transparancy for the Hungarian flag (meta edit).
 - Fixed all ECC Startup messages, some where missing.
 - Updated French languages for ECC Startup.
 - Changed best emulator for Vic-20 to XVIC.EXE.
 - Polished the Pinball nav image (ugly pixels).
 - Added the Atari logo in the Atari 2600/5200/7800 platform teaser image.
 - Added CDI (Disc Juggler image) extensions to CD platforms.
 - Fixed the systems info files to be more compatible with unicode languages,
   converted chars:
   ‘ > ', ’ > ', “ > ', ” > ', ÷ > /, % > percent, ½ > .1/2, ¼ > .1/4
   • > *, × > x, ¥ > Yen, $ > Dollar, £ > Pound, ~ > Approx.
 - Enterprise 64/128 extension update
   - Added: TRN, SAP, EP32, EP128S, COM, BAS, IMG, TAP, WAVE, DTF
   - Removed: PRG, BIN, 128, ROM
 - Removed CSO (Compressed ISO) extension for all CD image platforms.
 - Renamed the platform 'Híradástechnikai Szövetkezet HT-1080Z' to
   'Híradástechnika HT-1080Z'
 - Rearanged the ECC documentation, also included a FAQ (wip)

- Script updates:
 - Updated VIC-20, using the latest VICE emulator XVIC (atm v2.1) wich support
   commandlines, so no INI write is nessessary, all settings can be set with
   commandline parameters.
 - Added, VP script for Virtual Pinball, to run tables from the ECC GUI.
 - Added, PSXFIN script for Playstation 1, wich run the emulator in fullscreen.
 - Updated, script for 'ScummVM' v1.0.0.5 (DerMicha75)
   - Improved unpacking of agi/gob/saga files by overwriting existing files!
 - Updated, script for 'Project Tempest' v1.0.0.2 (Phoenix)
   - Fixed support for Windows Vista
   - Added messagebox and handle when have to enter a start address.

- ECC Startup
v2.3.2.7
- Improved language 'switching' for thirdparty tools, in the beginning only on
 firt start the language for thirparty tools where configured, now when changing
 the language inside ECC, the thirdparty tools will also be adjusted ;-)
- Renamed the list 'ecc_ThirdPartyConversionList' to 'ecc_ThirdPartyLanguageList'
v2.3.2.5
- Added a 'GetDriveInfo' parameter for drive information
 to let ECC give better support for removable drives!
v2.3.2.3
- ECC starts in windowed mode when the useable width for ECC < 1000 pixels.
 - when < 1000 pixels ECC may experience glitches in the GUI.
 - also a messagebox is added to inform the user. (one-time message)
- Fixed the Greek GUI language. (by putting the INI in unicode)
v2.3.2.2
- Fixed AutoIT Error: 'Line -1, Variable used without being declared.' on
 other systems than 2000/XP/Vista, like: Windows Server 2008
- Fixed Notepad++ auto-language if 'ThirdPartyConversionList.ini' is not available.

- ECC Live!
v3.2.0.2
- ECC Live! check on ECC startup will be disabled when there is no active
 internet connection found, this is because it will return errors all the time
 when you have no connection, you have to enable this manually again in the
 ECC configuration screen, when you have re-established the internet connection.
- Messagebox at the end will ask to view the log file now, this is only
 when updates have been installed or when an error has occured.
v3.2.0.1
- Implemented an ERROR KICKSTART for ECC Live!, this way when there is a bug
 in ECC.EXE and it cannot write the configs eccLive needs, it will continue
 to update assuming the OS is WINNT.

- 3rd party updates
 - Updated, Notepad++ v5.3
 - Updated, 7-Zip v4.65

- DAT file updates:
 - CPS-2: v2009.01.06 to v2009.03.10
 - CPS-3: v2007.07.10 to v2009.01.06 (from mame 0.129 dat)
 - MODEL1: *NEW* v2009.01.05 (from mame 0.129 dat)
 - MODEL2: v2008.06.05 to v2009.02.08
 - S11: *NEW* v2009.01.05 (from mame 0.129 dat)
 - S16: *NEW* v2009.01.05 (from mame 0.129 dat)
 - S18: *NEW* v2009.01.05 (from mame 0.129 dat)
 - S22: *NEW* v2009.01.05 (from mame 0.129 dat)
 - SVM: *NEW* v0.99.0.8 (by Gruby)
 - ZINC: v2005.07.31 to v2009.01.06 (from mame 0.129 dat)
 - MAME: v0.129 to v0.130
 - NEOGEO : v2009.01.07 to v2009.03.10


Version 0.9.9 (2009.01.18)

WIP 06.1 (2009.01.17)
- added template usage when creating a new script.

WIP 06 (2009.01.17)
- added eccscript option button in eccscript config
- added an eccscript refresh button if you add a new script

WIP 05.2 (2008.12.26)
- fixed the link to the new gamedb in this update :-) Now the search point to the new pages.

WIP 05.1 (2008.12.24)
- due to namechange of the ECC imageserver, now called imageControlCenter,
the link to download images in ECC has been adjusted!

WIP 05 (2008.12.13)
- added new category "platform"
- fixed the language string portoguese

WIP 04 (2008.10.21)
- fixed "ECC doesn't like Brøderbund" bug for all metaedit fields!
- added hungarian language for meta edit

WIP 03 (2008.08.30)
- fixed storage "Harddrive" bug (Dropdowns not filled)
- changed storage "Harddrive" to "Harddrive / Floppy" to
- fixed missing dump_type in romdb export
- catch crash, if copy text from internet to notes/review
- removed checksum check on save to avoid problems with rom options
- made the labels of eSearch a little bit smaller

WIP 02 (2008.08.29)
- added dump type selection to eSearch
- fixed "show only first rom" bug
- Added dump type to rom info area

WIP 01.5 (2008.08.21)
- Fixed problem, if you click on the top left emuControlCenter logo (open website again and again!)
- Fixed also the problem, if you open the asset/document folder and click to the main view!
- added new icon in context menu "browse documents"
- added documents "patches" folder for ips patches

WIP 01 (2008.08.19)
- added "open asset folder"
-- used to store 'manual', 'guides' and 'maps' to the ecc-user folder
-- implemented availablity state in meta info area

- ECC Startup
v2.3.2.1
- Changed the location of the thirdparty language conversion list.
v2.3.2.0
- Fixed splashscreen fading not working in Vista
- Added automatic language configuration of thirdparty tools.

- Small updates
 - Added extensions
   - Apple II: 2MG
   - Nintendo SNES: MGD, GD3
 - Removed Extensions:
   - Nintendo Gamecube: CSO, IMG, BIN, NRG, MDF
 - Updated default config for:
   - Hartung Gamemaster
   - Nintendo Gamecube (DolphinEx)
 - Updated the 'Atari800Win' script to support ATR & COM files properly.
 - Better support fo ScummVM
   - Added script for ScummVM (v1.0.0.3)
   - Made ECC handle ScummVM games like arcade platforms
   - Added ctrlmame DAT for ScummVM (by Gruby)
 - Updated eccScriptSystem v1.0.0.6:
   - Added 3rdparty variables.
   - made variables GLOBAL, so you can use these inside functions of your scripts.
 - Updated the script template.
 - Updated xpadder config with correct pathnames.

- DAT file updates:
 - MAME: v0.128 to v0.129
 - CPS-1: v2008.10.18 to v2009.01.07
 - CPS-2: v2008.10.18 to v2009.01.07
 - Neo-Geo: v2008.10.18 to v2009.01.07

- 3rd party updates
 - Updated, Notepad++ v5.1.4
 - Updated, 7-Zip v4.64
 - Updated, Autoit v3.3.0.0