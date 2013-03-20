<?php
// Define settings here
$config = array(
    // CSS Files
    'compress_css' => true,
    'compile_css' => true, // true (always), false (never), or 'auto'
    'css_files' =>  array(
                        'style.less',
                        '/admin/css/admin.less',
                        '/admin/css/editor.less',
                        '/js/plugins/jquery.colorbox/colorbox-dark.css 
                        | /js/plugins/anythingslider/anythingslider.css => /js/plugins.css'
                    ),
    
    // JS Files
    'compress_js' => true,
    'compile_js' => true, // true (always), false (never), or 'auto'
    'js_files' =>   array(
                        'libraries/modernizr.dev.js',
                        'plugins/jquery.placholder.js
                         | plugins/jquery.colorbox.js
                         | plugins/anythingslider/jquery.anythingslider.min.js => plugins.js',
                        'main.js => main.min.js',
                    ),
    
    // Custom Header Image
    'header_image_width' => 940,
    'header_image_height' => 320,
    
    // TypeKit Settings
    'typekit_id' => '',
    
    // Add custom fields to search results
    // e.g. 'custom_fields_to_search' => array('field_1', 'field_2')
    'custom_fields_to_search' => array(
        
    ),
);

return $config;