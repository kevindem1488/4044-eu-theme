<?php
/**
 * Plugin Name: 4044 Random Joke Generator
 * Plugin URI: https://github.com/kevindem1488/4044-eu-theme
 * Description: Provides a shortcode and REST endpoint that returns a random joke from an external API (JokeAPI). Shortcode: [4044_random_joke]
 * Version: 1.1.0
 * Author: kevindem1488
 * Author URI: https://github.com/kevindem1488
 * License: GPL v2 or later
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Default option name
define('FOUR_O_FOUR_JOKE_OPTIONS', '4044_joke_settings_options');

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
    $opts = get_option(FOUR_O_FOUR_JOKE_OPTIONS, array());

    $api_url = isset($opts['api_url']) && !empty($opts['api_url'])
        ? esc_url_raw($opts['api_url'])
        : 'https://v2.jokeapi.dev/joke/Any?blacklistFlags=nsfw,sexist,explicit&type=single,twopart';

    $cache_ttl = isset($opts['cache_ttl']) ? intval($opts['cache_ttl']) : (5 * MINUTE_IN_SECONDS);
    if ($cache_ttl < 0) $cache_ttl = 0;

    // Allow force bypass when current user can edit_posts and explicitly requests ?force=1
    $force = isset($_GET['force']) && intval($_GET['force']) === 1 && current_user_can('edit_posts');

    // Try transient first unless forced or ttl == 0
    $transient_key = '4044_random_joke';
    if (!$force && $cache_ttl > 0) {
        $cached = get_transient($transient_key);
        if ($cached && is_array($cached)) {
            return rest_ensure_response(array('source' => 'cache', 'joke' => $cached));
        }
    }

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
        $joke['text'] = trim($setup . "\n" . $delivery);
        $joke['setup'] = $setup;
        $joke['delivery'] = $delivery;
    } else {
        // Fallback
        $joke['type'] = 'unknown';
        $joke['text'] = wp_kses_post(wp_json_encode($data));
    }

    // Cache if TTL > 0
    if ($cache_ttl > 0) {
        set_transient($transient_key, $joke, $cache_ttl);
    }

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
        'autoload' => '0', // set to '1' to auto-load
    ), $atts, '4044_random_joke');

    $autoload_attr = $atts['autoload'] === '1' ? ' data-autoload="1"' : '';

    $html  = '<div class="' . esc_attr($atts['container_class']) . '"' . $autoload_attr . '>';
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
    wp_register_script('4044-joke-js', $plugin_url . 'assets/joke.js', array(), '1.0.0', true);
    wp_localize_script('4044-joke-js', 'fouro_four_joke_data', array(
        'rest_url' => esc_url_raw(rest_url('4044/v1/joke')),
        'nonce'    => wp_create_nonce('wp_rest'),
    ));

    wp_register_style('4044-joke-css', $plugin_url . 'assets/joke.css', array(), '1.0.0');
}
add_action('init', 'fouro_four_joke_assets');

/**
 * ADMIN: add settings page
 */
add_action('admin_menu', 'fouro_four_joke_admin_menu');
function fouro_four_joke_admin_menu()
{
    add_options_page(
        __('4044 Joke Settings', '4044-eu-theme'),
        __('4044 Jokes', '4044-eu-theme'),
        'manage_options',
        '4044-joke-settings',
        'fouro_four_joke_settings_page_html'
    );
}

add_action('admin_init', 'fouro_four_joke_settings_init');
function fouro_four_joke_settings_init()
{
    // Register settings: option group MUST match settings_fields() call
    register_setting('4044_joke_settings', FOUR_O_FOUR_JOKE_OPTIONS, 'fouro_four_joke_sanitize_options');

    // Section
    add_settings_section(
        '4044_joke_api_section',
        __('API Settings', '4044-eu-theme'),
        'fouro_four_joke_api_section_cb',
        '4044-joke-settings' // page
    );

    // Fields
    add_settings_field(
        '4044_joke_api_url',
        __('Joke API URL', '4044-eu-theme'),
        'fouro_four_joke_api_url_field_cb',
        '4044-joke-settings',
        '4044_joke_api_section'
    );

    add_settings_field(
        '4044_joke_cache_ttl',
        __('Cache TTL (seconds)', '4044-eu-theme'),
        'fouro_four_joke_cache_ttl_field_cb',
        '4044-joke-settings',
        '4044_joke_api_section'
    );
}

function fouro_four_joke_api_section_cb() {
    echo '<p>' . esc_html__('Configure external Joke API and caching behavior.', '4044-eu-theme') . '</p>';
}

function fouro_four_joke_api_url_field_cb()
{
    $opts = get_option(FOUR_O_FOUR_JOKE_OPTIONS, array());
    $val = isset($opts['api_url']) ? esc_attr($opts['api_url']) : 'https://v2.jokeapi.dev/joke/Any?blacklistFlags=nsfw,sexist,explicit&type=single,twopart';
    echo '<input type="text" name="' . FOUR_O_FOUR_JOKE_OPTIONS . '[api_url]" value="' . $val . '" class="regular-text" />';
    echo '<p class="description">' . esc_html__('Example: https://v2.jokeapi.dev/joke/Any?blacklistFlags=nsfw,sexist,explicit&type=single,twopart', '4044-eu-theme') . '</p>';
}

function fouro_four_joke_cache_ttl_field_cb()
{
    $opts = get_option(FOUR_O_FOUR_JOKE_OPTIONS, array());
    $val = isset($opts['cache_ttl']) ? intval($opts['cache_ttl']) : (5 * MINUTE_IN_SECONDS);
    echo '<input type="number" min="0" name="' . FOUR_O_FOUR_JOKE_OPTIONS . '[cache_ttl]" value="' . esc_attr($val) . '" class="small-text" />';
    echo '<p class="description">' . esc_html__('Number of seconds to cache a joke. Set 0 to disable caching.', '4044-eu-theme') . '</p>';
}

function fouro_four_joke_sanitize_options($input)
{
    $out = array();
    if (isset($input['api_url'])) {
        $out['api_url'] = esc_url_raw(trim($input['api_url']));
    }
    if (isset($input['cache_ttl'])) {
        $out['cache_ttl'] = max(0, intval($input['cache_ttl']));
    }
    return $out;
}

function fouro_four_joke_settings_page_html()
{
    if (!current_user_can('manage_options')) {
        return;
    }
    ?>
    <div class="wrap">
        <h1><?php echo esc_html__('4044 Random Joke Settings', '4044-eu-theme'); ?></h1>
        <form action="options.php" method="post">
            <?php
                // MUST match register_setting() first parameter
                settings_fields('4044_joke_settings');
                // MUST match the page slug used in add_settings_section/add_settings_field
                do_settings_sections('4044-joke-settings');
                submit_button();
            ?>
        </form>
    </div>
    <?php
}

/**
 * Clear transient on plugin activation/deactivation
 */
function fouro_four_joke_activate()
{
    delete_transient('4044_random_joke');
}
register_activation_hook(__FILE__, 'fouro_four_joke_activate');
register_deactivation_hook(__FILE__, 'fouro_four_joke_activate');
