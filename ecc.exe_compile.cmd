@ECHO OFF
title ECC.EXE compiler
echo Compiling ECC, please wait...
ecc-core\thirdparty\autoit\Aut2exe.exe /in ecc.exe_source.au3 /out ecc.exe /icon ecc-system\images\icons\ecc.ico /comp 4 /nopack /x86
echo Done!