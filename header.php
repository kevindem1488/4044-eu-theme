<?php
/**
 * Header Template
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php wp_title('|', true, 'right'); bloginfo('name'); ?></title>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    
    <header>
        <div class="header-container">
            <div class="logo-section">
                <h1 class="logo">
                    <a href="<?php echo esc_url(home_url('/')); ?>">
                        4044.EU
                        <span class="logo-subtitle"><?php bloginfo('description'); ?></span>
                    </a>
                </h1>
            </div>
            
            <nav class="main-navigation" id="main-nav">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'fallback_cb' => 'wp_page_menu',
                    'depth' => 2,
                ));
                ?>
            </nav>
            
            <div class="nav-toggle" id="nav-toggle">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </header>
    
    <main id="main-content">