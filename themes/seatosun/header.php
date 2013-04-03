<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package Skeleton WordPress Theme Framework
 * @subpackage skeleton
 * @author Simple Themes - www.simplethemes.com
 */
?>
<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" <?php language_attributes();?>> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" <?php language_attributes();?>> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" <?php language_attributes();?>> <![endif]-->
<!--[if IE 9 ]><html class="ie ie9" <?php language_attributes();?>> <![endif]-->
<!--[if (gte IE 10)|!(IE)]><!--><html <?php language_attributes();?>> <!--<![endif]-->
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    
    <title><?php
        // Detect Yoast SEO Plugin
        if (defined('WPSEO_VERSION')) {
            wp_title('');
        } else {
        /*
         * Print the <title> tag based on what is being viewed.
         */
        global $page, $paged;
    
        wp_title( '|', true, 'right' );
    
        // Add the blog name.
        bloginfo( 'name' );
    
        // Add the blog description for the home/front page.
        $site_description = get_bloginfo( 'description', 'display' );
        if ( $site_description && ( is_home() || is_front_page() ) )
            echo " | $site_description";
    
        // Add a page number if necessary:
        if ( $paged >= 2 || $page >= 2 )
            echo ' | ' . sprintf( __( 'Page %s', 'skeleton' ), max( $paged, $page ) );
        }
        ?>
    </title>
    
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    
    
    <!-- Mobile Specific Metas
    ================================================== -->
    
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" /> 
    
    <!-- Favicons
    ================================================== -->
    
    <link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri();?>/images/favicon.ico">
    
    <link rel="apple-touch-icon" href="<?php echo get_stylesheet_directory_uri();?>/images/apple-touch-icon.png">
    
    <link rel="apple-touch-icon" sizes="72x72" href="<?php echo get_stylesheet_directory_uri();?>/images/apple-touch-icon-72x72.png" />
    
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo get_stylesheet_directory_uri();?>/images/apple-touch-icon-114x114.png" />
    
    <link rel="pingback" href="<?php echo get_option('siteurl') .'/xmlrpc.php';?>" />
    <link rel="stylesheet" id="custom" href="<?php echo home_url() .'/?get_styles=css';?>" type="text/css" media="all" />
    
    <?php
        /* 
         * enqueue threaded comments support.
         */
        if ( is_singular() && get_option( 'thread_comments' ) )
            wp_enqueue_script( 'comment-reply' );
        // Load head elements
        wp_head();
    ?> 
</head>
<body <?php body_class(); ?>>
    <div id="wrap" class="container">
        <header id="header" class="sixteen columns">
            
              <?php st_header(); ?>
            
            <div id="navigation" class="ten columns omega" style="float: left;">
                <?php
                wp_nav_menu(array(
                    'container_class' => 'menu-header',
                    'theme_location' => 'primary'
                ));
                ?>
            </nav>
            <div id="search-bar-container">
                <input type="search" class="search-input" placeholder="Search" />
            </div>
        </header>