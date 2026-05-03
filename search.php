<?php
/**
 * Search Results Template
 */
get_header();
?>

<div class="container">
    <header class="search-header">
        <h1><?php printf(__('Search Results for: %s', '4044-eu-theme'), '<span>' . get_search_query() . '</span>'); ?></h1>
        <p><?php printf(__('Found %d results', '4044-eu-theme'), $wp_query->found_posts); ?></p>
    </header>
    
    <div class="articles-grid">
        <?php
        if (have_posts()) {
            while (have_posts()) {
                the_post();
                ?>
                <article class="article-card">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="article-image">
                            <?php the_post_thumbnail('article-featured'); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="article-content">
                        <?php
                        $categories = get_the_category();
                        if (!empty($categories)) {
                            foreach ($categories as $cat) {
                                echo '<span class="article-category">' . esc_html($cat->name) . '</span>';
                            }
                        }
                        ?>
                        <h3 class="article-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>
                        <p class="article-excerpt"><?php echo wp_trim_words(get_the_content(), 20); ?></p>
                        <div class="article-meta">
                            <span class="article-date">
                                <i class="fas fa-clock"></i>
                                <?php echo human_time_diff(get_the_time('U'), current_time('U')); ?> <?php _e('ago', '4044-eu-theme'); ?>
                            </span>
                            <a href="<?php the_permalink(); ?>" class="read-more"><?php _e('Read More', '4044-eu-theme'); ?></a>
                        </div>
                    </div>
                </article>
                <?php
            }
            
            echo '<div class="pagination">';
            echo paginate_links();
            echo '</div>';
        } else {
            echo '<p>' . __('No results found for your search.', '4044-eu-theme') . '</p>';
        }
        ?>
    </div>
</div>

<?php
get_footer();