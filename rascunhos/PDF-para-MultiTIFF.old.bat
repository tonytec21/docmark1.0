@echo off
setlocal

set "source_folder=C:\MATRICULAS EM PDF"
set "output_folder=C:\MATRICULAS\100000"

if not exist "%source_folder%" (
    echo Pasta de origem não encontrada.
    exit /b
)

if not exist "%output_folder%" (
    mkdir "%output_folder%"
    if errorlevel 1 (
        echo Falha ao criar a pasta de destino.
        exit /b
    )
)

echo Executando a conversão...

for %%F in ("%source_folder%\*.pdf") do (
    echo Convertendo arquivo: %%~nxF
    magick convert -density 200 -threshold 50%% -colorspace gray -compress Group4 "%%F" "%output_folder%\%%~nF.tiff"
)

echo Conversão concluída.

endlocal
