@echo off
REM ============================================================
REM  Bagikan aplikasi lewat INTERNET dengan ngrok (link https).
REM  Klik dua kali file ini. Link untuk pengguna ada di baris
REM  "Forwarding" (https://....ngrok-free.app atau .dev).
REM  Syarat: XAMPP MySQL menyala dan laptop ini tetap hidup.
REM  Tutup jendela ini untuk berhenti berbagi.
REM ============================================================
cd /d "%~dp0"
title Berbagi lewat internet (ngrok) - portal-toko3

REM Cari ngrok di PATH, kalau tidak ada pakai bawaan Laragon
set NGROK=ngrok
where ngrok >nul 2>nul
if errorlevel 1 set NGROK="C:\laragon\bin\ngrok\ngrok.exe"
if not exist "C:\laragon\bin\ngrok\ngrok.exe" (
    where ngrok >nul 2>nul
    if errorlevel 1 (
        echo ngrok belum terpasang. Unduh dari https://ngrok.com/download
        pause
        exit /b 1
    )
)

REM Server aplikasi di jendela terpisah, kecuali sudah berjalan di port 8080
REM (hanya bisa diakses lewat ngrok, tidak dibuka ke WiFi)
curl -s -f -o nul -m 3 http://localhost:8080/login
if errorlevel 1 (
    start "Server - portal-toko3" cmd /k C:\xampp\php\php.exe spark serve --host 127.0.0.1 --port 8080
) else (
    echo Server aplikasi sudah berjalan di port 8080.
)

echo Link untuk pengguna ada di baris "Forwarding" di bawah ini.
echo.
%NGROK% http 8080
pause
