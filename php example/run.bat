@echo off
title KynexAuth PHP Matrix Console
cls

set PHP_EXE=php

:: Check if php is in PATH
where php >nul 2>nul
if %errorlevel% neq 0 (
    :: Check winget installed location
    if exist "%LOCALAPPDATA%\Microsoft\WinGet\Packages\PHP.PHP.8.2_Microsoft.Winget.Source_8wekyb3d8bbwe\php.exe" (
        set PHP_EXE="%LOCALAPPDATA%\Microsoft\WinGet\Packages\PHP.PHP.8.2_Microsoft.Winget.Source_8wekyb3d8bbwe\php.exe"
    ) else (
        echo [!] PHP is not found on your system!
        echo [*] Please run: winget install PHP.PHP.8.2
        pause
        exit /b 1
    )
)

%PHP_EXE% index.php
pause
