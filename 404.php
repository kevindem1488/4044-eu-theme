<?php
/**
 * 404 Error Page
 */
get_header();
?>

<div class="container">
    <div class="error-404" style="text-align: center; padding: 60px 20px;">
        <h1 style="font-size: 5rem; margin-bottom: 20px;">404</h1>
        <h2><?php _e('Page Not Found', '4044-eu-theme'); ?></h2>
        <p><?php _e('Sorry, the page you are looking for does not exist.', '4044-eu-theme'); ?></p>
        
        <form method="get" action="<?php echo home_url('/'); ?>" style="margin: 30px 0;">
            <input type="search" name="s" placeholder="<?php _e('Search...', '4044-eu-theme'); ?>" style="padding: 10px; width: 300px; max-width: 100%;" />
            <button type="submit" class="btn"><?php _e('Search', '4044-eu-theme'); ?></button>
        </form>
        
        <p>
            <a href="<?php echo home_url('/'); ?>" class="btn"><?php _e('Back to Home', '4044-eu-theme'); ?></a>
        </p>
    </div>
</div>

<?php
get_footer();