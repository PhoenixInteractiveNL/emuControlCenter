## Changelog ECC ImagePackCenter (IPC)
***
v2.2.1.1 (2014.04.23)
- Fixed a bug where IPC crashes if there was no imagefile extension.

v2.2.1.0 (2014.03.29)
- Combined all global variables in the script eccToolVariables

v2.2.0.9 (2013.02.19)
- Fixed a imagepack export bug in "name" style filenames (like emumovies)
 with games that have a "." (dot) in their file name, the exported filenames
 should be ok now.

v2.2.0.8 (2012.11.19)
- Adjusted code to use 'cover_3d' instead of 'cover_inlay_03'.

v2.2.0.7 (2012.11.09)
- Adjusted the rom INI information to the new ECC 'selectedrom' triggersystem

v2.2.0.6 (2012.09.03)
- Fixed a bug where imported images are counted double!

v2.2.0.5 (2012.09.02)
- Fixed a bug that made IPC crash with the IMPORTER (option to clean was not in the GUI)
So i added this option to 'clean files with stripper' in the IMPORTER GUI, so import good
(clean) images in ECC.

v2.2.0.0 (2012.08.28)
- Added new feature to compress the images after export with 7ZIP, also added
a feature to add the volume size of the compressed archives (if you need to upload to filesharing website)

v2.1.0.5 (2012.08.25)
- Fixed a bug causing IPC to crash when exporting ECC style.
- Added new feature: Removing unnecessary metadata (junk) like EXIF, IPTC
and comments out of the imagefile.
This method could save loads of unnecessary MB's for the imagepacks and
slink it nicely without loss of imagequality!, this does NOT affect your original imagefiles!
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

v1.2.1.1 (2012.05.06)
- Fixed readme.txt include, this was not included in every generated 7z archive.
- Removed messagebox when user pressed 'cancel'.
- Improved ECC path fetching.
- Adjusted for new ECC script adressing (without BAT file)

v1.2.1.0 (2010.12.24)
- Added program exit when there are no images found for the selected platform.
- Converted traytips to tooltips, wich shows information more efficient.

v1.2.0.0 (2010.02.20)
- Adjusted to be integrated into ECC.

v1.1.0.3 (2009.11.16)
- Fixed a bug where IPC would give a false alarm about PNG/JPG standards.
- Made some grammatical changes/fixes

v1.1.0.2 (2009.11.16)
- Supporting spaces in the ECC USER folder
- Fixed a bug in the 'images support' subroutine.

v1.1.0.0 (2009.11.15)
- Supporting spaces in the ECC USER folder.
- Simplified the source code (by wAw)
- Minor grammatical changes.

v1.0.0.7 (2009.08.30)
- Now also converts the extensions to lowercase.

v1.0.0.6 (2009.06.09)
- Fixed a bug where the filenames that are being sorted, are getting the wrong filename. (only crc32)

v1.0.0.5 (2009.06.09)
- Coverage for ALL imageslots, added ones: cover_spine, cover_inlay, ingame_loading
- No 7zip windows anymore, will be hidden screens (background)
- Warning message if wrong filetypes are detected for that slot!
- Traytext will show percentage (hardcoded)
- Some code cleanup

v1.0.0.0 (2009.06.07)
- Initial release