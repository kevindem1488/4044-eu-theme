@echo off
REM 4044.eu WordPress Theme Installer for Windows
REM This script downloads and installs the theme

echo ========================================
echo 4044.eu Theme Installer
echo ========================================
echo.

REM Check if in WordPress directory
if not exist "wp-config.php" (
    echo Error: wp-config.php not found.
    echo Please run this script from your WordPress root directory.
    pause
    exit /b 1
)

echo X WordPress installation detected
echo.

REM Create themes directory if it doesn't exist
if not exist "wp-content\themes" (
    mkdir wp-content\themes
    echo X Created themes directory
)

echo.
echo Downloading 4044.eu theme...
echo.

cd wp-content\themes

if exist "4044-eu-theme" (
    echo Theme directory already exists. Updating...
    cd 4044-eu-theme
    git pull origin main
    cd ..
) else (
    git clone https://github.com/kevindem1488/4044-eu-theme.git
    echo X Theme downloaded successfully
)

cd ...\..

echo.
echo ========================================
echo X Installation Complete!
echo ========================================
echo.
echo Next Steps:
echo 1. Go to WordPress Admin Dashboard
echo 2. Navigate to: Appearance - Themes
echo 3. Find '4044.eu - Live News Europe'
echo 4. Click 'Activate'
echo 5. Go to: 4044 Panel - Configure APIs
echo.
echo Documentation: wp-content\themes\4044-eu-theme\README.md
echo.
pause
