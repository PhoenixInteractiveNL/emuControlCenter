You need the [datutil v2.46bh](https://github.com/PhoenixInteractiveNL/ecc-toolsused/tree/master/datutil) tool to create ECC datfile from the master MAME DATfile.

Usage:

    datutil.exe -G <system.c> -o <output file> -f cmp <source dat>

Example:

    datutil.exe -G cps3.c -o cps3.dat -f cmp mame.dat

a file called cps3.dat has been made, this DAT contains all DAT information for "Capcom Play System 3"".

### Batch conversion

Here is a list for batch conversion of the datfiles:

    datutil.exe -G cps1.cpp -o cps1.dat -f cmp mame.dat
    datutil.exe -G cps2.cpp -o cps2.dat -f cmp mame.dat
    datutil.exe -G cps3.cpp -o cps3.dat -f cmp mame.dat
    datutil.exe -G zn.cpp -o zinc.dat -f cmp mame.dat
    datutil.exe -G model1.cpp -o model1.dat -f cmp mame.dat
    datutil.exe -G model2.cpp -o model2.dat -f cmp mame.dat
    datutil.exe -G pgm.cpp -o pgm.dat -f cmp mame.dat
    datutil.exe -G naomi.cpp -o naomi.dat -f cmp mame.dat
    datutil.exe -G neodriv.hxx -o ng.dat -f cmp mame.dat
    datutil.exe -G system16.cpp -o s16.dat -f cmp mame.dat
    datutil.exe -G segas16a.cpp -a s16.dat -f cmp mame.dat
    datutil.exe -G segas16b.cpp -a s16.dat -f cmp mame.dat
    datutil.exe -G system18.cpp -o s18.dat -f cmp mame.dat
    datutil.exe -G segas18.cpp -a s18.dat -f cmp mame.dat
    datutil.exe -G namcos11.cpp -o s11.dat -f cmp mame.dat
    datutil.exe -G namcos12.cpp -a s11.dat -f cmp mame.dat
    datutil.exe -G namcos21.cpp -o s22.dat -f cmp mame.dat
    datutil.exe -G namcos22.cpp -a s22.dat -f cmp mame.dat
