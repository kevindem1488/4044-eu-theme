<?php
/**
 * Page Template
 */
get_header();
?>

<div class="container">
    <article class="single-page">
        <?php
        while (have_posts()) {
            the_post();
            ?>
            <header class="page-header">
                <h1 class="page-title"><?php the_title(); ?></h1>
            </header>
            
            <?php if (has_post_thumbnail()) : ?>
                <div class="page-thumbnail">
                    <?php the_post_thumbnail('article-large'); ?>
                </div>
            <?php endif; ?>
            
            <div class="page-content">
                <?php the_content(); ?>
            </div>
            <?php
        }
        ?>
    </article>
</div>

<?php
get_footer();