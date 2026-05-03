<?php
/**
 * Theme Installation & Setup
 */

// Create necessary database tables on activation
function 4044_theme_activate() {
    // Create custom tables if needed
    global $wpdb;
    
    // Ensure required post types are registered
    register_post_type('live_update', array(
        'public' => true,
        'supports' => array('title', 'editor', 'thumbnail'),
    ));
    
    register_post_type('sports_event', array(
        'public' => true,
        'supports' => array('title', 'editor', 'thumbnail'),
    ));
    
    // Flush rewrite rules
    flush_rewrite_rules();
    
    // Set default theme options
    if (!get_option('4044_theme_activated')) {
        add_option('4044_theme_activated', true);
        
        // Create default pages
        create_default_pages();
        
        // Create default menu
        create_default_menu();
    }
}
register_activation_hook(__FILE__, '4044_theme_activate');

/**
 * Create default pages
 */
function create_default_pages() {
    $pages = array(
        array(
            'post_title' => 'Privacy Policy',
            'post_name' => 'privacy-policy',
            'post_content' => 'Your privacy policy content here.',
        ),
        array(
            'post_title' => 'Terms of Service',
            'post_name' => 'terms',
            'post_content' => 'Your terms and conditions here.',
        ),
        array(
            'post_title' => 'Contact Us',
            'post_name' => 'contact',
            'post_content' => '[contact_form]',
        ),
    );
    
    foreach ($pages as $page) {
        if (!get_page_by_path($page['post_name'])) {
            wp_insert_post(array(
                'post_type' => 'page',
                'post_title' => $page['post_title'],
                'post_name' => $page['post_name'],
                'post_content' => $page['post_content'],
                'post_status' => 'publish',
            ));
        }
    }
}

/**
 * Create default menu
 */
function create_default_menu() {
    $menu_name = 'Primary Menu';
    $menu_exists = wp_get_nav_menu_object($menu_name);
    
    if (!$menu_exists) {
        $menu_id = wp_create_nav_menu($menu_name);
        
        // Get pages
        $home = get_page_by_path('home');
        $privacy = get_page_by_path('privacy-policy');
        $terms = get_page_by_path('terms');
        $contact = get_page_by_path('contact');
        
        // Add menu items
        wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' => 'Home',
            'menu-item-url' => home_url(),
            'menu-item-status' => 'publish',
        ));
        
        if ($privacy) {
            wp_update_nav_menu_item($menu_id, 0, array(
                'menu-item-object-id' => $privacy->ID,
                'menu-item-object' => 'page',
                'menu-item-type' => 'post_type',
                'menu-item-status' => 'publish',
            ));
        }
        
        // Set menu location
        $locations = get_theme_mod('nav_menu_locations');
        $locations['primary'] = $menu_id;
        set_theme_mod('nav_menu_locations', $locations);
    }
}

// Deactivation
function 4044_theme_deactivate() {
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, '4044_theme_deactivate');