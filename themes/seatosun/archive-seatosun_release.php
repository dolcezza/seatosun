<?php get_header(); ?>
    
    <?php get_template_part('masthead', 'release-archive'); ?>
    
    <?php get_sidebar('left'); ?>
    
    <?php get_template_part('loop', 'release-archive'); ?>
    
    <?php get_sidebar('right'); ?>
    
<?php get_footer(); ?>