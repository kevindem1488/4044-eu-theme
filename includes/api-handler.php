<?php
/**
 * REST API Handler for Live Updates
 */

add_action('rest_api_init', function() {
    register_rest_route('4044/v1', '/live-updates', array(
        'methods' => 'GET',
        'callback' => 'get_live_updates',
        'permission_callback' => '__return_true',
    ));
    
    register_rest_route('4044/v1', '/sports/matches', array(
        'methods' => 'GET',
        'callback' => 'get_sports_matches',
        'permission_callback' => '__return_true',
    ));
    
    register_rest_route('4044/v1', '/news/publish', array(
        'methods' => 'POST',
        'callback' => 'publish_news_with_ai',
        'permission_callback' => 'current_user_can:edit_posts',
    ));
});

/**
 * Get Live Updates
 */
function get_live_updates() {
    $args = array(
        'post_type' => 'live_update',
        'posts_per_page' => 10,
        'orderby' => 'date',
        'order' => 'DESC',
    );
    
    $updates = get_posts($args);
    $result = array();
    
    foreach ($updates as $update) {
        $result[] = array(
            'id' => $update->ID,
            'title' => $update->post_title,
            'excerpt' => wp_trim_words($update->post_content, 20),
            'link' => get_permalink($update),
            'date' => $update->post_date,
            'image' => get_the_post_thumbnail_url($update->ID) ?: THEME_URI . '/assets/images/placeholder.jpg',
        );
    }
    
    return rest_ensure_response($result);
}

/**
 * Get Sports Matches
 */
function get_sports_matches() {
    // Get cached matches or fetch from football-data.org
    $cache_key = '4044_sports_matches';
    $matches = get_transient($cache_key);
    
    if (false === $matches) {
        $api_key = get_theme_mod('football_data_api_key');
        
        if (empty($api_key)) {
            return rest_ensure_response(array(
                'matches' => array(),
                'message' => 'API key not configured'
            ));
        }
        
        $response = wp_remote_get('https://api.football-data.org/v4/matches?status=SCHEDULED,LIVE,FINISHED', array(
            'headers' => array('X-Auth-Token' => $api_key),
            'timeout' => 30,
        ));
        
        if (!is_wp_error($response)) {
            $body = json_decode(wp_remote_retrieve_body($response), true);
            $matches = $body['matches'] ?? array();
            
            // Cache for 5 minutes
            set_transient($cache_key, $matches, 5 * MINUTE_IN_SECONDS);
        } else {
            $matches = array();
        }
    }
    
    return rest_ensure_response(array('matches' => $matches));
}

/**
 * Publish News with AI Content Generation
 */
function publish_news_with_ai($request) {
    if (!current_user_can('edit_posts')) {
        return new WP_Error('unauthorized', 'Unauthorized', array('status' => 401));
    }
    
    $params = $request->get_json_params();
    $title = sanitize_text_field($params['title'] ?? '');
    $content = sanitize_textarea_field($params['content'] ?? '');
    $category = intval($params['category'] ?? 0);
    
    if (empty($title)) {
        return new WP_Error('missing_title', 'Title is required');
    }
    
    // Generate image using AI
    $image_url = generate_ai_image($title);
    
    // Expand content using AI if needed
    if (!empty($content)) {
        $content = expand_content_with_ai($content);
    }
    
    // Create post
    $post_id = wp_insert_post(array(
        'post_type' => 'post',
        'post_title' => $title,
        'post_content' => $content,
        'post_category' => array($category),
        'post_status' => 'publish',
    ));
    
    if (is_wp_error($post_id)) {
        return new WP_Error('post_creation_failed', 'Failed to create post');
    }
    
    // Attach featured image
    if ($image_url) {
        attach_image_to_post($post_id, $image_url);
    }
    
    return rest_ensure_response(array(
        'success' => true,
        'post_id' => $post_id,
        'message' => 'Article published successfully'
    ));
}