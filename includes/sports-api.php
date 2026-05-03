<?php
/**
 * Sports API Integration
 */

/**
 * Get matches from football-data.org
 */
function fetch_football_matches($league_id = null) {
    $api_key = get_theme_mod('football_data_api_key');
    
    if (empty($api_key)) {
        return false;
    }
    
    $url = 'https://api.football-data.org/v4/matches';
    
    if ($league_id) {
        $url .= '?competitions=' . $league_id;
    }
    
    $response = wp_remote_get($url, array(
        'headers' => array('X-Auth-Token' => $api_key),
        'timeout' => 30,
    ));
    
    if (is_wp_error($response)) {
        return false;
    }
    
    return json_decode(wp_remote_retrieve_body($response), true);
}

/**
 * Get league standings
 */
function fetch_league_standings($league_code) {
    $api_key = get_theme_mod('football_data_api_key');
    
    if (empty($api_key)) {
        return false;
    }
    
    $url = 'https://api.football-data.org/v4/competitions/' . $league_code . '/standings';
    
    $response = wp_remote_get($url, array(
        'headers' => array('X-Auth-Token' => $api_key),
        'timeout' => 30,
    ));
    
    if (is_wp_error($response)) {
        return false;
    }
    
    return json_decode(wp_remote_retrieve_body($response), true);
}

/**
 * Display football results table shortcode
 */
add_shortcode('4044_sports_table', function($atts) {
    $atts = shortcode_atts(array(
        'league' => 'all',
        'limit' => 10,
    ), $atts, '4044_sports_table');
    
    $matches = fetch_football_matches();
    
    if (!$matches || empty($matches['matches'])) {
        return '<p>' . __('No matches available', '4044-eu-theme') . '</p>';
    }
    
    $output = '<table class="sports-table">';
    $output .= '<thead><tr>';
    $output .= '<th>' . __('Date & Time', '4044-eu-theme') . '</th>';
    $output .= '<th>' . __('Home Team', '4044-eu-theme') . '</th>';
    $output .= '<th>' . __('Score', '4044-eu-theme') . '</th>';
    $output .= '<th>' . __('Away Team', '4044-eu-theme') . '</th>';
    $output .= '<th>' . __('Status', '4044-eu-theme') . '</th>';
    $output .= '<th>' . __('League', '4044-eu-theme') . '</th>';
    $output .= '</tr></thead>';
    $output .= '<tbody>';
    
    $count = 0;
    foreach ($matches['matches'] as $match) {
        if ($count >= intval($atts['limit'])) break;
        
        $status_class = strtolower($match['status']);
        $score = '-';
        
        if ($match['status'] !== 'SCHEDULED' && isset($match['score']['fullTime'])) {
            $score = $match['score']['fullTime']['home'] . ' - ' . $match['score']['fullTime']['away'];
        }
        
        $output .= '<tr>';
        $output .= '<td>' . date('d.m H:i', strtotime($match['utcDate'])) . '</td>';
        $output .= '<td class="match-team">' . esc_html($match['homeTeam']['name']) . '</td>';
        $output .= '<td class="match-score">' . esc_html($score) . '</td>';
        $output .= '<td class="match-team">' . esc_html($match['awayTeam']['name']) . '</td>';
        $output .= '<td><span class="match-status ' . $status_class . '">' . esc_html($match['status']) . '</span></td>';
        $output .= '<td>' . esc_html($match['competition']['name'] ?? 'N/A') . '</td>';
        $output .= '</tr>';
        
        $count++;
    }
    
    $output .= '</tbody>';
    $output .= '</table>';
    
    return $output;
});