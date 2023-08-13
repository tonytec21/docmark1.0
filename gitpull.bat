@echo off
title Atualizando o BookC
echo Aguarde atualizando o BookC...
cd %SystemDrive%\laragon\www\docmark
git pull
echo.
timeout /t 120
gitpull.bat