@echo off
set "source=%~1"
set "destination=\\files\MATRICULAS\100000"
xcopy "%source%" "%destination%" /Y /I
