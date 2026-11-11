@echo off
title Royal Collection - Luxury Apparel (Port 8090)
cd /d "%~dp0"
echo Starting Royal Collection Laravel Storefront at http://127.0.0.1:8090 ...
C:\xampp\php\php.exe artisan serve --host=127.0.0.1 --port=8090
pause
