## Changelog ECC ICC ImageInject
***
v1.1.0.9
- Adjusted CID checks to UID.

v1.1.0.8 (2014.03.28)
- Combined all global variables in the script eccToolVariables
- Changed URL's to form MINE.NU to .NL (due to changing webadres)

v1.1.0.7 (2012.11.09)
- Adjusted the rom INI information to the new ECC 'selectedrom' triggersystem

v1.1.0.6 (2012.07.04)
- Fixed issue with downloading images, due to other slashes / (instead of \)

v1.1.0.5 (2012.06.27)
- Now uses proper ECC User folder, wich is configured in ECC.

v1.1.0.3 (2012.06.06)
- Added error handler if server is offline.
- Added error handler if download was wrong due to server php error.

v1.1.0.2 (2012.06.03)
- Fixed: The label of the type of file that is downloading has been restored.
- Added: Counter for Total platform images bar.
- Some cosmetic improvements in the GUI.

v1.1.0.1 (2012.06.02)
- Fixed: Total platform images bar for downloading single rom images
- Improved: Total images platformbar to be much smoother (not 16 steps) by counting the ROMS in the ECC DB.
- Some cosmetic improvements in the GUI.

v1.1.0.0 (2012.06.02)
- New feature: Download all images for imported ECC roms.
- Can download all images for imported ECC roms.
- GUI improvements: ECCID and CRC32 are shown.

v1.0.0.2 (2012.05.06)
- Fixed a bug on deleting file when user pressed 'cancel'.
- Improved ECC path fetching.
- Adjusted for new ECC script addressing (without BAT file)

v1.0.0.1 (2012.05.05)
- Improved code of the images.ini download, now this is only done once.
- Added number of downloaded images to the final tooltip.

v1.0.0.0 (2012.05.06)
- Initial release