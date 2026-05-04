<?php
/**
 * Plugin Name: 4044 Random Joke Generator
 * Plugin URI: https://github.com/kevindem1488/4044-eu-theme
 * Description: Provides a shortcode and REST endpoint that returns a random joke from an external API (JokeAPI). Shortcode: [4044_random_joke]
 * Version: 1.0.0
 * Author: kevindem1488
 * Author URI: https://github.com/kevindem1488
 * License: GPL v2 or later
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Register REST route
add_action('rest_api_init', function () {
    register_rest_route('4044/v1', '/joke', array(
        'methods' => 'GET',
        'callback' => 'fouro_four_random_joke_rest',
        'permission_callback' => '__return_true',
    ));
});

/**
 * REST callback: fetch a random joke from external API (cached)
 */
function fouro_four_random_joke_rest($request)
{
    // Try transient first
    $cached = get_transient('4044_random_joke');
    if ($cached && is_array($cached)) {
        return rest_ensure_response(array('source' => 'cache', 'joke' => $cached));
    }

    // External API: JokeAPI (https://v2.jokeapi.dev)
    $api_url = 'https://v2.jokeapi.dev/joke/Any?blacklistFlags=nsfw,sexist,explicit&type=single,twopart';

    $response = wp_remote_get($api_url, array('timeout' => 10));

    if (is_wp_error($response)) {
        return new WP_Error('api_error', 'Unable to fetch joke from external API.', array('status' => 502));
    }

    $code = wp_remote_retrieve_response_code($response);
    $body = wp_remote_retrieve_body($response);

    if ($code !== 200 || empty($body)) {
        return new WP_Error('api_error', 'Invalid response from joke API.', array('status' => 502));
    }

    $data = json_decode($body, true);
    if (json_last_error() !== JSON_ERROR_NONE || empty($data)) {
        return new WP_Error('api_error', 'Malformed JSON from joke API.', array('status' => 502));
    }

    $joke = array();
    if (isset($data['type']) && $data['type'] === 'single' && isset($data['joke'])) {
        $joke['type'] = 'single';
        $joke['text'] = sanitize_text_field($data['joke']);
    } elseif (isset($data['type']) && $data['type'] === 'twopart') {
        $joke['type'] = 'twopart';
        $setup = isset($data['setup']) ? sanitize_text_field($data['setup']) : '';
        $delivery = isset($data['delivery']) ? sanitize_text_field($data['delivery']) : '';
        $joke['text'] = trim($setup . '\n' . $delivery);
        $joke['setup'] = $setup;
        $joke['delivery'] = $delivery;
    } else {
        // Fallback - try to map from common fields
        $joke['type'] = 'unknown';
        $joke['text'] = sanitize_text_field(print_r($data, true));
    }

    // Cache for 5 minutes
    set_transient('4044_random_joke', $joke, 5 * MINUTE_IN_SECONDS);

    return rest_ensure_response(array('source' => 'api', 'joke' => $joke));
}

/**
 * Shortcode: [4044_random_joke]
 * Outputs a container and button that fetches joke via REST endpoint.
 */
function fouro_four_random_joke_shortcode($atts = array())
{
    // Ensure scripts/styles are enqueued only when shortcode used
    wp_enqueue_script('4044-joke-js');
    wp_enqueue_style('4044-joke-css');

    $atts = shortcode_atts(array(
        'button_text' => __('Tell me a joke', '4044-eu-theme'),
        'container_class' => '4044-joke-container',
    ), $atts, '4044_random_joke');

    $html = '<div class="' . esc_attr($atts['container_class']) . '">';
    $html .= '<div class="4044-joke-text" aria-live="polite">' . esc_html__('Press the button to get a joke...', '4044-eu-theme') . '</div>';
    $html .= '<button class="4044-joke-button btn" type="button">' . esc_html($atts['button_text']) . '</button>';
    $html .= '</div>';

    return $html;
}
add_shortcode('4044_random_joke', 'fouro_four_random_joke_shortcode');

/**
 * Enqueue scripts and styles
 */
function fouro_four_joke_assets()
{
    $plugin_url = plugin_dir_url(__FILE__);
    wp_register_script('4044-joke-js', $plugin_url . 'assets/joke.js', array('jquery'), '1.0.0', true);
    wp_localize_script('4044-joke-js', 'fouro_four_joke_data', array(
        'rest_url' => esc_url_raw(rest_url('4044/v1/joke')),
        'nonce' => wp_create_nonce('wp_rest'),
    ));

    wp_register_style('4044-joke-css', $plugin_url . 'assets/joke.css', array(), '1.0.0');
}
add_action('init', 'fouro_four_joke_assets');

/**
 * Clear transient on plugin activation/deactivation
 */
function fouro_four_joke_activate()
{
    delete_transient('4044_random_joke');
}
register_activation_hook(__FILE__, 'fouro_four_joke_activate');
register_deactivation_hook(__FILE__, 'fouro_four_joke_activate');
