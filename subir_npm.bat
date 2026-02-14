@echo off
REM subir_npm.bat
REM 1) Sube a Git (add + commit + push) con el mensaje que indiques
REM 2) En el contenedor padre: git pull + npm install + npm run build + restart app
REM Uso: subir_npm.bat [mensaje de commit]
REM Si no pasas mensaje, te lo pide.

if "%~1"=="" (
    set /p MSG="Mensaje de commit: "
) else (
    set "MSG=%*"
)

cd /d "%~dp0"
echo.
echo === 1/2 Subiendo a Git ===
powershell -ExecutionPolicy Bypass -File "%~dp0subir.ps1" "%MSG%"
if errorlevel 1 (
    echo Error al subir. Se detiene aqui.
    pause
    exit /b 1
)

echo.
echo === 2/2 Actualizando en Docker (pull + npm install + build + restart) ===
cd /d "%~dp0.."
powershell -ExecutionPolicy Bypass -File "%~dp0..\scripts\actualizar_npm_run.ps1"
if errorlevel 1 (
    echo Error en actualizacion Docker.
    pause
    exit /b 1
)

echo.
echo === Subir + npm (actualizacion total con npm install) lista ===
pause
