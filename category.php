<?php
/**
 * Category Archive Template
 */
get_header();
?>

<div class="container">
    <header class="archive-header">
        <h1><?php single_cat_title(); ?></h1>
        <p><?php echo category_description(); ?></p>
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
                        <span class="article-category"><?php single_cat_title(); ?></span>
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
            echo '<p>' . __('No posts in this category.', '4044-eu-theme') . '</p>';
        }
        ?>
    </div>
</div>

<?php
get_footer();