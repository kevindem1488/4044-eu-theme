<?php
/**
 * Single Post Template
 */
get_header();
?>

<div class="container">
    <article class="single-post">
        <?php
        while (have_posts()) {
            the_post();
            ?>
            <header class="post-header">
                <h1 class="post-title"><?php the_title(); ?></h1>
                
                <div class="post-meta">
                    <span class="post-date">
                        <i class="fas fa-calendar"></i>
                        <?php echo get_the_date('d.m.Y H:i'); ?>
                    </span>
                    <span class="post-author">
                        <i class="fas fa-user"></i>
                        <?php the_author(); ?>
                    </span>
                    <span class="post-category">
                        <i class="fas fa-folder"></i>
                        <?php the_category(', '); ?>
                    </span>
                    <span class="post-comments">
                        <i class="fas fa-comments"></i>
                        <?php comments_number(); ?>
                    </span>
                </div>
            </header>
            
            <?php if (has_post_thumbnail()) : ?>
                <div class="post-thumbnail">
                    <?php the_post_thumbnail('article-large'); ?>
                </div>
            <?php endif; ?>
            
            <div class="post-content">
                <?php the_content(); ?>
            </div>
            
            <footer class="post-footer">
                <div class="post-tags">
                    <?php the_tags('<span class="tag">', '</span><span class="tag">', '</span>'); ?>
                </div>
                
                <div class="post-nav">
                    <div class="prev-post">
                        <?php previous_post_link('%link', '← ' . __('Previous', '4044-eu-theme')); ?>
                    </div>
                    <div class="next-post">
                        <?php next_post_link('%link', __('Next', '4044-eu-theme') . ' →'); ?>
                    </div>
                </div>
            </footer>
            
            <?php
            // Related Posts
            $categories = get_the_category();
            $category_ids = wp_list_pluck($categories, 'term_id');
            
            $args = array(
                'post_type' => 'post',
                'category__in' => $category_ids,
                'posts_per_page' => 3,
                'post__not_in' => array(get_the_ID()),
                'orderby' => 'date',
            );
            
            $related = get_posts($args);
            
            if (!empty($related)) :
                ?>
                <section class="related-posts">
                    <h2><?php _e('Related Articles', '4044-eu-theme'); ?></h2>
                    <div class="articles-grid">
                        <?php
                        foreach ($related as $post) {
                            setup_postdata($post);
                            ?>
                            <article class="article-card">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="article-image">
                                        <?php the_post_thumbnail('article-featured'); ?>
                                    </div>
                                <?php endif; ?>
                                <div class="article-content">
                                    <h3 class="article-title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h3>
                                    <p class="article-excerpt"><?php echo wp_trim_words(get_the_content(), 15); ?></p>
                                    <div class="article-meta">
                                        <a href="<?php the_permalink(); ?>" class="read-more"><?php _e('Read More', '4044-eu-theme'); ?></a>
                                    </div>
                                </div>
                            </article>
                            <?php
                        }
                        wp_reset_postdata();
                        ?>
                    </div>
                </section>
                <?php
            endif;
            
            // Comments
            if (comments_open() || get_comments_number()) {
                ?>
                <section class="comments-section">
                    <?php comments_template(); ?>
                </section>
                <?php
            }
            ?>
            <?php
        }
        ?>
    </article>
</div>

<?php
get_footer();