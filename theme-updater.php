<?php
/**
 * Theme Updater
 */

function 4044_check_for_updates() {
    $current_version = wp_get_theme()->get('Version');
    $github_version = get_remote_file_contents('https://raw.githubusercontent.com/kevindem1488/4044-eu-theme/main/style.css');
    
    // Parse version from CSS
    if (preg_match('/Version: ([0-9.]+)/', $github_version, $matches)) {
        $latest_version = $matches[1];
        
        if (version_compare($current_version, $latest_version, '<')) {
            // Notify about update
            add_action('admin_notices', function() use ($latest_version) {
                echo '<div class="notice notice-info is-dismissible">';
                echo '<p>4044.eu theme update available: ' . $latest_version . '</p>';
                echo '</div>';
            });
        }
    }
}
add_action('init', '4044_check_for_updates');

/**
 * Get remote file
 */
function get_remote_file_contents($url) {
    $response = wp_remote_get($url);
    if (is_wp_error($response)) {
        return false;
    }
    return wp_remote_retrieve_body($response);
}