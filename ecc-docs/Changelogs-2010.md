## Changelogs 2010
***
Version 1.1 (2010.07.10)

- Added platforms:
 - Exelvision EXL100
 - Funtech Super A'can
 - Fruit Machines
 - Galaksija - Galaksija Plus
 - Jungle Soft Vii
 - V. I. Lenin L'vov/Lviv
 - Philips P2000
 - Tesla PMD-85

WIP 06.1 (2010-02-20)
- Fixed an issue where broken image icons are shown in the GUI, when no roms are in the database (like on first startup)

WIP 06 (2010-02-20)
- Integrated ImagePackCreator (IPC) into ECC (no more standalone).
- Added icons to the top-menu system: tools > images.
- Adjusted some top-menu texts.
- Fixed a little bug in dropdownMediaType.
- Added genre 'Gambling - Quiz/Gameshow'.

WIP 05 (2010-02-19)
- Added about 150 new genres (vicman/dermicha75/te_lanus)
- Removed the 'beta' strings, ECC is now officially out of the beta period!
- Changed in dropdownMediaType > 'floppydics' to 'floppydisc'.

WIP 04 (2010-01-09)
- Added 'Info-ID' label in the right panel. (DerMicha75)
- Fixed year value in right-menu>quickfilter.
- Fixed trainer/multiplayer dropdown options to Yes/No.

WIP 03.2 (2009-11-23)
- Made the 'IMAGE' TAB in the mainscreen translatable.

WIP 03.1 (2009-11-22)
- Added active/nonactive properties to the unpackAll function, so the option cannot
 be selected, when autounpack is disabled!
- Added a note underneath the zip function howto automaticly purge the unpack folder.

WIP 03 (2009-11-20)
- Added an option in the ECC config to unpack ALL files with subdirs from a ZIP/7ZIP archive
 instead of the single rom file.

WIP 02 (2009-11-10)
- Fixed the build string in the titlebar.
- Added new dump types for you to search/filter ECC:
 - Favorite dump
 - Amiga WHDLoad dump
 - Amiga ADF dump
 - Amiga IPF dump
 - Amiga SPS dump
- Added (changed) 2 multiplayer settings:
 - 1P Only
 - 2P Only
- Added 5 new catagories
 - Action/RPG
 - Pinball/Pachinko
 - Sports/Bike
 - Sports/Snowboarding
 - Sports/Multi Game
- Added folder 'utils' into userfolder creation.

WIP 01 (2009-11-08)
- Added an option in ECC config to purge/empty the 'ecc-user\#_AUTO_UNPACKED' on ECC exit.

- Small updates
 - Added commanline parameter for NullDC (Sega Dreamcast platform)
 - Fixed "Sega Dreamcast" info file (was commodore content)

- Script updates
 - Added scripts:
   - 'EP128Emu' emulator for Enterprise 64/128
   - 'EP32-120' emulator for Enterprise 64/128
   - 'exl100_wx' emulator for Excelvision
   - 'dcexel' emulator for Excelvision
   - 'BFMulator'  emulator for Fruitmachines
   - 'Winape' emulator script v1.0.0.2:
     - Fixed: When using plain files the timer was set to fast, so i increased 1000 to 2500.
     - Fixed: When using ZIP files the '#' wasn't taken by the autoit 'send' command.
  - Added commanline parameter for Fusion on the Sega Mega CD platform.
  - Added 'bin' extension for the Amstrad GX4000 platform.
  - Dosbox v1.0.0.3
    - Fixed a problem when there is an IMG file found while the game needs to be started with a .EXE/.COM file.

- ECC Startup
v2.3.4.0
- ECC Startup now also maintains the emulatorlist, stored in the 'ecc_navigation.ini'
 file automaticly, based on if these emulator files exist:
 > ecc_[eccid]_system.ini
 > ecc_[eccid]_user.ini
 This way its more like a pluginnable interface for new platforms, and also prevents
 overwriting them with newer or other 'user' packages.
v2.3.3.9
- Increased the width of the splashscreen label a littlebit, so that more text
 can be shown.

- DATfile updates
 - CPS-1  : v2010.01.03 to v2010.05.18
 - CPS-2  : v2010.01.03 to v2010.05.18
 - MAME   : v0.137 to v0.138
 - MODEL1 : from mame v0.137 to v0.138
 - MODEL2 : from mame v0.137 to v0.138
 - NAOMI  : from mame v0.138 *NEW*
 - NEOGEO : v2009.09.15 to v2009.11.03
 - S11    : from mame v0.137 to v0.138
 - S18    : from mame v0.137 to v0.138
 - S22    : from mame v0.137 to v0.138
 - ZINC   : from mame v0.137 to v0.138

- Thirdparty updates
 - Notepad++ v5.7
 - AutoIt v3.3.6.1


Version 1.0.R2 (2010.02.25)

- Small updates
 - Updated right-click menu icons (by Blackerking)
 - Added extensions: .WAD for Nintendo Wii
 - DOSBox games will be imported from now with the ZIP parser, so only zipped
   files will be supported.
 - Added extensions
   - Nintendo WII: DOL, ELF
   - Sega 32X: SMD
   - Apple 3: DO

- Script updates
 - Added a DOSBox script with was in developement for 1 month on
   the forum, we have found solutions and we're proud that ECC can handle DOS
   games with DOSBox, we hopy you enjoy it too!
 - Added "IntelliX" subroutine into ECC script system
   - This subroutine uses the ECC config variables and applies them automaticly.
   - This should make handling of packed romfile inside archives a lot easier.
   - Autodetects if there is a .cue file present (with the same filename) and uses this
     when available
 - Added script for anex86 emulator for platform: PC-9801 (by te_lanus)

- Tool updates
 - ECC Live! v3.2.0.4
   - Fixed an issue where executing a file didn't work properly.

- DATfile updates
 - Updated DATfiles for:
   - CPS-1: from 2009.09.15 to  2010.01.03
   - CPS-2: from 2009.09.15 to  2010.01.03
   - MAME : from v0.135 to v0.136
 - Updated 'driver.ini' for MAME (lordashram)

- Thirdparty updates
 - Notepad++ v5.6.4
 - AutoIt v3.3.4.0