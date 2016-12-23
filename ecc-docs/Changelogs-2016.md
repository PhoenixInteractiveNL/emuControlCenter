## Changelogs 2016
***
![](https://raw.githubusercontent.com/wiki/PhoenixInteractiveNL/emuControlCenter/images/edc_banner.png)

ECC v1.22 (2016-12-23)

- ECC Core
  - ECC License now GPLv3
  - Fixed some missing icons in the Platform and Rom sub menu system.
  - Fixed an "freeze" issue with datfile importing if the path did not exist.
  - Fixed issue where you could not import v1.1 or older ECC DAT files properly.
  - Fixed an issue where calling the ABOUT screen made ECC close.
  - Fixed startup of some GUI's to CENTER screen.
  - Fixed some language lines in 'i18n_meta.php' files to be translated.
  - Fixed some issues & link in the offline helpfiles.
  - Updated PHP-GTK Glade3 GUI from v3.4.3 to v3.6.1.
  - Reworked all GLADE GUI's due to update, some icons have been changed/added.
  - Rearranged TOP menu icons.
  - Added new theme icons to the TOP menu, no more stock PHP-GTK icons.
  - Added proper deactivation on menu items when ALL PLATFORMS has beem selected.
  - Cleaned up code in GUI and php code for ROMdb.
  - Removed Xpadder and Xpadder options, the freeware v5.3 crashes on Windows 8/10.
  - New Option to select a external Joystick emulator yourself now.
  - Shipping WorldOfJoysticks v1.57 standard edition (worldofjoysticks.com) with ECC.
  - Updated ES language to ECC v1.21 [Jarlaxe]
  - Removed unique CID structure in ECC, now implemented UID in autoit variables.
  - Updated ECC EMU INI files in "ecc-system\system" these are now exported by EDC.
- EMUDOWNLOADCENTER
  - Introducing the new EDC module for ECC, with this you can download, install and
    configure emulators with a few clicks!
  - Project page is on GitHub: https://github.com/PhoenixInteractiveNL/emuDownloadCenter/wiki
  - This project had been started in OCT-2016, and with help of 'Shando' we have a lift-off!
  - You can help collecting and adding more platforms and emulators, it's all open source!
  - You can request specific EDC data export to a file, but you have to work for it ;-)
- ECC Documentation
  - Now using GitHub wiki markdown documentation, this way it's maintained better!
  - Made a Github wiki markdown to HMTL converter to generate offline documentation!
- ECC Update
  - v1.2.0.3
    - Removed redundant CID checks.
  - v1.2.0.2 (2016.09.15)
    - Fixed a bug where eccUpdate! could not find the last update from the server correctly. 
- eccThirdPartyConfig
  - v1.0.0.3 (2016.09.25)
    - Removed Xpadder config.
- eccScriptSystem
  - v1.3.0.1 (2016.09.25)
    - Removed Xpadder variable, added JoyEmulator variable.
- eccToolVariables
  - v1.0.2.0
    - Added EDC variables.
  - v1.0.1.0
    - Added UID string.
  - v1.0.0.9 (2016.09.25)
    - Removed Xpadder variable, added JoyEmulator variable.
- DatFileUpdater
  - v1.3.0.1
    - Removed DATE function and string, not used in newer MAME dats anymore
    - Fixed NeoGeo driver from 'neodrvr.cpp' to 'neodriv.hxx'
- MobyGamesImporter
  - Updated the emuMoviesDownloaderlist from 2012-10-10 to 2016-11-05.
- emuMoviesdownloader
  - v1.2.1.3
    - Removed redundant CID checks.
- iccImageInject
  - v1.1.0.9
    - Adjusted CID checks to UID.
- eccDiagnostics
  - v1.0.0.4
    - Adjusted CID checks to UID.
- Thirdparty updates
  - 7-zip v16.02 to v16.04
  - Notepad++ v6.9.2 to v7.2.2
- Updated DAT files for:
 - CPS-1  : v0.178 to v0.180 (mame)
 - CPS-2  : v0.178 to v0.180 (mame)
 - CPS-3  : v0.178 to v0.180 (mame)
 - MAME   : v0.178 to v0.180 (mame)
 - MODEL1 : v0.178 to v0.180 (mame)
 - MODEL2 : v0.178 to v0.180 (mame)
 - NAOMI  : v0.178 to v0.180 (mame)
 - NEOGEO : v0.178 to v0.180 (mame)
 - PGM    : v0.178 to v0.180 (mame)
 - S11    : v0.178 to v0.180 (mame)
 - S16    : v0.178 to v0.180 (mame)
 - S18    : v0.178 to v0.180 (mame)
 - S22    : v0.178 to v0.180 (mame)
 - ZINC   : v0.178 to v0.180 (mame)

***
![](https://raw.githubusercontent.com/wiki/PhoenixInteractiveNL/emuControlCenter/images/img_misc_source_files.png)

ECC v1.21 (2016-09-14)

- ECC SOURCE files released!, they include:
  - KODA AutoIt3 GUI's (KXF)
  - Photoshop images (PSD)
  - Fonts (TTF)
  - Scalable Vector Graphics (SVG)
  Files are in the 'ecc-source' folder.
- ECC CORE
  - Updated links in the GUI to the new ECC website.
  - Updated the splashscreen image.
  - Adjusted all links to the new ECC homepage:
    - https://github.com/PhoenixInteractiveNL/emuControlCenter/wiki
  - Reworked configuration GUI, placed options together where needed, deleted old options and TABS.
  - Selectable ECC DATABASE path!, put your database on a central location so every computer
    in a network can run the same data!
    - Some notes setting and using the ECC DATABASE path:
      - With manual input always use a ending slash \.
      - Network paths are not listed, but you can type them manually like: \\COMPUTER\PATH\.
      - Wrong pathnames or no rights to write will reset the DB tot he default "ecc-system\database" location.
      - When configuring a location of a existing ECC configuration/database, you have to copy the database
        manually to the new location.
      - You may experience slow response form ECC when you store the datbaase on a network path.
      - ECC is not tested to run from multiple computers with the same database,
        the database could be "in use by another application" let me know if it works!
  - Added a new PHP class "ecc-system\manager\cIniFileRegular.php" to read and write INI files easily.
  - Database "empty" is now the updated version (save some scripts to be executed everytime)
  - Added some more translations to be made in the GUI.
  - Fixed link to TAB help on mainscreen to readme.md.
  - Manager: ecccli_datFileImporter.php, Fixed database fixed location, now reading ecc settings.
  - Fixed and added function to start SQlite browser without the autoit3 script!
  - Added 3 new metadata fields to ECC database and GUI, also being exported from and imported to ECC.
    - Perspective, Visual, Description
  - Some Code and GUI cleanup.  
- ECC THEMES
  - Added loading aimation for theme: afterhours, miss-kit-purple
  - Removed incomplete themes: miss-kit-bubblegum, green-grass, nature-full-green (only on fresh installations)
- ECC DOCS
  - Placed Autoit and Xpadder docs in their respective folders now.
  - Updated Autoit3 Documentation.
- ECC Startup
  - v3.0.0.1
    - Minor GUI adjustement for removed checkbox.
	- Fixed unpacking DAT files with spaces in the filenames.
  - v3.0.0.2
	- Using animated GIF instead of AVI for loading animations, you can make you own easily now!
      You can find instructions at emuControlCenter WIKI on GitHub.
- ECC 3D Gallery
  - v1.2.0.0
    - Adjusted variable to fetch the proper websitelink from toolvariables.au3
- ECC CreateStartmenuShotcuts
  - v1.0.0.4
    - Now fetching website link from toolvariables.au3
- ECC Update
  - v1.2.0.1
    - Now fetching website link from toolvariables.au3
- 3RD Party updates
  - Mplayer Redxii-SVN-r37153-4.8.2 to Redxii-SVN-r37871-4.9.3 (i686)
- ECC Tool variables
  - v1.0.0.8
    - Added new variables for eccdb, now reading ecc settings.
	- Fixed an issue where the database could not be found at the "default" location.
 - Mobygames importer
    - v1.2.0.0
      - Added new fields to fill: Perspective, Visual
      - Description now in the META data location (instead of userdata)
	
- Scripts
  - Commodore Amiga: Merged the eccScript with the getgemusconfig script, placed
    the Amiga Gamebase inside the ecc-system\datfiles folder.

- DAT files updates
  - Amiga Gamebase v1.6 to v2.0

***
![](https://raw.githubusercontent.com/wiki/PhoenixInteractiveNL/emuControlCenter/images/ecc_splashscreen_120.png)

Version 1.20 (2016.08.10) THE GITHUB RELEASE

This GITHUB RELEASE means ECC is 'Jailbreaked' in some sort of kind to remove
limitations and improve it further with the rest of the world ;-)

- CORE
 - ECC.EXE source is FREE/ADDED.
 - ECCCORE is FREE/ADDED.
 - ecc-system\manager\cImportDatControlMame.php
   - MAME DAT headers have changed from 'game (' to 'machine (' , therefore adjusted the code to find headers.
 - ecc-system\manager\cGuiHelper.php
   - Removed version tags and time stamps from TXT and HTML files in the system userfolders.
 - ecc-system\manger\cValidator.php
   - Removed coding and encryption of ECC CORE settings (before ecccore.dat)
     ECC now loads in the plain PHP settings from ecccore.php.
 - New startup sound added.
 - Platform generated images and user folders are already generated, so no extra waiting to startup ECC!

- ECC Startup
 - v3.0.0.0 (2014.08.10)
   - Made opensource witch can be manually compiled to ecc.exe.
   - Added function to unzip ECC DAT files at first runtime, to prevent big DAT files hosted on Github.
   - Removed send bugreport function from startup screen.

- EmuMoviesDownloader (EMD)
 - v1.2.1.2 (2014.06.24)
   - Fixed & Updated encryption en decryption algorithm due to function break in
     the new autoit version _StringEncrypt has been replaced with _Crypt_EncryptData.
   - The account log-in data has to be re-entered due to encryption change!

- ECC Tool Variables
 - v1.0.0.6 (2014.06.24)
   - Updated allround variables for EmuMovies downloader (EMD)

- DatFileUpdater (DFU)
 - v1.3.0.0 (2016.08.10)
   - Added a feature to unpack 7z files DAT files once on first start due to Github filesize limit.
   - Updated and fixed platform listings to grab (.c to .cpp) MAME 0.17x+? to create CLRMAME DAT files.
   - Removed DAT dates, seems not te be listed in official MAME DAT anymore.
   - Fixed creating always the 'backup' folder, now it will be created only when making a backup.
   - Fixed NeoGeo platform DAT generation (seems to be neodriv.hxx?)
   - Fixed DAT version find from MAME DAT manual update.
   - Upped required space to 150MB when doing a manual MAME DAT update.
   - Removed check for ECC executable.

- eccUpdate
 - 1.2.0.0 (2016.08.10)
   - Now using Github to download updates instead of ecc-update server.

- 3RD party updates:
 - 7zip v9.32 to v16.02 (2016-05-21)
 - Autoit v3.3.12.0 to v3.3.14.2 (2015-09-18)
 - Notepad++ v6.6.6 to v6.9.2 (2016-05-18)

- Added file extensions
 - Atari ST: IFC
 - WiiU: RPX, WUD
 - XBox: XBE
 - XBox 360: XEX

- Updated DAT files for:
 - CPS-1  : v0.159 to v0.176 (mame)
 - CPS-2  : v0.159 to v0.176 (mame)
 - CPS-3  : v0.159 to v0.176 (mame)
 - MAME   : v0.159 to v0.176
 - MODEL1 : v0.159 to v0.176 (mame)
 - MODEL2 : v0.159 to v0.176 (mame)
 - NAOMI  : v0.159 to v0.176 (mame)
 - NEOGEO : v0.159 to v0.176 (mame)
 - PGM    : v0.159 to v0.176 (mame)
 - S11    : v0.159 to v0.176 (mame)
 - S16    : v0.159 to v0.176 (mame)
 - S18    : v0.159 to v0.176 (mame)
 - S22    : v0.159 to v0.176 (mame)
 - ZINC   : v0.159 to v0.176 (mame)
