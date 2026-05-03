#!/usr/bin/env php
<?php
/**
 * CLI Helper for 4044.eu Theme
 * Usage: php cli.php [command] [arguments]
 */

if (php_sapi_name() !== 'cli') {
    die('This script can only be run from command line.');
}

require_once(dirname(__FILE__) . '/../../../../wp-load.php');

$command = $argv[1] ?? 'help';

switch ($command) {
    case 'sync-news':
        echo "Syncing Google News...\n";
        sync_google_news();
        echo "Done!\n";
        break;
    
    case 'test-api':
        $api = $argv[2] ?? 'all';
        test_apis($api);
        break;
    
    case 'setup':
        echo "Running setup...\n";
        create_placeholder_images();
        echo "Setup complete!\n";
        break;
    
    case 'help':
    default:
        show_help();
        break;
}

function show_help() {
    echo "\n4044.eu Theme CLI\n";
    echo "==================\n\n";
    echo "Commands:\n";
    echo "  php cli.php sync-news      - Sync Google News\n";
    echo "  php cli.php test-api       - Test all APIs\n";
    echo "  php cli.php test-api football - Test football-data API\n";
    echo "  php cli.php test-api openai   - Test OpenAI API\n";
    echo "  php cli.php setup          - Run setup\n";
    echo "  php cli.php help           - Show this help\n\n";
}

function test_apis($api = 'all') {
    echo "Testing APIs...\n";
    
    if ($api === 'all' || $api === 'football') {
        echo "\nTesting Football-Data API...\n";
        $key = get_theme_mod('football_data_api_key');
        if (!$key) {
            echo "  ✗ No API key configured\n";
        } else {
            $response = wp_remote_get('https://api.football-data.org/v4/matches', array(
                'headers' => array('X-Auth-Token' => $key),
            ));
            echo is_wp_error($response) ? "  ✗ Error\n" : "  ✓ OK\n";
        }
    }
    
    if ($api === 'all' || $api === 'openai') {
        echo "\nTesting OpenAI API...\n";
        $key = get_theme_mod('openai_api_key');
        if (!$key) {
            echo "  ✗ No API key configured\n";
        } else {
            $response = wp_remote_post('https://api.openai.com/v1/models', array(
                'headers' => array('Authorization' => 'Bearer ' . $key),
            ));
            echo is_wp_error($response) ? "  ✗ Error\n" : "  ✓ OK\n";
        }
    }
}

?>