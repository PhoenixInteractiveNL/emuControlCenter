@ECHO OFF
title ECC documentation generator v1.0.0.1
rem This script converts GitHub wiki markdown to offline HTML documentation.
rem
rem Made by: Sebastiaan Ebeltjes (aka Phoenix)
rem Enjoy!

SET FARTFILE="..\ecc-core\thirdparty\fart\fart.exe"
SET MARKDOWNFILE="..\ecc-core\thirdparty\markdown\markdown.exe"

echo # Remove OLD files...
del *.htm

echo # Convert Markdown to HTML...
FOR %%X in ("*.md") DO (
  %MARKDOWNFILE% %%~dfX >> %%~dpnX.htm
)

rem The %%~dpnX is for expanding the filename of %%X to
rem d=drive(C:)
rem p=path(\Users\Family\Desktop\Example)
rem n=filename(test1) (without extension)
rem f=full filename(C:\Users\Family\Desktop\Example\test1.txt)

echo # PATCHING HTML files...
FOR %%X in ("*.htm") DO (
  rem Replace IMAGE links to local in HTML...
  %FARTFILE% %%~dfX --quiet --remove "https://raw.githubusercontent.com/wiki/PhoenixInteractiveNL/emuControlCenter/"
  rem Replace wiki PAGE links to local in HTML...
  %FARTFILE% %%~dfX --quiet --remove "https://github.com/PhoenixInteractiveNL/emuControlCenter/wiki/"
  rem ADD .htm to wiki PAGE links to local in HTML, also set target frame...
  rem convert '"><em>' to '.htm" target="MAIN"><em>' and add a .htm extension.
  %FARTFILE% %%~dfX --quiet -C "\x22\x3E\x3C\x65\x6D\x3E" "\x2e\x68\x74\x6d\x22\x20\x74\x61\x72\x67\x65\x74\x3d\x22\x4d\x41\x49\x4e\x22\x3e\x3c\x65\x6d\x3e"
  rem convert '"><strong>' to ' target="_blank"><strong>' to give blank page to external sites.
  %FARTFILE% %%~dfX --quiet -C "\x3e\x3c\x73\x74\x72\x6f\x6e\x67\x3e" "\x20\x74\x61\x72\x67\x65\x74\x3d\x22\x5f\x62\x6c\x61\x6e\x6b\x22\x3e\x3c\x73\x74\x72\x6f\x6e\x67\x3e"
)

echo # Build TITLE htm...

SET TITLEFILE="_Title.htm"

echo "<HTML>" >> %TITLEFILE%
echo "<HEAD>" >> %TITLEFILE%
echo "<TITLE>ECC Documentation</TITLE>" >> %TITLEFILE%
echo "<BASE TARGET="MAIN">" >> %TITLEFILE%
echo "</HEAD>" >> %TITLEFILE%
echo "<h2><center><b>emuControlCenter documentation<b/></center><h2>" >> %TITLEFILE%
echo "</HTML>" >> %TITLEFILE%
rem remove "
%FARTFILE% %TITLEFILE% --quiet --remove -C "\x22"

echo # Build INDEX html...

SET INDEXFILE="index.html"

echo "<HTML>" >> %INDEXFILE%
echo "<HEAD>" >> %INDEXFILE%
echo "<TITLE>ECC Documentation</TITLE>" >> %INDEXFILE%
echo "</HEAD>" >> %INDEXFILE%
echo "<FRAMESET ROWS='FRAMEDATA1'>" >> %INDEXFILE%
echo "     <FRAME SRC='_Title.htm' NAME='TITLE' SCROLLING='NO' frameborder='0' noresize='noresize'>" >> %INDEXFILE%
echo " " >> %INDEXFILE%
echo "     <FRAMESET COLS='FRAMEDATA2'>" >> %INDEXFILE%
echo "          <FRAME SRC='home.htm' NAME='MAIN' frameborder='1' noresize='noresize'>" >> %INDEXFILE%
echo "		  <FRAME SRC='_sidebar.htm' NAME='SIDEBAR' frameborder='1' noresize='noresize'>" >> %INDEXFILE%
echo "     </FRAMESET>" >> %INDEXFILE%
echo " " >> %INDEXFILE%
echo "    <FRAME SRC='_Footer.htm' NAME='FOOTER' SCROLLING='NO' frameborder='1' noresize='noresize'>" >> %INDEXFILE%
echo " " >> %INDEXFILE%
echo "    <NOFRAMES>" >> %INDEXFILE%
echo "    FRAMES NOT SUPPORTED!" >> %INDEXFILE%
echo "    </NOFRAMES>" >> %INDEXFILE%
echo "</FRAMESET>" >> %INDEXFILE%
echo " " >> %INDEXFILE%
echo "</HTML>" >> %INDEXFILE%
rem remove "
%FARTFILE% %INDEXFILE% --quiet --remove -C "\x22"

rem fix framedata (% cannot be done by echo)
rem FRAMEDATA1= 4%,*,5%
rem FRAMEDATA2= *,20%
%FARTFILE% %INDEXFILE% --quiet -C "FRAMEDATA1" "\x34\x25\x2c\x2a\x2c\x35\x25"
%FARTFILE% %INDEXFILE% --quiet -C "FRAMEDATA2" "\x2a\x2c\x32\x30\x25"

rem last patch for HOME link in sidebar.
%FARTFILE% _Sidebar.htm --quiet -C "https://github.com/PhoenixInteractiveNL/emuControlCenter/wiki.htm" "home.htm"

echo Done!