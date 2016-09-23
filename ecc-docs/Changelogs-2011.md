## Changelogs 2011
***
Version 1.11 (2012.02.18)

- Fixed a bug where the IPC topmenu translation didn't work.
- Fixed an issue where DATfiles created by ECC still had the 'v0.98' string.
- Fixed a problem where ECC could not import arcade ROMS, because the DATfiles didn't had any header.
- Fixed ECC DATfile importing.
- Fixed the DAT file export and import of the Rom DUMPTYPE.
- Fixed ECC recognizing the new MAME NeoGeo rom sets (lordashram)
- Fixed a bug causing a crash when using DAT import from clrmamepro. (BadWolf63)
- Added Developer tools for AutoIt3 wich are selectable through the ECC top menu.
- Added regions for roms: Australia, USA-Europe, USA-Japan
- Updated the HELP index file, also added Xpadder link
- Changed the "readme_thanks.txt" contents properly to the new situation in the DATfolder.
- Removed the "convert images" option from the top menu "tools", this was an old script and was used 3 years back to convert old-style images
 to the new way that ecc stores them now.

- Added ECC pro scripting
 - Included 'WinAPIEx.au3' if you need usage of scripting on API level, you can include and use this for your ECC scripts!
- Added ECC advanced scripting
 - Included all additional AU3 'help' files and UDF's for advanced scripting, like GUI's etc., located in 'ecc-core\thirdparty\autoit\include'
 - Included HELP file for UDF's used in scripting.
 - This is also the preperation pack to port the tools to Autoit3 (like eccTheme, eccBugreport, eccLive) wich need these files.

- Emulator Script updates
 - Added scripts for SummVM:
   - Residual v1.0.0.1
   - ScummVM-SP v1.1.0.1
 - Updated script for Vic20 - Xvic script v2.1.0.0 (Phoenix)
   - Fixed: The script can now determine the right memorysetting when the romfile is unpacked to a TEMP folder (using Prginfo).
   - Fixed: Now displays correctly the path to 'Prginfo.exe' when this program is not found.
   - Improved: The versionnumer of 'Prginfo.exe' in the script isn't needed anymore (less script maintenance).
 - Updated the 'ep128emu' script for the Enterprise platform v1.0.0.4 BETA (Vicman)
 - Updated the DCEXEL script for Excel 100 to v1.0.0.2 (Vicman)
   - Now working with all extensions.

- Tools / Script updates
  - Ported 'eccTheme' (VB6 coded) to 'GTK Theme Select' (ECC-GTKTS)
    wich is programmed in Autoit3 in a opensourceway, and also does not need OCX files.
  - Ported 'eccBugreport' (VB6 coded) to 'eccDiagnostics'
    wich is programmed in Autoit3 in a opensourceway, and also does not need OCX files.

  - ECC Startup
    v2.3.4.1
    - Added a system that will run DFU when a temp DATfile is found, to update the user DATS on startup.
    v2.3.4.2
    - Adjusted to send bugreports made with eccDiagnostics.

 - Imagepackcreator (IPC)
   v1.2.1.0 (2010.12.24)
   - Added program exit when there are no images found for the selected plaform.
   - Converted traytips to tooltips, wich shows information more efficient.

 - eccScriptSystem
   v1.2.0.5
   - Added '$eccFileRomRegion' to use the roms region setting in a script (given as english language)
     Possible regions: Asia, Brazil, Europe, Hispanic, Japan, USA, World, Australia, USA-Europe, USA-Japan
   - Added Manual Datfile Updater (DFU) v1.0.0.0 into the ECC top menu, this way you can update your DATfiles manually if you need too.
   v1.2.0.0
   - Added 8.3 Dosfile names on unpacked ROM files when 8.3 is enabled in ECC config! (only affects the FILEname of the ROM)
   v1.1.0.6
   - Added an extra 'flag' in the function 'EmuWindowControl' to choose if the emulator should startup too.

 - Datfile Updater (DFU)
    v1.2.5.5 (2012.02.26)
   - Fixed a critical issue where ECC-DFU would not create DAT files properly when there are spaces in the pathnames!
   v1.2.5.2 (2011.11.20)
   - Fixed date problem that is stored in the headers
   - Increased the need of available MB to update DATS, from 50 > 100 MB
   v1.2.5.1 (2011.05.01)
   - Added MAME fileversion to DATfile creation message.
   - Changed the forum link inside the DAT headers to 'mamedev.org/forum.html'
   v1.2.5.0 (2011.04.24)
   - Improved the DATfile headers with right information.
   - Added a system that will use the temp DATfile when found and automaticly update the user dats.
   v1.2.0.0 (2011.04.24)
   - Added support for the 64-Bit and i868 optimized version of MAME (executable selection).
   - Rounded the number of space free needed in MB (message) when you have not enough (also upped to 50MB).
   - Added check if the executable really is an 'official' MAME file.
   - Now writing the original MAME DATE inside the header, instead of the date on the users computer.
   - Cleaned up the code in DFU, made some functions etc.
   v1.1.0.0 (2010.11.30)
   - Fixed a problem where ECC could not import arcade ROMS, because the DATfiles didn't had any header.

 - Updated the eccSCript template file which shows how to include extra files.
 - Updated GTK Theme Select icon (instead of only 16x16)


- Thirdparty updates
 - Updated Notepad++ v5.8.0 to v5.9.8
 - Updated 7-zip v4.65 (2009-02-03) to v9.20 (2010-11-18)
 - Updated Autoit to 3.3.8.1 (2012-01-29)
 - Added 3rdparty tool: DatUtil v2.46 - 13/04/2009, Written by Logiqx (http://www.logiqx.com)
 - Added Koda v1.7.3.0 build 252 (http://koda.darkhost.ru)
   - Koda Language file's (localized downloadable)
   - Koda LanguageKit


- Added Developer tools for ECC wich are selectable through the ECC top menu.
 - SQLite Browser v1.1
 - Glade GUI Editor v3.4.3

- DATfile updates
 - CPS-1  : v2010.05.18 to mame v0.145
 - CPS-2  : v2010.05.18 to mame v0.145
 - CPS-3  : v2009.01.06 to mame v0.145
 - MAME   : v0.138 to v0.145
 - MODEL1 : from mame v0.138 to v0.145
 - MODEL2 : from mame v0.138 to v0.145
 - NAOMI  : from mame v0.138 to v0.145
 - NEOGEO : v2009.11.03 to mame v1.045
 - PGM    : v2009.10.16 to mame v1.045
 - S11    : from mame v0.137 to v0.145
 - S16    : from mame v0.135 to v0.145
 - S18    : from mame v0.138 to v0.145
 - S22    : from mame v0.138 to v0.145
 - ZINC   : from mame v0.138 to v0.145
 - SVM    : v0.99.1.5 to v0.99.1.7 (Gruby)