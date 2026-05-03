<?php
/**
 * Custom Admin Panel for 4044.eu
 */

/**
 * Add admin menu
 */
add_action('admin_menu', function() {
    add_menu_page(
        __('4044 Control Panel', '4044-eu-theme'),
        __('4044 Panel', '4044-eu-theme'),
        'manage_options',
        '4044-control-panel',
        'render_4044_control_panel',
        'dashicons-admin-settings',
        3
    );
    
    add_submenu_page(
        '4044-control-panel',
        __('Live Updates', '4044-eu-theme'),
        __('Live Updates', '4044-eu-theme'),
        'manage_options',
        '4044-live-updates',
        'render_live_updates_panel'
    );
    
    add_submenu_page(
        '4044-control-panel',
        __('Sports Integration', '4044-eu-theme'),
        __('Sports', '4044-eu-theme'),
        'manage_options',
        '4044-sports-panel',
        'render_sports_panel'
    );
    
    add_submenu_page(
        '4044-control-panel',
        __('AI Content', '4044-eu-theme'),
        __('AI Tools', '4044-eu-theme'),
        'manage_options',
        '4044-ai-tools',
        'render_ai_tools_panel'
    );
    
    add_submenu_page(
        '4044-control-panel',
        __('RSS Settings', '4044-eu-theme'),
        __('RSS Feeds', '4044-eu-theme'),
        'manage_options',
        '4044-rss-settings',
        'render_rss_settings_panel'
    );
    
    add_submenu_page(
        '4044-control-panel',
        __('Statistics', '4044-eu-theme'),
        __('Statistics', '4044-eu-theme'),
        'manage_options',
        '4044-statistics',
        'render_statistics_panel'
    );
});

/**
 * Main Control Panel
 */
function render_4044_control_panel() {
    ?>
    <div class="wrap">
        <h1><?php _e('4044.eu Control Panel', '4044-eu-theme'); ?></h1>
        <div class="admin-dashboard">
            <div class="dashboard-grid">
                <div class="dashboard-card">
                    <h2><?php _e('System Status', '4044-eu-theme'); ?></h2>
                    <p><strong><?php _e('API Connections:', '4044-eu-theme'); ?></strong></p>
                    <ul>
                        <li><?php 
                            $football_api = get_theme_mod('football_data_api_key');
                            echo ($football_api ? '✅' : '❌') . ' Football-Data.org';
                        ?></li>
                        <li><?php 
                            $openai_api = get_theme_mod('openai_api_key');
                            echo ($openai_api ? '✅' : '❌') . ' OpenAI';
                        ?></li>
                    </ul>
                </div>
                
                <div class="dashboard-card">
                    <h2><?php _e('Quick Stats', '4044-eu-theme'); ?></h2>
                    <?php
                    $posts_count = wp_count_posts('post');
                    $live_updates = wp_count_posts('live_update');
                    $comments_count = wp_count_comments();
                    ?>
                    <p><?php printf(__('Total Posts: %d', '4044-eu-theme'), $posts_count->publish); ?></p>
                    <p><?php printf(__('Live Updates: %d', '4044-eu-theme'), $live_updates->publish); ?></p>
                    <p><?php printf(__('Comments: %d', '4044-eu-theme'), $comments_count->total_comments); ?></p>
                </div>
                
                <div class="dashboard-card">
                    <h2><?php _e('RSS Feeds', '4044-eu-theme'); ?></h2>
                    <ul>
                        <li><a href="<?php echo home_url('/feed/'); ?>" target="_blank"><?php _e('Main Feed', '4044-eu-theme'); ?></a></li>
                        <li><a href="<?php echo home_url('/feed/live-updates/'); ?>" target="_blank"><?php _e('Live Updates', '4044-eu-theme'); ?></a></li>
                        <li><a href="<?php echo home_url('/feed/sports/'); ?>" target="_blank"><?php _e('Sports Feed', '4044-eu-theme'); ?></a></li>
                        <li><a href="<?php echo home_url('/feed/google-news/'); ?>" target="_blank"><?php _e('Google News', '4044-eu-theme'); ?></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <style>
        .admin-dashboard { margin: 20px 0; }
        .dashboard-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
        .dashboard-card { background: white; border: 1px solid #ddd; padding: 20px; border-radius: 5px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .dashboard-card h2 { margin-top: 0; color: #0066cc; }
        .dashboard-card ul { list-style: none; padding: 0; }
        .dashboard-card li { padding: 5px 0; }
    </style>
    <?php
}

/**
 * Live Updates Panel
 */
function render_live_updates_panel() {
    ?>
    <div class="wrap">
        <h1><?php _e('Live Updates Manager', '4044-eu-theme'); ?></h1>
        <p><?php _e('Manage and publish live news updates.', '4044-eu-theme'); ?></p>
        <a href="<?php echo admin_url('post-new.php?post_type=live_update'); ?>" class="button button-primary"><?php _e('Create New Update', '4044-eu-theme'); ?></a>
    </div>
    <?php
}

/**
 * Sports Panel
 */
function render_sports_panel() {
    ?>
    <div class="wrap">
        <h1><?php _e('Sports Integration', '4044-eu-theme'); ?></h1>
        <p><?php _e('Configure football-data.org API and manage sports content.', '4044-eu-theme'); ?></p>
        <form method="post" action="options.php">
            <?php settings_fields('api_settings'); ?>
            <table class="form-table">
                <tr>
                    <th><label for="football_data_api_key"><?php _e('Football-Data.org API Key', '4044-eu-theme'); ?></label></th>
                    <td>
                        <input type="password" id="football_data_api_key" name="football_data_api_key" value="<?php echo esc_attr(get_theme_mod('football_data_api_key')); ?>" class="regular-text" />
                        <p class="description"><?php _e('Get your API key from football-data.org', '4044-eu-theme'); ?></p>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

/**
 * AI Tools Panel
 */
function render_ai_tools_panel() {
    ?>
    <div class="wrap">
        <h1><?php _e('AI Content Tools', '4044-eu-theme'); ?></h1>
        <p><?php _e('Use AI to generate article images and content.', '4044-eu-theme'); ?></p>
        
        <div style="background: white; padding: 20px; border-radius: 5px; margin: 20px 0;">
            <h2><?php _e('Generate Article Image', '4044-eu-theme'); ?></h2>
            <form id="ai-image-form">
                <p>
                    <label for="image_prompt"><?php _e('Describe the image you want:', '4044-eu-theme'); ?></label><br />
                    <textarea id="image_prompt" rows="3" cols="50" required></textarea>
                </p>
                <p>
                    <button type="submit" class="button button-primary"><?php _e('Generate Image', '4044-eu-theme'); ?></button>
                </p>
                <div id="image-result"></div>
            </form>
        </div>
        
        <div style="background: white; padding: 20px; border-radius: 5px;">
            <h2><?php _e('Expand Article Content', '4044-eu-theme'); ?></h2>
            <form id="ai-content-form">
                <p>
                    <label for="content_prompt"><?php _e('Enter your article text:', '4044-eu-theme'); ?></label><br />
                    <textarea id="content_prompt" rows="5" cols="50" required></textarea>
                </p>
                <p>
                    <button type="submit" class="button button-primary"><?php _e('Expand Content', '4044-eu-theme'); ?></button>
                </p>
                <div id="content-result"></div>
            </form>
        </div>
    </div>
    <?php
}

/**
 * RSS Settings Panel
 */
function render_rss_settings_panel() {
    ?>
    <div class="wrap">
        <h1><?php _e('RSS Feed Settings', '4044-eu-theme'); ?></h1>
        <p><?php _e('Manage automatic RSS feeds and Google News synchronization.', '4044-eu-theme'); ?></p>
        
        <div style="background: white; padding: 20px; border-radius: 5px;">
            <h2><?php _e('Feed URLs', '4044-eu-theme'); ?></h2>
            <ul>
                <li><strong><?php _e('Main Feed:', '4044-eu-theme'); ?></strong> <a href="<?php echo home_url('/feed/'); ?>" target="_blank"><?php echo home_url('/feed/'); ?></a></li>
                <li><strong><?php _e('Live Updates Feed:', '4044-eu-theme'); ?></strong> <a href="<?php echo home_url('/feed/live-updates/'); ?>" target="_blank"><?php echo home_url('/feed/live-updates/'); ?></a></li>
                <li><strong><?php _e('Sports Feed:', '4044-eu-theme'); ?></strong> <a href="<?php echo home_url('/feed/sports/'); ?>" target="_blank"><?php echo home_url('/feed/sports/'); ?></a></li>
                <li><strong><?php _e('Google News Feed:', '4044-eu-theme'); ?></strong> <a href="<?php echo home_url('/feed/google-news/'); ?>" target="_blank"><?php echo home_url('/feed/google-news/'); ?></a></li>
            </ul>
        </div>
        
        <div style="background: white; padding: 20px; border-radius: 5px; margin-top: 20px;">
            <h2><?php _e('Auto-sync Settings', '4044-eu-theme'); ?></h2>
            <p><?php _e('Google News sync runs hourly automatically. Last sync: Not available', '4044-eu-theme'); ?></p>
            <button type="button" class="button button-secondary" id="sync-now"><?php _e('Sync Now', '4044-eu-theme'); ?></button>
        </div>
    </div>
    <?php
}

/**
 * Statistics Panel
 */
function render_statistics_panel() {
    ?>
    <div class="wrap">
        <h1><?php _e('Site Statistics', '4044-eu-theme'); ?></h1>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin: 20px 0;">
            <?php
            $stats = array(
                __('Total Posts', '4044-eu-theme') => wp_count_posts('post')->publish,
                __('Live Updates', '4044-eu-theme') => wp_count_posts('live_update')->publish,
                __('Sports Events', '4044-eu-theme') => wp_count_posts('sports_event')->publish,
                __('Total Comments', '4044-eu-theme') => wp_count_comments()->total_comments,
                __('Total Pages', '4044-eu-theme') => wp_count_posts('page')->publish,
                __('Total Categories', '4044-eu-theme') => count(get_categories()),
            );
            
            foreach ($stats as $label => $value) {
                ?>
                <div style="background: linear-gradient(135deg, #0066cc, #004999); color: white; padding: 20px; border-radius: 5px; text-align: center;">
                    <p style="margin: 0; font-size: 2em; font-weight: bold;"><?php echo intval($value); ?></p>
                    <p style="margin: 5px 0 0 0;"><?php echo esc_html($label); ?></p>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    <?php
}