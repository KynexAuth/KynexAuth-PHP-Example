@echo off
title KynexAuth PHP Web Server
cls
echo ===================================================
echo        KynexAuth PHP Local Web Server
echo ===================================================
echo.

set PHP_EXE=php

where php >nul 2>nul
if %errorlevel% neq 0 (
    if exist "%LOCALAPPDATA%\Microsoft\WinGet\Packages\PHP.PHP.8.2_Microsoft.Winget.Source_8wekyb3d8bbwe\php.exe" (
        set PHP_EXE="%LOCALAPPDATA%\Microsoft\WinGet\Packages\PHP.PHP.8.2_Microsoft.Winget.Source_8wekyb3d8bbwe\php.exe"
    ) else (
        echo [!] PHP is not found on your system!
        echo [*] Please run: winget install PHP.PHP.8.2
        pause
        exit /b 1
    )
)

echo [*] Starting local web server at http://localhost:8000 ...
echo [*] Opening browser...
start http://localhost:8000/index.php
echo.
echo [✓] Web Server is RUNNING. Press Ctrl+C to stop.
echo ===================================================
echo.

%PHP_EXE% -S localhost:8000
pause
