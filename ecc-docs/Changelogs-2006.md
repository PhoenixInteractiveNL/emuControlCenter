## Changelogs 2006
***
Version 0.5.899 (2006.12.31)

* ROMDB-GET now search for the selected ROM! (online website)
* rearange some widgeds on the right info pane (tabbed)
** Added DATA-View to pane
** Moved esearch to pane
** Moved romdb to pane
** Moved image-handling to pane
** Added some helptexts for images and romdb
* changed split for romdb to '.'
* updated the temp-folder create
* Added some more tooltips
* Navigation area now fixed.


Version 0.5.898 (2006.12.31)

* fixed space error - emulator could not be started if spaces in filename!
* Fixed a focus bug media-edit. Now it is possible to edit metadata.
* Added some tooltips for eccdb, navigation and main rom-view!
* Rename eccdb within ecc into eccdb_EMPTY for updates!
* Added shortcuts
** a = add new roms
** e = edit media
** b = bookmark
** x = remove from eccdb
* Implement new ini "ecc_navigation.ini" only for navigation
** This ini isnt transfered into user folder for updates!
** removed all nav-elements form ecc_general.ini
* Added ecc.ex v1.3.9.2
* Try to improved gui for Resolution 1024x768 (Open Status-Detail area)
* Added new platforms
** Thomson MO5
** Sega Megadrive 32X (also remove 32x from genesis/megadrive config)
** Atari 400
** Atari 800
** Playstation 1
** Added Wonderswan nav image


Version 0.5.897 (2006.12.29)

* Center all popup/dialog-windows
* ecc-core update (new dll fixes keystroke errors)
* Added new platforms
** sega sg1000
** sega sc3000
** Splitted wonderswan to b/w and color platform

![](https://raw.githubusercontent.com/wiki/PhoenixInteractiveNL/emuControlCenter/images/img_misc_champagne.jpg)

Version 0.5.896 (2006.12.29) PUBLIC RELEASE

* Add new field to database :-)


Version 0.5.895  (2006.12.28)

* eccdb/add improvements
** implemented status-area update
* Fixed category dropdown bug - on change emu-config!
* Cleaned all platform configs


Version 0.5.894  (2006.12.23)

* eccdb webservice
** Post implemented!
** Button for ecc db transfer added
* Added license-notes
* Added file_id.diz
* Update some copyright notes


Version 0.5.893 (2006.12.18)

* Added new Folder "ecc-user-configs"
** This folder make it easier to update the ecc-system without overwriting
** user configurations every time
*** On startup, ecc checks, if this folder is available... if not, creates it
*** history.ini is autocreated in this folder (it could be deleted on errors)
*** general.ini is autocreated in this folder (it could be deleted on errors)
*** Every platform.ini changed by the user is auto transfered into this folder
*** Backups of platform.ini are created in this folder to
* Refactored some ini functions
* Added a "unset ratings"-context menu item!
* create a deploy script for some pre-release tasks
** implemented eccCore.dat for internal configurations
** writen some trash collection functions for eg bak files
* Fixed some glitches in category dropdown... not complete fixed :-(
* Fixed wrong labels in plaform-contexe-menu
* Remove some debug-outputs!
* Add some ecc_readme.txt


Version 0.5.892 (2006.12.15)

* Bold font in search box
* Added new reset reg command for ecc.exe
* Optimized ecc-configuration area
* Temporarily removed webservices


Version 0.5.891 (2006.12.13)

* Optimized categories dropdown.
* Optimize navigation
* optimized icons
* removed some option-menu-items from top menu


Version 0.5.89 (2006.12.10)

* Implemented cursor-navigation in main treeview
** Cursor-Key left/right = prev/next page
** Cursor-Key ALT+left/right = first/last page
* Implemented icons for visibility options
*** Top navigation and icons no complete connected :-) TODO!
*** Implemented
**** Navigation autoupdate
**** Navigation hide empty
**** Hide duplicate roms
**** Hide images
* Fixed a little bug in eventbox for language-edit


Version 0.5.8899 (2006.12.05)

* Finaly fixed the link to the support forum :-)
* Added new TopMenu 'Startup' for ecc.exe commandline parameter
** implemented /deskicon
** implemented /starticon
** implemented /phpversion
** implemented /verify
** implemented /reset


Version 0.5.8898 (2006.12.03)

* Fixed problem, if user folder does not exists
* Fixed problem on save-image
* Added infotext to user-folder with version, platform name aso!
** create automaticly default ecc-user folder in ecc root directory!
* Media-Info
** Changes mediaedit-popup now also update media-info area direct!
* Added support button!


Version 0.5.8897 (2006.11.29)

* implemented direct editing of meta-flags like running, trainer aso.
* fixed a litte bug in the insert metadata-function
* removed direct rating in to... now build in media-info area!
* implement auto-update-ini for emuDownloadCenter


Version 0.5.8896 (2006.11.26)

* Media-Info
** added language-flags in mediainfo area!
*** added edit language button, if no language selected!
*** Click opens media-edit!
** First step of refactoring MediaInfo-Area and MediaEdit popup!
*** Goal is direct access to change meta-data direct in MediaInfo-Area
*** Added direct access eventbox to the rating *
*** Plans for instant update of MediaInfo-Area! (some refactoring work :-))
* Fixed support-forum link
* Added eula (to be discussed!)
* Fixed a big bug in relative-path handeling.
** refactor methods to create relative paths


Version 0.5.8895 (2006.11.19)

* Size of navigation-pane now rezizeable.
* Freeform-Search
** Now search automaticlly updates the navigation (if autoupdate selected)
** Selection of another platform in navigation dont remove search word
** Wildcard * is allowed
*** Use it like this: Year 19*0 (results in 1990, 1980, 1970 aso)
** Restructured search-area.
** Reset button now also reset rating selector
** Rating-Selector activates Search-Activestate-Button
* Search selectors
** Added status colors for selector (Blue=default, green=changed)
*** Search type
*** Search operator
*** Rating
* Help Menu (TopNavigation)
** Added links to some urls like website and forum.
** Added Offline-Documentation link
*** New folder ecc-help/ with offline documentation
*** Added a documentation css style and sample-pages


Version 0.5.8894 (2006.11.15)

* Freeform-Search
** Added timeout for keystrokes for better search performace


Version 0.5.8893 (2006.11.13)

* Implemented pseudo fuzzy search
** All words are splitted at the whitespace
*** Operators
*** = direct search
*** + AND search
*** | OR search


Version 0.5.8892 (2006.11.12)

* Implemented Freeform Search selector
** No, you can also search in other fields by freeform search
*** 'Name',
*** 'Year',
*** 'Developer',
*** 'Info',
*** 'Fileextension',
*** 'EccIdent',
*** 'CRC32 Checksum',
*** 'Filename/Path',
* Implemented Rating search
** Shows all rated roms ordered by rating
* Implemented new direct media-edit bar
** Implemented direct rate


Version 0.5.8891 (2006.11.07)

* Fixed a big bug in parsing roms.


Version 0.5.889 (2006.11.05)

* Implemented functions for context menue and top menu
** Menu Shell operations
*** Implement rename file at harddisk + automatic update of paths in eccdb
*** Implement copy file at harddisk + db update
*** Implement remove file from harddisk + dbupdate
** Reparse ROM-Directory
*** Direct parse the folder of the selected rom. (if your folder has updated!)
* Fixed a bug in parser...
** if you change the platform while paring, it shows the wrong pf in popups :-)


Version 0.5.888 (2006.10.29)

* Context menu update
** Optimized structure of context menues
** Fixed problem while opening context menu
** Added rating submenu
*** Shows ratings in cell and mediainfo
*** Added a new image to show rating in cellview
** Added shell operations submenu
*** Implemented browse rom folder
* Added some new categories
* implemented a new class to handle comboboxes
* Designed/Added a new splashscreen and in program teaser image
** new "emuControlCenter - explore your ROMS faster" :-)
* Added latest version of ecc.exe (1.30)
** added a hint to get eccLive (thanks to phoenix!)
* Wrote down tonns of todos and new features, i miss at this point.
** Research in other tools for interesting features :-)
** Found many but also found, that ecc will be a cool tool :-)


Version 0.5.8879 (2006.10.24)

* Updated ecc to PHP 5.1.4


Version 0.5.8878 (2006.10.23)

* Added 512MB RAM to my computer.... ecc runs now faster :-) (768 MB)
* Not found base_path for ecc-user will set to default folder (../ecc-user/)
* implemented 'maintenance'->'Reset ecc history' to reset the history file!
* Removed the auto ecc-splashscreen on startup, if ecc history is reseted!
* Adding experimental flag exportType to ecc_general.ini
** ECC will export datfiles with extension .ecc in datfile style
** CSV will export commaseperated files for excel/openoffice
*** No import for ecc csv files implemented yet :-)
* fixed bug in create user folder
* Added new extensions to some platforms
* Added new platform "commodore vic20" (by phoenix)
* Updated images for platform "commodore c64" (by phoenix)
* Added msx nav image! (by phoenix)


Version 0.5.8877 (2006.10.16)

* finalize a extension in "add new roms" in "all found" autoupdates navigation
* Extended "organize roms" -> now uses names from metadata for orga.
* dispatcher now default off
** could be enabled [USER_SWITCHES] useExtensionDispatcher = 1
* Double fileextensions are handled be confirm-popups!
* Larger process status area
* create buggy :-( FileParserGen for genesis roms (only to validate!)


Version 0.5.8875 (2006.10.11)

* Build in fileextension to fileparser dispatcher
** dispatcher get data from header to get the right parser for rom
** Only cDispatchBin/cValidatorGen implemented this time
*** not really good implemented header check :-) (i will check phoenix infos)
*** If not valid bin for gen, print out cmd debug info and dont parse rom!
*** Better implementation in 888 ;-)


Version 0.5.887 (2006.10.08)

* Parsing: Build in extension for many platform problem finder! :-)
** Added info-popup and exclude direct at #all found
** Ask for every dup extensions for Platform parse!
*** Added platform names for better overview!
* Added missing image for msx
* removed some debug output :-)
* Added [EXPERIMENTAL] section to ecc_general.ini
** Added switch to use standard win filssystem dialogs
** This mode cant remember the last selected folder/file from histroy :-(
** win32Dialogs = [0|1]
* Added latest version of ecc.exe 1.12 (by phoenix)
* Set main window title to "emuControlCenter"
* fixed a BIG bug in show unsaved images. Now search also in emu/screenshots
* fixed bug "rebuild_user_folder" in ecc config.
* fixed bug in "Configuration" i18n-language dropdown!
* fixed missing lang "parse_rom_detail_headers"


Version 0.5.886 (2006.10.03)

* Refactoring
** Start media / os detection
** glade constructor
* Menu->options: Radios for select view-mode in romlist (Default 1)
** 1. Show all Roms
** 2. Show all available metadata
** 3. Show only roms without metadata
* Added new Platforms
** MSX (by phoenix)
** Colorgenie (by phoenix)


Version 0.5.885 (2006.10.01)

* Menu options -> show only roms with metadata
** better overview of missing metadata
* Added show_media_pp to ecc_general.ini to change the "media per page" value
* refactoring release
** Added new Singleton FACTORY and refactor manager-names
** cleanup manager-folder
* I18N
** Language dropdowns translatable
* Removed save to history.ini for "show only avalable roms"
* Listviews Roms/Platform
** add background color
** add hilight color
* Added experimental and buggy "fast_list_refresh" to ecc_general.ini
* Added experimental webservice for meta/get meta/send (not really working :-))


Version 0.5.884 (2006.09.27)

* Added color-swap tho the navigation and main list
* escape & under dos (^&)
* Added window title status "emuControlCenter vX.X build XXX (state)"
* new startup bat
** ecc.bat
** ecc-dev.bat (shows debug output)
* fixed translation in context menu
* Remove save to history for hide images


Version 0.5.883 (2006.09.25)

* Platform category could be configured in frontend!
* New Platforms added (by phoenix)
** Coleco Vision
** GCE Vectrex
** Mattel Intellivision
* Added new version of platform/media images (by phoenix)


Version 0.5.882 (2006.09.24)

* Add Platform category dropdown.
** Categories could be assigned free in ecc_platform_xxx.ini
** e.g. category = Console
** Shows only Platforms for the selected Category
* Imagepopup
** remove images in contex-menu


Version 0.5.881 (2006.09.18)

* imagepopup
** added imageselect by preview image in scrollable area! :) Cool! :-)


Version 0.5.880 (2006.09.17)

* fixed bug in saved image handling. (<0.5.88 could not find saved images)
* Preview image clickable. Opens Imagepopup.
* Mediaedit-popup
** Mot longer modal... you can get the next rom to edit from the romlist.
** Added Filename/Packed Filename to popup!
* Imagepopup
** Popup now has autofocus. A click to the romlist updates the images in popup!
** Added Imageposition label
** Buttons a now only sensitive if needed
** Imagepopup now direct show selected image (position)!
* "Show all images" added to contextmenu ROMS
* Platform navigation scrollbars added
* i18n internationalization
** ecc-config dropdown for changing languages
** i18n parser statusarea
* Platform images / emuControlCenter-Logo clickable


Version 0.5.879 (2006.09.13)

* Added gif support
* saving title screens as ecc_crc32_ingame_title.ext
* first version of image-popup for full images.


Version 0.5.878 (2006.09.11)

* fixed bugs:
** problem with escaped string in getEightDotThreePath()
** writing NULL into db if a ecc-datfile is imported!


Version 0.5.865/0.5.866/0.5.877 (2006.09.10)

* ecc-configuration added
** Set your user folder
*** create, if not exists
*** create all ecc-system-subfolders in user-folder
** Activate/Deactivate Platforms from gui
** Set your datfile-author informations (header for your exports!)
* i18n Internationalization/Translation
** ecc_general.ini flag language = de
** Location ecc-system/i18n/language/i18n_popups.php (lang=language 2chars eg. de,en,nl)
** I step one, all dialogs and context-menues are translatable.
*** Done: Context menu Platformnavigation
*** Done: Context menu Romlist
*** Done: All Confirm/Info/File-Pathchooser-Dialogs!
** In step to, all Strings (also glade strings) are translatable via gettext!
* Start Media: add error-popup if emu is missing or wrong!
* navigation now is automaticlly sorted
* Added more image-slots for coverart and booklets
* Added support for Atari Jaguar. [by phoenix]
* Fix a bug in media-edit.
* set eccident for jaguar=jag, supervision=svn
* Added shortcuts for options and rom-actions (see main menu)
* Optimized platform navigation contextMenu
* media-info
** added archive - If rom is packed, the path of the packed file is shown.
** added location - path to the rom-/packed-file
* Added 8.3 Filename-Support for Watara-Supervision emulator
* Emus without commandline-support could be used using the RomRunner-PT [phoenix]


![](https://raw.githubusercontent.com/wiki/PhoenixInteractiveNL/emuControlCenter/images/img_misc_betatester.png)

Version 0.5.085 (2006.08.24) RELEASE TO FIRST BETA-TESTER

* Save last selected platform to history
* Reorganize Roms by categories implemented
* MenuBar
** All options organized in top menubar
* Maintenance
** Remove duplicate roms from db implemented
** DB Vacuum -> Vacuum sqlite database
** ecc-userfolder and subfolder
*** ecc creates folders automaticly on demand
*** ecc also create missing user-folder
*** subfolder could be configured in ecc_general.ini
* Absolute/Relative path convertion
** ecc create relative paths, if data is within the root dir of ecc
*** emulators
*** roms/media
** reorganize also creates relative paths, if possible
* ImageProcessing
** jpeg-compression could be configuredin ecc_general.ini
* File-Filter (File-Dialogs)
** Filefilter for Romcenter Datfiles (*.dat)
** Filefilter for emuControlCenter Datfiles (*.ecc)
* Spellcheck ;-)
* Tooltips wordings fixed :-)
* Images
** More ingame screenshots possible
*** ingame_start = startscreen of a game
*** ingame_play_1 = first ingame playing screenshot
*** ingame_play_2 = second ingame playing screenshot
*** ingame_play_3 = third ingame playing screenshot
* Added platform "Watara Supervision" [by phoenix]



Version 0.5.08  (2006.07.25)

** implemented Import of emuControlCenter- and Romcenter-Datfiles
*** support for importing Romcenter-Datfiles
**** Support 2.00/2.50 Dats with assigned fileextensions.
**** Dats without fileextensions couldn?t supported by ecc :-(
**** Dat-Stripper for Romcenter-Dats implemented
***** Clean up names from data like (PD) and [T-*]
***** Auto-Fill ecc?s extended meta-informations from stripped data
****** Languages
****** Freeware/PD
****** Year
****** Running (gooddump)
***** All stripped data is collected to field info in database!
***** Regular expressions could be configured in conf/ecc_dat_stripper.php
***** Dat-Stripper could be deactivated in conf/ecc_general.ini
** datfile-export
*** eSearch-Export
*** now you can use the eSearch-options to limit your export-data!
*** meta-informations like languages, status could be removed from database
*** data could be automaticlly backuped by ecc
** implemented edit platform.ini
*** Edit title for navigation now possible in gui
*** Edit paths and assign emulator now possible in gui
** implemented new tab platform informations in main-view
*** Show some facts about the platform like name, manufacurer, history, links
*** own ini-file (infos/ecc_platform_a2600_info.ini)
*** maybe used for categories like in MESS (show only platforms by atari)
** implemented Active [AUTOUPDATE|NORMAL] Navigation
*** navigation will only show the real result-counts of a search
*** combination with "Active [HIDE|SHOW] platforms w/o media" possible!
** implemented Active [HIDE|SHOW] platforms w/o media.
** clear dat database added
** optimized "optimize languages", now using multi-deletes
** added platforms
** Optimize null-data ordering, if there are no meta-infos for a file!
** Changed database
*** filedata = fdata
*** metadata = mdata
*** metadata_languages = mdata_languages
*** Atari 2600
*** Atari 5200
*** Atari 7800


Version 0.5.07  (2006.07.16)

** Status area
*** progressbar
*** show status of export, import and parsing
*** could be hidden
*** processes could be cancled
** Extended Search
*** result could be set to option = *|no|no+?|yes|yes+?|?
*** is saved to ecc_histroy.ini
*** could be hidden
** Image-Processing
*** New image-system improves the speed of ecc
*** select start/running ingame-shots in main-view from dropdown
*** show only saved or only unsaved images
** Add Platform-Nav context menu
** Inline-Help system restructured!


Version 0.5.06  (2006.07.10)

** Add Media-History-Button
*** Shows all Media, you?ve started in the last time.
*** Ordered by last launchtime!
*** Count editeable in ecc.ini ([USER_SWITCHES] media_history = 50)
** Image Toggle
*** Now you can toggle images on/off. (saved to history.ini)
** Cellview
*** Platform-Ident image added for better overview in all-mode.
*** Add more important infos from ecc-dat to cell
** Media-Edit
*** Now, edit Meta-Data is allowed from Bookmarks and History
*** Now, a media could be started from media-edit window
*** New Comboboxes for all options
*** category has now a multi-wrap-dropdown
*** languages-edit added
** Add Languages
*** Add Lanugage-Search
**** Now you can search for media in specified language
*** Add Languages-Edit
**** Languages could be added in media-edit popup
**** languages will shown in cellview and media-info
**** languages are exported and imported pipe-seperated
***** e.g. d64;Bubble Bobble;.d64;8727EFCD;1;0;8;1;0;0;2;0;1987;0;2;E|F|S;;;;;;#
***** e.g. English, French, Spanish
** New Languages
*** Based on data extracted form datfiles :-)
** New Categories
*** based on statistics "Genre Stats" of GBA-Lister
*** http://gbalister.emubase.de/show_stats_2.de.html
** Gui
*** Some improvements.
*** new background-color :-)
*** New navigation-images.
*** New images for plattform infos
** New Function for dropdowns.
*** now all dropdowns are created on the fly.
*** IndexedCombobox create simple or not so simple (image/wrapped) dropdowns.
** Add confirm-popup for "remove from ecc-db"
** DEVEL-Dat-Importer
*** function to get informations from dat to fill extended ecc-dat-dat
**** languages
**** pd / freeware (PD)
**** Translations (T+) (T-)
*** concat dat-information strings for more predefined meta-informations.
** Add Splash-Screen at startup
*** only shown first time, you open ecc (saved to history.ini)


Version 0.5.05  (2006.07.04)

** new user-folder structure
*** now relative and absolut path could be set in ecc.ini
*** now every media have his own folder with subfolders eg. images and export
**** better structure for sharing the media-data with other ecc user
**** first step for a planned user-folder to zip backup.
*** refactoring image function
**** save images now use new user-folder structure
**** fixed little bug in save image that overwrites "image 001" sometimes. :-)
** DatfileExport
*** insert credits from ecc.ini (author, web, email, comment)
*** save export now use new user-folder structure


Version 0.5.04  (2006.07.03)

** ini-manager
*** complete refactored
*** save user-selections for the next ecc-session to history.ini
**** navigation: save last selected plattform
**** parser: save last parsed folder
**** importer: save last selected eccDatFile
** Import / Export
*** optimize import. Now direct export from query without data-array
** sql
*** optimize queries
*** type-hinting and escaping.
** media-edit win
*** ; in user-input not allowed. Reason Import/Export and File-Rename-Option
** parser popup
*** file_lister shows found (unpacked and packed) by extension
*** parser shows new and changed media
** Internal windows
*** media-edit popup changed to splash-screen mode
*** parser popup changed to splash-screen mode
** temp. removed md5 support. ecc dont need md5 by now :-)


Version 0.5.03  (2006.07.01)

** eccini: refactored
*** [NAVIGATION] now creates the main-navigation of ecc
*** comments add for all sections
*** add new extensions to [FILE_PARSER] section
*** add "all found" to navigation.
** DatfileImport:
*** ecc-datFile import implemented
*** datFileImporter.php for romCenter imports. (DEVEL)
**** refreshed all mdata_ext_game fileinformations
** MediaMaintenance:
*** "all found" clear/optimize now have impact for all records.
*** function optimize db also optimize bookmarks
*** function clear db also optimize bookmarks
** internal refactoring manager


Version 0.5.02   (2006.06.20)

** MediaMaintenance
*** add function optimize db
*** add function clear db
** MediaEdit
*** add usk (altersfreigabe)
*** add multiplayer
*** add netplay
** ContextMenu
*** item remove from eccdb


Version 0.5.01  (2006.05.18)

* initial release