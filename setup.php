<?php
/**
 * Quick Setup Script
 * Run this file in browser to perform initial setup
 * 
 * Usage: http://yoursite.com/wp-content/themes/4044-eu-theme/setup.php
 */

// Check if WordPress is loaded
if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(dirname(dirname(__FILE__))) . '/');
}

require_once(ABSPATH . 'wp-load.php');

// Check if user is admin
if (!is_admin() || !current_user_can('manage_options')) {
    die('Access denied. Please log in as administrator.');
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>4044.eu Theme Setup</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #0066cc, #000000);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .setup-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            max-width: 600px;
            width: 100%;
            padding: 40px;
        }
        h1 {
            color: #0066cc;
            margin-bottom: 10px;
            font-size: 2rem;
        }
        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 1.1rem;
        }
        .step {
            margin-bottom: 30px;
            padding-bottom: 30px;
            border-bottom: 1px solid #eee;
        }
        .step:last-child {
            border-bottom: none;
        }
        .step h3 {
            color: #000;
            margin-bottom: 15px;
            font-size: 1.2rem;
        }
        .step p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 10px;
        }
        .step a {
            color: #0066cc;
            text-decoration: none;
            font-weight: 600;
        }
        .step a:hover {
            text-decoration: underline;
        }
        .button {
            display: inline-block;
            background: #0066cc;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 10px;
            font-weight: 600;
            transition: background 0.3s;
        }
        .button:hover {
            background: #004999;
        }
        .success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .info {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        code {
            background: #f5f5f5;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
        }
    </style>
</head>
<body>
    <div class="setup-container">
        <h1>🚀 4044.eu Setup</h1>
        <p class="subtitle">Welcome! Let's get your theme ready.</p>

        <div class="info">
            ✓ Theme is active and ready to configure
        </div>

        <div class="step">
            <h3>1️⃣ Configure API Keys</h3>
            <p>Your theme needs API keys to fetch sports data and generate AI content.</p>
            <p><strong>Football-Data.org:</strong> Get free API key at <a href="https://www.football-data.org/" target="_blank">football-data.org</a></p>
            <p><strong>OpenAI:</strong> Create account and get key at <a href="https://platform.openai.com/" target="_blank">platform.openai.com</a></p>
            <a href="<?php echo admin_url('customize.php'); ?>" class="button">Configure APIs</a>
        </div>

        <div class="step">
            <h3>2️⃣ Create Navigation Menu</h3>
            <p>Set up your main navigation menu with important pages.</p>
            <a href="<?php echo admin_url('nav-menus.php'); ?>" class="button">Create Menu</a>
        </div>

        <div class="step">
            <h3>3️⃣ Set Homepage</h3>
            <p>Configure your homepage and blog settings.</p>
            <a href="<?php echo admin_url('options-reading.php'); ?>" class="button">Reading Settings</a>
        </div>

        <div class="step">
            <h3>4️⃣ Access Control Panel</h3>
            <p>Manage all theme features from the custom admin panel.</p>
            <a href="<?php echo admin_url('admin.php?page=4044-control-panel'); ?>" class="button">Open Control Panel</a>
        </div>

        <div class="step">
            <h3>5️⃣ Create Content</h3>
            <p>Start creating posts, live updates, and sports events.</p>
            <a href="<?php echo admin_url('post-new.php'); ?>" class="button">Create Post</a>
            <a href="<?php echo admin_url('post-new.php?post_type=live_update'); ?>" class="button">Create Live Update</a>
        </div>

        <div class="step">
            <h3>📚 Documentation</h3>
            <p>Read the complete documentation for detailed information.</p>
            <a href="https://github.com/kevindem1488/4044-eu-theme/blob/main/README.md" target="_blank" class="button">View Documentation</a>
        </div>
    </div>
</body>
</html>