@echo off
setlocal

set "source_folder=%~1"
set "output_folder=%~1"

if not exist "%source_folder%" (
    echo Pasta de origem não encontrada.
    exit /b
)

if not exist "%output_folder%" (
    echo Pasta de destino inválida.
    exit /b
)

echo Executando a conversão...

for %%F in ("%source_folder%\*.pdf") do (
    echo Convertendo arquivo: %%~nxF
    magick convert -density 200 -colorspace Gray -type Bilevel "%%F" -compress Group4 "%output_folder%\%%~nF.tiff"
)

echo Conversão concluída.

endlocal
