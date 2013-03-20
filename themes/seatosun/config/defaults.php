<?php
// Define default settings
$defaults = array(
    // Custom Theme Settings Page
    'settings_prefix' => 'custom_theme_settings_',
    
    // CSS Files
    'compress_css' => false,
    'always_recompile_css' => false,
    'css_files' => 'style.css',
    
    // JS Files
    'compress_js' => false,
    'always_recompile_js' => false,
    'js_files' => 'main.js',
    
    // Includes folders
    'css_folder' => 'css/',
    'js_folder' => 'js/',
    'image_folder' => 'images/',
    'include_folder' => 'include/',
    
    // TypeKit Settings
    'typekit_id' => '',
    
    // Add custom fields to search results
    'custom_fields_to_search' => array(
        // Overwrite this in config.php to add custom fields to search results
        // e.g. 'custom_fields_to_search' => array('field_1', 'field_2')
    ),
);

return $defaults;
?>