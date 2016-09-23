## Changelogs 2012
***
![](https://raw.githubusercontent.com/wiki/PhoenixInteractiveNL/emuControlCenter/images/img_misc_6thyear.jpg)

Version 1.14 (2012.12.29) 6TH YEAR ANNIVERSARY!

- Fixed: a bug where external AU3 scripts did not function (being executed) properly
 on some machines and OS'es due usage to of 'start /b', now using COM object!
- Fixed: Thumb quality setting '20' in config can now be selected.
- Fixed: issues with the ROM start options in ECC config, now all options are
 properly disabled and unchecked when needed.
- Fixed: an issue where the ROM parameter box and some ROM start options where
 disabled when using eccScript.
- Fixed: Clearlook theme, this was missing when using the installer.
- Fixed: a bug in the ECC theme engine, where another template was selected it
 did not use the proper icons for that template.
- Fixed: a bug in the images TAB view, where names/titles of the games are the
 same as the archive filename.
- Added: Brand new ECC Video Player, you can now add a MP4/FLV file to your ROM!
- Added: integrated EmuMovie Downloader, with this tool integrated in ECC you can finally make
 your ROM artwork/images complete, you'll need an account on EmuMovies (registration is free)
- Added: a new feature to jump to a page directly (instead of scrolling trough all of them)
- Added new platforms:
 - Apple Macintosh
 - Atari Falcon
 - Nintendo e-Reader
 - Sega Model 3
- Added filextensions:
 - Casio PV-1000: BIN
 - Epoch Super Cassette Vision: BIN
 - Nintendo Wii: WBFS
 - Amstrad CPC: ROM
- Added: internal feature for the 'use .cue' option to look if there is a .cue
 file available if not then use normal extension.
- Added: a new option in ECC config to set the 'big filesize' parsing trigger
 to use the internal parser (PHP) or the external parser (autoit3) after a
 size limit. NOTE: PHP could crash if this is set to high!
- Added: a new option in ECC config to set the 'text cutt off' in the
 IMAGE TAB in the 'main view' to give a better view if you have one or more
 long names/titles.
- Added: SSF to be the default emulator for Sega Saturn. (also added website info's)
- Added: default media for platforms id: (MrX_Cuci)
 - a8bit, adam, bbc, col
- Added: Checkbox icons for options in the config menu.
- Updated: teaser image for 'Sega Model 2'
- Updated: nav images for 'Sega Model 1' and 'Sega Model 2'
- Updated: translations for NL and ES to v1.13
- Updated: the ICC ImageInject icon to 32x32
- Changed: MAME icon in platform contextmenu.
- ImageInject, 3D Gallery, KameleonCode, EmuDownloadCenter have been moved to
 the ecc-core\tools folder, this way all autoit3 tools are in the same folder!
- Changed: contextmenu icon for 'platform picture' and 'ICC'
- Changed: 'cover_inlay_03' to 'cover_3d', this fits better for the 3d cover images.
- Removed: FSUM support, FSUM cannot work properly with PHP COM objects.
 The COM objects are needed because the Windows 'start /b' does not function
 properly on every Windows OS, so there fore we use the 'new' COM objects.
 The replacement of FSUM is now a Autoit3 CRC32 wrapper, wich is a bit slower
 but works flawlessly! The parser has been renamed from 'ParserFSUM' to
 'ParserExternal', also all platform ini's have been updated.
- Restructured the translation files a bit.
- Totally restyled the config screen, changed: order, layout added new options
 and icons/images.
- Added and adjusted some icons in the ECC config.
- Updated the VIC20 script for XVIC:
 v2.2.0.1
   - Added: Better support for multilanguage keyboard layouts in the script.
   - Added: Tooltip on screen when the script is still busy.
 v2.2.0.0
   - Added: MultiROM support! (META ID needs to have [multi])
   - Added: Roms with SYS value support! (example META ID = [sys7424])

- Tool/Script updates
 - 3D Gallery
   v1.1.0.7 (2012.11.19)
   - Adjusted code to use 'cover_3d' instead of 'cover_inlay_03'.
   v1.1.0.6 (2012.11.09)
   - Adjusted the rom INI information to the new ECC 'selectedrom' triggersystem.
   v1.1.0.5 (2012.10.14)
   - Added hotkey ESCAPE to close the gallery.
   - Converted messageboxes into tooltips.

 - ImagePackCenter (IPC)
   v2.2.0.8 (2012.11.19)
   - Adjusted code to use 'cover_3d' instead of 'cover_inlay_03'.
   v2.2.0.7 (2012.11.09)
   - Adjusted the rom INI information to the new ECC 'selectedrom' triggersystem
   v2.2.0.6 (2012.09.03)
   - Fixed a bug where imported images are counted double!
   v2.2.0.5 (2012.09.02)
   - Fixed a bug that made IPC crash with the IMPORTER (option to clean was not in
     the GUI)
 So i added this option to 'clean files with stripper' in the
     IMPORTER GUI, so import good
 (clean) images in ECC.

 - ECC ScriptROM system
   v1.2.1.0 (2012.12.07)
   - Deamontools location now needs to be selected in ECC config!.
   v1.2.0.9 (2012.11.18)
   - Added more information if a file extension is not supported by deamontools.
   v1.2.0.8 (2012.11.07)
   - Added: new function 'GetActiveKeyboardLayout' to get the keyboard layout.

 - ECC Video player
   v1.1.0.3 (2012.11.11)
   - Using soundvolume variable used from the ECC config.
   - Adjusted default settings
   v1.1.0.2 (2012.11.11)
   - Fixed a bug where a new video is getting played when added while the ECC
     video player settings are off.
   - Fixed a bug in the messagebox that is shown if VLC cannot be initialized.
   v1.1.0.1 (2012.11.10)
   - Now stops the video if a rom is being deleted in ECC.
   - Added message (tooltip) if the ECC main window cannot be found.
   v1.1.0.0 (2012.11.10)
   - Added configurable variables used from the ECC config.
   - Autodisable video player if VLC is not found!

 - emuMoviesDownloader (EMD)
   v1.2.0.2 (2012.11.19)
   - Adjusted code to use 'cover_3d' instead of 'cover_inlay_03'.
   - Fixed a bug where files are not downloaded if a folder already exists
   - Fixed a bug where 'PCB' images are not downloaded corectly
   v1.2.0.1 (2012.11.15)
   - Fixed: Crash on importing/downloading videofiles.
   - Improved: Several GUI changes.
   v1.2.0.0 (2012.11.15)
   - Added: Cancel button.
   - Fixed: EMD will not create any doubles (PNG+JPG) of the same media (images).
   - Fixed: File extensions are now lowercased.
   - Improved: EMD now on Hyperspeed!, EMD will now first check if the file already
     exists before downloading the images data, this gives enormous speed increase!
   - Improved: The content list now only shows the content supported by ECC.
   - Improved: Clear processlist when downloading another content.
   - Made several GUI improvements and text changes.
   v1.1.0.0 (2012.11.09)
   - Added: new feature to download videos MP4/FLV from the EmuMovies server.
   v1.0.0.0 (2012.10.13)
   - With this tool integrated in ECC you can finally make your ROM artwork/images
     complete, you'll need an account on EmuMovies (registration is free)
   - Initial release

 - ICC ImageInject
   v1.1.0.7 (2012.11.09)
   - Adjusted the rom INI information to the new ECC 'selectedrom' triggersystem

 - eccDiagnostics
   v1.0.0.2 (2012.11.19)
   - Fixed scanning files to only scan files inside the ecc-core folder.

 - eccKameleonCode
   v1.0.0.1 (2012.09.27)
   - Fixed "empty" code for script when pressing cancel or closing the GUI.

- Notepad++ v6.2
- Updated 7zip from v9.28a to v9.30a
- Updated DAT files for:
 CPS-1  : v0.146 to v0.147 (mame)
 CPS-2  : v0.146 to v0.147 (mame)
 CPS-3  : v0.146 to v0.147 (mame)
 MAME   : v0.146 to v0.147
 MODEL1 : v0.146 to v0.147 (mame)
 MODEL2 : v0.146 to v0.147 (mame)
 NAOMI  : v0.146 to v0.147 (mame)
 NEOGEO : v0.146 to v0.147 (mame)
 PGM    : v0.146 to v0.147 (mame)
 S11    : v0.146 to v0.147 (mame)
 S16    : v0.146 to v0.147 (mame)
 S18    : v0.146 to v0.147 (mame)
 S22    : v0.146 to v0.147 (mame)
 ZINC   : v0.146 to v0.147 (mame)


Version 1.13 (2012.09.01)

- Fixed some problems using CTRL+F (Toggle Fullscreen)
 You can now undo fullscreen with the same keypresses.
 F9, F10 and F11 will not undo the fullscreen mode anymore.
- Fixed Importing RomCenter DATfiles v2.00/v2.50 when header could be invalid
 ECC is now checking 'other' header parts to confirm proper RomCenter DAT file.
- Fixed DAT importing progressbar counter and 'hanging' on 99%, it wil now
 properly show the count when finished importing.
- Fixed ROM searching on web from filemenu, now working properly again with new google command.
- Fixed typo's (CTRL Mame > CLR Mame, *.ecc > *.eccDat) in all languages
- Fixed some Russian translation files (some where portuguese :S)
- Added FSUM fileparser, this parser will always use fsum to parse big files like
 CD/DVD for a platform, this prevent a chance that ECC will crash using the
 'generic parser' (based on idea of Zaxth (ecc forum member))
 - Platforms id's with the new parser:
   3do, amigacd32, cdi, cdtv, dc, fmtowns, gc, jagcd, ngcd, pcecd, ps1, ps2, ps3
   psp, sat, smcd, wii, xbox, xbox360.
- Added 4 new genres:
 - Adventure - Visual Novel
 - Adventure - Tactical RPG
 - Adventure - Survival
 - Shoot'em Up - Survival
- Added new icons and rearranged the platform and rom context menu's.
- Rearranged platform context menu for image(packs) options.
- ECC can now also import/show/handle BMP & ICO files for the rom images.
- Added RAR support for ECC, you can now also parse RAR files! (not for multirom platforms like mame)
- Disabled/Removed ROMdb functions from main ECC GUI and filemenu's.
- Disabled/Removed an old function from the platform menu to convert images from the old ECC structure.
- Removed ECC ROMdb import feature from the platform menu.
- New Feature: Added thumnailsetting 180x120 and 300x200 for ROM detailview.
- Updated EN, ES, NL, DE, FR, HU translations to v1.12
- Added platform: Pinball - Future Pinball (eccid: fp)
- Updated the readme.txt & ECC factsheet.
- Updated the DOSBOX parser, now excluding 'trash' files to maintain a better and correct CRC32.
 NOTE: The CRC32 for this platform will be / are changed after this update!
- Tool/Script updates
 - ECC Startup
   v2.4.0.0 (2012.08.20)
   - Removed first startup messageboxes and replaced them with a brand new GUI.
   v2.3.4.8 (2012.08.11)
   - Adjusted to use eccUpdate instead of ECC Live! when checking for updates.

 - eccUpdate
   v1.0.0.4 (2012.08.31)
   - Fixed to write 'update increase' and delete TEMP files after ForceReload function.
   - Fixed preventing double running of eccUpdate.
   v1.0.0.3 (2012.08.28)
   - Added feature to also remove folders from the ecc structure.
   - Added feature to restart ECC after updating even if "ForceReload" is used to reload eccUpdate.
   - Fixed ECC closing before update may not work in some cases.
   v1.0.0.2 (2012.08.26)
   - Fixed scrolling to last line in the textbox, this works better now.
   - Added new feature to force reload of eccUpdate, in case if there are new
     instructions wich the old eccUpdate cannot handle.
   v1.0.0.1 (2012.08.22)
   - Fixed linefeed in log when there are no updates available.
   - Fixed some typo's.
   - Fixed "double" logging and checking.
   v1.0.0.0 (2012.08.11)
   - Replaces ECC Live! wich was programmed in VB6, this is a ported version in Autoit3.

 - ImagePackCenter
   v2.2.0.0 (2012.08.28)
   - Added new feature to compress the images after export with 7ZIP, also added
     a feature to add the volume size of the compressed archives (if you need to
     upload to filesharing website)
   v2.1.0.5 (2012.08.25)
   - Fixed a bug causing IPC to crash when exporting ECC style.
   - Added new feature: Removing uncessary metadata (junk) like EXIF, IPTC
     and comments out of the imagefile.
     This method could save loads of unessesary MB's for the imagepacks and
     slink it nicely without loss of imagequality!, this does NOT affect your
     original imagefiles!
   - Added new thirdparty tool: Stripper.exe (www.steelbytes.com)
   v2.1.0.1 (2012.08.15)
   - Fixed typo "EmuCenter" to "Romcenter"
   v2.1.0.0 (2012.07.09)
   - Renamed eccImagePackCreator to eccImagePackCenter
   - Fixed bug in the progresslabel
   - Made some GUI adjustments and text fixes
   - Included a brand new Importer!, with this you can also IMPORT imagefiles
     into ECC! based on CRC32 (No-Intro) or NAMED (Using DAT)
   v2.0.0.5 (2012.07.08)
   - Fixed a Bug where IPC freezes when creating an imagepack.
   - Added an error handler if a file could not be copied to destination.
   - Made some GUI text adjustments.
   v2.0.0.0 (2012.07.07)
   - Total overhaul of IPC, including a brand new GUI system. You can now
     export ECC images to various imagepackcollegues like No-Intro or EmuMovies.
     You can define lots of variables to even meet your custom needs!
   - Removed the 'create imagepack' option from the TOP menu and included
     this in the platform rightclick-menu.
   v1.2.1.2 (2012.07.04)
   - Changed filename for 7zip usage due to 7zip update.


 - 3D Gallery
   v1.1.0.4 (2012.07.06)
   - Fixed broken view on startup wich sometimes occured.
   v1.1.0.3 (2012.07.04)
   - Added new gallery: flshowcarouselblack (http://www.flshow.net)
   - Added missing preview image for gallery 'tiltviewer'.

   - Resized all preview images to fit better in the gallery configuration GUI (smoother)

 - ICC ImageInject
   v1.1.0.6 (2012.07.04)
   - Fixed issue with downloading images, due to other slashes / (instead of \)
   v1.1.0.5 (2012.06.27)
   - Now uses proper ECC User folder, wich is configured in ECC.

 - DatFileUpdater
   v1.2.5.7 (2012.07.04)
   - Changed filename for 7zip usage due to 7zip update.

 - eccThirdPartyConfig
   v1.0.0.1 (2012.07.04)
   - Fixed a bug where the usersetting was't saved properly, causing ThirdPartyConfig not to work as it should.

 - eccCreateStartmenuShotcuts
   v1.0.0.2 (2012.08.11)
   - Adjusted creating of shortcuts to use eccUpdate instead of ECC Live!

 - eccScriptSystem
   v1.2.0.7 (2012.09.01)
   - Fixed selecting executable for Deamon tools.
   v1.2.0.6 (2012.07.04)
   - Changed filename for 7zip usage due to 7zip update.

 - Updated 7zip to v9.28a