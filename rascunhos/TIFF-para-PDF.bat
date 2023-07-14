@echo off
setlocal enabledelayedexpansion

set "imagemagick=C:\Program Files\ImageMagick-7.1.1-Q16-HDRI\magick.exe"
set "pasta_tiff=%~1"

cd "%pasta_tiff%"

for %%i in (*.tif) do (
  set "input_file=%%i"
  set "output_file=!input_file:~0,-5!.pdf"
  "%imagemagick%" "!input_file!" "!output_file!"
)

echo Conversão concluída.

