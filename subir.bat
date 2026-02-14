@echo off
REM Sube cambios a GitHub (add + commit + push).
REM Uso: subir.bat [mensaje de commit]
REM Portátil: funciona donde sea que descargues/clones el proyecto.
powershell -ExecutionPolicy Bypass -File "%~dp0subir.ps1" %*
pause
