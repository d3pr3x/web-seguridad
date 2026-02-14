# subir.ps1 — Sube cambios a GitHub (add + commit + push) desde el directorio actual.
# Uso: .\subir.ps1 [mensaje]
# Sin mensaje: "Actualizacion para demo cliente"
# Portátil: funciona donde sea que descargues/clones el proyecto.

$ErrorActionPreference = "Stop"
$ScriptDir = Split-Path -Parent $MyInvocation.MyCommand.Path
Set-Location $ScriptDir

$msg = if ($args.Count -gt 0) { $args -join " " } else { "Actualizacion para demo cliente" }

if (-not (Test-Path ".git")) {
    Write-Host "ERROR: No hay repo git en este directorio." -ForegroundColor Red
    exit 1
}

Write-Host "Agregando cambios..." -ForegroundColor Cyan
git add .
$status = git status --short
if (-not $status) {
    Write-Host "No hay cambios que subir." -ForegroundColor Yellow
    exit 0
}
Write-Host "Commit: $msg" -ForegroundColor Cyan
git commit -m "$msg"
Write-Host "Subiendo a origin..." -ForegroundColor Green
$rama = & git branch --show-current 2>$null
if (-not $rama) { $rama = "main" }
git push origin $rama
if ($LASTEXITCODE -eq 0) {
    Write-Host "`nListo." -ForegroundColor Green
}
