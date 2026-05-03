<?php
/**
 * Create placeholder images
 * Run this to generate placeholder images
 */

function create_placeholder_images() {
    $uploads_dir = wp_upload_dir();
    $theme_dir = get_template_directory() . '/assets/images';
    
    if (!is_dir($theme_dir)) {
        mkdir($theme_dir, 0755, true);
    }
    
    // Create placeholder using PHP GD
    create_placeholder($theme_dir . '/placeholder.jpg', 400, 300, '#0066cc');
    create_placeholder($theme_dir . '/placeholder-large.jpg', 800, 500, '#004999');
    create_placeholder($theme_dir . '/placeholder-small.jpg', 150, 150, '#000000');
}

function create_placeholder($path, $width, $height, $color) {
    if (file_exists($path)) return;
    
    if (!extension_loaded('gd')) {
        // Fallback: create minimal PNG
        file_put_contents($path, base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg=='));
        return;
    }
    
    $image = imagecreatetruecolor($width, $height);
    $hex = ltrim($color, '#');
    $rgb = sscanf($hex, '%02x%02x%02x');
    $color_resource = imagecolorallocate($image, $rgb[0], $rgb[1], $rgb[2]);
    
    imagefill($image, 0, 0, $color_resource);
    
    $text_color = imagecolorallocate($image, 255, 255, 255);
    $text = '4044.eu';
    $font_size = 3;
    $text_x = ($width - (strlen($text) * imagefontwidth($font_size))) / 2;
    $text_y = ($height - imagefontheight($font_size)) / 2;
    
    imagestring($image, $font_size, $text_x, $text_y, $text, $text_color);
    
    imagejpeg($image, $path, 85);
    imagedestroy($image);
}

// Run on theme activation
add_action('after_switch_theme', 'create_placeholder_images');