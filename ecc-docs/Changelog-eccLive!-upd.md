## Changelog eccLive! upd (discontinued, now eccUpdate)
***
v1.2.0.2 (2008.08.09)
- Fixed a problem with some subroutines not working properly
- Also where the ECC Live! update did not unpack properly.

v1.2.0.1 (2008.07.21)
- Fixed php INI to 'php.ini' (instead of emucontrolcenter-php.ini)
- Fixed a problem with reading the local update info INI.

v1.2.0.0 (2008.05.31)
- Adjusted all paths to the new 'ecc-core' structure.

v1.1.0.6 (2008.03.08)
- Adjusted to work with ZIP files instead of RAR files, this because
there is no suitable RAR extension for PHP-GTK2 in feature update.

v1.1.0.5 (2008.02.20)
- Fixed an running problem 'please run from the ecc-tools folder'
on Win32 systems and maybe other operation systems.

v1.1.0.4 (2008.02.17)
- Fixed an issue where ECC Live! was in use so that it
could not be updated #2, this time using the 'timer'
handling again.

v1.1.0.3 (2008.02.17)
- Fixed an issue where ECC Live! was in use so that it
could not be updated.

v1.1.0.2 (2008.02.17)
- Fixed an issue where the update INI was not deleted.
- Removed an unused image in the file, now ECC Live! update
is more than 50 KB smaller.

v1.1.0.1 (2008.02.17)
- Fixed an issue when no ECC software could be found.
- Included the language files for ECC Live! updater.

v1.1.0.0 (2008.02.16)
- Adjusted ECC Live! Updator to use the PHP RAR extension for extraction.
- Removing the thirdparty tool RAR.EXE, it is not used anymore.

v1.0.0.5 (2007.09.30)
- Fixed an running issue on Win98 systems (and maybe other OS'es).

v1.0.0.4 (2007.09.12)
- Set unpacker from 'ecc-core\thirdparty\unrar\unrar.exe' to 'ecc-core\thirdparty\rar\rar.exe'.

v1.0.0.3 (2007.06.12)
- Updating unrar from v3.20 (2003) to v3.70 (2007). 
- Placing unrar in 'thirdparty' and added license.txt . 
- Changed Unrar path from 'ecc-core\unrar' to 'ecc-core\thirdparty\unrar'.

v1.0.0.2 (2007.05.05) 
- Fixed 'HALT' on Japanese / Chinese systems.
- The 'self validation' check on the tools that where incompatible/conflicting
with japanese / chinese charsets, hopefully this is fixed now!

v1.0.0.0 (2007.02.02) 
- Initial (test) release