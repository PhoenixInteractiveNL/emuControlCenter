## Changelog ECC emuMoviesDownloader (EMD)
***
v1.2.1.3
- Removed redundant CID checks.

v1.2.1.2 (2014.06.24)
- Fixed & Updated encryption en decryption algorithm due to function break in
 the new autoit version _StringEncrypt has been replaced with _Crypt_EncryptData.
- The account log-in data has to be re-entered due to encryption change!

v1.2.1.1 (2014.03.28)
- Combined all global variables in the script eccToolVariables

v1.2.1.0 (2013.12.01)
- Fixed "MAME Artwork" downloads, these download seems to be ZIP files now, so by
 adding a unpack feature for ZIP files these images are now extracted from the
 achives and given the proper name!
- Added check if 7Zip is working (due to "MAME Artwork" ZIP downloads)
- Added check if media is writable.
- Fixed a bug for the "Game Boy" platform when downloading resources from emuMovies
 The platform was not good detected due to a wrong double string in the EMD list.

v1.2.0.6 (2013.11.17)
- Improved search query's by removing "trash" in the search result

v1.2.0.5 (2013.10.04)
- Redesigned the EMD GUI, textbox has been made bigger!
- Fixed bug where EMD did not close after server error message.
- Fixed url in the EMD banner on the login menu to go their website.

v1.2.0.2 (2012.11.19)
- Adjusted code to use 'cover_3d' instead of 'cover_inlay_03'.
- Fixed a bug where files are not downloaded if a folder already exists
- Fixed a bug where 'PCB' images are not downloaded correctly

v1.2.0.1 (2012.11.15)
- Fixed: Crash on importing/downloading videofiles.
- Improved: Several GUI changes.

v1.2.0.0 (2012.11.15)
- Added: Cancel button.
- Fixed: EMD will not create any doubles (PNG+JPG) of the same media (images).
- Fixed: File extensions are now lowercased.
- Improved: EMD now on Hyperspeed!, EMD will now first check if the file already
exists before downloading the images data, this gives enormous speed increase!
- Improved: The content list now only shows the content supported by ECC.
- Improved: Clear processlist when downloading another content.
- Made several GUI improvements and text changes.

v1.1.0.0 (2012.11.09)
- Added: new feature to download videos MP4/FLV from the EmuMovies server.

v1.0.0.0 (2012.10.13)
- With this tool integrated in ECC you can finally make your ROM artwork/images
complete, you'll need an account on EmuMovies (registration is free)
- Initial release