@echo off
setlocal enabledelayedexpansion
set "SCRIPT_DIR=%~dp0"
:: Ask user for project name
set /p PROJECT_NAME=Enter your project name: 

:: Create Vite project
echo Creating Vite project "%PROJECT_NAME%"...
call npm create vite@latest %PROJECT_NAME% -- --template react

:: Move into project directory
cd %PROJECT_NAME%

:: Delete default src folder
echo Removing default src folder...
rmdir /s /q src

:: Copy custom src folder from parent
echo Copying custom src folder from script directory...
xcopy "%SCRIPT_DIR%src" "src" /E /I /Y

xcopy "%SCRIPT_DIR%php" "php" /E /I /Y

copy "%SCRIPT_DIR%index.html" "index.html" /y

rmdir /s /q public
echo Copying custom public folder from script directory...
xcopy "%SCRIPT_DIR%public" "public" /E /I /Y


:: Install base dependencies
echo Installing base dependencies...
call npm install

:: Install additional packages
echo Installing additional packages...
call npm install axios react-helmet react-router-dom universal-cookie
 

echo ✅ Setup complete!
echo Your React project is ready in: %cd%
start http://localhost:5173
call npm run dev
