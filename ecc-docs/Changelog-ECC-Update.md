## Changelog ECC Update
***
v1.2.0.1 (ECC v1.21)
- Now fetching website link from toolvariables.au3

v1.2.0.0 (2016.08.10)
- Now using Github to download updates instead of ecc-update server.

v1.0.0.8 (2014.04.10)
- Fixed a issue where executable files where not found due to wrong path.

v1.0.0.7 (2014.04.09)
- Improves code for deleting files and/or folders by setting attributes to write.
- Added new function to force eccUpdate to close

v1.0.0.6 (2014.03.28)
- Combined all global variables in the script eccToolVariables
- Changed URL's to form MINE.NU to .NL (due to changing webadres)

v1.0.0.5 (2013.11.30)
- Made some change in the GUI notification screen, now the update info is displayed
 first before the download starts, also made some other textual improvements
- Added new feature to skip (big) update if there is a better update available
 down the line, like MAME datfile updates, this save bandwidth for both party's!

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
- Replaces ECC Live! which was programmed in VB6, this is a ported version in Autoit3.