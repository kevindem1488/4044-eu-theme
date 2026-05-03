<?php
/**
 * AI Content Generation (OpenAI Integration)
 */

/**
 * Generate image using OpenAI DALL-E
 */
function generate_ai_image($prompt) {
    $api_key = get_theme_mod('openai_api_key');
    
    if (empty($api_key)) {
        return false;
    }
    
    $response = wp_remote_post('https://api.openai.com/v1/images/generations', array(
        'headers' => array(
            'Authorization' => 'Bearer ' . $api_key,
            'Content-Type' => 'application/json',
        ),
        'body' => json_encode(array(
            'prompt' => $prompt . ' (news, professional, 4K)',
            'n' => 1,
            'size' => '1024x576',
            'quality' => 'standard',
        )),
        'timeout' => 60,
    ));
    
    if (is_wp_error($response)) {
        return false;
    }
    
    $body = json_decode(wp_remote_retrieve_body($response), true);
    
    if (isset($body['data'][0]['url'])) {
        return $body['data'][0]['url'];
    }
    
    return false;
}

/**
 * Expand content using OpenAI GPT
 */
function expand_content_with_ai($content) {
    $api_key = get_theme_mod('openai_api_key');
    
    if (empty($api_key)) {
        return $content;
    }
    
    $prompt = "Expand and improve this news content, make it more detailed and engaging. Keep it factual and professional:\n\n" . $content;
    
    $response = wp_remote_post('https://api.openai.com/v1/chat/completions', array(
        'headers' => array(
            'Authorization' => 'Bearer ' . $api_key,
            'Content-Type' => 'application/json',
        ),
        'body' => json_encode(array(
            'model' => 'gpt-3.5-turbo',
            'messages' => array(
                array(
                    'role' => 'system',
                    'content' => 'You are a professional news writer. Expand and improve content while keeping facts accurate.',
                ),
                array(
                    'role' => 'user',
                    'content' => $prompt,
                ),
            ),
            'temperature' => 0.7,
            'max_tokens' => 1000,
        )),
        'timeout' => 60,
    ));
    
    if (is_wp_error($response)) {
        return $content;
    }
    
    $body = json_decode(wp_remote_retrieve_body($response), true);
    
    if (isset($body['choices'][0]['message']['content'])) {
        return $body['choices'][0]['message']['content'];
    }
    
    return $content;
}

/**
 * Attach image to post
 */
function attach_image_to_post($post_id, $image_url) {
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    
    // Download image
    $tmp = download_url($image_url);
    
    if (is_wp_error($tmp)) {
        return false;
    }
    
    // Move to uploads
    $file_array = array(
        'name' => basename($image_url),
        'tmp_name' => $tmp,
    );
    
    $attachment_id = media_handle_sideload($file_array, $post_id);
    
    if (!is_wp_error($attachment_id)) {
        set_post_thumbnail($post_id, $attachment_id);
        return true;
    }
    
    @unlink($tmp);
    return false;
}

/**
 * Generate article title using AI
 */
function generate_article_title($keywords) {
    $api_key = get_theme_mod('openai_api_key');
    
    if (empty($api_key)) {
        return '';
    }
    
    $response = wp_remote_post('https://api.openai.com/v1/chat/completions', array(
        'headers' => array(
            'Authorization' => 'Bearer ' . $api_key,
            'Content-Type' => 'application/json',
        ),
        'body' => json_encode(array(
            'model' => 'gpt-3.5-turbo',
            'messages' => array(
                array(
                    'role' => 'user',
                    'content' => "Generate a compelling news headline for these keywords: " . $keywords . ". Keep it under 80 characters and in English.",
                ),
            ),
            'temperature' => 0.8,
            'max_tokens' => 50,
        )),
        'timeout' => 30,
    ));
    
    if (is_wp_error($response)) {
        return '';
    }
    
    $body = json_decode(wp_remote_retrieve_body($response), true);
    
    if (isset($body['choices'][0]['message']['content'])) {
        return trim($body['choices'][0]['message']['content']);
    }
    
    return '';
}