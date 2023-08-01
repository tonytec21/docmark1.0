@echo off
set "filePath=%~1"
set "destination=\\files\MATRICULAS\100000"
copy "%filePath%" "%destination%"
