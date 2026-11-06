:: Mikhmon Server for Windows
:: Original Mikhmon Webserver (c) 2020 Laksamadi Guko. Community Edition by mikhmon.pro
::
:: Double-click for the menu, or run from a shortcut or Task Scheduler:
::   MikhmonServer.cmd start   start the server in the background
::   MikhmonServer.cmd stop    stop the server
@echo off
setlocal EnableExtensions
cd /d "%~dp0"

set "PHPVER=8.5.12"
set "PHPDIR=%~dp0php"
set "ROOTDIR=%~dp0mikhmon"
set "SERVER=%PHPDIR%\m-server.exe"
set "PORTFILE=%PHPDIR%\port.txt"
set "LOGFILE=%PHPDIR%\server.log"
set "PORTS=8080 8088 8090 8181 8888"
set "PORT="
set "LANURL="
set "MODE=%~1"

if /i "%MODE%"=="stop" goto cli_stop
if /i "%MODE%"=="start" goto checks
color 0F
mode con: cols=62 lines=26
title Mikhmon Server
call :banner

:checks
if not exist "%ROOTDIR%\index.php" (
  echo  Folder "mikhmon" not found next to this file.
  echo  Extract the whole ZIP first, then run MikhmonServer.cmd again.
  goto fail
)

if /i "%PROCESSOR_ARCHITECTURE%"=="x86" if not defined PROCESSOR_ARCHITEW6432 (
  echo  This build needs 64-bit Windows 10 or 11.
  goto fail
)

call :prepare_php || goto fail
if /i "%MODE%"=="start" goto cli_start
call :lan_ip

:status
call :running
if defined RUNNING goto menu_running
goto menu_stopped

:: ---------------------------------------------------------------
:menu_stopped
cls
call :header
echo   Status : STOPPED
echo.
echo   [1] Start server
echo   [0] Exit
echo.
choice /c 10 /n /m "  Press 1 or 0: "
if errorlevel 2 goto quit
call :start_server
if errorlevel 1 (
  echo.
  pause
  goto status
)
call :open_browser
goto status

:menu_running
cls
call :header
echo   Status : RUNNING (PHP %PHPVER%)
echo.
echo   This PC      : http://127.0.0.1:%PORT%
if defined LANURL echo   Other device : %LANURL%:%PORT%
echo.
echo   [1] Open Mikhmon in browser
echo   [2] Restart server
echo   [3] Stop server
echo   [4] Show server log
echo   [0] Exit (server keeps running)
echo.
choice /c 12340 /n /m "  Press 1, 2, 3, 4 or 0: "
if errorlevel 5 goto quit
if errorlevel 4 goto show_log
if errorlevel 3 goto do_stop
if errorlevel 2 goto do_restart
call :open_browser
goto status

:do_restart
call :stop_server
call :start_server
if errorlevel 1 pause
goto status

:do_stop
call :stop_server
goto status

:show_log
cls
call :header
echo   Last lines of php\server.log:
echo.
if exist "%LOGFILE%" (
  powershell -NoProfile -Command "Get-Content -LiteralPath $env:LOGFILE -Tail 15"
) else (
  echo   No log yet.
)
echo.
pause
goto status

:cli_start
call :running
if defined RUNNING (
  echo   Mikhmon is already running: http://127.0.0.1:%PORT%
  exit /b 0
)
call :start_server || exit /b 1
echo   Mikhmon is running: http://127.0.0.1:%PORT%
exit /b 0

:cli_stop
call :stop_server
exit /b 0

:quit
endlocal
exit /b 0

:fail
if /i "%MODE%"=="start" exit /b 1
echo.
pause
endlocal
exit /b 1

:: ---------------------------------------------------------------
:: Extract PHP on first run, or when php.zip holds a newer version.
:prepare_php
set "HAVEVER="
if exist "%PHPDIR%\VERSION" set /p HAVEVER=<"%PHPDIR%\VERSION"
if exist "%SERVER%" if "%HAVEVER%"=="%PHPVER%" goto check_php
if not exist "%PHPDIR%\php.zip" (
  echo  php\php.zip is missing. Download Mikhmon for Windows again:
  echo  https://mikhmon.pro/download
  exit /b 1
)
echo  Preparing PHP %PHPVER%, please wait...
taskkill /f /im m-server.exe >nul 2>&1
tar -xf "%PHPDIR%\php.zip" -C "%PHPDIR%" >nul 2>&1
if errorlevel 1 powershell -NoProfile -Command "Expand-Archive -LiteralPath $env:PHPDIR\php.zip -DestinationPath $env:PHPDIR -Force"
if not exist "%SERVER%" (
  echo  Could not extract php\php.zip.
  exit /b 1
)

:check_php
"%SERVER%" -n -v >nul 2>&1
if not errorlevel 1 exit /b 0
echo  PHP could not start on this PC. Install the Microsoft
echo  Visual C++ Redistributable (x64), then run this file again:
echo  https://aka.ms/vs/17/release/vc_redist.x64.exe
if /i "%MODE%"=="start" exit /b 1
echo.
choice /c YN /n /m "  Open the download page now? [Y/N] "
if not errorlevel 2 start "" "https://aka.ms/vs/17/release/vc_redist.x64.exe"
exit /b 1

:: Sets RUNNING and PORT when m-server.exe is up.
:running
set "RUNNING="
tasklist /nh /fi "IMAGENAME eq m-server.exe" 2>nul | find /i "m-server.exe" >nul || exit /b 0
set "RUNNING=1"
if exist "%PORTFILE%" set /p PORT=<"%PORTFILE%"
if not defined PORT set "PORT=8080"
exit /b 0

:start_server
set "PORT="
for %%p in (%PORTS%) do if not defined PORT call :port_free %%p && set "PORT=%%p"
if not defined PORT (
  echo   Ports %PORTS% are all in use.
  exit /b 1
)
echo   Starting server on port %PORT%...
>"%PORTFILE%" echo %PORT%
powershell -NoProfile -Command "Start-Process -WindowStyle Hidden -FilePath $env:SERVER -WorkingDirectory $env:PHPDIR -ArgumentList '-c','php.ini','-S',('0.0.0.0:'+$env:PORT),'-t',('\"'+$env:ROOTDIR+'\"') -RedirectStandardError $env:LOGFILE"
for /l %%i in (1,1,20) do (
  call :port_free %PORT% || exit /b 0
  ping -n 2 127.0.0.1 >nul
)
echo   The server did not start. Check php\server.log.
exit /b 1

:stop_server
echo   Stopping server...
taskkill /f /im m-server.exe >nul 2>&1
ping -n 2 127.0.0.1 >nul
exit /b 0

:: Exit code 0 when no local TCP socket uses port %1.
:port_free
netstat -an | findstr /r /c:"^ *TCP  *[^ ]*:%1 " >nul && exit /b 1
exit /b 0

:open_browser
start "" "http://127.0.0.1:%PORT%"
exit /b 0

:lan_ip
for /f "usebackq delims=" %%a in (`powershell -NoProfile -Command "(Get-NetIPConfiguration | Where-Object { $_.IPv4DefaultGateway -and $_.NetAdapter.Status -eq 'Up' } | Select-Object -First 1).IPv4Address.IPAddress" 2^>nul`) do set "LANURL=http://%%a"
exit /b 0

:header
echo.
echo   MIKHMON SERVER  ^|  Community Edition  ^|  mikhmon.pro
echo   ----------------------------------------------------------
echo.
exit /b 0

:banner
cls
echo.
echo 	    MMNdhyssssssssssssssssssyhdNMM
echo 	    MhsssssssssssssssssssssssssshM
echo 	    hsssssssso++++++++++ossssssssh
echo 	    sssssso-`            `-sssssss
echo 	    ssssss`.ssssssssssssss`.yyssss
echo 	    ssssso `-:+----//--oss` yhysss
echo 	    ssssso `/s/  `+s-  oss` yhhhys
echo 	    ssssso .ss/  :ss-  oss` yhhhhh
echo 	    ssssso .ss/  :ss-  oss` yhhhhh
echo 	    ssssso .ss/  :ss-  oss` yhhhhh
echo 	    ssssss`.ss/  :ss-  oss`.hhhhhh
echo 	    sssssso.```  ```   ```-yhhhhhh
echo 	    hssssssssooooooooooosyhhhhhhhd
echo 	    MdysssssssyyhhhhhhhhhhhhhhhhmM
echo 	    MMNmhysssssssyhhhhhhhhhhddmMMM
echo.
echo 	    	    MIKHMON SERVER
echo 		  by Laksamadi Guko
echo 	     Community Edition - mikhmon.pro
echo.
ping -n 3 127.0.0.1 >nul
exit /b 0
