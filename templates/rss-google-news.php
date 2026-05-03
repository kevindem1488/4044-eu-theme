<?php
/**
 * Google News RSS Template
 */
header('Content-Type: ' . feed_content_type('rss-http') . '; charset=' . get_option('blog_charset'), true);
echo '<?xml version="1.0" encoding="' . get_option('blog_charset') . '"?>' . PHP_EOL;
?>
<rss version="2.0" xmlns:google="http://base.google.com/cns/1.0">
<channel>
    <title><?php bloginfo_rss('name'); ?> - <?php _e('News', '4044-eu-theme'); ?></title>
    <link><?php bloginfo_rss('url'); ?></link>
    <description><?php bloginfo_rss('description'); ?></description>
    <language><?php echo get_bloginfo('language'); ?></language>
    <lastBuildDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_lastpostmodified('GMT'), false); ?></lastBuildDate>
    <image>
        <url><?php bloginfo_rss('url'); ?>/wp-content/themes/4044-eu-theme/assets/images/logo.png</url>
        <title><?php bloginfo_rss('name'); ?></title>
        <link><?php bloginfo_rss('url'); ?></link>
    </image>
    
    <?php
    $args = array(
        'posts_per_page' => 50,
        'orderby' => 'date',
        'order' => 'DESC',
    );
    
    $posts = get_posts($args);
    
    foreach ($posts as $post) {
        setup_postdata($post);
        ?>
        <item>
            <title><?php the_title_rss(); ?></title>
            <link><?php the_permalink_rss(); ?></link>
            <guid isPermaLink="true"><?php the_permalink_rss(); ?></guid>
            <pubDate><?php echo mysql2date('D, d M Y H:i:s +0000', $post->post_date_gmt, false); ?></pubDate>
            <description><![CDATA[<?php the_excerpt_rss(); ?>]]></description>
            <content:encoded><![CDATA[<?php the_content_rss(); ?>]]></content:encoded>
            <?php
            $image = get_the_post_thumbnail_url($post);
            if ($image) {
                echo '<google:image_link>' . esc_url($image) . '</google:image_link>';
            }
            ?>
            <?php
            $categories = get_the_category($post->ID);
            foreach ($categories as $cat) {
                echo '<category>' . esc_html($cat->name) . '</category>';
            }
            ?>
            <author><?php the_author_rss(); ?></author>
        </item>
        <?php
    }
    
    wp_reset_postdata();
    ?>
</channel>
</rss>