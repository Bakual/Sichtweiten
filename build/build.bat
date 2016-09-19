REM This will generate the zipfiles for Sichtweiten in /build/packages
REM This needs the zip binaries from Info-Zip installed. An installer can be found http://gnuwin32.sourceforge.net/packages/zip.htm
setlocal
SET PATH=%PATH%;C:\Program Files (x86)\GnuWin32\bin
rmdir /q /s packages
rmdir /q /s package
mkdir packages
mkdir package

REM Component
cd ../com_sichtweiten/
zip -r ../build/packages/com_sichtweiten.zip *
copy ..\build\packages\com_sichtweiten.zip ..\build\package

REM Modules
REM Plugins

REM Package
cd ../build/package/
copy ..\..\pkg_sichtweiten.xml
zip pkg_sichtweiten.zip *
del pkg_sichtweiten.xml
copy pkg_sichtweiten.zip ..\packages
cd ..
rmdir /q /s package
exit