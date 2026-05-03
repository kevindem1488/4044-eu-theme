<?php
/**
 * Live Updates RSS Template
 */
header('Content-Type: ' . feed_content_type('rss-http') . '; charset=' . get_option('blog_charset'), true);
echo '<?xml version="1.0" encoding="' . get_option('blog_charset') . '"?>' . PHP_EOL;
?>
<rss version="2.0">
<channel>
    <title><?php bloginfo_rss('name'); ?> - <?php _e('Live Updates', '4044-eu-theme'); ?></title>
    <link><?php bloginfo_rss('url'); ?></link>
    <description><?php _e('Breaking news and live updates from Europe', '4044-eu-theme'); ?></description>
    <language><?php echo get_bloginfo('language'); ?></language>
    <lastBuildDate><?php echo date('D, d M Y H:i:s +0000'); ?></lastBuildDate>
    
    <?php
    $args = array(
        'post_type' => 'live_update',
        'posts_per_page' => 50,
        'orderby' => 'date',
        'order' => 'DESC',
    );
    
    $updates = get_posts($args);
    
    foreach ($updates as $update) {
        ?>
        <item>
            <title><?php echo esc_html($update->post_title); ?></title>
            <link><?php echo esc_url(get_permalink($update)); ?></link>
            <guid isPermaLink="true"><?php echo esc_url(get_permalink($update)); ?></guid>
            <pubDate><?php echo mysql2date('D, d M Y H:i:s +0000', $update->post_date_gmt, false); ?></pubDate>
            <description><![CDATA[<?php echo wp_trim_words($update->post_content, 50); ?>]]></description>
            <content:encoded><![CDATA[<?php echo $update->post_content; ?>]]></content:encoded>
        </item>
        <?php
    }
    ?>
</channel>
</rss>