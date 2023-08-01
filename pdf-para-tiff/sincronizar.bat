@echo off
setlocal enabledelayedexpansion

set "origem=%~dp0\historico\*.tiff"
set "destino=C:\MATRICULAS\100000"

set /a count=0
for %%F in (%origem%) do (
    copy "%%F" "%destino%"
    if !errorlevel! equ 0 (
        set /a count+=1
    )
)

echo %count%
