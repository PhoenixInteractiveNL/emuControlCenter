## Changelog ECC DATFileUpdater (DFU)
***
v1.3.0.2
- Updated "NeoGeo" datfile extract naming to match MAME v2.00+

v1.3.0.1
- Removed DATE function and string, not used in newer MAME dats anymore
- Fixed NeoGeo driver from 'neodrvr.cpp' to 'neodriv.hxx'

v1.3.0.0 (2016.08.10)
- Added a feature to unpack 7z files DAT files once on first start due to Github filesize limit.
- Updated and fixed platform listings to grab (.c to .cpp) MAME 0.17x+? to create CLRMAME DAT files.
- Removed DAT dates, seems not te be listed in official MAME DAT anymore.
- Fixed creating always the 'backup' folder, now it will be created only when making a backup.
- Fixed NeoGeo platform DAT generation (seems to be neodriv.hxx?)
- Fixed DAT version find from MAME DAT manual update.
- Upped required space to 150MB when doing a manual MAME DAT update.
- Removed check for ECC executable.

v1.2.5.9 (2014.03.29)
- Fixed a bug where the 7Z backup was not created.
- Fixed a bug calling eccToolsVariables script

v1.2.5.8 (2014.03.28)
- Combined all global variables in the script eccToolVariables

Version 1.2.5.7 (2012.07.04)
- Changed filename for 7zip usage due to 7zip update.

v1.2.5.6 (2012.05.06)
- Improved ECC path fetching.
- Adjusted for new ECC script adressing (without BAT file)

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
- Added a system that will use the temp DATfile when found and automatically update the user dats.

v1.2.0.0 (2011.04.24)
- Added support for the 64-Bit and i868 optimized version of MAME (executable selection).
- Rounded the number of space free needed in MB (message) when you have not enough (also upped to 50MB).
- Added check if the executable really is an 'official' MAME file.
- Now writing the original MAME DATE inside the header, instead of the date on the users computer.
- Cleaned up the code in DFU, made some functions etc.

v1.1.0.0 (2010.11.30)
- Fixed a problem where ECC could not import arcade ROMS, because the DATfiles didn't had any header.

v1.0.0.0 (2011.05.06)
- Initial release