To convert the XML DAT to an CSV FILE from the command line you need this tool:[xml2csv](https://github.com/PhoenixInteractiveNL/ecc-toolsused/tree/master/xml2csv)

* Usage:

`xml2csv.exe <name of the XML file for input> <name of the CSV file to create> "<data to save (data1;data2)>" -D=<seperation mark>`

Example for the NDS xml dat:

`xml2csv.exe ADVANsCEne_NDScrc.xml ADVANsCEne_NDScrc.csv "imageNumber;romCRC;title" -D=;`

This will output a CSV file, wich has data seperated by ";" ,examples of the CSV file, after processing an XML DAT:

* XML DAT:
```xml
   <game>
         <imageNumber>1</imageNumber>
         <releaseNumber>1</releaseNumber>
         <title>Metroid Prime Hunters - First Hunt (Demo)</title>
         <saveType>None</saveType>
         <romSize>16777216</romSize>
         <publisher>Nintendo</publisher>
         <location>1</location>
         <sourceRom>Darkfader</sourceRom>
         <language>2</language>
         <files>
            <romCRC extension=".nds">C2FB5233</romCRC>
         </files>
         <im1CRC>789F71C4</im1CRC>
         <im2CRC>633C8693</im2CRC>
         <icoCRC>52079D61</icoCRC>
         <nfoCRC>F0245E69</nfoCRC>
         <genre>Demo</genre>
         <dumpdate>2005-03-08</dumpdate>
         <internalname>FIRST HUNT</internalname>
         <serial>NTR-AMFE-USA</serial>
         <version>1.0</version>
         <wifi>No</wifi>
         <filename>metroid.prime.first.hunt-df.zip</filename>
         <dirname>Metroid_Prime_First_Hunt_NDS-DF</dirname>
         <duplicateid>30</duplicateid>
         <comment>xxxx</comment>
      </game>
      <game>
         <imageNumber>2</imageNumber>
         <releaseNumber>2</releaseNumber>
         <title>Electroplankton</title>
         <saveType>None</saveType>
         <romSize>16777216</romSize>
         <publisher>Nintendo</publisher>
         <location>7</location>
         <sourceRom>Trashman</sourceRom>
         <language>256</language>
         <files>
            <romCRC extension=".nds">94767CD4</romCRC>
         </files>
         <im1CRC>4CF966EA</im1CRC>
         <im2CRC>82AA0065</im2CRC>
         <icoCRC>15F11361</icoCRC>
         <nfoCRC>54DEE27A</nfoCRC>
         <genre>Music</genre>
         <dumpdate>2005-06-17</dumpdate>
         <internalname>ELE PLANKTON</internalname>
         <serial>NTR-ATIJ-JPN</serial>
         <version>1.0</version>
         <wifi>No</wifi>
         <filename>trm-elpl.zip</filename>
         <dirname>Electroplankton_JPN_NDS-TRM</dirname>
         <duplicateid>72</duplicateid>
         <comment>0001</comment>
      </game>
      <game>
         <imageNumber>3</imageNumber>
         <releaseNumber>3</releaseNumber>
         <title>Need for Speed - Underground 2</title>
         <saveType>Eeprom - 64 kbit</saveType>
         <romSize>33554432</romSize>
         <publisher>Electronic Arts</publisher>
         <location>1</location>
         <sourceRom>Trashman</sourceRom>
         <language>4291</language>
         <files>
            <romCRC extension=".nds">C37AB273</romCRC>
         </files>
         <im1CRC>8C84BB28</im1CRC>
         <im2CRC>03FE72D8</im2CRC>
         <icoCRC>10F4712C</icoCRC>
         <nfoCRC>8CE36F9D</nfoCRC>
         <genre>Racing</genre>
         <dumpdate>2005-06-17</dumpdate>
         <internalname>NFSU2</internalname>
         <serial>NTR-AUGE-USA</serial>
         <version>1.0</version>
         <wifi>No</wifi>
         <filename>trm-nfs2.zip</filename>
         <dirname>Need_For_Speed_Underground_2_USA_NDS-TRM</dirname>
         <duplicateid>34</duplicateid>
         <comment>0002</comment>
      </game>
```

CSV OUTPUT:

    imageNumber;romCRC;title
    1;C2FB5233;Metroid Prime Hunters - First Hunt (Demo)
    2;94767CD4;Electroplankton
    3;C37AB273;Need for Speed - Underground 2
    4;03D56334;Yoshi Touch & Go
    5;662D929F;Feel the Magic - XY XX
    6;2CE68FAD;WarioWare - Touched!
    7;9B49BD53;Polarium
    8;E84022EC;Puyo Pop Fever
    9;F6AF8061;Pac-Pix
    10;FCC4DDF5;Space Invaders DS
    11;58126FB5;Cool 104 Joker & Setline
    12;5A18290A;Guru Guru Nagetto
    13;4F604498;Asphalt - Urban GT
    14;1CE5E154;WarioWare - Touched! (Demo)
    15;1D829D6F;Yoshi Touch & Go
    16;652FE4BC;Pac-Pix
    17;35EBD240;Catch! Touch! Yoshi!
    18;138556D1;Meteos
    19;0AD5A96F;Ridge Racer DS
    20;8CAF8639;WarioWare - Touched!
    21;E6BFA3BC;Mr. Driller - Drill Spirits
    22;1DA5EF2B;Chokkan Hitofude
    23;1915C4BB;Project Rub
    24;29715DEC;Super Mario 64 DS
    25;3F3F7DC8;Star Wars Episode III - Revenge of the Sith
    26;35281C5C;Robots
    27;AAC9842D;Super Mario 64 DS
    28;0D8B2B06;Pokemon Dash
    29;1276FB36;Metroid Prime Hunters - First Hunt (Demo)
    30;B909ADB3;Mr. Driller - Drill Spirits
    31;FE7DC5EE;Kirby - Canvas Curse
    32;BB881C67;GoldenEye - Rogue Agent
    33;441C4158;Sprung - The Dating Game
    34;7E1223DA;Polarium
    35;0F805B72;Bomberman
    36;82FF50C5;Kenshuui Tendo Dokuta
    37;0989A92C;Zoo Keeper
    38;00247816;Touch! Kirby's Magic Paintbrush
    39;1722923E;Daigasso! Band Brothers
    40;E6321562;Super Mario 64 DS
    41;7BBBABEE;Ping Pals
    42;EC861AB2;Another Code - 2tsu no Kioku
    43;B42072B8;Hanjuku Eiyuu DS - Egg Monster Hero
    44;5092F331;Need for Speed - Underground 2
    45;4823C71C;Nintendogs - Chihuahua & Friends
    46;3B275B19;Spider-Man 2
    47;CCF40226;Tennis no Ouji-Sama 2005 - Crystal Drive
    48;EC29F9F7;Urbz - Sims in the City, The
    49;925E2F90;Yakuman DS
    50;AA6EA8E3;Rayman DS