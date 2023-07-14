@echo off

setlocal enabledelayedexpansion

set input_dir=%~1
set output_dir=%~2

set "imagemagick=C:\Program Files\ImageMagick-7.1.1-Q16-HDRI\magick.exe"

for /R "%input_dir%" %%F in (*.tif) do (
    set "file_name=%%~nF"
    set "pdf_file=!output_dir!!file_name!.pdf"
    
    echo Converting TIFF to PDF: !pdf_file!
    "%imagemagick%" convert "%%F" "!pdf_file!"
)

echo Conversion complete!
pause
