<?php
/**
 * Sports Results RSS Template
 */
header('Content-Type: ' . feed_content_type('rss-http') . '; charset=' . get_option('blog_charset'), true);
echo '<?xml version="1.0" encoding="' . get_option('blog_charset') . '"?>' . PHP_EOL;
?>
<rss version="2.0">
<channel>
    <title><?php bloginfo_rss('name'); ?> - <?php _e('Sports Results', '4044-eu-theme'); ?></title>
    <link><?php bloginfo_rss('url'); ?></link>
    <description><?php _e('Live football results and sports updates', '4044-eu-theme'); ?></description>
    <language><?php echo get_bloginfo('language'); ?></language>
    <lastBuildDate><?php echo date('D, d M Y H:i:s +0000'); ?></lastBuildDate>
    
    <?php
    $args = array(
        'post_type' => 'sports_event',
        'posts_per_page' => 50,
        'orderby' => 'date',
        'order' => 'DESC',
    );
    
    $events = get_posts($args);
    
    foreach ($events as $event) {
        $meta = get_post_meta($event->ID);
        ?>
        <item>
            <title><?php echo esc_html($event->post_title); ?></title>
            <link><?php echo esc_url(get_permalink($event)); ?></link>
            <guid isPermaLink="true"><?php echo esc_url(get_permalink($event)); ?></guid>
            <pubDate><?php echo mysql2date('D, d M Y H:i:s +0000', $event->post_date_gmt, false); ?></pubDate>
            <description><![CDATA[<?php echo wp_trim_words($event->post_content, 50); ?>]]></description>
        </item>
        <?php
    }
    ?>
</channel>
</rss>