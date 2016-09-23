## Changelog ECC Startup
***
v3.0.0.2 (ECC v1.21)
- Using animated GIF instead of AVI for loading animations, you can make you own easily now! You can find instructions at emuControlCenter WIKI on GitHub.

v3.0.0.1 (ECC v1.21)
- Minor GUI adjustement for removed checkbox.
- Fixed unpacking DAT files with spaces in the filenames.

v3.0.0.0 (2014.08.10)
- Made opensource witch can be manually compiled to ecc.exe.
- Added function to unzip ECC DAT files at first runtime, to prevent big DAT files hosted on Github.
- Removed send bugreport function from startup screen.

v2.4.1.2 (2014.05.12)
- Fixed a crash if ECC is reloading on with 'empty unbpack folder' setting on.
- Fixed website link in exe description.
- Fixed fullscreen on startup, now true fullscreen (maximized)
- Removed 'always on-top' setting of the splashscreen.

v2.4.1.1 (2014.04.11)
- Fixed a bug where ecc could not detect the proper windows version to set
 compatibility for xpadder, now it will always write the REG key.

v2.4.1.0 (2014.04.09)
- Added code to install a "SIDE" update with 7zip, for example to update Autoit wich
 eccUpdate cannot do, because of "file in use" while running the script.
- Compiled with new Autoit v3.3.10.2, may return a false positive in some virusscanners!

v2.4.0.2 (2013.11.28)
- Fixed a bug where language settings for ecc startup aren't loaded properly.

v2.4.0.1 (2012.12.31)
- Fixed a problem where the ECC version isn't generated at first startup.
- Adjusted file info's/details in ECC executable properties.

v2.4.0.0 (2012.08.20)
- Removed first startup messageboxes and replaced them with a brand new GUI.

v2.3.4.8 (2012.08.11)
- Adjusted to use eccUpdate instead of ECC Live! when checking for updates.

v2.3.4.7 (2012.06.17)
- Changed bugreport settings due to new server software installation.

v2.3.4.6 (2012.05.13)
- Outsourced 3rdparty config from ECC.EXE (ecc-core\tools\eccThirdPartyConfig.au3).
- Now also configuring KODA language to selected ECC language (when found).

v2.3.4.5 (2012.05.05)
- Added feature on first time startup to select creating startmenu shortcuts or not.

v2.3.4.4 (2012.03.20)
- Fixed ECC loading (ajax loader), AVI working again, this was broken due to Autoit3 update.

v.3.4.3 (2012.02.16)
- Fixed an issue where ECC would not give proper information when '/getdriveinfo' is used.

v2.3.4.2 (2012.02.16)
- Adjusted to send bugreports made with eccDiagnostics.

v2.3.4.1 (2011.04.24)
- Added a system that will run DFU when a temp DATfile is found,
to update the user DATS on startup.

v2.3.4.0 (2009.06.29)
- ECC Startup now also maintains the emulatorlist, stored in the 'ecc_navigation.ini'
file automatically, based on if these emulator files exist:
> ecc_[eccid]_system.ini
> ecc_[eccid]_user.ini
This way its more like a pluginnable interface for new platforms, and also prevents
overwriting them with newer or other 'user' packages.

v2.3.3.9 (2010.03.29)
- Increased the width of the splashscreen label a littlebit, so that more text
can be shown.

v2.3.3.8 (2009.11.20)
- Fixed a bug where unpacked zip files where not deleted due to update 00411 wich
has a newer beta compile of autoit 3.3.1.5 the function 'Opt("OnExitFunc")'
doesn't work anymore, so i have found another solution, that does work!
- Fixed a bug where unpacked files should be deleted if the user-folder is not
the default "ecc-user" location.

v2.3.3.7 (2009.11.11)
- Added a function that configures Xpadder for Windows 7, so that no error occurs
when Xpadder is started, by writing a XPSP3 compatibility mode into the windows
registery.

v2.3.3.6 (2009.11.08)
- Added a function to delete the 'ecc-user\#_AUTO_UNPACKED' folder on ECC exit.
- This feature is only active in ECC v1.1 WIP or above!

v2.3.3.5 (2009.09.18)
- Fixed a possible problem to register the OCX files, now using the full path
and using quotes to register an OCX with 'regsvr32'.

v2.3.3.4 (2009.09.18)
- Fixed a problem with creating userfolders at the right location, now the userfolder
location is read from the ecc ini file.
- Fixed a problem with an infinite loop if the userfolder doesn't exist.

v2.3.3.3 (2009.08.04)
- Xpadder now also appears in the language wich is chosen in ECC.

v2.3.3.2 (2009.07.07)
- Possible fix for ECC splashscreen starting/staying in minimized mode #2.
- Improved ECC Bugreporting, now the bugreports are being send right away,
instead on next startup.

v2.3.3.1 (2009.07.07)
- Possible fix for ECC splashscreen starting/staying in minimized mode.
- Added ECC 1005 error, which shows the PHP error.log in a GUI when an
error has occurred.

v2.3.3.0 (2009.06.29)
- Improved they way if an (PHP) error occurred with ECC.
- The GUI splashscreen will be unloaded fist before the messagebox will be shown.
(the messagebox was sometimes behind the GUI, so it could hardly be clicked)
- When an PHP error has occured (which writes an error.log file), ECC startup will
immediatly quit. (instead of waiting a minute and nagging you with the
waiting splascreen)

v2.3.2.9 (2009.06.13)
- Added a PNG border to the splashscreen, for some shade (so it doesn't look flat)
- When reloading ECC the (reloading)splashscreen now also fades.
- Fixed a bug where the 'reloading' string wasn't shown properly when ecc was reloading.

v2.3.2.7 (2009.03.27)
- Improved language 'switching' for thirdparty tools, in the beginning only on
firt start the language for thirparty tools where configured, now when changing
the language inside ECC, the thirdparty tools will also be adjusted ;-)
- Renamed the list 'ecc_ThirdPartyConversionList' to 'ecc_ThirdPartyLanguageList'

v2.3.2.5 (2009.02.15)
- Added a 'GetDriveInfo' parameter for drive information
to let ECC give better support for removable drives! 

v2.3.2.3 (2009.01.26)
- ECC starts in windowed mode when the usable width for ECC < 1000 pixels.
- when < 1000 pixels ECC may experience glitches in the GUI.
- also a messagebox is added to inform the user. (one-time message)
- Fixed the Greek GUI language. (by putting the INI in unicode)

v2.3.2.2 (2009.01.19)
- Fixed AutoIT Error: 'Line -1, Variable used without being declared' on
other systems than 2000/XP/Vista, like: Windows Server 2008
- Fixed Notepad++ auto-language if 'ThirdPartyConversionList.ini' is not available.

v2.3.2.1 (2009.01.18)
- Changed the location of the thirdparty language conversion list.

v2.3.2.0 (2009.01.17)
- Fixed splashscreen fading not working in Vista
- Added automatic language configuration of thirdparty tools.

v2.3.1.2 (2008.11.27)
- Fixed a small bug where ECC startup did create the userfolders every time when
one or more platforms were disabled.

v2.3.1.1 (2008.11.25)
- ECC Startup will now scan your userfolder and create userfolders if necessary
and if the platform is set active!, this way when new platforms are inserted,
the userfolders for those added platforms will be automaticly created!
(thanks to Andreas for the 'create userfolder' implementation!)

v2.3.1.0 (2008.11.13)
- Made the splashscreen configurable & customizable
- You can edit ecc-themes\[THEMENAME]\splashscreen.ini to fit your needs.
- When 'splashscreen.ini' is not found in a ECC theme it uses the settings
from the "ecc-themes\default\splashscreen.ini" file.

v2.3.0.5 (2008.10.02)
- Let ECC start in fullscreen again.
- Found a better way to 'by-pass' the PHP-GTK window bug.
- Many small tweaks and improvements.

v2.3.0.4 (2008.08.28)
- Re-implemented the subroutine to ask for automatic bugreport,
it was missing after ECC startup v2.9.0.9 

v2.3.0.3 (2008.08.24)
- ECC now closes Xpadder when ECC closes!
- Improved OCX registering by scanning the tools folder (instead of static commands).
- Improved the option 'minimize to tray' by directly activating it when ECC reloads!
- Improved the option 'start xpadder on startup' by directly activating it when ECC reloads!

v2.3.0.2 (2008.08.18)
- Added missing translations for the Xpadder addon
- Now registering OCX files (for the tools) at startup (once), so people who use
RAR packages, have the OCX files registered at ECC startup.

v2.3.0.1 (2008.08.16)
- Fixed a problem where ECC restart made a bug in error.log.

v2.3.0.0 (2008.08.10)
- Added support for thirdpartytool: Xpadder.

v2.2.0.9 (2008.07.16)
- Startup ECC again with 'php.ini' (instead of 'php-emucontrolcenter.ini')

v2.2.0.8 (2008.07.13)
- Fixed a bug where the one-time-message box appears twice.
- Fixed a bug when the language selector did not work properly.

v2.2.0.7 (2008.06.30)
- Fixed a bug where the proper translation from the INI did not work.

v2.2.0.6 (2008.06.29)
- Added 1 decimal in the percentage counter.
- Increased space needed for media images and userfolders to 11 MB.

v2.2.0.5 (2008.06.29)
- Adjusted the MEDIA image location.
- Now using GD2 instead of imagemagick.
- Added percentage counter for the creation of media images.
- Added creation of CELL_I & NAV_I images.

v2.2.0.2 (2008.06.15)
- Increased free space needed for media images to 9 MB.
- Removed code residue from the ecc core detection.
- Removed splash time out calculation, now always 60 seconds!
- Removed the envment.ini creation, not needed anymore.
- Fixed the titlebar update bug on first restart. (thanks to Andreas)
- Fixed the ECC Bugreport path in the translations.

v2.2.0.0 (2008.05.31)
- Removed all MD5 hash checks.
- Removed OS detection, now using the 'ecc-core' structure.
- Adjusted all paths to the new 'ecc-core' structure.

v2.1.5.0 (2008.04.29)
- Added new images and a brand new splashscreen for the 0.9.7 release.
- Fixed an issue where the startup sound did not play or preview didn't work.

v2.1.3.3 (2008.02.28)
- Fixed an issue where ECC did not return to the original 
'maximized' size when ECC was minimized.

v2.1.3.2 (2008.02.18)
- Fixed an issue where ECC did not start when hailing from
a offside-location (like from the installer) this is fixed by
calling full paths.
- The file sOsDetect.php, has been improved to find the correct path.
- Removed the Kickstart line from the update INI, it is not used
anymore.

v2.1.3.1 (2008.02.17)
- Fixed update checking, because it did not function on a
first start of ecc, so we can now also remove the 'update now'
function from the installer.

v2.1.3.0 (2008.02.16)
- Adjusted to work with multiple cores (Win32, WinNT)

v2.1.2.7 (2008.02.06)
- ECC Startup did not run the desired PHP script for OS
detection due to spaces in paths, this has been corrected
by quoting the complete path.
- Added full paths to the OS & Bugreport PHP files.

v2.1.2.6 (2008.02.04)
- Fixed error message 'could not find ecc-core\php-win.exe'
Some users experienced that 'ecc-core\php-win.exe' could not
be found, this can happen because of foreign language sets,
now using full paths instead of relative paths.

v2.1.2.5 (2008.02.03)
- Adjusted to look in the ECC rootfolder for ECC error.log.

v2.1.2.4 (2008.01.27)
- ECC Startup is now hailing PHP to determine on what OS it
runs, the output syntax from PHP is different then Autoit,
so this is only to adjust things to the feature, because we
want to let ECC run on linux/apple/mac systems later-on ;)

v2.1.2.3 (2007.11.10)
- Removed PHP hash checks (now also feature 'core selection' is possible)

v2.1.2.2 (2007.11.05)
- Fixed a critical mistake by sending the environment
data in the bugreport already overwritten by the
new start of ECC, this means it was not the actual
environment data when the crash happened, now the
checking routine has been improved, also ECC Startup
waits for ECC Bugreport to finish on startup.
- Compiled with ANSI support, so ECC Startup can work
on WIN 98/ME systems.

v2.1.2.1 (2007.10.29)
- Tweaked the ECC 'startup time out' a litte bit, because
ECC has trouble with some (old) USB Flash disks and
would not start up the first time.

v2.1.2.0 (2007.10.20)
- Fixed GTK load on a server machine.
- This is done by setting the ECC php.ini path in the commandline.
- Added desktop data to environment INI.
- Removed the 'Bugreport using' text in ECC Startup.

v2.1.0.4 (2007.10.08)
- Fixed the 1st time language selecting, this didn't work, when the user folder
didn't exist. (it worked after the second time when starting ECC)

v2.1.0.3 (2007.10.02)
- Implented messagebox & option to enable automatic bugreport sending.

v2.1.0.2 (2007.09.30)
- Fixed a bug on creating images, where slow systems (700 Mhz/128 MB) would hang
with the message 'out of memory / low on system resources'

v2.1.0.1 (2007.09.16)
- Fixed a bug where ECC would not create the media images when not running from the ECC root folder.

v2.1.0.0 (2007.09.02)
- ECC Startup will generate 'media' images when necessary!
- This saves some MB's on installation, and also 80 KB for each platform update!
- The images look much nicer to!
- Included: thirparty tool 'convert' from imagemagick!

v2.0.2.1 (2007.09.01)
- Now works with PHP 5.2.4

v2.0.2.0 (2007.08.12)
- ECC Startup can now be translated, files can be found in 'ecc-system\translations'

v2.0.1.3 (2007.08.09)
- Fixed 'already started' bug for Windows Vista
- Now gives the user a one-time message when this happens

v2.0.1.2 (2007.07.23)
- Added a 'LOADING ECC...' label on startup
- Added a loadingbar in upper-right corner, sweet!

v2.0.1.1 (2007.07.19)
- Fixed the splashscreen unload before mainwindow pop-up!
- Added a splashscreen when ECC is restarting! 

v2.0.1.0 (2007.07.08)
- Added a language selector on first startup!
- Added command '/sndprev' to preview the selected startup sound
- eventually there will be a 'sound preview' button in ECC config!

v2.0.0.9 (2007.07.02)
- Has a 'smart splashscreen timeout calculator' built in ;) 
- On some systems ECC could not load within 20 seconds!
- Now validates the system and sets a timeout value!

v2.0.0.8 (2007.07.01)
- Increases the splashscreen timeout to 20 seconds
- On some systems ECC could not load within 15 seconds

v2.0.0.7 (2007.06.22)
- No more hash check on PHP.INI
- Changed PHP.INI settings:
- Changed error log location to 'ecc-core\error.log'
- Changed 'upload_max_filesize' from 2M to 2048M
- Changed 'post_max_size' from 8M to 2048M
- Changed 'memory_limit' from 64M to -1 (unlimited)
- Changed 'log_errors_max_len' from 1024 to 4096
- Changed 'output_buffering' from 4096 to 16384
- Changed 'max_execution_time' from 30 to -1 (unlimited)

v2.0.0.6 (2007.06.17)
- Adjusted splashscreen time-out to 15 seconds.

v2.0.0.5 (2007.06.16) 
- Now works with ECC Live! v2.2.0.0
- Fixed bug when 'startup check' notice shows message everytime!, until the user pressed 'yes'.

v2.0.0.4 (2007.06.11)
- Fixed bug when ECC did not startup when having spaces in the pathname! 
- Added timeout on ECC window catching! when ECC could/did not startup! 
- No more splashscreen hanging anymore!!, timeout is set on 5 sec! 
- Added extra window on 'error' to notice user to use Bugreport or forum.

v2.0.0.3 (2007.06.05)
- Put 'first startup' message to AFTER the checkup message.
- Added warning if user is no admin or not in admin mode.
- Because a user in admin mode has 'read-only' priveleges.
- Now works with php 5.2.3

v2.0.0.2 (2007.06.03)
- Fixed some issues with the sound playing.
- Fixed that sound plays again on ECC 'fastload'
- Fixed soundplay from being cut-off (because of unload ecc.exe)
- Sound now plays after splashcreen!
- Fixed issue if splashscreen hangs.
- When title screen hangs it should be closed when starting ECC again
- This should prevent unnessesary multiple instances of ecc.exe!
- ECC teaser image has been locked! (no change allowed)

v2.0.0.1 (2007.06.02)
- New splash screen & teaser image!
- All ECC 'yellow' icons have been updated and look much better!
- Added new startup sound.
- Added ECC teaser image validation.
- Reduced the splashscreen fade-in from 900 ms to 600 ms.

v2.0.0.0 (2007.06.01)
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
- Tweaked the ECC title a little bit now: version...build...date....update 
- Updated the 'default' general INI with the new strings 
- When general.ini not exist in 'ecc-user-configs' it will be copied from 'ecc-system\conf' 
- Removed commandline parameters 
- Command '/config' removed (Can now configured in ECC itself) 
- Command '/about' removed (now in ecc-docs file) 
- Command '/regreset' removed (now using INI file) 
- Command '/regdelete' removed (now using INI file) 
- Command '/deskicon' removed (done by installer) 
- Command '/verify' removed (done by external program later) 
- Command '/phpinfo' removed (not needed anymore) 
- Command '/unload' removed (not needed anymore, ecc should startup again when crashed)

v1.4.2.2 (2007.05.11)
- Fixed a 'ECC can not start twice' bug when changing from detail/list view.

v1.4.2.1 (2007.05.05)
- Auto update check on startup in total silent mode!
- Not even an error message will be shown, in some cases there is a error caused by ECC when writing 
new ini at fresh installation.
- And you will not get bothered of error messages showing up when something goes wrong

v1.4.2.0 (2007.05.05)
- Added 'auto update check' on startup!
- This will notify the user if there is an update available!
- Speed up the splashscreen a little bit.
- Sound now start playing when splashscreen is shown.

v1.4.1.2 (2007.03.11)
- 'ECC Startup Config' can now only start once (fix).
- Tweaked the 'fixed' title a little bit.

v1.4.1.1 (2006.03.07)
- The 'minimize-2-tray' function can now be configured! 
- Run 'ecc.exe /config' to configure ECC startup!

v1.4.1.0 (2007.03.06)
- ECC gets a fixed title after capturing the window! 
- Added a nice 'minimize-2-tray' function! 
- Added an egg... ;)

v1.4.0.3 (2007.03.05)
- ECC not longer standing on TOPMOST window! (fix)

v1.4.0.2 (2007.03.05)
- Fixed the 'freeze' bug on startup, by improving the focusing of the ECC window.

v1.4.0.1 (2007.02.28)
- Removed the disclaimer line from the 'about' box.
- Deleted Some old code.
- Splash image
- Changed the footer line.
- Syncronized the ECC icon with the background.
- Now locked with MD5 Hash (unchangeable).

v1.4.0.0 (2007.02.07)
- Fixed the registry writing, when not in 'admin' mode (HKLM --> HKCU) (fix)
- Removed 'first startup' pop-up if user wants startmenu icons (ECC installation takes care of this)
- Removed some commands:
- Removed /website (ECC installation takes care of this)
- Removed /starticon (ECC installation takes care of this)
- Removed /disclaimer (Tools and startup are distributed onder the same license)

v1.3.9.4 (2007.01.14)
- Deleted some duplicate code! 
- New command added: '/config' 
- Bring up a ECC Startup config console, only the Startup sound has been implented

v1.3.9.3 (2007.01.01)
- ECC now startup maximized!
- This is an fix of the 'displaymode = 2' setting

v1.3.9.2 (2006.10.30)
- Now compiled with Auto-it v3.2.2.0
- Has Performance improvements
- ECC will not run on a server machine anymore!

v1.3.9.1 (2006.12.23)
- Fixed some ECC Live! implentation issues.
- Improved process check-up
- No major crash error messages should appear from now!
- Minor text changes.
- ECC Live! Startmenu icons
- When creating startmenu icons and ECC Live! is present, startmenu icons for ECC Live will also be created!

v1.3.9.0 (2006.12.06)
- Version number changed to 4 digits
- X.X.0.0, Major change
0.0.X.0, Minor change
0.0.0.X, Revision
- Improved 'unload' command
- Now also unloads 'startup'.
- Added extra command:
- Added '-s' option (silent), executes command without displaying popup window.
- Added pop-up windows
- Added pop-up windows to the '/deskicon' & '/starticon' for verification.
- Improved look-a-like name
- Forbidden word 'emu' has now been fixed.
- Now also points '.' are detected.
- Improved integrated eccLive check!
- Only checks when running ECC, and not every command.
- New register commands 
- Command '/regreset' = Reset the register (reset values to zero) 
- Command '/regclean' = Clean the register (delete whole key and sub-keys) 
- NOTE: renamed command '/reset' to '/regreset'

v1.38 (2007.12.01)
- ECC Live! ready
- Added autoupdate integration at startup
- This means that it will start eccLive.exe in silent mode, no update found..., nothing to be seen ;)
- New updated icon ;)
- Improved INI check-up
- Check certain values if they are numbers
- Added Build values
- Build-in version number
- Extra info
- Improved startmenu integration
- Added sub-folders to sort-out shortcuts.
- Delete 'old shortcuts' first, then creates new ones

v1.37 (2006.11.23)
- Fixed 'displaymode' bug in '/windows' and '/developer' commandline options.
- The 'displaymode' setting has been disabled for these commandline options.
- Improved security
- Added 'ECC' Self check, when modified, hex-edited or anything else, it will display a message.
- A 'one-time' message will pop up when the 'ecc.exe' filename is changed to another name
- The original 'ecc startup' INI and PHP.INI are now locked and being verified, except when ecc.exe is renamed!
- 'look-a-like' names are now forbidden to use.
- INIFILE & PHP.INI are always set to read-only when ECC starts.
- Added commands:
- Added '/disclaimer' show disclaimer information
- Added '/starticon' to create startmenu icons.
- Added '/reset' to reset all 'ECC' registry values.
- Added '/unload' to unload the PHP engine.
- Do not use this function on a server machine!
- Code clean-up:
- Exported all functions and other routines to separate files.
- The function filecheck has been improved, it can now check a single file

v1.35 (2006.11.17)
- Improved mod support
- If the filename is abc.exe then the ini file would be '\abc-system\conf\abc_startup.ini'
- Improved security
  - Security check: fileversion check on PHP.EXE and PHPWIN.EXE on startup
  - if (other) program has no version, then version will result in 0.0.0.0.
- Improved error handling:
- Can now display 'info' commands without checking files (hopefully fixed for good ;)).
- Internal code cleanup
- Error handler with FUNCTION & CASE selecting instead of using IF statements
- Some functions melted together
- Added commands:
  - Added '/?' for commandline help.
  - Also displays when wrong commandline given!
  - Added '/deskicon' to create a shortcut on the desktop.
- Uses 'ecc-system\ecc.ico' as icon.
- When on rename it uses for example: 'abc-system\abc.ico' as icon.
- Name of the shortcut icon can be configured in INI.
- Comment of the shortcut icon can be configured in INI.
- Added '/verify' to check if PHP.EXE and PHP-WIN.EXE are 'original ECC' php files.
- Needed file 'MD5HASH.DLL' (57 KB), stored in 'ecc-core'.
- More configuration options:
  - One or unlimited processes can now be configured in INI.
  - Display mode can now be configured in INI.
  - Splashscreen can now be turned on or off in INI.
- Icons changed in the 'error' boxes, with the 'stop-sign' icon.
- Changed the '/info' command to '/about'.

v1.32 (2006.11.02)
- ECC.EXE is now ready for MOD's/ADD-ON's
- New Icon ;)
- Command '/eccinfo' changed into '/info'
- NEW Features:
- Auto fetch INI (EXAMPLE):
  - when you run ecc.exe it will use '\ecc-system\conf\ecc_startup.ini'
- now when you rename/copy ecc.exe to ems.exe, it will use '\ecc-system\conf\ems_startup.ini' 
- The 'script-php' file to be loaded, can now be configured in INI
- The 'script-php' window name (to have no php shell commands), can now be configured in INI
- The splashscreen title can now be configured in the INI file

v1.31 (2006.10.29)
- No tray icon.
- Splash screen title:
  - Text adjusted to "emuControlCenter loading..."
  - Removed window controls.
  - Title bar can now be set 'on' or 'off' in config file!
- Improved error handling:
- Added file check before reading the INI config file ;)
- Checks the config file if any value is 'zero' (empty).
- Auto size the splashscreen according to found JPG dimensions!
- Fade-in option added, see config file for options.

v1.30 (2006.10.29)
- ECC.EXE is now totally 'dynamic'.
- Uses the 'ecc-system\conf\ecc_startup.ini' for config.

v1.21 (2006.10.28)
- changed the name of the 'startup-file' from: 'speech_welcome_to_ecc.wav', to 'ecc_sound_startup.wav'.
- added mp3 extension to startup sound!
- If you have a wav and mp3 file, ecc will play them both (wav first).
- Added a ECC icon to the splash screen '\ecc-system\images\ecc_icon_small.ico'
- Icons added to the /phpversion & /phpinfo windows.
- Improved error handling:
- Added file check on command /phpversion & /phpinfo.
- Improved commandline function:
- Splashscreen doesn't popup when wrong commandline entered.
- Massive interal code cleanup!

v1.20 (2006.10.25)
- Commandline options are now 'fully' written.
- New commands added:
  - ECC.EXE /phpversion, Displays current PHP version.
  - ECC.EXE /phpinfo, Displays ECC PHP information.
- Displays a SPLASH SCREEN.
- To disable, rename/delete the file 'ecc-system\images\ecc_splashscreen.jpg'
- The splashscreen has a forced 2 second delay of it's own!
(because on some systems ecc gets loaded in 0,3 sec, and we won't see the splash screen ;))
- Plays a WELCOME SOUND.
- To disable, rename/delete the file 'ecc-system\sound\speech_welcome_to_ecc.wav'
- Improved error handler, can now display 'info' commands without checking files.

v1.12 (2006.10.08)
- Supress the 'php' shell window (window name 'emuControlCenter')
- A ERROR HANDLER has been implented for the files:
  - ecc-core\php.exe
  - ecc-core\php-win.exe
  - ecc-system\ecc.php
- Command /url now start up the 'DEFAULT' browser, instead of Microsoft EXPLORER.
- ECC.EXE information is now /inf, instead of /info
- Icons added to the /inf & /ver windows
- New command added:
  - ECC.EXE /win, Start ECC using php-win.exe (old behavior)

v1.1 (2006.09.29)
- Changed the 16x16 icon to 32x32.
- New commands added:
  - ECC.EXE /dev, Start ECC using php.exe.
  - ECC.EXE /url, startup EXPLORER and go to CAMYA website.
  - ECC.EXE /ver, displays current ECC.EXE version.
  - ECC.EXE /info, displays ECC information.

v1.0 (2006.09.27)
- Initial release, just replaces the 'ecc.bat' file
- Starts ECC with 'php-win.exe'