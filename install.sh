#!/bin/bash

# 4044.eu WordPress Theme Installer
# This script downloads and installs the theme

echo "========================================"
echo "4044.eu Theme Installer"
echo "========================================"
echo ""

# Check if in WordPress directory
if [ ! -f "wp-config.php" ]; then
    echo "Error: wp-config.php not found."
    echo "Please run this script from your WordPress root directory."
    exit 1
fi

echo "✓ WordPress installation detected"

# Create themes directory if it doesn't exist
if [ ! -d "wp-content/themes" ]; then
    mkdir -p wp-content/themes
    echo "✓ Created themes directory"
fi

# Download theme
echo ""
echo "Downloading 4044.eu theme..."

cd wp-content/themes

if [ -d "4044-eu-theme" ]; then
    echo "Theme directory already exists. Updating..."
    cd 4044-eu-theme
    git pull origin main
    cd ..
else
    git clone https://github.com/kevindem1488/4044-eu-theme.git
    echo "✓ Theme downloaded successfully"
fi

cd ../..

echo ""
echo "========================================"
echo "✓ Installation Complete!"
echo "========================================"
echo ""
echo "Next Steps:"
echo "1. Go to WordPress Admin Dashboard"
echo "2. Navigate to: Appearance → Themes"
echo "3. Find '4044.eu - Live News Europe'"
echo "4. Click 'Activate'"
echo "5. Go to: 4044 Panel → Configure APIs"
echo ""
echo "Documentation: wp-content/themes/4044-eu-theme/README.md"
echo ""
