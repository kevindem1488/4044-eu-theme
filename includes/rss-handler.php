<?php
/**
 * RSS Feed Handler with Google News Integration
 */

/**
 * Create custom RSS feeds
 */
add_action('init', function() {
    add_feed('google-news', 'google_news_feed_template');
    add_feed('live-updates', 'live_updates_feed_template');
    add_feed('sports', 'sports_feed_template');
});

/**
 * Google News RSS Template
 */
function google_news_feed_template() {
    load_template(THEME_DIR . '/templates/rss-google-news.php');
}

/**
 * Live Updates RSS Template
 */
function live_updates_feed_template() {
    load_template(THEME_DIR . '/templates/rss-live-updates.php');
}

/**
 * Sports RSS Template
 */
function sports_feed_template() {
    load_template(THEME_DIR . '/templates/rss-sports.php');
}

/**
 * Fetch Google News via RSS and store in custom feed
 */
function sync_google_news() {
    require_once(ABSPATH . WPINC . '/feed.php');
    
    $rss_url = 'https://news.google.com/rss/search?q=Europe+news&hl=en-US&gl=US&ceid=US:en';
    
    $feed = fetch_feed($rss_url);
    
    if (is_wp_error($feed)) {
        return false;
    }
    
    $items = $feed->get_items(0, 20);
    
    foreach ($items as $item) {
        $title = $item->get_title();
        $link = $item->get_link();
        $content = $item->get_content();
        $date = $item->get_date('U');
        
        // Check if post already exists
        $existing = get_posts(array(
            'post_title' => $title,
            'post_type' => 'post',
            'numberposts' => 1,
        ));
        
        if (empty($existing)) {
            wp_insert_post(array(
                'post_type' => 'post',
                'post_title' => $title,
                'post_content' => $content,
                'post_date' => date('Y-m-d H:i:s', $date),
                'post_status' => 'publish',
                'post_category' => array(1),
            ));
        }
    }
    
    return true;
}

/**
 * Schedule automatic Google News sync
 */
add_action('init', function() {
    if (!wp_next_scheduled('sync_google_news_hook')) {
        wp_schedule_event(time(), 'hourly', 'sync_google_news_hook');
    }
});

add_action('sync_google_news_hook', 'sync_google_news');

/**
 * Add RSS link to header
 */
add_action('wp_head', function() {
    echo '<link rel="alternate" type="application/rss+xml" title="' . get_bloginfo('name') . ' - All News" href="' . home_url('/feed/') . '" />';
    echo '<link rel="alternate" type="application/rss+xml" title="' . get_bloginfo('name') . ' - Live Updates" href="' . home_url('/feed/live-updates/') . '" />';
    echo '<link rel="alternate" type="application/rss+xml" title="' . get_bloginfo('name') . ' - Sports" href="' . home_url('/feed/sports/') . '" />';
    echo '<link rel="alternate" type="application/rss+xml" title="' . get_bloginfo('name') . ' - Google News" href="' . home_url('/feed/google-news/') . '" />';
});