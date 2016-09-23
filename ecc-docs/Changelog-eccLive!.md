## Changelog eccLive! (discontinued, now eccUpdate)
***
v3.2.0.4 (2010.01.22)
- Fixed an issue where executing a file didn't work properly.

v3.2.0.3 (2009.04.20)
- Fixed issues where filenames to add/replace strings could not be found.

v3.2.0.2 (2009.03.27)
- ECC Live! check on ECC startup will be disabled when there is no active
internet connection found, this is because it will return errors all the time
when you have no connection, you have to enable this manually again in the
ECC configuration screen, when you have re-established the internet connection.
- Messagebox at the end will ask to view the log file now, this is only
when updates have been installed or when an error has occured.

v3.2.0.1 (2009.01.20)
- Implemented an ERROR KICKSTART for ECC Live!, this way when there is a bug
in ECC.EXE and it cannot write the configs eccLive needs, it will continue
to update assuming the OS is WINNT.

v3.2.0.0 (2008.08.24)
- Fixed temporally file removal when ECC Live! is forced to shutdown.
- Fixed a bug to replace strings on the first line of a file.
- Fixed a bug where replacing strings in a file would write a currupt file.
- Added a new function to add a line in a file to a specific linenumber.
- Added errorhandler if a temp file could not be renamed.
- Added errorhandler if a file to replace strings does not exist.
- Changed discription how to handle when an update has to be done manually.

v3.1.0.9 (2008.08.16)
- Added driveletter detection.
- Added free space detection.
- Added new update function to replace strings in files.
- Added free space checking before installing update.
- Fixed a problem where the messagebox didn't show up in 'kickstart mode'.

v3.1.0.8 (2008.08.09)
- Fixed a problem with some subroutines not working properly.
- Also where ECC did not restart in kickstart mode #2.
- Fixed the double 'vv' version tag in the LOG file.
- Fixed a problem where ECC did shutdown where it should not do this.

v3.1.0.7 (2008.07.23)
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

v3.1.0.6 (2008.07.19)
- Use 'php.ini' again instead of 'php-emuControlCenter.ini' for unpacking files.
- Added better support to detect the installtype, for the 0.9.8 installer.
- Fixed a problem with the language detection, so the language should be working properly again.
- Fixed a problem where the 'info' box did not appear on some error messages.
- Maximum updates before asking to download new ECC version raised from 20 to 30.
- Changed layout & background.

v3.1.0.5 (2008.07.01)
- Fixed to get the OS info from the host INI (instead of the envment INI).
- Fixed crash by adding an error handler when 'ecc_local_update_info.ini' could not be found.
- Added messageboxes to inform the user of updates and error situations.
- Added a more 'detailed' information wich file is missing.

v3.1.0.0 (2008.05.31)
- Removed OS detection, now using the 'ecc-core' structure.
- Adjusted all paths to the new 'ecc-core' structure.

v3.0.0.6 (2008.03.08)
- Adjusted to work with ZIP files instead of RAR files, this because
there is no suitable RAR extension for PHP-GTK2 in feature update.

v3.0.0.5 (2008.03.05)
- Fixed an infinite loop when a update is not for the operating OS
or installtype.

v3.0.0.4 (2008.03.04)
- Fixed crash when updating ECC Live! to a more recent version.

v3.0.0.3 (2008.02.23)
- Fixed a string wich was 'there are 1 updates available'.
- Fixed update LOG writing when checking for updates.
- Logging status of ECC Live! is now appended to the existing log.

v3.0.0.2 (2008.02.20)
- Fixed an running problem 'please run from the ecc-tools folder'
on Win32 systems and maybe other operation systems.

v3.0.0.1 (2008.02.17) 
- Fixed an issue where ECC Live! did not start after an update
with the update check.
- The file 'ecc-tools\msflxgrd.ocx' will be removed because it
is not used anymore.

v3.0.0.0 (2008.02.16)
- Almost a Whole rewrite of the ECC Live! engine, not using table's anymore.
It's now more advanced even more things implented, user interface has been
simplified, no more buttons and stuff, also better messages implented.

v2.2.0.4 (2007.09.30) 
- Fixed an running issue on Win98 systems (and maybe other OS'es).
Version 2.2.0.3 (2007.09.12) 
- Set unpacker from 'ecc-core\thirdparty\unrar\unrar.exe' to 'ecc-core\thirdparty\rar\rar.exe'.

v2.2.0.2 (2007.09.01)
- ECC Live! is now also capable to display a message to get the users attention.

v2.2.0.1 (2007.06.13)
- ECC Live! is now also capable to remove (unused) folders in the ECC structure.

v2.2.0.0 (2007.06.12) 
- Own update check-up (instead of ecc.exe v1.4.2.x). 
- Using a INI file instead of 'zero byte' files to read-out 'status'. 
- Updating unrar from v3.20 (2003) to v3.70 (2007). 
- Placing unrar in 'thirdparty' and added license.txt. 
- Removed version check-ups of tools. 
- Adjusting some text in messageboxes. 
- Changed color & lettertype to verdana in info boxes.
- Cleaned-up some internal code.

v2.1.0.5 (2007.05.05)
- Added better connection messages.
- Added a row for 'lastupdate'.
- Added 'ecc start' after updating, this is only when updating from 'autoupdatecheck'.
- Resized the updatelist.

v2.1.0.2 (2007.05.02)
- Fixed 'HALT' on Japanese / Chinese systems.
The 'self validation' check on the tools that where incompatible/conflicting.
with japanese / chinese charsets, hopefully this is fixed now!

v2.1.0.0 (2007.04.20)
- White edition.
- Added XP look.

v2.0.0.2 (2007.02.14)
- Fixed a bug where ECC Live! crashed when clicked on the update list when updating/downloading. 
- Fixed a bug where old file(s) should be deleted on the next update that uses a delete list. 
- Made some fields a bit longer, also made the list a bit bigger. 
- Added a 'kickstart', when updating ECC Live!, the new version wil directly continue downloading. 
- Some text strings have been adjusted.

v2.0.0.1 (2007.02.05)
- Handling of the separate INI files! 
- The file 'ecc_local_update_info.ini' has been added, ECC Live! keep it's track in this file. 
- Fixed a small bug that sould say 'No update available...' and not 'Waiting...' when no updates found.

v2.0.0.0 (2007.02.04)
- Total rebuild, now using VB6 engine, new interface, and more...

v1.0.0.0 (2006.12.23)
- First Public!, released at first release of ECC!, build on the AutoIt 3 engine.