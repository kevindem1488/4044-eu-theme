<?php
/**
 * Theme Functions and Definitions
 */

if (!defined('THEME_VERSION')) {
    define('THEME_VERSION', '1.0.0');
}

if (!defined('THEME_DIR')) {
    define('THEME_DIR', get_template_directory());
}

if (!defined('THEME_URI')) {
    define('THEME_URI', get_template_directory_uri());
}

/**
 * Setup Theme
 */
function theme_setup() {
    load_theme_textdomain('4044-eu-theme', THEME_DIR . '/languages');
    
    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    
    // Register image sizes
    add_image_size('article-featured', 400, 250, true);
    add_image_size('article-large', 800, 500, true);
    add_image_size('article-small', 150, 150, true);
    
    // Register menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', '4044-eu-theme'),
        'footer' => __('Footer Menu', '4044-eu-theme'),
        'mobile' => __('Mobile Menu', '4044-eu-theme'),
    ));
    
    add_theme_support('custom-logo');
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
    add_theme_support('wp-block-styles');
    add_theme_support('woocommerce');
}
add_action('after_setup_theme', 'theme_setup');

/**
 * Enqueue Styles and Scripts
 */
function enqueue_theme_assets() {
    // Styles
    wp_enqueue_style('4044-main-style', THEME_URI . '/style.css', array(), THEME_VERSION);
    wp_enqueue_style('4044-responsive', THEME_URI . '/assets/css/responsive.css', array(), THEME_VERSION);
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), '6.4.0');
    
    // Scripts
    wp_enqueue_script('4044-main-script', THEME_URI . '/assets/js/main.js', array('jquery'), THEME_VERSION, true);
    wp_enqueue_script('4044-api-handler', THEME_URI . '/assets/js/api-handler.js', array('jquery'), THEME_VERSION, true);
    
    // Localize script
    wp_localize_script('4044-main-script', '4044Config', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'restUrl' => rest_url(),
        'nonce' => wp_create_nonce('4044-nonce'),
        'siteUrl' => home_url(),
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_theme_assets');

/**
 * Register Custom Post Types
 */
function register_custom_post_types() {
    // Live Updates
    register_post_type('live_update', array(
        'label' => __('Live Updates', '4044-eu-theme'),
        'description' => __('Breaking news and live updates', '4044-eu-theme'),
        'public' => true,
        'show_in_rest' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'menu_icon' => 'dashicons-update',
    ));
    
    // Sports Events
    register_post_type('sports_event', array(
        'label' => __('Sports Events', '4044-eu-theme'),
        'description' => __('Sports matches and tournaments', '4044-eu-theme'),
        'public' => true,
        'show_in_rest' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail'),
        'menu_icon' => 'dashicons-format-status',
    ));
}
add_action('init', 'register_custom_post_types');

/**
 * Register Custom Taxonomies
 */
function register_custom_taxonomies() {
    register_taxonomy('news_type', 'post', array(
        'label' => __('News Types', '4044-eu-theme'),
        'public' => true,
        'show_in_rest' => true,
        'hierarchical' => false,
    ));
    
    register_taxonomy('sport', 'sports_event', array(
        'label' => __('Sport Type', '4044-eu-theme'),
        'public' => true,
        'show_in_rest' => true,
        'hierarchical' => true,
    ));
}
add_action('init', 'register_custom_taxonomies');

/**
 * Custom Columns for Admin
 */
function add_custom_admin_columns($columns) {
    $columns['featured_image'] = __('Image', '4044-eu-theme');
    $columns['news_type'] = __('Type', '4044-eu-theme');
    return $columns;
}
add_filter('manage_post_columns', 'add_custom_admin_columns');

/**
 * Widget Areas
 */
function register_widget_areas() {
    register_sidebar(array(
        'name' => __('Sidebar', '4044-eu-theme'),
        'id' => 'primary-sidebar',
        'description' => __('Primary Sidebar', '4044-eu-theme'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
    
    register_sidebar(array(
        'name' => __('Footer Widget Area 1', '4044-eu-theme'),
        'id' => 'footer-1',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
}
add_action('widgets_init', 'register_widget_areas');

/**
 * Customize Settings
 */
function customize_theme_settings($wp_customize) {
    // API Keys
    $wp_customize->add_section('api_settings', array(
        'title' => __('API Settings', '4044-eu-theme'),
        'priority' => 30,
    ));
    
    // Football Data API
    $wp_customize->add_setting('football_data_api_key', array(
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('football_data_api_key', array(
        'label' => __('Football-Data.org API Key', '4044-eu-theme'),
        'section' => 'api_settings',
        'type' => 'password',
    ));
    
    // OpenAI API
    $wp_customize->add_setting('openai_api_key', array(
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('openai_api_key', array(
        'label' => __('OpenAI API Key', '4044-eu-theme'),
        'section' => 'api_settings',
        'type' => 'password',
    ));
}
add_action('customize_register', 'customize_theme_settings');

/**
 * Include Additional Files
 */
require_once THEME_DIR . '/includes/api-handler.php';
require_once THEME_DIR . '/includes/sports-api.php';
require_once THEME_DIR . '/includes/rss-handler.php';
require_once THEME_DIR . '/includes/ai-content.php';
require_once THEME_DIR . '/includes/admin-panel.php';

/**
 * Security Headers
 */
function add_security_headers() {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('X-XSS-Protection: 1; mode=block');
}
add_action('wp_head', 'add_security_headers');

/**
 * Disable Gutenberg for specific post types
 */
add_filter('gutenberg_can_edit_post_type', '__return_false');