-------------------------------------------------------------------------------
                         DatUtil v2.46 - 13/04/2009

                  Written by Logiqx (http://www.logiqx.com)
-------------------------------------------------------------------------------

Introduction
------------

DatUtil was created to aid in the creation of dat files for Rom Managers such
as ClrMamePro and RomCenter (Roman Scherzer / Eric Bole-Feysot). It can convert
between formats, extract individual games (and optionally their clones) and
cleanse dats. It supports a variety of formats...

Load and Save:

  - MAME ListInfo (ClrMamePro, MAME, MESS, RAINE, Shark)
  - RomCenter 2.50
  - MAME ListXML (MAME and MESS)
  - Generic XML
  - Tab Delimited
  - AAE RomList

Load only:

  - RomCenter 1.90
  - RomCenter 2.00
  - Nebula Drivers
  - Calice Drivers
  - Zinc --list-sets
  - GNGEO romrc
  - ClrMame Log
  - Folder Scan (subdirectories and zips)

Save only:

  - MAME GameList
  - Title List (for listing game titles - used by CPS-2 readme and RAINE docs)
  - RomCenter Sublist
  - Game Launcher / GameEx Map File
  - Hyperspin Database

Adding support for additional formats is really easy too. If you do add support
for a new format please let me know and send me a copy of the code!


Distribution
------------

The home page for DatUtil is http://www.logiqx.com/

Links may also be found at the following two sites:

ClrMamePro Home Page            RomCenter Home Page        
http://www.clrmame.com/   http://www.romcenter.com/


Basic Conversion
----------------

It is possible to convert dats between different formats with ease. The
most simple way to convert from one format to the other is like this:

  datutil <datfile>

This will convert a ClrMamePro dat (listxml or listinfo) to RomCenter format
or likewise, a RomCenter/ListXML datafile to listinfo format. The new dat is
called 'datutil.dat'. Listinfo format is the preferred output format.

If you want to specify a different output filename, use the '-o' option.

  e.g. datutil -o clrmame.dat romcenter.dat

This would convert romcenter.dat, saving the new file as clrmame.dat.
There is also a '-a' option to append to an existing file. This is especially
useful when converting/extracting individual games (explained later).

If you wish to specify the output format directly (for example, if you want
DatUtil to clean up the dat and resave in the same format) you can use the '-f'
option. Valid formats are 'listinfo', 'listxml', 'romcenter2', 'delimited',
'titlelist' or 'sublist'.

  e.g. datutil -f titlelist clrmame.dat

There are a number of aliases for each format:

  MAME ListInfo   listinfo info li clrmamepro clrmame cmpro cmp cm
  MAME ListXML    listxml xml lx
  Generic XML     genericxml generic gx
  RomCenter 2     romcenter2 rc2 romcenter rc
  Tab Delimited   delimited tab text
  MAME GameList   gamelist gl
  TitleList       titlelist titles
  RC2 Sublist     sublist sub rcsublist rcsub
  Map File        mapfile map

By default, non-essential details for ROM management are removed (e.g. chips
and dipswitch information) but these can be retained using the -k option. Only
the listinfo and listxml formats support all possible game details.

The option specific to the romdata format of Nebula Jukebox is -j. This allows
you to save only the Z80 and Sample ROMs since the other ROMs are irrelevant.

  e.g. datutil -j neogeo.dat


Directory Scanning
------------------

DatUtil can scan a directory and create a datfile for the ZIPs/subdirectories
within it. Simply specify the directory name to scan (instead of a datfile) and
DatUtil will generate the dat for you. Use the -x option to calculate SHA1 and
MD5 values for zipped ROMs.


Additional notes on conversion
------------------------------

The RomCenter 2 format doesn't support all of the potential information that is
in the MAME listinfo output. DatUtil will warn you if any information is lost
during conversion process though (e.g. SHA1 checksums, disks and samples).

When editing dats I find it easiest to work on a ClrMame dat (it's easier to
read) then convert it to RomCenter afterwards. DatUtil will create a RomCenter
dat just as good as you could have done by hand but less prone to errors.  ;-)

You will have noticed that ROMCenter dats and CMPro dats have a header section.
To specify values for the fields in the header, use the '-A', '-V', '-C', '-R',
'-F', '-T', '-E', '-H', '-U' and '-O' options (author, version, category,
name/refname, fullname/description, date, email, homepage, url, comment).
'-M' and '-P' are used to specify the type of merging and packing required.
'-N' is used to specify how nodump ROMs should be handled in CMPro.
Note that these options are all upper case (you must use the correct case).

The log file generated is by default a summary. If you want detailed warning
information and exact details of the cleansing performed, use the '-v' option
for a verbose log file.


Game Selection
--------------

If you just wish to convert/extract an individual game of the original dat
you can use the -g option (and optionally -c for its clones as well).

  e.g. datutil -g sf2 -c romcenter.dat

This would extract sf2 and all of its clones from romcenter.dat, saving them
under the name datutil.dat (of course, the '-o', '-a' and '-f' options could
be used in addition). This feature is useful for creating dats for an emulator
that uses ROM sets like those in MAME (extract from the output of listinfo).
When using -g and -c together you can specify a system such as neogeo or cpzn1
to extract all games on that system.

To select games based on a substring appearing in their description, use the -S
option (e.g. 'datutil -S"(US,(Eu" cps2.dat' to select US and Euro games).

If the datafile includes 'sourcefile' information (added to MAME in v0.85),
you can select all games from an individual sourcefile using the -G option.

The -g, -G and -S options allow you to provide a list of game/sourcefile names
in an external text file (put each entry on a separate line). This is utilised
by putting and @ symbol after the option. For example, if you have a text file
called cpzn.txt that contains two lines (cpzn1 and cpzn2 in this case):

  e.g. datutil -g @cpzn.txt -c listxml.dat

The above example would select all cpzn1 and cpzn2 games from listxml.dat.

In addition to external data files, multiple games/sourcefiles can be specified
on the command line using commas:

  e.g. datutil -g cpzn1,cpzn2 -c listxml.dat

I should also mention that game names including spaces do not require quotes
around them when using the @ method. If you are specifying them on the command
line then yes, they do require quotes.

One last thing to mention about the -g, -G and -S options is that you can use
the -! option to invert your selection (i.e. exclude games). This works for
individual game/sourcefile names and lists of names when using the @ symbol.

If you wish to exclude clones from the newly saved dat, you can use the '-r'
(remove clones) option.


Cleansing
---------

To cleanse a dat, simply run it through DatUtil and examine the log to see if
anything was altered (alternatively, just compare the dats). When performing
this kind of operation, you will normally want to use the -f option to retain
the original format (e.g. datutil -f listinfo <dat to cleanse>).

To convert all game names, ROM names and ROM CRCs to lower case, use the '-l'
option.

By default, the 'fix merging' feature will ensure that CRCs match in
the parent and its clones where they were previously a mixture of nodumps and
genuine CRCs. After the CRCs have been unified it will then specify that the
ROMs can be merged and flag the nodump to CRC converted ROM as a baddump.
This option will also remove bad parent references for cloneof/romof/sampleof
and ensure that there are no clones of clones in the data file. Duplicate ROMs,
disks and samples are also removed by default. In this instance, the
definition of 'duplicate' is where there are two or more ROMs/disks/samples of
the same name in the same game.

The default cleansing can be switched off (-X to switch off 'fix merging' and
-D to fix off 'fix duplicates') but I would recommend that you do not do so
as they are very useful. The only time that switching them off is necessary
is when individual games are being extracted with -g and you need the original
parent/merge details (which would be removed if 'fix merging' was left on).

Note: When using -X, DatUtil will not be able to identify CRC/SHA1 conflicts.

If you wish to remove objects such as ROMs, disks or samples you can use the
-p option. This has a variety of uses, including the removal of CPS-2 disks
which at this time are somewhat large (~4GB)!

The final option is '-s' for sorting the games before saving. This actually
sorts games by their parent name first (n.b. name, not description) and then
by the game name (n.b. name, not description).


Including missing information
-----------------------------

The -i option will retrieve information from another dat and include it in the
dat that you are working on. Basically, -i is meant for use with exactly
matching dats rather than any arbitrary dat that might have a different game
with the same name.

Example purpose:

Correct ROM details (size, MS5/SHA1, flags, etc), using ZIPs as reference:

datutil -i <dir> -f listinfo <dat>

Under these circumstances, size, crc, md5, sha1 and flags can be corrected.

Note: The -i option is not intended for retrieving descriptions, years or
manufacturers from another dat. Such an operation would be very unreliable
if it just used a game name as a method of matching! The -i option is only
intended fot the purpose described above.


Incorporating another data file
-------------------------------

The -I option will incorporate the roms/disks/samples from another data file.

datutil -I supplement.dat base.dat

If the same game exists in both data files then the ROMs from both data files
are combined. Standard cleansing functions will then remove the duplicate ROMs
within that game.


MD5 and SHA1
------------

DatUtil will output either MD5 or SHA1 but not both. Including MD5 and SHA1 in
addition to CRCs would be pointless and cause a hugely oversized output file!
By default DatUtil will output SHA1 values (in addition to CRC) but if you want
to output MD5 instead (which I would advise against) then use the -m option.

The directory scanner will by default not calculate SHA1 or MD5 information
because it requires ZIP files to be unzipped and slows down the directory scan.
If you require SHA1 information from a directory scan then use the -x option.
If you require MD5 from a scan (rather than SHA1), just use -m and -x together. 


Information and logging
-----------------------

DatUtil will only show summary information in its log file by default. If you
require full details about fixes and errors, use the the -v (verbose) option.

You should never need the -d option since that is for debugging DatUtil. ;)


Quick reference
---------------

Defaults:

  -   fix merging (used to be the -m option)
  -   remove duplicate ROMs/disks/samples

Saving:

  -f  output format (listinfo, listxml, romcenter2, delimited, sublist, etc)
  -q  always use quotes around strings (only applies to listinfo output)
  -k  keep as much information as possible from the source file
  -j  Nebula Jukebox - only load Z80 and Sample ROMs from romdata
  -o  output to file
  -a  append to file
  -t  test mode - no data file is actually saved (just loaded and cleansed)

Header text:

  -A  author
  -V  version
  -C  category
  -R  ref name
  -F  full name (i.e. description)
  -T  date
  -E  e-mail
  -H  homepage
  -U  url
  -O  comment
  -M  merging (none, split or full)
  -P  packing (zip or unzip)
  -N  nodump (obsolete, required or ignore)

Game selection:

  -g  individual game selection. use the @ symbol to specify a file of names
  -c  include clones (for use with the -g option)
  -G  select games from a specified sourcefile. The @ method is also supported
  -S  select games using substring of description. The @ method is supported
  -!  changes the -g, -G, -S options (including the -c option) to exclude games
  -r  remove clones

Cleansing:

  -l  lower case game names and ROM names
  -s  sort games by parent (sorted by parent name then game name)
  -i  include missing information from a 'reference' data file
  -I  Incorporate games from a 'supplement' data file
  -X  fix merging off (do not use unless you understand the consequences!)
  -D  remove duplicate ROMs/disks/samples (as above in terms of usage)
  -p  prune (remove) ROMs, disks and/or samples (e.g. '-p disk,sample')

MD5/SHA1:

  -x  Calculate SHA1/MD5 when scanning files or ZIPs in a directory
  -m  Use MD5 checksums rather than SHA1 (e.g. when using -x, -m can be used)

Information:

  -v  Verbose logging
  -d  Show debug messages


Dat features supported
----------------------

DatUtil has its own internal representation for datfiles but in MAME Listinfo
terms it supports:

  Comments   // style comments before a game
  Games      game, resource, machine, name, description, year, manufacturer,
             rebuildto, cloneof, romof, sampleof
  ROMs       rom, name, merge, size, crc, md5, sha1, status
  Disks      disk, name, merge, md5, sha1, status
  Samples    sample, name

These are the standard fields that are output. The -k option supports all of
the fields in MAME and MESS (as per the full DTDs in the listxml output).

DatUtil supports \\, \n, \xXX and \" inside listinfo objects and XML entities
(e.g. &amp; &#X;) inside XML attributes/elements.

Note: DatUtil does not support the 'archive' information in Raine's gameinfo
or Shark's listinfo files as no ROM manager supports this property.


Adding support for new dat formats
----------------------------------

To add support for new formats, take a look at the documentation for DatLib.
It is really easy to add support for a new format but if you can't code, drop
me an e-mail and I might do it for you (if I think it is a useful addition).


Tips and tricks
---------------

It is possible to remove CHDs for individual systems using a combination of
DatUtil options. For example, to remove all CPS-2 CHDs do the following:

datutil -f listinfo -! -G cps2.c -o new.dat mame.dat 
datutil -f listinfo -G cps2.c -p disk -a new.dat mame.dat 

You can check the resultant data file with the following commands:

mamediff -v mame.dat new.dat
notepad mamediff.log


Thanks
------

Thanks go to Eric Bole-Feysot for testing early versions of DatUtil and Pi for
beta testing DatUtil during the full course of its life.


Source
------

As with all of my tools, I have included the source code so you can make
your own modifications but please do not distribute those versions. If
you make any useful changes then please send them to me so that I can
include them in future releases.

To compile this tool, you will need to get DatLib from http://www.logiqx.com/

If you want to create a GUI, you will discover that DatUtil itself is little
more than a command line interface for DatLib. A GUI should therefore be very
easy to create but I would kindly request that you only implement the command
line options from DatUtil in GUI form (no more, no less).


Bug Reports
-----------

If you find any bugs please let me know about them and provide details such as
OS version, command you are using, dat you are running it against etc.

Feel free to send me any suggestions that you have also.

