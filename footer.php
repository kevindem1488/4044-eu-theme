<?php
/**
 * Footer Template
 */
?>
    </main>
    
    <footer>
        <div class="footer-content">
            <section class="footer-section">
                <h3><?php _e('About 4044.EU', '4044-eu-theme'); ?></h3>
                <p><?php bloginfo('description'); ?></p>
                <div class="social-links">
                    <?php
                    $social_links = get_theme_mod('social_links', array());
                    if (!empty($social_links)) {
                        foreach ($social_links as $network => $url) {
                            printf('<a href="%s" target="_blank" rel="noopener" title="%s"><i class="fab fa-%s"></i></a>', 
                                esc_url($url), 
                                esc_attr(ucfirst($network)), 
                                esc_attr($network)
                            );
                        }
                    }
                    ?>
                </div>
            </section>
            
            <section class="footer-section">
                <h3><?php _e('Categories', '4044-eu-theme'); ?></h3>
                <ul>
                    <?php
                    $categories = get_categories(array('hide_empty' => 1));
                    foreach ($categories as $category) {
                        printf('<li><a href="%s">%s</a></li>', 
                            esc_url(get_category_link($category->term_id)),
                            esc_html($category->name)
                        );
                    }
                    ?>
                </ul>
            </section>
            
            <section class="footer-section">
                <h3><?php _e('Latest News', '4044-eu-theme'); ?></h3>
                <ul>
                    <?php
                    $latest_posts = get_posts(array('numberposts' => 5));
                    foreach ($latest_posts as $post) {
                        printf('<li><a href="%s">%s</a></li>', 
                            esc_url(get_permalink($post)),
                            esc_html($post->post_title)
                        );
                    }
                    ?>
                </ul>
            </section>
            
            <section class="footer-section">
                <h3><?php _e('Contact & Info', '4044-eu-theme'); ?></h3>
                <ul>
                    <li><a href="<?php echo esc_url(get_page_link(get_page_by_path('privacy-policy')->ID ?? 0)); ?>"><?php _e('Privacy Policy', '4044-eu-theme'); ?></a></li>
                    <li><a href="<?php echo esc_url(get_page_link(get_page_by_path('terms')->ID ?? 0)); ?>"><?php _e('Terms of Service', '4044-eu-theme'); ?></a></li>
                    <li><a href="<?php echo esc_url(get_page_link(get_page_by_path('contact')->ID ?? 0)); ?>"><?php _e('Contact Us', '4044-eu-theme'); ?></a></li>
                    <li><a href="<?php echo esc_url(home_url('/rss')); ?>"><?php _e('RSS Feed', '4044-eu-theme'); ?></a></li>
                </ul>
            </section>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. <?php _e('All rights reserved.', '4044-eu-theme'); ?></p>
            <p><?php printf(__('Powered by %s', '4044-eu-theme'), '<a href="https://wordpress.org">WordPress</a>'); ?></p>
        </div>
    </footer>
    
    <?php wp_footer(); ?>
</body>
</html>