## Changelog ECC MobyGames Importer (MGI)
***
v1.2.0.0 (ECC v1.21)
- Added new fields to fill: Perspective, Visual
- Description now in the META data location (instead of userdata)

v1.1.0.2 (2014.05.25)
- Fixed bug when searching for data where multiple files are packed within the same archive.
- On manual search the filename will be put in the title field (when there is no META)

v1.1.0.1 (2014.04.23)
- Fixed MG description fetching due to changed website structure
- Moved MG tag on the end of the ROM description.
- Added cancel button to platform auto import.
- Changed the logo to the new 2014 version
- Some GUI changes

v1.1.0.0 (2014.04.23)
- Big update where you can select wich data should be updated.
- Settings are stored in a INI file.
- Rom Manual = Selections can be made what field to store
 (genre is not getting stored, but for info purposses only)
- Rom Auto -> ROM has No META data = FULL auto, so everything gets added,
 including the 'fixed' name
- Rom Auto -> ROM has META data = Asks wich field/value to overwrite,
 option to maintain the fixed name or use the ROM filename.
- Platform Auto -> Asks wich field/value to overwrite, if no META is
 available all data will be added, there is a option to use the fixed name or use the ROM filename.

v1.0.0.2 (2014.03.28)
- Combined all global variables in the script eccToolVariables

v1.0.0.1 (2013.11.09)
- Added better support to fetch the 4-digit release year if there is a month added.
- Fixed "Fixed RomName" where there is () and [] in the filename.

v1.0.0.0 (2013.10.30)
- Initial release!